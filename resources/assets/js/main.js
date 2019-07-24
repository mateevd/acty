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
	require('./absences.js');
	require('./activities.js');
	require('./charges.js');
	require('./configs.js');
	require('./dashboard.js');
	require('./dragdrop.js');
	require('./info.js');
	require('./log_activity.js');
	require('./login.js');
	require('./phases.js');
	require('./planning.js');
	require('./tasks.js');
	require('./users.js');
	require('./work_days.js');

	window.onscroll = function () {scrollFunction()};
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
			$('html, body').animate({scrollTop:0}, '130');
	}, false);

	/** FLASHY MESSAGES**/
	function flashy(message, link) {
		var template = $($("#flashy-template").html());
		$(".flashy").remove();
		template.find(".flashy__body").html(message).attr("href", link || "#").end()
			.appendTo("body").hide().fadeIn(300).delay(2800).animate({
			marginRight: "-100%"
		}, 300, "swing", function () {
			$(this).remove();
		});
	}

	/** BOOTSTRAP SORT COLUMS**/
	function sortSelect(selElem, sortVal) {
		// Checks for an object or string. Uses string as ID.
		switch (typeof selElem) {
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
			case "value": // sort by value
				sortVal = 1;
				break;
			default: // sort by text
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
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
		$("#user_card h1").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
		$("#resume_card h1").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
		$("#dashboard_user_card h1").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
		$("#dashboard_entity_card h1").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	$("#activitiesDetailsInput").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#activity_details_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	$("#chargesDetailsInput").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#charges_details_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	$("#wd_validate_input").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#wd_validate_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	$("#wd_deny_input").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#wd_deny_table tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	/** SESSION TIMEOUT LOGOUT + REDIRECT**/
	$(function () {
		var session_timeout = process.env.MIX_SESSION_LIFETIME;

		$.idleTimer(session_timeout * 60000);

		$(document).on("idle.idleTimer", function () {
			var dest_url = '/logout';
			$.ajax({
				type: "POST",
				url: dest_url,
				data: {_token: $('meta[name="csrf-token"]').attr('content')},
				success: function () {
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
		$('[data-toggle="popover"]').popover()
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
		}
		else {
			//c'est ok, on lance l'affichage du loading
			$(this).find('#btn-submit-form-date').addClass('apply-spin');
		}
	});
});