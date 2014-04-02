<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<div class="mod_chat" align="left">
  <?php if( $datos->online ): ?>
	<div class="btn"><a href="javascript:void(0);" onclick="javascript:return false;" id="mod_chat_toggle" title="Ver Usuarios"><img src="<?php echo $template;?>/imagenes/mod_chat_online.jpg" alt="Ver Usuarios" title="Ver Usuarios" /></a></div>
  <?php else:?>
	<!--<img src="<?php echo $template;?>/imagenes/mod_chat_offline.jpg" alt="Chat Desconectado" title="Chat Desconectado" />-->
    <img src="<?php echo $template;?>/imagenes/mod_chat_online.jpg" alt="Chat Desconectado" title="Chat Desconectado" />
  <?php endif;?>
</div>
<?php if( $datos->online ): ?>
	<div class="lista chat" id="mod_chat_lista" align="left">
      <div class="listado" id="mod_chat_listado">
      <ul>
      <?php foreach( $datos->usuarios as $usuario ) : ?>
        <li><a href="javascript:void(0);" onclick="javascript:chatWith('<?php echo $usuario->username;?>','<?php echo $usuario->name;?>'); return false;" title="<?php echo $usuario->name;?>"><?php echo $usuario->name;?></a></li>
      <?php endforeach; ?>
      </ul>
      </div>
      <div class="buscador">
      	<input type="text" name="chatquery" id="chatquery" class="inputbox" value="Buscar persona" title="Buscar persona" onblur="javascript:form_texto_blur(this);" onfocus="javascript:form_texto_focus(this);" onkeypress="javascript:modChatQuery(this)" size="40" />
      </div>
    </div>
<?php endif;?>