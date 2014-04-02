<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: controller.php 1 2009-09-01 13:31:26Z j.trumpes $
 * @package JoomDOC
 * @copyright (C) 2009 Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once (JPATH_COMPONENT_HELPERS . DS . 'documents.php');
class DocmanController extends JController
{
    function __construct ($config = array())
    {
        parent::__construct($config);
        $this->registerTask('license_result', 'license_result');
    }
    function display ()
    {
		$this->html_inicio();
        $gid = DocmanHelper::getGid();
        switch ($this->getTask()) {
            case 'cat_view':
                JRequest::setVar('view', 'docman');
                break;
            case 'doc_download':
            case 'doc_view':
                JRequest::setVar('view', 'download');
                break;
            case 'search_form':
            case 'search_result':
                JRequest::setVar('view', 'search');
                break;
            case 'doc_details':
                JRequest::setVar('view', 'document');
                break;
            case 'doc_edit':
                $view = $this->getView('document', 'html');
                $view->_displayForm();
				$this->html_fin();
                return;
            case 'doc_save':
            case 'save':
                DocumentsHelper::saveDocument($gid);
                break;
            case 'doc_cancel':
            case 'cancel':
                DocumentsHelper::cancelDocument($gid);
                break;
            case 'doc_move':
                $view = $this->getView('document', 'html');
                $view->_displayMove();
				$this->html_fin();
                return;
            case 'doc_move_process':
                DocumentsHelper::moveDocumentProcess($gid);
                break;
            case 'doc_checkin':
                DocumentsHelper::checkinDocument($gid);
                break;
            case 'doc_checkout':
                DocumentsHelper::checkoutDocument($gid);
                break;
            case 'doc_reset':
                DocumentsHelper::resetDocument($gid);
                break;
            case 'doc_delete':
                DocumentsHelper::deleteDocument($gid);
                break;
            case 'upload':
                $view = $this->getView('document', 'html');
                $view->_displayUpload(0);
				$this->html_fin();
                return;
            case 'doc_update':
                $view = $this->getView('document', 'html');
                $view->_displayUpload(1);
				$this->html_fin();
                return;
            case 'doc_approve':
                DocumentsHelper::approveDocument(array($gid));
                break;
            case 'doc_unpublish':
                DocumentsHelper::publishDocument(array($gid), 0);
                break;
            case 'doc_publish':
                DocumentsHelper::publishDocument(array($gid));
                break;
        }
        parent::display(true);
		$this->html_fin();
    }
    function license_result ()
    {
		$this->html_inicio();
        require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'downloads.php');
        DownloadsHelper::licenseDocumentProcess(DocmanHelper::getGid());
		$this->html_fin();
    }
	function html_inicio()
	{
		global $Itemid;
		$menu 	= JTable::getInstance('Menu');
		$menu->load( $Itemid );
		
		$core	= null;
		?>
			<div class="componente">
			<?php if ( $core ) : ?>
            	<div class="core"><img src="<?php echo $this->baseurl . '/images/core/'.  $core;?>" alt="<?php echo $menu->name; ?>" title="<?php echo $menu->name; ?>" border="0" /></div>
            <?php endif; ?>
            	<div class="contenido">
                    <div class="box_t"><div class="box_b"><div class="box_l"><div class="box_r"><div class="box_bl"><div class="box_br"><div class="box_tl"><div class="box_tr">
                    <div class="box com_docman">
                        <h1><?php echo $menu->name; ?></h1>
		<?php
	}
	function html_fin()
	{
		?>
                    </div>
                    </div></div></div></div></div></div></div></div>
                </div>
            </div>
		<?php
	}
}
?>