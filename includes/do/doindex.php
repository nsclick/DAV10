<?php

function doindex () {
	// Is user logged in?
	$user_id  = $user->get ( 'id' );
	$user_gid = $user->get ( 'gid' );

	if ( empty ( $user_id ) ) {
		$mainframe->redirect ( _DO_LOGIN_FORM );
	} else {
		if ( $user_gid == 31 ) {
			$option = JRequest::getCmd ( 'option' );
			if ( !$session->get( 'GPTI_redirect' ) || ( $option != 'com_gpti' && $option != 'com_user' ) ) {
				$session->set( 'GPTI_redirect', true );
				$mainframe->redirect( JRoute::_ ( "index.php?option=com_gpti&Itemid=27" ) );
			}
		}
	}
}

?>