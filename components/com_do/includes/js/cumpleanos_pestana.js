// JavaScript Document

window.addEvent('domready', function() {
									 
	$('cumpleanos-lista-link').addEvent('click', function(){
		$('cumpleanos-mes-contenido').setStyle('display', 'none');
		$('cumpleanos-mes-td').setProperty('class', 'pestana_off' );
        $('cumpleanos-lista-contenido').setStyle('display', 'block');
        $('cumpleanos-lista-td').setProperty('class', 'pestana_on' );
	});
	
	$('cumpleanos-mes-link').addEvent('click', function(){
		$('cumpleanos-lista-contenido').setStyle('display', 'none');
		$('cumpleanos-lista-td').setProperty('class', 'pestana_off' );
        $('cumpleanos-mes-contenido').setStyle('display', 'block');
        $('cumpleanos-mes-td').setProperty('class', 'pestana_on' );
	});
	
});