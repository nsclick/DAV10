<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: english.backend.php 649 2008-03-18 17:34:42Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
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

// -- Toolbar
define('_DML_TOOLBAR_SAVE', "Guardar");
define('_DML_TOOLBAR_CANCEL', "Cancelar");
define('_DML_TOOLBAR_NEW', "Nuevo");
define('_DML_TOOLBAR_NEW_DOC', "Nuevo Documento");
define('_DML_TOOLBAR_HOME', "Inicio");
define('_DML_TOOLBAR_UPLOAD', "Subir");
define('_DML_TOOLBAR_MOVE', "Mover");
define('_DML_TOOLBAR_COPY', "Copiar");
define('_DML_TOOLBAR_SEND', "Enviar");
define('_DML_TOOLBAR_BACK', "Atr&aacute;s");
define('_DML_TOOLBAR_PUBLISH', "Publicar");
define('_DML_TOOLBAR_UNPUBLISH', "No Publicar");
define('_DML_TOOLBAR_DEFAULT', "Predeterm.");
define('_DML_TOOLBAR_DELETE', "Borrar");
define('_DML_TOOLBAR_CLEAR', "Limpiar");
define('_DML_TOOLBAR_EDIT', "Editar");
define('_DML_TOOLBAR_EDIT_CSS', "Editar CSS");
define('_DML_TOOLBAR_APPLY', "Aplicar");

// -- Files
define('_DML_ORPHANS', "Hu&eacute;rfanos");
define('_DML_ORPHANS_LINKED', "Archivo(s) no borrado. No se puede borrar el archivo(s) vinculado al(los) documento(s).");
define('_DML_ORPHANS_PROBLEM', "Archivo(s) no borrado(s). Existe un problema con los permisos.");
define('_DML_ORPHANS_DELETED', "Archivo(s) borrado(s).");
define('_DML_LINKS', "V&iacute;nculos");
define('_DML_NEXT', "Sig.");
define('_DML_SUCCESS', "&iexcl;Exito!");
define('_DML_UPLOADMORE', "Subir m&aacute;s");
define('_DML_UPLOADWIZARD', "Subir con wizard");
define('_DML_UPLOADMETHOD', "Escoja M&eacute;todo de subida");
define('_DML_ISUPLOADING', "DOCman est&aacute; Cargando");
define('_DML_PLEASEWAIT', "Espere");
define('_DML_UPLOADDISK', "Wizard - Subir archivo desde su disco duro");
define('_DML_FILETOUPLOAD', "Escoja el archivo a subir");
define('_DML_BATCHMODE', "Modo de paquetes");
define('_DML_BATCHMODETT', "Con el modo por paquetes puede subir un archivo comprimido ZIP con varios archivos. El paquete ser&aacute; descomprimido autom&aacute;ticamente tras la subida al sistema. No incluya carpetas o sub-carpetas en el archivo ZIP, tenga en cuenta que durante el proceso se podr&iacute;a sobre escribir los archivos y carpetas existentes en DOCman si estos tienen el mismo nombre, actualmente no existe una protecci&oacute;n para la carga de archivo ZIP. Est&aacute; en versi&oacute;n experimental y se debe utilizar con precauci&oacute;n");

define('_DML_DOCMANISTRANSF', "DOCman est&aacute; transfiriendo<br />the file");
define('_DML_TRANSFERFROMWEB', _DML_UPLOADWIZARD . " - " . "transferir un archivo desde servidor web");
define('_DML_REMOTEURL', "URL Remota");
define('_DML_LINKURLTT', "Escriba la URL remota. La URL debe incluir tipo de servidor (http:// or ftp://) incluyendo cualquier informaci&oacute;n adicional necesaria, ejemplo: http://mamboforge.net/frs/download.php/2026/docmanV1.3.zip");
define('_DML_REMOTEURLTT', _DML_LINKURLTT . "<br />Aqu&iacute; puede utilizar cualquier nombre para el archivo utilizando el cambo &quot;Nombre Local&quot;.");
define('_DML_LOCALNAME', "Nombre Local");
define('_DML_LOCALNAMETT', "Introducir el nombre local que se mostrar&aacute; en el sistema."
     . "Este es un campo obligatorio ya que la URL no proporciona informaci&oacute;n suficiente para nombrar al documento.");
define('_DML_DOCUPDATED', "El documento ha sido actualizado.");
define('_DML_FILEUPLOADED', "El archivo a sido cargado.");
define('_DML_MAKENEWENTRY', "Crear una nueva entrada de documento utilizando este archivo.");
define('_DML_DISPLAYFILES', "Mostrar Archivos.");
define('_DML_ALLFILES', "Todos los Archivos");
define('_DML_DOCFILES', "Archivos de Documentos");
define('_DML_CREATEALINK', "Crear un Documento V&iacute;culado");
define('_DML_SELECTMETHODFIRST', "Por favor, especifique un m&eacute;todo de tranferencia");
define('_DML_ERROR_UPLOADING', "Error cargando.");
define('_DML_ZLIB_ERROR', "La operaci&oacute;n ha fallado ya que la librer&iacute;a ZLIB no est&aacute; presente en php.ini");
define('_DML_UNZIP_ERROR', "No se pudo descomprimir el archivo.");
define('_DML_SUBMIT', "Enviar");
define('_DML_NEW_FILE', "Archivo Nuevo");
define('_DML_MAKE_SELECTION', "Por favor, haga su selecci�n desde la lista.");

