$(function () {
	// Available panels
	// #mine
	// #ongoing
	// #terminated
	var tab_active = localStorage.getItem('activities-tab-active');
	if (tab_active) {
		$('#activities-tab-selection a[href="' + tab_active + '"]').tab('show');
	}
	else {
		$('#activities-tab-selection a[href="#mine"]').tab('show'); //default panel
	}

	$('#activities-tab-selection a[data-toggle="tab"]').click( function (e) {
		var _target = $(e.target).attr('href');
		if (!_target) _target = $(this).attr('href');

		localStorage.setItem('activities-tab-active', _target);
		$('#activities-tab-selection a[href="' + _target + '"]').tab('show');
	});

	$(document).on('click', '#downloadButton', function () {
		$('#exportTables').find('#type_activities').val("0");
		$('#exportTables').find('#type_charges').val("0");
		$('#exportTables #is_dates').attr('hidden', true);
		$('#exportTables #date_error_gap').attr('hidden', true);
		$('#exportTables #is_dates_personal').attr('hidden', true);
		$('#exportTables #date_error_gap_personal').attr('hidden', true);
	});

	$(document).on('change', '#type_charges', function () {
			var selected_item = $('#type_charges').val();

			if (selected_item == 1) {
				$('#exportTables #is_dates').attr('hidden', false);
				$('#exportTables #charge_date_end').attr('required', true);
				$('#exportTables #charge_date_start').attr('required', true);
			} else {
				$('#exportTables #is_dates').attr('hidden', true);
				$('#exportTables #charge_date_end').attr('required', false);
				$('#exportTables #charge_date_start').attr('required', false);
			}
	});

	$(document).on('change', '#type_personal', function () {
			var selected_item = $('#type_personal').val();

			if (selected_item == 1) {
				$('#exportTables #is_dates_personal').attr('hidden', false);
				$('#exportTables #personal_date_end').attr('required', true);
				$('#exportTables #personal_date_start').attr('required', true);
			} else {
				$('#exportTables #is_dates_personal').attr('hidden', true);
				$('#exportTables #personal_date_end').attr('required', false);
				$('#exportTables #personal_date_start').attr('required', false);
			}
	});

	$(document).on('click', '#createActivityButton', function () {
		$('#createActivity').find('#activity_name').val('');
		$('#createActivity').find('#activity_code').val('');
		$('#createActivity').find('#activity_manager').val(0);
		$('#createActivity').find('#activity_priority_id').val(null);
		$('#createActivity').find('#activity_state_id').val(1);
		$('#createActivity').find('#activity_business_id').val(0);
		$('#createActivity').find('#activity_start_p').val(null);
		$('#createActivity').find('#activity_type_id').val(null);
		$('#createActivity').find('#activity_date_requested').val(null);
		$('#createActivity').find('#activity_end_p').val(null);
		$('#createActivity').find('#activity_deputy').val(0);
		$('#createActivity').find('#activity_enveloppe').val(null);
		$('#createActivity').find('#activity_date_limit').val(null);
		$('#createActivity').find('#activity_description').val('');
		$('#createActivity').find('#activity_private').val(0);
		$('#createActivity').find('#activity_charges_investment').val(null);
		$('#createActivity').find('#activity_charges_operation').val(null);
	});

	$(document).on('click', '#updateActivityButton', function () {
		var data = JSON.parse($(this).data('activity'));
		$.each(data, function (key, value) {
			$('#updateActivity #' + key).val(value);
		});

		$("#updateActivity #activity_id").text('id='.concat(data.activity_id));
		$('#updateActivity #activity_name').text(data.activity_name);
	});

	/*SHOW PROJECT DETAILS*/
	$(document).on('click', '#detailsActivityButton', function () {
		var data_name = JSON.parse($(this).data('activity_name'));
		$("#detailsActivity #activity_name").text(data_name);
		$("#activity_details_table").empty(); // clear var after btn click
		$("#activitiesDetailsInput").val(''); // clear var after btn click

		$.getJSON('activities/details/' + $(this).data('activity_id'), function (data) {

			$.each(data.tasksForPhase, function (key, value) {
				//format date and numbers
				var cra_validate = value.cra_validate;
				var dateStringStartP = new Date(value.task_start_p);
				var dateStringEndP = new Date(value.task_end_p);
				var display_task_start_p = ('0' + dateStringStartP.getDate()).slice(-2) + '/' + ('0' + (dateStringStartP.getMonth() + 1)).slice(-2) + '/' + dateStringStartP.getFullYear();
				var display_task_end_p = ('0' + dateStringEndP.getDate()).slice(-2) + '/' + ('0' + (dateStringEndP.getMonth() + 1)).slice(-2) + '/' + dateStringEndP.getFullYear();

				if (value.task_days_p != null || value.task_days_r) {
					var val_task_days_p = value.task_days_p;
					var val_task_days_r = value.task_days_r
				} else {
					var val_task_days_p = 0;
					var val_task_days_r = 0
				}
				var display_task_days_p = $.number(value.task_days_p, 3, ',', ' ');
				var display_task_days_r = $.number(value.task_days_r, 3, ',', ' ');

				var status_flag = '<tr>';
				
				if (cra_validate == false)
				{
					if (value.task_status == 1) var status_flag = '<tr class="tr-task-terminated">'; 
					if (value.task_status == 2) var status_flag = '<tr class="tr-task-not-validated">'; 
					if (value.task_status == 3) var status_flag = '<tr class="tr-task-validated">'; 
				}

				if (cra_validate == true)
				{
					if (value.task_status == 1) var status_flag = '<tr class="tr-task-terminated">'; 
					if (value.task_status == 2) var status_flag = '<tr class="tr-task-not-validated">'; 
					if (value.task_status == 3) var status_flag = '<tr class="tr-task-validated">'; 
				}

				
				//create table + add values
				var line1 = $('<td class="text-left wrap-yes action-btn-no-body truncate-details">').attr("data-value", value.task_phase_name).text(value.task_phase_name);

				//Utile pour debug des cra, à enlever une fois que tout est testé
				//var line2 = $('<td class="text-left wrap-yes truncate-large">').attr("data-value", value.task_name).text(value.task_name);
				var line2 = $('<td class="text-left wrap-yes truncate-large">').attr("data-value", value.task_name).text(value.task_name.concat(' (').concat(value.task_status).concat(')'));
				
				var line3 = $('<td class="text-left wrap-yes truncate-details">').attr("data-value", value.task_full_name).text(value.task_full_name);
				var line4 = $('<td class="text-left wrap-yes truncate-details">').attr("data-value", value.task_type_name).text(value.task_type_name);
				var line5 = $('<td class="text-center wrap-yes truncate-small">').attr("data-value", value.task_start_p).text(display_task_start_p);
				var line6 = $('<td class="text-center wrap-yes truncate-small">').attr("data-value", value.task_end_p).text(display_task_end_p);
				var line7 = $('<td class="style-prevu text-right">').attr("data-value", val_task_days_p).text(display_task_days_p);
				var line8 = $('<td class="style-realise text-right">').attr("data-value", val_task_days_r).text(display_task_days_r);
				var $details_info = $(status_flag).append(line1, line2, line3, line4, line5, line6, line7, line8).appendTo('#activity_details_table');
			});
		});
	});

	//DELETE PROJECT
	$(document).on('click', '#deleteActivityButton', function () {
		var _activity_id = JSON.parse($(this).data('activity_id'));
		var _activity_name = JSON.parse($(this).data('activity_name'));

		$('#deleteActivity #activity_id').val(_activity_id);
		$("#deleteActivity #activity_id").text('id='.concat(_activity_id));
		$('#deleteActivity #activity_name').text(_activity_name);

	});

	//TERMINATE
	$(document).on('click', '#terminateActivityButton', function () {
		var activity_id = JSON.parse($(this).data('activity_id'));
		var data_name = JSON.parse($(this).data('activity_name'));

		$("#terminateActivity #activity_name").text(data_name);
		$('#terminateActivity #activity_id').val(activity_id);
	});

	//ACTIVATE
	$(document).on('click', '#activateActivityButton', function () {
		var activity_id = JSON.parse($(this).data('activity_id'));
		var data_name = JSON.parse($(this).data('activity_name'));

		$("#activateActivity #activity_name").text(data_name);
		$('#activateActivity #activity_id').val(activity_id);
	});

	//PRIVACY
	$(document).on('click', '#privacyActivityButton', function () {
		var activity_id = JSON.parse($(this).data('activity_id'));
		var activity_name = JSON.parse($(this).data('activity_name'));

		$('#privacyActivity #activity_id').val(activity_id);
		$('#privacyActivity #activity_name').text(activity_name);
	});


	$('#activity_create').submit(function(event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#activity_create').find( 'select, textarea, input' ).each(function(){
			if(!$(this).prop('hidden')){
				if( $(this).prop('required')){
					//c'est ici qu'on testera les valeurs et expressions régulières
					//pour le moment on ne teste que si le champ est vide
					if (!$(this).val() ) {
						fail = true;
						name = $(this).attr('name');
						fail_log += name + " is required.\n";
					}
				}
			}
		});
		console.log(fail_log);

		if ( fail ) {
			//c'est nok, on annule le submit
			event.preventDefault();
		}
		else {
			//c'est ok, on lance l'affichage du loading
			$(this).find('#btn-submit-form').addClass('apply-spin');
		}
	});

	$('#activity_update').submit(function(event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#activity_update').find( 'select, textarea, input' ).each(function(){
			if(!$(this).prop('hidden')){
				if( $(this).prop('required')){
					//c'est ici qu'on testera les valeurs et expressions régulières
					//pour le moment on ne teste que si le champ est vide
					if (!$(this).val() ) {
						fail = true;
						name = $(this).attr('name');
						fail_log += name + " is required.\n";
					}
				}
			}
		});
		console.log(fail_log);

		if ( fail ) {
			//c'est nok, on annule le submit
			event.preventDefault();
		}
		else {
			//c'est ok, on lance l'affichage du loading
			$(this).find('#btn-submit-form').addClass('apply-spin');
		}
	});

	$('#activity_export').submit(function(event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#activity_export').find( 'select, textarea, input' ).each(function(){
			if(!$(this).prop('hidden')){
				if( $(this).prop('required')){
					//c'est ici qu'on testera les valeurs et expressions régulières
					//pour le moment on ne teste que si le champ est vide
					if (!$(this).val() ) {
						fail = true;
						name = $(this).attr('name');
						fail_log += name + " is required.\n";
					}
				}
			}
		});
		console.log(fail_log);

		if ( fail ) {
			//c'est nok, on annule le submit
			event.preventDefault();
		}
		else {
			//c'est ok, on lance l'affichage du loading
			$(this).find('#btn-submit-form').addClass('apply-spin');
		}
	});

	$('#charges_export').submit(function(event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#charges_export').find( 'select, textarea, input' ).each(function(){
			if(!$(this).prop('hidden')){
				if( $(this).prop('required')){
					//c'est ici qu'on testera les valeurs et expressions régulières

					if ( $('#exportTables #charge_date_end').val() && $('#exportTables #charge_date_start').val() ){

						if ( $('#exportTables #charge_date_end').val() < $('#exportTables #charge_date_start').val() ){
							fail = true;
							name = $('#exportTables #charge_date_end').attr('name');
							name_start = $('#exportTables #charge_date_start').attr('name');
							fail_log += name + " > " + name_start + ".\n";
							$('#exportTables #date_error_gap').attr('hidden', false);
						}
						else {
							$('#exportTables #date_error_gap').attr('hidden', true);
						}
					}

					if (!$(this).val() ) {
						fail = true;
						name = $(this).attr('name');
						fail_log += name + " is required.\n";

					}
				}
			}
		});
		console.log(fail_log);

		if ( fail ) {
			//c'est nok, on annule le submit
			event.preventDefault();
		}
		else {
			//c'est ok, on lance l'affichage du loading
			$(this).find('#btn-submit-form').addClass('apply-spin');
			$('#exportTables').modal('hide');
		}
	});

	$('#personal_export').submit(function(event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#personal_export').find( 'select, textarea, input' ).each(function(){
			if(!$(this).prop('hidden')){
				if( $(this).prop('required')){
					//c'est ici qu'on testera les valeurs et expressions régulières

					if ( $('#exportTables #personal_date_end').val() && $('#exportTables #personal_date_start').val() ){

						if ( $('#exportTables #personal_date_end').val() < $('#exportTables #personal_date_start').val() ){
							fail = true;
							name = $('#exportTables #personal_date_end').attr('name');
							name_start = $('#exportTables #personal_date_start').attr('name');
							fail_log += name + " > " + name_start + ".\n";
							$('#exportTables #date_error_gap_personal').attr('hidden', false);
						}
						else {
							$('#exportTables #date_error_gap_personal').attr('hidden', true);
						}
					}

					if (!$(this).val() ) {
						fail = true;
						name = $(this).attr('name');
						fail_log += name + " is required.\n";

					}
				}
			}
		});
		console.log(fail_log);

		if ( fail ) {
			//c'est nok, on annule le submit
			event.preventDefault();
		}
		else {
			//c'est ok, on lance l'affichage du loading
			$(this).find('#btn-submit-form').addClass('apply-spin');
			$('#exportTables').modal('hide');
		}
	});

});


	//bootstrap input validation
	(function () {
		'use strict';
		window.addEventListener('load', function () {
			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			// Loop over them and prevent submission
			var validation = Array.prototype.filter.call(forms, function (form) {
				form.addEventListener('submit', function (event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					}
					form.classList.add('was-validated');
				}, false);
			});
		}, false);
	})();



	
