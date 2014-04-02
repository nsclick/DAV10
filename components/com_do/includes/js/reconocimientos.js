// JavaScript Document

window.addEvent('domready', function() {
									 
	var modCumpleLista = new Fx.Scroll('cumpleanos-lista-contenido');
	var modCumpleMes = new Fx.Scroll('cumpleanos-mes-contenido');
	$('cumpleanos-lista-down').addEvent('click', function(){
		modCumpleLista.scrollTo(0,$('cumpleanos-lista-contenido').scrollTop+80);
		modCumpleMes.scrollTo(0,$('cumpleanos-mes-contenido').scrollTop+80);
	});
	$('cumpleanos-lista-up').addEvent('click', function(){
		modCumpleLista.scrollTo(0,$('cumpleanos-lista-contenido').scrollTop-80);
		modCumpleMes.scrollTo(0,$('cumpleanos-mes-contenido').scrollTop-80);
	});
	
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