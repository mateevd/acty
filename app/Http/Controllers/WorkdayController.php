<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Phase;
use App\Models\Activity;
use App\Models\Task;
use App\Models\WorkDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkdayController extends Controller
{
	const subject = 'activityLog.Hours';
	
	/**
	 * ActivityController constructor.
	 */
	public function __construct()
	{
		$this->middleware('auth');

		$this->status_active = config('constants.status_active');
		$this->status_terminated = config('constants.status_terminated');
		$this->status_not_validated = config('constants.status_not_validated');
		$this->status_validated = config('constants.status_validated');
		$this->status_deleted = config('constants.status_deleted');
		
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
	 * Display a listing of the resource.
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		// SET CURRENT DATE OR GET GATE FROM FORM
		$current_date = $this->current_date;
		$current_month = $this->current_month;
		$current_year = $this->current_year;
		
		$direction_id = auth()->user()->department_id;
		$service_id = auth()->user()->service_id;
		
		// SQL REQUETES

		$current_user = auth()->user()->id;
		$current_user_full_name = auth()->user()->last_name . " " . auth()->user()->first_name;
		
		//OPEN_DAYS
		$current_open_days = DB::table('open_days')
			->select(DB::raw('days'))
			->where('open_days.month', '=', $current_month)
			->where('open_days.year', '=', $current_year)
			// ->whereRaw("open_days.month = '$current_month' and open_days.year = '$current_year'")
			->pluck('days');
		
		$open_days_month = (int)$current_open_days[0];
		
		//ABSENCES
		$user_total_absence = DB::table('absences')
			->select(DB::raw('IFNULL(sum(absences.days), 0) as user_total_absence_month'))
			->where('absences.user_id', '=', auth()->user()->id)
			->whereMonth('absences.date', '=', $current_month)
			->whereYear('absences.date', '=', $current_year)
			->pluck('user_total_absence_month');
		
		$user_total_absence_month = (float)$user_total_absence[0];
		
		//REALISE
		$user_total_realise = DB::table('work_days')
			->select(DB::raw('IFNULL(sum(work_days.days), 0) as user_total_realise_month'))
			->where('work_days.user_id', '=', auth()->user()->id)
			->whereMonth('work_days.date', '=', $current_month)
			->whereYear('work_days.date', '=', $current_year)
			->whereIn('work_days.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
			->pluck('user_total_realise_month');
		
		$user_total_realise_month = (float)$user_total_realise[0];
		
		//NOT COMMON VALUES (1 LINE PER WORK_DAY)
		//
		$userTimes = DB::table('users')
			->leftJoin('work_days', 'users.id', '=', 'work_days.user_id')
			->leftJoin('tasks', 'tasks.id', '=', 'work_days.task_id')
			->leftJoin('task_types', 'task_types.id', '=', 'tasks.task_type_id')
			->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
			->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
			->leftJoin('users as u2', 'u2.id', '=', 'activities.manager')
			->select(DB::raw("activities.id as activity_id,
							activities.code as activity_code,
							u2.trigramme as activity_manager,
							phases.id as phase_id,									
							phases.name as phase_name,
							tasks.id as task_id,
							tasks.start_p as task_start_p,
							tasks.name as task_name,
							task_types.name as task_type_name,									
							work_days.id as work_day_id,
							work_days.status as work_day_status,
							work_days.date as work_day_date,
							work_days.description as work_day_description,
							work_days.days as work_day_hours"))
			->where('users.id', '=', auth()->user()->id)
			->whereIn('work_days.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
			->whereDate('work_days.date', '>=', Carbon::parse($current_date)->startOfMonth())
			->whereDate('work_days.date', '<=', Carbon::parse($current_date)->endOfMonth())
			->orderBy('work_day_date', 'asc')
			->orderBy('activity_code', 'asc')
			->orderBy('phase_name', 'asc')
			->orderBy('task_name', 'asc')
			->get();

		$user_jours_max_month = $open_days_month - $user_total_absence_month;
		$user_jours_restants_month = $user_jours_max_month - $user_total_realise_month;

		//
		// TIMES FOR ENTITY
		//
		if (auth()->user()->role_id == config('constants.role_admin_id') ||
			auth()->user()->role_id == config('constants.role_directeur_id') ||
			auth()->user()->role_id == config('constants.role_service_id')) {
			
			// COMMON VALUES
			$entity_total_realise = DB::table('work_days')
				->leftJoin('users', 'users.id', '=', 'work_days.user_id')
				->select(DB::raw('IFNULL(sum(work_days.days), 0) as entity_total_realise_month'))
				->whereMonth('work_days.date', '=', $current_month)
				->whereYear('work_days.date', '=', $current_year);
			
			switch ('adidi') {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$entity_total_realise = $entity_total_realise
						->where('users.department_id', '=', $direction_id);
					break;
				case auth()->user()->role_id == config('constants.role_service_id'):
					$entity_total_realise = $entity_total_realise
						->where('users.service_id', '=', $service_id);
					break;
			}
			
			$entity_total_realise = $entity_total_realise
				->whereIn('work_days.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->pluck('entity_total_realise_month');
			
			$entity_total_realise_month = (float)$entity_total_realise[0];
			
			
			// COMMON VALUES
			$entity_total_absences = DB::table('absences')
				->leftJoin('users', 'users.id', '=', 'absences.user_id')
				->select(DB::raw('IFNULL(sum(absences.days), 0) as entity_total_absences_month'))
				->whereMonth('absences.date', '=', $current_month)
				->whereYear('absences.date', '=', $current_year);
			
			switch ('adidi') {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$entity_total_absences = $entity_total_absences
						->where('users.department_id', '=', auth()->user()->department_id);
					break;
				case auth()->user()->role_id == config('constants.role_service_id'):
					$entity_total_absences = $entity_total_absences
						->where('users.service_id', '=', auth()->user()->service_id);
					break;
			}
			
			$entity_total_absences = $entity_total_absences
				->pluck('entity_total_absences_month');
			
			$entity_total_absences_month = (float)$entity_total_absences[0];
			
			//NOT COMMON VALUES '' enlevés sur les $date
			$totalAbsUser = "IFNULL((select sum(abs.days) from absences as abs
									left join users as u3 on u3.id = abs.user_id
									where month(abs.date) = $current_month
									and year(abs.date) = $current_year
									and u3.id = users.id
									), 0) as 'total_abs_user'";
			
			$totalRealiseUser = "IFNULL((select sum(wd2.days) from work_days as wd2
									where month(wd2.date) = '$current_month'
									and year(wd2.date) = '$current_year'
									and wd2.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)
									and wd2.user_id = users.id
									), 0) as total_realise_user";
			
			$entityTimes = DB::table('users')
				->leftJoin('departments', 'departments.id', '=', 'users.department_id')
				->leftJoin('services', 'services.id', '=', 'users.service_id');
			
			switch ('times') {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$entityTimes = $entityTimes
						->where('users.department_id', '=', auth()->user()->department_id);
					break;
				case auth()->user()->role_id == config('constants.role_service_id'):
					$entityTimes = $entityTimes
						->where('users.service_id', '=', auth()->user()->service_id);
					break;
				default:
					$entityTimes = $entityTimes
						->where('users.id', '=', auth()->user()->id);
					break;
			}
			
			$fullName = "CONCAT(users.last_name, \" \", users.first_name) as full_name";
			
			$entityTimes = $entityTimes
				->select(DB::raw("users.id as user_id, $fullName, departments.name as department_name, services.name as service_name, $totalAbsUser, $totalRealiseUser"))
				->groupBy('users.id')
				->orderBy('users.last_name', 'asc')
				->orderBy('users.first_name', 'asc')
				->get();
			
			$open_days_entity = count($entityTimes) * $open_days_month;
			
			//
			// Entity times calculations
			//
			$entity_total_restants_month = (float)0;
			foreach ($entityTimes as $entityTime) {
				$entityTime->jour_max = $open_days_month - $entityTime->total_abs_user;
				$entityTime->jours_restants = $entityTime->jour_max - $entityTime->total_realise_user;
				$entity_total_restants_month += (float)$entityTime->jours_restants;
			}


			//
			//CRA VALIDATE PAGE CONSTRUCTION
			//
			if (session()->get('cra_validate') == true) {

				//LISTE DES UTILISATEURS DONT LE CRA EST À VALIDER
				$all_user_id = DB::table('users');
				switch ('users') {
					case auth()->user()->role_id == config('constants.role_admin_id'):
						$all_user_id = $all_user_id
							->where('users.department_id', '=', auth()->user()->department_id);
						break;
					case auth()->user()->role_id == config('constants.role_directeur_id'):
						$all_user_id = $all_user_id
							->where('users.department_id', '=', auth()->user()->department_id)
							->whereIn('users.role_id', [
									config('constants.role_directeur_id'),
									config('constants.role_service_id')
								]);
						break;
					case auth()->user()->role_id == config('constants.role_service_id'):
						$all_user_id = $all_user_id
							->where('users.service_id', '=', auth()->user()->service_id)
							->whereIn('users.role_id', [
									config('constants.role_projet_id'),
									config('constants.role_agent_id'),
									config('constants.role_prestataire_id')
								]);
						break;
					default:
						$all_user_id = $all_user_id
							->where('users.id', '=', auth()->user()->id);
						break;
				}
				$all_user_id = $all_user_id
				->orderBy('users.last_name')
				->orderBy('users.first_name')
				->orderBy('users.id')
				->pluck('users.id');

				// PARTIE ENTITY
				switch ('realise_mod') {
					case auth()->user()->role_id == config('constants.role_admin_id'):
						$realise_mod = "and us0.department_id = " . auth()->user()->department_id;
						break;
					case auth()->user()->role_id == config('constants.role_directeur_id'):
						$realise_mod = "and us0.department_id = " . auth()->user()->department_id;
						$realise_mod = $realise_mod . " and us0.role_id in (" . 
									config('constants.role_directeur_id') . "," .
									config('constants.role_service_id')
								. ")";
						break;
					case auth()->user()->role_id == config('constants.role_service_id'):
						$realise_mod = "and us0.service_id = " . auth()->user()->service_id;
						$realise_mod = $realise_mod . " and us0.role_id in (" . 
									config('constants.role_projet_id') . "," .
									config('constants.role_agent_id') . "," .
									config('constants.role_prestataire_id')
								. ")";
						break;
					default:
						$realise_mod = "and us0.id = " . auth()->user()->id;
						break;
				}

				$realiseEntity_active = "IFNULL((select sum(wd0.days) from work_days as wd0 
				left join users as us0 on us0.id = wd0.user_id
				where month(wd0.date) = '$current_month'
				and year(wd0.date) = '$current_year'
				and wd0.status in ($this->status_active)
				$realise_mod
				), 0) as realise_active";
				$realiseEntity_terminated = "IFNULL((select sum(wd1.days) from work_days as wd1 
				left join users as us0 on us0.id = wd1.user_id
				where month(wd1.date) = '$current_month'
				and year(wd1.date) = '$current_year'
				and wd1.status in ($this->status_terminated)
				$realise_mod
				), 0) as realise_terminated";
				$realiseEntity_not_validated = "IFNULL((select sum(wd2.days) from work_days as wd2
				left join users as us0 on us0.id = wd2.user_id
				where month(wd2.date) = '$current_month'
				and year(wd2.date) = '$current_year'
				and wd2.status in ($this->status_not_validated)
				$realise_mod
				), 0) as realise_not_validated";
				$realiseEntity_validated = "IFNULL((select sum(wd3.days) from work_days as wd3
				left join users as us0 on us0.id = wd3.user_id
				where month(wd3.date) = '$current_month'
				and year(wd3.date) = '$current_year'
				and wd3.status in ($this->status_validated)
				$realise_mod
				), 0) as realise_validated";


				$cra_entity = DB::table('users')
					->leftJoin('services', 'services.id', '=', 'users.service_id')
					->leftJoin('departments', 'departments.id', '=', 'users.department_id')
					->leftJoin('roles', 'roles.id', '=', 'users.role_id')
					->select(DB::raw("users.id as user_id,
						roles.id as role_id,
						roles.name as role_name,
						services.id as service_id,
						services.name as service_name,
						departments.id as department_id, 
						departments.name as department_name, 
						$fullName, 
						$realiseEntity_active,
						$realiseEntity_terminated,
						$realiseEntity_not_validated,
						$realiseEntity_validated
						"))
					->where('users.id', '=', auth()->user()->id)
					->first();

				// PARTIE USERS
				$realiseUser_active = "IFNULL((select sum(wd0.days) from work_days as wd0 
				where month(wd0.date) = '$current_month'
				and year(wd0.date) = '$current_year'
				and wd0.status in ($this->status_active)
				and wd0.user_id = users.id
				), 0) as realise_active";
				$realiseUser_terminated = "IFNULL((select sum(wd1.days) from work_days as wd1 
				where month(wd1.date) = '$current_month'
				and year(wd1.date) = '$current_year'
				and wd1.status in ($this->status_terminated)
				and wd1.user_id = users.id
				), 0) as realise_terminated";
				$realiseUser_not_validated = "IFNULL((select sum(wd2.days) from work_days as wd2
				where month(wd2.date) = '$current_month'
				and year(wd2.date) = '$current_year'
				and wd2.status in ($this->status_not_validated)
				and wd2.user_id = users.id
				), 0) as realise_not_validated";
				$realiseUser_validated = "IFNULL((select sum(wd3.days) from work_days as wd3
				where month(wd3.date) = '$current_month'
				and year(wd3.date) = '$current_year'
				and wd3.status in ($this->status_validated)
				and wd3.user_id = users.id
				), 0) as realise_validated";
				
				$cra_users = DB::table('users')
					->select(DB::raw("
						users.id as user_id, 
						$fullName,
						$realiseUser_active,
						$realiseUser_terminated,
						$realiseUser_not_validated,
						$realiseUser_validated"));

				switch ('cra_users') {
					case auth()->user()->role_id == config('constants.role_admin_id'):
						$cra_users = $cra_users
							->where('users.department_id', '=', auth()->user()->department_id);
						break;
					case auth()->user()->role_id == config('constants.role_directeur_id'):
						$cra_users = $cra_users
							->where('users.department_id', '=', auth()->user()->department_id)
							->whereIn('users.role_id', [
									config('constants.role_directeur_id'),
									config('constants.role_service_id')
								]);
						break;
					case auth()->user()->role_id == config('constants.role_service_id'):
						$cra_users = $cra_users
							->where('users.service_id', '=', auth()->user()->service_id)
							->whereIn('users.role_id', [
									config('constants.role_projet_id'),
									config('constants.role_agent_id'),
									config('constants.role_prestataire_id')
								]);
						break;
					default:
						$cra_users = $cra_users
							->where('users.id', '=', auth()->user()->id);
						break;
				}

			$cra_users = $cra_users
			->orderBy('users.last_name')
			->orderBy('users.first_name')
			->orderBy('users.id')
			->get();

				return view('work_days.work_days_index',
					compact(
						'all_user_id',
						'cra_entity',
						'cra_users',
						// 'cra_details',
						'current_year',
						'current_month',
						'current_date',
						'userTimes',
						'open_days_month',
						'user_total_absence_month',
						'user_total_realise_month',
						'user_jours_restants_month',
						
						'entityTimes',
						'open_days_entity',
						'entity_total_absences_month',
						'entity_total_realise_month',
						'entity_total_restants_month'));
			} else {
				return view('work_days.work_days_index',
					compact(
						'current_year',
						'current_date',
						'userTimes',
						'open_days_month',
						'user_total_absence_month',
						'user_total_realise_month',
						'user_jours_restants_month',
						
						'entityTimes',
						'open_days_entity',
						'entity_total_absences_month',
						'entity_total_realise_month',
						'entity_total_restants_month'));
			}
		} else {
			return view('work_days.work_days_index',
				compact(
					'current_year',
					'current_date',
					'userTimes',
					'open_days_month',
					'user_total_absence_month',
					'user_total_realise_month',
					'user_jours_restants_month'));
		}
		
	}

	public function details_to_validate($user_id, $month, $year)
	{
		//-----------------------------------------
		//SQL
		
		$WD_to_validate = DB::table('work_days')
			->leftJoin('tasks', 'tasks.id', '=', 'work_days.task_id')
			->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
			->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
			->leftJoin('users as u_manager', 'u_manager.id', '=', 'activities.manager')
			->leftJoin('task_types', 'task_types.id', '=', 'tasks.task_type_id')
			
			->where('work_days.user_id', '=', $user_id)
			->whereIn('work_days.status', [$this->status_terminated, $this->status_not_validated])
			->whereMonth('work_days.date', '=', $month)
			->whereYear('work_days.date', '=', $year)
		
			->select(DB::raw("activities.id as activity_id,
							activities.code as activity_code,
							u_manager.trigramme as activity_manager,
							phases.id as phase_id,									
							phases.name as phase_name,
							tasks.id as task_id,
							tasks.name as task_name,
							tasks.status as task_status,
							tasks.start_p as task_start_p,
							tasks.milestone as task_milestone,
							task_types.name as task_type_name,
							work_days.id as wd_id,
							work_days.user_id as wd_user_id,
							work_days.status as wd_status,
							work_days.date as wd_date,
							work_days.description as wd_description,
							work_days.days as wd_days"))

			->orderBy('activities.name', 'asc')
			->orderBy('phases.name', 'asc')
			->orderBy('tasks.name', 'asc')
			->orderBy('tasks.start_p', 'asc')
			->get();
		
		return ['WD_to_validate' => $WD_to_validate];
	}


	public function details_to_deny($user_id, $month, $year)
	{
		//-----------------------------------------
		//SQL
		
		$WD_to_deny = DB::table('work_days')
			->leftJoin('tasks', 'tasks.id', '=', 'work_days.task_id')
			->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
			->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
			->leftJoin('users as u_manager', 'u_manager.id', '=', 'activities.manager')
			->leftJoin('task_types', 'task_types.id', '=', 'tasks.task_type_id')
			
			->where('work_days.user_id', '=', $user_id)
			->whereIn('work_days.status', [$this->status_terminated, $this->status_validated])
			->whereMonth('work_days.date', '=', $month)
			->whereYear('work_days.date', '=', $year)
		
			->select(DB::raw("activities.id as activity_id,
							activities.code as activity_code,
							u_manager.trigramme as activity_manager,
							phases.id as phase_id,									
							phases.name as phase_name,
							tasks.id as task_id,
							tasks.name as task_name,
							tasks.status as task_status,
							tasks.start_p as task_start_p,
							tasks.milestone as task_milestone,
							task_types.name as task_type_name,
							work_days.id as wd_id,
							work_days.user_id as wd_user_id,
							work_days.status as wd_status,
							work_days.date as wd_date,
							work_days.description as wd_description,
							work_days.days as wd_days"))

			->orderBy('activities.name', 'asc')
			->orderBy('phases.name', 'asc')
			->orderBy('tasks.name', 'asc')
			->orderBy('tasks.start_p', 'asc')
			->get();
		
		return ['WD_to_deny' => $WD_to_deny];
	}

	/**
	 * Create new work day
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function create(Request $request)
	{
		$task = Task::find($request->task_id);
		
		/*
		 * Retrieve config flag value
		 */
		try {
			/*
			 * Begin DB transaction
			 */
			LogActivity::beginTransaction(__METHOD__, __LINE__);
			
			$wday = new WorkDay();
			$wday->date = $request->work_day_date;
			if ($request->work_day_days) $wday->days = $request->work_day_days; else $wday->days = 0;
			$wday->description = $request->work_day_description;
			$wday->user_id = auth()->user()->id;
			$wday->tasks()->associate($task);
			$wday->save();
			
			Task::update_task_R($request->task_id);
			Phase::update_phase_R($request->phase_id);
			Activity::update_activity_R($request->activity_id);
			
			LogActivity::dbCommit(__METHOD__, __LINE__);
			
			LogActivity::addToLog(trans(self::subject), __FUNCTION__, $wday);
			
			flashy()->success(trans('flash_message.work_day_create'));
			
		} catch (\Exception $ex) {
			/*
			 * DB rollback if DBMS actions fails
			 */
			LogActivity::dbRollback(__METHOD__, __LINE__);
			
			flashy()->error(trans('flash_message.work_day_create_error'));
		}
		return redirect()->back();
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}
	
	/**
	 * Display a listing of the resource.
	 * @param $task_id
	 * @return array
	 */
	public function show($task_id)
	{
		//-----------------------------------------
		//SQL
		
		$sumWorkDays = "IFNULL((select sum(days) from work_days where work_days.task_id = task_id), 0) as sum_work_days";
		
		$workDays = DB::table('work_days')
			->leftJoin('tasks', 'tasks.id', '=', 'work_days.task_id')
			->leftJoin('users', 'users.id', '=', 'work_days.user_id')
			->select(DB::raw("work_days.id as work_day_id,
				work_days.description as work_day_description,
				work_days.days as work_day_days,
				work_days.date as work_day_date,
				work_days.status as work_day_status,
				work_days.task_id as work_day_task_id,
				users.trigramme as user_trigramme,
				$sumWorkDays"))
			->where('work_days.task_id', '=', $task_id)
			->whereIn('work_days.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
			->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
			->orderBy('work_days.date', 'asc')
			->get();
		
		return ['workDays' => $workDays];
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}
	
	/**
	 * @param Request $request
	 * update time
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request)
	{
		$work_day = WorkDay::find($request->work_day_id);
		$task_id = $work_day->task_id;
		$phase_id = $request->phase_id;
		$activity_id = $request->activity_id;

		try {
			LogActivity::beginTransaction(__METHOD__, __LINE__);
			
			$work_day->date = $request->work_day_date;

			if ($work_day->days != $request->work_day_days)	{
				$work_day->days = $request->work_day_days;
			}

			if ($work_day->description != $request->work_day_description) {
				$work_day->description = $request->work_day_description;	
			}

			$update_task_status = false;
			$update_phase_status = false;
			$update_activity_status = false;


			if ($work_day->isDirty()) {
				
				$status_wd = $work_day->status;

				if (($status_wd == $this->status_terminated) ||
					($status_wd == $this->status_not_validated) ) {
					$work_day->status = $this->status_active;					
				}

				if (session()->get('cra_validate') == false) {
					if	($status_wd == $this->status_validated) {
						$work_day->status = $this->status_active;					
					}
				}

				$work_day->update();

				$status_task = Task::get_status($task_id);
				$new_task_status = Task::get_new_status($task_id);

				if ($new_task_status != $status_task) {
					Task::update_status_simple($task_id, $new_task_status);
					$update_task_status = true;
				}


				if ($update_task_status == true) 
				{
					$status_phase = Phase::get_status($phase_id);
					$new_phase_status = Phase::get_new_status($phase_id);

					if ($new_phase_status != $status_phase) {
						Phase::update_status_simple($phase_id, $new_phase_status);
						$update_phase_status = true;
					}
				}

				if ($update_phase_status == true) 
				{
					$status_activity = Activity::get_status($activity_id);
					$new_activity_status = Activity::get_new_status($phase_id);

					if ($new_activity_status != $status_activity) {
						Activity::update_status_simple($activity_id, $new_activity_status);
						$update_activity_status = true;
					}
				}				
				
				Task::update_task_R($task_id);
				Phase::update_phase_R($phase_id);
				Activity::update_activity_R($activity_id);
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, $work_day);
				
				flashy()->success(trans('flash_message.work_day_update'));
				
			} else {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->info(trans('flash_message.not_dirty'));
			}
			
		} catch (\Exception $ex) {
			
			LogActivity::dbRollback(__METHOD__, __LINE__);
			
			flashy()->error(trans('flash_message.work_day_update_error'));
		}
		
		return redirect()->back();
	}
	
	/**
	 * @param Request $request
	 * Delete Time
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public static function destroy(Request $request, $wd_id_in = null, $task_id_in = null, $phase_id_in = null, $activity_id_in = null)
	{
		//-----------------------------------------
		//SQL
		$status_deleted = config('constants.status_deleted');
		
		// On ne teste que le premier des paramètres optionnels
		// Car soit on les a tous, soit aucun
		if ($wd_id_in == null) {
			$wd_id = $request->work_day_id;
			$task_id = $request->task_id;
			$phase_id = $request->phase_id;
			$activity_id = $request->activity_id;
			$come_from_function = false;
		} else {
			$wd_id = $wd_id_in;
			$task_id = $task_id_in;
			$phase_id = $phase_id_in;
			$activity_id = $activity_id_in;
			$come_from_function = true;
		}
		
		try {
			
			LogActivity::beginTransaction(__METHOD__, __LINE__);
			
			$wd_status = WorkDay::get_status($wd_id);
			
			if ($wd_status != $status_deleted) {
				
				WorkDay::update_status_simple($wd_id, $status_deleted);
				
				if ($come_from_function == false) { // Veut dire qu'on vient directement de la vue
					Task::update_task_R($task_id);
					Phase::update_phase_R($phase_id);
					Activity::update_activity_R($activity_id);
				}
			}
			
			LogActivity::dbCommit(__METHOD__, __LINE__);
			
			LogActivity::addToLog(trans(self::subject), __function__, WorkDay::find($wd_id));
			
			flashy()->success(trans('flash_message.work_day_delete'));
			
		} catch (\Exception $ex) {
			
			LogActivity::dbRollback(__METHOD__, __LINE__);
			
			flashy()->error(trans('flash_message.work_day_delete_error'));
		}
		
		return redirect()->back();
	}


	public function validate_wd(Request $request, $type = null)
	{

		//-----------------------------------------
		//SQL

		if ($request->wd_id) 
			$wd_ids = explode(',', $request->wd_id);
		else
			$wd_ids = null;

		if($wd_ids){
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				if ($wd_ids != null) {
					foreach ($wd_ids as $wd_id) {

						$wd_status = WorkDay::get_status($wd_id);

						if (($wd_status == $this->status_terminated) ||
							($wd_status == $this->status_not_validated) ) 
						{
							$task_id = WorkDay::get_task_id($wd_id);
							$phase_id = Task::get_phase_id($task_id);
							$activity_id = Phase::get_activity_id($phase_id);
							
							WorkDay::update_status_simple($wd_id, $this->status_validated);

							Task::update_status_auto($task_id);
							Phase::update_status_auto($phase_id);
							Activity::update_status_auto($activity_id);

							Task::update_task_P($task_id);
							Task::update_task_R($task_id);

							Phase::update_phase_P($phase_id);
							Phase::update_phase_R($phase_id);

							Activity::update_activity_P($activity_id);
							Activity::update_activity_R($activity_id);
			
							LogActivity::addToLog(trans(self::subject), __function__, WorkDay::find($wd_id));
						}
					}
				}
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				
				flashy()->success(trans('flash_message.work_day_validate'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.work_day_validate_error'));
			}
		}
		else{
				flashy()->info(trans('flash_message.not_dirty'));
		}
		
		return redirect()->back();
	}

public function deny_wd(Request $request, $type = null)
	{

		//-----------------------------------------
		//SQL

		if ($request->wd_id) 
			$wd_ids = explode(',', $request->wd_id);
		else
			$wd_ids = null;

		if($wd_ids){
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				if ($wd_ids != null) {
					foreach ($wd_ids as $wd_id) {

						$wd_status = WorkDay::get_status($wd_id);

						if (($wd_status == $this->status_terminated) ||
							($wd_status == $this->status_validated) ) 
						{
							$task_id = WorkDay::get_task_id($wd_id);
							$phase_id = Task::get_phase_id($task_id);
							$activity_id = Phase::get_activity_id($phase_id);
							
							WorkDay::update_status_simple($wd_id, $this->status_not_validated);

							Task::update_status_auto($task_id);
							Phase::update_status_auto($phase_id);
							Activity::update_status_auto($activity_id);

							Task::update_task_P($task_id);
							Task::update_task_R($task_id);

							Phase::update_phase_P($phase_id);
							Phase::update_phase_R($phase_id);

							Activity::update_activity_P($activity_id);
							Activity::update_activity_R($activity_id);
			
							LogActivity::addToLog(trans(self::subject), __function__, WorkDay::find($wd_id));
						}
					}
				}
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				
				flashy()->success(trans('flash_message.work_day_deny'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.work_day_deny_error'));
			}
		}
		else{
				flashy()->info(trans('flash_message.not_dirty'));
		}
		
		return redirect()->back();
	}

	public function validate_user_all(Request $request, $type = null)
	{

		//-----------------------------------------
		//SQL
		
		$current_user = $request->user_id_in;
		$current_month = $request->current_month;
		$current_year = $request->current_year;

		$wd_ids = WorkDay::get_wd_for_user_month($current_user, $current_month, $current_year);

		if($wd_ids){
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				if ($wd_ids != null) {
					foreach ($wd_ids as $wd_id) {

						$wd_status = WorkDay::get_status($wd_id);

						if (($wd_status == $this->status_terminated) ||
							($wd_status == $this->status_not_validated) ) 
						{
							$task_id = WorkDay::get_task_id($wd_id);
							$phase_id = Task::get_phase_id($task_id);
							$activity_id = Phase::get_activity_id($phase_id);
							
							WorkDay::update_status_simple($wd_id, $this->status_validated);

							Task::update_status_auto($task_id);
							Phase::update_status_auto($phase_id);
							Activity::update_status_auto($activity_id);

							Task::update_task_P($task_id);
							Task::update_task_R($task_id);

							Phase::update_phase_P($phase_id);
							Phase::update_phase_R($phase_id);

							Activity::update_activity_P($activity_id);
							Activity::update_activity_R($activity_id);
			
							LogActivity::addToLog(trans(self::subject), __function__, WorkDay::find($wd_id));
						}
					}
				}
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				flashy()->success(trans('flash_message.work_day_validate_user_all'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.work_day_validate_user_all_error'));
			}
		}
		else{
				flashy()->info(trans('flash_message.not_dirty'));
		}
		
		return redirect()->back();
	}

	public function deny_user_all(Request $request, $type = null)
	{

		//-----------------------------------------
		//SQL
		
		$current_user = $request->user_id_in;
		$current_month = $request->current_month;
		$current_year = $request->current_year;

		$wd_ids = WorkDay::get_wd_for_user_month($current_user, $current_month, $current_year);

		if($wd_ids){
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				if ($wd_ids != null) {
					foreach ($wd_ids as $wd_id) {

						$wd_status = WorkDay::get_status($wd_id);

						if (($wd_status == $this->status_terminated) ||
							($wd_status == $this->status_validated) ) 
						{
							$task_id = WorkDay::get_task_id($wd_id);
							$phase_id = Task::get_phase_id($task_id);
							$activity_id = Phase::get_activity_id($phase_id);
							
							WorkDay::update_status_simple($wd_id, $this->status_not_validated);

							Task::update_status_auto($task_id);
							Phase::update_status_auto($phase_id);
							Activity::update_status_auto($activity_id);

							Task::update_task_P($task_id);
							Task::update_task_R($task_id);

							Phase::update_phase_P($phase_id);
							Phase::update_phase_R($phase_id);

							Activity::update_activity_P($activity_id);
							Activity::update_activity_R($activity_id);
			
							LogActivity::addToLog(trans(self::subject), __function__, WorkDay::find($wd_id));
						}
					}
				}
				
				LogActivity::dbCommit(__METHOD__, __LINE__);

				flashy()->success(trans('flash_message.work_day_deny_user_all'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.work_day_deny_user_all_error'));
			}
		}
		else{
				flashy()->info(trans('flash_message.not_dirty'));
		}
		
		return redirect()->back();
	}


	public function validate_all(Request $request)
	{

		//-----------------------------------------
		//SQL
		
		$all_users = explode(',', $request->user_id_in);
		$current_month = $request->current_month;
		$current_year = $request->current_year;

		$is_updated = false; 

		if ($all_users) 
		{

			if ($all_users != null) 
			{

				foreach ($all_users as $current_user) 
				{
					$wd_ids = WorkDay::get_wd_for_user_month($current_user, $current_month, $current_year);
					
					if($wd_ids)
					{
						if ($wd_ids != null) 
						{
							try 
							{
								LogActivity::beginTransaction(__METHOD__, __LINE__);

								foreach ($wd_ids as $wd_id) 
								{

									$wd_status = WorkDay::get_status($wd_id);

									if (($wd_status == $this->status_terminated) ||
										($wd_status == $this->status_not_validated) ) 
									{
										$is_updated = true;
		
										$task_id = WorkDay::get_task_id($wd_id);
										$phase_id = Task::get_phase_id($task_id);
										$activity_id = Phase::get_activity_id($phase_id);
										
										WorkDay::update_status_simple($wd_id, $this->status_validated);

										Task::update_status_auto($task_id);
										Phase::update_status_auto($phase_id);
										Activity::update_status_auto($activity_id);

										Task::update_task_P($task_id);
										Task::update_task_R($task_id);

										Phase::update_phase_P($phase_id);
										Phase::update_phase_R($phase_id);

										Activity::update_activity_P($activity_id);
										Activity::update_activity_R($activity_id);
						
										LogActivity::addToLog(trans(self::subject), __function__, WorkDay::find($wd_id));
									}
								}
							}
							catch (\Exception $ex) 
							{								
								LogActivity::dbRollback(__METHOD__, __LINE__);
								flashy()->error(trans('flash_message.work_day_validate_all_error'));
							}
							LogActivity::dbCommit(__METHOD__, __LINE__);
							flashy()->success(trans('flash_message.work_day_validate_all'));
						} 
					}
				}
			}
		}
		
		if($is_updated == false) {
			flashy()->info(trans('flash_message.not_dirty'));
		}	
			
		return redirect()->back();
	}

	public function deny_all(Request $request)
	{

		//-----------------------------------------
		//SQL
		
		$all_users = explode(',', $request->user_id_in);

		$is_updated = false; 

		if ($all_users) 
		{

			if ($all_users != null) 
			{

				foreach ($all_users as $current_user) 
				{
					// dd($request->user_id_in, $all_users, $current_user);
					$wd_ids = WorkDay::get_wd_for_user_month($current_user, $this->current_month, $this->current_year);
					
					if($wd_ids)
					{
						if ($wd_ids != null) 
						{
							try 
							{
								LogActivity::beginTransaction(__METHOD__, __LINE__);

								foreach ($wd_ids as $wd_id) 
								{

									$wd_status = WorkDay::get_status($wd_id);

									if (($wd_status == $this->status_terminated) ||
										($wd_status == $this->status_validated) ) 
									{
										$is_updated = true;
		
										$task_id = WorkDay::get_task_id($wd_id);
										$phase_id = Task::get_phase_id($task_id);
										$activity_id = Phase::get_activity_id($phase_id);
										
										WorkDay::update_status_simple($wd_id, $this->status_not_validated);

										Task::update_status_auto($task_id);
										Phase::update_status_auto($phase_id);
										Activity::update_status_auto($activity_id);

										Task::update_task_P($task_id);
										Task::update_task_R($task_id);

										Phase::update_phase_P($phase_id);
										Phase::update_phase_R($phase_id);

										Activity::update_activity_P($activity_id);
										Activity::update_activity_R($activity_id);
						
										LogActivity::addToLog(trans(self::subject), __function__, WorkDay::find($wd_id));
									}
								}
							}
							catch (\Exception $ex) 
							{								
								LogActivity::dbRollback(__METHOD__, __LINE__);
								flashy()->error(trans('flash_message.work_day_validate_all_error'));
							}
							LogActivity::dbCommit(__METHOD__, __LINE__);
							flashy()->success(trans('flash_message.work_day_validate_all'));
						} 
					}
				}
			}
		}
		
		if($is_updated == false) {
			flashy()->info(trans('flash_message.not_dirty'));
		}	
		
		return redirect()->back();
	}

}
