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
	
	class TaskController extends Controller
	{
		const subject = 'activityLog.Task';
		
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
		 * Tasks index page/Saisie des temps
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function index()
		{
			//=========================================================================
			//GLOBALS VARIABLES
			
			//-----------------------------------------
			//SET START OF WEEK //SJL : UTILE ?
			Carbon::setWeekEndsAt(Carbon::FRIDAY);
			
			//-----------------------------------------
			//SET CURRENT DATE OR GET GATE FROM FORM
			$current_date = $this->current_date;
			$current_month = $this->current_month;
			$current_year = $this->current_year;
			
			//-----------------------------------------
			//SQL
			$current_user = auth()->user()->id;
			$current_user_full_name = auth()->user()->last_name . " " . auth()->user()->first_name;
			//=========================================================================
			
			
			//=========================================================================
			//START TASKS INFO FOR CURRENT MONTH
			$taskWorkDaysMonth = "IFNULL((select sum(wdM.days) from work_days wdM where month(wdM.date) = '$current_month' and year(wdM.date) = '$current_year' and wdM.task_id = t.id and wdM.user_id = u_task.id and wdM.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)), 0) as task_work_days_month";
			
			//-----------------------------------------
			//MANAGER FULL NAME (#addHours)
			$fullName = "u_activity.id as userID, CONCAT(u_activity.last_name, \" \", u_activity.first_name) AS full_name_manager";
			
			$currentTasks = DB::table('tasks as t')
				->leftJoin('phases', 'phases.id', '=', 't.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->leftJoin('task_types', 't.task_type_id', '=', 'task_types.id')
				->leftJoin('users as u_task', 't.user_id', '=', 'u_task.id')
				->leftJoin('users as u_activity', 'activities.manager', '=', 'u_activity.id')
				->leftJoin('priorities', 'activities.priority_id', '=', 'priorities.id')
				->select(DB::raw("t.id as task_id,
            t.name as task_name,
            t.description as task_description,
            t.status as task_status,
            t.milestone as task_milestone,
            t.start_p as task_start_p,
            t.start_r as task_start_r,
            t.days_p as task_days_p,
            t.days_r as task_days_r,
            t.end_p as task_end_p,
            t.end_r as task_end_r,
            t.user_id as task_user_id,
            task_types.name as task_type_name,
            activities.name as activity_name,
            activities.manager as activity_manager,
            activities.deputy as activity_deputy,
            phases.id as phase_id,
            phases.name as phase_name,
            u_activity.trigramme as activity_manager_tri,
            u_task.trigramme as task_user_tri,
            priorities.id as priority_id,
            priorities.name as priority_name,
            activities.id as activity_id,
            activities.code as activity_code,
            $fullName, $taskWorkDaysMonth"))
				->whereIn('t.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereDate('t.start_p', '>=', Carbon::parse($current_date)->startOfMonth())
				->whereDate('t.start_p', '<=', Carbon::parse($current_date)->endOfMonth())
				->where('t.user_id', '=', $current_user)
				->groupBy('t.id')
				->orderBy('activities.code', 'asc')
				->orderBy('t.id', 'asc')
				->get();
			
			//-----------------------------------------
			//OPEN_DAYS
			$current_open_days = DB::table('open_days')
				->select(DB::raw('days'))
				->where('open_days.month', '=', $current_month)
				->where('open_days.year', '=', $current_year)
				->pluck('days');
			
			$open_days_month = (int)$current_open_days[0];
			
			//-----------------------------------------
			//ABSENCES
			$user_total_absence = DB::table('absences')
				->select(DB::raw('IFNULL(sum(absences.days), 0) as user_total_absence_month'))
				->where('absences.user_id', '=', auth()->user()->id)
				->whereMonth('absences.date', '=', $current_month)
				->whereYear('absences.date', '=', $current_year)
				->pluck('user_total_absence_month');
			
			$user_total_absence_month = (float)$user_total_absence[0];
			
			//-----------------------------------------
			//CALCULS TOTAUX
			$user_jours_max_month = 0;
			$user_total_restant_month = 0;
			$user_total_prevu_total_month = 0;
			$user_total_realise = 0;
			$user_total_realise_month = 0;
			$user_total_jours_restants_month = 0;
			
			if ($currentTasks) {
				foreach ($currentTasks as $currentTask) {
					if (($currentTask->task_status == $this->status_active) ||
						($currentTask->task_status == $this->status_terminated) ||
						($currentTask->task_status == $this->status_not_validated)) {
						$user_total_restant_month += $currentTask->task_days_p;
					}
					
					if (($currentTask->task_status == $this->status_active) ||
						($currentTask->task_status == $this->status_terminated) ||
						($currentTask->task_status == $this->status_not_validated) ||
						($currentTask->task_status == $this->status_validated)) {
						$user_total_prevu_total_month += $currentTask->task_days_p;
						$user_total_realise += $currentTask->task_days_r;
						$user_total_realise_month += $currentTask->task_work_days_month;
					}
				}
				
				$user_jours_max_month = $open_days_month - $user_total_absence_month;
				
				$user_total_jours_restants_month = $user_jours_max_month - $user_total_realise_month;
				if ($user_total_jours_restants_month < 0) $user_total_jours_restants_month = 0;
			}
			//END TASKS INFO FOR CURRENT MONTH
			//=========================================================================
			
			
			//=========================================================================
			//START ENTITY TASKS INFO FOR CURRENT MONTH
			$entity_taskWorkDaysMonth = "IFNULL((select sum(wdM.days) from work_days wdM where month(wdM.date) = '$current_month' and year(wdM.date) = '$current_year' and wdM.task_id = t.id and wdM.user_id = u_task.id and wdM.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)), 0) as entity_task_work_days_month";
			
			//MANAGER FULL NAME (#addHours)
			$fullNameAffected = "u_task.id as userID, CONCAT(u_task.last_name, \" \", u_task.first_name) AS full_name_affected";
			
			
			$entityTasks = DB::table('tasks as t')
				->leftJoin('phases', 'phases.id', '=', 't.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->leftJoin('task_types', 't.task_type_id', '=', 'task_types.id')
				->leftJoin('users as u_task', 't.user_id', '=', 'u_task.id')
				->leftJoin('users as u_activity', 'activities.manager', '=', 'u_activity.id')
				->leftJoin('priorities', 'activities.priority_id', '=', 'priorities.id')
				->select(DB::raw("t.id as task_id,
            t.name as task_name,
            t.description as task_description,
            t.status as task_status,
            t.milestone as task_milestone,
            t.start_p as task_start_p,
            t.start_r as task_start_r,
            t.days_p as task_days_p,
            t.days_r as task_days_r,
            t.end_p as task_end_p,
            t.end_r as task_end_r,
            task_types.name as task_type_name,
            activities.name as activity_name,
            activities.manager as activity_manager,
            phases.id as phase_id,
            phases.name as phase_name,
            u_activity.trigramme as activity_manager_tri,
            u_task.trigramme as task_user_tri,
            priorities.id as priority_id,
            priorities.name as priority_name,
            activities.id as activity_id,
            activities.code as activity_code,
            $fullNameAffected, $entity_taskWorkDaysMonth"))
				->whereIn('t.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereDate('t.start_p', '>=', Carbon::parse($current_date)->startOfMonth())
				->whereDate('t.start_p', '<=', Carbon::parse($current_date)->endOfMonth());
			
			switch ($entityTasks) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$entityTasks = $entityTasks->where('u_task.department_id', '=', auth()->user()->department_id);
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$entityTasks = $entityTasks->where('u_task.service_id', '=', auth()->user()->service_id);
					break;
				default:
					$entityTasks = $entityTasks->where('u_task.id', '=', auth()->user()->id);
					break;
			}
			
			$entityTasks = $entityTasks
				->groupBy('t.id')
				->orderBy('activities.code', 'asc')
				->orderBy('t.id', 'asc')
				->get();
			
			// COUNT PPL IN ENTITY
			$count_ppl = DB::table('users')
				->whereIn('users.status', [$this->status_active]);
			
			switch ($count_ppl) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$count_ppl = $count_ppl->where('users.department_id', '=', auth()->user()->department_id);
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$count_ppl = $count_ppl->where('users.service_id', '=', auth()->user()->service_id);
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$count_ppl = $count_ppl->where('users.id', '=', auth()->user()->id);
					break;
				default:
					$count_ppl = $count_ppl->where('users.id', '=', auth()->user()->id);
					break;
			}
			
			$count_ppl = $count_ppl
				->distinct()->count();
			
			//-----------------------------------------
			//OPEN_DAYS
			/*$entity_current_open_days = DB::table('open_days')
				->select(DB::raw('days'))
				->where('open_days.month', '=', $current_month)
				->where('open_days.year', '=', $current_year)
				->pluck('days');*/
			
			$entity_open_days_month = (int)$current_open_days[0] * $count_ppl;
			
			//-----------------------------------------
			//ABSENCES
			$entity_total_absence = DB::table('absences')
				->leftJoin('users', 'users.id', '=', 'absences.user_id')
				->select(DB::raw('IFNULL(sum(absences.days), 0) as entity_total_absences_month'))
				->whereMonth('absences.date', '=', $current_month)
				->whereYear('absences.date', '=', $current_year);
			
			switch ('adidi') {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$entity_total_absence = $entity_total_absence
						->where('users.department_id', '=', auth()->user()->department_id);
					break;
				case auth()->user()->role_id == config('constants.role_service_id'):
					$entity_total_absence = $entity_total_absence
						->where('users.service_id', '=', auth()->user()->service_id);
					break;
			}
			
			$entity_total_absence = $entity_total_absence
				->pluck('entity_total_absences_month');
			
			$entity_total_absence_month = (float)$entity_total_absence[0];
			
			
			//-----------------------------------------
			//CALCULS TOTAUX
			$entity_jours_max_month = 0;
			$entity_total_restant_month = 0;
			$entity_total_prevu_total_month = 0;
			$entity_total_realise = 0;
			$entity_total_realise_month = 0;
			$entity_total_jours_restants_month = 0;
			
			if ($entityTasks) {
				foreach ($entityTasks as $entityTask) {
					if (($entityTask->task_status == $this->status_active) ||
						($entityTask->task_status == $this->status_terminated) ||
						($entityTask->task_status == $this->status_not_validated)) {
						$entity_total_restant_month += $entityTask->task_days_p;
					}
					
					if (($entityTask->task_status == $this->status_active) ||
						($entityTask->task_status == $this->status_terminated) ||
						($entityTask->task_status == $this->status_not_validated) ||
						($entityTask->task_status == $this->status_validated)) {
						$entity_total_prevu_total_month += $entityTask->task_days_p;
						$entity_total_realise += $entityTask->task_days_r;
						$entity_total_realise_month += $entityTask->entity_task_work_days_month;
					}
				}
				
				$entity_jours_max_month = $entity_open_days_month - $entity_total_absence_month;
				
				$entity_total_jours_restants_month = $entity_jours_max_month - $entity_total_realise_month;
				if ($entity_total_jours_restants_month < 0) $entity_total_jours_restants_month = 0;
			}
			//END ENTITY TASKS INFO FOR CURRENT MONTH
			//=========================================================================
			
			
			//=========================================================================
			//START TASKS INFO FOR PREVIOUS MONTHES
			// $taskWorkDaysOld = "IFNULL((select sum(wdO.days) from work_days wdO where wdO.date < '$current_date_start_month' and wdO.task_id = t.id and wdO.user_id = t.user_id and wdO.status in ($this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated)), 0) as 'task_work_days_old'";
			
			$oldTasks = DB::table('tasks as t')
				->leftJoin('phases', 'phases.id', '=', 't.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->leftJoin('task_types', 't.task_type_id', '=', 'task_types.id')
				->leftJoin('users as u', 't.user_id', '=', 'u.id')
				->leftJoin('users as u_activity', 'activities.manager', '=', 'u_activity.id')
				->leftJoin('priorities', 'activities.priority_id', '=', 'priorities.id')
				->select(DB::raw("t.id as task_id,
            t.name as task_name,
            t.description as task_description,
            t.status as task_status,
            t.milestone as task_milestone,
            t.start_p as task_start_p,
            t.start_r as task_start_r,
            t.days_p as task_days_p,
            t.days_r as task_days_r,
            t.end_p as task_end_p,
            t.end_r as task_end_r,
            t.user_id as task_user_id,
            task_types.name as task_type_name,
            activities.name as activity_name,
            activities.manager as activity_manager,
            activities.deputy as activity_deputy,
            phases.id as phase_id,
            phases.name as phase_name,
            u_activity.trigramme as activity_manager_tri,
            u.trigramme as task_user_tri,
            priorities.id as priority_id,
            priorities.name as priority_name,
            activities.id as activity_id,
            activities.code as activity_code,
            $fullName"))
				->whereIn('t.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereDate('t.start_p', '<', Carbon::parse($current_date)->startOfMonth())
				->where('t.user_id', '=', $current_user)
				->groupBy('t.id')
				->orderBy('t.start_p', 'desc')
				->orderBy('activities.code', 'asc')
				->orderBy('t.id', 'asc')
				->get();
			
			//-----------------------------------------
			//ABSENCES
			$user_total_absence = DB::table('absences')
				->select(DB::raw('IFNULL(sum(absences.days), 0) as user_total_absence_old'))
				->where('absences.user_id', '=', $current_user)
				->where('absences.date', '<', Carbon::parse($current_date)->startOfMonth())
				->pluck('user_total_absence_old');
			
			$user_total_absence_old = (float)$user_total_absence[0];
			
			//-----------------------------------------
			//CALCULS TOTAUX
			$user_total_prevu_total_old = 0;
			$user_total_realise_old = 0;
			$user_total_restant_old = 0;
			
			if ($oldTasks) {
				foreach ($oldTasks as $oldTask) {
					if (($oldTask->task_status == $this->status_active) ||
						($oldTask->task_status == $this->status_terminated) ||
						($oldTask->task_status == $this->status_not_validated)) {
						$user_total_restant_old += $oldTask->task_days_p;
					}
					
					if (($oldTask->task_status == $this->status_active) ||
						($oldTask->task_status == $this->status_terminated) ||
						($oldTask->task_status == $this->status_not_validated) ||
						($oldTask->task_status == $this->status_validated)) {
						$user_total_prevu_total_old += $oldTask->task_days_p;
						$user_total_realise_old += $oldTask->task_days_r;
					}
				}
			}
			//END TASKS INFO FOR PREVIOUS MONTHES
			//=========================================================================
			
			
			//=========================================================================
			//START COMMON INFOS
			
			//-----------------------------------------
			//GET TASK TYPES (for FORMS)
			$task_types = DB::table('task_types')
				->orderBy('name', 'asc')
				->pluck('name', 'id');
			
			$milestones = array(
				config('constants.milestone_no') => ucfirst(trans('activity.lab_milestone_no')),
				config('constants.milestone_yes') => ucfirst(trans('activity.lab_milestone_yes'))
			);
			
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
			
			//-----------------------------------------
			//GET (public + active) ACTIVITIES (for FORMS)
			$activities_list = DB::table('activities')
				->where('activities.status', '=', config('constants.status_active'))
				->where('activities.private', '=', config('constants.private_no'))
				->orderBy('activities.code', 'asc')
				->pluck('activities.code', 'id');
			
			//=========================================================================
			//CALL VIEW
			return view('tasks.task_index',
				compact(
					'current_user_full_name',
					'current_date',
					'current_year',
					'current_month',
					
					'currentTasks',
					'open_days_month',
					'user_total_absence_month',
					'user_total_prevu_total_month',
					'user_total_restant_month',
					'user_total_realise',
					'user_total_realise_month',
					'user_total_jours_restants_month',
					
					'oldTasks',
					'user_total_absence_old',
					'user_total_prevu_total_old',
					'user_total_restant_old',
					'user_total_realise_old',
					
					'task_types',
					'milestones',
					'activities_list',
					'all_direction_users',
					
					'entityTasks',
					'entity_open_days_month',
					'entity_total_absence_month',
					'entity_total_realise',
					'entity_total_realise_month',
					'entity_total_restant_month',
					'entity_total_prevu_total_month'
				));
		}
		
		
		/**
		 * Get open/active phases for create public task form
		 * @param $activity_id
		 * @return array
		 */
		public function getPhases($activity_id)
		{
			//-----------------------------------------
			//SQL
			$this->status_active = config('constants.status_active');
			$private_no = config('constants.private_no');
			
			$phases_list = DB::table('phases')
				->where('phases.activity_id', '=', $activity_id)
				->whereIn('phases.status', [$this->status_active])
				->where('phases.private', '=', $private_no)
				->orderBy('phases.name')
				->pluck('phases.name', 'id');
			
			return ['phases_list' => $phases_list];
		}
		
		/**
		 * Create Phases
		 * @param Request $request
		 * @param $phase_id
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function create(Request $request)
		{
			/*Get PhaseID + Current Phase Code*/
			$phase_id = (int)$request->phase_id;
			$activity_id = (int)$request->activity_id;

			$phase = Phase::find($phase_id);

			$numberSelect = intval($request->numberSelect);
			$affected_users = json_decode($request->user_id_values);
			
			$is_updatable = false;
			
			$ori_start_p = Carbon::parse($request->task_start_p);
			$ori_start_p_first = Carbon::parse($request->task_start_p)->firstOfMonth();
			
			if ($request->task_end_p) $ori_end_p = Carbon::parse($request->task_end_p);
			else $ori_end_p = Carbon::parse($request->task_start_p)->endOfMonth();
			
			$ori_end_p_first = Carbon::parse($ori_end_p)->firstOfMonth();
			
			/*CREATE 1 TASK FOR EACH SELECTED*/
			try {
				/*
				 * Begin DB transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				/*Create task for each selected user*/
				if ($request->user_id_values) {
					foreach ($affected_users as $affected_user) {
						for ($occurence = 1; $occurence <= $numberSelect; $occurence++) {
							/*SET START DATE TO REQUEST START_P*/
							
							if ($occurence == 1) {
								$date_start = Carbon::parse($ori_start_p);
								$date_end = Carbon::parse($ori_end_p);
							} else {
								$date_start = Carbon::parse($ori_start_p_first)->addMonth($occurence - 1);
								$date_end = Carbon::parse($ori_end_p_first)->addMonth($occurence - 1)->endOfMonth();
							}
							
							$task = new Task();
							$task->name = $request->task_name;
							$task->task_type_id = $request->task_type_id;
							$task->description = $request->task_description;
							$task->start_p = $date_start;
							$task->end_p = $date_end;
							$task->days_p = $request->task_days_p ? $request->task_days_p : 0;
							$task->user_id = $affected_user;
							$task->milestone = $request->task_milestone;
							$task->status = 0;
							$task->phases()->associate($phase);
							$task->save();
							
							$is_updatable = true;
							
							LogActivity::addToLog(trans(self::subject), __function__, $task);
						}
					}
				} else {
					for ($occurence = 1; $occurence <= $numberSelect; $occurence++) {
						/*SET START DATE TO REQUEST START_P*/
						
						if ($occurence == 1) {
							$date_start = Carbon::parse($ori_start_p);
							$date_end = Carbon::parse($ori_end_p);
						} else {
							$date_start = Carbon::parse($ori_start_p_first)->addMonth($occurence - 1);
							$date_end = Carbon::parse($ori_end_p_first)->addMonth($occurence - 1)->endOfMonth();
						}
						
						$task = new Task();
						$task->name = $request->task_name;
						$task->task_type_id = $request->task_type_id;
						$task->description = $request->task_description;
						$task->start_p = $date_start;
						$task->end_p = $date_end;
						$task->days_p = $request->task_days_p ? $request->task_days_p : 0;
						$task->user_id = $request->public == 0 ? null : auth()->user()->id;
						$task->milestone = $request->public == 1 ? 0 : $request->task_milestone;
						$task->phases()->associate($phase);
						$task->save();
						
						$is_updatable = true;
						
						LogActivity::addToLog(trans(self::subject), __function__, $task);
					}
					
				}
				
				if ($is_updatable) {
					Phase::update_phase_P($phase_id);
					$status_phase = Phase::get_status($phase_id);// On vérifie le status de la phase suite à la création d'une tâche
					
					if (($status_phase == $this->status_terminated) ||
						($status_phase == $this->status_not_validated) ||
						($status_phase == $this->status_validated)) {
						
						$active_tasks_count = Phase::count_tasks_by_status($phase_id, [$this->status_active]); //si la phase n'est pas active on la réactive
						
						if ($active_tasks_count > 0) Phase::update_status_simple($phase_id, $this->status_active);
					}

					Activity::update_activity_P($activity_id);
					$status_activity = Activity::get_status($activity_id); // On vérifie le status de l'activité suite à la MAJ du status de la phase
					if (($status_activity == $this->status_terminated) ||
						($status_activity == $this->status_not_validated) ||
						($status_activity == $this->status_validated)) {
						
						$active_phases_count = Activity::count_phases_by_status($activity_id, [$this->status_active]); //si l'activité n'est pas active on la réactive 
						
						if ($active_phases_count > 0) Activity::update_status_simple($activity_id, $this->status_validated);
					}					
				}
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				flashy()->success(trans('flash_message.task_create'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.task_create_error'));
			}
			return redirect()->back();
		}
		
		/**
		 * @param Request $request
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function show(Request $request)
		{
			//
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
		 * Update Task
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function update(Request $request)
		{
			$task_id = $request->task_id;
			$phase_id = $request->phase_id;
			$activity_id = $request->activity_id;
			
			$task = Task::find($task_id);
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$task->name = $request->task_name;
				$task->task_type_id = $request->task_type_id;
				$task->description = $request->task_description;
				$task->start_p = $request->task_start_p;
				if ($request->task_end_p) {
					$task->end_p = $request->task_end_p;
				} else {
					$task->end_p = Carbon::parse($request->task_start_p)->endOfMonth();
				}
				$task->days_p = $request->task_days_p ? $request->task_days_p : 0;
				$task->user_id = $request->task_user_id;
				$task->milestone = $request->task_milestone;
				
				if ($task->isDirty()) {
					
					$task->update();
					
					LogActivity::addToLog(trans(self::subject), __function__, $task);
					
					Task::update_task_P($task_id);
					Phase::update_phase_P($phase_id);
					Activity::update_activity_P($activity_id);
					
					LogActivity::dbCommit(__METHOD__, __LINE__);
					flashy()->success(trans('flash_message.task_update'));
					
				} else {
					LogActivity::dbRollback(__METHOD__, __LINE__);
					flashy()->info(trans('flash_message.not_dirty'));
				}
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				flashy()->error(trans('flash_message.task_update_error'));
			}
			return redirect()->back();
		}
		
		public function prepareIDs(Request $request = null, $task_id_in = null, $phase_id_in = null, $activity_id_in = null)
		{
			if ($task_id_in == null) {
				$task_id = $request->task_id;
				$phase_id = $request->phase_id;
				$activity_id = $request->activity_id;
				$come_from_function = false;
			} else {
				$task_id = $task_id_in;
				$phase_id = $phase_id_in;
				$activity_id = $activity_id_in;
				$come_from_function = true;
			}
			
			return [
				"task_id" => $task_id,
				"phase_id" => $phase_id,
				"activity_id" => $activity_id,
				"come_from_function" => $come_from_function
			];
			
		}
		
		public function getWDs($task_id, $statuses = null)
		{
			if (!$statuses)
			{
				$statuses = [
					$this->status_active,
					$this->status_terminated,
					$this->status_not_validated,
					$this->status_validated];
			}
			
			$getWDs = DB::table('work_days')
				->where('work_days.task_id', '=', $task_id)
				->whereIn('work_days.status', $statuses)
				->select(DB::raw("
					work_days.id as id,
					work_days.status as status
					"))
				->get()->toArray();
			
			return $getWDs;
		}


		public function getTasksToDeal($tasks_id, $statuses = null)
		{
			if (!$statuses)
			{
				$statuses = [
					$this->status_active,
					$this->status_terminated,
					$this->status_not_validated,
					$this->status_validated];
			}

			$getTasksToDeal = DB::table('tasks')
				->whereIn('tasks.id', $tasks_id)
				->whereIn('tasks.status', $statuses)
				->select(DB::raw("
					tasks.id as id,
					tasks.status as status
					"))
				->get()->toArray();
			
			return $getTasksToDeal;
		}


		/**
		 * Delete task
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function destroy(Request $request, $task_id_in = null, $phase_id_in = null, $activity_id_in = null)
		{
			if ($task_id_in == null) {
				$task_id = $request->task_id;
				$phase_id = $request->phase_id;
				$activity_id = $request->activity_id;
				$come_from_function = false;
			} else {
				$task_id = $task_id_in;
				$phase_id = $phase_id_in;
				$activity_id = $activity_id_in;
				$come_from_function = true;
			}
			
			if ($task_id) {
				
				$ids = explode(',', $task_id);
				
				if ($ids) {

					$tmp_u_phases_list = [];
					$tmp_u_activities_list = [];
					try {
						LogActivity::beginTransaction(__METHOD__, __LINE__);
						
						foreach ($ids as $to_del_task_id) {
							
							if ($come_from_function == false) {
								$phase_id = Task::get_phase_id($to_del_task_id);
							}

							$tmp_u_phases_list[] = $phase_id;
							$tmp_u_activities_list[] = $activity_id;							
							
							$getWDs = $this->getWDs($to_del_task_id);

							if ($getWDs != null) {
								foreach ($getWDs as $wd) {
									$wd_id = $wd->id;

									WorkdayController::destroy($request, $wd_id, $to_del_task_id, $phase_id, $activity_id);
								}
							}
							
							Task::update_status_simple($to_del_task_id, $this->status_deleted);
							Task::update_task_P($to_del_task_id);
							Task::update_task_R($to_del_task_id);
							
							LogActivity::addToLog(trans(self::subject), __function__, Task::find($to_del_task_id));
						}

						//MISE A JOUR DES PHASES et ACTIVITES
						if ($come_from_function == false) { // Veut dire qu'on vient directement de la vue
							asort($tmp_u_phases_list);
							$u_phases_list = array_unique($tmp_u_phases_list);
							foreach ($u_phases_list as $u_phase_id) {
								Phase::update_phase_P($u_phase_id);
								Phase::update_phase_R($u_phase_id);
							}

							asort($tmp_u_activities_list);
							$u_activities_list = array_unique($tmp_u_activities_list);
							foreach ($u_activities_list as $u_activity_id) {
								Activity::update_activity_P($u_activity_id);
								Activity::update_activity_R($u_activity_id);
							}
						}

						LogActivity::dbCommit(__METHOD__, __LINE__);
						
						flashy()->success(trans('flash_message.task_delete'));
						
					} catch (\Exception $ex) {
						
						LogActivity::dbRollback(__METHOD__, __LINE__);
						
						flashy()->error(trans('flash_message.task_delete_error'));
					}
				}
			}
			
			return redirect()->back();
		}
		
		/**
		 * Terminate/ change status T/WD
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function terminate(Request $request, $task_id_in = null, $phase_id_in = null, $activity_id_in = null)
		{
			if ($task_id_in == null) {
				$task_id = $request->task_id;
				$phase_id = $request->phase_id;
				$activity_id = $request->activity_id;
				$come_from_function = false;
			} else {
				$task_id = $task_id_in;
				$phase_id = $phase_id_in;
				$activity_id = $activity_id_in;
				$come_from_function = true;
			}

			//Check type of return values
			if (is_array($task_id)) {
				$ids = $task_id;
			} else {
				$ids = explode(',', $task_id);
			}
			
			if (session()->get('cra_validate') == false) {
				$tasks_to_terminate = $this->getTasksToDeal($ids, [
					$this->status_active,
					$this->status_terminated,
					$this->status_not_validated
				]);
			}
			else
			{
				$tasks_to_terminate = $this->getTasksToDeal($ids, [
					$this->status_active
				]);				
			}
			
			$nb_tasks_to_terminate = count($tasks_to_terminate);

			if ($nb_tasks_to_terminate > 0) {

				$tmp_u_phases_list = [];
				$tmp_u_activities_list = [];
				try {
					
					LogActivity::beginTransaction(__METHOD__, __LINE__);
					
					foreach ($tasks_to_terminate as $task) {
						$task_id = $task->id;
						$status_task = $task->status;

						if ($come_from_function == false) {
							$phase_id = Task::get_phase_id($task_id);
						}

						$update_task = false;
						if (session()->get('cra_validate') == false) {

							$getWDs = $this->getWDs($task_id, [
													$this->status_active, 
													$this->status_terminated, 
													$this->status_not_validated]);

							if ($getWDs != null) {
								foreach ($getWDs as $wd) {
									$wd_id = $wd->id;

									WorkDay::update_status_simple($wd_id, $this->status_validated);
									$update_task = true;
								}
							}
						} 
						else 
						{
							$getWDs = $this->getWDs($task_id, [
													$this->status_active]);							

							if ($getWDs != null) {
								foreach ($getWDs as $wd) {
									$wd_id = $wd->id;
									
									WorkDay::update_status_simple($wd_id, $this->status_terminated);
									$update_task = true;
								}
							}
						}
						
						$new_task_status = Task::get_new_status($task_id);

						if ($new_task_status < 0) {
							if (session()->get('cra_validate') == false) 
								$new_task_status = $this->status_validated;
							else
								$new_task_status = $this->status_terminated;
						}

						if ($new_task_status != $status_task) {
							Task::update_status_simple($task_id, $new_task_status);
							$tmp_u_phases_list[] = $phase_id;
							$tmp_u_activities_list[] = $activity_id;							
						}
						
						if ($update_task == true) {
							Task::update_task_P($task_id);
						}
												
						LogActivity::addToLog(trans(self::subject), __function__, Task::find($task_id));
					}
					
					//MISE A JOUR DES PHASES et ACTIVITES
					if ($come_from_function == false) { // Veut dire qu'on vient directement de la vue
						asort($tmp_u_phases_list);
						$u_phases_list = array_unique($tmp_u_phases_list);
						foreach ($u_phases_list as $u_phase_id) {
							Phase::update_phase_P($u_phase_id);
						}

						asort($tmp_u_activities_list);
						$u_activities_list = array_unique($tmp_u_activities_list);
						foreach ($u_activities_list as $u_activity_id) {
							Activity::update_activity_P($u_activity_id);
						}
					}

					//DB commit if no errors
					LogActivity::dbCommit(__METHOD__, __LINE__);
					
					if ($nb_tasks_to_terminate > 1) {
						flashy()->success(trans('flash_message.task_terminate_multi'));
					}
					else
					{
						flashy()->success(trans('flash_message.task_terminate'));
					}
					
				} catch (\Exception $ex) {
					
					LogActivity::dbRollback(__METHOD__, __LINE__);
					
					flashy()->error(trans('flash_message.task_terminate_error'));
				}
			} else {
				flashy()->info(trans('flash_message.not_dirty'));
			}
			
			return redirect()->back();
		}
		
		/**
		 * Activate/ change status T/WD
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function activate(Request $request, $task_id_in = null, $phase_id_in = null, $activity_id_in = null)
		{
			
			if ($task_id_in == null) {
				$task_id = $request->task_id;
				$phase_id = $request->phase_id;
				$activity_id = $request->activity_id;
				$come_from_function = false;
			} else {
				$task_id = $task_id_in;
				$phase_id = $phase_id_in;
				$activity_id = $activity_id_in;
				$come_from_function = true;
			}
			
			//Check type of return values
			if (is_array($task_id)) {
				$ids = $task_id;
			} else {
				$ids = explode(',', $task_id);
			}

			if (session()->get('cra_validate') == false) {
				$tasks_to_activate = $this->getTasksToDeal($ids, [
					$this->status_terminated,
					$this->status_not_validated,
					$this->status_validated
				]);
			}
			else
			{
				$tasks_to_activate = $this->getTasksToDeal($ids, [
					$this->status_terminated,
					$this->status_not_validated,
					$this->status_validated
				]);				
			}
			
			$nb_tasks_to_activate = count($tasks_to_activate);

			if ($nb_tasks_to_activate > 0) 
			{
				
				$tmp_u_phases_list = [];
				$tmp_u_activities_list = [];
				try 
				{
					
					LogActivity::beginTransaction(__METHOD__, __LINE__);

					foreach ($tasks_to_activate as $task) {
						$task_id = $task->id;
						$status_task = $task->status;

						if ($come_from_function == false) {
							$phase_id = Task::get_phase_id($task_id);
						}

						$update_task = false;
						$update_task_status = false;
						
						if (session()->get('cra_validate') == 0) {
							
							$getWDs = $this->getWDs($task_id, [
													$this->status_terminated, 
													$this->status_not_validated]);

							if ($getWDs != null) {
								foreach ($getWDs as $wd) {
									$wd_id = $wd->id;

									WorkDay::update_status_simple($wd_id, $this->status_active);
									$update_task = true;
								}
							}
						} else {
							
							$getWDs = $this->getWDs($task_id, [
													$this->status_terminated, 
													$this->status_not_validated]);

							if ($getWDs != null) {
								foreach ($getWDs as $wd) {
									$wd_id = $wd->id;

									WorkDay::update_status_simple($wd_id, $this->status_active);
									$update_task = true;
								}
							}
						}
						
						$new_task_status = $this->status_active;

						if ($new_task_status != $status_task) {
							Task::update_status_simple($task_id, $new_task_status);
							$tmp_u_phases_list[] = $phase_id;
							$tmp_u_activities_list[] = $activity_id;
						}
						
						if ($update_task == true) Task::update_task_P($task_id);
						
						LogActivity::addToLog(trans(self::subject), __function__, Task::find($task_id));
					}

					//MISE A JOUR DES PHASES ET DES ACTIVITES
					if ($come_from_function == false) { // Veut dire qu'on vient directement de la vue
						asort($tmp_u_phases_list);
						$u_phases_list = array_unique($tmp_u_phases_list);
						foreach ($u_phases_list as $u_phase_id) {
							$status_phase = Phase::get_status($u_phase_id);
							$new_phase_status = Phase::get_new_status($u_phase_id);

							if ($new_phase_status < 0) $new_phase_status = $this->status_active;

							if ($new_phase_status != $status_phase) Phase::update_status_simple($u_phase_id, $new_phase_status);						

							Phase::update_phase_P($u_phase_id);
						}

						asort($tmp_u_activities_list);
						$u_activities_list = array_unique($tmp_u_activities_list);
						foreach ($u_activities_list as $u_activity_id) {
							$status_activity = Activity::get_status($u_activity_id); // On vérifie le status de l'activité suite à la MAJ du status de la phase
							$new_activity_status = Activity::get_new_status($u_activity_id);

							if ($new_activity_status < 0) $new_activity_status = $this->status_active;

							if ($new_activity_status != $status_activity) Activity::update_status_simple($u_activity_id, $new_activity_status);
							
							Activity::update_activity_P($u_activity_id);
						}
					}					
					
					LogActivity::dbCommit(__METHOD__, __LINE__);

					if ($nb_tasks_to_activate > 1) {
						flashy()->success(trans('flash_message.task_activate_multi'));
					}
					else
					{
						flashy()->success(trans('flash_message.task_activate'));
					}

				} 
				catch (\Exception $ex) 
				{
					LogActivity::dbRollback(__METHOD__, __LINE__);
					
					flashy()->error(trans('flash_message.task_activate_error'));
				}
			
			} 
			else 
			{
				flashy()->info(trans('flash_message.not_dirty'));
			}
			
			return redirect()->back();
		}
		
		/**
		 * Copy task
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function copy(Request $request)
		{
			$taskOld = Task::find($request->task_id);

			try {

				LogActivity::beginTransaction(__METHOD__, __LINE__);

				$task = New Task();
				$task->name = $taskOld->name;
				$task->task_type_id = $taskOld->task_type_id;
				$task->description = $taskOld->description;
				$task->start_p = $taskOld->start_p;
				$task->end_p = $taskOld->end_p;
				$task->days_p = $taskOld->days_p;
				$task->days_r = 0;
				$task->opex_p = $taskOld->opex_p;
				$task->opex_r = 0;
				$task->milestone = $taskOld->milestone;
				$task->status = $taskOld->status;
				$task->user_id = $taskOld->user_id;
				$task->phase_id = $taskOld->phase_id;
				$task->save();

				LogActivity::addToLog(trans(self::subject), __function__, $task);

				if($task->days_p != 0 || $task->opex_p != 0)
				{
					$task_id = $task->id;
					$phase_id = $task->phase_id;
					$activity_id = Phase::get_activity_id($phase_id);

					Task::update_task_P($task_id);
					Phase::update_phase_P($phase_id);
					Activity::update_activity_P($activity_id);
				}
								
				LogActivity::dbCommit(__METHOD__, __LINE__);

				flashy()->success(trans('flash_message.task_copy'));
			} catch (\Exception $ex) {

				LogActivity::dbRollback(__METHOD__, __LINE__);

				flashy()->error(trans('flash_message.task_copy_error'));
			}

			return redirect()->back();
		}

		/**
		 * Move multi task to dif activity/phase
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function copyMultiTask(Request $request)
		{
			$new_phase_id = $request->phase_id_copy;
			$new_activity_id = $request->activity_id_copy;
			$is_updatable = false;

			if ($request->task_id) 
			{
				$ids = explode(',', $request->task_id);
				if ($ids) 
				{
					try 
					{
						LogActivity::beginTransaction(__METHOD__, __LINE__);

						foreach ($ids as $task_id) 
						{
							$taskOld = Task::find($task_id);

							$task = New Task();
							$task->name = $taskOld->name;
							$task->description = $taskOld->description;
							$task->status = $taskOld->status;
							$task->milestone = $taskOld->milestone;
							$task->start_p = $taskOld->start_p;
							$task->end_p = $taskOld->end_p;
							$task->days_p = $taskOld->days_p;
							$task->days_r = 0;
							$task->user_id = $taskOld->user_id;
							$task->task_type_id = $taskOld->task_type_id;
							$task->phase_id = (int)$new_phase_id;
							$task->opex_p = $taskOld->opex_p;
							$task->opex_r = 0;

							$task->save();

							$is_updatable = true; 

							LogActivity::addToLog(trans(self::subject), __function__, $taskOld);
							LogActivity::addToLog(trans(self::subject), __function__, $task);


						}
						//UPDATE TARGET PHASE + ACTIVITY
						if($is_updatable) // Si $is_updatable == false c'est qu'aucune tâche n'a été copiée
						{
							// PHASE UPDATE
							Phase::update_phase_P($new_phase_id);

							$status_phase = Phase::get_status($new_phase_id);
							
							if (($status_phase == $this->status_terminated) ||
								($status_phase == $this->status_not_validated) ||
								($status_phase == $this->status_validated)) {
								
								$active_tasks_count = Phase::count_tasks_by_status($new_phase_id, [$this->status_active]); //si la phase n'est pas active on la réactive
								
								if ($active_tasks_count > 0) Phase::update_status_simple($new_phase_id, $this->status_active);
							}						

							// ACTIVITY UPDATE
							Activity::update_activity_P($new_activity_id);
						}	

						LogActivity::dbCommit(__METHOD__, __LINE__);

						flashy()->success(trans('flash_message.task_copy'));
					} catch (\Exception $ex) {

						LogActivity::dbRollback(__METHOD__, __LINE__);

						flashy()->error(trans('flash_message.task_copy_error'));
					}
				}
				else {
						flashy()->info(trans('flash_message.not_dirty'));
					}				
			}
			else {
				flashy()->info(trans('flash_message.not_dirty'));
			}			

			return redirect()->back();
		}
		
		/**
		 * Move task to dif activity/phase
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function moveMultiTask(Request $request)
		{
			$new_phase_id = $request->phase_id_move;
			$new_activity_id = $request->activity_id_move;
			$is_updatable = false;
			
			if ($request->task_id) 
			{
				$ids = explode(',', $request->task_id);
				if ($ids) 
				{
					$tmp_u_phases_list = [];
					$tmp_u_activities_list = [];					
					try 
					{
						LogActivity::beginTransaction(__METHOD__, __LINE__);
						
						foreach ($ids as $id) {
							$task = Task::find($id);
	
							$old_phase_id = $task->phase_id;
							$old_activity_id = Phase::get_activity_id($old_phase_id);
							$tmp_u_phases_list[] = $old_phase_id;
							$tmp_u_activities_list[] = $old_activity_id;

							$task->phase_id = $new_phase_id;

							$task->save();
							
							$is_updatable = true;

							LogActivity::addToLog(trans(self::subject), __function__, $task);
						}

						// UPDATE OLD PHASES - OLD ACTIVITY - NEW PHASES - NEW ACTIVITY
						if ($is_updatable) // Si $is_updatable == false c'est qu'aucune tâche n'a été déplacée
						{
							// OLD PHASE UPDATE
							asort($tmp_u_phases_list);
							$u_phases_list = array_unique($tmp_u_phases_list);
							foreach ($u_phases_list as $u_phase_id) {
								Phase::update_phase_P($u_phase_id);
								Phase::update_phase_R($u_phase_id);

								$status_phase = Phase::get_status($u_phase_id);
								
								if (($status_phase == $this->status_terminated) ||
									($status_phase == $this->status_not_validated) ||
									($status_phase == $this->status_validated)) {
									
									$active_tasks_count = Phase::count_tasks_by_status($u_phase_id, [$this->status_active]); //si la phase n'est pas active on la réactive
									
									if ($active_tasks_count > 0) Phase::update_status_simple($u_phase_id, $this->status_active);
								}								
							}

							// OLD ACTIVITY UPDATE
							asort($tmp_u_activities_list);
							$u_activities_list = array_unique($tmp_u_activities_list);
							foreach ($u_activities_list as $u_activity_id) {
								Activity::update_activity_P($u_activity_id);
								Activity::update_activity_R($u_activity_id);
							}

							// NEW PHASE UPDATE
							Phase::update_phase_P($new_phase_id);
							Phase::update_phase_R($new_phase_id);

							$status_phase = Phase::get_status($new_phase_id);
							
							if (($status_phase == $this->status_terminated) ||
								($status_phase == $this->status_not_validated) ||
								($status_phase == $this->status_validated)) {
								
								$active_tasks_count = Phase::count_tasks_by_status($new_phase_id, [$this->status_active]); //si la phase n'est pas active on la réactive
								
								if ($active_tasks_count > 0) Phase::update_status_simple($new_phase_id, $this->status_active);
							}

							// NEW ACTIVITY UPDATE
							Activity::update_activity_P($new_activity_id);
							Activity::update_activity_R($new_activity_id);
						}
						
						LogActivity::dbCommit(__METHOD__, __LINE__);
						
						flashy()->success(trans('flash_message.task_move'));
					} catch (\Exception $ex) {
						
						LogActivity::dbRollback(__METHOD__, __LINE__);
						
						flashy()->error(trans('flash_message.task_move_error'));
					}
				}
				else {
					flashy()->info(trans('flash_message.not_dirty'));
				}				
			}
			else {
				flashy()->info(trans('flash_message.not_dirty'));
			}			
			
			return redirect()->back();
		}
	}
