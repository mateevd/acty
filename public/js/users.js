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
/******/ 	return __webpack_require__(__webpack_require__.s = 35);
/******/ })
/************************************************************************/
/******/ ({

/***/ 35:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(36);


/***/ }),

/***/ 36:
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
	$(document).on('click', '#editUserButton', function () {
		var data = JSON.parse($(this).data('user'));
		$("#editUser #user_name").text(data.user_first_name + " " + data.user_last_name);
		$("#editUser #user_id").text('id='.concat(data.user_id));

		var current_direction = data.user_department_id2;
		// var current_service = data.user_service_id2;				

		$.each(data, function (key, value) {
			$('#editUser #' + key).val(value);
		});

		makeServicesList_Edit(current_direction);
	});

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

/***/ })

/******/ });