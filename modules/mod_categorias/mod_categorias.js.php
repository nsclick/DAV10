<?php

?>
window.addEvent('domready', function() {
<?php foreach( $datos->categorias as $c => $categoria ) : if( count( $categoria->articles ) ): ?>
	$('mod_categorias-cat<?php echo $categoria->id;?>-link').addEvent('click', function(){
        <?php foreach( $datos->categorias as $cc => $cat ) : if( count( $cat->articles ) ): ?>
       		$('mod_categorias-cat<?php echo $cat->id;?>-arts').setStyle('display', 'none');
        	$('mod_categorias-cat<?php echo $cat->id;?>-td').setProperty('class', 'pestana_off' );
            $('mod_categorias-cat<?php echo $cat->id;?>-title').setProperty('class', 'off' );
       	<?php endif; endforeach; ?>
        $('mod_categorias-cat<?php echo $categoria->id;?>-arts').setStyle('display', 'block');
        $('mod_categorias-cat<?php echo $categoria->id;?>-td').setProperty('class', 'pestana_on' );
        $('mod_categorias-cat<?php echo $categoria->id;?>-title').setProperty('class', 'activo' );
	});
<?php endif; endforeach;?>
});