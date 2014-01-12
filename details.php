<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * A PyroCMS example module to simplify the use of PyroCMS Streams Core 
 *
 *
 * @author      Adnan Sagar, Web Semantics, Inc. Dev Team
 * @website     http://websemantics.ca
 * @package     StreamsCRUD
 * @subpackage  
 * @copyright   MIT
 */

require 'config/constants.php';

class Module_Ee extends Module {

    private $namespace = null;

    public $version = null;

    public function __construct() {
       
        parent::__construct();
        
        // Load language file
        $this->load->language(MODULE_NAME.'/'.MODULE_NAME);

        // Load the helper file
        $this->load->helper(MODULE_NAME.'/help');

        // Load the main config file
        $this->config->load(MODULE_NAME.'/config');

        // Load streams / fields declaration file
        $this->config->load(MODULE_NAME.'/streams');

        // Read config info, i.e. namespace and current module version
        $this->namespace = $this->config->item('namespace');
        $this->version = $this->config->item('version');

    }

    public function info() {

        $url = 'admin/'.MODULE_NAME;

        // Get streams declarations
        $streams = $this->config->item('streams');

        // First menu item, the Dashboard
        $sections = array(
                'dashboard' => array(
                    'name' => _lang('dashboard'),
                    'uri' => $url,
                )
            );

        // Create a menu item for each stream
        foreach ($streams as $stream_slug => $info) {
            $sections[$stream_slug] = array(
                    'name' => _lang($stream_slug),
                    'uri' => $url.'/'.$stream_slug.'/index',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => _slang($stream_slug.':new'),
                            'uri' => $url.'/'.$stream_slug.'/create',
                            'class' => 'add'
                        )
                    )
                );
        }

        // Return the details
        return array(
            'name' => array(
                'en' => $this->config->item('name')
            ),
            'description' => array(
                'en' => $this->config->item('description')
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content',
            'sections' => $sections
        );
    }

    /**
     * Install Module
     *
     * Setup Fileds and Streams
     *
     */
    
    public function install(){

        $this->load->driver('Streams');

        // Make sure the namespace is clean
        $this->streams->utilities->remove_namespace($this->namespace);

        // Get table prefix for streams
        $prefix = $this->config->item('prefix');

        // Get streams declarations
        $streams = $this->config->item('streams');

        // Get fields declarations
        $fields = $this->config->item('fields');

        // Get data declarations
        $data = $this->config->item('data');

        // A list of all created stream ids, .. this will be used for 'relationship' types
        $stream_ids = array();

        // (1) Create all provided fields
        foreach ($streams as $stream_slug => $stream) {
          
          $about = (isset($stream['about']))?$stream['about']:null;

          if(! $stream_ids[$stream_slug] = $this->streams->streams->add_stream($stream['name'], 
                                                   $stream_slug, 
                                                   $this->namespace, 
                                                   $prefix, $about)) return false;

        }

        // (2) Create all provided streams
        foreach ($fields as $field_slug => $field) {

            $extra = (isset($field['extra']))?$field['extra']:null;

            // Process the $extra value for stream_id references (if any)
            if(!is_null($extra) && isset($extra['choose_stream'])){
                // Replace stream place holder (i.e. stream slug) with the actual stream id
                // ... find Special streams first
                $special_streams = array('profiles'=>3, 'def_page_fields'=>2,'blog'=>1);

                if(array_key_exists($extra['choose_stream'], $special_streams))
                    $extra['choose_stream'] = $special_streams[$extra['choose_stream']];
                else if(array_key_exists($extra['choose_stream'], $stream_ids ))
                    $extra['choose_stream'] = $stream_ids[$extra['choose_stream']];

            }

            if(!$this->streams->fields->add_field(array('name'          => $field['name'],
                                                        'slug'          => $field_slug,
                                                        'namespace'     => $this->namespace,
                                                        'type'          => $field['type'],
                                                        'extra'         => $extra

            ))) return false;
        }

        // (3) Assign fields to streams
        foreach ($streams as $stream_slug => $stream) {
            $fields = $stream['fields'];

            // Default values
            $fields['assign'] = isset($fields['assign'])?$fields['assign']:array();
            $fields['required'] = isset($fields['required'])?$fields['required']:array();
            $fields['unique'] = isset($fields['unique'])?$fields['unique']:array();
            $fields['title_column'] = isset($fields['title_column'])?$fields['title_column']:array();

            foreach ($fields['assign'] as $field_slug) {
                // Construct the assign data array
                $assign_data = array (
                    'required' => in_array($field_slug, $fields['required']),
                    'unique' => in_array($field_slug, $fields['unique']),
                    'title_column' => in_array($field_slug, $fields['title_column'])
                    );

              $this->streams->fields->assign_field($this->namespace, $stream_slug, $field_slug, $assign_data);
            }

        // Update the view_options
        $this->streams->streams->update_stream($stream_slug , $this->namespace, array(
            'view_options' => $fields['view_options']
        ));

        }

        // (4) Insert data ... also link data references if any (relationship fields)
        $ref = array();
        foreach ($data as $stream_slug => $data_array) {
            $ref[$stream_slug] = array();
            foreach ($data_array as $key => $entry_data) {
               // ----------------------------------------------------------
               // Parse entry data for feild references (relationship types)
               foreach ($entry_data as $field_slug => $value) {
                  // Loop over all previous data looking for possible reference (i.e. faqs.ref)
                  // With the following convention: 'stream_sulg@uniqu_ref'. [uniqu_ref == $_key]
                  foreach ($ref as $_stream_slug => $item) {
                    foreach ($item as $_key => $_id) {
                      $entry_data[$field_slug] = str_replace($_stream_slug.'@'.$_key , $_id, $entry_data[$field_slug]);
                    }
                  }
               }
               // ----------------------------------------------------------
               $ref[$stream_slug][$key] = $this->streams->entries->insert_entry($entry_data,  $stream_slug, $this->namespace);
            }
        }
        return true;
    }

    /**
     * Uninstall module including the entire module streams namespace
     *
     */
    
    public function uninstall(){

        $this->load->driver('Streams');
        
        $this->streams->utilities->remove_namespace($this->namespace);

        return true;
    }

    public function upgrade($old_version){

        return true;
    }

    public function help(){

        return "There's no documentation provided for this module.";
    }

}