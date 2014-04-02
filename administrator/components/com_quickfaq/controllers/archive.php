<?php
/**
 * @version 1.0 $Id: archive.php 136 2008-08-04 14:33:18Z schlu $
 * @package Joomla
 * @subpackage QuickFAQ
 * @copyright (C) 2005 - 2008 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
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

jimport('joomla.application.component.controller');

/**
 * QuickFAQ Component Archive Controller
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickFaqControllerArchive extends QuickFaqController
{
	/**
	 * Constructor
	 *
	 *@since 1.0
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * unarchives an Item
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function unarchive()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseWarning(500, JText::_( 'SELECT ITEM UNARCHIVE' ) );
		}
		
		$model = $this->getModel('archive');

		if(!$model->unarchive($cid)) {
			JError::raiseError(500, $model->getError());
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_('ITEMS UNARCHIVED');

		$this->setRedirect( 'index.php?option=com_quickfaq&view=archive', $msg );
	}

	/**
	 * removes an itam
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseWarning(500, JText::_( 'SELECT ITEM DELETE' ) );
		}		
		
		$model = $this->getModel('archive');
		if(!$model->delete($cid)) {
			JError::raiseError(500, $model->getError());
		}

		$total = count( $cid );
		$msg = $total.' '.JText::_( 'ITEMS DELETED');

		$this->setRedirect( 'index.php?option=com_quickfaq&view=archive', $msg );
	}
}
?>