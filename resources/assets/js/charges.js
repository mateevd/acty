/*SHOW PROJECT DETAILS*/
$(document).on('click', '#detailsChargesButton', function () {
	var user_id = JSON.parse($(this).data('user_id'));
	var charge_month = JSON.parse($(this).data('charge_month'));
	var charge_year = JSON.parse($(this).data('charge_year'));
	var full_name = $(this).data('full_name');

	$("#detailsCharges #user_id").text(user_id);
	$("#detailsCharges #charge_month").text(charge_month);
	$("#detailsCharges #charge_year").text(charge_year);
	$("#detailsCharges #full_name").text(full_name);


	$("#charges_details_table").empty(); // clear var after btn click
	$("#chargesDetailsInput").val(''); // clear var after btn click

	$.getJSON('charges/details/' + user_id + '/' + charge_month + '/' + charge_year, function (data) {

		$.each(data.detailCharge, function (key, value) {

			//format date and numbers
			var dateStringStartP = new Date(value.task_start_p);
			var dateStringEndP = new Date(value.task_end_p);
			var display_task_start_p = ('0' + dateStringStartP.getDate()).slice(-2) + '/' + ('0' + (dateStringStartP.getMonth() + 1)).slice(-2) + '/' + dateStringStartP.getFullYear();
			var display_task_end_p = ('0' + dateStringEndP.getDate()).slice(-2) + '/' + ('0' + (dateStringEndP.getMonth() + 1)).slice(-2) + '/' + dateStringEndP.getFullYear();

			var val_task_days_p = 0;
			var val_task_days_r = 0;
			if (value.task_days_p) val_task_days_p = value.task_days_p;
			if (value.task_days_r) val_task_days_r = value.task_days_r;

			var display_task_days_p = $.number(value.task_days_p, 3, ',', ' ');
			var display_task_days_r = $.number(value.task_days_r, 3, ',', ' ');

			
			if (value.task_status == 1) var status_flag = '<tr class="tr-task-terminated">'; else var status_flag = '<tr>';
			if (value.task_status == 2) var status_flag = '<tr class="tr-task-not-validated">'; else var status_flag = '<tr>';
			if (value.task_status == 3) var status_flag = '<tr class="tr-task-validated">'; else var status_flag = '<tr>';

			//create table + add values
			var line_activity_name = $('<td class="text-left truncate-details">').attr("data-value", value.activity_name).text(value.activity_name);
			var line_phase_name = $('<td class="text-left truncate-details">').attr("data-value", value.phase_name).text(value.phase_name);
			var line_task_name = $('<td class="text-left truncate-details">').attr("data-value", value.task_name).text(value.task_name.concat(" - ").concat(value.task_status));
			var line_task_type_name = $('<td class="text-left truncate-details">').attr("data-value", value.task_type_name).text(value.task_type_name);
			var line_task_start_p = $('<td class="text-center truncate-details">').attr("data-value", value.task_start_p).text(display_task_start_p);
			var line_task_end_p = $('<td class="text-center truncate-details">').attr("data-value", value.task_end_p).text(display_task_end_p);
			var line_task_days_p = $('<td class="style-prevu text-right">').attr("data-value", val_task_days_p).text(display_task_days_p);
			var line_task_days_r = $('<td class="style-realise text-right">').attr("data-value", val_task_days_r).text(display_task_days_r);
			var $details_info = $(status_flag).append(line_activity_name,
				line_phase_name,
				line_task_name,
				line_task_type_name,
				line_task_start_p,
				line_task_end_p,
				line_task_days_p,
				line_task_days_r).appendTo('#charges_details_table');
		});
	});
});

$(function () {
	// Available panels
	// #absence_user
	// #absence_direction
	var tab_active = localStorage.getItem('charges-tab-active');
	if (tab_active) {
		$('#charges-tab-selection a[href="' + tab_active + '"]').tab('show');
	}
	else {
		$('#charges-tab-selection a[href="#charges_all"]').tab('show'); //default panel
	}

	$('#charges-tab-selection a[data-toggle="tab"]').click( function (e) {
		var _target = $(e.target).attr('href');
		if (!_target) _target = $(this).attr('href');

		localStorage.setItem('charges-tab-active', _target);
		$('#charges-tab-selection a[href="' + _target + '"]').tab('show');
	});
});
