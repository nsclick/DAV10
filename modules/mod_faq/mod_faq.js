
window.addEvent('domready', function() {
	//var modFaqLista = new Fx.Scroll('mod_faq_lista');
	alert("se");
	$('mod_faq_anterior').addEvent('click', function(){
		modFaqLista.scrollTo($('mod_faq_lista').scrollLeft-888,0);
	});
	$('mod_faq_siguiente').addEvent('click', function(){
		alert($('mod_faq_lista').scrollLeft);
		//modFaqLista.scrollTo($('mod_faq_lista').scrollLeft+888,0);
	});
});


/*jQuery(document).ready(function()
{
	jQuery("#mod_faq_anterior").click(function(){
		//jQuery("#mod_faq_lista").scrollLeft(jQuery("#mod_faq_lista").scrollLeft-888);	
		//alert(jQuery("#mod_faq_lista").scrollLeft());
		jQuery("#mod_faq_lista").scrollTo(jQuery("#mod_faq_lista").scrollLeft-888,0, {queue:true});	
	});
	jQuery("#mod_faq_siguiente").click(function(){
		//jQuery("#mod_faq_lista").scrollLeft(jQuery("#mod_faq_lista").scrollLeft+888);
		jQuery("#mod_faq_lista").scrollTo(jQuery("#mod_faq_lista").scrollLeft+888,0, {queue:true});
	});
	//jQuery(".scrollable").scrollable();
});*/
