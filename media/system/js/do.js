/**
* @version		$Id: do.js 2010-04-07 Sebastián García Truan $
* @package		DO JavaScript
* @subpackage	DO
* @autor		Diseño Objetivo - www.disenobjetivo.cl - disenobjetivo@disenobjetivo.cl
* @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
* @license		LICENCIA_DO.php
*/

/**
 * Funciones JavaScript
 *
 */

	// Objetos Principales
	var char_a = String.fromCharCode(225);
	var char_e = String.fromCharCode(233);
	var char_i = String.fromCharCode(237);
	var char_o = String.fromCharCode(243);
	var char_u = String.fromCharCode(250);
	var char_n = String.fromCharCode(241);
	var char_A = String.fromCharCode(193);
	var char_E = String.fromCharCode(201);
	var char_I = String.fromCharCode(205);
	var char_O = String.fromCharCode(211);
	var char_U = String.fromCharCode(218);
	var char_N = String.fromCharCode(209);
	var ipregunta = String.fromCharCode(191);
	var DOM = document.getElementById;
	var IE4 = document.all;
	var NN4 = document.layers;
	
	var FXdivsID	= new Array;
	var FXdivsOBJ	= new Array;
	
	
	function getObjeto( objetoid )
	{
		objeto = null;
		if(DOM){ var objeto = document.getElementById(objetoid);
		}else if(IE4){ var objeto = document.all[objetoid];
		}else if(NN4){ var objeto = document.layers[objetoid]; }
		
		return objeto;
	}      

	function form_texto_blur( obj )
	{
		if( obj.value == '' ){
			obj.value = obj.title;
		}
	}
	
	function form_texto_focus( obj )
	{
		if( obj.value == obj.title ){
			obj.value = '';
		}
	}

	function posicionObjeto(obj) {
		var curleft = 0;
		  var curtop = 0;
		  if (obj.offsetParent) {
				do {
					  curleft += obj.offsetLeft;
					  curtop += obj.offsetTop;
				} while (obj = obj.offsetParent);
		  }
		  return [curleft,curtop];
	}
	
