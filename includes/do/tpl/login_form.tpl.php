<?php
  $session    =& JFactory::getSession();
  $config   = JFactory::getConfig();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <!-- Metas -->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <!-- Title -->
  <title>Cl&iacute;nica D&aacute;vila - Login</title>
  
  <!-- Favicon -->
  <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
  
  <!--  Stylehsheet-->
  <style type="text/css">
  	body					{ color:#999; font-size:12px; font-family:Arial, Helvetica, sans-serif; margin:100px 0px 0px 0px; }
  	form					{ display:inline; }
  	table					{ color:#000; font-size:12px; }
  	div.mensaje				{ color:#F00; font-size:14px; font-weight:bold; text-align:center; padding:10px; }
  	div.principal			{ /*width:100%; padding:0px; margin:0px; text-align:center;*/ }
  	div.contenedor			{ background-image:url(images/login_bg.jpg); background-repeat:no-repeat; width:515px; height:366px; overflow:hidden; }
  	div.formulario			{ margin:255px 0px 0px 220px; }
  	.form					{ border:1px solid #FFF; background-color:#FFF; font-size:11px; width:95px; color:#999; }
  	.submit					{ border:1px solid #DDD; background-color:#FFF; font-size:11px; color:#999; height:18px; }
  	.msj					{ font-size:10px; color:#ffffff; text-align:right; padding:2px 8px 0px 10px; font-weight:bold; }
  </style>
</head>
<body>

<!-- Main -->
<div align="center" class="principal">
  <?php if ( $config->getValue('offline') ) : ?>
    <div class="mensaje"><?php echo _DO_LOGIN_OFFLINE; ?></div>
  <?php endif; ?>
  
  <!-- Container -->
  <div class="contenedor">
    <!-- Form -->
    <div class="formulario">
      <?php if ( self :: IPprivada() ) : ?>
        
        <!-- Private Form -->
        <form name="login" method="post" action="<?php echo _DO_LOGIN_URL;?>">
          <input type="hidden" value="<?php echo JURI::base();?>login.php" name="url_acceso" />
          <input type="hidden" value="<?php echo JURI::base();?>login.php" name="url_login" />
          <input type="hidden" value="<?php echo $session->getId();?>" name="phpsid" />
          
          <table width="295" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td>Usuario (Rut):</td>
              <td>Contrase&ntilde;a:</td>
              <td>&nbsp;</td>
            </tr>

            <tr>
              <td><input type="text" name="us" id="us" class="form" value="Usuario (Rut)" title="Usuario (Rut)"<?php echo $config->getValue('offline') ? ' disabled="disabled"':'';?> onblur="javascript:formBlur(this);" onfocus="javascript:formFocus(this);" /></td>
              <td><input type="password" name="valor" class="form" value="Contrase&ntilde;a" title="Contrase&ntilde;a"<?php echo $config->getValue('offline') ? ' disabled="disabled"':'';?> onblur="javascript:formBlur(this);" onfocus="javascript:formFocus(this);" /></td>
              <td><input type="submit" name="submit" id="submit" value="Entrar &raquo;"<?php echo $config->getValue('offline') ? ' disabled="disabled"':'';?> class="submit" /></td>
            </tr>

            <?php if( defined('_DO_ERROR') ) : ?>
              <tr>
                <td colspan="3" class="msj"><?php echo _DO_ERROR;?></td>
              </tr>
            <?php endif;?>

          </table>

        </form>
        <!--/ Private Form -->

      <?php else : ?>
        
        <!-- Non Private Form -->
        <form name="login" method="post" action="<?php echo _DO_LOGIN_FORM; ?>">
          
          <table width="295" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td>Usuario (Rut):</td>
              <td>Contrase&ntilde;a:</td>
              <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td><input type="text" name="us" id="us" class="form" value="Usuario (Rut)" title="Usuario (Rut)"<?php echo $config->getValue('offline') ? ' disabled="disabled"':'';?> onblur="javascript:formBlur(this);" onfocus="javascript:formFocus(this);" /></td>
              <td><input type="password" name="valor" class="form" value="Contrase&ntilde;a" title="Contrase&ntilde;a"<?php echo $config->getValue('offline') ? ' disabled="disabled"':'';?> onblur="javascript:formBlur(this);" onfocus="javascript:formFocus(this);" /></td>
              <td><input type="submit" name="submit" id="submit" value="Entrar &raquo;"<?php echo $config->getValue('offline') ? ' disabled="disabled"':'';?> class="submit" /></td>
            </tr>

            <?php if( defined('_DO_ERROR') ) : ?>
              <tr>
                <td colspan="3" class="msj"><?php echo _DO_ERROR;?></td>
              </tr>
            <?php endif;?>

          </table>

          <input type="hidden" name="_do_login" value="1" />

          <?php echo JHTML::_( 'form.token' ); ?>
        </form>
        <!--/ Non Private Form -->

      <?php endif; ?>

    </div>
    <!--/ Form -->
  </div>
  <!--/ Container -->

</div>
<!--/ Main -->


<!-- Script -->
<script type="text/javascript">
  function formBlur( obj )
  {
    if( obj.value == '' ){
      obj.value = obj.title;
    }
  }
  function formFocus( obj )
  {
    if( obj.value == obj.title ){
      obj.value = '';
    }
  }

  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
  
  document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
  
  try{
    var pageTracker = _gat._getTracker("UA-21901064-1");
    pageTracker._trackPageview();
  } catch(e) {
    // do nothing...
  }
</script>

</body>
</html>