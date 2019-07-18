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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
__webpack_require__(16);
__webpack_require__(17);
__webpack_require__(18);
__webpack_require__(19);
__webpack_require__(20);
__webpack_require__(21);
__webpack_require__(22);
__webpack_require__(23);
module.exports = __webpack_require__(24);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/** SPINNER ANIMATION **/
$(window).on("load", function () {
	$('#main-div').show();
	$('#loading-message').hide();
	$('#spinner-top').hide();
	$('#footer-text').show();
});

$(document).on('click', '.apply-spin', function () {
	$('#footer-text').hide();
	$('#spinner-top').show();
	$('#loading-message').show();
	$('#main-div').hide();
});

$(document).ready(function () {
	__webpack_require__(2);
	__webpack_require__(3);
	__webpack_require__(4);
	__webpack_require__(5);
	__webpack_require__(6);
	__webpack_require__(7);
	__webpack_require__(8);
	__webpack_require__(9);
	__webpack_require__(10);
	__webpack_require__(11);
	__webpack_require__(12);
	__webpack_require__(13);
	__webpack_require__(14);
	__webpack_require__(15);

	window.onscroll = function () {
		scrollFunction();
	};
	//Show btn after scroll
	function scrollFunction() {
		if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
			document.getElementById("back_to_top").style.display = "block";
		} else {
			document.getElementById("back_to_top").style.display = "none";
		}
	}

	//Back to top on click
	var el = document.getElementById("back_to_top");
	el.addEventListener("click", function (e) {
		e.preventDefault();
		$('html, body').animate({ scrollTop: 0 }, '130');
	}, false);

	/** FLASHY MESSAGES**/
	function flashy(message, link) {
		var template = $($("#flashy-template").html());
		$(".flashy").remove();
		template.find(".flashy__body").html(message).attr("href", link || "#").end().appendTo("body").hide().fadeIn(300).delay(2800).animate({
			marginRight: "-100%"
		}, 300, "swing", function () {
			$(this).remove();
		});
	}

	/** BOOTSTRAP SORT COLUMS**/
	function sortSelect(selElem, sortVal) {
		// Checks for an object or string. Uses string as ID.
		switch (typeof selElem === 'undefined' ? 'undefined' : _typeof(selElem)) {
			case "string":
				selElem = document.getElementById(selElem);
				break;
			case "object":
				if (selElem == null) return false;
				break;
			default:
				return false;
		}

		// Builds the options list.
		var tmpAry = new Array();
		for (var i = 0; i < selElem.options.length; i++) {
			tmpAry[i] = new Array();
			tmpAry[i][0] = selElem.options[i].text;
			tmpAry[i][1] = selElem.options[i].value;
		}

		// allows sortVal to be optional, defaults to text.
		switch (sortVal) {
			case "value":
				// sort by value
				sortVal = 1;
				break;
			default:
				// sort by text
				sortVal = 0;
		}
		tmpAry.sort(function (a, b) {
			return a[sortVal] == b[sortVal] ? 0 : a[sortVal] < b[sortVal] ? -1 : 1;
		});

		// removes all options from the select.
		while (selElem.options.length > 0) {
			selElem.options[0] = null;
		}

		// recreates all options with the new order.
		for (var i = 0; i < tmpAry.length; i++) {
			var op = new Option(tmpAry[i][0], tmpAry[i][1]);
			selElem.options[i] = op;
		}

		return true;
	}

	/** UNIVERSAL FILTERS**/
	$("#filterInput").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#filter_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
		$("#user_card h1").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	$("#activitiesDetailsInput").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#activity_details_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	$("#chargesDetailsInput").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#charges_details_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	$("#wd_validate_input").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#wd_validate_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	$("#wd_deny_input").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#wd_deny_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	/** SESSION TIMEOUT LOGOUT + REDIRECT**/
	// $(function () {
	// 	var session_timeout = process.env.MIX_SESSION_LIFETIME;
	//
	// 	$.idleTimer(session_timeout * 60000);
	//
	// 	$(document).on("idle.idleTimer", function () {
	// 		var dest_url = '/logout';
	// 		$.ajax({
	// 			type: "POST",
	// 			url: dest_url,
	// 			data: {_token: $('meta[name="csrf-token"]').attr('content')},
	// 			success: function () {
	// 				window.location.href = '/login';
	// 			}
	// 		});
	// 	});
	// });
	//
	// //success message timeout
	// setTimeout(function () {
	// 	$('#successMessage').fadeOut('fast');
	// }, 5000);

	//disable dropdown if att readonly
	if ($(".select_false").attr("readonly")) {
		$(".select_false").css("pointer-events", "none");
	}

	// $(function () {
	// 	$('[data-toggle="popover"]').popover()
	// });
	// $('.popover-dismiss').popover({
	// 	trigger: 'focus'
	// });

	$(".readonly").keydown(function (e) {
		e.preventDefault();
	});

	$('#date_change').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#date_change').find('select, textarea, input').each(function () {
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
			$(this).find('#btn-submit-form-date').addClass('apply-spin');
		}
	});
});

