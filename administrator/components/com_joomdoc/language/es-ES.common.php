<?php
/**
 * JoomDOC 1.4.x - Joomla! Document Manager
 * @version $Id: english.common.php 651 2008-03-20 20:33:15Z mjaz $
 * @package JoomDOC_1.4
 * @copyright (C) 2003-2008 The JoomDOC Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official web site
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * TRANSLATORS:
 * PLEASE ADD THE INFO BELOW
 */

/**
 * Language:spanish for JoomDOC by Artio
 * Creator:Chema
 * Website:http://www.descargasjoomla.com
 * E-mail:chema_34@hotmail.es
 * Revision:2.0_rev1
 * Date:09/17/2009
 */

define ('_DM_DATEFORMAT_LONG','%d.%m.%Y %H:%M'); // use PHP strftime Format, more info at http://php.net
define ('_DM_DATEFORMAT_SHORT','%d.%m.%Y');		 // use PHP strftime Format, more info at http://php.net
define ('_DM_ISO','iso-8859-1');
define ('_DM_LANG','es');

// -- General
define('_DML_NAME', "Nombre");
define('_DML_DATE', "Fecha");
define('_DML_DATE_MODIFIED', "Fecha de modificaci&oacute;n");
define('_DML_HITS', "Hits");
define('_DML_SIZE', "Tama&ntilde;o");
define('_DML_EXT', "Extensi&oacute;n");
define('_DML_MIME', "Tipo Mime");
define('_DML_THUMBNAIL', "Thumbnail");
define('_DML_DESCRIPTION', "Descripci&oacute;n");
define('_DML_VERSION', "Versi&oacute;n");
define('_DML_DEFAULT', "Predeterm.");
define('_DML_FOLDER', "Carpeta");
define('_DML_FOLDERS', "Carpetas");
define('_DML_FILE', "Archivo");
define('_DML_FILES', "Archivos");
define('_DML_URL', "URL");
define('_DML_PARAMS', "Paraacute;metros");
define('_DML_PARAMETERS', "Paraacute;metros");
define('_DML_TOP', "Arriba");
define('_DML_PROPERTY', "Propiedad");
define('_DML_VALUE', "Valor");
define('_DML_PATH', "Ruta");

define('_DML_DOC', "Documento");
define('_DML_DOCS', "Documentos");
define('_DML_DOCUMENT', "Documento");
define('_DML_CAT', "Categor&iacute;a");
define('_DML_CATS', "Categor&iacute;as");
define('_DML_CATEGORY', "Categor&iacute;a");

define('_DML_UPLOAD', "Subir");
define('_DML_SECURITY', "Seguridad");
define('_DML_CPANEL', "Panel de Control DOCman");
define('_DML_CONFIG', "Configuraci&oacute;n");
define('_DML_LICENSE', "Licencia");
define('_DML_LICENSES', "Licencias");
define('_DML_UPDATES', "Actualizaciones");
define('_DML_DOWNLOADS', "Descargas");

define('_DML_HOMEPAGE', "Inicio");

define('_DML_NO', "No");
define('_DML_YES', "S&iacute");
define('_DML_OK', "OK");
define('_DML_CANCEL', "Cancelar");
define('_DML_ADD', "A&ntilde;adir");
define('_DML_EDIT', "Editar");
define('_DML_CONTINUE', "Continuar");
define('_DML_SAVE', "Guardar");

define('_DML_APPROVED', "Aprovado");
define('_DML_DELETED', "Borrado");

define('_DML_INSTALL', "Instalar");
define('_DML_PUBLISHED', "Publicado");
define('_DML_UNPUBLISH', "No Publicar");
define('_DML_CHECKED_OUT', "Reservado");

define('_DML_TOOLTIP', "Ayuda DOCman");
define('_DML_FILTER_NAME', "Filtrar por nombre");

define('_DML_TITLE', "T&iacute;tulo");
define('_DML_MULTIPLE_SELECTS', "mantenga presionada la tecla <b>Ctrl</b> (con Windows/Unix/Linux) o la tecla <b>Command</b> (con Mac) mientras hace su selecci&oacute;n.");

