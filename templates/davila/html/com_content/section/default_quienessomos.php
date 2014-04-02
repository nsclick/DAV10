<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>
	<div class="com_content seccion quienessomos" align="left">
    	<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
        <h2><?php echo $this->escape($this->categories[0]->title);?></h2>
        <div class="box_descripcion">
        	<?php echo $this->categories[0]->description; ?>
        </div>
        <div class="box_descripcion_bottom"><img src="images/pix_transparente.gif" alt="" width="710" height="18" /></div>
        <!--<div class="descripcion">
        	<div class="bg_top"><img src="images/pix_transparente.gif" alt="" width="710" height="6" /></div>
        	<?php echo $this->categories[0]->description; ?>
        </div>-->
        <h2 class="dos"><?php echo $this->escape($this->categories[1]->title);?></h2>
      <?php if ( count( $this->categories[1]->articles ) ) : $total = count( $this->categories[1]->articles ); ?>
      	<div class="organigrama">
          <table width="710" border="0" cellpadding="0" cellspacing="0" class="organigrama">
            <tr>
            <?php for($a=0; $a < $total; $a+=2) :
				$article = $this->categories[1]->articles[$a];
				$patronImg = "(<img[^<>]*/>)";
				if ( ereg( $patronImg, $article->introtext, $regs ) ) :
					$img 			= $regs[1];
					$patronStyle 	= "( style=\"[^<>]*\")";
					$img 			= ereg_replace($patronStyle,"",$img);
					$patronAlign 	= "( align=\"[^<>]*\")";
					$img 			= ereg_replace($patronAlign,"",$img);
					$patronHspace 	= "( hspace=\"[^<>]*\")";
					$img			= ereg_replace($patronHspace,"",$img);
					$patronVspace 	= "( vspace=\"[^<>]*\")";
					$img			= ereg_replace($patronVspace,"",$img);
					
					$img			= ereg_replace("(\">)"," align=\"right\" hspace=\"5\" />",$img);
					
					$article->img			= $img;
					$article->introtext		= ereg_replace($patronImg,"",$article->introtext);
					$article->introtext		= ereg_replace("<p>","",$article->introtext);
					$article->introtext		= ereg_replace("</p>","",$article->introtext);
				endif;
			?>
              <td width="50%" valign="top" align="left" style="padding-right:55px;">
                <?php echo $article->img;?>
              	<a href="<?php echo $article->link; ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a><br />
                <?php echo $article->introtext;?>
              </td>
              <?php if( $a%2==0 && $a+1==$total ) : ?>
              <td>&nbsp;</td>
              <?php else:
			  	$article = $this->categories[1]->articles[$a+1];
				$patronImg = "(<img[^<>]*/>)";
				if ( ereg( $patronImg, $article->introtext, $regs ) ) :
					$img 			= $regs[1];
					$patronStyle 	= "( style=\"[^<>]*\")";
					$img 			= ereg_replace($patronStyle,"",$img);
					$patronAlign 	= "( align=\"[^<>]*\")";
					$img 			= ereg_replace($patronAlign,"",$img);
					$patronHspace 	= "( hspace=\"[^<>]*\")";
					$img			= ereg_replace($patronHspace,"",$img);
					$patronVspace 	= "( vspace=\"[^<>]*\")";
					$img			= ereg_replace($patronVspace,"",$img);
					
					$img			= ereg_replace("(\">)"," align=\"right\" hspace=\"5\" />",$img);
					
					$article->img			= $img;
					$article->introtext		= ereg_replace($patronImg,"",$article->introtext);
					$article->introtext		= ereg_replace("<p>","",$article->introtext);
					$article->introtext		= ereg_replace("</p>","",$article->introtext);
				endif;
			  ?>
              <td width="50%" valign="top" align="left" style="padding-left:55px;">
                <?php echo $article->img;?>
              	<a href="<?php echo $article->link; ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a><br />
                <?php echo $article->introtext;?>
              </td>
			<?php if( $a+2<$total ) : ?>
            </tr>
            <tr>
            <?php endif;?>
              <?php endif;?>
            <?php endfor; ?>
            </tr>
          </table>
        </div>
      <?php endif; ?>
    </div>