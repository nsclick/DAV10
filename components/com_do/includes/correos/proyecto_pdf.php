<?php
	global $mosConfig_live_site, $mosConfig_absolute_path, $id, $database, $mosConfig_mailfrom, $mosConfig_fromname;
	
	/* Clase de Objetos */	
	require($mosConfig_absolute_path."/includes/ezpdf/clase_ezpdf.php");
	
	function setearColor( &$pdf, $r, $g, $b ){
		$pdf->setColor((hexdec($r))/255,(hexdec($g))/255,(hexdec($b))/255);
		$pdf->setStrokeColor((hexdec($r))/255,(hexdec($g))/255,(hexdec($b))/255);
	}
	
	$representante = strval( mosGetParam( $_REQUEST, 'representante', '' ) );
	$titulo = strval( mosGetParam( $_REQUEST, 'titulo', '' ) );
	$fecha = date("Y-m-d");
	
	$query = "INSERT INTO #__dx_fondo"
	. "\n (representante, titulo, fecha)"
	. "\n VALUES ('$representante', '$titulo', '$fecha')"
	;
	$database->setQuery( $query );
	$database->query();
	
	$folio = mysql_insert_id();

	$textos = array();
	
	$textos[] = "<b>CERTIFICADO DE POSTULACIÓN</b>";
	$textos[] = "AL FONDO CONCURSABLE DICOEX PARA LAS ASOCIACIONES CHILENAS EN EL EXTERIOR";
	$textos[] = "Estimado(a) <b>".$representante."</b>,";
	$textos[] = "la postulación de su proyecto <b>".$titulo."</b>,";
	$textos[] = "se ha llevado a cabo correctamente y tiene el siguiente folio: <b>".$folio."</b>.";
	//$textos[] = "y tiene el siguiente folio: <b>".$folio."</b>.";
	$textos[] = "Para continuar con el proceso, usted debe imprimir este certificado y entregarlo en la Embajada o Consulado";
	$textos[] = "de Chile de su circunscripción,  junto a los siguientes documentos que se señalan en las bases:";
	$textos[] = "Hecho este trámite, la Embajada o Consulado remitirá los documentos a Dicoex,";
	$textos[] = "quien al recibirlos, dará curso a su postulación.";
	$textos[] = "Gracias por participar.";
	$textos[] = "<b>DIRECCIÓN PARA LA COMUNIDAD DE CHILENOS EN EL EXTERIOR</b>";
	
	$anexos = array();
	$anexos[] = "- Certificado de Personalidad Jurídica";
	$anexos[] = "- Lista individualizada de los miembros de la Asociación";
	$anexos[] = "- Fotocopia del Documento de Identidad del Representante Legal";
	$anexos[] = "- Justificación detallada del Aporte Local";
	$anexos[] = "- Antecedentes de experiencias de la Asociación";
	$anexos[] = "- Declaración Jurada del Presidente de la Asociación";

	/* PDF */
		/*  Configuraciones Inciailes del script */
		error_reporting(E_ALL);
		ini_set("memory_limit","16M");
		set_time_limit(1800);
		$paginaAncho = 612;
		$paginaAlto = 500;
		$mTop = 80;			//margen top
		$mRight = 30;		//margen right
		$mBottom = 80;		//margen bottom
		$mLeft = 30;		//margen left

		$sizePagina = array(0,0,$paginaAncho,$paginaAlto);
		
		$pdf = new Cezpdf($sizePagina,'portrait');
		
		//$pdf -> ezSetMargins($mTop,$mBottom,$mLeft,$mRight);		// Margenes del pdf
		$pdf->selectFont($mosConfig_absolute_path."/includes/ezpdf/fuentes/Helvetica.afm");			// Fuente del pdf

		$certificado = $pdf->openObject();
		$pdf->saveState();
			setearColor( $pdf, "00", "00", "00" );
			$ancho = $paginaAncho - ( $mLeft - 10 ) - ( $mRight - 10 );
			$alto = $paginaAlto - ( $mTop - 10 ) - ( $mBottom - 10 );
			$pdf->filledRectangle($mLeft-10,$mTop-10,$ancho,$alto);
			setearColor( $pdf, "FF", "FF", "FF" );
			$ancho = $paginaAncho - ( $mLeft - 5 ) - ( $mRight - 5 );
			$alto = $paginaAlto - ( $mTop - 5 ) - ( $mBottom - 5 );
			$pdf->filledRectangle($mLeft-5,$mTop-5,$ancho,$alto);

			setearColor( $pdf, "00", "00", "00" );
			
			$yPos = $paginaAlto - $mTop - 20;
			$pdf->addText($mRight,$yPos,12,$textos[0]);
			
			$yPos -= 11;
			$pdf->addText($mRight,$yPos,10,$textos[1]);
			
			$yPos -= 25;
			$pdf->addText($mRight,$yPos,10,$textos[2]);
			
			$yPos -= 25;
			$pdf->addText($mRight,$yPos,10,$textos[3]);
			
			$yPos -= 11;
			$pdf->addText($mRight,$yPos,10,$textos[4]);
			
			$yPos -= 25;
			$pdf->addText($mRight,$yPos,10,$textos[5]);
			$yPos -= 11;
			$pdf->addText($mRight,$yPos,10,$textos[6]);
			$yPos -= 10;
			foreach( $anexos as $anexo ){
				$yPos -= 15;
				$pdf->addText($mRight+15,$yPos,10,$anexo);
			}

			$yPos -= 20;
			$pdf->addText($mRight,$yPos,10,$textos[7]);
			$yPos -= 11;
			$pdf->addText($mRight,$yPos,10,$textos[8]);

			$yPos -= 30;
			$pdf->addText($mRight,$yPos,10,$textos[9]);
			
			$yPos -= 30;
			$pdf->addText($mRight,$yPos,11,$textos[10]);
			//Logo
			$img = $mosConfig_absolute_path."/templates/dicoex/images/timbre.jpg";
			if (file_exists($img)){
				$tmp=getimagesize($img);
				$imgAncho=$tmp[0];
				$imgAlto=$tmp[1];
				//$yPos -= $imgAlto + 10;
				$xPos = $mLeft + $pdf->getTextWidth(11,$textos[10]) + 10;
			  $pdf->addJpegFromFile($img,$xPos,$yPos,$imgAncho,$imgAlto);
			}

		$pdf->restoreState();
		$pdf->closeObject();
		$pdf->addObject($certificado,'add');
		
		/*
		// Se crea el archivo
		$pdfcode = $pdf->ezOutput();
		$tmppdf=$mosConfig_absolute_path . "/components/com_content/certificados/".$folio.".pdf";
		$tmppdflink=$mosConfig_live_site . "/components/com_content/certificados/".$folio.".pdf";
		$fp=fopen($tmppdf,'wb');
		fwrite($fp,$pdfcode);
		fclose($fp);
		chmod($tmppdf, 0644);
		
		// Se crea el PDF
		$opciones = array();
		$opciones['Content-Disposition'] = "$folio.pdf";
		$pdf->ezStream($opciones);
		*/
		
		$pdfcode = $pdf->ezOutput();
		$nombrearchivo = 'DICOEX_Certificado_Proyecto_#' . $folio;
		$nombre_temp = tempnam("/tmp", "$nombrearchivo.pdf");
		$gestor = fopen($nombre_temp, "wb");
		fwrite($gestor, $pdfcode);
		fclose($gestor);
		
		$email = strval( mosGetParam( $_REQUEST, 'email', '' ) );
		
		$recipiente		= array();
		$recipiente[]	= $email;
		//$recipiente[]	= "italadriz@disenobjetivo.cl";
		$cc				= NULL;
		//$bcc			= NULL;
		$bcc			= array();
		$bcc[]			= "portalchilesomostodos@gmail.com";
		//$bcc[]			= "sebastiangarciat@gmail.com";
		$sujeto			= "Certificado Nuevo Proyecto";
		$acuserecivo	= NULL;
		$html			= 0;
		$texto			= "Estimado $representante,\npara continuar con el proceso, usted debe imprimir el certificado adjunto y entregarlo en la Embajada o Consulado de Chile de su circunscripción, junto a los documentos señalados en las bases.";
		$adjuntos = array();
		$adjuntos[0]["ruta"] = $nombre_temp;
		$adjuntos[0]["nombre"] = $nombrearchivo.".pdf";
		
		$success = mosMail( $mosConfig_mailfrom, $mosConfig_fromname , $recipiente, $sujeto, $texto, $html, $cc, $bcc, $adjuntos, NULL, NULL, $acuserecivo );
		if (!$success) {
			mosErrorAlert( _CONTACT_FORM_NC );
		}

		unlink($nombre_temp);
?>