// -- Documents
define('_DML_MOVECAT', "Mover Categor&iacute;a");
define('_DML_MOVETOCAT', "Mover a Categor&iacute;a");
define('_DML_DOCSMOVED', "El Documento se est&aacute; moviendo");
define('_DML_COPYCAT', "Copiar Categor&iacute;a");
define('_DML_COPYTOCAT', "Copiar a Categor&iacute;a");
define('_DML_COPY_OF', "Copia de"); // Copia de [nombre del documento]
define('_DML_DOCSCOPIED', "Documentos siendo copiados");
define('_DML_DOCS_NOT_APPROVED', "documentos no aprobado");
define('_DML_DOCS_NOT_PUBLISHED', "documentos no publicados");
define('_DML_NO_PENDING_DOCS', "No hay documentos pendientes.");
define('_DML_FILE_MISSING', "***falta el archivo***");
define('_DML_YOU_MUST_UPLOAD', "Primero debe cargar un documento en esta secci&oacute;n.");
define('_DML_THE_MODULE', "El m&oacute;dulo");
define('_DML_IS_BEING', "esta&aacute; siendo editado por otro administrador en este momento");
define('_DML_NO_LICENSE', "sin licencia");
define('_DML_LINKED', "->DOCUMENTO VINCULADO<-");
define('_DML_CURRENT', "Actual");
define('_DML_LICENSE_TYPE', "Tipo de Licencia");
define('_DML_FILETITLE', "T&iacute;tulo del Archivo");
define('_DML_OWNER_TOOLTIP', "Esto determina quien puede ver y bajar el documento. Elija: "
     . "*Todo el Mundo* si desea que todo el mundo tenga acceso al documento. "
     . "*Usuarios registrados* solamente los usuarios registrados accedan al documento. "
     . "Puede asignar el documento a un s&oacute;lo usuario seleccionando el nombre en " . _DML_USERS . "; "
     . "solamente ese usuario tiene todo el acceso. "
     . "Puede asignar el documento a un grupo de usuarios registrados seleccionando el grupo en " . _DML_GROUPS . "; "
     . "solamente los integrantes del grupo tendr&aacute;n acceso total al documento.");
define('_MANT_TOOLTIP', "Este determina quien posee acceso a editar o mantener el documento. "
     . "Cuando un usuario o miembro del grupo es " . _DML_MAINTAINER . " de un documento, significa que, dichos usuarios pueden realizar tareas espec&iacute;ficas de mantenimiento sobre dicho docuemnto como: editar, actualizar, mover, aprobar y borrar.");
define('_DML_MAKE_SURE', 'Asegurese de que la URL comienza <br />con "http://"');
define('_DML_DOCURL', "URL del Documento:");
define('_DML_DOCURL_TOOLTIP', "Cuando tenga documentos vinculados debe escribir la direcci&oacute;n del sitio web del documento aqu&iacute;. Siempre debe especificar el tipo de protocolo a utilizar (http:// o ftp://) al comienzo de la URL.");
define('_DML_HOMEPAGE_TOOLTIP', "Opcionalmente puede incluir una direcci&oacute;n web que tenga alguna relaci&oacute;n con el documento actual, para ello siempre debe incluir http:// al principio del enlace.");
define('_DML_LICENSE_TOOLTIP', "El documento puede tener unas condiciones de licencia que los visitantes deben aceptar al acceder al mismo. Aqu&iacute; puede definir el tipo de licencia.");
define('_DML_DISPLAY_LICENSE', "Mostrar condiciones/licencia al ver el documeto");
define('_DML_DISPLAY_LIC_TOOLTIP', "Seleccione *Si* si desea que la licencia se muestre antes de permitir el acceso al documento.");
define('_DML_APPROVED_TOOLTIP', "Los documentos deben ser aprobados para que sean visibles y accesible. Eliga *Si* y no se olvide de publicar el documento, ya que ambas opciones deben estar habilitadas para permitir que el archivo est&eacute; visible y accesible desde el portal");
define('_DML_PLEASE_SEL_CAT', "Por Favor, defina al menos una categor&iacute;a primero");
define('_DML_MANT_TOOLTIP', "Esta determina quien puede editar y mantener el documento. "
     . "Cuando un usuario o miembro del grupo es " . _DML_MAINTAINER . " de un documento, significa que, dichos usuarios pueden realizar tareas espec&iacute;ficas de mantenimiento sobre dicho docuemnto como: editar, actualizar, mover, aprobar y borrar.");
define('_DML_DISPLAY_LIC', "Mostrar Licencia");

define('_DML_TAB_PERMISSIONS', "Permisos");
define('_DML_TAB_LICENSE', "Licencia");
define('_DML_TAB_DETAILS', "Detalles");
define('_DML_TAB_PARAMS', "Par&aacute;metros");

define('_DML_TITLE_DOCINFORMATION', "Informaci&oacute;n del Documento");
define('_DML_TITLE_DOCPERMISSIONS', "Permisos del Documento");
define('_DML_TITLE_DOCLICENSES', "Licencias del Documento");
define('_DML_TITLE_DOCDETAILS', "Detalles del Documento");
define('_DML_TITLE_DOCPARAMETERS', "Par&aacute;metros del Documento");

