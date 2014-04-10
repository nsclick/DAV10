<?php if ( count( $categories ) ) : ?>
window.addEvent('domready', function() {
  <?php foreach( $categories as $c => $category ) : if( count( $category->articles ) ) : ?>
    <?php foreach( $category->articles as $a => $article ) : ?>
	$('articulos-art<?php echo $article->id;?>-link').addEvent('click', function(){
	  <?php foreach( $categories as $c => $category2 ) : if( count( $category2->articles ) ) : ?>
        <?php foreach( $category2->articles as $a2 => $article2 ) : ?>
       		$('articulos-art<?php echo $article2->id;?>-contenedor').setStyle('display', 'none');
            $('articulos-art<?php echo $article2->id;?>-link').setProperty('class', '' );
		<?php endforeach;?>
      <?php endif; endforeach;?>
      $('articulos-art<?php echo $article->id;?>-contenedor').setStyle('display', 'block');
      $('articulos-art<?php echo $article->id;?>-link').setProperty('class', 'activo' );
	});
    <?php endforeach;?>
  <?php endif; endforeach;?>
});
<?php endif;?>