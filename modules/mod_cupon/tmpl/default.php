<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$app					=& JFactory::getApplication();
$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<script type="text/javascript">
	/*window.addEvent('domready', function() {
		ancho		= 530;
		alto		= 520;

		var hz		= window.screen.height;
		var wz		= window.screen.width;
		var top		= ((hz/2)-(alto/2));
		var left	= ((wz/2)-(ancho/2));
		propiedades	= "status=no,toolbar=no,scrollbars=no,titlebar=no,menubar=no,resizable=no,width="+ancho+",height="+alto+",directories=no,location=no,top="+top+",left="+left;
		window.open('cupon.php','Cupon',propiedades);
	});*/
	window.addEvent('domready', function() {
		SqueezeBox.initialize({
			handler: 'iframe',
			urlopt: '<?php echo JURI::base();?>cupon.php' ,
			size: {x: 540, y: 530}
		});
		SqueezeBox.initURL( '<?php echo JURI::base();?>cupon.php' );
		SqueezeBox.fromElement();
	});
</script>