/**
* FORM ARREGLO
*
*/
	function Form_Arreglo( hash, orden, nombre, label, file_base )
	{
		this.hash			= hash;
		this.orden			= orden;
		this.nombre			= nombre;
		this.label			= label;
		this.file_base		= file_base;
		this.valor			= null;
		
		this.agregar		= form_arreglo_agregar;
		this.agrega			= form_arreglo_agrega;
		this.archivo		= form_arreglo_archivo;
		this.eliminar		= form_arreglo_eliminar;
		this.cambiaOrden	= form_arreglo_cambiaOrden;
		this.reordena		= form_arreglo_reordena;
		this.addOrden		= form_arreglo_link_add_orden;
		this.addEliminar	= form_arreglo_link_add_eliminar;
	}
	
	
	function form_arreglo_agregar()
	{
		if( $(this.nombre) )
		{
			valor	= $(this.nombre).value;
			if( valor == '' || valor == this.label )
			{
				alert( "Debe ingresar un valor" );
				$(this.nombre).focus();
				return false;
			}
			
			if( this.hash == 'file' )
			{
				this.archivo();
			}
			else
			{
				switch( this.hash )
				{
					case 'str' :
						this.valor		= String(valor);
						this.agrega();
					break;
					case 'int' :
						if( isNaN( valor ) )
						{
							alert( "El valor debe ser un número" );
							$(this.nombre).focus();
							$(this.nombre).select();
							return false;
						}
						this.valor		= parseInt(valor);
						this.agrega();
					break;
					case 'float' :
						if( isNaN( valor ) )
						{
							alert( "El valor debe ser un número" );
							$(this.nombre).focus();
							$(this.nombre).select();
							return false;
						}
						this.valor		= parseFloat(valor);
						this.agrega();
					break;
				}
				//$(this.nombre).setAttribute('value', this.label );
				$(this.nombre).value	= this.label;
			}
		}
	}
	
	function form_arreglo_agrega()
	{
		idcontenedor	= 'do_form_arreglo_contenedor_' + this.nombre;
		idindice		= 'do_form_arreglo_indice_' + this.nombre;
		contenedor		= getObjeto( idcontenedor );
		indice			= getObjeto( idindice );
		variables		= new Array(this.hash,this.orden,this.nombre,this.label,this.file_base);
		if( contenedor && indice )
		{
			indiceArreglo	= parseInt( indice.value ) + 1;
			// se crea el div contenido
			//<div class=\"do_form_arreglo_subcontenedor\" id=\"do_form_arreglo_subcontenedor_$nombre_$indiceArreglo\">
			contenido	= document.createElement('div');
						contenido.className		= 'do_form_arreglo_subcontenedor';
						contenido.id			= 'do_form_arreglo_subcontenedor_'+this.nombre+'_'+indiceArreglo;
						
			// se crea el div texto
			//. "\t\t\t<div class=\"do_form_arreglo_texto\">" . $valor . "</div>\n"
			texto		= document.createElement('div');
						texto.className			= 'do_form_arreglo_texto';
						texto.id				= 'do_form_arreglo_texto_'+this.nombre+'_'+indiceArreglo;
						texto.appendChild( document.createTextNode( this.valor ) );
						arreglo		= document.createElement('input');
									arreglo.type	= 'hidden';
									arreglo.name	= this.nombre+'[]';
									arreglo.id		= 'do_form_arreglo_'+this.nombre+'_'+indiceArreglo;
									arreglo.value	= this.valor;
						texto.appendChild( arreglo );
						
			contenido.appendChild( texto );
						
			// se crea el div de orden
			/*
				$ordenhtml	= "\t\t\t<div class=\"do_form_arreglo_orden\" align\"center\">";
				$ordenhtml	= $indiceArreglo ? '<a html="javascript:void(0);" onclick="javascript:form_arreglo_cambiaOrden(\'' . $nombre . '\','.$indiceArreglo.','.$indiceArreglo-1.'); return false;" title="Subir">&uarr;</a>' : '';
				$ordenhtml	= $indiceArreglo && $indiceArreglo < count( $valores ) - 1 ? '&nbsp;&nbsp;':'';
				$ordenhtml	= $indiceArreglo < count( $valores ) - 1 ? '<a html="javascript:void(0);" onclick="javascript:form_arreglo_cambiaOrden(\'' . $nombre . '\','.$indiceArreglo.','.$indiceArreglo+1.'); return false;" title="Bajar">&darr;</a>' : '';
				$ordenhtml	= "</div>\n";
			*/
			
			if( this.orden == 'usuario' )
			{
				divorden= document.createElement('div');
						divorden.className			= 'do_form_arreglo_orden';
						divorden.align				= 'center';
						divorden.id					= 'do_form_arreglo_orden_'+this.nombre+'_'+indiceArreglo;
				contenido.appendChild( divorden );
			}
			
			// se crea el div eliminar
			//"\t\t\t<div class=\"do_form_arreglo_eliminar\" align\"center\"><a href=\"javascript:void(0);\" onclick=\"javascript:form_arreglo_eliminar('" . $nombre . "', " . $indiceArreglo . ")\" title=\"Eliminar\">[X]</a></div>\n"
			eliminar	= document.createElement('div');
						eliminar.className		= 'do_form_arreglo_eliminar';
						eliminar.align			= 'center';
						imgeliminarid			= 'do_form_arreglo_eliminar_' + this.nombre + '_' + indiceArreglo + '_img';
						aeliminar	= document.createElement('a');
								aeliminar.href 	= 'javascript:void(0);';
								this.addEliminar( aeliminar, indiceArreglo, imgeliminarid, 'images/do/form/eliminar1.png' );
								aeliminar.title	= 'Eliminar';
						imgeliminar	= document.createElement('img');
									imgeliminar.src		= 'images/do/form/eliminar0.png';
									imgeliminar.border	= 0;
									imgeliminar.alt		= 'eliminar';
									imgeliminar.id		= imgeliminarid;
						aeliminar.appendChild( imgeliminar );
				eliminar.appendChild( aeliminar );
			contenido.appendChild( eliminar );
			
			contenedor.appendChild( contenido );
			
			indice.value 	= indiceArreglo;
			this.reordena();
		}
	}
	
	function form_arreglo_archivo()
	{
		alert( "archivo" );
		idcontenedor			= 'do_form_arreglo_contenedor_' + this.nombre;
		
		// creamos iframe oculto
		iframe					= document.createElement('iframe');
		iframe.id				= 'do_form_arreglo_iframe_' + this.nombre;
		iframe.name				= 'do_form_arreglo_iframe_' + this.nombre;
		iframe.style.display	= 'none';
		
		$(idcontenedor).appendChild( iframe );
		
		objform					= document.adminForm;
		/*
		formato					= document.createElement('input');
		formato.type			= 'hidden';
		formato.name			= 'restriccion_formato';
		formato.value			= 'imagen';
		objform.appendChild( formato );
		
		tamano					= document.createElement('input');
		tamano.type				= 'hidden';
		tamano.name				= 'restriccion_tamano';
		tamano.value			= '100x100';
		objform.appendChild( tamano );
		*/
		action					= objform.action;
		target					= objform.target;
		objform.action			= 'index.php';
		objform.target			= iframe.name;
		objform.task.value		= 'imagen';
		
		objform.submit();
		objform.action			= action;
		objform.target			= target;
		
		objform.removeChild( formato );
		objform.removeChild( tamano );
	}
	
	function form_arreglo_eliminar( indice )
	{
		//alert(indice);
		idcontenedor	= 'do_form_arreglo_contenedor_' + this.nombre;
		idcontenido		= 'do_form_arreglo_subcontenedor_' + this.nombre + '_' + indice;
		idindice		= 'do_form_arreglo_indice_' + this.nombre;
		
		variables		= new Array(this.hash,this.orden,this.nombre,this.label,this.file_base);
		contenedor		= getObjeto( idcontenedor );
		contenido		= getObjeto( idcontenido );
		objindice		= getObjeto( idindice );
		if( contenedor && contenido && objindice )
		{
			
			indiceArreglo	= parseInt( objindice.value );
			for( i=indice; i < indiceArreglo; ++i )
			{
				this.cambiaOrden( i, 1 );
			}
			idcontenido		= 'do_form_arreglo_subcontenedor_' + this.nombre + '_' + indiceArreglo;
			contenido		= getObjeto( idcontenido );
			if( contenido )
			{
				contenedor.removeChild( contenido );
				$(idindice).value 	= indiceArreglo - 1;
			}
			
			/*
			//--horizontal
			if( typeof FXdivsID[idcontenido] != "undefined" )
			{
				var myHorizontalSlide = FXdivsID[idcontenido];
				
				divcontenido		= $(idcontenido);
				$(idcontenedor).replaceChild(FXdivsOBJ[idcontenido], $(idcontenedor).firstChild);
				divslide			= $(idcontenedor).firstChild;
				divslide.appendChild( divcontenido );
			}
			else
			{
				var myHorizontalSlide = new Fx.Slide(idcontenido, {mode: 'vertical'});
				myHorizontalSlide.addEvent('complete', function() {
					var myDO_form_arreglo = new Form_Arreglo(variables[0], variables[1], variables[2], variables[3], variables[4]);
					indiceArreglo	= parseInt( $(idindice).value );
					for( i=indice; i < indiceArreglo; ++i )
					{
						myDO_form_arreglo.cambiaOrden( i, 1 );
					}
					this.show();
					divslide	= $(idcontenido).parentNode;
					$(idcontenido).removeAttribute('style');
					FXdivsOBJ[idcontenido]	= divslide;
					$(idcontenedor).replaceChild($(idcontenido),divslide);
					idcontenido		= 'do_form_arreglo_subcontenedor_' + myDO_form_arreglo.nombre + '_' + indiceArreglo;
					$(idcontenedor).removeChild( $(idcontenido) );
					
					$(idindice).value 	= indiceArreglo - 1;
					myDO_form_arreglo.reordena();
				});
				FXdivsID[idcontenido]	= myHorizontalSlide;
			}
			myHorizontalSlide.toggle();
			*/
		}
	}
	
	function form_arreglo_cambiaOrden( indice_uno, inc )
	{
		indice_uno		= parseInt( indice_uno );
		indice_dos		= indice_uno + parseInt( inc );
		idcontenido_uno	= 'do_form_arreglo_texto_' + this.nombre + '_' + indice_uno;
		idcontenido_dos	= 'do_form_arreglo_texto_' + this.nombre + '_' + indice_dos;
		contenido_uno	= getObjeto( idcontenido_uno );
		contenido_dos	= getObjeto( idcontenido_dos );
		
		if( contenido_uno && contenido_dos )
		{
			tmp_uno		= contenido_uno.firstChild.nodeValue;
			tmp_dos		= contenido_uno.lastChild.value;
			
			contenido_uno.firstChild.nodeValue	= contenido_dos.firstChild.nodeValue;
			contenido_uno.lastChild.value		= contenido_dos.lastChild.value;
			
			contenido_dos.firstChild.nodeValue	= tmp_uno;
			contenido_dos.lastChild.value		= tmp_dos;
		}
	}
	
	function form_arreglo_reordena()
	{
		idindice		= 'do_form_arreglo_indice_' + this.nombre;
		indice			= getObjeto( idindice );
		nombre			= this.nombre;
		variables		= new Array(this.hash,this.orden,this.nombre,this.label,this.file_base);
		ies				= new Array;
		if( indice )
		{
			indiceArreglo	= parseInt( indice.value );
			for( i=0; i <= indiceArreglo; ++i ){
				ies[i]	= i;
				idorden			= 'do_form_arreglo_orden_' + this.nombre + '_' + i;
				orden			= getObjeto( idorden );
				if( orden )
				{
					// removemos todo
					while (orden.hasChildNodes()) {
						orden.removeChild(orden.firstChild);
					}
					// agregamos si corresponde
					if( i )
					{
						imgsubirid	= 'do_form_arreglo_orden_' + this.nombre + '_' + i + '_up';
						asubir	= document.createElement('a');
								asubir.href 	= 'javascript:void(0);';
								this.addOrden( asubir, imgsubirid, 'images/do/form/uparrow1.png', i, -1 );
								asubir.title	= 'Subir';
						imgsubir	= document.createElement('img');
									imgsubir.src		= 'images/do/form/uparrow0.png';
									imgsubir.border		= 0;
									imgsubir.alt		= 'subir';
									imgsubir.id			= imgsubirid;
						asubir.appendChild( imgsubir );
						orden.appendChild( asubir );
						if( i == indiceArreglo )
						{
							// transparente 16x16
							imgtransparente	= document.createElement('img');
											imgtransparente.src		= 'images/do/pix_transparente.gif';
											imgtransparente.border	= 0;
											imgtransparente.alt		= '';
											imgtransparente.width	= 16;
											imgtransparente.height	= 16;
							orden.appendChild( imgtransparente );
						}
					}
					if( i < indiceArreglo )
					{
						if( !i )
						{
							// transparente 16x16
							imgtransparente	= document.createElement('img');
											imgtransparente.src		= 'images/do/pix_transparente.gif';
											imgtransparente.border	= 0;
											imgtransparente.alt		= '';
											imgtransparente.width	= 16;
											imgtransparente.height	= 16;
							orden.appendChild( imgtransparente );
						}
						imgbajarid	= 'do_form_arreglo_orden_' + this.nombre + '_' + i + '_down';
						abajar	= document.createElement('a');
								abajar.href 	= 'javascript:void(0);';
								this.addOrden( abajar, imgbajarid, 'images/do/form/downarrow1.png', i, 1 );
								abajar.title	= 'Bajar';
						imgbajar	= document.createElement('img');
									imgbajar.src		= 'images/do/form/downarrow0.png';
									imgbajar.border		= 0;
									imgbajar.alt		= 'bajar';
									imgbajar.id			= imgbajarid;
									//imgbajar.style.cssFloat	= 'left';
						//abajar.appendChild( document.createTextNode( '&darr;' ) );
						abajar.appendChild( imgbajar );
						orden.appendChild( abajar );
					}
					if( !i && !indiceArreglo )
					{
						// transparente 16x16
						imgtransparente	= document.createElement('img');
										imgtransparente.src		= 'images/do/pix_transparente.gif';
										imgtransparente.border	= 0;
										imgtransparente.alt		= '';
										imgtransparente.width	= 32;
										imgtransparente.height	= 32;
						orden.appendChild( imgtransparente );
					}
				}
			}
		}
	}
	
	function form_arreglo_link_add_orden( objlink, imgid, imgover, i, inc )
	{
		variables		= new Array(this.hash,this.orden,this.nombre,this.label,this.file_base);
		
		if (objlink.addEventListener) {
			objlink.addEventListener('click', function(){
														var myDO_form_arreglo = new Form_Arreglo(variables[0], variables[1], variables[2], variables[3], variables[4]);
														myDO_form_arreglo.cambiaOrden(i,inc);
													},
											  false);
		} else if (objlink.attachEvent) {
			objlink.attachEvent('onclick',  function(){
														var myDO_form_arreglo = new Form_Arreglo(variables[0], variables[1], variables[2], variables[3], variables[4]);
														myDO_form_arreglo.cambiaOrden(i,inc);
												   } );
		}
		
		if (objlink.addEventListener) {
			objlink.addEventListener('mouseout', function(){ MM_swapImgRestore(); }, false);
		} else if (objlink.attachEvent) {
			objlink.attachEvent('onmouseout',  function(){ MM_swapImgRestore(); } );
		}
		
		if (objlink.addEventListener) {
			objlink.addEventListener('mouseover', function(){ MM_swapImage(imgid,'',imgover,1); }, false);
		} else if (objlink.attachEvent) {
			objlink.attachEvent('onmouseover',  function(){ MM_swapImage(imgid,'',imgover,1); } );
		}
	}
	
	function form_arreglo_link_add_eliminar( objlink, indice, imgid, imgover )
	{
		variables		= new Array(this.hash,this.orden,this.nombre,this.label,this.file_base);
		
		if (objlink.addEventListener) {
		objlink.addEventListener('click', function(){
												var myDO_form_arreglo = new Form_Arreglo(variables[0], variables[1], variables[2], variables[3], variables[4]);
												myDO_form_arreglo.eliminar(indice);
													},
										  false);
		} else if (objlink.attachEvent) {
			objlink.attachEvent('onclick',  function(){
												var myDO_form_arreglo = new Form_Arreglo(variables[0], variables[1], variables[2], variables[3], variables[4]);
												myDO_form_arreglo.eliminar(indice);
												   } );
		}
		
		if (objlink.addEventListener) {
			objlink.addEventListener('mouseout', function(){ MM_swapImgRestore(); }, false);
		} else if (objlink.attachEvent) {
			objlink.attachEvent('onmouseout',  function(){ MM_swapImgRestore(); } );
		}
		
		if (objlink.addEventListener) {
			objlink.addEventListener('mouseover', function(){ MM_swapImage(imgid,'',imgover,1); }, false);
		} else if (objlink.attachEvent) {
			objlink.attachEvent('onmouseover',  function(){ MM_swapImage(imgid,'',imgover,1); } );
		}
	}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function revisarDigito( dvr, objrut )
{	
	dv = dvr + ""	
	if ( dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4' && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9' && dv != 'k'  && dv != 'K')	
	{		
				
		objrut.focus();		
		objrut.select();		
		return false;	
	}	
	return true;
}

