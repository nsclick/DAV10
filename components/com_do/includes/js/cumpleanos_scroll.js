// JavaScript Document

window.addEvent('domready', function() {
									 
	var modCumpleLista = new Fx.Scroll('cumpleanos-lista-contenido');
	$('cumpleanos-lista-down').addEvent('click', function(){
		modCumpleLista.scrollTo(0,$('cumpleanos-lista-contenido').scrollTop+80);
	});
	$('cumpleanos-lista-up').addEvent('click', function(){
		modCumpleLista.scrollTo(0,$('cumpleanos-lista-contenido').scrollTop-80);
	});
	
});