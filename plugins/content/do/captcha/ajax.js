
	/**************************************/
	/*                                    */
	/*          Diseno Objetivo           */
	/*      Fono: (56-02) 769 54 46       */
	/*     http://www.disenobjetivo.cl    */
	/*   disenobjetivo@disenobjetivo.cl   */
	/*                                    */
	/**************************************/

var cntCaptcha_Conexion=false; // Variable que manipula la conexion.
//var cntCaptcha_Servidor="plugins/content/dicoex/captcha/ajax.php"; // Determina la pagina donde buscar
var cntCaptcha_Servidor="plugins/content/dicoex/captcha/ajax.php"; // Determina la pagina donde buscar

// funcion que realiza la conexion con el objeto XMLHTTP...
function plugin_do_captcha_conectar()
{
	if(window.XMLHttpRequest)
		cntCaptcha_Conexion=new XMLHttpRequest(); //mozilla
	else if(window.ActiveXObject)
		cntCaptcha_Conexion=new ActiveXObject("Microsoft.XMLHTTP"); //microsoft
}

function plugin_do_captcha_contenido()
{
	/* readyState devuelve el estado de la conexion. puede valer:
	 *	0- No inicializado (Es el valor inicial de readyState)
	 *	1- Abierto (El método "open" ha tenido éxito)
	 *	2- Enviado (Se ha completado la solicitud pero ningun dato ha sido recibido todavía)
	 *	3- Recibiendo
	 *	4- Respuesta completa (Todos los datos han sido recibidos)
	 */
	//alert(cntCaptcha_Conexion.readyState);
	// En espera del valor 4
	if(cntCaptcha_Conexion.readyState!=4) return;
	/* status: contiene un codigo enviado por el servidor
	 *	200-Completado con éxito
	 *	404-No se encontró URL
	 *	414-Los valores pasados por GET superan los 512
	 * statusText: contiene el texto del estado
	 */
	 alert(window.location);
	 alert(cntCaptcha_Conexion.statusText);
	if(cntCaptcha_Conexion.status==200) // Si conexion HTTP es buena !!!
	{
		//si recibimos algun valor a mostrar...
		if(cntCaptcha_Conexion.responseText)
		{
			/* Modificamos el identificador temp con el valor recibido por la consulta
			*	Podemos recibir diferentes tipos de datos:
			*	responseText-Datos devueltos por el servidor en formato cadena
			*	responseXML-Datos devueltos por el servidor en forma de documento XML
			*/
			alert("aki");
			elemento = null;
			if(DOM){ var elemento = document.getElementById('contenedorCatchap');
			}else if(IE4){ var elemento = document.all['contenedorCatchap'];
			}else if(NN4){ var elemento = document.layers['contenedorCatchap']; }
			if(	elemento != null ){
				elemento.innerHTML = cntCaptcha_Conexion.responseText;
			}
			
		}
	}

	cntCaptcha_Conexion=false;
}

function plugin_do_captcha_comprobar()
{
	// si no recibimos cadena, no hacemos nada.
	// Cadena=la cadena a buscar en la base de datos
	/* Si cadena es igual a Palabra, no se realiza la busqueda. Puede ser que pulsen la tecla tabulador,
	 * y no interesa que vuelva a verificar...*/
	// Si ya esta conectado, cancela la solicitud en espera de que termine
	if(cntCaptcha_Conexion) return; // Previene uso repetido del boton.
	
	// Realiza la conexion
	plugin_do_captcha_conectar();
	
	// Si la conexion es correcta...
	if(cntCaptcha_Conexion)
	{
		/* Preparamos una conexion con el servidor:
		*	POST|GET - determina como se envian los datos al servidor
		*	true - No sincronizado. Ello significa que la página WEB no es interferida en su funcionamiento
		*	por la respuesta del servidor. El usuario puede continuar usando la página mientras el servidor
		*	retorna una respuesta que la actualizará, usualmente, en forma parcial.
		*	false - Sincronizado */
		cntCaptcha_Conexion.open("POST",cntCaptcha_Servidor,true);

		// Añade un par etiqueta/valor a la cabecera HTTP a enviar. Si no lo colocamos, no se pasan los parametros.
		cntCaptcha_Conexion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		// Cada vez que el estado de la conexión (readyState) cambie se ejecutara el contenido de esta "funcion()"
		cntCaptcha_Conexion.onreadystatechange=function()
		{
			plugin_do_captcha_contenido();
		}
		
		date=new Date();
		/* Realiza la solicitud al servidor. Puede enviar una cadena de caracteres, o un objeto del tipo XML
		 * Si no deseamos enviar ningun valor, enviariamos null */
		cntCaptcha_Conexion.send("&"+date.getTime());
	}
}

// Funcion que inicia la busqueda.
// Tiene que recibir el identificador donde mostrar el listado, y la cadena a buscar
function plugin_do_captcha()
{
	if(cntCaptcha_Conexion!=false)
	{
		//si esta en medio de una conexion, la cancelamos
		cntCaptcha_Conexion.abort();
		cntCaptcha_Conexion=false;
	}
	plugin_do_captcha_comprobar();
}