function revisarDigito2( crut, objrut )
{	
	largo = crut.length;	
	if ( largo < 2 )	
	{		
				
		objrut.focus();		
		objrut.select();		
		return false;
	}	
	if ( largo > 2 )		
		rut = crut.substring(0, largo - 1);	
	else		
		rut = crut.charAt(0);	
	dv = crut.charAt(largo-1);	
	revisarDigito( dv, objrut );	

	if ( rut == null || dv == null )
		return 0	

	var dvr = '0'	
	suma = 0	
	mul  = 2	

	for (i= rut.length -1 ; i >= 0; i--)	
	{	
		suma = suma + rut.charAt(i) * mul		
		if (mul == 7)			
			mul = 2		
		else    			
			mul++	
	}	
	res = suma % 11	
	if (res==1)		
		dvr = 'k'	
	else if (res==0)		
		dvr = '0'	
	else	
	{		
		dvi = 11-res		
		dvr = dvi + ""	
	}
	if ( dvr != dv.toLowerCase() )	
	{		
				
		objrut.focus();		
		objrut.select();		
		return false;	
	}

	return true
}

function Rut(texto,objrut)
{	
	var tmpstr = "";	
	for ( i=0; i < texto.length ; i++ )		
		if ( texto.charAt(i) != ' ' && texto.charAt(i) != '.' && texto.charAt(i) != '-' )
			tmpstr = tmpstr + texto.charAt(i);	
	texto = tmpstr;	
	largo = texto.length;	

	if ( largo < 2 )	
	{		
				
		objrut.focus();		
		objrut.select();		
		return false;
	}	

	for (i=0; i < largo ; i++ )	
	{			
		if ( texto.charAt(i) !="0" && texto.charAt(i) != "1" && texto.charAt(i) !="2" && texto.charAt(i) != "3" && texto.charAt(i) != "4" && texto.charAt(i) !="5" && texto.charAt(i) != "6" && texto.charAt(i) != "7" && texto.charAt(i) !="8" && texto.charAt(i) != "9" && texto.charAt(i) !="k" && texto.charAt(i) != "K" )
 		{			
						
			objrut.focus();			
			objrut.select();			
			return false;		
		}	
	}	

	var invertido = "";	
	for ( i=(largo-1),j=0; i>=0; i--,j++ )		
		invertido = invertido + texto.charAt(i);	
	var dtexto = "";	
	dtexto = dtexto + invertido.charAt(0);	
	dtexto = dtexto + '-';	
	cnt = 0;	

	for ( i=1,j=2; i<largo; i++,j++ )	
	{		
		//alert("i=[" + i + "] j=[" + j +"]" );		
		if ( cnt == 3 )		
		{			
			dtexto = dtexto + '.';			
			j++;			
			dtexto = dtexto + invertido.charAt(i);			
			cnt = 1;		
		}		
		else		
		{				
			dtexto = dtexto + invertido.charAt(i);			
			cnt++;		
		}	
	}	

	invertido = "";	
	for ( i=(dtexto.length-1),j=0; i>=0; i--,j++ )		
		invertido = invertido + dtexto.charAt(i);	

	objrut.value = invertido.toUpperCase()		

	if ( revisarDigito2(texto,objrut) )		
		return true;	

	return false;
}

