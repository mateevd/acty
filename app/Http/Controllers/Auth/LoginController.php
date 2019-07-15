<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
	
	const subject = 'activityLog.Login';
	const line = ' -line ';
	const level = ' -level ';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	public function username()
	{
		return 'login';
	}
	
	protected function authenticated()
	{
		$config_module = DB::table('config_module')
			->get();
		
		if (array_key_exists(0, $config_module->toArray())) session()->put('budget_option', $config_module[0]->module_status); else session()->put('budget_option', 0);
		if (array_key_exists(1, $config_module->toArray())) session()->put('cra_validate', $config_module[1]->module_status); else session()->put('cra_validate', 0);
		
		/*
		 * @info get user info
		 */
		$user_tri = auth()->user()->trigramme;
		$user_first_name = auth()->user()->first_name;
		$user_last_name = auth()->user()->last_name;
		$user_role = auth()->user()->roles->name;
		$user_service = auth()->user()->services->code;
		$user_department = auth()->user()->departments->code;
		$user_config = User::find(auth()->user()->id)->user_config;
		
		/*
		 * @info store user/app config into session
		 */
		session()->put('user_tri', $user_tri);
		session()->put('user_first_name', $user_first_name);
		session()->put('user_last_name', $user_last_name);
		session()->put('user_role', $user_role);
		session()->put('user_service', $user_service);
		session()->put('user_department', $user_department);
		
		if ($user_config != null) {
			if (isset(json_decode($user_config)->theme)) session()->put('theme', json_decode($user_config)->theme); else session()->put('theme', 0);
			if (isset(json_decode($user_config)->zoom)) session()->put('zoom', json_decode($user_config)->zoom); else session()->put('zoom', 0);
			if (isset(json_decode($user_config)->lang)) session()->put('lang', json_decode($user_config)->lang); else session()->put('lang', 0);
			
		} else {
			session()->put('theme', 0);
			session()->put('zoom', 0);
			session()->put('lang', 0);
		}
		
		//store date info
		$current_date = Carbon::now();
		$current_month = Carbon::now()->month;
		$current_year = Carbon::now()->year;
		$month_name = Carbon::parse($current_date)->localeMonth;
		
		session()->put('current_date', $current_date);
		session()->put('current_month', $current_month);
		session()->put('current_year', $current_year);
		session()->put('month_name', $month_name);
		
		try {
			/*
			 * Begin DB transaction
			 */
			LogActivity::beginTransaction(__METHOD__, __LINE__);
			
			User::find(auth()->id())->update(['last_session' => session()->getId()]);
			
			/*
			 * DB commit if no errors
			 */
			LogActivity::dbCommit(__METHOD__, __LINE__);
			
			/*
			 * Add to database logActivity
			 */
			LogActivity::addToLog(trans(self::subject), __function__, auth()->user());
			
		} catch (\Exception $ex) {
			/*
			 * DB rollback if DBMS actions fails
			 */
			LogActivity::dbRollback(__METHOD__, __LINE__);
			flashy()->error(trans('flash_message.user_login_error'));
		}
		
		return redirect('/home');
	}
	
	
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function logout()
	{
		LogActivity::addToLog(trans(self::subject), __function__, auth()->user());
		$this->guard()->logout();
		session()->invalidate();
		session()->flush();
		
		return redirect('/login');
	}
}
