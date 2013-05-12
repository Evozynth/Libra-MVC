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
     * Add text and optional variables.
     * 
     * @param string $string Content to be displayed.
     * @param array $variables Array containing the varibales that should be available for the included file.
     * @param string $region The theme region, uses string 'default' as default region.
     * @return $this.
     */
    public function AddString($string, $variables = array(), $region = 'default') {
        $this->views[$region][] = array('type' => 'string', 'string' => $string, 'variables' => $variables);
        return $this;
    }
    
    
    /**
     * Add a view as file to be included and optional variables
     * 
     * @param string $file Path to the file to be included.
     * @param array $variables Containing the variables that should be available for the included file.
     * @param string $region The theme region, uses string 'default' as defualt region.
     * @return $this.
     */
    public function AddInclude($file, $variables = array(), $region = 'default') {
        $this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
        return $this;
    }
    
    /**
     * Check if there exists views for a specific region.
     * 
     * @param string/array $region The theme region(s).
     * @return boolean true if region has views, else false.
     */
    public function RegionHasView($region) {
        if (is_array($region)) {
            foreach ($region as $val) {
                if (isset($this->views[$val])) {
                    return true;
                }
            }
            return false;
        } else {
            return (isset($this->views[$region]));
        }
    }
    
    /**
     * Render all views according to their type.
     * 
     * @param string $region The region to render views for.
     */
    public function Render($region = 'default') {
        if (!isset($this->views[$region])) return;
        foreach ($this->views[$region] as $view) {
            switch ($view['type']) {
                case 'include':
                    extract($view['variables']);
                    include($view['file']);
                    break;
                case 'string':
                    extract($view['variables']);
                    echo $view['string'];
                    break;
            }
        }
    }
    
    public function AddStyle($value) {
        if (isset($this->data['inline_style'])) {
            $this->data['inline_style'] .= $value;
        } else {
            $this->data['inline_style'] = $value;
        }
        return $this;
    }
    
}
