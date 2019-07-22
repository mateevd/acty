<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Phase extends Model
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
		'activity_id',
	];

	public function activities()
	{
		return $this->belongsTo('App\Models\Activity', 'activity_id');
	}

	public function tasks()
	{
		return $this->hasMany('App\Models\Task', 'phase_id');
	}


	public static function update_status_simple($phase_id, $new_status)
	{
		$phase_status = DB::table('phases')
			->where('phases.id', '=', $phase_id)
			->update(['phases.status' => $new_status]);
	}

	public static function update_status_auto($phase_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		$nb_active = Phase::count_tasks_by_status($phase_id, [$status_active]);
		$nb_terminated = Phase::count_tasks_by_status($phase_id, [$status_terminated]);
		$nb_not_validated = Phase::count_tasks_by_status($phase_id, [$status_not_validated]);
		$nb_validated = Phase::count_tasks_by_status($phase_id, [$status_validated]);

		$current_status = Phase::get_status($phase_id);
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
				Phase::update_status_simple($phase_id, $new_status);
			}

	}

	public static function update_phase_P($phase_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		//START_P + END_P
		//DAYS_P + OPEX_P
		$new_p = DB::table('tasks')
			->where('tasks.phase_id', '=', $phase_id)
			->whereIn('tasks.status', [
					$status_active,
					$status_terminated,
					$status_not_validated])
			->select(DB::raw("min(tasks.start_p) as new_start_p,
							max(tasks.end_p) as new_end_p,
							sum(tasks.days_p) as new_days_p,
							sum(tasks.opex_p) as new_opex_p"))
			->get();

		//DAYS_P_TOTAL + OPEX_P_TOTAL
		$new_p_total = DB::table('tasks')
			->where('tasks.phase_id', '=', $phase_id)
			->whereIn('tasks.status', [
					$status_active,
					$status_terminated,
					$status_not_validated,
					$status_validated])
			->select(DB::raw("sum(tasks.days_p) as new_days_p_total,
							sum(tasks.opex_p) as new_opex_p_total"))
			->get();

		$update_phase_P = DB::table('phases')
			->where('phases.id', '=', $phase_id)
			->update([
						'phases.days_p' => $new_p[0]->new_days_p,
						'phases.days_p_total' => $new_p_total[0]->new_days_p_total, 
						'phases.opex_p' => $new_p[0]->new_opex_p, 
						'phases.opex_p_total' => $new_p_total[0]->new_opex_p_total, 
						'phases.start_p' => $new_p[0]->new_start_p, 
						'phases.end_p' => $new_p[0]->new_end_p]);
	}



	public static function update_phase_R($phase_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		//START_R + END_R
		//DAYS_R + OPEX_R
		$new_r = DB::table('tasks')
			->where('tasks.phase_id', '=', $phase_id)
			->whereIn('tasks.status', [
					$status_active,
					$status_terminated,
					$status_not_validated,
					$status_validated])
			->select(DB::raw("min(tasks.start_r) as new_start_r,
							max(tasks.end_r) as new_end_r,
							sum(tasks.days_r) as new_days_r,
							sum(tasks.opex_r) as new_opex_r"))
			->get();

		$update_phase_R = DB::table('phases')
			->where('phases.id', '=', $phase_id)
			->update([
						'phases.days_r' => $new_r[0]->new_days_r,
						'phases.opex_r' => $new_r[0]->new_opex_r, 
						'phases.start_r' => $new_r[0]->new_start_r, 
						'phases.end_r' => $new_r[0]->new_end_r]);
	}


	public static function get_status($phase_id)
	{
		$status = -1; 

		$phase_status = DB::table('phases')
			->where('phases.id', '=', $phase_id)
			->pluck('phases.status');

		if (isset($phase_status[0])) {
			$status = $phase_status[0];
		}

		return $status;
	}	

	public static function get_activity_id($phase_id)
	{
		$activity = -1; 

		$phase_activity = DB::table('phases')
			->where('phases.id', '=', $phase_id)
			->pluck('phases.activity_id');

		if (isset($phase_activity[0])) {
			$activity = $phase_activity[0];
		}

		return $activity;
	}


	public static function count_tasks_by_status($phase_id, $status)
	{
		$count = -1; 

		$nb_status_count = DB::table('tasks')
			->where('tasks.phase_id', '=', $phase_id)
			->whereIn('tasks.status', $status)
		->select(DB::raw("count(distinct tasks.id) as nb_count"))
		->get();				

		if (isset($nb_status_count[0])) {
			$count = $nb_status_count[0]->nb_count;
		}

		return $count;
	}	

	public static function get_new_status($phase_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		$status_phase = Phase::get_status($phase_id);

		$active_tasks_count = Phase::count_tasks_by_status($phase_id, [$status_active]);
		$terminated_tasks_count = Phase::count_tasks_by_status($phase_id, [$status_terminated]);
		$not_validated_tasks_count = Phase::count_tasks_by_status($phase_id, [$status_not_validated]);
		$validated_tasks_count = Phase::count_tasks_by_status($phase_id, [$status_validated]);


		if ($active_tasks_count > 0) {
			return $status_active;
		}

		if (session()->get('cra_validate') == 0)
		{
			if (($terminated_tasks_count > 0) ||
			 	($not_validated_tasks_count > 0) ||
				($validated_tasks_count > 0)) {
				return $status_validated;
			}
		}
		else
		{
			if ($not_validated_tasks_count > 0) {
				return $status_not_validated;
			}

			if ($terminated_tasks_count > 0) {
				return $status_terminated;
			}

			if ($validated_tasks_count > 0) {
				return $status_validated;
			}
		}

		return -1;
	}	


}
