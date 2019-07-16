<?php
	
	/**
	 * Operations CRUD pour class Absences
	 *
	 * @copyright   Ity-Consulting (http://www.ity-consulting.com)
	 * @author      Darin Mateev <dmateev@ity-consulting.com>
	 * @version     1.1
	 *
	 * Historique
	 * ----------
	 * 2019.05.26 - DAM : Ajout de commentaires\n
	 *
	 */
	
	namespace App\Http\Controllers;
	
	use App\Helpers\LogActivity;
	use App\Models\Absence;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	
	class AbsenceController extends Controller
	{
		const subject = 'activityLog.Absence';
		
		/**
		 * ActivityController constructor.
		 */
		public function __construct()
		{
			$this->middleware('auth');
			
			$this->middleware(function ($request, $next) {
				if (session()->has(['current_date', 'current_month', 'current_year'])) {
					$this->current_date = session('current_date');
					$this->current_month = session('current_month');
					$this->current_year = session('current_year');
				} else {
					$this->current_date = Carbon::now();
					$this->current_month = Carbon::now()->month;
					$this->current_year = Carbon::now()->year;
				}
				
				return $next($request);
			});
		}
		
		/**
		 * @param $value string/int $value the value to be handled
		 * @return int value
		 */
		public function getInt($value)
		{
			if (is_int($value)) {
				return $value;
			}
			return intval($value);
		}
		
		/**
		 * Absences index page
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function index()
		{
			//-----------------------------------------
			//SET CURRENT DATE OR GET GATE FROM FORM
			$current_date = $this->current_date;
			$current_month = $this->current_month;
			$current_year = $this->current_year;
			
			//-----------------------------------------
			//SQL
			$current_user = auth()->user()->id;
			$direction_id = auth()->user()->department_id;
			$service_id = auth()->user()->service_id;
			$current_date_start_month = Carbon::parse($current_date)->startOfMonth();
			
			//-----------------------------------------
			//USER FULL NAME
			$fullName = "CONCAT(u_absences.last_name, \" \", u_absences.first_name) AS full_name";
			//=========================================================================
			
			
			//=========================================================================
			//START ABSENCES FOR CURRENT USER AND CURRENT MONTH
			
			//-----------------------------------------
			//TOTAL ABSENCES FOR CURRENT USER FOR CURRENT MONTH
			$user_total_absence = DB::table('absences')
				->select(DB::raw('IFNULL(sum(absences.days), 0) as user_total_absence'))
				->where('absences.user_id', '=', auth()->user()->id)
				->whereMonth('absences.date', '=', $current_month)
				->whereYear('absences.date', '=', $current_year)
				->pluck('user_total_absence');
			
			$user_total_absence_month = (float)$user_total_absence[0];
			
			//-----------------------------------------
			//ABSENCES OBJECT FOR DISPLAY AND JS
			$userAbsences = DB::table('absences')
				->leftJoin('absence_types', 'absence_types.id', '=', 'absences.absence_type_id')
				->leftJoin('users as u_absences', 'absences.user_id', '=', 'u_absences.id')
				->select(DB::raw("$fullName,
				absences.date as absence_date,
				absences.id as absence_id,
				absences.days as absence_days,
				absences.description as absence_description,
				absence_types.name as absence_types_name,
				absence_types.id as absence_type_id"))
				->whereDate('absences.date', '>=', Carbon::parse($current_date)->startOfMonth())
				->whereDate('absences.date', '<=', Carbon::parse($current_date)->endOfMonth())
				->where('u_absences.id', '=', $current_user)
				->orderBy('absences.date', 'asc')
				->get();
			
			//-----------------------------------------
			//OPEN_DAYS
			$current_open_days = DB::table('open_days')
				->select(DB::raw('days'))
				->where('open_days.month', '=', $current_month)
				->where('open_days.year', '=', $current_year)
				->pluck('days');
			
			$open_days_month = (int)$current_open_days[0];
			
			//END ABSENCES FOR CURRENT USER AND CURRENT MONTH
			//=========================================================================
			
			
			//=========================================================================
			//START ABSENCES FOR ENTITY AND CURRENT MONTH
			
			//-----------------------------------------
			//TOTAL ABSENCES PER ENTITY
			$totalAbsUserByType = "IFNULL((select sum(absbyTYPE.days) from absences as absbyTYPE left join users as u3 on u3.id = absbyTYPE.user_id where month(absbyTYPE.date) = '$current_month' 
and year(absbyTYPE.date) = '$current_year' and absbyTYPE.absence_type_id = absence_types.id and absbyTYPE.user_id = u_absences.id), 0) as 'total_abs_user_by_type'";
			
			// COMMON VALUES
			$entity_total_absences = DB::table('absences')
				->leftJoin('users', 'users.id', '=', 'absences.user_id')
				->select(DB::raw('IFNULL(sum(absences.days), 0) as entity_total_absences_month'))
				->whereMonth('absences.date', '=', $current_month)
				->whereYear('absences.date', '=', $current_year);
			
			switch ('adidi') {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$entity_total_absences = $entity_total_absences
						->where('users.department_id', '=', $direction_id);
					break;
				case auth()->user()->role_id == config('constants.role_service_id'):
					$entity_total_absences = $entity_total_absences
						->where('users.service_id', '=', $service_id);
					break;
				default:
					$entity_total_absences = $entity_total_absences
						->where('users.id', '=', $current_user);
					break;
			}
			
			$entity_total_absences = $entity_total_absences
				->pluck('entity_total_absences_month');
			
			$entity_total_absences_month = (float)$entity_total_absences[0];
			
			//-----------------------------------------
			//ABSENCES OBJECT FOR DISPLAY AND JS
			$entityAbsences = DB::table('absences')
				->leftJoin('absence_types', 'absence_types.id', '=', 'absences.absence_type_id')
				->leftJoin('users as u_absences', 'absences.user_id', '=', 'u_absences.id')
				->leftJoin('departments', 'departments.id', '=', 'u_absences.department_id')
				->leftJoin('services', 'services.id', '=', 'u_absences.service_id')
				->whereDate('absences.date', '>=', Carbon::parse($current_date)->startOfMonth())
				->whereDate('absences.date', '<=', Carbon::parse($current_date)->endOfMonth());
			
			$entityQuery = "u_absences.id as user_id, $fullName, departments.name as department_name, services.name as service_name, absence_types.name as absence_types_name, $totalAbsUserByType";
			//-----------------------------------------
			//CASING ADMIN / DIRECTION / SERVICE
			switch ('absences') {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$entityQuery = $entityQuery . ", services.name as services_name";
					$entityAbsences = $entityAbsences
						->where('u_absences.department_id', '=', $direction_id);
					break;
				case auth()->user()->role_id == config('constants.role_service_id'):
					$entityAbsences = $entityAbsences
						->where('u_absences.service_id', '=', $service_id);
					break;
				default:
					$entity_total_absences = $entity_total_absences
						->where('u_absences.id', '=', $current_user);
					break;
			}
			
			$entityAbsences = $entityAbsences
				->select(DB::raw("$entityQuery"))
				->groupBy('u_absences.id')
				->groupBy('absence_types.id')
				->orderBy('u_absences.last_name', 'asc')
				->orderBy('u_absences.first_name', 'asc')
				->get();
			
			$open_days_entity = count($entityAbsences) * $open_days_month;
			
			//-----------------------------------------
			//ABSENCE TYPES
			$absence_types = DB::table('absence_types')
				->pluck('name', 'id');
			
			//START ABSENCES FOR ENTITY AND CURRENT MONTH
			//=========================================================================
			
			return view('absences.absence_index',
				compact(
					'current_date',
					'current_year',
					'open_days_month',
					
					'userAbsences',
					'user_total_absence_month',
					
					'entityAbsences',
					'absence_types',
					'open_days_entity',
					'entity_total_absences_month'));
		}
		
		/**
		 *   Create new absence
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function create(Request $request)
		{
			/*
			 * Retrieve config flag value
			 */
			try {
				/*
				 * Begin DB transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				
				$occurenceSelect = intval($request->occurenceSelect);
				for ($occurence = 1; $occurence <= $occurenceSelect; $occurence++) {
					
					$date_start = Carbon::parse($request->absence_date)->firstOfMonth()->addMonth($occurence - 1);
					$absence_date = Carbon::parse($request->absence_date)->addMonth($occurence - 1);
					
					if ($date_start->month != $absence_date->month)
						$absence_date = $date_start->endOfMonth();
					
					$absence = new Absence();
					$absence->date = $absence_date;
					$absence->days = $request->absence_days;
					$absence->description = $request->absence_description;
					$absence->absence_type_id = $request->absence_type_id;
					$absence->user_id = auth()->user()->id;
					$absence->save();
					
					
					/*
					 * DB commit if no errors
					 */
					LogActivity::dbCommit(__METHOD__, __LINE__);
					
					/*
					 * Add to database logActivity
					 */
					LogActivity::addToLog(trans(self::subject), __function__, $absence);
					
					flashy()->success(trans('flash_message.absence_create'));
				}
			} catch (\Exception $ex) {
				/*
				 * DB rollback if DBMS actions fails
				 */
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.absence_create_error'));
			}
			return redirect()->back();
		}
		
		/**
		 *
		 */
		public function store()
		{
			//
		}
		
		/**
		 *
		 */
		public function show()
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param  int $id
		 */
		public function edit($id)
		{
			//
		}
		
		/**
		 *   Update absence
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function update(Request $request)
		{
			$absence = Absence::find($request->absence_id);
			try {
				/*
				 * Begin of transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$absence->date = $request->absence_date;
				if ($absence->days != $request->absence_days)
					$absence->days = $request->absence_days;
				$absence->description = $request->absence_description;
				$absence->absence_type_id = $request->absence_type_id;
				/*
				 * Update if any changes are made
				 * else do nothing
				 */
				if ($absence->isDirty()) {
					$absence->update();
					/*
					 * DB commit if no errors
					 */
					LogActivity::dbCommit(__METHOD__, __LINE__);
					/*
					 * Add to DB LogActivity
					 */
					LogActivity::addToLog(trans(self::subject), __function__, $absence);
					/*
					 * Show flachy message
					 */
					flashy()->success(trans('flash_message.absence_update'));
				} else {
					LogActivity::dbRollback(__METHOD__, __LINE__);
					flashy()->info(trans('flash_message.not_dirty'));
				}
				
			} catch (\Exception $ex) {
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				/*
				 * Show flachy message
				 */
				flashy()->error(trans('flash_message.absence_update_error'));
			}
			return redirect()->back();
		}
		
		/**
		 *   Delete absence
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function destroy(Request $request)
		{
			$absence = Absence::find($request->absence_id);
			try {
				/*
				 * Begin of transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				$absence->delete();
				
				/*
				 * DB commit if no errors
				 */
				LogActivity::dbCommit(__METHOD__, __LINE__);
				/*
				 * Add to DB LogActivity
				 */
				LogActivity::addToLog(trans(self::subject), __function__, $absence);
				/*
				 * Show flachy message
				 */
				flashy()->success(trans('flash_message.absence_delete'));
			} catch (\Exception $ex) {
				
				/*
				 * Rollback if DB commit fails
				 */
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				/*
				 * Show flachy message
				 */
				flashy()->error(trans('flash_message.absence_delete_error'));
			}
			return redirect()->back();
		}
	}
