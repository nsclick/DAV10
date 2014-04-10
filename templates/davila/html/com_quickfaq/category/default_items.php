<?php
/**
 * @version 1.0 $Id: default_items.php 195 2009-01-30 06:33:12Z schlu $
 * @package Joomla
 * @subpackage QuickFAQ
 * @copyright (C) 2008 - 2009 Christoph Lukes
 * @license GNU/GPL, see LICENCE.php
 * QuickFAQ is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * QuickFAQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with QuickFAQ; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
						<?php if( count( $this->items ) ) : ?>
							<?php foreach ($this->items as $item) : ?>
                            <div class="item">
                            	<span class="titulo"><a href="<?php echo $item->link; ?>" title="<?php echo $this->escape($item->title); ?>"><?php echo $this->escape($item->title); ?></a></span><span class="fecha"><?php echo date("d/m/Y", strtotime($item->publish_up));?></span>
                                <div class="introtext">
                                	<?php echo $item->introtext;?>
                                </div>
                            </div>
                            <?php endforeach; ?>
						<?php endif; ?>