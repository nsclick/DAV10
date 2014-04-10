$(function() {

	$.fn.editable.defaults.mode = 'inline';

		$('#nombre_usuario').editable({
		type: 'text',
		name: 'nombre_usuario'
	});

});