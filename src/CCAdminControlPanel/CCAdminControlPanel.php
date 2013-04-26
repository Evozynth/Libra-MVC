<?php
/**
 * Admin Control Panel
 * 
 * @package LibraCore
 */
class CCAdminControlPanel extends CObject implements IController {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Show Admin Control Panel
     */
    public function Index() {
        $this->views->SetTitle('ACP: Admin Control Panel');
        $this->views->AddInclude(__DIR__ . '/index.tpl.php');
    }
}
