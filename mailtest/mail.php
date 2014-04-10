<?php
		ini_set('display_errors', true);  
		ini_set ('error_reporting', E_ALL); 
		require("../configuration.php");
		$config 	= new JConfig();

		$mailer		= isset($_POST['mailer']) ? $_POST['mailer'] : $config->mailer;
		$host		= isset($_POST['host']) ? $_POST['host'] : $config->smtphost;
		$port		= isset($_POST['port']) ? $_POST['port'] : $config->smtpport;
		$secure		= isset($_POST['secure']) ? $_POST['secure'] : $config->smtpsecure;
		$auth		= isset($_POST['auth']) ? $_POST['auth'] : $config->smtpauth;
		$user		= isset($_POST['user']) ? $_POST['user'] : $config->smtpuser;
		$pass		= isset($_POST['pass']) ? $_POST['pass'] : $config->smtppass;
		$from		= isset($_POST['from']) ? $_POST['from'] : $config->mailfrom;
		$email		= isset($_POST['email']) ? $_POST['email'] : '';
		$mensaje	= isset($_POST['mensaje']) ? $_POST['mensaje'] : '';
		$sendmail	= isset($_POST['sendmail']) ? $_POST['sendmail'] : 0;
		$subject	= isset($_POST['subject']) ? $_POST['subject'] : "Prueba Correo Davila.cl";
?>
<html>
<head>

</head>
<body>
<form name="testmail" action="mail.php" method="post">
	<div>
        <label for="mailer">Mailer:</label>
        <select name="mailer" id="mailer">
            <option value="smtp"<?php echo $mailer=='smtp'?' selected="selected"':'';?>>SMTP</option>
            <option value="mail"<?php echo $mailer=='mail'?' selected="selected"':'';?>>Funci&oacute;n mail</option>
        </select>
    </div>
    <hr>
    SMTP:
	<div>
        <label for="host">Host:</label>
        <input type="text" name="host" id="host" value="<?php echo $host;?>" />
    </div>
	<div>
        <label for="port">Puerto:</label>
        <input type="text" name="port" id="port" value="<?php echo $port;?>" />
    </div>
    <div>
    	<label for="secure">Seguridad:</label>
        <select size="1" class="inputbox" id="secure" name="secure">
        	<option value="none">Nada</option>
            <option value="ssl"<?php echo $secure=='ssl'?' selected="selected"':'';?>>SSL</option>
            <option value="tls"<?php echo $secure=='tls'?' selected="selected"':'';?>>TLS</option>
        </select>
    </div>
	<div>
        <label for="auth">Auth:</label>
        <select name="auth" id="auth">
            <option value="1"<?php echo $auth=='1'?' selected="selected"':'';?>>Si</option>
            <option value="0"<?php echo $auth=='0'?' selected="selected"':'';?>>No</option>
        </select>
    </div>
	<div>
        <label for="user">User:</label>
        <input type="text" name="user" id="user" value="<?php echo $user;?>" />
    </div>
	<div>
        <label for="pass">Pass:</label>
        <input type="text" name="pass" id="pass" value="<?php echo $pass;?>" />
    </div>
    <hr>
    Correo:
	<div>
        <label for="from">De:</label>
        <input type="text" name="from" id="from" value="<?php echo $from;?>" size="30" />
    </div>
	<div>
        <label for="email">Para:</label>
        <input type="text" name="email" id="email" value="<?php echo $email;?>" size="30" />
    </div>
	<div>
        <label for="subject">Sujeto:</label>
        <input type="text" name="subject" id="subject" value="<?php echo $subject;?>" size="50" />
    </div>
	<div>
        <label for="mensaje">Mensaje:</label>
        <textarea name="mensaje" id="mensaje"><?php echo $mensaje;?></textarea>
    </div>
    <br>
    <input type="hidden" name="sendmail" value="1" />
    <div><input type="submit" name="btnsubmit" value="Enviar" /></div>
</form>
<?php
	if( $sendmail ) :
		echo "<hr>";	
		//Clases requeridas de phpMailer
		require("class.phpmailer.php");
		require("class.smtp.php");
		
		$mail = new PHPMailer();
		$mail->SetLanguage('es','./');
		$mail->IsSmtp();
/*
		$mail->Host = "200.55.208.198";
		$mail->SMTPAuth = true;
		$mail->Username = "massivo@disenobjetivo.cl";
		$mail->Password = "massivo.do";
*/
		$mail->Host = $host;
		$mail->Port = $port;
		if ($secure == 'ssl' || $secure == 'tls') {
			$mail->SMTPSecure = $secure;
		}
		$mail->SMTPAuth = $auth;
		$mail->Username = $user;
		$mail->Password = $pass;

		
		$mail->CharSet 	= "UTF-8";
//		$mail->IsMail();
		$mail->Mailer 	= $mailer;
		//$mail->Mailer 	= "smtp";
		//$mail->Mailer 	= "sendmail";

		$mail->From = $from;
		$mail->FromName = $config->fromname;
		$mail->AddAddress($email);      // name is optional
//		$mail->AddAddress("sebastiangarciat@gmail.com");
//		$mail->AddCC("sebastiangarciat@gmail.com");
//		$mail->AddReplyTo($email, $nombre);

		$mail->IsHTML(true);        // set email format to HTML

		$cuerpo = date("Y-m-d H:i:s") .
				"mail->Host = ".$mail->Host."<br />" .
				"mail->SMTPAuth = ".$mail->SMTPAuth."<br />" .
				"mail->Username = ".$mail->Username."<br />" .
				"mail->Password = ".$mail->Password."<br />" .
				"mail->Mailer = ".$mail->Mailer."<br />";
		/*$cuerpoAlt = date("Y-m-d H:i:s") .
					"mail->Host = ".$mail->Host."\n" .
					"mail->SMTPAuth = ".$mail->SMTPAuth."\n" .
					"mail->Username = ".$mail->Username."\n" .
					"mail->Password = ".$mail->Password."\n" .
					"mail->Mailer = ".$mail->Mailer."\n";*/
		$cuerpo		= $mensaje == '' ? $cuerpo : $mensaje;

		$mail->Subject = $subject;
		$mail->Body    = $cuerpo;
		$mail->AltBody = $cuerpo;
		
		if($mail->Send()){		
			echo "&Eacute;xito<br />";
			echo "mail->Host = ".$mail->Host."<br />";
			echo "mail->SMTPAuth = ".$mail->SMTPAuth."<br />";
			echo "mail->Username = ".$mail->Username."<br />";
			echo "mail->Password = ".$mail->Password."<br />";
			echo "mail->Mailer = ".$mail->Mailer."<br />";
			
		}else{
		
			echo "Error<br />";
			echo "mail->Host = ".$mail->Host."<br />";
			echo "mail->SMTPAuth = ".$mail->SMTPAuth."<br />";
			echo "mail->Username = ".$mail->Username."<br />";
			echo "mail->Password = ".$mail->Password."<br />";
			echo "mail->Mailer = ".$mail->Mailer."<br />";
			if( $mail->error_count > 0 ) {
				echo "<br />Mailer Error: " . $mail->ErrorInfo . "";
			}
			
		}
	endif;
?>
</body>
</html>