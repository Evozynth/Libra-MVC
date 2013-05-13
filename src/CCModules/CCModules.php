<?php
/**
 * To manage and analyse all modules of Libra.
 * 
 * @package LibraCore
 */
class CCModules extends CObject implements IController {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Show an index-page and display what can be done through this controller.
     */
    public function Index() {
        $modules = new CMModule();
        $controllers = $modules->AvailableControllers();
        $allModules = $modules->ReadAndAnalys();
        $this->views->SetTitle('Manage Modules')
                    ->Addinclude(__DIR__.'/index.tpl.php', array('controllers' => $controllers), 'primary')
                    ->AddInclude(__DIR__.'/sidebar.tpl.php', array('modules' => $allModules), 'sidebar');
    }
    
    public function Install() {
        $modules = new CMModule();
        $results = $modules->Install();
        $allModules = $modules->ReadAndAnalys();
        $this->views->SetTitle('Install modules')
                    ->AddInclude(__DIR__.'/install.tpl.php', array('modules' => $results), 'primary')
                    ->AddInclude(__DIR__.'/sidebar.tpl.php', array('modules' => $allModules), 'sidebar');
    }
}