$(function () {
	
	// Available panels
	// #planning
	// #resume
	var tab_active = localStorage.getItem('planning-tab-active');
	if (tab_active) {
		$('#planning-tab-selection a[href="' + tab_active + '"]').tab('show');
	}
	else {
		$('#planning-tab-selection a[href="#planning"]').tab('show'); //default panel
	}

	$('#planning-tab-selection a[data-toggle="tab"]').click( function (e) {
		var _target = $(e.target).attr('href');
		if (!_target) _target = $(this).attr('href');

		localStorage.setItem('planning-tab-active', _target);
		$('#planning-tab-selection a[href="' + _target + '"]').tab('show');
	});
});	