<?php
	
	namespace App\Http\Controllers;
	
	use App\Helpers\LogActivity;
	use App\Models\Role;
	use Carbon\Carbon;
	use Illuminate\Foundation\Auth\AuthenticatesUsers;
	use Illuminate\Http\Request;
	use App\User;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	
	class UserController extends Controller
	{
		use AuthenticatesUsers;
		
		const subject = 'activityLog.User';
		
		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
			$this->middleware('auth');
		}
		
		/**
		 * @param $value string/int $value the value to be handled
		 * @return int value
		 */
		public function getInt($value) {
			if ( is_int($value)) {
				return $value;
			}
			return intval($value);
		}
		
		/**
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function settings(Request $request)
		{
			/*
			 * @info create data for json object
			 */
			if ($request->theme == null) $theme = session()->get('theme'); else $theme = $request->theme;
			if ($request->zoom == null) $zoom = session()->get('zoom'); else $zoom = $request->zoom;
			if ($request->lang == null) $lang = session()->get('lang'); else $lang = $request->lang;
			
			$data = [
				'theme' => $theme,
				'zoom' => $zoom,
				'lang' => $lang,
			];
			try {
				/*
				 * Begin of DB transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				/*
			 * @info update user configs
			 */
				$object = User::find(Auth::user()->id);
				$object->user_config = json_encode($data);
				
				if ($object->isDirty()) {
					$object->save();
					
					session()->put('theme', $theme);
					session()->put('zoom', $zoom);
					session()->put('lang', $lang);
					
					LogActivity::dbCommit(__METHOD__, __LINE__);
					
					LogActivity::addToLog(trans(self::subject), __function__, $object);
					flashy()->success(trans('flash_message.user_update'));
				} else {
					LogActivity::dbRollback(__METHOD__, __LINE__);
					flashy()->info(trans('flash_message.not_dirty'));
				}
			} catch (\Exception $ex) {
				/*
				 * DB rollback if commit fails
				 */
				LogActivity::dbRollback(__METHOD__, __LINE__);
				flashy()->success(trans('flash_message.user_update_error'));
			}
			
			return redirect()->back();
		}
		
		public function getData()
		{
			$users = DB::table('users')
				->leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('services', 'services.id', '=', 'users.service_id')
				->leftJoin('departments', 'departments.id', '=', 'users.department_id');
			
			switch ('users') {
				case(auth()->user()->roles->name == (config('constants.role_directeur'))):
					$users = $users->where('users.department_id', '=', auth::user()->department_id);
					break;
				
				case(auth()->user()->roles->name == (config('constants.role_service'))):
					$users = $users->where('users.service_id', '=', auth::user()->service_id);
					break;
			}
			$users = $users->select('users.id as user_id',
				'users.first_name as user_first_name',
				'users.last_name as user_last_name',
				'users.email as user_email',
				'users.login as user_login',
				'users.trigramme as user_trigramme',
				'users.daily_cost as user_daily_cost',
				'users.status as user_status',
				'users.department_id as user_department_id',
				'users.department_id as user_department_id2',
				'users.service_id as user_service_id',
				'users.service_id as user_service_id2',
				'users.role_id as user_role_id',
				'departments.name as user_department_name',
				'services.name as user_service_name',
				'roles.name as user_role_name')
				->orderBy('users.last_name', 'asc')
				->orderBy('users.first_name', 'asc')
				->orderBy('users.login', 'asc')
				->orderBy('users.department_id', 'asc')
				->orderBy('users.service_id', 'asc')
				->get();
			
			return response()->json(['data' => $users]);
		}
		
		/**
		 * @info Display a listing of the resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function index()
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

			$users = DB::table('users')
				->leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('services', 'services.id', '=', 'users.service_id')
				->leftJoin('departments', 'departments.id', '=', 'users.department_id');
			
			switch ('users') {
				case(auth()->user()->roles->name == (config('constants.role_directeur'))):
					$users = $users->where('users.department_id', '=', auth::user()->department_id);
					break;
				
				case(auth()->user()->roles->name == (config('constants.role_service'))):
					$users = $users->where('users.service_id', '=', auth::user()->service_id);
					break;
			}
			$users = $users->select('users.id as user_id',
				'users.first_name as user_first_name',
				'users.last_name as user_last_name',
				'users.email as user_email',
				'users.login as user_login',
				'users.trigramme as user_trigramme',
				'users.daily_cost as user_daily_cost',
				'users.status as user_status',
				'users.department_id as user_department_id',
				'users.department_id as user_department_id2',
				'users.service_id as user_service_id',
				'users.service_id as user_service_id2',
				'users.role_id as user_role_id',
				'departments.name as user_department_name',
				'services.name as user_service_name',
				'roles.name as user_role_name')
				->orderBy('users.last_name', 'asc')
				->orderBy('users.first_name', 'asc')
				->orderBy('users.login', 'asc')
				->orderBy('users.department_id', 'asc')
				->orderBy('users.service_id', 'asc')
				->get();
			
			//SJL CHECK
			/*Get departments*/
			$departments = DB::table('departments');
			if (auth()->user()->roles->name == config('constants.role_service')) {
				$departments = $departments->where('id', '=', auth()->user()->department_id)
					->pluck('name', 'id');
			} elseif (auth()->user()->roles->name == config('constants.role_directeur')) {
				$departments = $departments->where('id', '=', auth()->user()->department_id)
					->pluck('name', 'id');
			} else {
				$departments = $departments->pluck('name', 'id');
			}
			
			/*liste services modal edition*/
			$services = DB::table('services');
			if (auth()->user()->roles->name == config('constants.role_service')) {
				$services = $services->where('id', '=', auth()->user()->service_id)
					->pluck('name', 'id');
			} elseif (auth()->user()->roles->name == config('constants.role_directeur')) {
				$services = $services->where('department_id', '=', auth()->user()->department_id)
					->pluck('name', 'id');
			} else {
				$services = $services->pluck('name', 'id');
			}
			
			//LISTE DES ROLES
			$roles = DB::table('roles')
				->where('id', '>=', auth()->user()->roles->id)
				->pluck('name', 'id');
			
			return view('users.user_index', 
				compact(
					'current_date',
					'current_year',
					'current_month',

					'users', 
					'departments', 
					'services', 
					'roles'));
		}
		
		public function getServicesList($user_department_id)
		{
			//liste services modal creation
			$user_service_id = DB::table('services')
				->where('department_id', '=', $user_department_id)
				->pluck('name', 'id');
			
			return ['user_service_id' => $user_service_id];
		}
		
		/**
		 * @info Create user
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
		 */
		public function create(Request $request)
		{
			$user_config = [
				'theme' => 0,
				'lang' => 0,
				'zoom' => 0,
			];
			
			try {
				/*
				 * Begin DB transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$user = new User();
				$user->first_name = $request->user_first_name;
				$user->last_name = $request->user_last_name;
				$user->email = $request->user_email;
				$user->login = $request->user_login;
				$user->trigramme = $request->user_trigramme;
				$user->role_id = $request->user_role_id;
				$user->password = bcrypt($request->{'current-password'});
				$user->department_id = $request->user_department_id;
				$user->service_id = $request->user_service_id;
				if ($request->user_daily_cost) $user->daily_cost = $request->user_daily_cost;
				else $user->daily_cost = 0;
				$user->user_config = json_encode($user_config);
				$user->save();
				
				/*
				 * DB commit if no errors
				 */
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, $user);
				flashy()->success(trans('flash_message.user_create'));
				
			} catch (\Exception $ex) {
				flashy()->error(trans('flash_message.user_create_error'));
				/*
				 * DB rollback if commit fails
				 */
				LogActivity::dbRollback(__METHOD__, __LINE__);
			}
			return redirect('users');
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
		 * Display the specified resource.
		 *
		 * @param  int $id
		 * @return \Illuminate\Http\Response
		 */
		public function show($id)
		{
			//
		}
		
		/**
		 * @param User $users
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function edit()
		{
			//
		}
		
		/**
		 * @info Update user
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function update(Request $request)
		{
			try {
				/*
				 * Begin DB transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$user = User::find($request->user_id);
				$user->first_name = $request->user_first_name;
				$user->last_name = $request->user_last_name;
				$user->email = $request->user_email;
				$user->login = $request->user_login;
				$user->trigramme = $request->user_trigramme;
				$user->role_id = $request->user_role_id;
				$user->password = bcrypt($request->{'current-password'});
				$user->department_id = $request->user_department_id2;
				$user->service_id = $request->user_service_id2;
				if ($request->user_daily_cost) $user->daily_cost = $request->user_daily_cost;
				else $user->daily_cost = 0;
				
				/*
				 * Update if any changes are made
				 * else do nothing
				 */
				if ($user->isDirty()) {
					$user->update();
					/*
					 * DB commit if no errors
					 */
					LogActivity::dbCommit(__METHOD__, __LINE__);
					
					LogActivity::addToLog(trans(self::subject), __function__, $user);
					
					flashy()->success(trans('flash_message.user_update'));
				} else {
					LogActivity::dbRollback(__METHOD__, __LINE__);
					flashy()->info(trans('flash_message.not_dirty'));
				}
				
			} catch (\Exception $ex) {
				flashy()->error(trans('flash_message.user_update_error'));
				/*
				 * DB rollback if commit fails
				 */
				LogActivity::dbRollback(__METHOD__, __LINE__);
				flashy()->error(trans('flash_message.user_update_error'));
			}
			return redirect()->back();
		}
		
		/**
		 * @info delete User
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function destroy(Request $request)
		{
			$user = User::find($request->user_id);
			$user->status = config('constants.status_deleted');
			$user->save();
			
			LogActivity::addToLog(trans(self::subject), __function__, $user);
			flashy()->success(trans('flash_message.user_delete'));
			return redirect()->back();
		}
		
		/**
		 * @info terminate User
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function terminate(Request $request)
		{
			$user = User::find($request->user_id);
			$user->status = config('constants.status_terminated');
			$user->save();
			
			flashy()->success(trans('flash_message.user_terminate'));
			return redirect()->back();
		}
		
		/**
		 * @info activate User
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function activate(Request $request)
		{
			$user = User::find($request->user_id);
			$user->status = config('constants.status_active');
			$user->save();
			
			flashy()->success(trans('flash_message.user_activate'));
			return redirect()->back();
		}
	}
