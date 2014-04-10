<?php // no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
						<?php if( count( $this->items ) ) : ?>
                        <div class="spacer">
							<?php foreach ($this->items as $i => $item) :
								$divprops 	= "";
								switch( $i % 4 ) :
									case 0:
										$align		= ' align="left"';
										$divprops	= $align.' class="itemizq"';
										break;
									case 1:
									case 2:
										$align		= ' align="center"';
										$divprops	= ' align="center" class="itemcen"';
										break;
									case 3:
										$align		= ' align="right"';
										$divprops	= ' align="left" class="itemder"';
										break;
								endswitch;
								
								echo $i && $i%4==0 ? '</div><div class="spacer">':'';
							?>
                            <div<?php echo $divprops;?>>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" class="itemsgal">
                                <tr>
                                  <td<?php echo $align;?>><a href="<?php echo $item->link; ?>" title="<?php echo $this->escape($item->title); ?>"><img src="<?php echo $template;?>/imagenes/icono_galeria.jpg" alt="" border="0" vspace="10"<?php echo $align==' align="center"'?'':$align;?> /></a></td>
                                </tr>
                                <tr>
                                  <td align="left" class="titulo"><a href="<?php echo $item->link; ?>" title="<?php echo $this->escape($item->title); ?>"><?php echo $this->escape($item->title); ?></a></td>
                                </tr>
								<?php if(!$this->DOparams->get('fechas') ): ?>
                                <tr>
                                  <td align="left" class="intro">Fecha: <?php echo date("d/m/Y", strtotime($item->publish_up));?></td>
                                </tr>
								<?php endif; ?>
                              </table>
                            	<!--<div style="overflow:hidden;"<?php echo $align;?>><a href="<?php echo $item->link; ?>" title="<?php echo $this->escape($item->title); ?>"><img src="<?php echo $template;?>/imagenes/icono_galeria.jpg" alt="" border="0" vspace="10"<?php echo $align==' align="center"'?'':$align;?> /></a></div>
                            	<div class="titulo"><a href="<?php echo $item->link; ?>" title="<?php echo $this->escape($item->title); ?>"><?php echo $this->escape($item->title); ?></a></div>
                                <div class="introtext">
                                	<?php /*echo $item->introtext;*/?>
                                    Fecha: <?php echo date("d/m/Y", strtotime($item->publish_up));?>
                                </div>-->
                            </div>
                            <?php endforeach; ?>
						<?php endif; ?>
                        </div>