define('_DML_CREATED_BY', "Creado por");
define('_DML_UPDATED_BY', "Actualizado por");
define('_DML_SELECT_ITEM_DEL', "Seleccione un elemento a borrar");
define('_DML_SELECT_ITEM_MOVE', "Seleccione un elemento a mover");
define('_DML_SELECT_ITEM_COPY', "Seleccione un elemento a copiar");
define('_STATUS_YOU', "Este documento fu&eacute; aprobado por usted.");
define('_STATUS_NOT_OUT', "Este documento no est&aacute; aprobado.");
define('_DML_NEW_DOCUMENT', "Documento Nuevo");
define('_DML_DOCUMENTS_MOVED_TO', "Documentos movidos a"); // [Number of] Documents moved to [location]
define('_DML_DOCUMENTS_COPIED_TO', "Documentos copiados a"); // [Number of] Documents moved to [location]


// -- Categories
define('_DML_CATDETAILS', "Detalles de Categor&iacute;a");
define('_DML_CATTITLE', "T&iacute;ntulo de Categor&iacute;a");
define('_DML_CATNAME', "Nombre de Categor&iacute;a");
define('_DML_LONGNAME', "Nombre largo a mostrar en la cabecera");
define('_DML_PARENTITEM', "Elemento Pariente");
define('_DML_IMAGE', "Imagen");
define('_DML_PREVIEW', "Vista Previa");
define('_DML_IMAGEPOS', "Posici&oacute;n de Imagen");
define('_DML_ORDERING', "Orden");
define('_DML_ACCESSLEVEL', "Nivel de Acceso");
define('_DML_CREATEMENUITEM', "Esto crear&aacute; un enlace de men&uacute; desde el men&uacute; que seleccione.");
define('_DML_SELECTMENU', "Seleccione un Men&uacute;");
define('_DML_SELECTMENUTYPE', "Seleccione un Tipo de Men&uacute;");
define('_DML_MENUITEMNAME', "Nombre del Elemento de Men&uacute;");
define('_DML_SELECTCATTO', "Seleccione a Categor&iacute;a");
define('_DML_SELECTCATTODELETE', "Seleccione la categor&iacute;a a borrar");
define('_DML_REORDER', "Orden");
define('_DML_ACCESS', "Acceso");
define('_DML_CAT_MUST_SELECT_NAME', "La categor&iacute;a debe tener un nombre");
define('_DML_CATS_CANT_BE_REMOVED', "no puede ser removida. Tiene documentos asociados o sub-categor&iacute;as");

// -- Groups
define('_DML_TITLE_GROUPS', "Grupos");
define('_DML_CANNOT_DEL_GROUP', "No se puede borrar el grupo en este momento ya que tiene archivos que le pertenecen.");
define('_DML_USERS_AVAILABLE', "Usuarios Disponibles");
define('_DML_MEMBERS_IN_GROUP', "Miembros en este grupo");
define('_DML_ADD_GROUP_TIP', "Haga doble-click en el nombre o seleccione y presione la flecha para a&ntilde;adir/quitar un usuario como miembro del grupo."
     . "Para seleccionar m&aacute;s de un usuario a la vez, " . _DML_MULTIPLE_SELECTS);
define('_DML_ADDING_USERS', "A&ntilde;adiendo usuarios a miembros del grupo");
define('_DML_FILL_FORM', "Por Favor, rellene el formulario correctamente");
define('_DML_ONLY_ADMIN_EMAIL', "&iexcl;Solamente un Super Administrador puede enviar mensajes masivos!");
define('_DML_NO_TARGET_EMAIL', "No hay usuarios con Emails v&aacute;lidos en el grupo");
define('_DML_THIS_IS', "Este este un mensaje de e-mail de");
define('_DML_SENT_BY', "enviado por DOCman a los miembros de del grupo de documentos");
define('_DML_EMAIL_SENT_TO', "E-mail enviado a");
define('_DML_MEMBERS', "Miembros");
define('_DML_EMAIL', "E-mail");
define('_DML_USER_BLOCKED', "bloqueado");

// -- Licenses
define('_DML_LICENSE_TEXT', "Texto de Licencia");
define('_DML_CANNOT_DEL_LICENSE', "No se puede borrar la licencia ya que alg&uacute;n documento lo est&aacute; utilizando.");

// -- Config
define('_DML_FRONTEND', "Portal");
define('_DML_PERMISSIONS', "Permisos");
define('_DML_RESETDEFAULT', "Resetear a pre-definido");
define('_DML_ASCENDENT', "Ascendiente");
define('_DML_DESCENDENT', "Descendiente");

define('_DML_CONFIGURATION', "Configuraci&oacute;n de DOCman");
define('_DML_CONFIG_UPDATED', "Los datos Configuraci&oactue;n han sido actualizados.");
define('_DML_CONFIG_WARNING', "CUIDADO: La Configuraci&oactue;n se ha actualizado pero el l&iacute;mite m&aacute;ximo de subidas (Upload-Max) es mayor que el configurado en PHP: ");
define('_DML_CONFIG_ERROR', "Ha ocurrido un error: &iexcl;No se puede abrir el archivo de Configuraci&oactue;n para escribir!");
define('_DML_CONFIG_ERROR_UPLOAD', "ERROR: El l&iacute;mite m&aacute;ximo de subidas (Upload-Max) no puede ser negativo.");

