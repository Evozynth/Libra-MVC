<?php
/**
 * Standard controller layout
 *
 * @package LibraCore
 */
class CCIndex extends CObject implements IController {
    
    /**
     * Constructor
     */
	public function __construct() {
        parent::__construct();
	}
	
	/**
	 * Implementing interface IController. All controllers must have an index action.
	 */
	public function Index() {
	    $this->Menu();
	}
    
    private function Menu() {
        $menu = array('index', 'index/index', 'guestbook', 'developer', 'developer/index', 'developer/links', 'developer/display-object');
        
        $html = null;
        foreach ($menu as $val) {
            $html .= "<li><a href=" . $this->request->CreateUrl($val) . ">$val</a></li>";
        }
        
        $this->data['title'] = "The index controller";
        $this->data['main'] = <<<EOD
<h1>The index controller</h1>
<p>This is what you can do for now.</p>
<ul>
{$html}
</ul>
EOD;
    }
	
}