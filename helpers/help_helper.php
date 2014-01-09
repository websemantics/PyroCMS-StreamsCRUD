<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * A PyroCMS example module to simplify the use of PyroCMS Streams Core 
 *
 * Generic Helper function
 *
 *
 * @author      Adnan Sagar, Web Semantics, Inc. Dev Team
 * @website     http://websemantics.ca
 * @package     StreamsCRUD
 * @subpackage  
 * @copyright   MIT
 */


if (!function_exists('_lang')){


	/**
	 * language translate function
	 *
	 * @return  mixed
	 */

	function  _lang($text){
	  
	  return lang(MODULE_NAME.':'.$text);

	}

	function  _slang($text){
	  
	  return MODULE_NAME.':'.$text;

	}

}