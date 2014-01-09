<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A PyroCMS example module to simplify the use of PyroCMS Streams Core 
 *
 * Stream Admin controller for Faqs stream
 *
 *
 * @author      Adnan Sagar, Web Semantics, Inc. Dev Team
 * @website     http://websemantics.ca
 * @package     StreamsCRUD
 * @subpackage  
 * @copyright   MIT
 */

require 'admin_streams.php';

class Admin_faqs extends Admin_streams {
    public $section = 'faqs';
}