define('_DML_CFG_DOCMANTT', "ayuda DOCman...");
define('_DML_CFG_ALLOWBLANKS', "Permitir espacios");
define('_DML_CFG_REJECT', "Denegar");
define('_DML_CFG_CONVERTUNDER', "Convertir en l&iacute;neas");
define('_DML_CFG_CONVERTDASH', "Convertir en guiones");
define('_DML_CFG_REMOVEBLANKS', "Quitar espacios");
define('_DML_CFG_PATHFORSTORING', "Ruta para los archivos");
define('_DML_CFG_PATHTT', "En este apartado debe definir la ruta del directorio local donde se guardan los archivos. Debe incluir la ruta absoluta. Puede acceptar la ruta predeterminada o si lo prefiere puede indicar otra.<br /><br />"
     . "Por Ejemplo, en Linux ser&iacute;a algo como /var/usr/www/dmdocuments<br /><br />"
     . "Si usa Windows , puede ser, por ejemplo, c:/inetpub/www/dmdocuments");
define('_DML_CFG_SECTIONISDOWN', "&iquest;Apagar la Secci&oacute;n?");
define('_DML_CFG_SECTIONTT', "Si desea detener el acceso de los usuarios a los documentos cambie esta opci&oacute;n a *Si*. <br />"
     . "Esta opci&oacute;n nos facilita realizar pruebas al actualizar DOCman.<br /><br />"
     . "Administradores y usuarios especiales siempre tendr&aacute;n acceso aunque seleccione *No* en la opci&oacute;n. <br />"
    );
define('_DML_CFG_NUMBEROFDOCS', "N&uacute;mero de documentos por p&aacute;gina");
define('_DML_CFG_NUMBERTT', "N&uacute;mero de documentos a mostrar en una p&aacute;gina. Si el total de documentos es mayor que la cantidad elegida se mostrar&aacute;n los elementos de paginaci&oacute;n autom&aacute;ticamente.");

define('_DML_CFG_GUEST', "Invitados");
define('_DML_CFG_GUEST_NO', "No Invitados");
define('_DML_CFG_GUEST_X', "S&oacute;lo Mostrar");
define('_DML_CFG_GUEST_RX', "Mostrar, Ver y  Bajar");
define('_DML_CFG_GUEST_TT', "Aqu&iacute; decide lo que los visitantes no registrados (Invitados) pueden hacer: <br />*"
     . _DML_CFG_GUEST_NO . "* Los documentos no son visibles<br />*"
     . _DML_CFG_GUEST_X . "* Pueden ver los documentos exitentes pero no pueden acceder. <br />*"
     . _DML_CFG_GUEST_RX . "* Les permite ver y acceder al documento."
     . "<br /><br />Este permiso es adicional a los permisos de un usuario sobre los documentos."
     . "</span>");

define('_DML_CFG_AUTHOR_NONE', "Sin Acceso");
define('_DML_CFG_AUTHOR_READ', "S&oacute;lo Bajar");
define('_DML_CFG_AUTHOR_BOTH', "Bajar y Editar");

define('_DML_CFG_ICONSIZE', "Set de Iconos");
define('_DML_CFG_DAYSFORNEW', "D&iacute;as como Nuevo");
define('_DML_CFG_DAYSFORNEWTT', "Indique el n&uacute;mero de D&iacute;as que el documento aparece como documento nuevo. Se mostrar&aacute; con la etiqueta *" . _DML_NEW . "* junto al nombre del documento al mostrar el listado. Si indica 0 D&iacute;as, no se mostrar&aacute; la etiqueta.");
define('_DML_CFG_HOT', "Descargas populares");
define('_DML_CFG_HOTTT', "Puede indicar el n&uacute;mero de veces que un documento debe ser bajado antes de convertirse en popular. Se mostrar&aacute; con la etiqueta *" . _DML_HOT . "* cerca del nombre del documento al mostrar el listado. Si indica 0, no se mostrar&aacute; la etiqueta..");
define('_DML_CFG_DISPLAYLICENSES', "&iquest;Mostrar Licencias?");

define('_DML_CFG_VIEW', "Ver");
define('_DML_CFG_VIEWTT', "Le permite decidir el usuario o grupo predeterminado que puede ver y descargar los documentos. Esta opci&oacute;n puede ser sobreescrita con la cofiguraci&oacute;n en los permisos de un documento en particular.");
define('_DML_CFG_MAINTAIN', "Mantenedor");
define('_DML_CFG_MAINTAINTT', "Le permite decidir el usuario o grupo predeterminado realiza el mantenimiento de los documentos. Esta opci&oacute;n puede ser sobreescrita con la cofiguraci&oacute;n en los permisos de un documento en particular.");
define('_DML_CFG_CREATORS_PERM', "Creadores Pueden");
define('_DML_CFG_CREATORSPERMTT', "Aqu&iacute; Decide lo que los creadores pueden hacer.<br /><br />"
     . "Estos permisos son adicionales a los permitidos por los visitantes y mantenedores configurados en cada documento.");
define('_DML_CFG_WHOCANAREADER', "Descarga");
define('_DML_CFG_WHOCANAREADERTT', "Puede seleccionar si un creador o mantenedor puede decidir quien puede ver el documento.<br /><br />"
     . "**** Los administradores siempre tienen permisos para ver el documento.");
define('_DML_CFG_WHOCANAEDITOR', "Editar");
define('_DML_CFG_WHOCANAEDITORTT', "Puede seleccionar si un creador o mantenedor puede decidir quien puede mantener el documento.<br /><br />"
     . "**** Los administradores siempre pueden selccionar al mantenedor.");

