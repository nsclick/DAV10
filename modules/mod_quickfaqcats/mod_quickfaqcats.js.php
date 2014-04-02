<?php

?>
window.addEvent('domready', function() {
<?php foreach( $datos->categorias as $c => $categoria ) : if( count( $categoria->articles ) ): ?>
	$('quickfaq-cat<?php echo $categoria->id;?>-link').addEvent('click', function(){
        <?php foreach( $datos->categorias as $cc => $cat ) : if( count( $cat->articles ) ): ?>
       		$('quickfaq-cat<?php echo $cat->id;?>-arts').setStyle('display', 'none');
        	$('quickfaq-cat<?php echo $cat->id;?>-td').setProperty('class', 'pestana_off' );
            $('quickfaq-cat<?php echo $cat->id;?>-title').setProperty('class', 'off' );
       	<?php endif; endforeach; ?>
        $('quickfaq-cat<?php echo $categoria->id;?>-arts').setStyle('display', 'block');
        $('quickfaq-cat<?php echo $categoria->id;?>-td').setProperty('class', 'pestana_on' );
        $('quickfaq-cat<?php echo $categoria->id;?>-title').setProperty('class', 'activo' );
	});
<?php endif; endforeach;?>
});