/***/ }),
/* 2 */
/***/ (function(module, exports) {

$(function () {

	// Available panels
	// #absence_user
	// #absence_direction
	var tab_active = localStorage.getItem('absences-tab-active');
	if (tab_active) {
		$('#absences-tab-selection a[href="' + tab_active + '"]').tab('show');
	} else {
		$('#absences-tab-selection a[href="#absence_user"]').tab('show'); //default panel
	}

	$('#absences-tab-selection a[data-toggle="tab"]').click(function (e) {
		var _target = $(e.target).attr('href');
		if (!_target) _target = $(this).attr('href');

		localStorage.setItem('absences-tab-active', _target);
		$('#absences-tab-selection a[href="' + _target + '"]').tab('show');
	});

	//DAYS
	$(document).on('click', '#addAbsenceButton', function () {
		$('#addAbsence').find('#absence_days').attr('count', 0);
		$('#addAbsence').find('#absence_days').attr('value', 0);
		$('#addAbsence').find('#absence_description').val('');
		$('#addAbsence').find('#absence_type').val(1);
		$('#addAbsence').find('#occurenceSelect').val(1);

		$('#addAbsence #absence_days').val(0);
		var counter = 0;

		$("#hoursBtns .modal-custom-btn-absence").on('click', function () {
			var val = parseFloat($(this).attr('value'));
			counter += val;
			$('#addAbsence').find('#absence_days').attr('count', counter);
			$('#addAbsence').find('#absence_days').val(counter);
		});

		$("#delhoursBtn .modal-custom-btn-absence-reset").on('click', function () {
			counter = 0;
			$('#addAbsence #absence_days').val(0);
			$('#addAbsence').find('#absence_days').attr('count', 0);
			$('#addAbsence').find('#absence_days').attr('value', 0);
		});
	});

	$(document).on('click', '#editAbsenceButton', function () {
		var data = JSON.parse($(this).data('absence'));

		$.each(data, function (key, value) {
			$('#editAbsence #' + key).val(value);
		});

		$('#editAbsence').find('#absence_days').attr('count', 0);
		$('#editAbsence').find('#absence_days').attr('value', 0);

		$("#editAbsence #absence_id").text('id='.concat(data.absence_id));

		//Calculs - DEB
		var counter = parseFloat(data.absence_days);
		$('#editAbsence #absence_days').val(counter);

		$("#hoursBtns .modal-custom-btn-absence").on('click', function () {
			var val = parseFloat($(this).attr('value'));
			counter += val;
			$('#editAbsence').find('#absence_days').attr('count', counter);
			$('#editAbsence').find('#absence_days').val(counter);
		});

		$("#delhoursBtn .modal-custom-btn-absence-reset").on('click', function () {
			counter = parseFloat(data.absence_days);
			$('#editAbsence #absence_days').val(counter);
			$('#editAbsence').find('#absence_days').attr('count', 0);
			$('#editAbsence').find('#absence_days').attr('value', 0);
		});
	});

	//Enlève l'attribut disabled sur les inputs days - obligatoire
	$(document).on('click', '#btn-submit-form', function () {
		$('#editAbsence #absence_days').attr('disabled', false);
		$('#addAbsence #absence_days').attr('disabled', false);
	});

	$(document).on('click', '#deleteAbsenceButton', function () {
		var absence_id = JSON.parse($(this).data('absence_id'));
		$('#deleteAbsence #absence_id').val(absence_id);
		$('#deleteAbsence #absence_id').text('id='.concat(absence_id));
	});

	$('#absence_update').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#absence_update').find('select, textarea, input').each(function () {
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

	$('#absence_create').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#absence_create').find('select, textarea, input').each(function () {
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

/***/ }),
/* 3 */
/***/ (function(module, exports) {

$(function () {
	// Available panels
	// #mine
	// #ongoing
	// #terminated
	// var tab_active = localStorage.getItem('activities-tab-active');
	// if (tab_active) {
	// 	$('#activities-tab-selection a[href="' + tab_active + '"]').tab('show');
	// }
	// else {
	// 	$('#activities-tab-selection a[href="#mine"]').tab('show'); //default panel
	// }
	//
	// $('#activities-tab-selection a[data-toggle="tab"]').click( function (e) {
	// 	var _target = $(e.target).attr('href');
	// 	if (!_target) _target = $(this).attr('href');
	//
	// 	localStorage.setItem('activities-tab-active', _target);
	// 	$('#activities-tab-selection a[href="' + _target + '"]').tab('show');
	// });

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
					var val_task_days_r = value.task_days_r;
				} else {
					var val_task_days_p = 0;
					var val_task_days_r = 0;
				}
				var display_task_days_p = $.number(value.task_days_p, 3, ',', ' ');
				var display_task_days_r = $.number(value.task_days_r, 3, ',', ' ');

				var status_flag = '<tr>';

				if (cra_validate == false) {
					if (value.task_status == 1) var status_flag = '<tr class="tr-task-terminated">';
					if (value.task_status == 2) var status_flag = '<tr class="tr-task-not-validated">';
					if (value.task_status == 3) var status_flag = '<tr class="tr-task-validated">';
				}

				if (cra_validate == true) {
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

	$('#activity_create').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#activity_create').find('select, textarea, input').each(function () {
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

	$('#activity_update').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#activity_update').find('select, textarea, input').each(function () {
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

	$('#activity_export').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#activity_export').find('select, textarea, input').each(function () {
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

	$('#charges_export').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#charges_export').find('select, textarea, input').each(function () {
			if (!$(this).prop('hidden')) {
				if ($(this).prop('required')) {
					//c'est ici qu'on testera les valeurs et expressions régulières

					if ($('#exportTables #charge_date_end').val() && $('#exportTables #charge_date_start').val()) {

						if ($('#exportTables #charge_date_end').val() < $('#exportTables #charge_date_start').val()) {
							fail = true;
							name = $('#exportTables #charge_date_end').attr('name');
							name_start = $('#exportTables #charge_date_start').attr('name');
							fail_log += name + " > " + name_start + ".\n";
							$('#exportTables #date_error_gap').attr('hidden', false);
						} else {
							$('#exportTables #date_error_gap').attr('hidden', true);
						}
					}

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
			$('#exportTables').modal('hide');
		}
	});

	$('#personal_export').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#personal_export').find('select, textarea, input').each(function () {
			if (!$(this).prop('hidden')) {
				if ($(this).prop('required')) {
					//c'est ici qu'on testera les valeurs et expressions régulières

					if ($('#exportTables #personal_date_end').val() && $('#exportTables #personal_date_start').val()) {

						if ($('#exportTables #personal_date_end').val() < $('#exportTables #personal_date_start').val()) {
							fail = true;
							name = $('#exportTables #personal_date_end').attr('name');
							name_start = $('#exportTables #personal_date_start').attr('name');
							fail_log += name + " > " + name_start + ".\n";
							$('#exportTables #date_error_gap_personal').attr('hidden', false);
						} else {
							$('#exportTables #date_error_gap_personal').attr('hidden', true);
						}
					}

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

/***/ }),
/* 4 */
/***/ (function(module, exports) {

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

			if (value.task_status == 1) var status_flag = '<tr class="tr-task-terminated">';else var status_flag = '<tr>';
			if (value.task_status == 2) var status_flag = '<tr class="tr-task-not-validated">';else var status_flag = '<tr>';
			if (value.task_status == 3) var status_flag = '<tr class="tr-task-validated">';else var status_flag = '<tr>';

			//create table + add values
			var line_activity_name = $('<td class="text-left truncate-details">').attr("data-value", value.activity_name).text(value.activity_name);
			var line_phase_name = $('<td class="text-left truncate-details">').attr("data-value", value.phase_name).text(value.phase_name);
			var line_task_name = $('<td class="text-left truncate-details">').attr("data-value", value.task_name).text(value.task_name.concat(" - ").concat(value.task_status));
			var line_task_type_name = $('<td class="text-left truncate-details">').attr("data-value", value.task_type_name).text(value.task_type_name);
			var line_task_start_p = $('<td class="text-center truncate-details">').attr("data-value", value.task_start_p).text(display_task_start_p);
			var line_task_end_p = $('<td class="text-center truncate-details">').attr("data-value", value.task_end_p).text(display_task_end_p);
			var line_task_days_p = $('<td class="style-prevu text-right">').attr("data-value", val_task_days_p).text(display_task_days_p);
			var line_task_days_r = $('<td class="style-realise text-right">').attr("data-value", val_task_days_r).text(display_task_days_r);
			var $details_info = $(status_flag).append(line_activity_name, line_phase_name, line_task_name, line_task_type_name, line_task_start_p, line_task_end_p, line_task_days_p, line_task_days_r).appendTo('#charges_details_table');
		});
	});
});

// $(function () {
// 	// Available panels
// 	// #absence_user
// 	// #absence_direction
// 	var tab_active = localStorage.getItem('charges-tab-active');
// 	if (tab_active) {
// 		$('#charges-tab-selection a[href="' + tab_active + '"]').tab('show');
// 	}
// 	else {
// 		$('#charges-tab-selection a[href="#charges_all"]').tab('show'); //default panel
// 	}
//
// 	$('#charges-tab-selection a[data-toggle="tab"]').click( function (e) {
// 		var _target = $(e.target).attr('href');
// 		if (!_target) _target = $(this).attr('href');
//
// 		localStorage.setItem('charges-tab-active', _target);
// 		$('#charges-tab-selection a[href="' + _target + '"]').tab('show');
// 	});
// });

/***/ }),
/* 5 */
/***/ (function(module, exports) {

$(function () {

	$('#password_change').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#password_change').find('select, textarea, input').each(function () {
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
			$(this).find('#btn-submit-form-password').addClass('apply-spin');
		}
	});

	$('#display_change').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#display_change').find('select, textarea, input').each(function () {
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
			$(this).find('#btn-submit-form-display').addClass('apply-spin');
		}
	});
});

