// JavaScript Document

	/*window.addEvent('domready', function()
	{
		cdisplay		= 'none';
		//var modChat = new Fx.Slide('mod_chat_lista');
		$('mod_chat_toggle').addEvent('click', function()
		{
			//modChat.toggle();
			$('mod_chat_lista').setStyle('display', cdisplay=='none'?'block':'none');
			cdisplay	= cdisplay=='none'?'block':'none';
			
			pos = $(this).getPosition();*/
			//top = parseInt(pos['x']);
			//left = parseInt(pos['y']);
			
			//alert(pos['x'] + " " + pos['y']);
			//$('mod_chat_lista').setStyle('top',top+'px');
			//$('mod_chat_lista').setStyle('left',left+'px');
			//$('mod_chat_lista').setStyle('top',top);
			//$('mod_chat_lista').setStyle('left',left);
			
			//$('mod_chat_lista').setPosition(pos);
			
			/*pos = posicionObjeto(getObjeto('mod_chat_toggle'));
			//alert(pos[0]+" "+pos[1]);
			//top = parseInt(pos[1]) - 330;
			top = parseInt(pos[1]) - 315;
			left = parseInt(pos[0]) - 80;
			$('mod_chat_lista').setStyle('top',top+'px');
			$('mod_chat_lista').setStyle('left',left+'px');
			*/
		//});
		//modChat.hide();
	//});
	
	jQuery(document).ready(function()
	{
		jQuery("#mod_chat_listado li").hover(function(){
			jQuery(this).css({'background-color': '#EEE'});					   
		}, function(){
			jQuery(this).css({'background-color': '#FFF'});
		});
		
		pos = jQuery('#mod_chat_toggle').offset();
		if (jQuery.browser.msie )
		{
			if( jQuery.browser.version.substr(0,1)<8 )
			{
				jQuery('#mod_chat_lista').css({'top': parseInt(pos["top"])-300, 'left': parseInt(pos["left"])-80 });
			}else
			{
				jQuery('#mod_chat_lista').css({'top': parseInt(pos["top"])-330, 'left': parseInt(pos["left"])-80 });
			}
		}else
		{
			jQuery('#mod_chat_lista').css({'top': parseInt(pos["top"])-320, 'left': parseInt(pos["left"])-80 });
		}
		
		jQuery('#mod_chat_toggle').toggle(function() {
			jQuery("#mod_chat_lista").show("slow");
		}, function() {
		 	jQuery("#mod_chat_lista").hide("slow");
		});
	});

	var ajaxChat_Conexion	= false;			// Variable que manipula la conexion.
	var ajaxChat_Servidor	= "index.php";		// Determina la pagina donde buscar
	var ajaxChat_ID			= "";				//Determina la ultima palabra buscada.

	// funcion que realiza la conexion con el objeto XMLHTTP...
	function chat_ajax_conectar()
	{
		if(window.XMLHttpRequest)
			ajaxChat_Conexion=new XMLHttpRequest(); //mozilla
		else if(window.ActiveXObject)
			ajaxChat_Conexion=new ActiveXObject("Microsoft.XMLHTTP"); //microsoft
	}

	function chat_ajax_contenido()
	{
		/* readyState devuelve el estado de la conexion. puede valer:
		 *	0- No inicializado (Es el valor inicial de readyState)
		 *	1- Abierto (El método "open" ha tenido éxito)
		 *	2- Enviado (Se ha completado la solicitud pero ningun dato ha sido recibido todavía)
		 *	3- Recibiendo
		 *	4- Respuesta completa (Todos los datos han sido recibidos)
		 */
	
		// En espera del valor 4
		if(ajaxChat_Conexion.readyState!=4) return;
		/* status: contiene un codigo enviado por el servidor
		 *	200-Completado con éxito
		 *	404-No se encontró URL
		 *	414-Los valores pasados por GET superan los 512
		 * statusText: contiene el texto del estado
		 */
		 
		if(ajaxChat_Conexion.status==200) // Si conexion HTTP es buena !!!
		{
			//si recibimos algun valor a mostrar...
			if(ajaxChat_Conexion.responseText)
			{
				/* Modificamos el identificador temp con el valor recibido por la consulta
				*	Podemos recibir diferentes tipos de datos:
				*	responseText-Datos devueltos por el servidor en formato cadena
				*	responseXML-Datos devueltos por el servidor en forma de documento XML
				*/
				//document.getElementById('siac_img_check_user').src = Conexion.responseText;
				//siac_ajax_consulta_rut_check(parseInt(conRut_Conexion.responseText));
				divid		='mod_chat_listado';
				elemento 	= getObjeto( divid );
				if(	elemento != null ){
					elemento.innerHTML = ajaxChat_Conexion.responseText;
				}
				
			}
		}
	
		ajaxChat_Conexion=false;
	}

	function chat_ajax_comprobar(id)
	{
		// si no recibimos cadena, no hacemos nada.
		// Cadena=la cadena a buscar en la base de datos
		/* Si cadena es igual a Palabra, no se realiza la busqueda. Puede ser que pulsen la tecla tabulador,
		 * y no interesa que vuelva a verificar...*/
		//if(id && id!=ajaxChat_ID)
		//{
			// Si ya esta conectado, cancela la solicitud en espera de que termine
			if(ajaxChat_Conexion) return; // Previene uso repetido del boton.
			
			// Realiza la conexion
			chat_ajax_conectar();
			
			// Si la conexion es correcta...
			if(ajaxChat_Conexion)
			{
				/*
				divid		='usuarios_en_linea';
				elemento 	= getObject( divid );
				if(	elemento != null ){
					bibliotecaContenido	= elemento.innerHTML;
					pos = posicionObjeto( elemento );
					curleft = parseInt(pos[0]) + 60;
					curtop = parseInt(pos[1]) + 6;
					bibliotecaNuevoContenido = '<img src="images/cargando.gif" alt="" style="position:absolute; top:'+curtop+'px; left:'+curleft+'px; z-index:100;" /><div style="filter: alpha(opacity=20); filter: progid:DXImageTransform.Microsoft.Alpha(opacity=20); -moz-opacity: 0.20; opacity:0.2; margin:0px; padding:0px;">'+bibliotecaContenido+'</div>';
					elemento.innerHTML = bibliotecaNuevoContenido;
				}
				*/
				
				// Esta variable, se utiliza para igualar con la cadena a buscar.
				ajaxChat_ID=id;
	
				/* Preparamos una conexion con el servidor:
				*	POST|GET - determina como se envian los datos al servidor
				*	true - No sincronizado. Ello significa que la página WEB no es interferida en su funcionamiento
				*	por la respuesta del servidor. El usuario puede continuar usando la página mientras el servidor
				*	retorna una respuesta que la actualizará, usualmente, en forma parcial.
				*	false - Sincronizado */
				ajaxChat_Conexion.open("POST",ajaxChat_Servidor,true);
	
				// Añade un par etiqueta/valor a la cabecera HTTP a enviar. Si no lo colocamos, no se pasan los parametros.
				ajaxChat_Conexion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		
				// Cada vez que el estado de la conexión (readyState) cambie se ejecutara el contenido de esta "funcion()"
				ajaxChat_Conexion.onreadystatechange=function()
				{
					chat_ajax_contenido();
				}
				
				/* Realiza la solicitud al servidor. Puede enviar una cadena de caracteres, o un objeto del tipo XML
				 * Si no deseamos enviar ningun valor, enviariamos null */
				cadenapost = "option=com_do&c=personas&task=chat&tmpl=componente&query="+ajaxChat_ID;
				
				date=new Date();
				cadenapost += "&"+date.getTime();
				ajaxChat_Conexion.send(cadenapost);
			}
		//}
	}

	function modChatQuery( objtxt )
	{
		if(ajaxChat_Conexion!=false)
		{
			//si esta en medio de una conexion, la cancelamos
			ajaxChat_Conexion.abort();
			ajaxChat_Conexion=false;
		}
		if( objtxt.value != ajaxChat_ID && objtxt.value != objtxt.title )
		{
			chat_ajax_comprobar( objtxt.value );
		}
	}