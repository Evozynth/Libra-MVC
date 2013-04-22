<?php
/**
 * Holding an instance of CLibra to enable use of $this in subclasses.
 * 
 * @package LibraCore
 */
class CObject {
    /**
     * Memebers
     */
    public $config;
    public $request;
    public $data;
    public $db;
    public $views;
    
    /**
     * Constructor
     */
    protected function __construct() {
        $li = CLibra::Instance();
        $this->config = &$li->config;
        $this->request = &$li->request;
        $this->data = &$li->data;
        $this->db = &$li->db;
        $this->views = &$li->views;
    }
}
