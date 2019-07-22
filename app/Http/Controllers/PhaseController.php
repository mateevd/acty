<?php
	
	namespace App\Http\Controllers;
	
	use App\Helpers\LogActivity;
	use App\Models\Phase;
	use App\Models\Activity;
	use App\Models\Task;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	
	class PhaseController extends Controller
	{
		const subject = 'activityLog.Phase';
		
		/**
		 * ActivityController constructor.
		 */
		public function __construct()
		{
			$this->middleware('auth');
			$this->status_active = config('constants.status_active');
			$this->status_terminated = config('constants.status_terminated');
			$this->status_not_validated = config('constants.status_not_validated');
			$this->status_validated = config('constants.status_validated');
			$this->status_deleted = config('constants.status_deleted');
			
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
			
			$this->task_controller = new TaskController();
		}
		
		/**
		 * @param Request $request
		 */
		public function index(Request $request)
		{
			//
		}
		
		/**
		 * @info CREATE PHASE
		 * @param Request $request
		 * @param $activity_id
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function create(Request $request, $activity_id)
		{
			/*
			 * Retrieve config flag value
			 */
			try {
				/*
				 * Begin DB transaction
				 */
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$activity = Activity::find($activity_id);
				$phase = new Phase();
				$phase->name = $request->phase_name;
				$phase->description = $request->phase_description;
				$phase->start_p = $request->phase_start_p;
				$phase->end_p = $request->phase_end_p;
				$phase->private = $request->phase_private;
				$phase->status = 0;
				$phase->activities()->associate($activity);
				
				$phase->save();
				
				/*
				 * DB commit if no errors
				 */
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, $phase);
				
				flashy()->success(trans('flash_message.phase_create'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
			}
			return redirect()->back();
		}
		
		/**
		 * EDIT PHASE
		 * @param Request $request
		 * @param $id
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function edit(Request $request)
		{
			//
		}
		
		/**
		 * @info UPDATE PHASE
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function update(Request $request)
		{
			
			$phase_id = $request->phase_id;
			
			$phase = Phase::find($request->phase_id);
			
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$phase->name = $request->phase_name;
				$phase->description = $request->phase_description;
				$phase->start_p = $request->phase_start_p;
				$phase->end_p = $request->phase_end_p;
				$phase->private = $request->phase_private;
				
				if ($phase->isDirty()) {
					$phase->update();
					
					LogActivity::dbCommit(__METHOD__, __LINE__);
					
					LogActivity::addToLog(trans(self::subject), __function__, $phase);
					
					flashy()->success(trans('flash_message.phase_update'));
				} else {
					LogActivity::dbRollback(__METHOD__, __LINE__);
					flashy()->info(trans('flash_message.not_dirty'));
				}
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->success(trans('flash_message.phase_update_error'));
			}
			
			return redirect()->back();
		}

		public function getTasks($phase_id, $statuses = null)
		{

			if (!$statuses)
			{
				$statuses = [
					$this->status_active,
					$this->status_terminated,
					$this->status_not_validated,
					$this->status_validated];
			}
			
			$getTasks = DB::table('tasks')
				->where('tasks.phase_id', '=', $phase_id)
				->whereIn('tasks.status', $statuses)
				->select(DB::raw("
					tasks.id as id,
					tasks.status as status
					"))
				->get()->toArray();
			
			return $getTasks;
		}

		/**
		 * @info CHANGE STATUS/TERMINATE P/T/WD
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function terminate(Request $request, $phase_id_in = null, $activity_id_in = null)
		{
			// On ne teste que le premier des paramètres optionnels
			// Car soit on les a tous, soit aucun
			if ($phase_id_in == null) {
				$phase_id = $request->phase_id;
				$activity_id = $request->activity_id;
				$come_from_function = false;
			} else {
				$phase_id = $phase_id_in;
				$activity_id = $activity_id_in;
				$come_from_function = true;
			}
			
			//-----------------------------------------
			//SQL
			
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$status_phase = Phase::get_status($phase_id);
				$update_phase = false;
				
				if (session()->get('cra_validate') == 0) {
					
					if (($status_phase == $this->status_active) ||
						($status_phase == $this->status_terminated) ||
						($status_phase == $this->status_not_validated)) {

						$getTasks = $this->getTasks($phase_id, [
							$this->status_active, 
							$this->status_terminated, 
							$this->status_not_validated]);

						if ($getTasks != null) {
							foreach ($getTasks as $task) {
								$task_id = $task->id;
								
								$this->task_controller->terminate($request, $task_id, $phase_id, $activity_id);
								$update_phase = true;
							}
						}
					}
				} else {

					if (($status_phase == $this->status_active) ||
						($status_phase == $this->status_not_validated) ||
						($status_phase == $this->status_validated)) {

						$getTasks = $this->getTasks($phase_id, [
							$this->status_active]);

						if ($getTasks != null) {
							foreach ($getTasks as $task) {
								$task_id = $task->id;
								
								$this->task_controller->terminate($request, $task_id, $phase_id, $activity_id);
								$update_phase = true;
							}
						}					
					}
				}

				$new_phase_status = Phase::get_new_status($phase_id);
				
				if ($new_phase_status < 0) {
					if (session()->get('cra_validate') == 0) 
						$new_phase_status = $this->status_terminated;
					else
						$new_phase_status = $this->status_validated;
				}

				if ($new_phase_status != $status_phase) {
					Phase::update_status_simple($phase_id, $new_phase_status);
				}
				
				if ($update_phase == true) {
					Phase::update_phase_P($phase_id);
				}
				
				if ($come_from_function == false) { // Veut dire qu'on vient directement de la vue
					Activity::update_activity_P($activity_id);
				}
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, Phase::find($phase_id));
				
				flashy()->success(trans('flash_message.phase_terminate'));
				
			} catch (\Exception $ex) {
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.phase_terminate_error'));
			}
			return redirect()->back();
		}
		
		/**
		 * @info CHANGE STATUS/ACTIVATE P/T/WD
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function activate(Request $request, $phase_id_in = null, $activity_id_in = null)
		{
			// On ne teste que le premier des paramètres optionnels
			// Car soit on les a tous, soit aucun
			if ($phase_id_in == null) {
				$phase_id = $request->phase_id;
				$activity_id = $request->activity_id;
				$come_from_function = false;
			} else {
				$phase_id = $phase_id_in;
				$activity_id = $activity_id_in;
				$come_from_function = true;
			}
			
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$status_phase = Phase::get_status($phase_id);
				$update_phase = false;
				$update_phase_status = false;
				
				if (session()->get('cra_validate') == false) {
					
					if (($status_phase == $this->status_terminated) ||
						($status_phase == $this->status_not_validated) ||
						($status_phase == $this->status_validated)) {
						
						$getTasks = $this->getTasks($phase_id, [
							$this->status_terminated, 
							$this->status_not_validated]);

						foreach ($getTasks as $task) {
								$task_id = $task->id;
								
								$this->task_controller->activate($request, $task_id, $phase_id, $activity_id);
								$update_phase = true;
							}
					}
				} else {
					
					if (($status_phase == $this->status_terminated) ||
						($status_phase == $this->status_not_validated) ||
						($status_phase == $this->status_validated)) {
						
						$getTasks = $this->getTasks($phase_id, [
							$this->status_terminated, 
							$this->status_not_validated]);

						foreach ($getTasks as $task) {
							$task_id = $task->id;
							
							$this->task_controller->activate($request, $task_id, $phase_id, $activity_id);
							$update_phase = true;
						}
					}
				}

				//UPDATE PHASE
				$new_phase_status = $this->status_active;

				if ($new_phase_status != $status_phase) Phase::update_status_simple($phase_id, $new_phase_status);
				
				if ($update_phase == true) Phase::update_phase_P($phase_id);
				
				//UPDATE ACTIVITY
				if ($come_from_function == false) { // Veut dire qu'on vient directement de la vue
					$status_activity = Activity::get_status($activity_id); // On vérifie le status de l'activité suite à la MAJ du status de la phase
					$new_activity_status = Activity::get_new_status($activity_id);

					if ($new_activity_status < 0) $new_activity_status = $this->status_active;

					if ($new_activity_status != $status_activity) Activity::update_status_simple($activity_id, $new_activity_status);
					
					Activity::update_activity_P($activity_id);
				}
				
				LogActivity::addToLog(trans(self::subject), __function__, Phase::find($phase_id));
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				flashy()->success(trans('flash_message.phase_activate'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->error(trans('flash_message.phase_activate_error'));
			}

			return redirect()->back();
		}
		
		/**
		 * @info CHANGE PRIVACY PHASE
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function privacy(Request $request)
		{
			$phase_obj = Phase::find($request->phase_id);
			
			if ($phase_obj->private == config('constants.private_no')) {
				$phase = DB::table('phases')
					->where('phases.id', '=', $request->phase_id)
					->update(['phases.private' => config('constants.private_yes')]);
				$action = __function__ . 'toprivate';
			} else {
				$phase = DB::table('phases')
					->where('phases.id', '=', $request->phase_id)
					->update(['phases.private' => config('constants.private_no')]);
				$action = __function__ . 'topublic';
			}
			
			LogActivity::addToLog(trans(self::subject), $action, Phase::find($request->phase_id));
			return redirect()->back();
		}
		
		/**
		 * CHANGE STATUS/DELETE P/T/WD
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function destroy(Request $request, $phase_id_in = null, $activity_id_in = null)
		{
			//-----------------------------------------
			//SQL

			
			// On ne teste que le premier des paramètres optionnels
			// Car soit on les a tous, soit aucun
			if ($phase_id_in == null) {
				$phase_id = $request->phase_id;
				$activity_id = $request->activity_id;
				$come_from_function = false;
			} else {
				$phase_id = $phase_id_in;
				$activity_id = $activity_id_in;
				$come_from_function = true;
			}
			
			
			try {
				
				LogActivity::beginTransaction(__METHOD__, __LINE__);

				$getTasks = $this->getTasks($phase_id);

				if ($getTasks != null) {
					foreach ($getTasks as $task) {
						$task_id = $task->id;
						
						$this->task_controller->destroy($request, $task_id, $phase_id, $activity_id);
					}
				}
				
				Phase::update_status_simple($phase_id, $this->status_deleted);
				Phase::update_phase_P($phase_id);
				Phase::update_phase_R($phase_id);
				
				// ACTIVITY UPDATE
				if ($come_from_function == false) { // Veut dire qu'on vient directement de la vue
					Activity::update_activity_P($activity_id);
					Activity::update_activity_R($activity_id);
				}
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, Phase::find($phase_id));
				
				flashy()->success(trans('flash_message.phase_delete'));
				
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->success(trans('flash_message.phase_delete_error'));
			}
			
			return redirect()->back();
		}
		
		/**
		 * @param Request $request
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function movePhase(Request $request)
		{
			$phase_id = $request->phase_id;
			
			try {
				LogActivity::beginTransaction(__METHOD__, __LINE__);
				
				$phase = Phase::find($phase_id);
				
				$old_activity_id = $phase->activity_id;
				$new_activity_id = $request->activity_id;
				
				$phase->activity_id = $new_activity_id;
				
				$phase->save();
				
				// UPDATE OLD ACTIVITY
				Activity::update_activity_P($old_activity_id);
				Activity::update_activity_R($old_activity_id);
				
				// UPDATE NEW ACTIVITY
				Activity::update_activity_P($new_activity_id);
				Activity::update_activity_R($new_activity_id);
				
				LogActivity::dbCommit(__METHOD__, __LINE__);
				
				LogActivity::addToLog(trans(self::subject), __function__, $phase);
				
				flashy()->success(trans('flash_message.phase_move'));
			} catch (\Exception $ex) {
				
				LogActivity::dbRollback(__METHOD__, __LINE__);
				
				flashy()->success(trans('flash_message.phase_move_error'));
			}
			
			return redirect()->back();
		}
	}
