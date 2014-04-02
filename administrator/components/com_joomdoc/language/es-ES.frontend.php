<?php
/**
 * JoomDOC 1.4.x - Joomla! Document Manager
 * @version $Id: english.frontend.php 635 2008-02-28 14:09:18Z mjaz $
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

// -- General
define('_DML_NOLOG', "Se debe validar como usuario para acceder a la secci&oacute;n.");
define('_DML_NOLOG_UPLOAD', "Se debe validar como usuario y estar autorizado para subir documentos.");
define('_DML_NOLOG_DOWNLOAD', "Se debe validar como usuario y estar autorizado para descargar documentos.");
define('_DML_NOAPPROVED_DOWNLOAD', "El documento debe ser aprobado antes de ser bajado.");
define('_DML_NOPUBLISHED_DOWNLOAD', "El documento debe ser publicado antes de ser bajado.");
define('_DML_ISDOWN', "Los sentimos pero esta secci&oacute;n est&aacute; temporalmente fuera de servicio. Int&eacute;ntelo m&aacute;s tarde.");
define('_DML_SECTION_TITLE', "Descargas");

// -- Files
define('_DML_DOCLINKTO', "Documento vinculado a ");
define('_DML_DOCLINKON', "V&iacute;nculo creado el ");
define('_DML_ERROR_LINKING', "Error al conectar con el host.");
define('_DML_LINKTO', "V&iacute;nculo a ");
define('_DML_DONE', "Listo.");
define('_DML_FILE_UNAVAILABLE', "El Archivo no est&aacute; disponible en el servidor");

// -- Documents
define('_DML_TAB_BASIC', "Basico");
define('_DML_TAB_PERMISSIONS', "Permisos");
define('_DML_TAB_LICENSE', "Licencia");
define('_DML_TAB_DETAILS', "Detalles");
define('_DML_TAB_PARAMS', "Par&aacute;metros");
define('_DML_OP_CANCELED', "Operaci&oacute;n Cancelada");
define('_DML_CREATED_BY', "Creado por");
define('_DML_UPDATED_BY', "Ultima actualizaci&oacute;n por");
define('_DML_DOCMOVED', "El documento se ha movido");
define('_DML_MOVETO', "Mover a");
define('_DML_MOVETHEFILES', "Mover los archivos");
define('_DML_SELECTFILE', "Seleccione un archivo");
define('_DML_THANKSDOCMAN', "Gracias por tu env&iacute;o.Recuerda que las extensiones son revisadas &aacute;ntes de ser publicadas.");
define('_DML_NO_LICENSE', "Sin licencia");
define('_DML_DISPLAY_LIC', "Mostrar la Licencia");
define('_DML_LICENSE_TYPE', "Tipo de Licencia");
define('_DML_MANT_TOOLTIP', "Esto determina quien puede editar o mantener el documento. "
     . "Cuando un usuario o miembro del grupo es el " . _DML_MAINTAINER . " del documento se refiere a que ellos tienen ciertas opciones de permisos sobre el documento: editar, actualizar, mover, verificar y borrar.");
define('_DML_ON', "en");
define('_DML_CURRENT', "Actual");
define('_DML_YOU_MUST_UPLOAD', "Primero debe incluir un documento para esta secci&oacute;n.");
define('_DML_THE_MODULE', "El M&oacute;dulo");
define('_DML_IS_BEING', "est&aacute; siendo editado por otro administrador este momento");
define('_DML_LINKED', "->DOCUMENTO VINCULADO<-");
define('_DML_FILETITLE', "T&iacute;tulo del Documento");
define('_DML_OWNER_TOOLTIP', "Esto determina quien puede ver y bajar el documento. Elija: "
     . "*Todo el Mundo* si desea que todo el mundo tenga acceso al documento. "
     . "*Usuarios registrados* solamente los usuarios registrados accedan al documento. "
     . "Puede asignar el documento a un s&oacute;lo usuario seleccionando el nombre en " . _DML_USERS . "; "
     . "solamente ese usuario tiene todo el acceso. "
     . "Puede asignar el documento a un grupo de usuarios registrados seleccionando el grupo en " . _DML_GROUPS . "; "
     . "solamante los integrantes del grupo tendr&aacute;n acceso total al documento.");
define('_DML_MAKE_SURE', 'Asegurese de que la URL comienza <br />con "http://"');
define('_DML_DOCURL', "URL del Documento:");
define('_DML_DOCDELETED', "Documento borrado.");
define('_DML_DOCURL_TOOLTIP', "Cuando tenga documentos vinculados debe escribir la direcci&oacute;n del sitio web del documento aqu&iacute;. Siempre debe especificar el tipo de protocolo a utilizar (http:// o ftp://) al comienzo de la URL.");
define('_DML_HOMEPAGE_TOOLTIP', "Opcionalmente puede incluir una direcci&oacute;n web que tenga alguna relaci&oacute;n con el documento actual, para ello siempre debe incluir http:// al principio del v&iacute;nculo.");
define('_DML_LICENSE_TOOLTIP', "El documento puede tener unas condiciones de licencia que los visitantes deben aceptar al acceder al mismo. Aqu&iacute; puede definir el tipo de licencia.");
define('_DML_DISPLAY_LICENSE', "Mostrar condiciones/licencia al ver el documeto");
define('_DML_DISPLAY_LIC_TOOLTIP', "Seleccione *Si* si desea que la licencia se muestre antes de permitir el acceso al documento.");
define('_DML_APPROVED_TOOLTIP', "Los documentos deben ser aprovados para que sean visibles y accesibles. Eliga *Si* y no se olvide de publicar el documento, ya que ambas opciones deben estar habilitadas para permitir que el archivo est&eacute; visible y accesible desde el portal");
define('_DML_RESET_COUNTER', "Resetear el Contador");
define('_DML_PROBLEM_SAVING_DOCUMENT', "Hubo un problema grabando el archivo");

// -- Download
define('_DML_PROCEED', "Haga click aqu&iacute; para proceder");
define('_DML_YOU_MUST', "Debe aceptar las condiciones para ver el documento.");
define('_DML_NOTDOWN', "En este momento el archivo est&aacute; siendo actualizado o editado y no est&aacute; disponible.");
define('_DML_ANTILEECH_ACTIVE', "Est&aacute; intentando acceder desde un dominio no autorizado.");
define('_DML_DONT_AGREE', "No Estoy Conforme.");
define('_DML_AGREE', "Estoy Conforme.");

// -- Upload
define('_DML_UPLOADED', "Subido.");
define('_DML_SUBMIT', "Enviar");
define('_DML_NEXT', "Sig. >>>");
define('_DML_BACK', "<<< Prev.");
define('_DML_LINK', "Enlace");
define('_DML_EDITDOC', "Editar este documento");
define('_DML_UPLOADWIZARD', "Wizard de Subidas");
define('_DML_UPLOADMETHOD', "Escoja un m&eacute;todo de subida");
define('_DML_ISUPLOADING', "DOCman Cargando");
define('_DML_PLEASEWAIT', "Por Favor, espere");
define('_DML_DOCMANISLINKING', "DOCman verificando <br />el v&iacute;nculo");
define('_DML_DOCMANISTRANSF', "DOCman transfiriendo<br />el documento");
define('_DML_TRANSFER', "Transferir");
define('_DML_REMOTEURL', "URL Remota");
define('_DML_LINKURLTT', "Escriba la URL remota. La URL debe incluir tipo de servidor (http:// or ftp://) incluyendo cualquier informaci&oacute;n adicional necesaria, ejemplo: http://mamboforge.net/frs/download.php/2026/docmanV1.3.zip");
define('_DML_REMOTEURLTT', _DML_LINKURLTT . "<br />Aqu&iacute; puede utilizar cualquier nombre para el archivo utilizando el campo &quot;Nombre Local&quot;.");
define('_DML_LOCALNAME', "Nombre Local");
define('_DML_LOCALNAMETT', "Introducir el nombre local que se mostrar&aacute; en el sistema."
     . "Este es un campo obligatorio ya que la URL no facilita informaci&oacute;n suficiente para el documento.");
define('_DML_ERROR_UPLOADING', "Error de subida.");

// -- Search
define('_DML_SELECCAT', "Seleccione categor&iacute;a");
define('_DML_ALLCATS', "Todas las categor&iacute;as");
define('_DML_SEARCH_WHERE', "Buscar donde");
define('_DML_SEARCH_MODE', "Buscar por");
define('_DML_SEARCH', "Buscar");
define('_DML_SEARCH_REVRS', "Reverso");
define('_DML_SEARCH_REGEX', "Expresi&oacute;n Regular");
define('_DML_NOT', "Opuesto"); // Used for Inversion

// -- E-mail
define('_DML_EMAIL_GROUP', "Mandar E-mail a un grupo");
define('_DML_SUBJECT', "T&iacute;tulo");
define('_DML_EMAIL_LEADIN', "Texto Introductorio");
define('_DML_MESSAGE', "Mensaje Principal");
define('_DML_SEND_EMAIL', "Enviar");

//Document tasks
define('_DML_BUTTON_DOWNLOAD', "Descargar");
define('_DML_BUTTON_VIEW', "Ver");
define('_DML_BUTTON_DETAILS', "Detalles");
define('_DML_BUTTON_EDIT', "Editar");
define('_DML_BUTTON_MOVE', "Mover");
define('_DML_BUTTON_DELETE', "Borrar");
define('_DML_BUTTON_UPDATE', "Actualizar");
define('_DML_BUTTON_CHECKOUT', "Liberar");
define('_DML_BUTTON_CHECKIN', "Reservar");
define('_DML_BUTTON_UNPUBLISH', "No Publicar");
define('_DML_BUTTON_PUBLISH', "Publicar");
define('_DML_BUTTON_RESET', "Resetear");
define('_DML_BUTTON_APPROVE', "Aprobar");


// -- Added v1.4.0 RC1
define('_DML_CHECKED_IN', "Verificado");