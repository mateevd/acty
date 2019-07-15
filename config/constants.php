<?php
return [
    'log_activate' => 1,
	
	'status_active' => 0,
	'status_terminated' => 1,
	'status_not_validated' => 2,
	'status_validated' => 3,
	'status_deleted' => 9,
	
	'private_no' => 0,
	'private_yes' => 1,
	
	'milestone_no' => 0,
	'milestone_yes' => 1,
	
    //Range for year select
    //SJL : à mettre en config base
    'start_year' => '2018',
    'end_year' => '2023',

    /*activity state*/
    'state_framing' => 1,
    'state_planned' => 2,
    'state_inProgress' => 3,
    'state_suspended' => 4,
    'state_canceled' => 5,
    'state_ended' => 6,

    'role_admin' => "Admin",
    'role_directeur' => "Directeur",
    'role_service' => "Chef de service",
    'role_projet' => "Chef de projet",
    'role_agent' => "Agent",
    'role_prestataire' => "Prestataire",
	
	'role_admin_id' => 1,
	'role_directeur_id' => 2,
	'role_service_id' => 3,
	'role_projet_id' => 4,
	'role_agent_id' => 5,
	'role_prestataire_id' => 6,
	
	/*user configs*/
	'lang_fr' => "FR",
	'lang_en' => "EN",
	
	'theme_def' => 0,
	'theme_01' => 1,
	'theme_02' => 2,
	'theme_03' => 3,
	
	'zoom_def' => 0,
	'zoom_75' => 1,
	'zoom_100' => 2,
	'zoom_125' => 3,
	
	/*export*/
	'export_item_activities' => 'activities',
	'export_item_charges' => 'charges',
	'export_item_personal' => 'personal',

	'export_activities_all' => 0,
	'export_activities_actives' => 1,
	'export_charges_all' => 0,
	'export_charges_bydate' => 1,
	'export_personal_all' => 0,
	'export_personal_bydate' => 1,

];