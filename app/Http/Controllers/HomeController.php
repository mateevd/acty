<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
	
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function logActivity()
	{
		if (auth()->user()->hasRole(config('constants.role_admin'))) {
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
}
