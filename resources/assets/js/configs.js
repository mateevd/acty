$(function () {

	$('#password_change' ).submit( function( event ) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#password_change').find( 'select, textarea, input' ).each(function(){
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
			$(this).find('#btn-submit-form-password').addClass('apply-spin');
		}
	});

	$('#display_change' ).submit( function( event ) {
		//validation des champs
		var fail = false;
		var fail_log = '';
		var name;
		$('#display_change').find( 'select, textarea, input' ).each(function(){
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
			$(this).find('#btn-submit-form-display').addClass('apply-spin');
		}
	});



});