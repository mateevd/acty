<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Activity extends Model
{
	public $timestamps = false;
	
	protected $fillable = [
		'name',
		'description',
		'activity_code',
		'activity_type_id',
		'start_p',
		'start_r',
		'end_p',
		'end_r',
		'days_p',
		'days_r',
		'date_requested',
	];

	/**
	 * @var array
	 */
	protected $dates = [
		'start_p',
		'start_r',
		'end_p',
		'end_r',
		'date_requested',
	];


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function phases()
	{
		return $this->hasMany('App\Models\Phase', 'activity_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function users()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function activity_types()
	{
		return $this->belongsTo('App\Models\ActivityType', 'activity_type_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function priorities()
	{
		return $this->belongsTo('App\Models\Priority', 'priority_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function departments()
	{
		return $this->belongsTo('App\Models\Department', 'department_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function services()
	{
		return $this->belongsTo('App\Models\Service', 'service_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function business_dep()
	{
		return $this->belongsTo('App\Models\Businesses', 'business_id');
	}

	/**
	 * Update Activity status
	 * @param $activity_id
	 * @param $new_status
	 */
	public static function update_status_simple($activity_id, $new_status)
	{
		$activity_status = DB::table('activities')
			->where('activities.id', '=', $activity_id)
			->update(['activities.status' => $new_status]);
	}

	public static function update_status_auto($activity_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		$nb_active = Activity::count_phases_by_status($activity_id, [$status_active]);
		$nb_terminated = Activity::count_phases_by_status($activity_id, [$status_terminated]);
		$nb_not_validated = Activity::count_phases_by_status($activity_id, [$status_not_validated]);
		$nb_validated = Activity::count_phases_by_status($activity_id, [$status_validated]);

		$current_status = Activity::get_status($activity_id);
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
				Activity::update_status_simple($activity_id, $new_status);
			}
	}

	public static function update_activity_P($activity_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		//START_P + END_P
		//DAYS_P + OPEX_P
		$new_p = DB::table('phases')
			->where('phases.activity_id', '=', $activity_id)
			->whereIn('phases.status', [
					$status_active,
					$status_terminated,
					$status_not_validated])
			->select(DB::raw("min(phases.start_p) as new_start_p,
							max(phases.end_p) as new_end_p,
							sum(phases.days_p) as new_days_p,
							sum(phases.opex_p) as new_opex_p"))
			->get();

		//DAYS_P_TOTAL + OPEX_P_TOTAL
		$new_p_total = DB::table('phases')
			->where('phases.activity_id', '=', $activity_id)
			->whereIn('phases.status', [
					$status_active,
					$status_terminated,
					$status_not_validated,
					$status_validated])
			->select(DB::raw("sum(phases.days_p_total) as new_days_p_total,
							sum(phases.opex_p_total) as new_opex_p_total"))
			->get();

		$update_activity_P = DB::table('activities')
			->where('activities.id', '=', $activity_id)
			->update([
						'activities.days_p' => $new_p[0]->new_days_p,
						'activities.days_p_total' => $new_p_total[0]->new_days_p_total, 
						'activities.opex_p' => $new_p[0]->new_opex_p, 
						'activities.opex_p_total' => $new_p_total[0]->new_opex_p_total, 
						'activities.start_p' => $new_p[0]->new_start_p, 
						'activities.end_p' => $new_p[0]->new_end_p]);
	}


	public static function update_activity_R($activity_id)
	{
		//-----------------------------------------
		//SQL
		$status_active = config('constants.status_active');
		$status_terminated = config('constants.status_terminated');
		$status_not_validated = config('constants.status_not_validated');
		$status_validated = config('constants.status_validated');

		//START_R + END_R
		//DAYS_R + OPEX_R
		$new_r = DB::table('phases')
			->where('phases.activity_id', '=', $activity_id)
			->whereIn('phases.status', [
					$status_active,
					$status_terminated,
					$status_not_validated,
					$status_validated])
			->select(DB::raw("min(phases.start_r) as new_start_r,
							max(phases.end_r) as new_end_r,
							sum(phases.days_r) as new_days_r,
							sum(phases.opex_r) as new_opex_r"))
			->get();

		$update_activity_R = DB::table('activities')
			->where('activities.id', '=', $activity_id)
			->update([
						'activities.days_r' => $new_r[0]->new_days_r,
						'activities.opex_r' => $new_r[0]->new_opex_r, 
						'activities.start_r' => $new_r[0]->new_start_r, 
						'activities.end_r' => $new_r[0]->new_end_r]);
	}

	public static function get_status($activity_id)
	{
		$status = -1; 

		$activity_status = DB::table('activities')
			->where('activities.id', '=', $activity_id)
			->pluck('activities.status');

		if (isset($activity_status[0])) {
			$status = $activity_status[0];
		}

		return $status;
	}   

	public static function count_phases_by_status($activity_id, $status)
	{
		$count = -1; 

		$nb_status_count = DB::table('phases')
			->where('phases.activity_id', '=', $activity_id)
			->whereIn('phases.status', $status)
		->select(DB::raw("count(distinct phases.id) as nb_count"))
		->get();				

		if (isset($nb_status_count[0])) {
			$count = $nb_status_count[0]->nb_count;
		}

		return $count;
	}	


}
