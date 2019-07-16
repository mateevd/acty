<?php
	
	namespace App\Http\Controllers;
	
	use App\Helpers\LogActivity;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Hash;
	
	class HomeController extends Controller
	{
		/**
		 * Create a new controller instance.
		 *
		 * @return void
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
			
			$this->status_active = config('constants.status_active');
			$this->status_terminated = config('constants.status_terminated');
			$this->status_not_validated = config('constants.status_not_validated');
			$this->status_validated = config('constants.status_validated');
			$this->status_deleted = config('constants.status_deleted');
			
		}
		
		/**
		 * Show the application dashboard.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function logActivity()
		{
			if (auth()->user()->roles->name == (config('constants.role_admin'))) {
				$current_date = $this->current_date;
				$current_month = $this->current_month;
				$current_year = $this->current_year;
				
				$logs = LogActivity::logActivityLists();
				
				return view('extra.log_activity',
					compact(
						'current_date',
						'current_year',
						'current_month',
						
						'logs'));
			} else {
				return redirect('home');
			}
		}
		
		/**
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showChangePasswordForm()
		{
			return view('extra.configs');
		}
		
		/**
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function changePassword(Request $request)
		{
			if (!(Hash::check($request->get('current-password'), auth()->user()->password))) {
				// The passwords matches
				return redirect()->back()->with("error", trans('app.password_wrong'));
			}
			if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
				//Current password and new password are same
				return redirect()->back()->with("error", trans('app.password_new_is_the_same'));
			}
			
			if (strcmp($request->get('new-password'), $request->get('new-password-confirm')) != 0) {
				//Current password and new password are same
				return redirect()->back()->with("error", trans('app.password_confirmation_is_not_correct'));
			}
			
			//Change Password
			$user = auth()->user();
			$user->password = bcrypt($request->get('new-password'));
			$user->save();
			
			/*Show flashy message*/
			flashy()->success(trans('flash_message.paswword_success'));
			
			return redirect('home');
		}
		
		/**
		 * Show the application dashboard.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function index()
		{
			//-----------------------------------------
			//SET CURRENT DATE OR GET GATE FROM FORM
			$current_date = $this->current_date;
			$current_month = $this->current_month;
			$current_year = $this->current_year;
			
			$dateStartMonth = Carbon::parse($this->current_date)->startOfMonth();
			$dateEndMonth = Carbon::parse($this->current_date)->endOfMonth();
			
			$cra_validate = session()->get('cra_validate');
			
			//-----------------------------------------
			// ACTIVITIES COUNT BY STATUS
			//-----------------------------------------
			//FOR ENTITY
			$entity_activities_count_by_status = DB::table('activities')
				->select(DB::raw("activities.status as status, count(distinct activities.id) as number"))
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereDate('activities.start_p', '<', Carbon::parse($dateStartMonth)->endOfMonth())
				->groupBy('activities.status')
				->orderBy('activities.status');
			
			switch ($entity_activities_count_by_status) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$entity_activities_count_by_status = $entity_activities_count_by_status->where('activities.department_id', '=', auth()->user()->department_id);
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$entity_activities_count_by_status = $entity_activities_count_by_status->where('activities.service_id', '=', auth()->user()->service_id);
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$entity_activities_count_by_status = $entity_activities_count_by_status->where('activities.manager', '=', auth()->user()->id);
					break;
				default:
					$entity_activities_count_by_status = $entity_activities_count_by_status->where('activities.manager', '=', auth()->user()->id);
					break;
			}
			
			$entity_activities_count_by_status = $entity_activities_count_by_status->get();
			
			$entity_activities_running_count = 0;
			$entity_activities_terminated_count = 0;
			$entity_activities_not_validated_count = 0;
			$entity_activities_validated_count = 0;
			
			foreach ($entity_activities_count_by_status as $status_count) {
				if ($status_count->status == $this->status_active) $entity_activities_running_count = $status_count->number;
				
				if ($cra_validate) {
					if ($status_count->status == $this->status_terminated) $entity_activities_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_activities_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_activities_validated_count = $status_count->number;
				} else {
					if ($status_count->status == $this->status_terminated) $entity_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_activities_validated_count += $status_count->number;
				}
			}
			
			//FOR USER
			$user_activities_count_by_status = DB::table('activities')
				->select(DB::raw("activities.status as status, count(distinct activities.id) as number"))
				->where('activities.manager', '=', auth()->user()->id)
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereDate('activities.start_p', '<', Carbon::parse($dateStartMonth)->endOfMonth())
				->groupBy('activities.status')
				->orderBy('activities.status')
				->get();
			
			$user_activities_running_count = 0;
			$user_activities_terminated_count = 0;
			$user_activities_not_validated_count = 0;
			$user_activities_validated_count = 0;
			
			foreach ($user_activities_count_by_status as $status_count) {
				if ($status_count->status == $this->status_active) $user_activities_running_count = $status_count->number;
				
				if ($cra_validate) {
					if ($status_count->status == $this->status_terminated) $user_activities_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_activities_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $user_activities_validated_count = $status_count->number;
				} else {
					if ($status_count->status == $this->status_terminated) $user_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $user_activities_validated_count += $status_count->number;
				}
			}
			
			
			//-----------------------------------------
			// TASKS COUNT BY STATUS
			//-----------------------------------------
			//FOR ENTITY
			$entity_tasks_count_by_status = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status');
			
			switch ($entity_tasks_count_by_status) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$entity_tasks_count_by_status = $entity_tasks_count_by_status->where('activities.department_id', '=', auth()->user()->department_id);
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$entity_tasks_count_by_status = $entity_tasks_count_by_status->where('activities.service_id', '=', auth()->user()->service_id);
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$entity_tasks_count_by_status = $entity_tasks_count_by_status->where('activities.manager', '=', auth()->user()->id);
					break;
				default:
					$entity_tasks_count_by_status = $entity_tasks_count_by_status->where('activities.manager', '=', auth()->user()->id);
					break;
			}
			
			$entity_tasks_count_by_status = $entity_tasks_count_by_status->get();
			
			$entity_tasks_running_count = 0;
			$entity_tasks_terminated_count = 0;
			$entity_tasks_not_validated_count = 0;
			$entity_tasks_validated_count = 0;
			
			foreach ($entity_tasks_count_by_status as $status_count) {
				if ($status_count->status == $this->status_active) $entity_tasks_running_count = $status_count->number;
				
				if ($cra_validate) {
					if ($status_count->status == $this->status_terminated) $entity_tasks_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_tasks_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_tasks_validated_count = $status_count->number;
				} else {
					if ($status_count->status == $this->status_terminated) $entity_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_tasks_validated_count += $status_count->number;
				}
			}
			
			//FOR USER
			$user_tasks_count_by_status = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->where('activities.manager', '=', auth()->user()->id)
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status')
				->get();
			
			$user_tasks_running_count = 0;
			$user_tasks_terminated_count = 0;
			$user_tasks_not_validated_count = 0;
			$user_tasks_validated_count = 0;
			
			foreach ($user_tasks_count_by_status as $status_count) {
				if ($status_count->status == $this->status_active) $user_tasks_running_count = $status_count->number;
				
				if ($cra_validate) {
					if ($status_count->status == $this->status_terminated) $user_tasks_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_tasks_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $user_tasks_validated_count = $status_count->number;
				} else {
					if ($status_count->status == $this->status_terminated) $user_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $user_tasks_validated_count += $status_count->number;
				}
			}
			
			//-----------------------------------------
			// ACTIVITIES COUNT BY STATUS AND ENTITY
			//-----------------------------------------
			//FOR ENTITY
			$entity_activities_count_by_status_and_entity = DB::table('activities')
				->select(DB::raw("activities.status as status, count(distinct activities.id) as number"))
				->leftJoin('phases', 'phases.activity_id', '=', 'activities.id')
				->leftJoin('tasks', 'tasks.phase_id', '=', 'phases.id')
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('activities.status')
				->orderBy('activities.status');
			
			switch ($entity_activities_count_by_status_and_entity) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$entity_activities_count_by_status_and_entity = $entity_activities_count_by_status_and_entity->where('users.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$entity_activities_count_by_status_and_entity = $entity_activities_count_by_status_and_entity->where('users.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$entity_activities_count_by_status_and_entity = $entity_activities_count_by_status_and_entity->where('users.id', '=', auth()->user()->id)->get();
					break;
				default:
					$entity_activities_count_by_status_and_entity = $entity_activities_count_by_status_and_entity->where('users.id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_activities_for_entity_running_count = 0;
			$entity_activities_for_entity_terminated_count = 0;
			$entity_activities_for_entity_not_validated_count = 0;
			$entity_activities_for_entity_validated_count = 0;
			
			foreach ($entity_activities_count_by_status_and_entity as $status_count) {
				if ($status_count->status == $this->status_active) $entity_activities_for_entity_running_count = $status_count->number;
				
				if ($cra_validate) {
					if ($status_count->status == $this->status_terminated) $entity_activities_for_entity_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_activities_for_entity_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_activities_for_entity_validated_count = $status_count->number;
				} else {
					if ($status_count->status == $this->status_terminated) $entity_activities_for_entity_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_activities_for_entity_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_activities_for_entity_validated_count += $status_count->number;
				}
				
			}
			
			//FOR USER
			$user_activities_count_by_status_and_entity = DB::table('activities')
				->leftJoin('phases', 'phases.activity_id', '=', 'activities.id')
				->leftJoin('tasks', 'tasks.phase_id', '=', 'phases.id')
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->where('users.id', '=', auth()->user()->id)
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('activities.status')
				->orderBy('activities.status')
				->select(DB::raw("activities.status as status, count(distinct activities.id) as number"))
				->get();
			
			$user_activities_for_entity_running_count = 0;
			$user_activities_for_entity_terminated_count = 0;
			$user_activities_for_entity_not_validated_count = 0;
			$user_activities_for_entity_validated_count = 0;
			
			foreach ($user_activities_count_by_status_and_entity as $status_count) {
				if ($status_count->status == $this->status_active) $user_activities_for_entity_running_count = $status_count->number;
				
				if ($cra_validate) {
					if ($status_count->status == $this->status_terminated) $user_activities_for_entity_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_activities_for_entity_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $user_activities_for_entity_validated_count = $status_count->number;
				} else {
					if ($status_count->status == $this->status_terminated) $user_activities_for_entity_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_activities_for_entity_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $user_activities_for_entity_validated_count += $status_count->number;
				}
				
			}
			
			//-----------------------------------------
			// TASKS COUNT BY STATUS AND ENTITY
			//-----------------------------------------
			//FOR ENTITY
			$entity_tasks_count_by_status_and_entity = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status');
			
			switch ($entity_tasks_count_by_status_and_entity) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$entity_tasks_count_by_status_and_entity = $entity_tasks_count_by_status_and_entity->where('users.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$entity_tasks_count_by_status_and_entity = $entity_tasks_count_by_status_and_entity->where('users.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$entity_tasks_count_by_status_and_entity = $entity_tasks_count_by_status_and_entity->where('users.id', '=', auth()->user()->id)->get();
					break;
				default:
					$entity_tasks_count_by_status_and_entity = $entity_tasks_count_by_status_and_entity->where('users.id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_tasks_for_entity_running_count = 0;
			$entity_tasks_for_entity_terminated_count = 0;
			$entity_tasks_for_entity_not_validated_count = 0;
			$entity_tasks_for_entity_validated_count = 0;
			
			foreach ($entity_tasks_count_by_status_and_entity as $status_count) {
				if ($status_count->status == $this->status_active) $entity_tasks_for_entity_running_count = $status_count->number;
				
				if ($cra_validate) {
					if ($status_count->status == $this->status_terminated) $entity_tasks_for_entity_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_tasks_for_entity_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_tasks_for_entity_validated_count = $status_count->number;
				} else {
					if ($status_count->status == $this->status_terminated) $entity_tasks_for_entity_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_tasks_for_entity_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_tasks_for_entity_validated_count += $status_count->number;
				}
			}
			
			//FOR USER
			$user_tasks_count_by_status_and_entity = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->where('users.id', '=', auth()->user()->id)
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->get();
			
			$user_tasks_for_entity_running_count = 0;
			$user_tasks_for_entity_terminated_count = 0;
			$user_tasks_for_entity_not_validated_count = 0;
			$user_tasks_for_entity_validated_count = 0;
			
			foreach ($user_tasks_count_by_status_and_entity as $status_count) {
				if ($status_count->status == $this->status_active) $user_tasks_for_entity_running_count = $status_count->number;
				
				if ($cra_validate) {
					if ($status_count->status == $this->status_terminated) $user_tasks_for_entity_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_tasks_for_entity_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $user_tasks_for_entity_validated_count = $status_count->number;
				} else {
					if ($status_count->status == $this->status_terminated) $user_tasks_for_entity_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_tasks_for_entity_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $user_tasks_for_entity_validated_count += $status_count->number;
				}
			}
			
			//-----------------------------------------
			// ABSENCES COUNT
			//-----------------------------------------
			//FOR ENTITY
			$entity_absences_count_by_month = DB::table('absences')
				->select(DB::raw('month(absences.date) as mm, IFNULL(sum(absences.days), 0) as absences_month'))
				->leftJoin('users', 'users.id', '=', 'absences.user_id')
				->whereBetween('absences.date', [Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(+3)->endOfMonth()])
				->groupBy('mm')
				->orderBy('mm');
			
			switch ($entity_absences_count_by_month) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$entity_absences_count_by_month = $entity_absences_count_by_month->where('users.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$entity_absences_count_by_month = $entity_absences_count_by_month->where('users.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$entity_absences_count_by_month = $entity_absences_count_by_month->where('users.id', '=', auth()->user()->service_id)->get();
					break;
				default:
					$entity_absences_count_by_month = $entity_absences_count_by_month->where('users.id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_abs_array = [];
			for ($i = -2; $i < 4; $i++) {
				$entity_abs_array[$i] = 0;
			}
			
			foreach ($entity_absences_count_by_month as $absences_count) {
				
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth()->month) $entity_abs_array['-2'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(-1)->startOfMonth()->month) $entity_abs_array['-1'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth()->month) $entity_abs_array['0'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(+1)->startOfMonth()->month) $entity_abs_array['1'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(+2)->startOfMonth()->month) $entity_abs_array['2'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(+3)->startOfMonth()->month) $entity_abs_array['3'] = $absences_count->absences_month;
			}
			
			//FOR USER
			$user_absences_count_by_month = DB::table('absences')
				->select(DB::raw('month(absences.date) as mm, IFNULL(sum(absences.days), 0) as absences_month'))
				->leftJoin('users', 'users.id', '=', 'absences.user_id')
				->whereBetween('absences.date', [Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(+3)->endOfMonth()])
				->groupBy('mm')
				->orderBy('mm')
				->where('users.id', '=', auth()->user()->id)
				->get();
			
			$user_abs_array = [];
			
			for ($i = -2; $i < 4; $i++) {
				$user_abs_array[$i] = 0;
			}
			
			foreach ($user_absences_count_by_month as $absences_count) {
				
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth()->month) $user_abs_array['-2'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(-1)->startOfMonth()->month) $user_abs_array['-1'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth()->month) $user_abs_array['0'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(+1)->startOfMonth()->month) $user_abs_array['1'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(+2)->startOfMonth()->month) $user_abs_array['2'] = $absences_count->absences_month;
				if ($absences_count->mm == Carbon::parse($dateStartMonth)->addMonth(+3)->startOfMonth()->month) $user_abs_array['3'] = $absences_count->absences_month;
			}
			
			//-----------------------------------------
			// TASKS COUNT
			//-----------------------------------------
			//FOR ENTITY
			$entity_tasks_count_by_status = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status');
			
			switch ($entity_tasks_count_by_status) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$entity_tasks_count_by_status = $entity_tasks_count_by_status->where('activities.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$entity_tasks_count_by_status = $entity_tasks_count_by_status->where('activities.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$entity_tasks_count_by_status = $entity_tasks_count_by_status->where('activities.manager', '=', auth()->user()->id)->get();
					break;
				default:
					$entity_tasks_count_by_status = $entity_tasks_count_by_status->where('activities.manager', '=', auth()->user()->id)->get();
					break;
			}
			
			//FOR USER
			$user_tasks_count_by_status = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status')
				->where('activities.manager', '=', auth()->user()->id)
				->get();
			
			//-----------------------------------------
			// TOTAL PREVU COUNT
			//-----------------------------------------
			//FOR ENTITY
			$entity_prevu_total_by_month = DB::table('tasks')
				->select(DB::raw('month(tasks.start_p) as mm, IFNULL(sum(tasks.days_p), 0) as prevu_total'))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(+3)->endOfMonth()])
				->groupBy('mm')
				->orderBy('mm');
			
			switch ($entity_prevu_total_by_month) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$entity_prevu_total_by_month = $entity_prevu_total_by_month->where('activities.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$entity_prevu_total_by_month = $entity_prevu_total_by_month->where('activities.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$entity_prevu_total_by_month = $entity_prevu_total_by_month->where('activities.manager', '=', auth()->user()->id)->get();
					break;
				default:
					$entity_prevu_total_by_month = $entity_prevu_total_by_month->where('tasks.user_id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_prevu_total_array = [];
			for ($i = -2; $i < 4; $i++) {
				$entity_prevu_total_array[$i] = 0;
			}
			
			foreach ($entity_prevu_total_by_month as $prevu_total_month) {
				
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth()->month) $entity_prevu_total_array['-2'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(-1)->startOfMonth()->month) $entity_prevu_total_array['-1'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth()->month) $entity_prevu_total_array['0'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(+1)->startOfMonth()->month) $entity_prevu_total_array['1'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(+2)->startOfMonth()->month) $entity_prevu_total_array['2'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(+3)->startOfMonth()->month) $entity_prevu_total_array['3'] = $prevu_total_month->prevu_total;
			}
			
			//FOR USER
			$user_prevu_total_by_month = DB::table('tasks')
				->select(DB::raw('month(tasks.start_p) as mm, IFNULL(sum(tasks.days_p), 0) as prevu_total'))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p', [Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(+3)->endOfMonth()])
				->groupBy('mm')
				->orderBy('mm')
				->where('tasks.user_id', '=', auth()->user()->id)
				->get();
			
			$user_prevu_total_array = [];
			for ($i = -2; $i < 4; $i++) {
				$user_prevu_total_array[$i] = 0;
			}
			
			foreach ($user_prevu_total_by_month as $prevu_total_month) {
				
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth()->month) $user_prevu_total_array['-2'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(-1)->startOfMonth()->month) $user_prevu_total_array['-1'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth()->month) $user_prevu_total_array['0'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(+1)->startOfMonth()->month) $user_prevu_total_array['1'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(+2)->startOfMonth()->month) $user_prevu_total_array['2'] = $prevu_total_month->prevu_total;
				if ($prevu_total_month->mm == Carbon::parse($dateStartMonth)->addMonth(+3)->startOfMonth()->month) $user_prevu_total_array['3'] = $prevu_total_month->prevu_total;
			}
			
			//-----------------------------------------
			// REALISE COUNT
			//-----------------------------------------
			//FOR ENTITY
			$entity_realise_by_month = DB::table('work_days')
				->select(DB::raw('month(work_days.date) as mm, IFNULL(sum(work_days.days), 0) as realise'))
				->leftJoin('users', 'users.id', '=', 'work_days.user_id')
				->whereIn('work_days.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('work_days.date', [Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(+3)->endOfMonth()])
				->groupBy('mm')
				->orderBy('mm');
			
			switch ($entity_realise_by_month) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$entity_realise_by_month = $entity_realise_by_month->where('users.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$entity_realise_by_month = $entity_realise_by_month->where('users.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$entity_realise_by_month = $entity_realise_by_month->where('users.id', '=', auth()->user()->id)->get();
					break;
				default:
					$entity_realise_by_month = $entity_realise_by_month->where('users.id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_realise_array = [];
			for ($i = -2; $i < 4; $i++) {
				$entity_realise_array[$i] = 0;
			}
			
			foreach ($entity_realise_by_month as $realise_month) {
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth()->month) $entity_realise_array['-2'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(-1)->startOfMonth()->month) $entity_realise_array['-1'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth()->month) $entity_realise_array['0'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(+1)->startOfMonth()->month) $entity_realise_array['1'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(+2)->startOfMonth()->month) $entity_realise_array['2'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(+3)->startOfMonth()->month) $entity_realise_array['3'] = $realise_month->realise;
			}
			
			//FOR USER
			$user_realise_by_month = DB::table('work_days')
				->select(DB::raw('month(work_days.date) as mm, IFNULL(sum(work_days.days), 0) as realise'))
				->leftJoin('users', 'users.id', '=', 'work_days.user_id')
				->whereIn('work_days.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('work_days.date', [Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(+3)->endOfMonth()])
				->groupBy('mm')
				->orderBy('mm')
				->where('users.id', '=', auth()->user()->id)
				->get();
			
			$user_realise_array = [];
			
			for ($i = -2; $i < 4; $i++) {
				$user_realise_array[$i] = 0;
			}
			
			foreach ($user_realise_by_month as $realise_month) {
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth()->month) $user_realise_array['-2'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(-1)->startOfMonth()->month) $user_realise_array['-1'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth()->month) $user_realise_array['0'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(+1)->startOfMonth()->month) $user_realise_array['1'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(+2)->startOfMonth()->month) $user_realise_array['2'] = $realise_month->realise;
				if ($realise_month->mm == Carbon::parse($dateStartMonth)->addMonth(+3)->startOfMonth()->month) $user_realise_array['3'] = $realise_month->realise;
			}
			
			//-----------------------------------------
			// OPEN_DAYS BY MONTH
			//-----------------------------------------
			// COUNT PPL IN ENTITY
			$count_ppl = DB::table('users')
				->whereIn('users.status', [$this->status_active]);
			
			switch ($count_ppl) {
				case auth()->user()->roles->name == (config('constants.role_directeur')) || auth()->user()->roles->name == (config('constants.role_admin')):
					$count_ppl = $count_ppl->where('users.department_id', '=', auth()->user()->department_id);
					break;
				case (auth()->user()->roles->name == (config('constants.role_service'))):
					$count_ppl = $count_ppl->where('users.service_id', '=', auth()->user()->service_id);
					break;
				case (auth()->user()->roles->name == (config('constants.role_projet'))):
					$count_ppl = $count_ppl->where('users.id', '=', auth()->user()->id);
					break;
				default:
					$count_ppl = $count_ppl->where('users.id', '=', auth()->user()->id);
					break;
			}
			
			$count_ppl = $count_ppl
				->distinct()->count();
			
			// COUNT PPL IN ENTITY
			$sql_open_days_range = [];
			for ($i = -2; $i < 4; $i++) {
				$tmp_month = array(
					'mm' => Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month,
					'yy' => Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year);
				
				$sql_open_days_range[$i] = $tmp_month;
			}
			
			$open_days_by_month = DB::table('open_days');
			
			foreach ($sql_open_days_range as $odem) {
				$open_days_by_month = $open_days_by_month->orWhere(static function ($query) use ($odem) {
					$query
						->where('open_days.month', '=', $odem['mm'])
						->where('open_days.year', '=', $odem['yy']);
				});
			}
			
			$open_days_by_month = $open_days_by_month
				->select(DB::raw('open_days.month as mm, IFNULL((open_days.days), 0) as od'))
				->groupBy('mm')
				->orderBy('mm')
				->get();
			
			
			for ($i = -2; $i < 4; $i++) {
				$entity_open_days_array[$i] = 0;
				$user_open_days_array[$i] = 0;
			}
			
			foreach ($open_days_by_month as $open_days) {
				
				if ($open_days->mm == Carbon::parse($dateStartMonth)->addMonth(-2)->startOfMonth()->month) {
					$entity_open_days_array['-2'] = $open_days->od * $count_ppl;
					$user_open_days_array['-2'] = $open_days->od;
				}
				
				if ($open_days->mm == Carbon::parse($dateStartMonth)->addMonth(-1)->startOfMonth()->month) {
					$entity_open_days_array['-1'] = $open_days->od * $count_ppl;
					$user_open_days_array['-1'] = $open_days->od;
				}
				
				if ($open_days->mm == Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth()->month) {
					$entity_open_days_array['0'] = $open_days->od * $count_ppl;
					$user_open_days_array['0'] = $open_days->od;
				}
				
				if ($open_days->mm == Carbon::parse($dateStartMonth)->addMonth(+1)->startOfMonth()->month) {
					$entity_open_days_array['1'] = $open_days->od * $count_ppl;
					$user_open_days_array['1'] = $open_days->od;
				}
				
				if ($open_days->mm == Carbon::parse($dateStartMonth)->addMonth(+2)->startOfMonth()->month) {
					$entity_open_days_array['2'] = $open_days->od * $count_ppl;
					$user_open_days_array['2'] = $open_days->od;
				}
				
				if ($open_days->mm == Carbon::parse($dateStartMonth)->addMonth(+3)->startOfMonth()->month) {
					$entity_open_days_array['3'] = $open_days->od * $count_ppl;
					$user_open_days_array['3'] = $open_days->od;
				}
				
			}
			
			//-----------------------------------------
			// RESTANT COUNT
			//-----------------------------------------
			for ($i = -2; $i < 4; $i++) {
				$entity_restant_array[$i] = $entity_open_days_array[$i] - $entity_abs_array[$i] - $entity_realise_array[$i];
				$user_restant_array[$i] = $user_open_days_array[$i] - $user_abs_array[$i] - $user_realise_array[$i];
			}
			
			return view('home', compact(
				'current_date',
				'entity_activities_running_count',
				'entity_activities_terminated_count',
				'entity_activities_not_validated_count',
				'entity_activities_validated_count',
				
				'entity_tasks_running_count',
				'entity_tasks_terminated_count',
				'entity_tasks_not_validated_count',
				'entity_tasks_validated_count',
				
				'entity_activities_for_entity_running_count',
				'entity_activities_for_entity_terminated_count',
				'entity_activities_for_entity_not_validated_count',
				'entity_activities_for_entity_validated_count',
				
				'entity_tasks_for_entity_running_count',
				'entity_tasks_for_entity_terminated_count',
				'entity_tasks_for_entity_not_validated_count',
				'entity_tasks_for_entity_validated_count',
				
				'entity_open_days_array',
				'entity_abs_array',
				'entity_prevu_total_array',
				'entity_realise_array',
				'entity_restant_array',
				
				'user_activities_running_count',
				'user_activities_terminated_count',
				'user_activities_not_validated_count',
				'user_activities_validated_count',
				
				'user_tasks_running_count',
				'user_tasks_terminated_count',
				'user_tasks_not_validated_count',
				'user_tasks_validated_count',
				
				'user_activities_for_entity_running_count',
				'user_activities_for_entity_terminated_count',
				'user_activities_for_entity_not_validated_count',
				'user_activities_for_entity_validated_count',
				
				'user_tasks_for_entity_running_count',
				'user_tasks_for_entity_terminated_count',
				'user_tasks_for_entity_not_validated_count',
				'user_tasks_for_entity_validated_count',
				
				'user_open_days_array',
				'user_abs_array',
				'user_prevu_total_array',
				'user_realise_array',
				'user_restant_array'
			));
		}
		
		/**
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 * get task types info for page help
		 */
		public function getTaskTypeHelp()
		{
			//-----------------------------------------
			//SET CURRENT DATE OR GET GATE FROM FORM
			$current_date = $this->current_date;
			$current_month = $this->current_month;
			$current_year = $this->current_year;
			
			$task_types = DB::table('task_types')
				->select('name as task_name', 'description as task_description')
				->orderBy('name', 'asc')
				->get();
			
			$open_days = DB::table('open_days')
				->select('year', 'month', 'days')
				->get();
			
			return view('extra.info',
				compact(
					'current_date',
					'current_year',
					'current_month',
					
					'task_types',
					'open_days'));
		}
	}
