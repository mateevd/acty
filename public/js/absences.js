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
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ 11:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(12);


/***/ }),

/***/ 12:
/***/ (function(module, exports) {

$(function () {

	// Available panels
	// #absence_user
	// #absence_direction
	// var tab_active = localStorage.getItem('absences-tab-active');
	// if (tab_active) {
	// 	$('#absences-tab-selection a[href="' + tab_active + '"]').tab('show');
	// }
	// else {
	// 	$('#absences-tab-selection a[href="#absence_user"]').tab('show'); //default panel
	// }
	//
	// $('#absences-tab-selection a[data-toggle="tab"]').click( function (e) {
	// 	var _target = $(e.target).attr('href');
	// 	if (!_target) _target = $(this).attr('href');
	//
	// 	localStorage.setItem('absences-tab-active', _target);
	// 	$('#absences-tab-selection a[href="' + _target + '"]').tab('show');
	// });


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

/***/ })

/******/ });