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
			//SQL
			//SET CURRENT DATE OR GET GATE FROM FORM
			$current_date = $this->current_date;
			$current_month = $this->current_month;
			$current_year = $this->current_year;
			
			$dateStartMonth = Carbon::parse($current_date)->startOfMonth();
			$dateEndMonth = Carbon::parse($current_date)->endOfMonth();
			
			$cra_validate = session()->get('cra_validate');
			
			$period_fisrt = -2;
			$period_last = 6;
			
			//-----------------------------------------
			// USER/ENTITY ACTIVITIES
			//-----------------------------------------
			//FOR ENTITY
			$ea = DB::table('activities')
				->select(DB::raw("activities.status as status, count(distinct activities.id) as number"))
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereDate('activities.start_p', '<=', Carbon::parse($dateStartMonth)->endOfMonth())
				->groupBy('activities.status')
				->orderBy('activities.status');
			
			switch ($ea) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$ea = $ea->where('activities.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$ea = $ea->where('activities.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$ea = $ea->where('activities.manager', '=', auth()->user()->id)->get();
					break;
				default:
					$ea = $ea->where('activities.manager', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_activities_running_count = 0;
			$entity_activities_terminated_count = 0;
			$entity_activities_not_validated_count = 0;
			$entity_activities_validated_count = 0;
			
			foreach ($ea as $status_count) {
				if ($status_count->status == $this->status_active) $entity_activities_running_count = $status_count->number;
				
				if($cra_validate)
				{
					if ($status_count->status == $this->status_terminated) $entity_activities_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_activities_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_activities_validated_count = $status_count->number;
				}
				else
				{
					if ($status_count->status == $this->status_terminated) $entity_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_activities_validated_count += $status_count->number;
				}
			}
			
			//FOR USER
			$ua = DB::table('activities')
				->select(DB::raw("activities.status as status, count(distinct activities.id) as number"))
				->where('activities.manager', '=', auth()->user()->id)
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereDate('activities.start_p', '<=', Carbon::parse($dateStartMonth)->endOfMonth())
				->groupBy('activities.status')
				->orderBy('activities.status')
				->get();
			
			$user_activities_running_count = 0;
			$user_activities_terminated_count = 0;
			$user_activities_not_validated_count = 0;
			$user_activities_validated_count = 0;
			
			foreach ($ua as $status_count) {
				if ($status_count->status == $this->status_active) $user_activities_running_count = $status_count->number;
				
				if($cra_validate)
				{
					if ($status_count->status == $this->status_terminated) $user_activities_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_activities_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $user_activities_validated_count = $status_count->number;
				}
				else
				{
					if ($status_count->status == $this->status_terminated) $user_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $user_activities_validated_count += $status_count->number;
				}
			}
			
			
			//-----------------------------------------
			// TASKS FOR USER/ENTITY ACTIVITIES
			//-----------------------------------------
			//FOR ENTITY
			$t4ea = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status');
			
			switch ($t4ea) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$t4ea = $t4ea->where('activities.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$t4ea = $t4ea->where('activities.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$t4ea = $t4ea->where('activities.manager', '=', auth()->user()->id)->get();
					break;
				default:
					$t4ea = $t4ea->where('activities.manager', '=', auth()->user()->id)->get();
					break;
			}
			
			$tasks_for_entity_activities_running_count = 0;
			$tasks_for_entity_activities_terminated_count = 0;
			$tasks_for_entity_activities_not_validated_count = 0;
			$tasks_for_entity_activities_validated_count = 0;
			
			foreach ($t4ea as $status_count) {
				if ($status_count->status == $this->status_active) $tasks_for_entity_activities_running_count = $status_count->number;
				
				if($cra_validate)
				{
					if ($status_count->status == $this->status_terminated) $tasks_for_entity_activities_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $tasks_for_entity_activities_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $tasks_for_entity_activities_validated_count = $status_count->number;
				}
				else
				{
					if ($status_count->status == $this->status_terminated) $tasks_for_entity_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $tasks_for_entity_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $tasks_for_entity_activities_validated_count += $status_count->number;
				}
			}
			
			//FOR USER
			$t4ua = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->where('activities.manager', '=', auth()->user()->id)
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status')
				->get();
			
			$tasks_for_user_activities_running_count = 0;
			$tasks_for_user_activities_terminated_count = 0;
			$tasks_for_user_activities_not_validated_count = 0;
			$tasks_for_user_activities_validated_count = 0;
			
			foreach ($t4ua as $status_count) {
				if ($status_count->status == $this->status_active) $tasks_for_user_activities_running_count = $status_count->number;
				
				if($cra_validate)
				{
					if ($status_count->status == $this->status_terminated) $tasks_for_user_activities_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $tasks_for_user_activities_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $tasks_for_user_activities_validated_count = $status_count->number;
				}
				else
				{
					if ($status_count->status == $this->status_terminated) $tasks_for_user_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $tasks_for_user_activities_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $tasks_for_user_activities_validated_count += $status_count->number;
				}
			}
			
			
			//-----------------------------------------
			// ACTIVITIES FOR USER/ENTITY TASKS 
			//-----------------------------------------
			//FOR ENTITY
			$a4et = DB::table('activities')
				->select(DB::raw("activities.status as status, count(distinct activities.id) as number"))
				->leftJoin('phases', 'phases.activity_id', '=', 'activities.id')
				->leftJoin('tasks', 'tasks.phase_id', '=', 'phases.id')
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('activities.status')
				->orderBy('activities.status');
			
			switch ($a4et) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$a4et = $a4et->where('users.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$a4et = $a4et->where('users.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$a4et = $a4et->where('users.id', '=', auth()->user()->id)->get();
					break;
				default:
					$a4et = $a4et->where('users.id', '=', auth()->user()->id)->get();
					break;
			}
			
			$activities_for_entity_tasks_running_count = 0;
			$activities_for_entity_tasks_terminated_count = 0;
			$activities_for_entity_tasks_not_validated_count = 0;
			$activities_for_entity_tasks_validated_count = 0;
			
			foreach ($a4et as $status_count) {
				if ($status_count->status == $this->status_active) $activities_for_entity_tasks_running_count = $status_count->number;
				
				if($cra_validate)
				{
					if ($status_count->status == $this->status_terminated) $activities_for_entity_tasks_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $activities_for_entity_tasks_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $activities_for_entity_tasks_validated_count = $status_count->number;
				}
				else
				{
					if ($status_count->status == $this->status_terminated) $activities_for_entity_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $activities_for_entity_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $activities_for_entity_tasks_validated_count += $status_count->number;
				}
				
			}
			
			//FOR USER
			$a4ut = DB::table('activities')
				->leftJoin('phases', 'phases.activity_id', '=', 'activities.id')
				->leftJoin('tasks', 'tasks.phase_id', '=', 'phases.id')
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->where('users.id', '=', auth()->user()->id)
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('activities.status')
				->orderBy('activities.status')
				->select(DB::raw("activities.status as status, count(distinct activities.id) as number"))
				->get();
			
			$activities_for_user_tasks_running_count = 0;
			$activities_for_user_tasks_terminated_count = 0;
			$activities_for_user_tasks_not_validated_count = 0;
			$activities_for_user_tasks_validated_count = 0;
			
			foreach ($a4ut as $status_count) {
				if ($status_count->status == $this->status_active) $activities_for_user_tasks_running_count = $status_count->number;
				
				if($cra_validate)
				{
					if ($status_count->status == $this->status_terminated) $activities_for_user_tasks_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $activities_for_user_tasks_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $activities_for_user_tasks_validated_count = $status_count->number;
				}
				else
				{
					if ($status_count->status == $this->status_terminated) $activities_for_user_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $activities_for_user_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $activities_for_user_tasks_validated_count += $status_count->number;
				}
				
			}
			
			
			//-----------------------------------------
			// USER/ENTITY TASKS COUNT BY STATUS
			//-----------------------------------------
			//FOR ENTITY
			$et = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status');
			
			switch ($et) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$et = $et->where('users.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$et = $et->where('users.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$et = $et->where('users.id', '=', auth()->user()->id)->get();
					break;
				default:
					$et = $et->where('users.id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_tasks_running_count = 0;
			$entity_tasks_terminated_count = 0;
			$entity_tasks_not_validated_count = 0;
			$entity_tasks_validated_count = 0;
			
			foreach ($et as $status_count) {
				if ($status_count->status == $this->status_active) $entity_tasks_running_count = $status_count->number;
				
				if($cra_validate)
				{
					if ($status_count->status == $this->status_terminated) $entity_tasks_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_tasks_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_tasks_validated_count = $status_count->number;
				}
				else
				{
					if ($status_count->status == $this->status_terminated) $entity_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $entity_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $entity_tasks_validated_count += $status_count->number;
				}
			}
			
			//FOR USER
			$ut = DB::table('tasks')
				->select(DB::raw("tasks.status as status, count(distinct tasks.id) as number"))
				->leftJoin('users', 'users.id', '=', 'tasks.user_id')
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->where('users.id', '=', auth()->user()->id)
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth(0)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth(0)->endOfMonth()])
				->groupBy('tasks.status')
				->orderBy('tasks.status')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->get();
			
			$user_tasks_running_count = 0;
			$user_tasks_terminated_count = 0;
			$user_tasks_not_validated_count = 0;
			$user_tasks_validated_count = 0;
			
			foreach ($ut as $status_count) {
				if ($status_count->status == $this->status_active) $user_tasks_running_count = $status_count->number;
				
				if($cra_validate)
				{
					if ($status_count->status == $this->status_terminated) $user_tasks_terminated_count = $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_tasks_not_validated_count = $status_count->number;
					if ($status_count->status == $this->status_validated) $user_tasks_validated_count = $status_count->number;
				}
				else
				{
					if ($status_count->status == $this->status_terminated) $user_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_not_validated) $user_tasks_validated_count += $status_count->number;
					if ($status_count->status == $this->status_validated) $user_tasks_validated_count += $status_count->number;
				}
			}
			
			
			//-----------------------------------------
			// ABSENCES COUNT 
			//-----------------------------------------
			//FOR ENTITY
			$eabs = DB::table('absences')
				->select(DB::raw('concat(year(absences.date), lpad(month(absences.date), 2, "00")) as yyyymm, IFNULL(sum(absences.days), 0) as absences_month'))
				->leftJoin('users', 'users.id', '=', 'absences.user_id')
				->whereBetween('absences.date',  [	Carbon::parse($dateStartMonth)->addMonth($period_fisrt)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth($period_last)->endOfMonth()])
				->groupBy('yyyymm')
				->orderBy('yyyymm');
			
			switch ($eabs) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$eabs = $eabs->where('users.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$eabs = $eabs->where('users.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$eabs = $eabs->where('users.id', '=', auth()->user()->service_id)->get();
					break;
				default:
					$eabs = $eabs->where('users.id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_abs_array = [];
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$entity_abs_array[$i] = 0;
			}
			
			foreach ($eabs as $absences_count) {
				for($i=$period_fisrt; $i<=$period_last; $i++) {
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					if ($absences_count->yyyymm == $key) $entity_abs_array[$i] = $absences_count->absences_month;
				}
			}
			
			//FOR USER
			$uabs = DB::table('absences')
				->select(DB::raw('concat(year(absences.date), lpad(month(absences.date), 2, "00")) as yyyymm, IFNULL(sum(absences.days), 0) as absences_month'))
				->leftJoin('users', 'users.id', '=', 'absences.user_id')
				->whereBetween('absences.date',  [	Carbon::parse($dateStartMonth)->addMonth($period_fisrt)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth($period_last)->endOfMonth()])
				->groupBy('yyyymm')
				->orderBy('yyyymm')
				->where('users.id', '=', auth()->user()->id)
				->get();
			
			$user_abs_array = [];
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$user_abs_array[$i] = 0;
			}
			
			foreach ($uabs as $absences_count) {
				for($i=$period_fisrt; $i<=$period_last; $i++) {
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					if ($absences_count->yyyymm == $key) $user_abs_array[$i] = $absences_count->absences_month;
				}
			}
			
			//-----------------------------------------
			// TOTAL PREVU COUNT 
			//-----------------------------------------
			//FOR ENTITY
			$etp = DB::table('tasks')
				->select(DB::raw('concat(year(tasks.start_p), lpad(month(tasks.start_p), 2, "00")) as yyyymm, IFNULL(sum(tasks.days_p), 0) as prevu_total'))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated,$this->status_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth($period_fisrt)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth($period_last)->endOfMonth()])
				->groupBy('yyyymm')
				->orderBy('yyyymm');
			
			switch ($etp) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$etp = $etp->where('activities.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$etp = $etp->where('activities.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$etp = $etp->where('activities.manager', '=', auth()->user()->id)->get();
					break;
				default:
					$etp = $etp->where('tasks.user_id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_prevu_total_array = [];
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$entity_prevu_total_array[$i] = 0;
			}
			
			foreach ($etp as $prevu_total_month) {
				for($i=$period_fisrt; $i<=$period_last; $i++) {
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					if ($prevu_total_month->yyyymm == $key) $entity_prevu_total_array[$i] = $prevu_total_month->prevu_total;
				}
			}
			
			//FOR USER
			$utp = DB::table('tasks')
				->select(DB::raw('concat(year(tasks.start_p), lpad(month(tasks.start_p), 2, "00")) as yyyymm, IFNULL(sum(tasks.days_p), 0) as prevu_total'))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated,$this->status_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth($period_fisrt)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth($period_last)->endOfMonth()])
				->groupBy('yyyymm')
				->orderBy('yyyymm')
				->where('tasks.user_id', '=', auth()->user()->id)
				->get();
			
			$user_prevu_total_array = [];
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$user_prevu_total_array[$i] = 0;
			}
			
			foreach ($utp as $prevu_total_month) {
				for($i=$period_fisrt; $i<=$period_last; $i++) {
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					if ($prevu_total_month->yyyymm == $key) $user_prevu_total_array[$i] = $prevu_total_month->prevu_total;
				}
			}
			
			//-----------------------------------------
			// RESTANT COUNT 
			//-----------------------------------------
			//FOR ENTITY
			$ep = DB::table('tasks')
				->select(DB::raw('concat(year(tasks.start_p), lpad(month(tasks.start_p), 2, "00")) as yyyymm, IFNULL(sum(tasks.days_p), 0) as prevu'))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth($period_fisrt)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth($period_last)->endOfMonth()])
				->groupBy('yyyymm')
				->orderBy('yyyymm');
			
			switch ($ep) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$ep = $ep->where('activities.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$ep = $ep->where('activities.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$ep = $ep->where('activities.manager', '=', auth()->user()->id)->get();
					break;
				default:
					$ep = $ep->where('tasks.user_id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_prevu_array = [];
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$entity_prevu_array[$i] = 0;
			}
			
			foreach ($ep as $prevu_month) {
				for($i=$period_fisrt; $i<=$period_last; $i++) {
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					if ($prevu_month->yyyymm == $key) $entity_prevu_array[$i] = $prevu_month->prevu;
				}
			}
			
			//FOR USER
			$up = DB::table('tasks')
				->select(DB::raw('concat(year(tasks.start_p), lpad(month(tasks.start_p), 2, "00")) as yyyymm, IFNULL(sum(tasks.days_p), 0) as prevu'))
				->leftJoin('phases', 'phases.id', '=', 'tasks.phase_id')
				->leftJoin('activities', 'activities.id', '=', 'phases.activity_id')
				->whereIn('activities.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereIn('tasks.status', [$this->status_active, $this->status_terminated, $this->status_not_validated])
				->whereBetween('tasks.start_p',  [	Carbon::parse($dateStartMonth)->addMonth($period_fisrt)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth($period_last)->endOfMonth()])
				->groupBy('yyyymm')
				->orderBy('yyyymm')
				->where('tasks.user_id', '=', auth()->user()->id)
				->get();
			
			$user_prevu_array = [];
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$user_prevu_array[$i] = 0;
			}
			
			foreach ($up as $prevu_month) {
				for($i=$period_fisrt; $i<=$period_last; $i++) {
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					if ($prevu_month->yyyymm == $key) $user_prevu_array[$i] = $prevu_month->prevu;
				}
			}
			
			
			//-----------------------------------------
			// REALISE COUNT 
			//-----------------------------------------
			//FOR ENTITY
			$er = DB::table('work_days')
				->select(DB::raw('concat(year(work_days.date), lpad(month(work_days.date), 2, "00")) as yyyymm, IFNULL(sum(work_days.days), 0) as realise'))
				->leftJoin('users', 'users.id', '=', 'work_days.user_id')
				->whereIn('work_days.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('work_days.date',  [	Carbon::parse($dateStartMonth)->addMonth($period_fisrt)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth($period_last)->endOfMonth()])
				->groupBy('yyyymm')
				->orderBy('yyyymm');
			
			switch ($er) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$er = $er->where('users.department_id', '=', auth()->user()->department_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$er = $er->where('users.service_id', '=', auth()->user()->service_id)->get();
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$er = $er->where('users.id', '=', auth()->user()->id)->get();
					break;
				default:
					$er = $er->where('users.id', '=', auth()->user()->id)->get();
					break;
			}
			
			$entity_realise_array = [];
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$entity_realise_array[$i] = 0;
			}
			
			foreach ($er as $realise_month) {
				for($i=$period_fisrt; $i<=$period_last; $i++) {
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					if ($realise_month->yyyymm == $key) $entity_realise_array[$i] = $realise_month->realise;
				}
			}
			
			//FOR USER
			$ur = DB::table('work_days')
				->select(DB::raw('concat(year(work_days.date), lpad(month(work_days.date), 2, "00")) as yyyymm, IFNULL(sum(work_days.days), 0) as realise'))
				->leftJoin('users', 'users.id', '=', 'work_days.user_id')
				->whereIn('work_days.status', [$this->status_active, $this->status_terminated, $this->status_not_validated, $this->status_validated])
				->whereBetween('work_days.date',  [	Carbon::parse($dateStartMonth)->addMonth($period_fisrt)->startOfMonth(),
					Carbon::parse($dateStartMonth)->addMonth($period_last)->endOfMonth()])
				->groupBy('yyyymm')
				->orderBy('yyyymm')
				->where('users.id', '=', auth()->user()->id)
				->get();
			
			$user_realise_array = [];
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$user_realise_array[$i] = 0;
			}
			
			foreach ($ur as $realise_month) {
				for($i=$period_fisrt; $i<=$period_last; $i++) {
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					if ($realise_month->yyyymm == $key) $user_realise_array[$i] = $realise_month->realise;
				}
			}
			
			//-----------------------------------------
			// OPEN_DAYS BY MONTH
			//-----------------------------------------
			// COUNT PPL IN ENTITY
			$eppl = DB::table('users')
				->whereIn('users.status', [$this->status_active]);
			
			switch ($eppl) {
				case auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_admin_id'):
					$eppl = $eppl->where('users.department_id', '=', auth()->user()->department_id);
					break;
				case (auth()->user()->role_id == config('constants.role_service_id')):
					$eppl = $eppl->where('users.service_id', '=', auth()->user()->service_id);
					break;
				case (auth()->user()->role_id == config('constants.role_projet_id')):
					$eppl = $eppl->where('users.id', '=', auth()->user()->id);
					break;
				default:
					$eppl = $eppl->where('users.id', '=', auth()->user()->id);
					break;
			}
			
			$eppl = $eppl
				->distinct()->count();
			
			// COUNT OD FOR PERIOD
			$sql_open_days_range = [];
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$tmp_month = array(
					'mm' => Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month,
					'yy' => Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year);
				
				$sql_open_days_range[$i] = $tmp_month;
			}
			
			$open_days_by_month = DB::table('open_days')
				->select(DB::raw('concat(open_days.year, lpad(open_days.month, 2, "00")) as yyyymm, IFNULL((open_days.days), 0) as od'));
			
			foreach ($sql_open_days_range as $sodr)
			{
				$open_days_by_month = $open_days_by_month->orWhere(static function($query) use($sodr){
					$query
						->where('open_days.month', '=', $sodr['mm'])
						->where('open_days.year', '=', $sodr['yy']);
				});
			}
			
			$open_days_by_month = $open_days_by_month
				->groupBy('yyyymm')
				->orderBy('yyyymm')
				->get();
			
			for ($i=$period_fisrt; $i<=$period_last; $i++) {
				$entity_open_days_array[$i] = 0;
				$user_open_days_array[$i] = 0;
			}
			
			foreach ($open_days_by_month as $open_days) {
				
				for($i=$period_fisrt; $i<=$period_last; $i++)
				{
					$key = Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->year . str_pad(Carbon::parse($dateStartMonth)->addMonth($i)->startOfMonth()->month, 2, "00", STR_PAD_LEFT);
					
					if ($open_days->yyyymm == $key)
					{
						$entity_open_days_array[$i] = $open_days->od*$eppl;
						$user_open_days_array[$i] = $open_days->od*1;
						
						// RESTANT COUNT
						$entity_restant_array[$i] = $entity_open_days_array[$i] - $entity_abs_array[$i] - $entity_realise_array[$i];
						$user_restant_array[$i] = $user_open_days_array[$i] - $user_abs_array[$i] - $user_realise_array[$i];
					}
				}
			}
			
			return view('home', compact(
				'current_date',
				'entity_activities_running_count',
				'entity_activities_terminated_count',
				'entity_activities_not_validated_count',
				'entity_activities_validated_count',
				
				'tasks_for_entity_activities_running_count',
				'tasks_for_entity_activities_terminated_count',
				'tasks_for_entity_activities_not_validated_count',
				'tasks_for_entity_activities_validated_count',
				
				'entity_tasks_running_count',
				'entity_tasks_terminated_count',
				'entity_tasks_not_validated_count',
				'entity_tasks_validated_count',
				
				'activities_for_entity_tasks_running_count',
				'activities_for_entity_tasks_terminated_count',
				'activities_for_entity_tasks_not_validated_count',
				'activities_for_entity_tasks_validated_count',
				
				'entity_open_days_array',
				'entity_abs_array',
				'entity_prevu_total_array',
				'entity_prevu_array',
				'entity_realise_array',
				'entity_restant_array',
				
				'user_activities_running_count',
				'user_activities_terminated_count',
				'user_activities_not_validated_count',
				'user_activities_validated_count',
				
				'tasks_for_user_activities_running_count',
				'tasks_for_user_activities_terminated_count',
				'tasks_for_user_activities_not_validated_count',
				'tasks_for_user_activities_validated_count',
				
				'user_tasks_running_count',
				'user_tasks_terminated_count',
				'user_tasks_not_validated_count',
				'user_tasks_validated_count',
				
				'activities_for_user_tasks_running_count',
				'activities_for_user_tasks_terminated_count',
				'activities_for_user_tasks_not_validated_count',
				'activities_for_user_tasks_validated_count',
				
				'user_open_days_array',
				'user_abs_array',
				'user_prevu_total_array',
				'user_prevu_array',
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
