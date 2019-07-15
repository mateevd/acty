<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkDay extends Model
{
	public $timestamps = false;
	
    protected $fillable = [
        'description',
        'days',
        'date',
        'status',
    ];

    public function tasks()
    {
        return $this->belongsTo('App\Models\Task', 'task_id');
    }
	
	public static function update_status_simple($wd_id, $new_status)
	{
		$wd_status = DB::table('work_days')
			->where('work_days.id', '=', $wd_id)
			->update(['work_days.status' => $new_status]);
	}

	public static function get_status($wd_id)
	{
		$status = -1; 

		$wd_status = DB::table('work_days')
			->where('work_days.id', '=', $wd_id)
			->pluck('work_days.status');

		if (isset($wd_status[0])) {
			$status = $wd_status[0];
		}

		return $status;
	}	

	public static function get_task_id($wd_id)
	{
		$status = -1; 

		$wd_status = DB::table('work_days')
			->where('work_days.id', '=', $wd_id)
			->pluck('work_days.task_id');

		if (isset($wd_status[0])) {
			$status = $wd_status[0];
		}

		return $status;
	}	

	public static function get_wd_for_user_month($user_id, $month, $year)
	{
		$wd_tab = []; 

		$wd_ids = DB::table('work_days')
			->where('work_days.user_id', '=', $user_id)
			->whereMonth('work_days.date', $month)
			->whereYear('work_days.date', $year)
			->pluck('work_days.id');

		if (isset($wd_ids[0])) {
			$wd_tab = $wd_ids;
		}

		return $wd_tab;
	}	
	
}
