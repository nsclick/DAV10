<?php
/**
 * @version		$Id: reconocimientos.php 2010-08-07 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class DOCupones extends JTable
{
	/** @var int */
	var $id						= null;
	/** @var string */
	var $usuario				= null;
	/** @var date */
	var $nacimiento				= null;
	/** @var datetime */
	var $impresion				= null;
	
	function __construct( &$_db )
	{
		parent::__construct( '#__do_cupones', 'id', $_db );
	}

	/**
	 * Overloaded check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check()
	{
		return true;
	}
	
	function revisar()
	{
		$usuario	=& JFactory::getUser();
		$limite		= date('Y-m-d H:i:s' , time() - ( 60 * 60 * 24 * 30 * 10 ));
		
		$query 		= "SELECT *"
					. " FROM $this->_tbl"
					. " WHERE usuario = '" . $this->_db->getEscaped($usuario->get('username')) . "'"
					. " AND impresion >= '$limite'"
					;
		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectList();
		
		return !count($rows);
	}
	
	function lista( &$lists=array() )
	{

		$q 		= "SELECT COUNT(cp.id)"
					. " FROM $this->_tbl AS cp"
					. " LEFT JOIN #__users AS uu ON uu.username = cp.usuario"
					. " ORDER BY cp.impresion DESC"
					;
		
		$this->_db->setQuery( $q );
		$lists['total'] = $this->_db->loadResult();

		$query 		= "SELECT cp.*, uu.name AS nombre"
					. " FROM $this->_tbl AS cp"
					. " LEFT JOIN #__users AS uu ON uu.username = cp.usuario"
					. " ORDER BY cp.impresion DESC"
					;
		
		$this->_db->setQuery( $query );
		$rows		= $this->_db->loadObjectList();
		
		/*$y	 		= date("Y");
		$rows		= array();
		if( count( $r ) ) :
			foreach( $r as $index => $row ) :
				$fechatiempo	= strtotime("$y-".date("m-d",strtotime($row->nacimiento)));
				if( array_search($fechatiempo,array_keys($rows)) === false ) :
					$rows[$fechatiempo]	= $row;
				else :
					$flag = false;
					for($k=1;$flag==false;++$k) :
						if( array_search($fechatiempo+$k,array_keys($rows)) === false ) :
							$rows[$fechatiempo+$k]	= $row;
							$flag = true;
							break;
						endif;
					endfor;
				endif;
			endforeach;
		endif;
		
		krsort($rows);*/
		$obj			= array();
		$obj_ant		= array();
		$obj_dia_ant	= array();
		$arr			= array();
		$arr_final		= array();
		
		$cumple			= array();
		$y				= date("Y");
		$m				= date("m");
		$d				= date("d");
		if( count( $rows ) ) :
			foreach( $rows as $i => $row ) :

				$mes 					=  date("m",strtotime($row->nacimiento));
				$dia					=  date("d",strtotime($row->nacimiento));
				$cumple[$mes][$dia][$i]		= $row ;
				
			endforeach;
		endif;
		//print_r($cumple);
		krsort($cumple);
		
		if( count( $cumple ) ) :
			foreach( $cumple as $ind => $cump ) :
					krsort($cumple[$ind]);
			endforeach;
		endif;

 		if( count($cumple) ):	
			foreach( $cumple as $mes => $lista_mes ): 
				if( count($lista_mes) ):
					if( $m < $mes ) :
						foreach( $lista_mes as $lista_dia ): 
							if( count( $lista_dia ) ):
								foreach( $lista_dia as $persona ): 
									if( count( $persona ) ):
										$obj[] =  $persona; 
									endif;
								endforeach;								
							endif;
						endforeach;
					else:
						foreach( $lista_mes as $dia => $lista_dia ): 
							if( count( $lista_dia ) ):
								if( $d < $dia ):
									foreach( $lista_dia as $persona ): 
										if( count( $persona ) ):
											$obj_ant[] =  $persona; 
										endif;
									endforeach;		
								else:
									foreach( $lista_dia as $persona ): 
										if( count( $persona ) ):
											$obj_dia_ant[] =  $persona; 
										endif;
									endforeach;		
								endif;
							endif;
						endforeach;
					endif;
				endif;
			endforeach;
		endif;
		$oo = 0;
		$arr = array_merge( $obj_dia_ant , $obj , $obj_ant );
		if( count( $arr ) ):
			for($o=0; count($arr)>$o ; $o++ ):
				if( $o >= $lists['limitstart'] ):
					if( $oo <= $lists['limit'] ):
						$arr_final[] = $arr[$o];
					endif;
					$oo++;
				endif;
			endfor;
		endif;
		return $arr_final;
	}

}
?>
