<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A PyroCMS example module to simplify the use of PyroCMS Streams Core 
 *
 * A Generic Frontend controller for the module
 *
 *
 * @author      Adnan Sagar, Web Semantics, Inc. Dev Team
 * @website     http://websemantics.ca
 * @package     StreamsCRUD
 * @subpackage  
 * @copyright   MIT
 */


class StreamsCRUD extends Public_Controller{

    /**
     * The constructor
     * @access public
     * @return void
     */
    
    public function __construct() {
        parent::__construct();
        $this->lang->load(MODULE_NAME.'/'.MODULE_NAME);
        $this->load->driver('Streams');
        $this->template->append_css('module::style.css');

        // Load streams / fields declaration file
        $this->config->load(MODULE_NAME.'/streams');
    }

    /**
     * 
     * Module Main Admin Page
     *
     * We are using the Streams API to grab
     * data from the faqs database. It handles
     * pagination as well.
     *
     * @access	public
     * @return	void
     */
    
    public function index() {

        $namespace = $this->config->item('namespace');

        $data['faqs'] = $this->streams->entries->get_entries(array(
            'stream' => 'faqs',
            'namespace' => $namespace,
        ));

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('index', $data);
    }

}

/* End of file ee.php */
