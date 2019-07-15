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
/******/ 	return __webpack_require__(__webpack_require__.s = 30);
/******/ })
/************************************************************************/
/******/ ({

/***/ 30:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(31);


/***/ }),

/***/ 31:
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

/***/ })

/******/ });