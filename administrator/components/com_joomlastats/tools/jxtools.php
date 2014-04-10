<?php
/**
* @version $Id: jxtools.php,v 2.0 2008/10/26 10:37:37 mic Exp $
* @desc various tools for various extensions
* @package Various - JoomlaStats
* @copyright (C) 2008 mic - michael [ http://www.joomx.com ]
* @author mic info@joomx.com http://www.joomx.com
* @license other
*/

/**
 * this tools require the JTEXT.class for translation
 */

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'No Direct Access (J.1.5)' );
}

if( !defined( 'JXTOOLS_BASEPATH' ) ) {
	define( 'JXTOOLS_BASEPATH', dirname( __FILE__) .DS );
}

if( !defined( '_JEXEC' ) ) {
	require_once( JXTOOLS_BASEPATH . 'compat.joomla_1.0.php' );
}

if( !function_exists( 'file_get_contents' ) ) {
	/**
	 * check if file_get_contents function are avaliable
	 * @param	string	filename to return contents
	 */
	function file_get_contents( $file ) {
		$v = file( $file );
		return ( $v ) ? implode( '', $v ) : false;
	}
}

/**
 * @since J.1.0.11
 */
if( !function_exists( 'josGetArrayInts' ) ) {
	function josGetArrayInts( $name, $type=NULL ) {
		if ( $type == NULL ) {
			$type = $_POST;
		}

		$array = mosGetParam( $type, $name, array(0) );

		mosArrayToInts( $array );

		if( !is_array( $array ) ) {
			$array = array(0);
		}

		return $array;
	}
}

if( !function_exists( 'explodeTrim' ) ) {
	/**
	 * splitting string and trim each element
	 * @param	string		string		values
	 * @return	array
	 */
	function explodeTrim( $string ) {

		// check last sign, eleminate unwanted
		if( substr( $string, -1 ) == ';' ) {
			$string = substr( $string, 0, strlen( $string ) - 1 );
		}

		$arr 	= explode( ';', $string );
		$count 	= count( $arr );

		for( $i = 0; $i < $count; $i++ ) {
			$arr[$i] = trim( $arr[$i] );
		}

		return $arr;
	}
}

if( !function_exists( '_smartSubstr' ) ) {
	/**
	 * Smart split of given string
	 *
	 * @author mic <info@joomx.com>
	 * @copyright michael (mic) pagler
	 * @license Other
	 * @version 1.2 (2007.08.22)
	 *
	 * @param string	$text				text to cut
	 * @param int		$len				length to cut the text
	 * @param string	$suffix				optional chars for suffix
	 * @return cut string
	 */
	function _smartSubstr( $text, $len = 30, $suffix = ' ...' ) {

		if( !$text ) {
			return;
		}

		// handover original to tmp.var, maybe we need it ...
		$textOrg = $text;
		$textLen = strlen( $text );

		if( $len <= 0 ){
			$len = 10;
		}elseif( $textLen < $len ) {
			$len = $textLen;
		}
		$len1 = $len - ( strlen( $suffix ) < 5 ? strlen( $suffix ) : 4 );

		if( $textLen <= $len ) {
			return $text;
		}

		$text = _smartCut( $text, $textLen, $len, $len1, $suffix );

		// if text is empty, do it again and fill text value
	    if( trim( $text, $suffix ) == '' ) {
	    	$text = _smartCut( $textOrg, $textLen, $len, '1', $suffix, true );
	    }

	    return $text;
	}

	/**
	 * Cut or expand given string
	 * called by _smartSubstr
	 * loop is limited to factor 1.5 of $len
	 *
	 * @author mic <info@joomx.com>
	 * @copyright michael (mic) pagler
	 * @license Other
	 * @version 1.2 (2007.08.22)
	 *
	 * @access private
	 * @param string $text
	 * @param int $textLen
	 * @param int $len
	 * @param int $len1
	 * @param string $suffix
	 * @param bool $type		false=count down | true=count up
	 * @return string
	 */
	function _smartCut( &$text, $textLen, $len, $len1, $suffix, $type = false ) {
		if( $textLen >= $len1 ) {
		    // checking asci 0 and asci 32
	        if( ord( substr( $text, $len1 ) ) != '0' && ord( substr( $text, $len1 ) ) != '32' ) {
	            while( ( ord( substr( $text, $len1 ) ) != '0' ) && ( ord( substr( $text, $len1 ) ) != '32' ) && $len1 > 0 ){
	            	if( $type ) {
	                	$len1++;
	            	}else{
	            		$len1--;
	            	}
	            	// limit loop
	            	if( $len1 >= ( $len * 1.5 ) ) {
	            		break;
	            	}
	            }
	            $text = substr( $text, 0, ( $len - ( $len - $len1 ) ) );
	        }else{
	            $text = substr( $text, 0, $len1 );
	        }

	        $text .= $suffix;
	    }

	    return $text;
	}
}

