<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
						<?php if( count( $this->items ) ) : ?>
							<?php foreach ($this->items as $item) : ?>
                            <div class="item">
                            	<span class="titulo"><a href="<?php echo $item->link; ?>" title="<?php echo $this->escape($item->title); ?>"><?php echo $this->escape($item->title); ?></a></span>
								<?php if(!$this->DOparams->get('fechas') ): ?>
									<span class="fecha"><?php echo date("d/m/Y", strtotime($item->publish_up));?></span>
                                <?php endif; ?>
								<div class="introtext">
                                	<?php echo $item->introtext;?>
                                </div>
                            </div>
                            <?php endforeach; ?>
						<?php endif; ?>
