<?php
/**
 * A container to hold a bunch of views
 * 
 * @package LibraCore
 */
class CViewContainer {
    /**
     * Members
     */
    private $data = array();
    private $views = array();
    
    /**
     * Constructor
     */
    public function __construct() {
        ;
    }
    
    /**
     * Getters
     */
    public function GetData() { return $this->data; }
    
    /**
     * Set the title of the page
     * 
     * @param $value string to be set as title
     */
    public function setTitle($value) {
        return $this->SetVariable('title', $value);
    }
    
    /**
     * Set any vairable that should be available for the theme engine.
     * 
     * @param $key string name of the key
     * @param $value string to be set for the key 
     */
    public function SetVariable($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }
    
    
    /**
     * Add a view as file to be included and optional variables
     * 
     * @param $file string path to the file to be included.
     * @param $variables array containing the variables that should be available for the included file.
     */
    public function AddInclude($file, $variables = array()) {
        $this->views[] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
        return $this;
    }
    
    /**
     * Render all views according to their type.
     */
    public function Render() {
        foreach ($this->views as $view) {
            switch ($view['type']) {
                case 'include':
                    extract($view['variables']);
                    include($view['file']);
                    break;
            }
        }
    }
    
}