/***/ }),
/* 6 */
/***/ (function(module, exports) {

$(function () {

	// Available panels
	// #dashboard_user
	// #dashboard_entity
	var tab_active = localStorage.getItem('dashboard-tab-active');
	if (tab_active) {
		$('#dashboard-tab-selection a[href="' + tab_active + '"]').tab('show');
	} else {
		$('#dashboard-tab-selection a[href="#dashboard_user"]').tab('show'); //default panel
	}

	$('#dashboard-tab-selection a[data-toggle="tab"]').click(function (e) {
		var _target = $(e.target).attr('href');
		if (!_target) _target = $(this).attr('href');

		localStorage.setItem('dashboard-tab-active', _target);
		$('#dashboard-tab-selection a[href="' + _target + '"]').tab('show');
	});
});

/***/ }),
/* 7 */
/***/ (function(module, exports) {

(function () {
	//exclude older browsers by the features we need them to support
	//and legacy opera explicitly so we don't waste time on a dead browser
	if (!document.querySelectorAll || !('draggable' in document.createElement('span')) || window.opera) {
		return;
	}

	//get the collection of draggable targets and add their draggable attribute
	for (var targets = document.querySelectorAll('[data-draggable="target"]'), len = targets.length, i = 0; i < len; i++) {
		targets[i].setAttribute('aria-dropeffect', 'none');
	}

	//get the collection of draggable items and add their draggable attributes
	for (var items = document.querySelectorAll('[data-draggable="item"]'), len = items.length, i = 0; i < len; i++) {
		items[i].setAttribute('draggable', 'true');
		items[i].setAttribute('aria-grabbed', 'false');
		items[i].setAttribute('tabindex', '0');
	}

	//dictionary for storing the selections data
	//comprising an array of the currently selected items
	//a reference to the selected items' owning container
	//and a refernce to the current drop target container
	var selections = {
		items: [],
		owner: null,
		droptarget: null
	};

	//function for selecting an item
	function ChangeSelectionDown(item) {
		//reset this item's grabbed state
		item.setAttribute('aria-grabbed', 'false');

		//then find and remove this item from the existing items array
		for (var len = selections.items.length, i = 0; i < len; i++) {
			if (selections.items[i] == item) {
				selections.items.splice(i, 1);
				break;
			}
		}

		var nextitem = item.nextElementSibling;

		if (nextitem) {
			//set this item's grabbed state
			nextitem.setAttribute('aria-grabbed', 'true');
			nextitem.focus();

			//add it to the items array
			selections.items.push(nextitem);
		}
	}

	function ChangeSelectionUp(item) {
		//reset this item's grabbed state
		item.setAttribute('aria-grabbed', 'false');

		//then find and remove this item from the existing items array
		for (var len = selections.items.length, i = 0; i < len; i++) {
			if (selections.items[i] == item) {
				selections.items.splice(i, 1);
				break;
			}
		}

		var previousitem = item.previousElementSibling;

		if (previousitem) {
			//set this item's grabbed state
			previousitem.setAttribute('aria-grabbed', 'true');
			previousitem.focus();

			//add it to the items array
			selections.items.push(previousitem);
		}
	}

	//function for selecting an item
	function addSelection(item) {
		//if the owner reference is still null, set it to this item's parent
		//so that further selection is only allowed within the same container
		if (!selections.owner) {
			selections.owner = item.parentNode;
		}

		//or if that's already happened then compare it with this item's parent
		//and if they're not the same container, return to prevent selection
		else if (selections.owner != item.parentNode) {
				return;
			}

		//set this item's grabbed state
		item.setAttribute('aria-grabbed', 'true');

		//add it to the items array
		selections.items.push(item);
	}

	//function for unselecting an item
	function removeSelection(item) {
		//reset this item's grabbed state
		item.setAttribute('aria-grabbed', 'false');

		//then find and remove this item from the existing items array
		for (var len = selections.items.length, i = 0; i < len; i++) {
			if (selections.items[i] == item) {
				selections.items.splice(i, 1);
				break;
			}
		}
	}

	//function for resetting all selections
	function clearSelections() {
		//if we have any selected items
		if (selections.items.length) {
			//reset the owner reference
			selections.owner = null;

			//reset the grabbed state on every selected item
			for (var len = selections.items.length, i = 0; i < len; i++) {
				selections.items[i].setAttribute('aria-grabbed', 'false');
			}

			//then reset the items array
			selections.items = [];
		}
	}

	//shorctut function for testing whether CTRL is pressed
	function hasModifier(e) {
		// return (e.ctrlKey || e.metaKey);
		return e.ctrlKey;
	}

	//shorctut function for testing whether SHIFT is pressed
	function hasModifierShift(e) {
		return e.shiftKey;
	}

	//function for applying dropeffect to the target containers
	function addDropeffects() {
		//apply aria-dropeffect and tabindex to all targets apart from the owner
		for (var len = targets.length, i = 0; i < len; i++) {
			if (targets[i] != selections.owner && targets[i].getAttribute('aria-dropeffect') == 'none') {
				targets[i].setAttribute('aria-dropeffect', 'move');
				targets[i].setAttribute('tabindex', '0');
			}
		}

		//remove aria-grabbed and tabindex from all items inside those containers
		for (var len = items.length, i = 0; i < len; i++) {
			if (items[i].parentNode != selections.owner && items[i].getAttribute('aria-grabbed')) {
				items[i].removeAttribute('aria-grabbed');
				items[i].removeAttribute('tabindex');
			}
		}
	}

	//function for removing dropeffect from the target containers
	function clearDropeffects() {
		//if we have any selected items
		if (selections.items.length) {
			//reset aria-dropeffect and remove tabindex from all targets
			for (var len = targets.length, i = 0; i < len; i++) {
				if (targets[i].getAttribute('aria-dropeffect') != 'none') {
					targets[i].setAttribute('aria-dropeffect', 'none');
					targets[i].removeAttribute('tabindex');
				}
			}

			//restore aria-grabbed and tabindex to all selectable items
			//without changing the grabbed value of any existing selected items
			for (var len = items.length, i = 0; i < len; i++) {
				if (!items[i].getAttribute('aria-grabbed')) {
					items[i].setAttribute('aria-grabbed', 'false');
					items[i].setAttribute('tabindex', '0');
				} else if (items[i].getAttribute('aria-grabbed') == 'true') {
					items[i].setAttribute('tabindex', '0');
				}
			}
		}
	}

	//shortcut function for identifying an event element's target container
	function getContainer(element) {
		do {
			if (element.nodeType == 1 && element.getAttribute('aria-dropeffect')) {
				return element;
			}
		} while (element = element.parentNode);

		return null;
	}

	//mousedown event to implement single selection
	document.addEventListener('mousedown', function (e) {
		//if the element is a draggable item
		if (e.target.getAttribute('draggable')) {
			//clear dropeffect from the target containers
			clearDropeffects();

			//if the multiple selection modifier is not pressed
			//and the item's grabbed state is currently false
			if (!hasModifier(e) && e.target.getAttribute('aria-grabbed') == 'false') {
				//clear all existing selections
				clearSelections();

				//then add this new selection
				addSelection(e.target);
			}
		}

		//else [if the element is anything else]
		//and the selection modifier is not pressed
		else if (!hasModifier(e)) {
				//clear dropeffect from the target containers
				clearDropeffects();

				//clear all existing selections
				clearSelections();
			}

			//else [if the element is anything else and the modifier is pressed]
			else {
					//clear dropeffect from the target containers
					clearDropeffects();
				}
	}, false);

	//mouseup event to implement multiple selection
	document.addEventListener('mouseup', function (e) {
		//if the element is a draggable item
		//and the multipler selection modifier is pressed
		if (e.target.getAttribute('draggable') && hasModifier(e)) {
			//if the item's grabbed state is currently true
			if (e.target.getAttribute('aria-grabbed') == 'true') {
				//unselect this item
				removeSelection(e.target);

				//if that was the only selected item
				//then reset the owner container reference
				if (!selections.items.length) {
					selections.owner = null;
				}
			}

			//else [if the item's grabbed state is false]
			else {
					//add this additional selection
					addSelection(e.target);
				}
		}
	}, false);

	//dragstart event to initiate mouse dragging
	document.addEventListener('dragstart', function (e) {
		//if the element's parent is not the owner, then block this event
		if (selections.owner != e.target.parentNode) {
			e.preventDefault();
			return;
		}

		//[else] if the multiple selection modifier is pressed
		//and the item's grabbed state is currently false
		if (hasModifier(e) && e.target.getAttribute('aria-grabbed') == 'false') {
			//add this additional selection
			addSelection(e.target);
		}

		//we don't need the transfer data, but we have to define something
		//otherwise the drop action won't work at all in firefox
		//most browsers support the proper mime-type syntax, eg. "text/plain"
		//but we have to use this incorrect syntax for the benefit of IE10+
		e.dataTransfer.setData('text', '');

		//apply dropeffect to the target containers
		addDropeffects();
	}, false);

	//keydown event to implement selection and abort
	document.addEventListener('keydown', function (e) {
		//if the element is a grabbable item
		if (e.target.getAttribute('aria-grabbed')) {
			//Space is the selection or unselection keystroke
			if (e.keyCode == 32) {
				//if the multiple selection modifier is pressed
				if (hasModifier(e)) {
					//if the item's grabbed state is currently true
					if (e.target.getAttribute('aria-grabbed') == 'true') {
						//if this is the only selected item, clear dropeffect
						//from the target containers, which we must do first
						//in case subsequent unselection sets owner to null
						if (selections.items.length == 1) {
							clearDropeffects();
						}

						//unselect this item
						removeSelection(e.target);

						//if we have any selections
						//apply dropeffect to the target containers,
						//in case earlier selections were made by mouse
						if (selections.items.length) {
							addDropeffects();
						}

						//if that was the only selected item
						//then reset the owner container reference
						if (!selections.items.length) {
							selections.owner = null;
						}
					}

					//else [if its grabbed state is currently false]
					else {
							//add this additional selection
							addSelection(e.target);

							//apply dropeffect to the target containers
							addDropeffects();
						}
				}

				//else [if the multiple selection modifier is not pressed]
				//and the item's grabbed state is currently false
				else if (e.target.getAttribute('aria-grabbed') == 'false') {
						//clear dropeffect from the target containers
						clearDropeffects();

						//clear all existing selections
						clearSelections();

						//add this new selection
						addSelection(e.target);

						//apply dropeffect to the target containers
						addDropeffects();
					}

					//else [if modifier is not pressed and grabbed is already true]
					else {
							//apply dropeffect to the target containers
							addDropeffects();
						}

				//then prevent default to avoid any conflict with native actions
				e.preventDefault();
			}

			//Modifier + M is the end-of-selection keystroke
			if (e.keyCode == 77 && hasModifier(e)) {
				//if we have any selected items
				if (selections.items.length) {
					//apply dropeffect to the target containers
					//in case earlier selections were made by mouse
					addDropeffects();

					//if the owner container is the last one, focus the first one
					if (selections.owner == targets[targets.length - 1]) {
						targets[0].focus();
					}

					//else [if it's not the last one], find and focus the next one
					else {
							for (var len = targets.length, i = 0; i < len; i++) {
								if (selections.owner == targets[i]) {
									targets[i + 1].focus();
									break;
								}
							}
						}
				}

				//then prevent default to avoid any conflict with native actions
				e.preventDefault();
			}

			// //up arrow
			// if(e.keyCode == 38)
			// {
			// 	ChangeSelectionUp(e.target);

			// 	//then prevent default to avoid any conflict with native actions
			// 	e.preventDefault();
			// }

			// //down arrow
			// if(e.keyCode == 40)
			// {
			// 	ChangeSelectionDown(e.target);

			// 	//then prevent default to avoid any conflict with native actions
			// 	e.preventDefault();
			// }

		}

		//Escape is the abort keystroke (for any target element)
		if (e.keyCode == 27) {
			//if we have any selected items
			if (selections.items.length) {
				//clear dropeffect from the target containers
				clearDropeffects();

				//then set focus back on the last item that was selected, which is
				//necessary because we've removed tabindex from the current focus
				selections.items[selections.items.length - 1].focus();

				//clear all existing selections
				clearSelections();

				//but don't prevent default so that native actions can still occur
			}
		}
	}, false);

	//related variable is needed to maintain a reference to the
	//dragleave's relatedTarget, since it doesn't have e.relatedTarget
	var related = null;

	//dragenter event to set that variable
	document.addEventListener('dragenter', function (e) {
		related = e.target;
	}, false);

	//dragleave event to maintain target highlighting using that variable
	document.addEventListener('dragleave', function (e) {
		//get a drop target reference from the relatedTarget
		var droptarget = getContainer(related);

		//if the target is the owner then it's not a valid drop target
		if (droptarget == selections.owner) {
			droptarget = null;
		}

		//if the drop target is different from the last stored reference
		//(or we have one of those references but not the other one)
		if (droptarget != selections.droptarget) {
			//if we have a saved reference, clear its existing dragover class
			if (selections.droptarget) {
				selections.droptarget.className = selections.droptarget.className.replace(/ dragover/g, '');
			}

			//apply the dragover class to the new drop target reference
			if (droptarget) {
				droptarget.className += ' dragover';
			}

			//then save that reference for next time
			selections.droptarget = droptarget;
		}
	}, false);

	//dragover event to allow the drag by preventing its default
	document.addEventListener('dragover', function (e) {
		//if we have any selected items, allow them to be dragged
		if (selections.items.length) {
			e.preventDefault();
		}
	}, false);

	//dragend event to implement items being validly dropped into targets,
	//or invalidly dropped elsewhere, and to clean-up the interface either way
	document.addEventListener('dragend', function (e) {
		//if we have a valid drop target reference
		//(which implies that we have some selected items)
		if (selections.droptarget) {
			//append the selected items to the end of the target container
			for (var len = selections.items.length, i = 0; i < len; i++) {
				selections.droptarget.appendChild(selections.items[i]);
			}

			//prevent default to allow the action
			e.preventDefault();
		}

		//if we have any selected items
		if (selections.items.length) {
			//clear dropeffect from the target containers
			clearDropeffects();

			//if we have a valid drop target reference
			if (selections.droptarget) {
				var user_id_values = [];

				$('#user_id li').each(function () {
					// id of ul
					user_id_values.push($(this).data('value'));
				});
				$('#user_id_values').attr('value', JSON.stringify(user_id_values));

				//reset the selections array
				clearSelections();

				//reset the target's dragover class
				selections.droptarget.className = selections.droptarget.className.replace(/ dragover/g, '');

				//reset the target reference
				selections.droptarget = null;
			}
		}
	}, false);
})();

/***/ }),
/* 8 */
/***/ (function(module, exports) {

$(function () {

	// Available panels
	// #task_types
	// #open_days
	// #change_log
	// var tab_active = localStorage.getItem('info-tab-active');
	// if (tab_active) {
	// 	$('#info-tab-selection a[href="' + tab_active + '"]').tab('show');
	// }
	// else {
	// 	$('#info-tab-selection a[href="#task_types"]').tab('show'); //default panel
	// }
	//
	// $('#info-tab-selection a[data-toggle="tab"]').click( function (e) {
	// 	var _target = $(e.target).attr('href');
	// 	if (!_target) _target = $(this).attr('href');
	//
	// 	localStorage.setItem('info-tab-active', _target);
	// 	$('#info-tab-selection a[href="' + _target + '"]').tab('show');
	// });

});

/***/ }),
/* 9 */
/***/ (function(module, exports) {

$(function () {

	// Available panels
	// #log_activity
	var tab_active = localStorage.getItem('log_activity-tab-active');
	if (tab_active) {
		$('#log_activity-tab-selection a[href="' + tab_active + '"]').tab('show');
	} else {
		$('#log_activity-tab-selection a[href="#log_activity"]').tab('show'); //default panel
	}

	$('#plannilog_activityng-tab-selection a[data-toggle="tab"]').click(function (e) {
		var _target = $(e.target).attr('href');
		if (!_target) _target = $(this).attr('href');

		localStorage.setItem('log_activity-tab-active', _target);
		$('#log_activity-tab-selection a[href="' + _target + '"]').tab('show');
	});
});

/***/ }),
/* 10 */
/***/ (function(module, exports) {

$(function () {

	$('#login_page').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#login_page').find('select, textarea, input').each(function () {
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

/***/ }),
/* 11 */
/***/ (function(module, exports) {

$(function () {

	//Update phase
	$(document).on('click', '#editButton', function () {
		var data = JSON.parse($(this).data('phase'));
		$.each(data, function (key, value) {
			$('#editPhase #' + key).val(value);
		});

		$('#editPhase #phase_id').text('id='.concat(data.phase_id));
		$('#editPhase #phase_name').text(data.phase_name);
	});

	//Terminate phase
	$(document).on('click', '#terminateButton', function () {
		var phase_id = JSON.parse($(this).data('phase_id'));
		var phase_name = JSON.parse($(this).data('phase_name'));
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#terminatePhase #phase_id').val(phase_id);
		$('#terminatePhase #phase_name').text(phase_name);
		$('#terminatePhase #activity_id').val(activity_id);
		$('#terminatePhase #phase_id').text('id='.concat(phase_id));
	});

	//Activate phase
	$(document).on('click', '#activateButton', function () {
		var phase_id = JSON.parse($(this).data('phase_id'));
		var activity_id = JSON.parse($(this).data('activity_id'));
		var phase_name = JSON.parse($(this).data('phase_name'));

		$('#activatePhase #phase_name').text(phase_name);
		$('#activatePhase #phase_id').val(phase_id);
		$('#activatePhase #activity_id').val(activity_id);
		$('#activatePhase #phase_id').text('id='.concat(phase_id));
	});

	//Privacy phase
	$(document).on('click', '#privacyPhaseButton', function () {
		var phase_id = JSON.parse($(this).data('phase_id'));
		var phase_name = JSON.parse($(this).data('phase_name'));

		$('#privacyPhase #phase_id').val(phase_id);
		$('#privacyPhase #phase_name').text(phase_name);
		$('#privacyPhase #phase_id').text('id='.concat(phase_id));
	});

	//Delete phase
	$(document).on('click', '#deletePhaseButton', function () {
		var phase_id = JSON.parse($(this).data('phase_id'));
		var phase_name = JSON.parse($(this).data('phase_name'));
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#deletePhase #phase_id').val(phase_id);
		$('#deletePhase #phase_name').text(phase_name);
		$('#deletePhase #activity_id').val(activity_id);
		$('#deletePhase #phase_id').text('id='.concat(phase_id));
	});

	//Move phase
	$(document).on('click', '#movePhaseButton', function () {
		var phase_id = JSON.parse($(this).data('phase_id'));
		var phase_name = JSON.parse($(this).data('phase_name'));
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#movePhase #phase_id').val(phase_id);
		$('#movePhase #phase_name').text(phase_name);
		$('#movePhase #activity_id').val(activity_id);
		$('#movePhase #phase_id').text('id='.concat(phase_id));
	});

	$('#phase_create').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#phase_create').find('select, textarea, input').each(function () {
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

	$('#phase_update').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#phase_update').find('select, textarea, input').each(function () {
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

	$('#phase_move').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#phase_move').find('select, textarea, input').each(function () {
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

	//Mise à jour des icônes et du cookie local quand clic sur expand phase
	$('.btn-accordion').click(function () {

		var is_expanded = localStorage.getItem($(this).attr('data-target'));

		var going_shown = !$($(this).attr('data-target')).hasClass('show');

		var nb_expanded = 0;

		$('.btn-accordion').each(function (i, obj) {
			if ($($(this).attr('data-target')).hasClass('show')) {
				nb_expanded += 1;
			}
		});

		if (going_shown) nb_expanded += 1;else nb_expanded -= 1;

		if (going_shown) {
			localStorage.setItem($(this).attr('data-target'), "_expanded");
			$(this).addClass('collapsed');
		} else {
			//if (is_expanded) {
			localStorage.removeItem($(this).attr('data-target'));
			$(this).removeClass('collapsed');
			//}
		}

		if (nb_expanded == $('.btn-accordion').length) {
			$('#collapseAllButton').removeClass('collapsed-all');
		}
		if (nb_expanded == 0) {
			$('#collapseAllButton').addClass('collapsed-all');
		}
	});

	//Remise en état des accordions via cookie local
	$('.btn-accordion').each(function () {
		var _panel_target = $(this).attr('data-target');

		var is_extended = localStorage.getItem(_panel_target);

		if (is_extended != null) {
			$(this).removeClass('collapsed');
			$(_panel_target).addClass('show');
			$('#collapseAllButton').removeClass('collapsed-all');
		}
	});

	//CollapseAll button (SJL)
	$(document).on('click', '#collapseAllButton', function () {

		var phases = JSON.parse($(this).data('phases'));

		var is_collapsed_all = $(this).hasClass('collapsed-all');

		if (is_collapsed_all) {
			$(this).removeClass('collapsed-all');
			$.each(phases, function (key, value) {
				var _panel_target = '#task_panel' + value.phase_id;
				$(_panel_target).addClass('show');
				localStorage.setItem(_panel_target, "_expanded");

				var _button_target = '#collapseButton' + value.phase_id;
				$(_button_target).removeClass('collapsed');
			});
		} else {
			$(this).addClass('collapsed-all');
			$.each(phases, function (key, value) {
				var _panel_target = '#task_panel' + value.phase_id;
				$(_panel_target).removeClass('show');
				localStorage.removeItem(_panel_target);

				var _button_target = '#collapseButton' + value.phase_id;
				$(_button_target).addClass('collapsed');
			});
		}
	});
});

/***/ }),
/* 12 */
/***/ (function(module, exports) {

$(function () {

	// Available panels
	// #planning
	// #resume
	// var tab_active = localStorage.getItem('planning-tab-active');
	// if (tab_active) {
	// 	$('#planning-tab-selection a[href="' + tab_active + '"]').tab('show');
	// }
	// else {
	// 	$('#planning-tab-selection a[href="#planning"]').tab('show'); //default panel
	// }
	//
	// $('#planning-tab-selection a[data-toggle="tab"]').click( function (e) {
	// 	var _target = $(e.target).attr('href');
	// 	if (!_target) _target = $(this).attr('href');
	//
	// 	localStorage.setItem('planning-tab-active', _target);
	// 	$('#planning-tab-selection a[href="' + _target + '"]').tab('show');
	// });
});

/***/ }),
/* 13 */
/***/ (function(module, exports) {

$(function () {

	// Available panels
	// #mois_selectionne
	// #mois_anterieurs
	// var tab_active = localStorage.getItem('tasks-tab-active');
	// if (tab_active) {
	// 	$('#tasks-tab-selection a[href="' + tab_active + '"]').tab('show');
	// }
	// else {
	// 	$('#tasks-tab-selection a[href="#tasks_user"]').tab('show'); //default panel
	// }
	//
	// $('#tasks-tab-selection a[data-toggle="tab"]').click( function (e) {
	// 	var _target = $(e.target).attr('href');
	// 	if (!_target) _target = $(this).attr('href');
	//
	// 	localStorage.setItem('tasks-tab-active', _target);
	// 	$('#tasks-tab-selection a[href="' + _target + '"]').tab('show');
	// });

	//Create task
	$(document).on('click', '#createTaskBtn', function () {

		var _phase = JSON.parse($(this).data('phase'));

		$('.createTask #phase_id').val(_phase.phase_id);
		$('.createTask #phase_name').text(_phase.phase_name);
		$('.createTask #activity_id').val(_phase.activity_id);
	});

	//Edit task
	$(document).on('click', '#editTaskButton', function () {
		var data = JSON.parse($(this).data('task'));

		$.each(data, function (key, value) {
			$('#editTask #' + key).val(value);
		});

		var phase_id = JSON.parse($(this).data('phase_id'));
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#editTask #phase_id').val(phase_id);
		$('#editTask #activity_id').val(activity_id);
		$("#editTask #task_name").text(data.task_name);
		$("#editTask #task_id").text('id='.concat(data.task_id));
	});

	//Terminate task
	$(document).on('click', '#terminateTaskButton', function () {
		var task_id = JSON.parse($(this).data('task_id'));
		var task_name = JSON.parse($(this).data('task_name'));
		var phase_id = JSON.parse($(this).data('phase_id'));
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#terminateTask #task_id').val(task_id);
		$('#terminateTask #task_name').text(task_name);
		$('#terminateTask #phase_id').val(phase_id);
		$('#terminateTask #activity_id').val(activity_id);
		$('#terminateTask #task_id').text('id='.concat(task_id));
	});

	//Activate task
	$(document).on('click', '#activateTaskButton', function () {
		var task_id = JSON.parse($(this).data('task_id'));
		var task_name = JSON.parse($(this).data('task_name'));
		var phase_id = JSON.parse($(this).data('phase_id'));
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#activateTask #task_id').val(task_id);
		$('#activateTask #task_name').text(task_name);
		$('#activateTask #phase_id').val(phase_id);
		$('#activateTask #activity_id').val(activity_id);
		$('#activateTask #task_id').text('id='.concat(task_id));
	});

	//Delete task
	$(document).on('click', '#deleteTaskButton', function () {
		var task_id = JSON.parse($(this).data('task_id'));
		var task_name = JSON.parse($(this).data('task_name'));
		var phase_id = JSON.parse($(this).data('phase_id'));
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#deleteTask #task_id').val(task_id);
		$('#deleteTask #task_name').text(task_name);
		$('#deleteTask #phase_id').val(phase_id);
		$('#deleteTask #activity_id').val(activity_id);
		$('#deleteTask #task_id').text('id='.concat(task_id));
	});

	//Delete multi task
	$(document).on('click', '#deleteMultiTaskButton', function () {
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#deleteMultiTask #activity_id').val(activity_id);
	});

	//Terminate multi task
	$(document).on('click', '#terminateMultiTaskButton', function () {
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#terminateMultiTask #activity_id').val(activity_id);
	});

	//Activate multi task
	$(document).on('click', '#activateMultiTaskButton', function () {
		var activity_id = JSON.parse($(this).data('activity_id'));

		$('#activateMultiTask #activity_id').val(activity_id);
	});

	//Copy task
	$(document).on('click', '#copyTaskButton', function () {
		var task_id = JSON.parse($(this).data('task_id'));
		var task_name = JSON.parse($(this).data('task_name'));

		$('#copySingleTask #task_id').val(task_id);
		$('#copySingleTask #task_name').val(task_name);
		$('#copySingleTask #task_id').text('id='.concat(task_id));
	});

	//Multitask select
	$(".multi-tasks-select").prop('disabled', 'disabled');

	//Change button status
	$('.multi-tasks-select-checkboxes').change(function () {
		if ($('.multi-tasks-select-checkboxes').is(':checked') == true) {
			$('.multi-tasks-select').prop('disabled', false);
		} else {
			$('.multi-tasks-select').prop('disabled', true);
		}
	});

	//Get IDs for multi task
	$('.multi-tasks-select').click(function () {
		var multi_tasks_ids = [];

		$('.multi-tasks-select-checkboxes').each(function () {
			if ($(this).is(':checked')) {
				multi_tasks_ids.push(this.value);
			}
		});

		$('#deleteMultiTask #task_id').val(multi_tasks_ids);
		$('#moveMultiTask #task_id').val(multi_tasks_ids);
		$('#copyMultiTask #task_id').val(multi_tasks_ids);
		$('#terminateMultiTask #task_id').val(multi_tasks_ids);
		$('#activateMultiTask #task_id').val(multi_tasks_ids);
	});

	//Function dropdown for copy multitask
	//Enable phase select if activity selected
	$('#task_activities_list').change(function () {
		$('#task_phases_list').prop('disabled', false);
		if ($("#task_activities_list option:selected").text() === '') {
			$('#task_phases_list').empty();
			$('#task_phases_list').prop('disabled', 'disabled');
		} else {
			makePhasesList($("#task_activities_list option:selected")[0].value);
		}
	});

	//Populate phase list by activity selected
	function makePhasesList(activity_id) {
		$.getJSON('/tasks/getPhases/' + activity_id, function (data) {
			var $phases_list = $("#task_phases_list");
			$phases_list.empty(); // remove old options
			$.each(data.phases_list, function (key, value) {
				$phases_list.append($("<option></option>").attr("value", key).text(value));
			});
			//Tri de la liste
			sortSelect('task_phases_list');
		});
	}

	//Function dropdown for move multitask
	//Enable phase select if activity selected
	$('#task_activities_list2').change(function () {
		$('#task_phases_list2').prop('disabled', false);
		if ($("#task_activities_list2 option:selected").text() === '') {
			$('#task_phases_list2').empty();
			$('#task_phases_list2').prop('disabled', 'disabled');
		} else {
			makePhasesList2($("#task_activities_list2 option:selected")[0].value);
		}
	});

	//Populate phase list by activity selected
	function makePhasesList2(activity_id) {
		$.getJSON('/tasks/getPhases/' + activity_id, function (data) {
			var $phases_list = $("#task_phases_list2");
			$phases_list.empty(); // remove old options
			$.each(data.phases_list, function (key, value) {
				$phases_list.append($("<option></option>").attr("value", key).text(value));
			});
			//Tri de la liste
			sortSelect('task_phases_list2');
		});
	}

	$('#task_create').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#task_create').find('select, textarea, input').each(function () {
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

	$('#task_update').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#task_update').find('select, textarea, input').each(function () {
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

	$('#task_terminate_multi').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#task_terminate_multi').find('select, textarea, input').each(function () {
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

	$('#task_move_multi').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#task_move_multi').find('select, textarea, input').each(function () {
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

	$('#task_copy_multi').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#task_copy_multi').find('select, textarea, input').each(function () {
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

	$('#task_create_public').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#task_create_public').find('select, textarea, input').each(function () {
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

/***/ }),
/* 14 */
/***/ (function(module, exports) {

$(function () {
	// Available panels
	// #users_list
	var tab_active = localStorage.getItem('users-tab-active');
	if (tab_active) {
		$('#users-tab-selection a[href="' + tab_active + '"]').tab('show');
	} else {
		$('#users-tab-selection a[href="#users_list"]').tab('show'); //default panel
	}

	$('#users-tab-selection a[data-toggle="tab"]').click(function (e) {
		var _target = $(e.target).attr('href');
		if (!_target) _target = $(this).attr('href');

		localStorage.setItem('users-tab-active', _target);
		$('#users-tab-selection a[href="' + _target + '"]').tab('show');
	});
});

$(function () {
	var url = window.location.href;

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		$.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
	});

	$('#example thead th').each(function () {
		var title = $(this).text();
		$(this).html('<input style="z-index: 99999" type="text" placeholder="Search ' + title + '" />');
	});

	var table = $('#example').DataTable({
		"processing": true,
		"paginate": false,
		// "responsive": true,
		"searching": true,
		"ordering": true,
		"select": true,
		"ajax": {
			"url": "http://localhost:8000/users/getData",
			"type": "GET"
		},
		"columns": [{ "data": "user_last_name" }, { "data": "user_first_name" }, { "data": "user_login" }, { "data": "user_role_name" }, { "data": "user_email" }, { "data": "user_status" }, { "data": "user_trigramme" }, { "data": "user_department_name" }, { "data": "user_service_name" }, { "data": "user_daily_cost" }],
		dom: 'Bfrtip',
		buttons: [{
			className: 'fa fa-plus svg-large btn-theme-fonce-leger',
			action: function action(e, dt, node, config) {
				jQuery("#createUser").modal("show");
			}
		}, {
			className: 'fa fa-edit svg-small btn-theme-clair-fort',
			action: function action(e, dt, node, config) {
				var rowData = table.rows({ selected: true }).data()[0];
				// now do what you need to do wht the row data
				$('#editUser').modal('show');

				// var data = JSON.parse($(this).data('user'));
				$("#editUser #user_name").text(rowData.user_first_name + " " + rowData.user_last_name);
				$("#editUser #user_id").text('id='.concat(rowData.user_id));

				var current_direction = rowData.user_department_id2;
				// var current_service = data.user_service_id2;

				$.each(rowData, function (key, value) {
					$('#editUser #' + key).val(value);
				});

				makeServicesList_Edit(current_direction);
			},
			enabled: false

		}],
		initComplete: function initComplete() {
			this.api().columns().every(function () {
				var that = this;

				$('input', this.header()).on('keyup change', function () {

					if (that.search() !== this.value) {
						that.search(this.value).draw();
					}
				});
			});
		}

	});
	table.on('select deselect', function () {
		var selectedRows = table.rows({ selected: true }).count();
		table.button(1).enable(selectedRows === 1);
	});

	//init modal fields of createUser
	$(document).on('click', '#createUserButton', function () {
		$('#createUser').find('#user_first_name').val('');
		$('#createUser').find('#user_last_name').val('');
		$('#createUser').find('#user_email').val('');
		$('#createUser').find('#current-password').val('');
		$('#createUser').find('#user_trigramme').val('');
		$('#createUser').find('#user_login').val('');
		$('#createUser').find('#user_daily_cost').val(0);
		$('#createUser').find('#user_role_id').val(5);
		// $('#createUser').find('#user_department_id').val(1);

		var connected_user_direction = parseInt($("#createUser #user_department_id").val());
		var connected_user_service = parseInt($("#createUser #user_service_id").val());

		makeServicesList_Create(connected_user_direction);
	});

	//Edit user
	// $(document).on('click', '#editUserButton', function () {
	// 	var data = JSON.parse($(this).data('user'));
	// 	$("#editUser #user_name").text(data.user_first_name + " " + data.user_last_name);
	// 	$("#editUser #user_id").text('id='.concat(data.user_id));
	//
	// 	var current_direction = data.user_department_id2;
	// 	// var current_service = data.user_service_id2;
	//
	// 	$.each(data, function (key, value) {
	// 		$('#editUser #' + key).val(value);
	// 	});
	//
	// 	makeServicesList_Edit(current_direction);
	// });

	//Terminate user
	$(document).on('click', '#terminateUserButton', function () {
		var user_id = JSON.parse($(this).data('user_id'));
		var user_first_name = JSON.parse($(this).data('user_first_name'));
		var user_last_name = JSON.parse($(this).data('user_last_name'));
		$("#terminateUser #user_name").text(user_first_name + " " + user_last_name);
		$('#terminateUser #user_id').val(user_id);
	});

	//Activate user
	$(document).on('click', '#activateUserButton', function () {
		var user_id = JSON.parse($(this).data('user_id'));
		var user_first_name = JSON.parse($(this).data('user_first_name'));
		var user_last_name = JSON.parse($(this).data('user_last_name'));
		$("#activateUser #user_name").text(user_first_name + " " + user_last_name);
		$('#activateUser #user_id').val(user_id);
	});

	//Delete user
	$(document).on('click', '#deleteUserButton', function () {
		var user_id = JSON.parse($(this).data('user_id'));
		var user_first_name = JSON.parse($(this).data('user_first_name'));
		var user_last_name = JSON.parse($(this).data('user_last_name'));
		$("#deleteUser #user_name").text(user_first_name + " " + user_last_name);
		$('#deleteUser #user_id').val(user_id);
	});

	//Function dropdown for user crud
	//Enable user services select if department is selected
	$('#user_department_id').change(function () {
		$('#user_service_id').prop('disabled', false);

		if ($("#user_department_id option:selected").text() === '') {
			$('#user_service_id').empty();
			$('#user_service_id').prop('disabled', true);
		} else {
			var selected_direction = parseInt($("#user_department_id option:selected")[0].value);
			makeServicesList_Create(selected_direction);
		}
	});

	$('#user_department_id2').change(function () {
		$('#user_service_id2').prop('disabled', false);

		if ($("#user_department_id2 option:selected").text() === '') {
			$('#user_service_id2').empty();
			$('#user_service_id2').prop('disabled', true);
		} else {
			var selected_direction = parseInt($("#user_department_id2 option:selected")[0].value);
			makeServicesList_Edit(selected_direction);
		}
	});

	//Populate services list by department selected
	function makeServicesList_Create(user_department_id) {
		$.getJSON('/users/getServicesList/' + user_department_id, function (data) {

			var user_service_id = $("#createUser #user_service_id");

			user_service_id.empty(); // remove old options

			$.each(data.user_service_id, function (key, value) {
				user_service_id.append($("<option></option>").attr("value", key).text(value));
			});
		});
	}

	function makeServicesList_Edit(user_department_id) {
		$.getJSON('/users/getServicesList/' + user_department_id, function (data) {

			var list_service = $("#editUser #user_service_id2");
			var current_service = list_service.val();

			list_service.empty(); // remove old options

			$.each(data.user_service_id, function (key, value) {
				if (key == current_service) list_service.append($("<option selected></option>").attr("value", key).text(value));else list_service.append($("<option></option>").attr("value", key).text(value));
			});
		});
	}

	//Enlève l'attribut disabled sur les inputs days - obligatoire
	$(document).on('click', '#btn-submit-form', function () {
		$('#createUser #user_department_id').attr('disabled', false);
		$('#createUser #user_service_id').attr('disabled', false);

		$('#editUser #user_department_id2').attr('disabled', false);
		$('#editUser #user_service_id2').attr('disabled', false);
	});

	$('#user_create').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#user_create').find('select, textarea, input').each(function () {
			if (!$(this).prop('hidden')) {
				if ($(this).prop('required')) {
					//c'est ici qu'on testera les valeurs et expressions régulières
					//pour le moment on ne teste que si le champ est vide

					//Attention, il faudrait inclure le flag admin ici pour éviter de rendre 
					//le champ disabled aussi pour les admins.
					//Mais en attendant, c'est la meilleure solution
					$('#createUser #user_department_id').attr('disabled', true);

					if ($(this).attr('name') == "user_email") {
						var current_email = $(this).val();
						if (current_email.length <= 3 || current_email.indexOf("@") + 1 <= 1 || current_email.indexOf("@") + 1 >= current_email.length) {
							fail = true;
							name = $(this).attr('name');
							fail_log += name + " is required.\n";
						}
					}

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
			$('#createUser #user_department_id').attr('disabled', false);
			$('#createUser #user_service_id').attr('disabled', false);

			$(this).find('#btn-submit-form').addClass('apply-spin');
		}
	});

	$('#user_update').submit(function (event) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#user_update').find('select, textarea, input').each(function () {
			if (!$(this).prop('hidden')) {
				if ($(this).prop('required')) {
					//c'est ici qu'on testera les valeurs et expressions régulières
					//pour le moment on ne teste que si le champ est vide

					//Attention, il faudrait inclure le flag admin ici pour éviter de rendre 
					//le champ disabled aussi pour les admins.
					//Mais en attendant, c'est la meilleure solution
					$('#editUser #user_department_id2').attr('disabled', true);

					if ($(this).attr('name') == "user_email") {
						var current_email = $(this).val();
						if (current_email.length <= 3 || current_email.indexOf("@") + 1 <= 1 || current_email.indexOf("@") + 1 >= current_email.length) {
							fail = true;
							name = $(this).attr('name');
							fail_log += name + " is required.\n";
						}
					}

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
			$('#editUser #user_department_id2').attr('disabled', false);
			$('#editUser #user_service_id2').attr('disabled', false);
			$(this).find('#btn-submit-form').addClass('apply-spin');
		}
	});
});

/***/ }),
/* 15 */
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

/***/ }),
/* 16 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 17 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 18 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 19 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 20 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 21 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 22 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 23 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 24 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);