function RevisarEmail(mail){
  valor = true;
  largo = mail.length;
  arroa=0;
  cont=0;
  punto=0;
  sigue=0;
  
  for(i=0;i<largo;i++){
    if(cont>0){
    	if((mail.charAt(i) == "@") && (arroa != 1)){
        	arroa=1;
        	cont=-1;
      	}//if
      		if(arroa==1){
        		if(mail.charAt(i) == "."){
        			punto=1;
        			cont=-1;
      			}//if
			}//if
    }//if
    
	if((cont>0) && (arroa ==1) && (punto == 1)){
      i=largo;
      sigue=1;
    }//if_
    	cont=cont+1;
  }//for
  
  if(sigue==0){
  	valor=false;
  }//if
  
  return valor;
  
}

//v1.7
// Flash Player Version Detection
// Detect Client Browser type
// Copyright 2005-2007 Adobe Systems Incorporated.  All rights reserved.
var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;

function ControlVersion()
{
	var version;
	var axo;
	var e;

	// NOTE : new ActiveXObject(strFoo) throws an exception if strFoo isn't in the registry

	try {
		// version will be set for 7.X or greater players
		axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
		version = axo.GetVariable("$version");
	} catch (e) {
	}

	if (!version)
	{
		try {
			// version will be set for 6.X players only
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
			
			// installed player is some revision of 6.0
			// GetVariable("$version") crashes for versions 6.0.22 through 6.0.29,
			// so we have to be careful. 
			
			// default to the first public version
			version = "WIN 6,0,21,0";

			// throws if AllowScripAccess does not exist (introduced in 6.0r47)		
			axo.AllowScriptAccess = "always";

			// safe to call for 6.0r47 or greater
			version = axo.GetVariable("$version");

		} catch (e) {
		}
	}

	if (!version)
	{
		try {
			// version will be set for 4.X or 5.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.3");
			version = axo.GetVariable("$version");
		} catch (e) {
		}
	}

	if (!version)
	{
		try {
			// version will be set for 3.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.3");
			version = "WIN 3,0,18,0";
		} catch (e) {
		}
	}

	if (!version)
	{
		try {
			// version will be set for 2.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
			version = "WIN 2,0,0,11";
		} catch (e) {
			version = -1;
		}
	}
	
	return version;
}

