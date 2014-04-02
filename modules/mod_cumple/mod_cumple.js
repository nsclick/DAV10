// JavaScript Document

window.addEvent('domready', function() {
	var modCumpleLista = new Fx.Scroll('mod_cumple_con_lista');
	$('mod_cumple_down').addEvent('click', function(){
		modCumpleLista.scrollTo(0,$('mod_cumple_con_lista').scrollTop+30);
	});
	$('mod_cumple_up').addEvent('click', function(){
		modCumpleLista.scrollTo(0,$('mod_cumple_con_lista').scrollTop-30);
	});
});