if( !class_exists( 'jxTools' ) ) {
	/**
	 * generic tool class for various
	 * (c) mic [ http://www.joomx.com ] 2007
	 * @version 1.2
	 *
	 */

	class jxTools
	{
		// var for language holder
		var $lng;

		/**
		 * load correct language - helper function if CMS < Joomla! 1.5.x
		 *
		 * Checks several language settings and loads correct language
		 *
		 * @param bool 		$site		is backend (false) or frontend (true) - used for getting admin language if exists
		 * @param string	$langPath	path for language files
		 * @param bool 		$browser	check also browser setting
		 * @param string	$ilang		used $option to call correct language (e.g.: com_install) - ONLY USED AT INSTALL!
		 * @version 1.6 2008.10.26
		 */
		function _checkLanguage( $site = true, $langPath = '', $browser = false, $ilang = '' ) {
			global $mainframe, $option;
			global $ExtPath;
			global $lng;

			if( !$langPath ) {
				echo '<script>alert(\'NO path to language defined (' . $option . ')!\');window.history.go(-1);</script>'
				. "\n";
				exit();
			}

			if( $ilang ) {
				$option = $ilang;
			}

			// build array of possible languages to check (incl. CMS-language: user or admin)
		    $langs		= array( 'bg', 'cs', 'da', 'de', 'en', 'fi', 'fr', 'it', 'es', 'hr', 'hu', 'lt', 'nl', 'no', 'pl', 'pt', 'ro', 'ru', 'sv' );
			$det_lang	= '';
			$alang		= $mainframe->getCfg( 'alang' ); // check if an admin language is defined
			$cmsLang	= $mainframe->getCfg( 'lang' ); // get user language

			if( $browser ) {
				// check users browser language
			    if( phpversion() >= '4.1.0' ){
			        // php => 4.1.0
			        $det_lang = strtolower( substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ));
			    }else{
			        // php < 4.10
			        global $HTTP_SERVER_VARS;
			        $det_lang = strtolower( substr( $HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE'], 0, 2 ));
			    }
			}

			if( $mainframe->getCfg( 'debug' ) ) {
				echo '<pre>'
				. 'DEBUG info from jxTools (function _checkLanguage):<br />'
		        . 'Detected lang [' . $det_lang . ']<br />'
		        . 'Admin Language [' . $alang . ']<br />'
		        . 'User Language [' . $cmsLang . ']<br />'
		        . 'Language path [' . $langPath . ']<br />'
		        . 'Variable $ExtPath[AdminAbs] [' . $ExtPath['AdminAbs'] . ']<br />'
		        . '<-------------------------------------------------------><br />'
		        . 'option [' . $option . ']'
		        . '</pre>';
		    }

		    if( !$site ) {
		    	// try to get admin lang if backend
		    	if( $alang ) {
		    		$cmsLang = $alang;
		    	}
		    }

		    $langs[] = $cmsLang;

		    if( !$det_lang ) {
		    	$det_lang = $cmsLang;
		    }

		    if( in_array( $det_lang, $langs ) ) {
		        switch( strtolower( $det_lang ) ){

		        	case 'bg':
		        	case 'bg_bg':
		        	case 'bgr_bgr':
		        	case 'bulgarian':
		        		$lng = 'bg-BG';
		        		break;

		        	case 'cs':
		        	case 'cs_CZ':
		        	case 'csy':
		        	case 'czech':
		        		$lng = 'cs-CZ';

		        	case 'da':
		        	case 'da_dk':
		        	case 'da_dk.iso8859-1':
		        	case 'da_dk.iso.8859-1':
		        	case 'da_dk.iso8859-15':
		        	case 'da_dk.ibm-850':
		        	case 'dan':
		        	case 'danish':
		        		$lng = 'da-DK';

		        	case 'fi':
		        	case 'fi_fi':
		        	case 'fin':
		        	case 'finnish':
		        		$lng = 'fi-FI';
		        		break;

		        	case 'de':
		        	case 'german':
					case 'germanf':
					case 'germani':
					case 'de_de':
					case 'de-de':
					case 'de_at':
					case 'de_ch':
					case 'ge':
		                $lng = 'de-DE';
		                break;

		            case 'es':
		            case 'es_es':
		            case 'es-es':
		            case 'spain':
		            case 'spanish':
		                $lng = 'es-ES';
		                break;

		            case 'fr':
		            case 'fr_fr':
		            case 'fr_be':
		            case 'fr_ca':
		            case 'fr_lu':
		            case 'fr_ch':
		            case 'french':
		            case 'fra':
		            case 'france':
		            case 'frb':
		            case 'french-belgian':
		            case 'frc':
		            case 'french-canadian':
		            case 'french-swiss':
		            case 'frs':
		                $lng = 'fr-FR';
		                break;

		            case 'hr':
		            case 'hr_hr':
		            case 'croatian':
		            case 'croatia':
		            	$lng = 'hr-HR';

		            case 'hu':
		            case 'hu_hu':
		            case 'hun':
		            case 'hungarian':
		            case 'hungary':
		            	$lng = 'hu-HU';
		            	break;

		            case 'it':
		            case 'it_it':
		            case 'it_ch':
		            case 'italian':
		            case 'ita':
		            case 'italian-swiss':
		            case 'its':
		                $lng = 'it-IT';
		                break;

		            case 'lt':
		            case 'lt_lt':
		            case 'lithuanian':
		            	$lng = 'lt-LT';
		            	break;

		            case 'nl':
		            case 'nl_nl':
		            case 'nl_be':
		            case 'dutch':
		            case 'nld':
		            case 'nld_nld':
		            case 'belgian':
		            case 'dutch-belgian':
		                $lng = 'nl-NL';
		                break;

		            case 'no':
		            case 'no_no':
		            case 'norwegian':
		            case 'nor':
		            case 'norwegian-bokmal':
		            case 'non':
		            case 'norwegian-nynorsk':
		            	$lng = 'no-NO';
		            	break;

		            case 'pl':
		            case 'pl_pl':
		            case 'pl-pl':
		            case 'polish':
		            case 'plk':
		                $lng = 'pl-PL';
		                break;

		            case 'pt':
		            case 'pt_pt':
		            case 'pt_br':
		            case 'portuguese':
		            case 'ptg':
		            case 'portuguese-brazil':
		            case 'ptb':
		            	$lng = 'pt-PT';
		            	break;

		            case 'ro':
		            case 'ro_ro':
		            case 'romanian':
		            	$lng = 'ro-RO';
		            	break;

		            case 'ru':
		            case 'ru_ru':
		            case 'ru_ua':
		            case 'russian':
		            case 'rus':
		            	$lng = 'ru-RU';
		            	break;

		            case 'sv':
		            case 'sv_se':
		            case 'sv_fi':
		            case 'swedish':
		            case 'sve':
		            	$lng = 'sv-SE';
		            	break;

		            case 'en':
		            case 'english':
					case 'en_gb':
					case 'en_us':
					case 'en-us':
					case 'en_ca':
					case 'en-ca':
					case 'en_ie':
					case 'en-ie':
					case 'uk':
		            case 'us':
		            default:
		                 $lng = 'en-GB';
		                break;
		        }
		    }else{
		        $lng = 'en-GB';
		    }

		    // check if lang file does exist, because en-GB is always existing
		    if( $lng != 'en-GB' ) {
		    	if( !file_exists( $langPath .DS. $lng ) ) {
		    		$lng = 'en-GB';
		    	}
		    }

		    if( $mainframe->getCfg( 'debug' ) ) {
		    	echo '<pre>return language [' . $lng . ']</pre>';
		    }

		    if( !defined( '_JEXEC' ) ) {
				require_once( JXTOOLS_BASEPATH . 'language' . DS . 'language.php' );
				require_once( JXTOOLS_BASEPATH . 'language' . DS . 'registry.php' );
			}

			$lang =& JLanguageHelper::getLanguage( $lng );
			$lang->setDebug( $mainframe->getCfg( 'debug' ) );
			$lang->load( $option, $langPath );
		}


		function _getLng() {
			global $lng;

			return $lng;
		}

		/**
		 * *********** new function belonging to J.1.5.x ***************
		 * @since 2008.09.05
		 */

		/**
		 * Gets a parameter value from the $_REQUEST object
		 *
		 * @param string $paramName The parameter name
		 * @param string $defaultValue The default value (null if not specified)
		 * @return mixed The parameter value
		 */
		function getParam( $paramName, $defaultValue = null ) {
			if( !defined('_JEXEC') ) {
				return mosGetParam( $_REQUEST, $paramName, $defaultValue );
			}else{
				return JRequest::getVar( $paramName, $defaultValue );
			}
		}

		/**
		 * Returns the site's base URI
		 *
		 * @return string the site's URI e.g. http://www.mysite.com
		 */
		function siteURI() {
			if( !defined( '_JEXEC' ) ) {
				$port = ( $_SERVER['SERVER_PORT'] == 80 ) ? '' : ':' . $_SERVER['SERVER_PORT'];
				$root = ( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'] )
						. $port . $_SERVER['PHP_SELF'];
				$root = str_replace( '/administrator/', '/', $root );
				$upto = strpos( $root, '/index' );
				$root = substr( $root, 0, $upto );

				$https = !( empty( $_SERVER['HTTPS'] ) || ( $_SERVER['HTTPS'] == 'off' ) );
				$protocol = $https ? 'https://' : 'http://';

				return $protocol . $root;
			}else{
				return substr_replace( JURI::root(), '', -1, 1 );
			}
		}

		/**
		 * Returns Joomla!'s database object
		 *
		 * @return JDatabase
		 */
		function getDatabase() {
			if( defined( '_JEXEC' ) ) {
				$database 	= & JFactory::getDBO();
			}else{
				global $database;
			}

			return $database;
		}

		function getDBPrefix() {
			if( !defined('_JEXEC') ) {
				global $mosConfig_dbprefix;
				return $mosConfig_dbprefix;
			}else{
				$conf =& JFactory::getConfig();
				return $conf->getValue( 'config.dbprefix' );
			}
		}

		/**
		 * Parse an INI file and return an associative array.
		 * Since PHP versions before 5.1 are buggy, we use an own solution
		 *
		 * @param string	$file The file to process
		 * @param bool		$process_sections True to also process INI sections
		 * @return array An associative array of sections, keys and values
		 */
		function ParseIniFile( $file, $process_sections ) {
			//if( version_compare( PHP_VERSION, '5.1.0', '>=' ) ) {
			//	return parse_ini_file( $file, $process_sections ); //parse_ini_file is not available in PHP safe mode
			//}else{
				return self::_ParseIniFile( $file, $process_sections );
			//}
		}

		/**
		 * A PHP based INI file parser.
		 *
		 * borrought from php.net posted by asohn ~at~ aircanopy ~dot~ net
		 * see: http://gr.php.net/manual/en/function.parse-ini-file.php#82900
		 *
		 * @param	string	$file Filename to process
		 * @param	bool	$process_sections True to also process INI sections
		 *
		 * @return	array An associative array of sections, keys and values
		 * @access private
		 */
		function _ParseIniFile( $file, $process_sections = false ) {
			$process_sections = ( $process_sections !== true ) ? false : true;

			$ini = file( $file );
			if( count( $ini ) == 0 ) {
				return array();
			}

			$sections	= array();
			$values	= array();
			$result	= array();
			$globals	= array();
			$i 		= 0;

			foreach( $ini as $line ) {
				$line = trim( $line );
				$line = str_replace( '\t', ' ', $line );

				// comments
				if( !preg_match( '/^[a-zA-Z0-9[]/', $line ) ) {
					continue;
				}

				// sections
				if( $line{0} == '[' ) {
					$tmp		= explode(']', $line );
					$sections[]	= trim( substr( $tmp[0], 1 ) );
					$i++;
					continue;
				}

				// key-value pair
				list( $key, $value ) = explode( '=', $line, 2 );
				$key	= trim( $key );
				$value	= trim( $value );
				if( strstr( $value, ';' ) ) {
					$tmp = explode( ';', $value );
					if( count( $tmp ) == 2 ) {
						if( ( ( $value{0} != '"' ) && ( $value{0} != '\'' ) )
						|| preg_match( '/^".*"\s*;/', $value )
						|| preg_match( '/^".*;[^"]*$/', $value )
						|| preg_match( "/^'.*'\s*;/", $value )
						|| preg_match( "/^'.*;[^']*$/", $value ) ) {
							$value = $tmp[0];
						}
					}else{
						if( $value{0} == '"' ) {
							$value = preg_replace( '/^"(.*)".*/', '$1', $value );
						}elseif( $value{0} == '\'' ) {
							$value = preg_replace( "/^'(.*)'.*/", '$1', $value );
				        }else{
				        	$value = $tmp[0];
				        }
					}
				}
				$value = trim( $value );
				$value = trim( $value, "'\"" );

				if( $i == 0 ) {
					if( substr( $line, -1, 2 ) == '[]' ) {
						$globals[$key][] = $value;
					}else{
						$globals[$key] = $value;
					}
				}else{
					if( substr( $line, -1, 2) == '[]' ) {
						$values[$i-1][$key][] = $value;
					}else{
						$values[$i-1][$key] = $value;
					}
				}
			}

			for( $j = 0; $j < $i; $j++ ) {
				if( $process_sections === true ) {
					$result[$sections[$j]] = $values[$j];
				}else{
					$result[] = $values[$j];
				}
			}

			return $result + $globals;
		}

		/**
		 * get CMS version
		 *
		 * @return string
		 */
		function _getCMSVersion() {
			if( class_exists( 'joomlaversion' ) ) {
				$class = 'joomlaversion';
			}elseif( class_exists( 'JVersion' ) ) {
				$class = 'JVersion';
			}
			$version = new $class;

			return 'J' . str_replace( '.', '', $version->getShortVersion() );
		}
	} // end class jxTools
}

if( !function_exists( '_CheckCMSVersion' ) ) {
	/**
	 * Helper function to find correct cms version
	 *
	 * @return bool
	 * @author mic [ http://www.joomx.com ]
	 * @since 2008.02.24
	 */
	function _CheckCMSVersion() {
		global $_VERSION;

		if( $_VERSION->RELEASE >= '1.0' && $_VERSION->DEV_LEVEL >= '14' ) {
			return true;
		}else{
			return false;
		}
	}
}