// JavaScript helper required to detect Flash Player PlugIn version information
function GetSwfVer(){
	// NS/Opera version >= 3 check for Flash plugin in plugin array
	var flashVer = -1;
	
	if (navigator.plugins != null && navigator.plugins.length > 0) {
		if (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]) {
			var swVer2 = navigator.plugins["Shockwave Flash 2.0"] ? " 2.0" : "";
			var flashDescription = navigator.plugins["Shockwave Flash" + swVer2].description;
			var descArray = flashDescription.split(" ");
			var tempArrayMajor = descArray[2].split(".");			
			var versionMajor = tempArrayMajor[0];
			var versionMinor = tempArrayMajor[1];
			var versionRevision = descArray[3];
			if (versionRevision == "") {
				versionRevision = descArray[4];
			}
			if (versionRevision[0] == "d") {
				versionRevision = versionRevision.substring(1);
			} else if (versionRevision[0] == "r") {
				versionRevision = versionRevision.substring(1);
				if (versionRevision.indexOf("d") > 0) {
					versionRevision = versionRevision.substring(0, versionRevision.indexOf("d"));
				}
			}
			var flashVer = versionMajor + "." + versionMinor + "." + versionRevision;
		}
	}
	// MSN/WebTV 2.6 supports Flash 4
	else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.6") != -1) flashVer = 4;
	// WebTV 2.5 supports Flash 3
	else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.5") != -1) flashVer = 3;
	// older WebTV supports Flash 2
	else if (navigator.userAgent.toLowerCase().indexOf("webtv") != -1) flashVer = 2;
	else if ( isIE && isWin && !isOpera ) {
		flashVer = ControlVersion();
	}	
	return flashVer;
}

