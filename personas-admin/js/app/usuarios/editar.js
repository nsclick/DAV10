$(document).ready(function() {
	$('select.sel2').select2();

	$('#estado').switchy();

	var bgColor = '#A1EA7F';

	$('#estado').on('change', function() {

		var esteEs = $(this);

		if (esteEs.val() == 0) {
			bgColor = '#ED7A7A';
			$('.estado-msj').html('<p class="des">Desactivado</p>');
		} else {
			bgColor = '#A1EA7F';
			$('.estado-msj').html('<p class="act">Activado</p>');
		}

		$('.switchy-bar').animate({
			backgroundColor: bgColor
		});
	});

	if ($('#estado').val() == 0) {
		bgColor = '#ED7A7A';
		$('.estado-msj').html('<p class="des">Desactivado</p>');
	} else {
		bgColor = '#A1EA7F';
		$('.estado-msj').html('<p class="act">Activado</p>');
	}

	$('.switchy-bar').animate({
		backgroundColor: bgColor
	});

	var este = "";

	$('.mod-contr').live('click', function(e) {
		e.preventDefault();
		este = $(this);
		este.addClass('no-mod-contr');
		este.removeClass('mod-contr');
		este.text('No modificar contraseña');
		$('.contrasenas input').attr({
			'disabled': false
		});
		$('.contrasenas').css({
			'display': 'block'
		});
	});

	$('.no-mod-contr').live('click', function(e) {
		e.preventDefault();
		var este = $(this);
		este.addClass('mod-contr');
		este.removeClass('no-mod-contr');
		este.text('Modificar contraseña');
		$('.contrasenas input').attr({
			'disabled': true
		});
		$('.contrasenas').css({
			'display': 'none'
		});
	});
});