<?php
/**
 * A test controller for themes.
 * 
 * @package LibraCore
 */
class CCTheme extends CObject implements IController {
    /**
     * Contructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function Index() {
        $this->views->SetTitle('Theme')
                    ->AddInclude(__DIR__.'/index.tpl.php', array(
                       'theme_name' => $this->config['theme']['name'],
                    ));
    }
}