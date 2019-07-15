<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class DateSelect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    if ($request->monthSelect && $request->yearSelect || $request->userSelect) {
		    $current_date = Carbon::createFromDate($request->yearSelect, $request->monthSelect, 1);
		    $current_month = $request->monthSelect;
		    $current_year = Carbon::now()->year($request->yearSelect)->year;
		    $month_name = Carbon::parse($current_date)->localeMonth;
		    
		    session()->put('current_date', $current_date);
		    session()->put('current_month', $current_month);
		    session()->put('current_year', $current_year);
		    session()->put('month_name', $month_name);
	    }
        return $next($request);
    }
}
