<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * A PyroCMS example module to simplify the use of PyroCMS Streams Core 
 *
 * A Generic Streams Admin controller to handle streams CRUD operations
 *
 *
 * @author      Adnan Sagar, Web Semantics, Inc. Dev Team
 * @website     http://websemantics.ca
 * @package     StreamsCRUD
 * @subpackage  
 * @copyright   MIT
 */

class Admin_streams extends Admin_Controller{
    
    protected $namespace = null;

    public $section = null; /* Active Tab (equals, Stream Slug) */

    protected $stream = null; 

    protected $ctl_uri = null;

    public function __construct() {
        parent::__construct();

        $this->stream = $this->section;

        $this->load->driver('Streams');

        $this->config->load(MODULE_NAME.'/config');
        $this->config->load(MODULE_NAME.'/streams');

        $this->namespace = $this->config->item('namespace');
        
        // Current stream controller uri
        $this->ctl_uri = 'admin/'.MODULE_NAME.'/'.$this->stream;

    }

    /**
     * List a strem entries using Streams CP Driver
     *
     */
    
    public function index() {

        // Customization for the Entries Table
        $extra = array(
             // The page title
            'title' => _lang($this->stream),
             // Delete and Edit buttons. The placeholder -entry_id- will be replaced by the entry id
            'buttons' => array(
                            array(
                                'label' => lang('global:edit'),
                                'url' => $this->ctl_uri.'/edit/-entry_id-'
                            ),
                            array(
                                'label' => lang('global:delete'),
                                'url' => $this->ctl_uri.'/delete/-entry_id-',
                                'confirm' => true
                            )
                        )
            );

        $pagination = $this->config->item('pagination');

        $this->streams->cp->entries_table($this->stream, $this->namespace, $pagination, $this->ctl_uri.'/index', true, $extra);
    }

   /**
     *  
     * Create a new entry using entry_form function
     * 
     */
    
    public function create() {

        $extra = array(
            'return' => $this->ctl_uri.'/index',
            'success_message' => _lang('submit_success'),
            'failure_message' => _lang('submit_failure'),
            'title' => _lang($this->stream.':new')
         );

        $this->streams->cp->entry_form($this->stream, $this->namespace, 'new', null, true, $extra);
    }


    /**
     * 
     * Edit a stream entry using entry_form function
     *
     */
    
    public function edit($id = 0) {
        $extra = array(
            'return' => $this->ctl_uri.'/index',
            'success_message' => _lang('submit_success'),
            'failure_message' => _lang('submit_failure'),
            'title' => _lang($this->stream.':edit')
        );

        $this->streams->cp->entry_form($this->stream, $this->namespace, 'edit', $id, true, $extra);
    }

    /**
     * 
     * Delete a stream entry by id
     *
     */
    
    public function delete($id = 0) {

        $this->streams->entries->delete_entry($id, $this->stream, $this->namespace);
        $this->session->set_flashdata('error', _lang('deleted'));
 
        redirect($this->ctl_uri.'/index');
    }
}