// When called with reqMajorVer, reqMinorVer, reqRevision returns true if that version or greater is available
function DetectFlashVer(reqMajorVer, reqMinorVer, reqRevision)
{
	versionStr = GetSwfVer();
	if (versionStr == -1 ) {
		return false;
	} else if (versionStr != 0) {
		if(isIE && isWin && !isOpera) {
			// Given "WIN 2,0,0,11"
			tempArray         = versionStr.split(" "); 	// ["WIN", "2,0,0,11"]
			tempString        = tempArray[1];			// "2,0,0,11"
			versionArray      = tempString.split(",");	// ['2', '0', '0', '11']
		} else {
			versionArray      = versionStr.split(".");
		}
		var versionMajor      = versionArray[0];
		var versionMinor      = versionArray[1];
		var versionRevision   = versionArray[2];

        	// is the major.revision >= requested major.revision AND the minor version >= requested minor
		if (versionMajor > parseFloat(reqMajorVer)) {
			return true;
		} else if (versionMajor == parseFloat(reqMajorVer)) {
			if (versionMinor > parseFloat(reqMinorVer))
				return true;
			else if (versionMinor == parseFloat(reqMinorVer)) {
				if (versionRevision >= parseFloat(reqRevision))
					return true;
			}
		}
		return false;
	}
}

