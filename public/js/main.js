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
__webpack_require__(2);
__webpack_require__(3);
__webpack_require__(4);
__webpack_require__(5);
__webpack_require__(6);
__webpack_require__(7);
__webpack_require__(8);
__webpack_require__(9);
module.exports = __webpack_require__(10);


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

	/** BTN BACK TO TOP **/
	window.onscroll = function () {
		scrollFunction();
	};

	function scrollFunction() {
		if (document.body.scrollTop > 10 || document.documentElement.scrollTop > 10) {
			document.getElementById("back_to_top").style.display = "block";
		} else {
			document.getElementById("back_to_top").style.display = "none";
		}
	}

	// When the user clicks on the button, scroll to the top of the document
	function topFunction() {
		document.body.scrollTop = 0; // For Safari
		document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
	}

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
	$(function () {
		var session_timeout = "120";

		$.idleTimer(session_timeout * 60000);

		$(document).on("idle.idleTimer", function () {
			var dest_url = '/logout';
			$.ajax({
				type: "POST",
				url: dest_url,
				data: { _token: $('meta[name="csrf-token"]').attr('content') },
				success: function success() {
					window.location.href = '/login';
				}
			});
		});
	});

	//success message timeout
	setTimeout(function () {
		$('#successMessage').fadeOut('fast');
	}, 5000);

	//disable dropdown if att readonly
	if ($(".select_false").attr("readonly")) {
		$(".select_false").css("pointer-events", "none");
	}

	$(function () {
		$('[data-toggle="popover"]').popover();
	});
	$('.popover-dismiss').popover({
		trigger: 'focus'
	});

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

// removed by extract-text-webpack-plugin

/***/ }),
/* 3 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 4 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 5 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 6 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 7 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 8 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 9 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 10 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);