define('_DML_USER', "Usuario");
define('_DML_OWNER', "Due&ntilde;o");
define('_DML_CREATOR', "Creadore");
define('_DML_EDITOR', "Editor");
define('_DML_MAINTAINER', "Mantenedor");
define('_DML_UNKNOWN', "Desconocido");

define('_DML_FILEICON_ALT', "Icono del Archivo");

define('_DML_NOT_AUTHORIZED', "No autorizado");
define('_DML_ERROR', "Error");
define('_DML_OPERATION_FAILED', "Operaci&oacute;n Fallida");

define('_DML_EDIT_THIS_MODULE', "Editar este m&oacute;dulo");
define('_DML_UNPUBLISH_THIS_MODULE', "No publicar este m&oacute;dulo");
define('_DML_ORDER_THIS_MODULE', "Ordenar este m&oacute;dulo");

define('_DML_WRITABLE', "Modificable");
define('_DML_UNWRITABLE', "No Modificable");

define('_DML_SAVED_CHANGES', "Cambios Guardados");
define('_DML_ARE_YOU_SURE', "&iquest;Est&aacute; seguro(a)?");

define('_DML_HELP', "Ayuda");

// -- HTML Class
define('_DML_SELECT_CAT', "Seleccione Categor&iacute;a");
define('_DML_SELECT_DOC', "Seleccione Documento");
define('_DML_SELECT_FILE', "Seleccione Archivo");
define('_DML_ALL_CATS', "- Todas las Categor&iacute;as");
define('_DML_SELECT_USER', "Seleccione Usuario");
define('_DML_GENERAL', "General");
define('_DML_GROUPS', "Grupos");
define('_DML_DOCMAN_GROUPS', "Grupos de DOCman");
define('_DML_MAMBO_GROUPS', "Grupos de Joomla");
define('_DML_JOOMLA_GROUPS', "Grupos Joomla! "); // alias
define('_DML_USERS', "Usuarios");
define('_DML_EVERYBODY', "Todo el Mundo");
define('_DML_ALL_REGISTERED', "Todos los miembros de Descargas Joomla");
define('_DML_NO_USER_ACCESS', "Sin acceso a usuarios");
define('_DML_AUTO_APPROVE', "Auto Aprovar");
define('_DML_AUTO_PUBLISH', "Auto Publicar");
define('_DML_GROUP', "Grupo");
define('_DML_GROUP_PUBLISHER', "Quien Publica");
define('_DML_GROUP_EDITOR', "Editor");
define('_DML_GROUP_AUTHOR', "Autor");

// -- File Class
define('_DML_OPTION_HTTP', "Subir un archivo desde su PC");
define('_DML_OPTION_XFER', "Transferir el archivo desde otro servidor a este servidor");
define('_DML_OPTION_LINK', "Vincular a un archivo en otro servidor");
define('_DML_SIZEEXCEEDS', "El tama&ntilde;o del archivo excede lo permitido.");
define('_DML_ONLYPARTIAL', "S&oacute se ha recibido parte del archivo. Por favor, intente de nuevo.");
define('_DML_NOUPLOADED', "No ha subido ning&uacute;n documento.");
define('_DML_TRANSFERERROR', "Ha ocurrido un error de transferencia");
define('_DML_DIRPROBLEM', "Hay un problema con el directorio, no se ha podido mover el archivo.");
define('_DML_DIRPROBLEM2', "Problema con la Carpeta");
define('_DML_COULDNOTCONNECT', "No se pudo conectar al host");
define('_DML_COULDNOTOPEN', "No se pudo abrir la carpeta de destino. Por favor, verifique los permisos.");
define('_DML_FILETYPE', "Tipo de Archivo");
define('_DML_NOTPERMITED', "No Permitido");
define('_DML_EMPTY', "Vac&iacute;o");

