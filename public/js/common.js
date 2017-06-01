$.fn.datepicker.defaults.format = 'dd/mm/yyyy';

function rebind_clockpicker()
{
	$('[data-provide="clockpicker"]').clockpicker({
		donetext: 'Done',
		twelvehour: true,
	});
}

function show_modal(url)
{
	$.get(url)
		.done(function(response) {
			$('body').append(response);
		})
		.fail(function(jqxhr, settings, exception) {
			alert(exception);
	});
}

rebind_clockpicker();