define('_DML_CFG_EMAILGROUP', "&iquest;E-mail al grupo de usuarios?");
define('_DML_CFG_EMAILGROUPTT', "Si selecciona *Si*, se mostrar&aacute; un enlace en aquellos documentos cuyo propietario sea un grupo, para que un usuario puede enviar un email a los usuarios de dicho grupo.");

define('_DML_CFG_UPLOAD', "Subidas");
define('_DML_CFG_UPLOADTT', "Selecciona que usuario o grupo puede subir archivos. Este controla todos los m&eacute;todos de subidas: http, enlace y transferencia");
define('_DML_CFG_APPROVE', "Aprobar");
define('_DML_CFG_APPROVETT', "Le permite selccionar al ususario o grupo que puede aprobar documentos.<br />Los documentos deben estar aprobados y publicados para que sean accesible");
define('_DML_CFG_PUBLISH', "Publicar");
define('_DML_CFG_PUBLISHTT', "Le permite selccionar al ususario o grupo que puede publicar documentos.<br />Los documentos deben estar aprobados y publicados para que sean accesible.");
define('_DML_CFG_USER_UPLOAD', "Seleccione Quien Puede Subir");
define('_DML_CFG_USER_APPROVE', "Seleccione Quien Puede Aprobar");
define('_DML_CFG_USER_PUBLISH', "Seleccione Quien Puede Publicar");

define('_DML_CFG_EXTALLOWED', "Extensiones permitidas");
define('_DML_CFG_EXTALLOWEDTT', "Extensiones permitidas, separadas por |. Desde el panel de control se puede subir cualquier tipo de archivo.");
define('_DML_CFG_MAXFILESIZE', "Tama&ntilde;o M&aacute;ximo permitido. Subida");
define('_DML_CFG_MAXFILESIZETT', "Tama&ntilde;o M&aacute;ximo permitido para las subidas de archivos desde el portal. Puede usar K/M/G para determinar el Tama&ntilde;o.<br />Este no limita el Tama&ntilde;o de los archivos subidos desde el panel de control (admin). <br /><hr />Tambi&eacute;n existe, upload_max_filesize, configurado en su en PHP que actualmente es ");
define('_DML_CFG_USERCANUPLOAD', "&iquest;Puede el usuario puede subir cualquier tipo de archivo?");
define('_DML_CFG_USERCANUPLOADTT', "Si selecciona *Si* y *Subida de Usuarios* es *Si*, los usuarios registrados pueden subir cualquier tipo de archivos, las restricciones anteriores ser&aacute;n ignoradas.");
define('_DML_CFG_OVERWRITEFILES', "&iquest;Sobreescribir Archivos??");
define('_DML_CFG_OVERWRITEFILESTT', "Si el nombre de archivo existe, este ser&aacute; reemplazado con el nuevo archivo.");
define('_DML_CFG_LOWERCASE', "&iquest;Nombre en min&uacute;sculas?");
define('_DML_CFG_LOWERCASETT', "Con la opci&oacute;n *Si* los nombres de archivos se convertir&aacute;n a min&uacute;sculas, ejemplo &nbsp;SourArchivo.TXT se convierte en  suarchivo.txt<br />");
define('_DML_CFG_FILENAMEBLANKS', "Nombre de archivo con espacios");
define('_DML_CFG_FILENAMEBLANKSTT', "Uso de archivos con nombre que contienen espacios:<br />"
     . "*Permitir Espacios* los guarda con los espacios.<br />"
     . "*Rechazar* no permite grabar el archivo.<br /><br />"
     . "Tambi&eacute puede convertir los espacios en l&iacute;neas (_), o guiones (-) o quitar los espacios.");
define('_DML_CFG_REJECTFILENAMES', "Rechazar nombres de archivos");
define('_DML_CFG_REJECTFILENAMESTT', "Introduzca un listado de nombres de archivos no permitidos a subir al sistema, cada nombre debe estar separados por (|). Estos suelen ser nombres de archivos que podr&iacute;an comprometer la seguridad de su sistema. <br />Puede utilizar expesiones regulares entre los simbolos | para filtrar s&iacute;mbolos que problem&aacute;ticos como (* $ ?)");
define('_DML_CFG_UPMETHODS', "&iquest;M&eacute;todo de Subida?");
define('_DML_CFG_UPMETHODSTT', "Seleccione todos los M&eacute;todos que los usarios pueden usar. Para  seleccionar varios, " . _DML_MULTIPLE_SELECTS);

define('_DML_CFG_ANTILEECH', "&iquest;Sistema Anti-leech?");
define('_DML_CFG_ANTILEECHTT', "El sistema anti-leech rechaza las conexiones no autorizadas a sus documentos. "
     . "Si se selecciona *Si* el sistema verfica si la petici&oacute;n descargar/ver "
     . "(desde HTTP referido) se ha originado desde un host de la lista de hosts permitidos, si no est&aacute; en dicha lista se prohibir&aacute; el acceso."
     . "Esto previene el beneficiarce de su repositorio de archivos.<br /><br />"
     . "NOTA. DocMAN soporta enlaces directos entre sistemas. "
     . "Si utiliza enlaces para sus archivos, asegurese de que su host est&aacute; en la lista de hosts autorizados en el sitio remoto."
    );
define('_DML_CFG_ALLOWEDHOSTS', "Hosts Permitidos");
define('_DML_CFG_ALLOWEDHOSTSTT', "Lista de hosts permitidos cuanto el sistema anti-leech est&aacute; activado. Para incluir varios hosts, incluya los nombres de los mismo separados por (|).<br />El valor predeterminado suele ser seguro.");

