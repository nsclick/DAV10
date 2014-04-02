$(document).ready(function() {
	$('select.sel2').select2();

	$('#estado').switchy();

	$('#estado').on('change', function() {

		// Animate Switchy Bar background color
		var bgColor = '#A1EA7F';

		if ($(this).val() == 0) {
			bgColor = '#ED7A7A';
		} else if ($(this).val() == 1) {
			bgColor = '#A1EA7F';
		}

		$('.switchy-bar').animate({
			backgroundColor: bgColor
		});

		if ($('#estado').val() == 0) {
			$('.estado-msj').html('<p class="des">Desactivado</p>');
		} else {
			$('.estado-msj').html('<p class="act">Activado</p>');
		}
	});

	if ($('#estado').val() == 0) {
		$('.estado-msj').html('<p class="des">Desactivado</p>');
	} else {
		$('.estado-msj').html('<p class="act">Activado</p>');
	}
});