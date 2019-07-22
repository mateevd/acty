<?php
	
	namespace App\Http\Controllers;
	
	use Carbon\Carbon;
	use Illuminate\Support\Facades\DB;
	
	class ChargeController extends Controller
	{
		
		const subject = 'activityLog.Charge';
		const line = ' -line ';
		const level = ' -level ';
		
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
		 * @info index page Charges
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function index()
		{
			//=========================================================================
			//GLOBALS VARIABLES
			
			//-----------------------------------------
			//SET START OF WEEK //SJL : UTILE ?
			Carbon::setWeekEndsAt(Carbon::FRIDAY); // SJL : ce n'est plus utile ?
			
			//-----------------------------------------
			//SET CURRENT DATE OR GET GATE FROM FORM
			$current_date = $this->current_date;
			$current_month = $this->current_month;
			$current_year = $this->current_year;
			
			//-----------------------------------------
			//DISPLAY DATES
			$range_start = Carbon::parse($current_date);
			$range_end = Carbon::parse($current_date)->addMonth(11);
			do {
				$displayMonths[$range_start->format('m/Y')] = $range_start->format('m/Y');
			} while ($range_start->addMonth() <= $range_end);
			
			$range_start = Carbon::parse($current_date);
			
			//-----------------------------------------
			//SQL
			
			$current_user = auth()->user()->id;
			$direction_id = auth()->user()->department_id;
			$service_id = auth()->user()->service_id;
			$current_date_start_month = Carbon::parse($current_date)->startOfMonth();
			
			//-----------------------------------------
			//USER FULL NAME
			$fullName = "CONCAT(users.last_name, \" \", users.first_name) AS full_name";
			//=========================================================================
			
			
			//-----------------------------------------
			//OPEN_DAYS FOR CURRENT MONTH
			$open_days = "IFNULL((select open_days.days from open_days
							where open_days.month = month(tasks.start_p)
							and open_days.year = year(tasks.start_p)), 0) 
							as open_days";
			
			//-----------------------------------------
			//SUM ABSENCES FOR CURRENT USER AND CURRENT MONTH
			$sum_absences = "IFNULL((select sum(abs_month.days) from absences as abs_month
							where abs_month.user_id = users.id 
							and month(abs_month.date) = month(tasks.start_p)
							and year(abs_month.date) = year(tasks.start_p)
							), 0) 
							as sum_absences";
			
			//-----------------------------------------
			//SUM DAYS_P FOR CURRENT USER AND CURRENT MONTH
			$sum_prevu = "IFNULL((select sum(t_prevu.days_p) from tasks as t_prevu
							where t_prevu.user_id = users.id
							and year(t_prevu.start_p) = year(tasks.start_p) 
							and month(t_prevu.start_p) = month(tasks.start_p) 
							and t_prevu.status in ($this->status_active, $this->status_terminated, $this->status_not_validated)
							), 0) 
							as sum_prevu";
			
			//-----------------------------------------
			//SUM DAYS_R FOR CURRENT USER AND CURRENT MONTH
			$sum_realise = "IFNULL((select sum(wd_realise.days) from work_days as wd_realise
							where wd_realise.user_id = users.id 
							and month(wd_realise.date) = month(tasks.start_p) 
							and year(wd_realise.date) = year(tasks.start_p)
							and wd_realise.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)
							), 0) 
							as sum_realise";
			
			
			//=========================================================================
			//ENTITY CHARGES - MAKE UNION TO HAVE ALL USERS (MYSQL DOES NOT SUPPORT OUTER)
			$entity_charges_union1 = DB::table('users')
				->leftJoin('services', 'services.id', '=', 'users.service_id')
				->leftJoin('departments', 'departments.id', '=', 'users.department_id');
			
			$users_charges_union2 = DB::table('users')
				->leftJoin('tasks', 'users.id', '=', 'tasks.user_id')
				->leftJoin('services', 'services.id', '=', 'users.service_id')
				->leftJoin('departments', 'departments.id', '=', 'users.department_id')
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereDate('tasks.start_p', '>=', $range_start->startOfMonth())
				->whereDate('tasks.start_p', '<=', $range_end->endOfMonth());
			
			switch ('charge') {
				case(auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id')):
					$entity_charges_union1 = $entity_charges_union1
						->where('users.department_id', '=', auth()->user()->department_id);
					$users_charges_union2 = $users_charges_union2
						->where('users.department_id', '=', auth()->user()->department_id);
					break;
				case(auth()->user()->role_id == config('constants.role_service_id')):
					$entity_charges_union1 = $entity_charges_union1
						->where('users.service_id', '=', auth()->user()->service_id);
					$users_charges_union2 = $users_charges_union2
						->where('users.service_id', '=', auth()->user()->service_id);
					break;
				default:
					$entity_charges_union1 = $entity_charges_union1
						->where('users.id', '=', auth()->user()->id);
					$users_charges_union2 = $users_charges_union2
						->where('users.id', '=', auth()->user()->id);
					break;
			}
			
			$entity_charges_union1 = $entity_charges_union1
				->select(DB::raw("users.id as user_id, departments.name as department_name, services.name as service_name, users.trigramme as trigramme, $fullName, 0 as yy, 0 as mm, 0 as open_days, 0 as sum_absences, 0 as sum_prevu, 0 as sum_realise"));
			
			$users_charges_union2 = $users_charges_union2
				->select(DB::raw("users.id as user_id, departments.name as department_name, services.name as service_name, users.trigramme as trigramme, $fullName, year(tasks.start_p) as yy, month(tasks.start_p) as mm, $open_days, $sum_absences, $sum_prevu, $sum_realise"));
			
			//-----------------------------------------
			//UNION COMMAND
			$entity_charges = $entity_charges_union1->union($users_charges_union2)
				->groupBy('user_id')
				->groupBy('yy')
				->groupBy('mm')
				->orderBy('full_name', 'asc')
				->orderBy('yy', 'asc')
				->orderBy('mm', 'asc')
				->get();
			
			$entity_users = DB::table('users')
				->leftJoin('services', 'services.id', '=', 'users.service_id')
				->leftJoin('departments', 'departments.id', '=', 'users.department_id');
			
			switch ('entity_users') {
				case(auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id')):
					$entity_users = $entity_users
						->where('users.department_id', '=', auth()->user()->department_id);
					break;
				case(auth()->user()->role_id == config('constants.role_service_id')):
					$entity_users = $entity_users
						->where('users.service_id', '=', auth()->user()->service_id);
					break;
				default:
					$entity_users = $entity_users
						->where('users.id', '=', auth()->user()->id);
					break;
			}
			
			$entity_users = $entity_users
				->whereIn('users.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->select(DB::raw("users.id as user_id, $fullName, departments.name as department_name, services.name as service_name"))
				->orderBy('full_name', 'asc')
				->orderBy('department_name', 'asc')
				->orderBy('service_name', 'asc')
				->get();
			
			//-----------------------------------------
			//CHARGES AND CAPACITIES CALCULATION
			foreach ($entity_charges as $entity_charge) {
				$entity_charge->user_capacity = ($entity_charge->open_days - $entity_charge->sum_absences - $entity_charge->sum_realise);
				
				$entity_charge->jours_planifiables = $entity_charge->open_days - $entity_charge->sum_absences - $entity_charge->sum_realise - $entity_charge->sum_prevu;
				// if ($entity_charge->jours_planifiables < 0) $entity_charge->jours_planifiables = 0;
				
				$entity_charge->charge_totale = 0;
				
				if ($entity_charge->user_capacity != 0) {
					$entity_charge->charge_totale = $entity_charge->sum_prevu / $entity_charge->user_capacity * 100;
				} else {
					if ($entity_charge->sum_prevu != 0) {
						$entity_charge->charge_totale = -1;
					}
				}
				
				// if ($entity_charge->charge_totale < 0) $entity_charge->charge_totale = 0;
				
				$entity_charge->display_month = str_pad($entity_charge->mm, 2, '0', STR_PAD_LEFT);
				$entity_charge->display_year = str_pad($entity_charge->yy, 4, '0', STR_PAD_LEFT);
			}
			
			return view('charges.charge_index',
				compact(
					'entity_charges',
					'entity_users',
					'displayMonths',
					'current_date'
				));
		}
		
		/**
		 * @param $user_id
		 * @param $month
		 * @param $year
		 * @return array
		 */
		public function details($user_id, $month, $year)
		{
			//-----------------------------------------
			//SQL
			$detailCharge = DB::table('tasks as t')
				->leftJoin('phases', 'phases.id', '=', 't.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->leftJoin('task_types', 't.task_type_id', '=', 'task_types.id')
				->select(DB::raw("
				activities.name as activity_name,
				phases.name as phase_name,
				t.name as task_name,
				task_types.name as task_type_name,
				t.start_p as task_start_p,
				t.end_p as task_end_p,
				t.days_p as task_days_p,
				t.days_r as task_days_r,
				t.status as task_status"))
				->where('t.user_id', '=', $user_id)
				->whereMonth('t.start_p', '=', $month)
				->whereYear('t.start_p', '=', $year)
				->whereIn('t.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->orderBy('t.start_p', 'asc')
				->orderBy('activities.name', 'asc')
				->get();
			
			return ['detailCharge' => $detailCharge];
		}
		
	}