define('_DML_CFG_LOG', "&iquest;Logs de vistas?");
define('_DML_CFG_LOGTT', "Este registra las ip remotas, fecha y hora del documento visto. "
     . "Este registro de logs puede generar demasiada informaci&oacute;n en su base de datos.<hr />"
     . "Existen Mambots adicionales para mayor capacidad de logs.");

define('_DML_CFG_UPDATESERVER', "Actualizar Servidor");
define('_DML_CFG_UPDATESERVERTT', "DOCman se puede actualizar automatic&aacute;mente desde la web, Tambi&eacute;n puede cargar nuevos m&oacute;dulos, plugins y bots. Incluso puede crear cambios en a base de tados en el acto miestras se &iexcl;actualiza! Es este espacio puede especificar la URL del servidor de actualizaci&oacute;n de DOCman. A no ser que la direcci&oacute;n haya cambiado d&eacute;jela como est&aacute;.");
define('_DML_CFG_DEFAULTLISTING', "Orden del listado predeterminado");
define('_DML_CFG_TRIMWHITESPACE', "Cortar espacios en blanco");
define('_DML_CFG_TRIMWHITESPACETT', "Cortar espacios al comienzo y l&iacute;neas en blanco en la salida de la plantilla, se ahorra espacio y ancho de banda.");

define('_DML_CFG_ERR_DOCPATH', 'Pesta&ntilde;a [' . _DML_GENERAL . '] \'' . _DML_CFG_PATHFORSTORING . '\' debe ser indicada.');
define('_DML_CFG_ERR_PERPAGE', 'Pesta&ntilde;a [' . _DML_FRONTEND . '] \'' . _DML_CFG_NUMBEROFDOCS . '\' debe ser un valor mayor de 0');
define('_DML_CFG_ERR_NEW', 'Pesta&ntilde;a [' . _DML_FRONTEND . '] \'' . _DML_CFG_DAYSFORNEW . '\' debe ser un valor n&uacute;merico de 0 o mayor');
define('_DML_CFG_ERR_HOT', 'Pesta&ntilde;a [' . _DML_FRONTEND . '] \'' . _DML_CFG_HOT . '\' debe ser un valor n&uacute;merico de 0 o mayor');
define('_DML_CFG_ERR_UPLOAD', 'Pesta&ntilde;a [' . _DML_PERMISSIONS . '] \'' . _DML_CFG_UPLOAD . '\': Seleccione quien puede subir documentos.');
define('_DML_CFG_ERR_APPROVE', 'Pesta&ntilde;a [' . _DML_PERMISSIONS . '] \'' . _DML_CFG_APPROVE . '\': Seleccione quien puede aprobar documentos.');
define('_DML_CFG_ERR_DOWNLOAD', 'Pesta&ntilde;a [' . _DML_PERMISSIONS . '] \'' . _DML_CFG_VIEW . '\': Seleccione un usuario/grupo predeterminado.');
define('_DML_CFG_ERR_EDIT', 'Pesta&ntilde;a [' . _DML_PERMISSIONS . '] \'' . _DML_CFG_MAINTAIN . '\': Seleccione un usuario/grupo predeterminado para el mantenimiento.');
define('_DML_CFG_EXTENSIONSVIEWING', "Extensiones Visibles");
define('_DML_CFG_EXTENSIONSVIEWINGTT', "Los tipos de archivos que por su extensi&oacute;n pueden ser vistos. Deje el balor vac&iacute;o para ninguno, incluya * para todos o use (|) para varios typos como:(txt|pdf).");

define('_DML_CFG_GENERALSET', "Valores Generales");
define('_DML_CFG_THEMES', "Plantillas");
define('_DML_CFG_EXTRADOCINFO', "Information Extra de Documento");
define('_DML_CFG_GUESTPERM', "Permisos Invitados");
define('_DML_CFG_FRONTPERM', "Permisos Front-End");
define('_DML_CFG_DOCPERM', "Permisos Documentos");
define('_DML_CFG_OVERRIDEVIEW', "Sobreescribir Vista");
define('_DML_CFG_OVERRIDEMANT', "Sobreescribir Mantener");
define('_DML_CFG_CREATORPERM', "Permisos Creador");
define('_DML_CFG_FILEXTENSIONS', "Extensiones de Archivos");
define('_DML_CFG_FILENAMES', "Nombres de Archivos");

define('_DML_CFG_PROCESS_BOTS', "Procesar Mambots de Contenido?");
define('_DML_CFG_PROCESS_BOTSTT', "Aplica los Mambots de Contenido en el documento o en la descripci&oacute;n de las Categor&iacute;as. Esto le permite usar {tags} en tus descripciones. *Advertencia* No todos los Mambots son compatibles con esta opci&oacute;n.");
define('_DML_CFG_INDIVIDUAL_PERM', "Permitir Permisos Individuales por Usuario");
define('_DML_CFG_INDIVIDUAL_PERMTT', "Cuando apaga esta opci&oacute;n, Ud. podr&aacute; asignar permisos a un grupo, pero no a usuarios individualmente. Sus permisos asignados actualmente a sus documentos no cambiar&aacute;n, pero cuando edite un documento que fu&eacute; asignado a un usuario individual, Ud. tendr&aacute; que seleccionar un grupo a cambio. Apague esta opci&oacute; para mejorar el funcionamiento y el uso de la memoria cuando tenga bases de datos muy grandes. ");
define('_DML_CFG_HIDE_REMOTE', "Ocultar enlaces remotos"); 
define('_DML_CFG_HIDE_REMOTETT', "Esta opci&oacute;n oculta los v&iacute;nculos de archivos remotos en el modo de vista detallada del documento. Los usuarios con permisos edici&oacute;n podr&aacute;n ver el v&iacute;nculo. *NOTA* Esto NO garantiza la protecci&oacute;n total para los v&iacute;nculos remotos, los usuarios tienen la posibilidad de descubrir donde se encuentra el archivo remoto.");

