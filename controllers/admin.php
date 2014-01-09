<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A PyroCMS example module to simplify the use of PyroCMS Streams Core 
 *
 * A Generic Admin page for the module, namely Dashboard.
 *
 *
 * @author      Adnan Sagar, Web Semantics, Inc. Dev Team
 * @website     http://websemantics.ca
 * @package     StreamsCRUD
 * @subpackage  
 * @copyright   MIT
 */

class Admin extends Admin_Controller {

    protected $section = 'dashboard'; /* Active section tab */

    /**
     * Show the Dashboard
     *
     *
     * @return	void
     */
    
    public function index() {
        $data = array();
        
          $this->template->title($this->module_details['name'])
          ->build('admin/index', $data);
    }

}