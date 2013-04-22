<?php
/**
 * Holding an instance of CLibra to enable use of $this in subclasses.
 * 
 * @package LibraCore
 */
class CObject {
    public $config;
    public $request;
    public $data;
    public $db;
    
    /**
     * Constructor
     */
    protected function __construct() {
        $li = CLibra::Instance();
        $this->config = &$li->config;
        $this->request = &$li->request;
        $this->data = &$li->data;
        $this->db = &$li->db;
    }
}
