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
/******/ 	return __webpack_require__(__webpack_require__.s = 33);
/******/ })
/************************************************************************/
/******/ ({

/***/ 33:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(34);


/***/ }),

/***/ 34:
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

/***/ })

/******/ });