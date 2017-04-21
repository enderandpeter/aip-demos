jQuery(function($){
	$('#start_date').datetimepicker({
		format: 'm/d/Y h:a',
		minDate: $($('#start_date').data('startdate'))
	});
});