<?php
	
	namespace App\Http\Controllers;
	
	use App\Helpers\LogActivity;
	use App\Models\Phase;
	use App\Models\Activity;
	use App\User;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Response;
	
	class ActivityController extends Controller
	{
		const subject = 'activityLog.Activity';
		
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
				$this->cra_validate = session('cra_validate');
				return $next($request);
			});
			
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
			
//			$this->phase_controller = new PhaseController();
			
		}
		
		public function download(Request $request)
		{
//-----------------------------------------
//SQL
			$name = config('app.name') . '_' . str_replace(' ', '_', Carbon::now()) . '_' . $request->export_item;
			$headers = [
				'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
				'Content-type' => 'text/csv; charset=UTF-8',
				'Content-Encoding' => 'UTF-8',
				'Content-Disposition' => "attachment; filename=$name.csv",
				'Expires' => '0',
				'Pragma' => 'public'
			];
			
			if ($request->export_item == config('constants.export_item_activities')) {
				$list = DB::table('activities')
					->leftjoin('users as u_manager', 'u_manager.id', '=', 'activities.manager')
					->leftjoin('users as u_deputy', 'u_deputy.id', '=', 'activities.deputy')
					->select(DB::raw("
						activities.id as id,
						activities.name as name,
						activities.code as code,
						activities.description as descr,
						CONCAT(u_manager.last_name, \" \", u_manager.first_name) as manager,
						CONCAT(u_deputy.last_name, \" \", u_deputy.first_name) as deputy,
						activities.start_p as start,
						activities.end_p as end,

						format(activities.enveloppe, 4, 'fr_FR') as enveloppe,
						format(activities.days_p_total, 4, 'fr_FR') as prevu,
						format(activities.days_p, 4, 'fr_FR') as restant
						"));
				if ($request->type_activities == config('constants.export_activities_actives')) {
					$list = $list
						->where('activities.status', '=', $this->status_active);
				}
				$list = $list
					->orderBy('activities.id')
					->get()
					->toArray();
			}
			
			if ($request->export_item == config('constants.export_item_charges')) {
				$start_date = Carbon::parse($request->charge_date_start);
				$end_date = Carbon::parse($request->charge_date_end);
				
				$list = DB::table('tasks as tsk')
					->leftjoin('phases as phs', 'phs.id', '=', 'tsk.phase_id')
					->leftjoin('activities as act', 'act.id', '=', 'phs.activity_id')
					->leftjoin('task_types as tt', 'tt.id', '=', 'tsk.task_type_id')
					->leftjoin('users as u_manager', 'u_manager.id', '=', 'act.manager')
					->leftjoin('users as u_deputy', 'u_deputy.id', '=', 'act.deputy')
					->leftjoin('users as u_task', 'u_task.id', '=', 'tsk.user_id')
					->select(DB::raw("
						act.id as act_id,
						act.name as act_name,
						act.code as act_code,
						CONCAT(u_manager.last_name, \" \", u_manager.first_name) as act_manager,
						CONCAT(u_deputy.last_name, \" \", u_deputy.first_name) as act_deputy,
						
						phs.id as phs_id,
						phs.name as phs_name,
						
						tsk.id as tsk_id,
						tsk.name as tsk_name,
						CONCAT(u_task.last_name, \" \", u_task.first_name) as tsk_affected,
						tt.name as tsk_type,
						tsk.description as descr,
						tsk.start_p as tsk_start,
						tsk.end_p as tsk_end,
						format(tsk.days_p, 4, 'fr_FR') as tsk_prev,
						format(tsk.days_r, 4, 'fr_FR') as tsk_real
						"))
					->where('tsk.status', '<>', $this->status_deleted)
					->where('phs.status', '<>', $this->status_deleted)
					->where('act.status', '<>', $this->status_deleted);
				
				if ($request->type_charges == config('constants.export_charges_bydate')) {
					$list = $list
						->whereDate('tsk.start_p', '>=', $start_date)
						->whereDate('tsk.end_p', '<=', $end_date);
				}
				
				$list = $list
					->orderBy('act.id')
					->orderBy('phs.id')
					->orderBy('tsk.id')
					->get()
					->toArray();
			}
			
			if ($request->export_item == config('constants.export_item_personal')) {
				$start_date = Carbon::parse($request->personal_date_start);
				$end_date = Carbon::parse($request->personal_date_end);
				
				$list = DB::table('work_days as wd')
					->leftjoin('tasks as tsk', 'tsk.id', '=', 'wd.task_id')
					->leftjoin('phases as phs', 'phs.id', '=', 'tsk.phase_id')
					->leftjoin('activities as act', 'act.id', '=', 'phs.activity_id')
					->leftjoin('task_types as tt', 'tt.id', '=', 'tsk.task_type_id')
					->leftjoin('users as u_manager', 'u_manager.id', '=', 'act.manager')
					->leftjoin('users as u_deputy', 'u_deputy.id', '=', 'act.deputy')
					->leftjoin('users as u_task', 'u_task.id', '=', 'tsk.user_id')
					->select(DB::raw("
						act.id as act_id,
						act.name as act_name,
						act.code as act_code,
						CONCAT(u_manager.last_name, \" \", u_manager.first_name) as act_manager,
						CONCAT(u_deputy.last_name, \" \", u_deputy.first_name) as act_deputy,
						
						phs.id as phs_id,
						phs.name as phs_name,
						
						tsk.id as tsk_id,
						tsk.name as tsk_name,
						CONCAT(u_task.last_name, \" \", u_task.first_name) as tsk_affected,
						tt.name as tsk_type,
						tsk.start_p as tsk_start,
						tsk.end_p as tsk_end,
						format(tsk.days_p, 4, 'fr_FR') as tsk_prev,
						format(tsk.days_r, 4, 'fr_FR') as tsk_real,
						
						wd.date as wd_date,
						wd.description as descr,
						format(wd.days, 4, 'fr_FR') as wd_days
						"))
					->where('wd.user_id', '=', auth()->user()->id)
					->where('wd.status', '<>', $this->status_deleted)
					->where('tsk.status', '<>', $this->status_deleted)
					->where('phs.status', '<>', $this->status_deleted)
					->where('act.status', '<>', $this->status_deleted);

//SJL: locale à chacker quand on fera du multilangue
				
				if ($request->type_personal == config('constants.export_personal_bydate')) {
					$list = $list
						->whereDate('wd.date', '>=', $start_date)
						->whereDate('wd.date', '<=', $end_date);
				}
				
				$list = $list
					->orderBy('wd.date')
					->orderBy('act.id')
					->orderBy('phs.id')
					->orderBy('tsk.id')
					->get()
					->toArray();
			}
			
			foreach ($list as $key => $value) {
				if ($value->descr) {
					$value->descr = str_replace("\r\n", "", $value->descr);
					$value->descr = str_replace("\r", "", $value->descr);
				}
			};
			
			# add headers for each column in the CSV download
			if ($list) {
				array_unshift($list, array_keys(get_object_vars($list[0])));
				
				$callback = function () use ($list) {
					$FH = fopen('php://output', 'w+');
					foreach ($list as $key => $value) {
						fputs($FH, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
// fputcsv($FH, (array)$value, ',', '"', '\\');
						fputcsv($FH, (array)$value, ';', '"'); //SJL : j'ai mis le point virgule en séparateur
					}
					fclose($FH);
				};
				
				return Response::stream($callback, 200, $headers);
			}
			
			return redirect()->back();
		}
		
		/**
		 * Get activities for activity page
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
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
			$direction_id = auth()->user()->department_id;
			
			//-----------------------------------------
			//USER FULL NAME
			$fullName = "CONCAT(users.last_name, \" \", users.first_name) AS full_name";
			//=========================================================================
			
			
			$privacies = array(
				config('constants.private_no') => ucfirst(trans('activity.lab_private_no')),
				config('constants.private_yes') => ucfirst(trans('activity.lab_private_yes'))
			);
			
			//Get priorities
			$priorities = DB::table('priorities')
				->orderBy('rank', 'asc')
				->pluck('name', 'id');
			
			//Get activity types
			$activity_types = DB::table('activity_types')
				->orderBy('name', 'asc')
				->pluck('name', 'id');
			
			//Get departments
			$departments = DB::table('departments')
				->pluck('name', 'id');
			
			//Get services
			$services = DB::table('services')
				->pluck('name', 'id');
			
			// Get businesses
			$businesses = DB::table('businesses')
				->pluck('name', 'id');
			
			//Get user name + concat
			$managers = DB::table('users')
				->select(DB::raw("id, $fullName"))
				->whereIn('users.role_id', [
					config('constants.role_admin_id'),
					config('constants.role_directeur_id'),
					config('constants.role_service_id'),
					config('constants.role_projet_id')
				])
				->orderBy('last_name')
				->pluck('full_name', 'id');
			
			$deputies = $managers;
			
			//Get states
			$states = DB::table('states')
				->pluck('name', 'id');
			
			// //=========================================================================
			// //COUNT PHASES FOR CURRENT ACTIVITIES
			// $countPhases = "(select count(distinct ph1.id) from activities as act1 
			// left join phases as ph1 on ph1.activity_id = act1.id 
			// left join tasks as ts1 on ph1.id = ts1.phase_id 
			// where act1.id = activities.id and 
			// ph1.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated) and 
			// ts1.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)) as nb_phases";
			
			// //COUNT TASKS FOR CURRENT ACTIVITIES
			// $countTasks = "(select count(distinct ts1.id) from activities as act1 
			// left join phases as ph1 on ph1.activity_id = act1.id 
			// left join tasks as ts1 on ph1.id = ts1.phase_id 
			// where act1.id = activities.id and 
			// ph1.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated) and 
			// ts1.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)) as nb_tasks";
			
			
			//Table Projects joins
			$activities = DB::table('activities')
				->leftJoin('users as u_manager', 'activities.manager', '=', 'u_manager.id')
				->leftJoin('users as u_deputy', 'activities.deputy', '=', 'u_deputy.id')
				->leftJoin('activity_types', 'activities.activity_type_id', '=', 'activity_types.id')
				->leftJoin('departments', 'activities.department_id', '=', 'departments.id')
				->leftJoin('businesses', 'activities.business_id', '=', 'businesses.id')
				->leftJoin('services', 'activities.service_id', '=', 'services.id')
				->leftJoin('states', 'states.id', '=', 'activities.state_id')
				->leftJoin('priorities', 'activities.priority_id', '=', 'priorities.id')
				->select(DB::raw("
					activities.id as activity_id,
					activities.name as activity_name,
					activities.manager as activity_manager,
					activities.deputy as activity_deputy,
					activities.code as activity_code,
					activities.activity_type_id as activity_type_id,
					activities.status as activity_status,
					activities.private as activity_private,
					activities.enveloppe as activity_enveloppe,
					activities.start_p as activity_start_p,
					activities.end_p as activity_end_p,
					activities.start_r as activity_start_r,
					activities.end_r as activity_end_r,
					activities.days_p as activity_days_p,
					activities.days_p_total as activity_days_p_total,
					activities.days_r as activity_days_r,
					activities.date_requested as activity_date_requested,
					activities.date_limit as activity_date_limit,
					activities.priority_id as activity_priority_id,
					activities.state_id as activity_state_id,
					activities.charges_investment as activity_charges_investment,
					activities.charges_operation as activity_charges_operation,
					activities.opex_p as activity_opex_p,
					activities.opex_p_total as activity_opex_p_total,
					activities.opex_r as activity_opex_r,
					activities.description as activity_description,
					u_manager.trigramme as activity_manager_tri,
					u_deputy.trigramme as activity_deputy_tri,
					activity_types.name as activity_type_name,
					activities.department_id as activity_department_id,
					departments.name as activity_department_name,
					activities.service_id as activity_service_id,
					services.code as activity_service_code,
					priorities.id as activity_priority_id,
					priorities.name as activity_priority_name,
					businesses.id as activity_business_id,
					businesses.name as activity_business_name,
					states.rank as activity_state_rank"))
				->where('activities.department_id', '=', $direction_id)
				->whereIn('activities.status', [
					$this->status_active,
					$this->status_terminated,
					$this->status_not_validated,
					$this->status_validated])
				->orderBy('activities.name', 'asc')
				->orderBy('activities.start_p', 'asc')
				->get()->toArray();
			
			foreach ($activities as $activity) {
				$activity->activity_budget_total = $activity->activity_opex_p_total + $activity->activity_charges_investment + $activity->activity_charges_operation;
			}
			
			
			//ALL ACTIVITIES
			$activities_count = count($activities);
			
			$activities_count_active = isset(array_count_values(array_column($activities, 'activity_status'))[$this->status_active]) ?
				array_count_values(array_column($activities, 'activity_status'))[$this->status_active] : 0;
			
			$activities_count_terminated = isset(array_count_values(array_column($activities, 'activity_status'))[$this->status_terminated]) ?
				array_count_values(array_column($activities, 'activity_status'))[$this->status_terminated] : 0;
			
			$activities_count_not_validated = isset(array_count_values(array_column($activities, 'activity_status'))[$this->status_not_validated]) ?
				array_count_values(array_column($activities, 'activity_status'))[$this->status_not_validated] : 0;
			
			$activities_count_validated = isset(array_count_values(array_column($activities, 'activity_status'))[$this->status_validated]) ?
				array_count_values(array_column($activities, 'activity_status'))[$this->status_validated] : 0;
			
			if ($this->cra_validate == false) {
				$activities_count_validated = $activities_count_validated +
					$activities_count_not_validated +
					$activities_count_terminated;
				
				$activities_count_not_validated = 0;
				$activities_count_terminated = 0;
			}
			
			
			//CONNECTED USER ACTIVITIES
			$activities_mine = array_filter($activities, function ($v) {
				
				switch ($v) {
					case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
						$v = ($v->activity_department_id == auth()->user()->department_id ||
							$v->activity_manager == auth()->user()->id ||
							$v->activity_deputy == auth()->user()->id);
						break;
					case (auth()->user()->role_id == config('constants.role_service_id')):
						$v = ($v->activity_service_id == auth()->user()->service_id ||
							$v->activity_manager == auth()->user()->id ||
							$v->activity_deputy == auth()->user()->id);
						break;
					case (auth()->user()->role_id == config('constants.role_projet_id')):
						$v = ($v->activity_manager == auth()->user()->id ||
							$v->activity_deputy == auth()->user()->id);
						break;
					default:
						$v = ($v->activity_manager == auth()->user()->id ||
							$v->activity_deputy == auth()->user()->id);
						break;
				}
				
				return ($v);
			});
			
			$activities_mine_count = count($activities_mine);
			
			$activities_mine_count_active = isset(array_count_values(array_column($activities_mine, 'activity_status'))[$this->status_active]) ?
				array_count_values(array_column($activities_mine, 'activity_status'))[$this->status_active] : 0;
			
			$activities_mine_count_terminated = isset(array_count_values(array_column($activities_mine, 'activity_status'))[$this->status_terminated]) ?
				array_count_values(array_column($activities_mine, 'activity_status'))[$this->status_terminated] : 0;
			
			$activities_mine_count_not_validated = isset(array_count_values(array_column($activities_mine, 'activity_status'))[$this->status_not_validated]) ?
				array_count_values(array_column($activities_mine, 'activity_status'))[$this->status_not_validated] : 0;
			
			$activities_mine_count_validated = isset(array_count_values(array_column($activities_mine, 'activity_status'))[$this->status_validated]) ?
				array_count_values(array_column($activities_mine, 'activity_status'))[$this->status_validated] : 0;
			
			if ($this->cra_validate == false) {
				$activities_mine_count_validated = $activities_mine_count_validated +
					$activities_mine_count_not_validated +
					$activities_mine_count_terminated;
				
				$activities_mine_count_not_validated = 0;
				$activities_mine_count_terminated = 0;
			}
			
			return view('activities.activity_index',
				compact(
					'current_date',
					'current_year',
					'current_month',
					
					'activities_count',
					'activities_count_active',
					'activities_count_terminated',
					'activities_count_not_validated',
					'activities_count_validated',
					
					'activities_mine_count',
					'activities_mine_count_active',
					'activities_mine_count_terminated',
					'activities_mine_count_not_validated',
					'activities_mine_count_validated',
					
					'activities_mine',
					'activities',
					'privacies',
					'priorities',
					'activity_types',
					'departments',
					'services',
					'deputies',
					'businesses',
					'managers',
					'states'));
		}
		
		/**
		 * Function create activity
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
		 */
		public function create(Request $request)
		{
			/*get manager service & department ids*/
			if ($request->activity_manager) {
				$get_service = User::with('activities')->where('id', '=', $request->activity_manager)->pluck('service_id')->first();
				$get_department = User::with('activities')->where('id', '=', $request->activity_manager)->pluck('department_id')->first();
			} else {
				$get_service = auth()->user()->service_id;
				$get_department = auth()->user()->department_id;
			}
			
			try {
				/*
				 * Begin DB transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$activity = new Activity();
				$activity->name = $request->activity_name;
				$activity->code = $request->activity_code;
				$activity->manager = $request->activity_manager;
				$activity->priority_id = $request->activity_priority_id;
				$activity->activity_type_id = $request->activity_type_id;
				$activity->state_id = $request->activity_state_id;
				$activity->start_p = $request->activity_start_p;
				$activity->date_requested = $request->activity_date_requested;
				$activity->business_id = $request->activity_business_id;
				$activity->end_p = $request->activity_end_p;
				$activity->date_limit = $request->activity_date_limit;
				$activity->enveloppe = $request->activity_enveloppe;
				$activity->charges_investment = $request->activity_charges_investment;
				$activity->charges_operation = $request->activity_charges_operation;
				$activity->description = $request->activity_description;
				$activity->deputy = $request->activity_deputy;
				$activity->private = $request->activity_private;
				$activity->status = 0;
				
				
				$activity->service_id = $get_service;
				$activity->department_id = $get_department;
				$activity->user_id = auth()->user()->id;
				$activity->save();
				
				/*
				 * DB commit if no errors
				 */
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, $activity);
				
				/*Show flashy message*/
				flashy()->success(trans('flash_message.activity_create'));
				
			} catch (\Exception $ex) {
				/*
				 * DB rollback if commit fails
				 */
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				/*Show flashy message*/
				flashy()->error(trans('flash_message.activity_create_error'));
			}
			return redirect()->back();
		}
		
		/**
		 * Details of activity/phases + tasks/
		 * @param $activity_id
		 * @return array
		 */
		public function details($activity_id)
		{
			//-----------------------------------------
			//SQL
			$getPhasesForActivity = DB::table('phases')
				->where('phases.activity_id', '=', $activity_id);
			
			if ($this->cra_validate == false) {
				$getPhasesForActivity = $getPhasesForActivity
					->whereIn('phases.status', [$this->status_active, $this->status_validated]);
			} else {
				$getPhasesForActivity = $getPhasesForActivity
					->whereIn('phases.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated]);
			}
			
			$getPhasesForActivity = $getPhasesForActivity
				->pluck('phases.id');
			
			
			$tasksForPhase = DB::table('tasks')
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->leftJoin('task_types', 'task_types.id', '=', 'tasks.task_type_id')
				->whereIn('tasks.phase_id', $getPhasesForActivity);
			
			if ($this->cra_validate == false) {
				$tasksForPhase = $tasksForPhase
					->whereIn('tasks.status', [$this->status_active, $this->status_validated]);
			} else {
				$tasksForPhase = $tasksForPhase
					->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated]);
			}
			
			$tasksForPhase = $tasksForPhase
				->select(DB::raw("CONCAT(users.last_name, \" \", users.first_name) as task_full_name,
					phases.name as task_phase_name,
					tasks.name as task_name,
					tasks.start_p as task_start_p,
					tasks.end_p as task_end_p,
					tasks.days_p as task_days_p,
					tasks.days_r as task_days_r,
					tasks.status as task_status,
					task_types.name as task_type_name,
					$this->cra_validate as cra_validate
					"))
				->orderBy('phases.name', 'asc')
				->orderBy('tasks.start_p', 'asc')
				->orderBy('tasks.task_type_id', 'asc')
				->orderBy('tasks.name', 'asc')
				->orderBy('users.last_name', 'asc')
				->orderBy('users.first_name', 'asc')
				->get();
			
			return ['tasksForPhase' => $tasksForPhase];
		}
		
		/**
		 * Page Planification
		 * @param $activity_id
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function plan($activity_id)
		{
			//-----------------------------------------
			//SET CURRENT DATE OR GET GATE FROM FORM
			if (session()->has(['current_date', 'current_month', 'current_year'])) {
				$current_date = session()->get('current_date');
				$current_month = session()->get('current_month');
				$current_year = session()->get('current_year');
			} else {
				$current_date = Carbon::now();
				$current_month = Carbon::now()->month;
				$current_year = Carbon::now()->year;
			}
			
			//-----------------------------------------
			//SQL
			
			$activity_status = Activity::get_status($activity_id);
			if (($activity_status != $this->status_active) &&
				($activity_status != $this->status_terminated) &&
				($activity_status != $this->status_not_validated) &&
				($activity_status != $this->status_validated)) {
				return redirect('activities');
			}
			
			//Set date to current start of month
			$current_date = Carbon::now()->startOfMonth();
			
			$fullName = "CONCAT(users.last_name, \" \", users.first_name) AS full_name";
			
			$nb_total_users = DB::table('users')
				->count();
			
			$all_direction_users_connected_user = DB::table('users')
				->select(DB::raw("users.id, $fullName"))
				->where('users.id', auth()->user()->id);
			
			$all_direction_users_others = DB::table('users')
				->select(DB::raw("users.id, $fullName"))
				->whereIn('users.status', [$this->status_active])
				->whereIn('users.role_id', [
					config('constants.role_admin_id'),
					config('constants.role_directeur_id'),
					config('constants.role_service_id'),
					config('constants.role_projet_id'),
					config('constants.role_agent_id'),
					config('constants.role_prestataire_id')
				])
				->where('users.id', '<>', auth()->user()->id)
				->limit($nb_total_users)
				->orderBy('full_name', 'asc');
			
			//-----------------------------------------
			//UNION COMMAND TO GET CONNECTED USER FIRST
			$all_direction_users = $all_direction_users_connected_user->union($all_direction_users_others)
				->pluck('full_name', 'users.id');
			
			
			$activity = DB::table('activities')
				->leftJoin('phases', 'activities.id', '=', 'phases.activity_id')
				->leftJoin('tasks', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('users', 'users.id', '=', 'activities.manager')
				->where('activities.id', '=', $activity_id)
				->groupBy('activities.id')
				->select(DB::raw("activities.id as activity_id,
			activities.name as activity_name,
			activities.code as activity_code,
			activities.status as activity_status,
			activities.manager as activity_manager,
			activities.deputy as activity_deputy,
			activities.department_id as activity_department_id,
			activities.service_id as activity_service_id,
			activities.start_p as activity_start_p,
			activities.start_r as activity_start_r,
			activities.end_p as activity_end_p,
			activities.end_r as activity_end_r,
			activities.enveloppe as activity_enveloppe,
			activities.days_p as activity_days_p,
			activities.days_p_total as activity_days_p_total,
			activities.days_r as activity_days_r,
			activities.charges_investment as activity_charges_investment,
			activities.charges_operation as activity_charges_operation,
			activities.opex_p as activity_opex_p,
			activities.opex_p_total as activity_opex_p_total,
			activities.opex_r as activity_opex_r, $fullName"))
				->first();
			
			//Test if activity exists
			if (!isset($activity))
				return redirect('activities');
			
			//Format date and values
			//SJL to check
			$activity->activity_budget_total = $activity->activity_opex_p_total + $activity->activity_charges_investment + $activity->activity_charges_operation;
			
			$activity->activity_days_p_total = number_format($activity->activity_days_p_total, 3, ',', ' ');
			$activity->activity_days_p = number_format($activity->activity_days_p, 3, ',', ' ');
			$activity->activity_days_r = number_format($activity->activity_days_r, 3, ',', ' ');
			$activity->activity_enveloppe = number_format($activity->activity_enveloppe, 3, ',', ' ');
			
			$activity->activity_charges_investment = number_format($activity->activity_charges_investment, 0, ',', ' ');
			$activity->activity_charges_operation = number_format($activity->activity_charges_operation, 0, ',', ' ');
			$activity->activity_opex_p = number_format($activity->activity_opex_p, 0, ',', ' ');
			$activity->activity_opex_p_total = number_format($activity->activity_opex_p_total, 0, ',', ' ');
			$activity->activity_opex_r = number_format($activity->activity_opex_r, 0, ',', ' ');
			$activity->activity_budget_total = number_format($activity->activity_budget_total, 0, ',', ' ');
			
			$countTasks = "IFNULL((select count(ts.id) from tasks as ts
			where ts.phase_id = phases.id
			and ts.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)), 0)
			as count_tasks";
			
			$countTasks_active = "IFNULL((select count(ts.id) from tasks as ts
			where ts.phase_id = phases.id
			and ts.status in ($this->status_active)), 0)
			as count_tasks_active";
			
			$countTasks_terminated = "IFNULL((select count(ts.id) from tasks as ts
			where ts.phase_id = phases.id
			and ts.status in ($this->status_terminated)), 0)
			as count_tasks_terminated";
			
			$countTasks_not_validated = "IFNULL((select count(ts.id) from tasks as ts
			where ts.phase_id = phases.id
			and ts.status in ($this->status_not_validated)), 0)
			as count_tasks_not_validated";
			
			$countTasks_active_validated = "IFNULL((select count(ts.id) from tasks as ts
			where ts.phase_id = phases.id
			and ts.status in ($this->status_validated)), 0)
			as count_tasks_validated";
			
			$phases = DB::table('phases')
				->where('phases.activity_id', '=', $activity_id)
				->whereIn('phases.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->select(DB::raw("phases.id as phase_id,
			phases.name as phase_name,
			phases.status as phase_status,
			phases.private as phase_private,
			phases.description as phase_description,
			phases.start_p as phase_start_p,
			phases.start_r as phase_start_r,
			phases.end_p as phase_end_p,
			phases.end_r as phase_end_r,
			phases.days_p as phase_days_p,
			phases.days_p_total as phase_days_p_total,
			phases.days_r as phase_days_r,
			phases.activity_id as activity_id, 
			$countTasks,
			$countTasks_active,
			$countTasks_terminated,
			$countTasks_not_validated,
			$countTasks_active_validated
			"))
				->groupBy('phases.id')
				->get();
			
			//find phase id
			$phaseID = [];
			foreach ($phases as $phase)
				$phaseID[] = $phase->phase_id;
			
			//find phase id
			$all_phase_id = DB::table('phases')
				->where('phases.activity_id', '=', $activity_id)
				->whereIn('phases.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->select(DB::raw("phases.id as phase_id"))
				->groupBy('phases.id')
				->get();
			
			
			$sumAbsences = "IFNULL((select sum(abs.days) from absences as abs
			where tasks.user_id = abs.user_id
			and month(tasks.start_p) = month(abs.date)
			and year(tasks.start_p) = year(abs.date)), 0)
			as sum_absences";
			
			$openDays = "IFNULL((select open_days.days from open_days
			where month(tasks.start_p) = open_days.month
			and year(tasks.start_p) = open_days.year), 0)
			as open_days";
			
			$sumPrevu = "(select sum(t2.days_p) from tasks as t2
			where t2.status = $this->status_active
			and t2.user_id = tasks.user_id
			and year(t2.start_p) = year(tasks.start_p)
			and month(t2.start_p) = month(tasks.start_p))
			as sum_prevu";
			
			$sumRealise = "IFNULL((select sum(wd2.days) from work_days as wd2
			where wd2.user_id = tasks.user_id
			and wd2.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)
			and month(wd2.date) = month(tasks.start_p)
			and year(wd2.date) = year(tasks.start_p)), 0)
			as sum_realise";
			
			$fullNameManager = "CONCAT(u2.last_name, \" \", u2.first_name) AS full_name_manager";
			
			//GET TASK BY ID
			$tasks = DB::table('tasks')
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->leftJoin('task_types', 'task_types.id', '=', 'tasks.task_type_id')
				->leftJoin('users', 'tasks.user_id', '=', 'users.id')
				->leftJoin('users as u2', 'activities.manager', '=', 'u2.id')
				->select(DB::raw("tasks.id as task_id,
			tasks.name as task_name,
			tasks.description as task_description,
			tasks.status as task_status,
			tasks.milestone as task_milestone,
			tasks.start_p as task_start_p,
			tasks.start_r as task_start_r,
			tasks.end_p as task_end_p,
			tasks.end_r as task_end_r,
			tasks.days_p as task_days_p,
			tasks.days_r as task_days_r,
			tasks.phase_id as phase_id,
			tasks.task_type_id as task_type_id,
			tasks.user_id as task_user_id,
			users.trigramme as trigramme,
			task_types.name as task_type_name,
			activities.id as activity_id,
			activities.name as activity_name,
			phases.name as phase_name,
			$sumPrevu, $sumRealise, $fullName, $sumAbsences, $openDays, $fullNameManager"))
				->whereIn('tasks.phase_id', $phaseID)
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->groupBy('tasks.id')
				->orderBy('tasks.start_p', 'asc')
				->orderBy('tasks.task_type_id', 'asc')
				->orderBy('tasks.name', 'asc')
				->orderBy('tasks.user_id', 'asc')
				->get();
			
			//-----------------------------------------
			//CHARGES AND CAPACITIES CALCULATION
			foreach ($tasks as $task) {
				$task->user_capacity = ($task->open_days - $task->sum_absences - $task->sum_realise);
				
				$task->jours_planifiables = $task->open_days - $task->sum_absences - $task->sum_realise - $task->sum_prevu;
				if ($task->jours_planifiables < 0) $task->jours_planifiables = 0;
				
				$task->charge_totale = 0;
				if ($task->user_capacity != 0) $task->charge_totale = $task->sum_prevu / $task->user_capacity * 100;
				if ($task->charge_totale < 0) $task->charge_totale = 0;
				
				$task->display_month = Carbon::parse($task->task_start_p)->format('m');
				$task->display_year = Carbon::parse($task->task_start_p)->format('Y');
			}
			
			$task_types = DB::table('task_types')
				->orderBy('name', 'asc')
				->pluck('name', 'id');
			
			$activities_list = DB::table('activities')
				->where('activities.private', '=', config('constants.private_no'))
				->where('activities.status', '=', $this->status_active)
				->orderBy('activities.code', 'asc')
				->pluck('activities.code', 'id');
			
			$privacies = array(
				config('constants.private_no') => ucfirst(trans('activity.lab_private_no')),
				config('constants.private_yes') => ucfirst(trans('activity.lab_private_yes'))
			);
			$milestones = array(
				config('constants.milestone_no') => ucfirst(trans('activity.lab_milestone_no')),
				config('constants.milestone_yes') => ucfirst(trans('activity.lab_milestone_yes'))
			);
			
			//USER VERIFICATION
			switch ($activity) {
				case auth()->user()->role_id == config('constants.role_agent_id'):
					return redirect('activities');
					break;
				
				case auth()->user()->role_id == config('constants.role_projet_id'):
					if ($activity->activity_manager != auth()->user()->id
						&& $activity->activity_deputy != auth()->user()->id
						|| $activity->activity_status == $this->status_terminated
						|| $activity->activity_status == $this->status_validated)
						return redirect('activities');
					break;
				
				case auth()->user()->role_id == config('constants.role_service_id'):
					if ($activity->activity_service_id != auth()->user()->service_id
						&& $activity->activity_manager != auth()->user()->id
						&& $activity->activity_deputy != auth()->user()->id
						|| $activity->activity_status == $this->status_terminated
						|| $activity->activity_status == $this->status_validated)
						return redirect('activities');
					break;
				
				case auth()->user()->role_id == config('constants.role_directeur_id'):
					if ($activity->activity_department_id != auth()->user()->department_id
						&& $activity->activity_manager != auth()->user()->id
						&& $activity->activity_deputy != auth()->user()->id
						|| $activity->activity_status == $this->status_terminated
						|| $activity->activity_status == $this->status_validated)
						return redirect('activities');
					break;
				
			}
			
			return view('activities.planning',
				compact(
					'current_date',
					'current_year',
					'current_month',
					
					'activity',
					'phases',
					'all_phase_id',
					'tasks',
					'activities_list',
					'all_direction_users',
					'task_types',
					'privacies',
					'milestones',
					'current_date'));
		}
		
		/**
		 * Update Activity
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function update(Request $request)
		{
			//get manager service & department ids
			if ($request->activity_manager) {
				$get_service = User::with('activities')->where('id', '=', $request->activity_manager)->pluck('service_id')->first();
				$get_department = User::with('activities')->where('id', '=', $request->activity_manager)->pluck('department_id')->first();
			} else {
				$get_service = auth()->user()->service_id;
				$get_department = auth()->user()->department_id;
			}
			
			$activity = Activity::find($request->activity_id);
			
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$activity->name = $request->activity_name;
				$activity->manager = $request->activity_manager;
				$activity->deputy = $request->activity_deputy;
				$activity->description = $request->activity_description;
				$activity->code = $request->activity_code;
				
				$activity->private = $request->activity_private;
				$activity->enveloppe = $request->activity_enveloppe;
				$activity->charges_investment = $request->activity_charges_investment;
				$activity->charges_operation = $request->activity_charges_operation;
				$activity->start_p = $request->activity_start_p;
				
				$activity->end_p = $request->activity_end_p;
				
				$activity->date_requested = $request->activity_date_requested;
				$activity->date_limit = $request->activity_date_limit;
				
				$activity->activity_type_id = $request->activity_type_id;
				$activity->priority_id = $request->activity_priority_id;
				
				$activity->department_id = $get_department;
				$activity->service_id = $get_service;
				$activity->business_id = $request->activity_business_id;
				$activity->state_id = $request->activity_state_id;
				
				//SJL à rajouter dans la modale
				//$activity->charges_investment = $request->activity_charges_investment;
				//$activity->charges_operation = $request->activity_charges_operation;
				
				
				if ($activity->isDirty()) {
					$activity->save();
					
					LogActivity::dbCommit(__METHOD__, __LINE__);
					
					LogActivity::addToLog(trans(self::subject), __function__, $activity);
					
					flashy()->success(trans('flash_message.activity_update'));
				} else {
					
					LogActivity::dbRollback(__METHOD__, __LINE__);
					
					flashy()->info(trans('flash_message.not_dirty'));
				}
				
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.activity_update_error'));
			}
			
			return redirect()->back();
		}
		
		public function getPhases($activity_id, $statuses = null)
		{
			
			if (!$statuses) {
				$statuses = [
					$this->status_active,
					$this->status_terminated,
					$this->status_not_validated,
					$this->status_validated];
			}
			
			$getPhases = DB::table('phases')
				->where('phases.activity_id', '=', $activity_id)
				->whereIn('phases.status', $statuses)
				->select(DB::raw("
					phases.id as id,
					phases.status as status
					"))
				->get()->toArray();
			
			return $getPhases;
		}
		
		/**
		 * Terminate(change status) P/P/T/WD
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
		 */
		public function terminate(Request $request)
		{
			//-----------------------------------------
			//SQL
			$activity_id = $request->activity_id;
			
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$status_activity = Activity::get_status($activity_id);
				$update_activity = false;
				
				if (session()->get('cra_validate') == 0) {
					
					if (($status_activity == $this->status_active) ||
						($status_activity == $this->status_terminated) ||
						($status_activity == $this->status_not_validated)) {
						
						$getPhases = $this->getPhases($activity_id, [
							$this->status_active,
							$this->status_terminated,
							$this->status_not_validated]);
						
						if ($getPhases != null) {
							foreach ($getPhases as $phase) {
								$phase_id = $phase->id;
								
								$this->phase_controller->terminate($request, $phase_id, $activity_id);
								$update_activity = true;
							}
						}
					}
				} else {
					
					if (($status_activity == $this->status_active) ||
						($status_activity == $this->status_not_validated) ||
						($status_activity == $this->status_validated)) {
						
						$getPhases = $this->getPhases($activity_id, [
							$this->status_active]);
						
						if ($getPhases != null) {
							foreach ($getPhases as $phase) {
								$phase_id = $phase->id;
								
								$this->phase_controller->terminate($request, $phase_id, $activity_id);
								$update_activity = true;
							}
						}
					}
				}
				
				$new_activity_status = Activity::get_new_status($activity_id);
				
				if ($new_activity_status < 0) {
					if (session()->get('cra_validate') == 0)
						$new_activity_status = $this->status_terminated;
					else
						$new_activity_status = $this->status_validated;
				}
				
				if ($new_activity_status != $status_activity) {
					Activity::update_status_simple($activity_id, $new_activity_status);
				}
				
				if ($update_activity == true) {
					Activity::update_activity_P($activity_id);
				}
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, Activity::find($activity_id));
				
				flashy()->success(trans('flash_message.activity_terminate'));
				
			} catch (\Exception $ex) {
				
				flashy()->error(trans('flash_message.activity_terminate_error'));
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
			}
			
			return redirect('activities');
		}
		
		/**
		 * Activate(change status) Activity
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
		 */
		public function activate(Request $request)
		{
			//-----------------------------------------
			//SQL
			
			$activity_id = $request->activity_id;
			
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$status_activity = Activity::get_status($activity_id);
				$update_activity = false;
				$update_activity_status = false;
				
				if (session()->get('cra_validate') == 0) {
					
					if (($status_activity == $this->status_terminated) ||
						($status_activity == $this->status_not_validated) ||
						($status_activity == $this->status_validated)) {
						
						$getPhases = $this->getPhases($activity_id, [
							$this->status_terminated,
							$this->status_not_validated]);
						
						if ($getPhases != null) {
							foreach ($getPhases as $phase) {
								$phase_id = $phase->id;
								
								$this->phase_controller->activate($request, $phase_id, $activity_id);
								$update_activity = true;
							}
						}
					}
				} else {
					
					if (($status_activity == $this->status_terminated) ||
						($status_activity == $this->status_not_validated) ||
						($status_activity == $this->status_validated)) {
						
						$getPhases = $this->getPhases($activity_id, [
							$this->status_terminated,
							$this->status_not_validated]);
						
						if ($getPhases != null) {
							foreach ($getPhases as $phase) {
								$phase_id = $phase->id;
								
								$this->phase_controller->activate($request, $phase_id, $activity_id);
								$update_activity = true;
							}
						}
					}
				}
				
				//UPDATE ACTIVITY
				$new_activity_status = $this->status_active;
				
				if ($new_activity_status != $status_activity) Activity::update_status_simple($activity_id, $new_activity_status);
				
				if ($update_activity == true) Activity::update_activity_P($activity_id);
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, Activity::find($activity_id));
				
				flashy()->success(trans('flash_message.activity_activate'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.activity_activate_error'));
			}
			
			return redirect('activities');
		}
		
		/**
		 * Change privacy activity
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function privacy(Request $request)
		{
			$activity_obj = Activity::find($request->activity_id);
			
			if ($activity_obj->private == config('constants.private_no')) {
				$activity = DB::table('activities')
					->where('activities.id', '=', $request->activity_id)
					->update(['activities.private' => config('constants.private_yes')]);
				$action = __function__ . 'toprivate';
			} else {
				$activity = DB::table('activities')
					->where('activities.id', '=', $request->activity_id)
					->update(['activities.private' => config('constants.private_no')]);
				$action = __function__ . 'topublic';
			}
			
			LogActivity::addToLog(trans(self::subject), $action, Activity::find($request->activity_id));
			return redirect()->back();
		}
		
		/**
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 * Delete(change status) P/P/T/WD
		 */
		public function destroy(Request $request)
		{
			//-----------------------------------------
			//SQL
			
			$activity_id = $request->activity_id;
			
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$getPhases = $this->getPhases($activity_id);
				
				if ($getPhases != null) {
					foreach ($getPhases as $phase) {
						$phase_id = $phase->id;
						
						$this->phase_controller->destroy($request, $phase_id, $activity_id);
					}
				}
				
				Activity::update_status_simple($activity_id, $this->status_deleted);
				Activity::update_activity_P($activity_id);
				Activity::update_activity_R($activity_id);
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, Activity::find($activity_id));
				
				flashy()->success(trans('flash_message.activity_delete'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.activity_delete_error'));
			}
			return redirect()->back();
		}
	}
