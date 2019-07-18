$(function () {

	// Available panels
	// #absence_user
	// #absence_direction
	var tab_active = localStorage.getItem('absences-tab-active');
	if (tab_active) {
		$('#absences-tab-selection a[href="' + tab_active + '"]').tab('show');
	}
	else {
		$('#absences-tab-selection a[href="#absence_user"]').tab('show'); //default panel
	}

	$('#absences-tab-selection a[data-toggle="tab"]').click( function (e) {
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

	$('#absence_update' ).submit( function( event ) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#absence_update').find( 'select, textarea, input' ).each(function(){
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

	$('#absence_create' ).submit( function( event ) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#absence_create').find( 'select, textarea, input' ).each(function(){
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

});