define('_DML_ALREADYEXISTS', "ya existe.");
define('_DML_PROTOCOL', "Protocolo");
define('_DML_NOTSUPPORTED', "no suportado.");
define('_DML_NOFILENAME', "No se especific&oacute; el nombre del archivo.");
define('_DML_FILENAME', "Nombre de Archivo");
define('_DML_CONTAINBLANKS', "contiene espacios.");
define('_DML_ISNOTVALID', "no es un nombre v&aacute;lido");
define('_DML_SELECTIMAGE', "Seleccione Imagen");
define('_DML_FAILEDTOCREATEDIR', "No se pudo crear la carpeta");
define('_DML_DIRNOTEXISTS', "La carpeta no existe, no se pueden borrar los archivos");
define('_DML_TEMPLATEEMPTY', "La id de plantilla est&aacute; vac&iacute;a, no se puede borrar los archivos");
define('_DML_INTERRORMAMBOT', "Error Interno: no mambot set");
define('_DML_INTERRORMABOT', _DML_INTERRORMAMBOT); // alias
define('_DML_NOTARGGIVEN', "no se han facilitados argumentos suficientes");
define('_DML_ARG', "argumento");
define('_DML_ISNOTARRAY', "no es un array");

define('_DML_NEW', "nuevo!");
define('_DML_HOT', "popular!");

define('_DML_BYTES', "Bytes");
define('_DML_KB', "kB");
define('_DML_MB', "MB");
define('_DML_GB', "GB");
define('_DML_TB', "TB");

// -- Form Validation
define('_DML_ENTRY_ERRORS', "Sistema de Mensaje de DOCman: Por Favor, corriga los siguientes errores:");
define('_DML_ENTRY_TITLE', "Debe incluir un i&iacute;tulo.");
define('_DML_ENTRY_NAME', "Debe incluir un nombre.");
define('_DML_ENTRY_DATE', "Debe incluir una fecha.");
define('_DML_ENTRY_OWNER', "Debe asignar a un due&ntilde;o.");
define('_DML_ENTRY_CAT', "Debe incluir una categor&iacute;a.");
define('_DML_ENTRY_DOC', "Debe incluir un documento seleccionado.");
define('_DML_ENTRY_MAINT', "Debe asignar a un mantenedor.");

define('_DML_ENTRY_DOCLINK_LINK', "El documento necesita que tenga seleccionado un ENLACE");
define('_DML_ENTRY_DOCLINK', "El documento posee tanto el enlace del archivo como el nombre del documento.");
define('_DML_ENTRY_DOCLINK_PROTOCOL', "Protocolo desconocido del enlace del documento");
define('_DML_ENTRY_DOCLINK_NAME', "Se necesita el enlace completo del documento");
define('_DML_ENTRY_DOCLINK_HOST', "Se necesita una URL completa");
define('_DML_ENTRY_DOCLINK_INVALID', "Archivo no encontrado");
define('_DML_FILENAME_REQUIRED', "Se requiere un nombre de archivo");

// Missing  constants from J!1.0.x
define('_DML_FILTER', "Filtro");
define('_DML_UPDATE', "Actualizar");
define('_DML_SEARCH_ANYWORDS', "Cualquier palabra");
define('_DML_SEARCH_ALLWORDS', "Todas las palabras");
define('_DML_SEARCH_PHRASE', "Frase exacta");
define('_DML_SEARCH_NEWEST', "Los nuevos primero");
define('_DML_SEARCH_OLDEST', "Los antiguos primero");
define('_DML_SEARCH_POPULAR', "M&aacute;s popular");
define('_DML_SEARCH_ALPHABETICAL', "Alfab&eacute;tico");
define('_DML_SEARCH_CATEGORY', "Categor&iacute;a");
define('_DML_SEARCH_MESSAGE', "Los t&eacute;minos para la busqueda deben ser de un m&iacute;nimo de 3 caracteres y un m&aacute;ximo de 20 caracteres");
define('_DML_SEARCH_TITLE', "Buscar");
define('_DML_PROMPT_KEYWORD', "Buscar Palabras");
define('_DML_SEARCH_MATCHES', "%d resultados de b&uacute;squeda");
define('_DML_NOKEYWORD', "La b&uacute;squeda no ha dado resultados");
define('_DML_IGNOREKEYWORD', "Uno o m&aacute;s palabras han sido ignoradas en la b&uacute;squeda");
define('_DML_CMN_ORDERING', "Ordenando");

// Added JoomDOC 1.4 RC3
define('_DML_HELP', "Ayuda");
