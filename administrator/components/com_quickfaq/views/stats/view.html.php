<?php
/**
 * @version 1.0 $Id: view.html.php 197 2009-01-31 21:34:36Z schlu $
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Stats View
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewStats extends JView
{
	/**
	 * Creates the Entrypage
	 *
	 * @since 1.0
	 */
	function display( $tpl = null )
	{
		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$document	= & JFactory::getDocument();
		$pane   	= & JPane::getInstance('Tabs');
		$lang 		= & JFactory::getLanguage();
		//$user 		= & JFactory::getUser();
		
		// Get data from the model
		$genstats 	= & $this->get( 'Generalstats' );
		$popular	= & $this->get( 'Popular' );
		$rating		= & $this->get( 'Rating' );
		$worstrating= & $this->get( 'WorstRating' );
		$favoured	= & $this->get( 'Favoured' );
		$statestats	= & $this->get( 'Statestats' );
		$votesstats	= & $this->get( 'Votesstats' );
		$creators	= & $this->get( 'Creators' );
		$editors	= & $this->get( 'Editors' );

		//build toolbar
		JToolBarHelper::title( JText::_( 'STATISTICS' ), 'stats' );
		JToolBarHelper::Back();

		//add css and submenu to document
		$document->addStyleSheet('components/com_quickfaq/assets/css/quickfaqbackend.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet('components/com_quickfaq/assets/css/quickfaqbackend_rtl.css');
    	}
		
		//Create Submenu
		JSubMenuHelper::addEntry( JText::_( 'HOME' ), 'index.php?option=com_quickfaq');
		JSubMenuHelper::addEntry( JText::_( 'ITEMS' ), 'index.php?option=com_quickfaq&view=items');
		JSubMenuHelper::addEntry( JText::_( 'CATEGORIES' ), 'index.php?option=com_quickfaq&view=categories');
		JSubMenuHelper::addEntry( JText::_( 'TAGS' ), 'index.php?option=com_quickfaq&view=tags');
		JSubMenuHelper::addEntry( JText::_( 'ARCHIVE' ), 'index.php?option=com_quickfaq&view=archive');
		JSubMenuHelper::addEntry( JText::_( 'FILEMANAGER' ), 'index.php?option=com_quickfaq&view=filemanager');
		JSubMenuHelper::addEntry( JText::_( 'STATISTICS' ), 'index.php?option=com_quickfaq&view=stats', true);
		

		$this->assignRef('pane'			, $pane);
		$this->assignRef('genstats'		, $genstats);
		$this->assignRef('popular'		, $popular);
		$this->assignRef('rating'		, $rating);
		$this->assignRef('worstrating'	, $worstrating);
		$this->assignRef('favoured'		, $favoured);
		$this->assignRef('statestats'	, $statestats);
		$this->assignRef('votesstats'	, $votesstats);
		$this->assignRef('creators'		, $creators);
		$this->assignRef('editors'		, $editors);

		parent::display($tpl);
	}
	
	/**
	 * Creates the buttons view
	 *
	 * @param string $link targeturl
	 * @param string $image path to image
	 * @param string $text image description
	 * @param boolean $modal 1 for loading in modal
	 */
	function quickiconButton( $link, $image, $text, $modal = 0 )
	{
		//initialise variables
		$lang 		= & JFactory::getLanguage();
  		?>

		<div style="float:<?php echo $lang->isRTL() ? 'right' : 'left'; ?>;">
			<div class="icon">
				<?php
				if ($modal == 1) {
					JHTML::_('behavior.modal');
				?>
					<a href="<?php echo $link.'&amp;tmpl=component'; ?>" style="cursor:pointer" class="modal" rel="{handler: 'iframe', size: {x: 650, y: 400}}">
				<?php
				} else {
				?>
					<a href="<?php echo $link; ?>">
				<?php
				}

					echo JHTML::_('image', 'administrator/components/com_quickfaq/assets/images/'.$image, $text );
				?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
}