<?php
	
	/**
	 * Reprise des information d'évolution des tailles des bases de donnéees.
	 *
	 * @copyright   Ity-Consulting (http://www.ity-consulting.com)
	 * @author      Frederic Truchon-Bartes <ftruchon-bartes@ity-consulting.com>
	 * @version     1.1
	 *
	 * @brief Fltre des donnes d'activite des utilisateurs
	 *
	 * Cette classe permet de filtrer les informations avant leur écriture dans la table logactivities.
	 * L'objectif est d'avoir les données utilies pour faire du reporting.
	 *
	 * Historique
	 * ----------
	 * 2019.05.26 - FTB : Ajout de commentaires\n
	 *
	 */
	
	namespace App\Helpers;
	
	use Carbon\Carbon;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Request;
	use App\Models\LogActivity as LogActivityModel;
	
	
	class LogActivity
	{
		
		const line = ' -line ';		///< Simplication pour le debug
		const level = ' -level ';	///< Simplication pour le debug
		
		
		/**
		 * Standard messages produced by the class. Can be modified for il8n
		 * @var array $_messages
		 */
		const _messages = array(
			'action_ukn' => 'action inconnue',
			'item_ukn' => 'item inconnu',
		);
				
		
		/**
		 * Filter user data for the log
		 *
		 * @param string $action action effectuee sur l'item
		 * @param array  $data   donnees initiales à filtrer
		 * @return array
		 */
		public static function handleDataUtilisateur($action, $data) {
			// Initialize the result
			$temp = [];
			
			switch ($action) {
				case 'authenticated' :
				case 'logout' :
				case 'create' :
				case 'update' :
				case 'destroy' :
				case 'settings' :
					$temp['id'] 		= $data['id'];
					$temp['role_id'] 	= $data['role_id'];
					return $temp;
				
				default :
					// A voir comment on procede
					Log::debug(__METHOD__ . ' ' . self::_messages['action_ukn'] . ' ' . $action);
					return $data;
			}
			return $data;
		}
		
		/**
		 * Filter task data for the log
		 *
		 * @param string $action action effectuee sur l'item
		 * @param array  $data   donnees initiales à filtrer
		 * @return array
		 */
		public static function handleDataTaches($action, $data) {
			$temp = [];
			
			switch ($action) {
				case 'create' :
				case 'update' :
				case 'destroy' :
					$temp['id'] 		  = $data['id'];
					$temp['name'] 		  = $data['name'];
					$temp['task_type_id'] = $data['task_type_id'];
					$temp['start_p']      = Carbon::parse($data['start_p'])->format('d-m-Y');
					$temp['status'] 	  = $data['status'];
					$temp['milestone'] 	  = $data['milestone'];
					$temp['user_id'] 	  = $data['user_id'];
					return $temp;
				
				case 'moveTask' :
				case 'moveMultiTask' :
					$temp['id'] 	  = $data['id'];
					$temp['name'] 	  = $data['name'];
					$temp['private']  = $data['private'];
					$temp['status']   = $data['status'];
					return $temp;
				
				case 'copy' :
				case 'copyMultiTask' :
					$temp['id'] 	  = $data['id'];
					$temp['name'] 	  = $data['name'];
					$temp['private']  = $data['private'];
					$temp['status']   = $data['status'];
					return $temp;
				
				case 'activate' :
				case 'terminate' :
				case 'terminateAll' :
				case 'terminateMultiTask' :
					$temp['id'] 	  = $data['id'];
					$temp['name'] 	  = $data['name'];
					$temp['private']  = $data['private'];
					$temp['status']   = $data['status'];
					return $temp;
				
				default :
					// A voir comment on procede
					Log::debug(__METHOD__ . ' ' . self::_messages['action_ukn'] . ' ' . $action);
					return $data;
				
			}
			return $data;
		}
		
		
		/**
		 * Filter phases data for the log
		 *
		 * @param string $action action effectuee sur l'item
		 * @param array  $data   donnees initiales à filtrer
		 * @return array
		 */
		public static function handleDataPhases($action, $data) {
			$temp = [];
			
			switch ($action) {
				case 'create' :
				case 'update' :
				case 'destroy' :
					$temp['id'] 	     = $data['id'];
					$temp['name'] 		 = $data['name'];
					$temp['start_p']     = Carbon::parse($data['start_p'])->format('d-m-Y');
					$temp['status']      = $data['status'];
					$temp['private'] 	 = $data['private'];
					$temp['activity_id'] = $data['activity_id'];
					return $temp;
				
				case 'activate' :
				case 'terminate' :
					$temp['id'] 	  = $data['id'];
					$temp['name'] 	  = $data['name'];
					$temp['private']  = $data['private'];
					$temp['status']   = $data['status'];
					return $temp;
				
				case 'privacytoprivate' :	// Pour cette action, dans la log 2 formats differents un avec des [] et l'autre sans
				case 'privacytopublic' :	// Pour cette action, dans la log 2 formats differents un avec des [] et l'autre sans
					$temp['id'] 	  = $data['id'];
					$temp['name'] 	  = $data['name'];
					$temp['private']  = $data['private'];
					$temp['status']   = $data['status'];
					return $temp;
				
				case 'movePhase' :
					$temp['id'] 	  = $data['id'];
					$temp['name'] 	  = $data['name'];
					$temp['private']  = $data['private'];
					$temp['status']   = $data['status'];
					return $temp;
				
				default :
					// A voir comment on procede
					Log::debug(__METHOD__ . ' ' . self::_messages['action_ukn'] . ' ' . $action);
					return $data;
			}
			return $data;
		}
		
		/**
		 * Filter activite data for the log
		 * @param string $action action effectuee sur l'item
		 * @param array  $data   donnees initiales à filtrer
		 * @return array
		 */
		public static function handleDataActivite($action, $data) {
			// Initialize the result
			$temp = [];
			
			switch ($action) {
				case 'create' :
				case 'update' :
				case 'destroy' :
					$temp['id'] 	            = $data['id'];
					$temp['name'] 	  	 		= $data['name'];
					$temp['manager']  	 		= $data['manager'];
					$temp['code'] 	  	 		= $data['code'];
					$temp['priority_id'] 		= $data['priority_id'];
					$temp['activity_type_id']   = $data['activity_type_id'];
					$temp['state_id']  	 		= $data['state_id'];
					$temp['status']  	 		= $data['status'];
					return $temp;
				
				case 'privacytoprivate' :	// Pour cette action, dans la log 2 formats differents un avec des [] et l'autre sans
				case 'privacytopublic' :	// Pour cette action, dans la log 2 formats differents un avec des [] et l'autre sans
					$temp['id'] 	  = $data['id'];
					$temp['name'] 	  = $data['name'];
					$temp['code'] 	  = $data['code'];
					$temp['manager']  = $data['manager'];
					$temp['private']  = $data['private'];
					$temp['status']   = $data['status'];
					return $temp;
				
				case 'activate' :
				case 'terminate' :
					$temp['id'] = $data['id'];   // $data = 305
					return $temp;
				
				default :
					// A voir comment on procede
					Log::debug(__METHOD__ . ' ' . self::_messages['action_ukn'] . ' ' . $action);
					return $data;
				
			}
			// by default, return the intial data
			return $data;
		}
		
		
		/**
		 * Filter absences  data for the log
		 * @param string $action action effectuee sur l'item
		 * @param array  $data   donnees initiales à filtrer
		 * @return array
		 */
		public static function handleDataAbsences($action, $data) {
			// Initialize the result
			$temp = [];
			
			switch ($action) {
				case 'create' :
				case 'update' :
				case 'destroy' :
					$temp['id'] 	  	 	 = $data['id'];
					$temp['days']  	 		 = $data['days'];
					$temp['absence_type_id'] = $data['absence_type_id'];
					$temp['user_id'] 	  	 = $data['user_id'];
					return $temp;
				
				default :
					// A voir comment on procede
					Log::debug(__METHOD__ . ' ' . self::_messages['action_ukn'] . ' ' . $action);
					return $data;
			}
			
			return $data;
		}
		
		/**
		 * Filter heures data for the log
		 * @param string $action action effectuee sur l'item
		 * @param array  $data   donnees initiales à filtrer
		 * @return array
		 */
		public static function handleDataHeures($action, $data) {
			// Initialize the result
			$temp = [];
			
			switch ($action) {
				case 'create' :
				case 'update' :
				case 'destroy' :
					$temp['date'] 	 = $data['date'];
					$temp['user_id'] = $data['user_id'];
					$temp['task_id'] = $data['task_id'];
					return $temp;
				
				default :
					// A voir comment on procede
					Log::debug(__METHOD__ . ' ' . self::_messages['action_ukn'] . ' ' . $action);
					return $data;
			}
			// by default, return the intial data
			return $data;
		}
		
		/**
		 * Filtrer les donnees a ecrire dans la log
		 *
		 * @param  string $item   type d'item (absence, activite, tache, pahse, utilisateur, etc.)
		 * @param  string $action type d'ction sur l'item (CRUD, login, logout, etc.)
		 * @param  array  $data   donnees initiale à traiter (faire maigrir)
		 * @return array donnees a reellemment mettre dans logactivities
		 */
		public static function handleData($item, $action, $data) {
			switch ($item) {
				case 'Absences'    : return self::handleDataAbsences($action, $data);
				case 'Activité'    : return self::handleDataActivite($action, $data);
				case 'Heures'      : return self::handleDataHeures($action, $data);
				case 'Phases'      : return self::handleDataPhases($action, $data);
				case 'Tâches'      : return self::handleDataTaches($action, $data);
				case 'Utilisateur' : return self::handleDataUtilisateur($action, $data);
				
				default :
					// A voir comment on procede
					Log::debug(__METHOD__ . ' ' . self::_messages['item_ukn'] . ' ' . $item);
					return $data;
			}
		}
		
		/**
		 *  Ajout d'information dans la table des log.
		 *
		 * @param  string $subject  type d'item
		 * @param  string $function action sur l'item (i.e. CRUD)
		 * @param  array  $data     donnes traitees (au format Php)
		 */
		public static function addToLog($subject, $function, $data)
		{
			try {
				DB::beginTransaction();
				$log = [];
				$log['subject'] = $subject;
				$log['function'] = $function;
				$log['ip'] = Request::ip();
				$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
				
				$log['data'] = json_encode(self::handleData($subject, $function, $data), JSON_UNESCAPED_UNICODE); // Filtre log data
				
				LogActivityModel::create($log);
				DB::commit();
			} catch (\Exception $ex) {
				DB::rollBack();
			}
		}
		
		/**
		 * @return \Illuminate\Support\Collection
		 */
		public static function logActivityLists()
		{
			$condition = Carbon::now()->addMonth(-12);
			DB::table('log_activities')->where('created_at', '<', $condition)->delete();
			return DB::table('log_activities')->orderBy('id', 'desc')->paginate(250);
		}
		
		/**
		 *  DBMS Transaction begin
		 *
		 * @param  string __METHOD__  method used
		 * @param  string __LINE__    line into the source code
		 */
		public static function beginTransaction($method, $line){
			//if (config('constants.log_activate') == 1)
			if (config('constants.log_activate'))
				Log::debug(__METHOD__ . self::line . __LINE__ . self::level . DB::transactionLevel());
			
			DB::beginTransaction();
			
			//if (config('constants.log_activate') == 1)
			if (config('constants.log_activate'))
				Log::debug(__METHOD__ . self::line . __LINE__ . self::level . DB::transactionLevel());
		}
		
		/**
		 *  DBMS Transaction commit
		 *
		 * @param  string __METHOD__  method used
		 * @param  string __LINE__    line into the source code
		 */
		public static function dbCommit($method, $line){
			//if (config('constants.log_activate') == 1)
			if (config('constants.log_activate'))
				Log::debug(__METHOD__ . self::line . __LINE__ . self::level . DB::transactionLevel());
			
			DB::commit();
			
			if (config('constants.log_activate'))
				Log::debug(__METHOD__ . self::line . __LINE__ . self::level . DB::transactionLevel());
		}
		
		
		/**
		 *  DBMS Transaction rollback
		 *
		 * @param  string __METHOD__  method used
		 * @param  string __LINE__    line into the source code
		 */
		public static function dbRollback($method, $line){
			if (config('constants.log_activate'))
				Log::debug(__METHOD__ . self::line . __LINE__ . self::level . DB::transactionLevel());
			
			DB::rollBack();
			
			if (config('constants.log_activate'))
				Log::debug(__METHOD__ . self::line . __LINE__ . self::level . DB::transactionLevel());
		}
		
	}
	
	