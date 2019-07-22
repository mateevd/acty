<?php
	
namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Task extends Model
{
	public $timestamps = false;
	
	protected $fillable = [
		'name',
		'description',
		'start_p',
		'start_r',
		'end_p',
		'end_r',
		'days_p',
		'days_r',
	];
	
	public function phases()
	{
		return $this->belongsTo('App\Models\Phase', 'phase_id');
	}
	
	public function task_types()
	{
		return $this->belongsTo('App\Models\TaskType', 'task_type_id');
	}
	
	public function work_days()
	{
		return $this->hasMany('App\Models\WorkDay', 'task_id');
	}
	
	public function users()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public static function update_status_simple($task_id, $new_status)
	{
		$task_status = DB::table('tasks')
			->where('tasks.id', '=', $task_id)
			->update(['tasks.status' => $new_status]);
	}
	
	public static function update_status_auto($task_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');
		$status_deleted = config('constants.status_deleted');

		$nb_active = Task::count_wd_by_status($task_id, [$status_active]);
		$nb_terminated = Task::count_wd_by_status($task_id, [$status_terminated]);
		$nb_not_validated = Task::count_wd_by_status($task_id, [$status_not_validated]);
		$nb_validated = Task::count_wd_by_status($task_id, [$status_validated]);

		$current_status = Task::get_status($task_id);
		$new_status = $status_active;

		if($nb_not_validated > 0){
			$new_status = $status_not_validated;
		}elseif ($nb_active > 0) {
			$new_status = $status_active;
		}elseif ($nb_terminated > 0) {
			$new_status = $status_terminated;
		}elseif($nb_validated > 0){
			$new_status = $status_validated;
		}

		if (($current_status == $status_terminated) ||
			($current_status == $status_not_validated) ||
			($current_status == $status_validated) )

			{
				Task::update_status_simple($task_id, $new_status);
			}

	}

	public static function update_task_P($task_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		$get_task_opex = DB::table('tasks')
			->leftJoin('users', 'users.id', '=', 'tasks.user_id')
			->where('tasks.id', '=', $task_id)
			->whereIn('tasks.status', [
					$status_active,
					$status_terminated,
					$status_not_validated,
					$status_validated])
			->selectRaw('sum(days_p*users.daily_cost) as opex_p_calc')
			->pluck('opex_p_calc');
		
		$update_task = DB::table('tasks')
			->where('tasks.id', '=', $task_id)
			->update(['tasks.opex_p' => $get_task_opex[0]]);
	}

	public static function update_task_R($task_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		$get_task = DB::table('work_days')
			->where('work_days.task_id', '=', $task_id)
			->whereIn('work_days.status', [
					$status_active,
					$status_terminated,
					$status_not_validated,
					$status_validated])

			->selectRaw('sum(days) as hours, min(date) as start_r, max(date) as end_r')
			->get()
			->toArray();
		
		$get_task_opex = DB::table('work_days')
			->leftJoin('users', 'users.id', '=', 'work_days.user_id')
			->where('work_days.task_id', '=', $task_id)
			->whereIn('work_days.status', [
					$status_active,
					$status_terminated,
					$status_not_validated,
					$status_validated])
			
			->selectRaw('sum(days*users.daily_cost) as opex_r_calc')
			->pluck('opex_r_calc');
		
		$update_task = DB::table('tasks')
			->where('tasks.id', '=', $task_id)
			->update(['tasks.days_r' => $get_task[0]->hours,
				'tasks.start_r' => $get_task[0]->start_r,
				'tasks.end_r' => $get_task[0]->end_r,
				'tasks.opex_r' => $get_task_opex[0]
			]);
	}

	public static function get_status($task_id)
	{
		$status = -1; 

		$task_status = DB::table('tasks')
			->where('tasks.id', '=', $task_id)
			->pluck('tasks.status');

		if (isset($task_status[0])) {
			$status = $task_status[0];
		}

		return $status;
	}	

	public static function get_phase_id($task_id)
	{
		$phase = -1; 

		$task_phase = DB::table('tasks')
			->where('tasks.id', '=', $task_id)
			->pluck('tasks.phase_id');

		if (isset($task_phase[0])) {
			$phase = $task_phase[0];
		}

		return $phase;
	}

	public static function count_tasks_for_status($task_id, $status)
	{
		$count = -1; 

		$tasks_status_count = DB::table('tasks')
			->whereIn('tasks.id', $task_id)
			->whereIn('tasks.status', $status)
		->select(DB::raw("count(distinct tasks.id) as tasks_count"))
		->get();				

		if (isset($tasks_status_count[0])) {
			$count = $tasks_status_count[0]->tasks_count;
		}

		return $count;
	}	

	public static function count_wd_by_status($task_id, $status)
	{
		$count = -1; 


		$nb_status_count = DB::table('work_days')
			->where('work_days.task_id', '=', $task_id)
			->whereIn('work_days.status', $status)
		->select(DB::raw("count(distinct work_days.id) as nb_count"))
		->get();				

		if (isset($nb_status_count[0])) {
			$count = $nb_status_count[0]->nb_count;
		}

		return $count;
	}	

	public static function get_new_status($task_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		$status_phase = Task::get_status($task_id);

		$active_wd_count = Task::count_wd_by_status($task_id, [$status_active]);
		$terminated_wd_count = Task::count_wd_by_status($task_id, [$status_terminated]);
		$not_validated_wd_count = Task::count_wd_by_status($task_id, [$status_not_validated]);
		$validated_wd_count = Task::count_wd_by_status($task_id, [$status_validated]);


		if ($active_wd_count > 0) {
			return $status_active;
		}

		if (session()->get('cra_validate') == 0)
		{
			if (($terminated_wd_count > 0) ||
			 	($not_validated_wd_count > 0) ||
				($validated_wd_count > 0)) {
				return $status_validated;
			}
		}
		else
		{
			if ($not_validated_wd_count > 0) {
				return $status_not_validated;
			}

			if ($terminated_wd_count > 0) {
				return $status_terminated;
			}

			if ($validated_wd_count > 0) {
				return $status_validated;
			}
		}

		return -1;
	}	

}