function AC_AddExtension(src, ext)
{
  if (src.indexOf('?') != -1)
    return src.replace(/\?/, ext+'?'); 
  else
    return src + ext;
}

function AC_Generateobj(objAttrs, params, embedAttrs) 
{ 
  var str = '';
  if (isIE && isWin && !isOpera)
  {
    str += '<object ';
    for (var i in objAttrs)
    {
      str += i + '="' + objAttrs[i] + '" ';
    }
    str += '>';
    for (var i in params)
    {
      str += '<param name="' + i + '" value="' + params[i] + '" /> ';
    }
    str += '</object>';
  }
  else
  {
    str += '<embed ';
    for (var i in embedAttrs)
    {
      str += i + '="' + embedAttrs[i] + '" ';
    }
    str += '> </embed>';
  }

  document.write(str);
}

function AC_FL_RunContent(){
  var ret = 
    AC_GetArgs
    (  arguments, ".swf", "movie", "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
     , "application/x-shockwave-flash"
    );
  AC_Generateobj(ret.objAttrs, ret.params, ret.embedAttrs);
}

function AC_SW_RunContent(){
  var ret = 
    AC_GetArgs
    (  arguments, ".dcr", "src", "clsid:166B1BCA-3F9C-11CF-8075-444553540000"
     , null
    );
  AC_Generateobj(ret.objAttrs, ret.params, ret.embedAttrs);
}

