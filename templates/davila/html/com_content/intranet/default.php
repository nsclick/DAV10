<?php // no direct access
defined('_JEXEC') or die('Restricted access');

?>
				<div style="width:100%; margin-bottom:8px;">
                    <div class="box_t"><div class="box_b"><div class="box_l"><div class="box_r"><div class="box_bl"><div class="box_br"><div class="box_tl"><div class="box_tr">
                    <div class="box">
                        <div class="frontpage">
                            <div class="contenido">
                                <h1>Noticias Pehuenche</h1>
                                <div class="separador"><img src="images/pix_transparente.gif" width="1" height="1" alt="" /></div>
                                <?php echo $this->total; ?>
								<?php for ($i = $this->pagination->limitstart; $i < 1; $i++) : ?>
                                    <?php if ($i >= $this->total) : break; endif; ?>
                                    <div class="item">
                                    <?php
                                        $this->item =& $this->getItem($i, $this->params);
                                       	echo $this->loadTemplate('item');
                                    ?>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <div class="agenda">
                                <h1 class="agenda">Agenda</h1>
                                <div class="separador"><img src="images/pix_transparente.gif" width="1" height="1" alt="" /></div>
                                <div align="center"><?php echo $this->agenda;?></div>
                            </div>
                        <div class="separador"><img src="images/pix_transparente.gif" width="1" height="1" alt="" /></div>
						<?php for ($i = 1; $i < ($this->pagination->limitstart + $this->params->get('num_leading_articles')); $i++) : ?>
                            <?php if ($i >= $this->total) : break; endif; ?>
                            <div class="item">
                            <?php
                                $this->item =& $this->getItem($i, $this->params);
                                echo $this->loadTemplate('item');
                            ?>
                            </div>
                        <div class="separador"><img src="images/pix_transparente.gif" width="1" height="1" alt="" /></div>
                        <?php endfor; ?>
                        </div>
                    </div>
                    </div></div></div></div></div></div></div></div>
				</div>