// -- Statistics
define('_DML_STATS', "Estad&iacute;sticas");
define('_DML_DOCSTATS', "Estad&iacute;sticas DOCman - Los 50 m&aacute;s bajados");
define('_DML_RANK', "Posici&oacute;");

// -- Logs
define('_DML_DOWNLOAD_LOGS', "Logs de Descargas");
define('_DML_IP', "IP");
define('_DML_BROWSER', "Navegador");
define('_DML_OS', "Sistema Operativo");
define('_DML_ANONYMOUS', "An&oacute;nimos");

// -- Updates
define('_DML_UPGRADE', "Actualizar");
define('_DML_YOU_HAVE_VERSION', "usted tiene la versi&oacute;n");
define('_DML_UPTODATE', "Su versi&oacute;n est&aacute; actualizada.");
define('_DML_NO_UP_AVAIL', "No hay actualizaciones en este momento.");
define('_DML_COULD_NOT_COPY', "No se copiaron todos los archivos. Verifique los permisos. Detenido en el archivo");
define('_DML_UPDATING_DB', "Actualizando base se datos...");
define('_DML_DELETING_OLD', "Borrando archivos antiguos...");
define('_DML_ERROR_DELETING_OLD', "Error al borrar archivos. No es error cr&iacute;tico.");
define('_DML_PACKAGE', "Paquete");
define('_DML_INST_CLICK', "instalado. Click");
define('_DML_HERE', "aqu&iacute;");
define('_DML_TO_CONT', "para continuar");
define('_DML_ERROR_READING', "error leyendo");
define('_DML_XML_ERROR', "archivo XML no v&aacute;lido");
define('_DML_CHECKING_UP', "Verificando actualizaciones");
define('_DML_RELEASED_ON', "Lanzado el");

// -- Themes
define('_DML_THEMES', "Plantillas");
define('_DML_EDIT_DEFAULT_THEME', "Editar Plantilla Actual");
define('_DML_THEME_INSTALLED', "Iconos de Plantillas instaladas.");
define('_DML_ADJUST_CONFIG', "Ajustar configuraci&oacute;n.");
define('_DML_NEED_ZLIB', "El instalador no puede continuar hasta que el zlib est&eacute; instalado");
define('_DML_INSTALLER_ERROR', "Error del instalador");
define('_DML_SUCCESFULLY_INSTALLED', "Instalaci&oacute;n Exitosa");
define('_DML_ENABLE_FILE_UPLOADS', "Debe autorizar subida de archivos para continuar");
define('_DML_UPLOAD_ERROR', "Error en Subida");
define('_DML_EXTRACT_FAILED', "Extracci&oacute;n Fallida");
define('_DML_INSTALL_FAILED', "Instalaci&oacute;n Fallida");
define('_DML_UNINSTALL_FAILED', "Desinstalaci&oacute;n Fallida");
define('_DML_INSTALL_FROM_DIRECTORY', "Instalar desde el Directorio");
define('_DML_INSTALL_DIRECTORY', "Directorio de Instalaci&oacute;n");
define('_DML_PACKAGE_FILE', "Paquete de Archivos");
define('_DML_UPLOAD_PACKAGE_FILE', "Subir Paquete de Archivos");
define('_DML_UPLOAD_AND_INSTALL', "Subir Archivo e Instalar");
define('_DML_INSTALL_THEME', "Instalar Plantilla");
define('_DML_SELECT_DIRECTORY', "Por favor seleccione un directorio");
define('_DML_SELECT_PACKAGE', "Por favor seleccione un paquete");
define('_DML_STYLESHEET_EDITOR', "Editor de CSS de Platilla");
define('_DML_OPFAILED_NO_TEMPLATE', _DML_OPERATION_FAILED.": No especific&oacute; plantilla");
define('_DML_OPFAILED_CONTENT_EMPTY', _DML_OPERATION_FAILED.": El contenido est&aacute; vac&iacute;o");
define('_DML_OPFAILED_UNWRITABLE', _DML_OPERATION_FAILED.": El archivo est&aacute; protegido (not writable)");
define('_DML_OPFAILED_CANT_OPEN_FILE', _DML_OPERATION_FAILED.": No se pudo abrir el archivo para escribir");
define('_DML_OPFAILED_COULDNT_OPEN', _DML_OPERATION_FAILED.": No se pudo abrir");
define('_DML_AUTHOR_URL', "URL del autor" );
define('_DML_AUTHOR', "Autor" );
define('_DML_INSTALLED_THEMES', "Plantillas Instaladas");
define('_DML_THEME_DETAILS', "Detalles de Plantilla");
define('_DML_EDIT_THEME', "Editar Plantilla");

// -- E-mail
define('_DML_EMAIL_GROUP', "Enviar E-mail a un Grupo");
define('_DML_SUBJECT', "T&iacute;tulo");
define('_DML_EMAIL_LEADIN', "Texto introductorio");
define('_DML_MESSAGE', "Mensaje Principal");
define('_DML_SEND_EMAIL', "Enviar");