function AC_GetArgs(args, ext, srcParamName, classid, mimeType){
  var ret = new Object();
  ret.embedAttrs = new Object();
  ret.params = new Object();
  ret.objAttrs = new Object();
  for (var i=0; i < args.length; i=i+2){
    var currArg = args[i].toLowerCase();    

    switch (currArg){	
      case "classid":
        break;
      case "pluginspage":
        ret.embedAttrs[args[i]] = args[i+1];
        break;
      case "src":
      case "movie":	
        args[i+1] = AC_AddExtension(args[i+1], ext);
        ret.embedAttrs["src"] = args[i+1];
        ret.params[srcParamName] = args[i+1];
        break;
      case "onafterupdate":
      case "onbeforeupdate":
      case "onblur":
      case "oncellchange":
      case "onclick":
      case "ondblClick":
      case "ondrag":
      case "ondragend":
      case "ondragenter":
      case "ondragleave":
      case "ondragover":
      case "ondrop":
      case "onfinish":
      case "onfocus":
      case "onhelp":
      case "onmousedown":
      case "onmouseup":
      case "onmouseover":
      case "onmousemove":
      case "onmouseout":
      case "onkeypress":
      case "onkeydown":
      case "onkeyup":
      case "onload":
      case "onlosecapture":
      case "onpropertychange":
      case "onreadystatechange":
      case "onrowsdelete":
      case "onrowenter":
      case "onrowexit":
      case "onrowsinserted":
      case "onstart":
      case "onscroll":
      case "onbeforeeditfocus":
      case "onactivate":
      case "onbeforedeactivate":
      case "ondeactivate":
      case "type":
      case "codebase":
      case "id":
        ret.objAttrs[args[i]] = args[i+1];
        break;
      case "width":
      case "height":
      case "align":
      case "vspace": 
      case "hspace":
      case "class":
      case "title":
      case "accesskey":
      case "name":
      case "tabindex":
        ret.embedAttrs[args[i]] = ret.objAttrs[args[i]] = args[i+1];
        break;
      default:
        ret.embedAttrs[args[i]] = ret.params[args[i]] = args[i+1];
    }
  }
  ret.objAttrs["classid"] = classid;
  if (mimeType) ret.embedAttrs["type"] = mimeType;
  return ret;
}
