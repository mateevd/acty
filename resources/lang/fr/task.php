<?php

return [

	/*Tasks Tabs*/
	'tab_agent' => 'Mois sélectionné',
	'tab_service' => 'Mois sélectionné (Service)',
	'tab_department' => 'Mois sélectionné (Direction)',

	/*Tasks Actions*/
	'act_create' => 'Ajouter une tâche',
	'act_update' => 'Modifier',

	'act_delete' => 'Supprimer',
	'act_delete_desc_01' => 'Le prévu et le réalisé d\'une tâche supprimée sont retirés de tous les calculs.',
	'act_delete_confirm' => 'Êtes-vous sûr de vouloir supprimer la tâche ?',

	'act_delete_multi' => 'Supprimer plusieurs tâches',
	'act_delete_multi_confirm' => 'Êtes-vous sûr de vouloir supprimer les tâches sélectionnées ?',

	'act_copy' => 'Copier',
	'act_copy_single' => 'Copier une tâche au sein de la même phase',
	'act_copy_desc_01' => 'Le prévu et le réalisé de la tâche d\'origine seront conservés dans la phase.',
	'act_copy_desc_02' => 'Le prévu de la tâche créée sera identique à la tâche d\'origine et viendra s\'ajouter à la phase.',
	'act_copy_desc_03' => 'Le réalisé de la tâche créée sera réinitialisé.',

	'act_copy_multi' => 'Copier plusieurs tâches',
	'act_copy_multi_confirm' => 'Choissez l\'activité et la phase de destination',
	'act_copy_desc_multi_01' => 'Pour chaque tâche, le prévu et le réalisé de la tâche d\'origine seront conservés dans la phase d\'origine.',
	'act_copy_desc_multi_02' => 'Pour chaque tâche, le prévu de la tâche créée sera identique à la tâche d\'origine et viendra s\'ajouter à la phase de destination.',
	'act_copy_desc_multi_03' => 'Pour chaque tâche, le réalisé d\'une tâche copiée sera réinitialisé dans la phase de destination.',

	'act_move' => 'Déplacer',
	'act_move_multi' => 'Déplacer plusieurs tâches',
	'act_move_desc' => 'Le prévu et le réalisé d\'une tâche déplacée seront supprimés de la phase de provenance et rajoutés à la phase de destination.',
	'act_move_multi_confirm' => 'Choissez l\'activité et la phase de destination',

	'act_terminate' => 'Terminer',
	'act_terminate_desc_01' => 'Le réalisé sera conservé, mais le prévu sera retiré de tous les calculs.',
	'act_terminate_desc_02' => 'La réactivation d\'une tâche se fait par le responsable de l\'activité, le chef de service ou le directeur',
	'act_terminate_all' => 'Terminer toutes les tâches',

	'act_terminate_multi' => 'Terminer plusieurs tâches',
	'act_terminate_multi_desc_01' => 'Le réalisé de toutes les tâches sélectionnées sera conservé, mais le prévu sera retiré de tous les calculs.',
	'act_terminate_multi_confirm' => 'Êtes-vous sûr de vouloir terminer les tâches sélectionnées ?',

	'act_activate' => 'Réactiver',
	'act_activate_all' => 'Réactiver toutes les tâches',
	'act_activate_desc_01' => 'Le prévu de la tâche sera réintégré aux calculs.',
	'act_activate_desc_02' => 'Tous les temps de la tâche qui ont déjà été validés devront-être revalidés.',

	'act_activate_multi' => 'Réactiver plusieurs tâches',
	'act_activate_multi_desc_01' => 'Le prévu de toutes les tâches sélectionnées sera réintégré aux calculs.',
	'act_activate_multi_confirm' => 'Êtes-vous sûr de vouloir réactiver les tâches sélectionnées ?',

	
	/*Tasks Tooltips*/
	'tool_create' => 'Créer une tâche ou une série de tâches récurrentes pour une ou plusieurs personnes via une fenêtre d\'interface',
	'tool_create_public' => 'Créer une tâche dans n\'importe quelle activité partagée de votre direction. La phase dans laquelle la tâche sera créée doit aussi être partagée',
	'tool_update' => 'Êditer',
	'tool_delete' => 'Supprimer',
	'tool_delete_multi' => 'Supprimer les tâches selectionnées',
	'tool_terminate' => 'Terminer (le réalisé sera conservé, mais le prévu sera retiré des calculs de charge)',
	'tool_activate' => 'Réactiver una tâche terminée',
	'tool_copy' => 'Copier',
	'tool_copy_multi' => 'Copier les tâches selectionnées',
	'tool_move_multi' => 'Déplacer les tâches selectionnées',
	'tool_normale' => 'Tâche normale',
	'tool_jalon' => 'Tâche jalon',
	'tool_add_hours' => 'Ajouter des heurs effectueés sur la tâche',

	/*Tasks Labels*/
	'lab_task' => 'Tâche',
	'lab_task_id' => 'ID tâche : ',
	'lab_phase' => 'Phase',
	'lab_project' => 'Activité',
	'lab_task_name' => 'Nom de la tâche',
	'lab_days' => 'Prévu',
	'lab_restant' => 'Restant',
	'lab_occurrence' => 'Occurence(s)',
	'lab_task_type' => 'Type de tâche',
	'lab_date_start' => 'Début',
	'lab_date_start_real' => 'Début (réal.)',
	'lab_date_end' => 'Échéance',
	'lab_date_end_real' => 'Fin (réal.)',
	'lab_user_affected' => 'Affectation',
	'lab_user_charge' => 'Affectation (charge du mois pour l\'utilisateur)',
	'lab_description' => 'Description',
	'lab_jalon' => 'Jalon',
	'lab_select' => 'Sélec.',
	'lab_actions' => 'Actions',

	'lab_days_real' => 'Réalisé',
	'lab_real_day' => 'Jour de réalisation',

	/*Hours Modal*/
	'hours_add' => 'Rajouter : ',
	'hours_remove' => 'Actions',
	'hours_total' => 'Actions',
	'hours_date' => 'Actions',
	'hours_days_total' => 'Actions',
	'hours_description' => 'Actions',
	'hours_desc' => 'Actions',
	'hours_history' => 'Historique des jours déjà saisis sur cette tâche',


	//old to delete
	'TaskNav' => 'Temps',
	'Tasks' => 'Tâches',
	'New' => 'Créer une tâche',
	'NewPublic' => 'Créer une tâche',
	'NewOccurence' => 'Créer une tâche',
	'Edit' => 'Modifier la tâche',
	'Delete' => 'Supprimer',
	'CopyLabel' => 'Copier',
	'Copy' => 'Copier les tâches selectionnées',
	'Move' => 'Déplacer les tâches selectionnées',
	'ConfirmDelete' => 'Êtes-vous sûr de vouloir supprimer la ou les tâches sélectionnées ?',
	'Term' => 'Terminer',
	'TermLabel' => 'Terminé',
	'TerminateALL' => 'Tout terminer',
	'Terminate' => 'Êtes-vous sûr de vouloir terminer la tâche ?',
	'TerminateMulti' => 'Êtes-vous sûr de vouloir terminer toutes les tâches du mois sélectionnées ?',
	'Act' => 'Réactiver',
	'Activate' => 'Êtes-vous sûr de vouloir réactiver la tâche ?',
	'Comment' => 'Commentaire lié à la saisie de votre temps',
	'History' => 'Historique des jours déjà saisis sur cette tâche',
	'Agent' => 'Utilisateur',

	'Yes' => 'Oui',
	'Back' => 'Retour',
	'Save' => 'Sauvegarder',

	'ID' => 'ID',
	'Name' => 'Nom',
	'NameFull' => 'Nom de la tâche',
	'Status' => 'Statut',
	'Select' => 'Sélec.',
	'Milestone' => 'Jalon',
	'Desc' => 'Description',
	'Code' => 'Code de la tâche',
	'Type' => 'Type de tâche',
	'Types' => 'Types de tâche',
	'StartP' => 'Début',
	'StartR' => 'Début (réal.)',
	'EndP' => 'Échéance',
	'EndR' => 'Fin (réal.)',
	'DaysP' => 'Prévu',
	'DaysR' => 'Réalisé',
	'DaysR_total' => 'Réalisé total',
	'DateR' => 'Demande',
	'Priority' => 'Prio.',
	'Assign' => ' Affectation (charge du mois pour l\'utilisateur)',
	'Charge' => 'Charge',
	'MyTasks' => 'Mes tâches commençant le mois sélectionné ',
	'MyTasksR' => 'Mes tâches commençant les mois antérieurs au mois sélectionné',
	'MyTasksF' => 'Mes tâches réalisées',
	'Hours' => 'Ajouter des heures',
	'Occurence' => 'Occurence(s)',
	'Actions' => 'Actions',



	/*Tasks Labels*/
	'lab_task' => 'Tâche',
	'lab_month_detail' => 'Détail du mois',
	'lab_mois_selectionne' => 'Mois sélectionné',
	'lab_tâches_anterieures' => 'Antérieures ',
];
