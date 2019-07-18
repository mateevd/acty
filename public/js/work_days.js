/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 37);
/******/ })
/************************************************************************/
/******/ ({

/***/ 37:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(38);


/***/ }),

/***/ 38:
/***/ (function(module, exports) {

$(function () {

	// Available panels
	// #times_user
	// #times_entity
	// var tab_active = localStorage.getItem('wdays-tab-active');
	// if (tab_active) {
	// 	$('#wdays-tab-selection a[href="' + tab_active + '"]').tab('show');
	// }
	// else {
	// 	$('#wdays-tab-selection a[href="#times_user"]').tab('show'); //default panel
	// }
	//
	// $('#wdays-tab-selection a[data-toggle="tab"]').click( function (e) {
	// 	var _target = $(e.target).attr('href');
	// 	if (!_target) _target = $(this).attr('href');
	//
	// 	localStorage.setItem('wdays-tab-active', _target);
	// 	$('#wdays-tab-selection a[href="' + _target + '"]').tab('show');
	// });


	//HOURS (TASKS PAGES)
	$(document).on('click', '#addHoursButton', function () {
		//Initilisations - DEB
		$('#addHours #is_history').attr('hidden', true);

		$('#addHours').find('#work_day_days').attr('count', 0);
		$('#addHours').find('#work_day_days').attr('value', 0);
		$('#addHours').find('#hours').attr('count', 0);
		$('#addHours').find('#hours').attr('value', 0);

		var _task_show = JSON.parse($(this).data('task_show'));

		if (_task_show.task_start_p) {
			var _start_p = new Date(_task_show.task_start_p);
			var show_start_p = ('0' + (_start_p.getMonth() + 1)).slice(-2) + '/' + _start_p.getFullYear();
			$("#addHours #task_date").text('('.concat(show_start_p).concat(')'));
		}

		if (_task_show.task_description) {
			$('#addHours #is_description').attr('hidden', false);
			$('#addHours #task_description').text(_task_show.task_description);
		} else $('#addHours #is_description').attr('hidden', true);

		$('#addHours #task_name').text(_task_show.task_name);
		$('#addHours #task_type').text(_task_show.task_type_name);
		$('#addHours #phase_id').val(parseInt(_task_show.phase_id));
		$('#addHours #task_id').val(parseInt(_task_show.task_id));
		$('#addHours #task_id').text('id='.concat(_task_show.task_id));

		$('#addHours #task_phase_name').text(_task_show.phase_name);

		$('#addHours #activity_id').val(parseInt(_task_show.activity_id));
		$('#addHours #task_activity_name').text(_task_show.activity_name);
		$('#addHours #task_activity_cpi_name').text(_task_show.full_name_manager);

		//Création de l'historique - DEB
		$.getJSON('wdays/show/' + _task_show.task_id, function (data) {

			$("#wdays_table").empty();

			$.each(data.workDays, function (key, value) {

				//Affichage du tableau
				$('#addHours #is_history').attr('hidden', false);

				//Formattage de la date
				var dateString = new Date(value.work_day_date);
				var display_date = ('0' + dateString.getDate()).slice(-2) + '/' + ('0' + (dateString.getMonth() + 1)).slice(-2) + '/' + dateString.getFullYear();

				//Formattage de l'affichage - Darin : à remplacer par les fonctions jquery.numer.js
				var parsed_days = parseFloat(value.work_day_days);
				var formatted_days = parsed_days.toString().replace(",", " ").replace(".", ",");

				//Création des lignes
				var status_flag = '<tr>';
				if (value.work_day_status == 1) status_flag = '<tr class="tr-task-terminated">';
				if (value.work_day_status == 2) status_flag = '<tr class="tr-task-not-validated">';
				if (value.work_day_status == 3) status_flag = '<tr class="tr-task-validated">';

				var current_description = "(".concat(value.work_day_status).concat(")");
				if (value.work_day_description) current_description = value.work_day_description.concat(current_description);

				var line_days = $('<td>').attr("class", "text-right").attr("data-value", parsed_days).text(formatted_days);
				var line_date = $('<td>').attr("class", "text-center").attr("data-value", value.work_day_date).text(display_date);
				var line_description = $('<td>').attr("class", "wrap-yes truncate-large").attr("data-value", value.work_day_description).text(current_description);
				var line_user_trigram = $('<td>').attr("class", "text-center").attr("data-value", value.user_trigramme).text(value.user_trigramme);

				var $wday_info = $(status_flag).append(line_days, line_date, line_description, line_user_trigram).appendTo('#wdays_table');
			});
		});
		//Création de l'historique - FIN

		//Calculs - DEB
		$('#addHours #work_day_days').val(0);
		$('#addHours #hours').val(0);
		var counter = 0;

		$("#hoursBtns .modal-custom-btn-hours").on('click', function () {
			var val = parseFloat($(this).attr('value'));
			counter += val;
			$('#addHours').find('#work_day_days').attr('count', counter);
			$('#addHours').find('#work_day_days').val(counter);
			$('#addHours').find('#hours').attr('count', counter * 8);
			$('#addHours').find('#hours').val(counter * 8);
		});

		$("#delhoursBtn .modal-custom-btn-hours-reset").on('click', function () {
			counter = 0;
			$('#addHours #work_day_days').val(0);
			$('#addHours #hours').val(0);
			$('#addHours').find('#work_day_days').attr('count', 0);
			$('#addHours').find('#work_day_days').attr('value', 0);
			$('#addHours').find('#hours').attr('count', 0);
			$('#addHours').find('#hours').attr('value', 0);
		});
		//Calculs - FIN		
	});

	//EDIT TIME
	$(document).on('click', '#editTimeButton', function () {
		//Initilisations - DEB

		$('#editTime').find('#work_day_days').attr('count', 0);
		$('#editTime').find('#work_day_days').attr('value', 0);
		$('#editTime').find('#hours').attr('count', 0);
		$('#editTime').find('#hours').attr('value', 0);

		var work_day = JSON.parse($(this).data('work_day'));

		if (work_day.task_description) {
			$('#editTime #is_description').attr('hidden', false);
			$('#editTime #task_description').text(work_day.task_description);
		} else $('#editTime #is_description').attr('hidden', true);

		$('#editTime #task_name').text(work_day.task_name);
		$('#editTime #task_type').text(work_day.task_type_name);
		$('#editTime #phase_id').val(parseInt(work_day.phase_id));
		$('#editTime #task_id').val(parseInt(work_day.task_id));
		$('#editTime #work_day_id').val(parseInt(work_day.work_day_id));
		$('#editTime #work_day_id').text('id='.concat(work_day.work_day_id));
		$('#editTime #work_day_description').val(work_day.work_day_description);

		$('#editTime #task_phase_name').text(work_day.phase_name);

		$('#editTime #activity_id').val(parseInt(work_day.activity_id));
		$('#editTime #task_activity_name').text(work_day.activity_code);
		$('#editTime #task_activity_cpi_name').text(work_day.activity_manager);

		$("#editTime #work_day_date").val(work_day.work_day_date);

		//Calculs - DEB
		var counter = parseFloat(work_day.work_day_hours);
		$('#editTime #work_day_days').val(counter);
		$('#editTime #hours').val(counter * 8);

		$("#hoursBtns .modal-custom-btn-hours").on('click', function () {
			var val = parseFloat($(this).attr('value'));
			counter += val;
			$('#editTime').find('#work_day_days').attr('count', counter);
			$('#editTime').find('#work_day_days').val(counter);
			$('#editTime').find('#hours').attr('count', counter * 8);
			$('#editTime').find('#hours').val(counter * 8);
		});

		$("#delhoursBtn .modal-custom-btn-hours-reset").on('click', function () {
			counter = parseFloat(work_day.work_day_hours);
			$('#editTime #work_day_days').val(counter);
			$('#editTime #hours').val(counter * 8);
			$('#editTime').find('#work_day_days').attr('count', 0);
			$('#editTime').find('#work_day_days').attr('value', 0);
			$('#editTime').find('#hours').attr('count', 0);
			$('#editTime').find('#hours').attr('value', 0);
		});
		//Calculs - FIN
	});

	//Enlève l'attribut disabled sur input work_day_days - obligatoire
	$(document).on('click', '#btn-submit-form', function () {
		$('#editTime #work_day_days').attr('disabled', false);
		$('#addHours #work_day_days').attr('disabled', false);
	});

	$(document).on('click', '#deleteTimeButton', function () {
		var work_day_id = JSON.parse($(this).data('work_day_id'));
		var task_id = JSON.parse($(this).data('task_id'));
		var phase_id = JSON.parse($(this).data('phase_id'));
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#deleteTime #work_day_id').val(work_day_id);
		$('#deleteTime #task_id').val(task_id);
		$('#deleteTime #phase_id').val(phase_id);
		$('#deleteTime #activity_id').val(activity_id);

		$('#deleteTime #work_day_id').text('id='.concat(work_day_id));
	});

	$(document).on('click', '#validateWD-btn', function () {
		var user_name = JSON.parse($(this).data('user_name'));
		var user_id = JSON.parse($(this).data('user_id'));
		var current_month = JSON.parse($(this).data('current_month'));
		var current_year = JSON.parse($(this).data('current_year'));

		$('#validateWD #user_name').text(user_name);
		$('#validateWD #user_id').val(user_id);
		$('#validateWD #user_id_in').val(user_id);
		$('#validateWD #current_month').val(current_month);
		$('#validateWD #current_year').val(current_year);

		$('#validateWD #user_id').text('id='.concat(user_id));

		$("#wd_validate_table").empty(); // clear var after btn click
		$("#wd_validate_input").val(''); // clear var after btn click

		//Création de la liste des WD
		$.getJSON('wdays/details_to_validate/' + user_id + '/' + current_month + '/' + current_year, function (data) {

			$("#wd_validate_table").empty();

			$.each(data.WD_to_validate, function (key, value) {

				//Formattage de la date
				var dateString = new Date(value.wd_date);
				var display_wd_date = ('0' + dateString.getDate()).slice(-2) + '/' + ('0' + (dateString.getMonth() + 1)).slice(-2) + '/' + dateString.getFullYear();

				var dateString = new Date(value.task_start_p);
				var display_task_start_p = ('0' + (dateString.getMonth() + 1)).slice(-2) + '/' + dateString.getFullYear();

				//Formattage de l'affichage - Darin : à remplacer par les fonctions jquery.numer.js
				var parsed_days = parseFloat(value.wd_days);
				// var formatted_days = ((value.wd_days.toString()).replace(",", " ")).replace(".", ",");
				// var formatted_days = new Intl.NumberFormat().format(value.wd_days);
				var formatted_days = parsed_days.toLocaleString(undefined, // leave undefined to use the browser's locale,
				// or use a string like 'en-US' to override it.
				{ minimumFractionDigits: 3 });

				//Création des lignes
				var status_flag = '<tr>';
				if (value.wd_status == 1) status_flag = '<tr class="tr-task-terminated">';
				if (value.wd_status == 2) status_flag = '<tr class="tr-task-not-validated">';
				if (value.wd_status == 3) status_flag = '<tr class="tr-task-validated">';

				var current_description = "(".concat(value.wd_id).concat("-").concat(value.wd_status).concat(")");

				if (value.wd_description) current_description = value.wd_description.concat(current_description);

				var btn_td = $('<td>').attr("class", "pad-h-none");
				var btn_div = $('<div>').attr("class", "flex-row flex-wrap-yes justify-center align-center width-rem-3");
				var btn_input = $('<input>').attr("class", "multi-wd-select-checkboxes").attr("type", "checkbox").attr("name", "selectWDs[]").attr("value", value.wd_id);

				var btn_construction = btn_td.append(btn_div.append(btn_input));

				var line_activity_name = $('<td class="text-left truncate-details">').attr("data-value", value.activity_code).text(value.activity_code);
				var line_phase_name = $('<td class="text-left truncate-details">').attr("data-value", value.phase_name).text(value.phase_name);
				var line_task_name = $('<td class="text-left truncate-details">').attr("data-value", value.task_name).text(value.task_name.concat(" - ").concat(value.task_status));
				var line_task_type_name = $('<td class="text-left truncate-details">').attr("data-value", value.task_type_name).text(value.task_type_name);
				var line_task_start_p = $('<td class="text-center truncate-details">').attr("data-value", value.task_start_p).text(display_task_start_p);
				var line_wd_date = $('<td>').attr("class", "text-center").attr("data-value", value.wd_date).text(display_wd_date);
				var line_wd_desc = $('<td>').attr("class", "text-left wrap-yes truncate-large").attr("data-value", value.work_day_description).text(current_description);
				var line_wd_days = $('<td class="style-realise text-right">').attr("data-value", parsed_days).text(formatted_days);

				var $details_info = $(status_flag).append(btn_construction, line_activity_name, line_phase_name, line_task_name, line_task_type_name, line_task_start_p, line_wd_date, line_wd_desc, line_wd_days).appendTo('#wd_validate_table');
			});
		});
	});

	$(document).on('click', '#denyWD-btn', function () {
		var user_name = JSON.parse($(this).data('user_name'));
		var user_id = JSON.parse($(this).data('user_id'));
		var current_month = JSON.parse($(this).data('current_month'));
		var current_year = JSON.parse($(this).data('current_year'));

		$('#denyWD #user_name').text(user_name);
		$('#denyWD #user_id').val(user_id);
		$('#denyWD #user_id_in').val(user_id);
		$('#denyWD #current_month').val(current_month);
		$('#denyWD #current_year').val(current_year);

		$('#denyWD #user_id').text('id='.concat(user_id));

		$("#wd_deny_table").empty(); // clear var after btn click
		$("#wd_deny_input").val(''); // clear var after btn click

		//Création de la liste des WD
		$.getJSON('wdays/details_to_deny/' + user_id + '/' + current_month + '/' + current_year, function (data) {

			$("#wd_deny_table").empty();

			$.each(data.WD_to_deny, function (key, value) {

				//Formattage de la date
				var dateString = new Date(value.wd_date);
				var display_wd_date = ('0' + dateString.getDate()).slice(-2) + '/' + ('0' + (dateString.getMonth() + 1)).slice(-2) + '/' + dateString.getFullYear();

				var dateString = new Date(value.task_start_p);
				var display_task_start_p = ('0' + (dateString.getMonth() + 1)).slice(-2) + '/' + dateString.getFullYear();

				//Formattage de l'affichage - Darin : à remplacer par les fonctions jquery.numer.js
				var parsed_days = parseFloat(value.wd_days);
				// var formatted_days = ((value.wd_days.toString()).replace(",", " ")).replace(".", ",");
				var formatted_days = parsed_days.toLocaleString(undefined, // leave undefined to use the browser's locale,
				// or use a string like 'en-US' to override it.
				{ minimumFractionDigits: 3 });

				//Création des lignes
				var status_flag = '<tr>';
				if (value.wd_status == 1) status_flag = '<tr class="tr-task-terminated">';
				if (value.wd_status == 2) status_flag = '<tr class="tr-task-not-validated">';
				if (value.wd_status == 3) status_flag = '<tr class="tr-task-validated">';

				var current_description = "(".concat(value.wd_id).concat("-").concat(value.wd_status).concat(")");

				if (value.wd_description) current_description = value.wd_description.concat(current_description);

				var btn_td = $('<td>').attr("class", "pad-h-none");
				var btn_div = $('<div>').attr("class", "flex-row flex-wrap-yes justify-center align-center width-rem-3");
				var btn_input = $('<input>').attr("class", "multi-wd-select-checkboxes").attr("type", "checkbox").attr("name", "selectWDs[]").attr("value", value.wd_id);

				var btn_construction = btn_td.append(btn_div.append(btn_input));

				var line_activity_name = $('<td class="text-left truncate-details">').attr("data-value", value.activity_code).text(value.activity_code);
				var line_phase_name = $('<td class="text-left truncate-details">').attr("data-value", value.phase_name).text(value.phase_name);
				var line_task_name = $('<td class="text-left truncate-details">').attr("data-value", value.task_name).text(value.task_name.concat(" - ").concat(value.task_status));
				var line_task_type_name = $('<td class="text-left truncate-details">').attr("data-value", value.task_type_name).text(value.task_type_name);
				var line_task_start_p = $('<td class="text-center truncate-details">').attr("data-value", value.task_start_p).text(display_task_start_p);
				var line_wd_date = $('<td>').attr("class", "text-center").attr("data-value", value.wd_date).text(display_wd_date);
				var line_wd_desc = $('<td>').attr("class", "text-left wrap-yes truncate-large").attr("data-value", value.work_day_description).text(current_description);
				var line_wd_days = $('<td class="style-realise text-right">').attr("data-value", parsed_days).text(formatted_days);

				var $details_info = $(status_flag).append(btn_construction, line_activity_name, line_phase_name, line_task_name, line_task_type_name, line_task_start_p, line_wd_date, line_wd_desc, line_wd_days).appendTo('#wd_deny_table');
			});
		});
	});

	$('#wday_validate').submit(function (event) {

		var multi_wd_ids = [];

		$('.multi-wd-select-checkboxes').each(function () {
			if ($(this).is(':checked')) {
				multi_wd_ids.push(this.value);
			}
		});

		$('#validateWD #wd_id').val(multi_wd_ids);
	});

	$('#wday_deny').submit(function (event) {

		var multi_wd_ids = [];

		$('.multi-wd-select-checkboxes').each(function () {
			if ($(this).is(':checked')) {
				multi_wd_ids.push(this.value);
			}
		});

		$('#denyWD #wd_id').val(multi_wd_ids);
	});

	$(document).on('click', '#validateUserAllWD-btn', function () {
		var user_name = JSON.parse($(this).data('user_name'));
		var user_id = JSON.parse($(this).data('user_id'));
		var current_month = JSON.parse($(this).data('current_month'));
		var current_year = JSON.parse($(this).data('current_year'));

		$('#validateUserAllWD #user_name').text(user_name);
		$('#validateUserAllWD #user_id').val(user_id);
		$('#validateUserAllWD #user_id_in').val(user_id);
		$('#validateUserAllWD #current_month').val(current_month);
		$('#validateUserAllWD #current_year').val(current_year);

		$('#validateUserAllWD #user_id').text('id='.concat(user_id));
	});

	$(document).on('click', '#denyUserAllWD-btn', function () {
		var user_name = JSON.parse($(this).data('user_name'));
		var user_id = JSON.parse($(this).data('user_id'));
		var current_month = JSON.parse($(this).data('current_month'));
		var current_year = JSON.parse($(this).data('current_year'));

		$('#denyUserAllWD #user_name').text(user_name);
		$('#denyUserAllWD #user_id').val(user_id);
		$('#denyUserAllWD #user_id_in').val(user_id);
		$('#denyUserAllWD #current_month').val(current_month);
		$('#denyUserAllWD #current_year').val(current_year);

		$('#denyUserAllWD #user_id').text('id='.concat(user_id));
	});

	$(document).on('click', '#validateAllWD-btn', function () {
		var user_id = JSON.parse($(this).data('user_id'));
		var current_month = JSON.parse($(this).data('current_month'));
		var current_year = JSON.parse($(this).data('current_year'));

		$('#validateAllWD #user_id').val(user_id);
		$('#validateAllWD #user_id_in').val(user_id);
		$('#validateAllWD #current_month').val(current_month);
		$('#validateAllWD #current_year').val(current_year);

		$('#validateAllWD #user_id').text('id='.concat(user_id));
	});

	$(document).on('click', '#denyAllWD-btn', function () {
		var user_id = JSON.parse($(this).data('user_id'));
		var current_month = JSON.parse($(this).data('current_month'));
		var current_year = JSON.parse($(this).data('current_year'));

		$('#denyAllWD #user_id').val(user_id);
		$('#denyAllWD #user_id_in').val(user_id);
		$('#denyAllWD #current_month').val(current_month);
		$('#denyAllWD #current_year').val(current_year);

		$('#denyAllWD #user_id').text('id='.concat(user_id));
	});

	$('#wday_create').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#wday_create').find('select, textarea, input').each(function () {
			if (!$(this).prop('hidden')) {
				if ($(this).prop('required')) {
					//c'est ici qu'on testera les valeurs et expressions régulières
					//pour le moment on ne teste que si le champ est vide
					if (!$(this).val()) {
						fail = true;
						name = $(this).attr('name');
						fail_log += name + " is required.\n";
					}
				}
			}
		});
		console.log(fail_log);

		if (fail) {
			//c'est nok, on annule le submit
			event.preventDefault();
		} else {
			//c'est ok, on lance l'affichage du loading
			$(this).find('#btn-submit-form').addClass('apply-spin');
		}
	});

	$('#wday_update').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#wday_update').find('select, textarea, input').each(function () {
			if (!$(this).prop('hidden')) {
				if ($(this).prop('required')) {
					//c'est ici qu'on testera les valeurs et expressions régulières
					//pour le moment on ne teste que si le champ est vide
					if (!$(this).val()) {
						fail = true;
						name = $(this).attr('name');
						fail_log += name + " is required.\n";
					}
				}
			}
		});
		console.log(fail_log);

		if (fail) {
			//c'est nok, on annule le submit
			event.preventDefault();
		} else {
			//c'est ok, on lance l'affichage du loading
			$(this).find('#btn-submit-form').addClass('apply-spin');
		}
	});
});

/***/ })

/******/ });