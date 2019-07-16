<?php
	
	namespace App\Http\Middleware;
	
	use App\Models\Absence;
	use App\Models\Task;
	use Carbon\Carbon;
	use Closure;
	
	class TaskCountBadge
	{
		/**
		 * Handle an incoming request.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  \Closure $next
		 * @return mixed
		 */
		public function handle($request, Closure $next)
		{
			if (auth()->check()) {
				$tasks = Task::where('tasks.user_id', '=', auth()->user()->id)
					->where('tasks.status', '=', config('constants.status_active'));
				if (session()->has(['current_month', 'current_year'])) {
					$tasks = $tasks
						->whereMonth('tasks.start_p', '=', session()->get('current_month'))
						->whereYear('tasks.start_p', '=', session()->get('current_year'));
				} else {
					$tasks = $tasks
						->whereDate('tasks.start_p', '<=', Carbon::now()->endOfMonth());
				}
				$tasks = $tasks
					->count();
				
				$absences = Absence::where('absences.user_id', '=', auth()->user()->id);
				if (session()->has(['current_month', 'current_year'])) {
					$absences = $absences
						->whereMonth('absences.date', '=',session()->get('current_month'))
						->whereYear('absences.date', '=', session()->get('current_year'));
				} else {
					$absences = $absences
						->whereMonth('absences.date', '=', Carbon::now()->month)
						->whereYear('absences.date', '=', Carbon::now()->year);
				}
				$absences = $absences
					->count();
				
				$request->session()->put('taskCount', $tasks);
				$request->session()->put('absenceCount', $absences);
			}
			return $next($request);
		}
	}