// -- Credits
define('_DML_CREDITS', "Cr&eacute;ditos" );
define('_DML_APPLICATION', "Aplicaci&oacute;n");
define('_DML_ICONS', "Iconos");
define('_DML_ICONS_PERMISSION', "Iconos usados con permiso de" );
define('_DML_CHANGELOG', "Changelog");

// -- Clear Data
define('_DML_CLEARDATA', "Borrar Datos" );
define('_DML_CLEARDATA_CLEARED', "Datos Borrados " );
define('_DML_CLEARDATA_FAILED', "Falla en Borrado de Datos : " );
define('_DML_CLEARDATA_ITEM', "Elemento" );
define('_DML_CLEARDATA_CLEAR', "Borrar" );
define('_DML_CLEARDATA_CATS_CONTAIN_DOCS', "Borrar Documentos antes de borrar Categor&iacute;as");
define('_DML_CLEARDATA_DELETE_DOCS_FIRST', "Borrar Documentos antes de borrar Archivos");

// -- Sample data
define('_DML_SAMPLE_CATEGORY', "Categor&iacute;a de Muestra" );
define('_DML_SAMPLE_CATEGORY_DESC', "Ud. puede borrar esta Categor&iacute;a de Muestra" );
define('_DML_SAMPLE_DOC', "Documento de Muestra" );
define('_DML_SAMPLE_DOC_DESC', "Ud. puede borrar este Documento de Muestra y el archivo al cual est&aacute; vinculado." );
define('_DML_SAMPLE_FILENAME', "sample_file.png" );
define('_DML_SAMPLE_COMPLETED', "Se finaliz&oacute; la importaci&oacute;n de Datos de Muestra." );
define('_DML_SAMPLE_GROUP', "Grupo Muestra" );
define('_DML_SAMPLE_GROUP_DESC', "Ud. puede usar grupos para asignar permisos a un grupo de usuarios." );
define('_DML_SAMPLE_LICENSE', "Licencia de Muestra" );
define('_DML_SAMPLE_LICENSE_DESC', "Ud. puede asignar Licencias a documentos (opcional)." );

// -- Added v1.4.0 RC1
define('_DML_CFG_COMPAT', "Compatibilidad" );
define('_DML_CFG_SPECIALCOMPATMODE', "Modo &quot;Especial&quot; de compatibilidad" );
define('_DML_CFG_SPECIALCOMPATMODETT', "En modo compatible &quot;Special&quot; DOCman 1.3 los usuarios son Managers, Adminitradores y Super Administradores. En modo Joomla!, este tambi&eacute;n incluye Autores, Publicadores y Editores.");
define('_DML_CFG_SPECIALCOMPAT_DM13', "DOCman 1.3" ); 
define('_DML_CFG_SPECIALCOMPAT_J10', "Joomla!" );

// -- Migration, From DOCman 2
define('_DM_MGR_OLD_INSTALL_NO_EXISTS','La migracion no ha sido satisfactoria.Docman probablemente no este instalado en el sistema.No se pudo encontrar el archivo de configuracion.');
define('_DM_MGR_NEW_DMPATH_NOT_SET','La migracion no ha sido exitosa.Debe de establecer la ruta de acceso hacia el archivo de configuracion de JoomDOC.');
define('_DM_MGR_FATAL_ERROR','Migracion no exitosa.');
define('_DM_MGR_TABLES_NO_EXISTS','Las Tablas de migracion no existen: ');
define('_DML_MIGRATION','Migracion');
define('_DM_MGR_CONFIRM','Estas seguro de realizar la migración de datos desde DOCman a JoomDOC ?');
define('_DM_MGR_ERR_LOAD_OLD_LICENCES','Problema durante la carga de las antiguas licencias');
define('_DM_MGR_ERR_LOAD_OLD_GROUPS','Problema durante la carga de los atiguos grupos');
define('_DM_MGR_ERR_LOAD_OLD_LOGS','Problema durante la carga de logs anteriores');
define('_DM_MGR_ERR_LOAD_OLD_HISTORY','Problema durante la carga de la antigua historia');
define('_DM_MGR_ERR_LOAD_OLD_DOCUMENTS','Problema durante la carga de los antiguos documentos');
define('_DM_MGR_ERR_LOAD_OLD_CATEGORIES','Problema durante la carga de las antiguas categorias');
define('_DM_MGR_SUCCESS','Migrado satisfactoriamente');
//copy files
define('_DM_MGR_DIR_NO_EXISTS','El directorio no existe: ');
define('_DM_MGR_DIR_IS_UNWRITEABLE','El directorio es inescribible : ');
define('_DM_MGR_UNABLE_COPY','Imposible copiar los archivos: ');
define('_DM_MGR_DIR_NO_READABLE','El directorio con los archivos antiguos no es legible: ');
define('_DM_MGR_DIR_NO_EXISTS','El directorio con los archivos antiguos no existe: ');
define('_DM_MGR_UNABLE_FIND_OLD_CONF','No se puede encontrar el directorio antiguo con los archivos de configuracion de: ');
define('_DM_MGR_UNABLE_READ_OLD_CONF','No se puede leer el archivo de configuracion viejo: ');
define('_DM_MGR_UNABLE_FIND_OLD_CONF_FILE','No se puede encontrar el archivo de configuracion viejo: ');