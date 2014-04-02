<?php

/**
 * config
 */
$config		= JFactory::getConfig();

/**
 * session
 */
$session	=& JFactory::getSession();

/**
 * do tables
 */
JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_do'.DS.'tablas' );

/**
 * Defines
 */
define ( '_DO_PATH', dirname ( str_replace ( $_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME'] ) ) );
define( '_DO', 1 );
define( '_DO_HOME', _DO_PATH . '/index.php' );
define( '_DO_LOGIN_BANMEDICA', true );
define( '_DO_LOGIN_BANMEDICA_REDIRECT', true );
define( '_DO_LOGIN_JOOMLA', true );
define( '_DO_LOGIN_FORM',  _DO_PATH . '/login.php' );
define( '_DO_LOGIN_URL', 'http://banmeta4web.banmedica.cl/cdavila/verificar_pregunta_intranet.asp' );
define( '_DO_LOGIN_OFFLINE', 'Estimado Usuario, por problemas técnicos, el servicio del Portal Dávila se encuentra suspendido.<br />Esperamos que esto se solucione pronto. Agradecemos su comprensión.' );
define( '_DO_FOTOS_BASE', _DO_PATH . '/images/fotos/' );
define( '_DO_ANALYTICS', true );

?>