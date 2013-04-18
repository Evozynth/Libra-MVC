<?php
/**
 * Controller for development and testing purpose, helpfull methods for the developer.
 * 
 * @package LibraCore
 */
class CCDeveloper implements IController {
    
    /**
     *  Implementing interface IController. All controllers must have an index action.
     */
    public function Index() {
        $this->Menu();
    }
    
    /**
     * Create a list of links in the supported ways.
     */
    public function Links() {
        $this->Menu();
        
        $li = CLibra::Instance();
        
        $url = 'developer/links';
        $current = $li->request->CreateUrl($url);
        
        $li->request->cleanUrl = false;
        $li->request->querystringUrl = false;
        $default = $li->request->CreateUrl($url);
        
        $li->request->cleanUrl = true;
        $clean = $li->request->CreateUrl($url);
        
        $li->request->cleanUrl = false;
        $li->request->querystringUrl = true;
        $querystring = $li->request->CreateUrl($url);
        
        $li->data['main'] .= <<<EOD
<h2>CRequest::CreateUrl()</h2>
<p>Here is a list of urls created using above method with variuos settings. All links shoudl lead to the same page.</p>
<ul>
    <li><a href="$current">This is the current setting</a></li>
    <li><a href="$default">This would be the default url</a></li>
    <li><a href="$clean">This should be a clean url</a></li>
    <li><a href="$querystring">This should be a querystring like url</a></li>
</ul>
<p>Enables variuos and flexible url-strategy.</p>
EOD;

    }

    /**
     * Create a method that shows the menu, same for all methods
     */
    private function Menu() {
           $li = CLibra::Instance();
           $menu = array('developer', 'developer/index', 'developer/links');
           
           $html = null;
           foreach ($menu as $val) {
               $html .= '<li><a href="' . $li->request->CreateUrl($val) . '">' . $val . "</a></li>\n";
           }
           
           $li->data['title'] = 'The Developer Controller';
           $li->data['main'] = <<<EOD
<h1>The Developer Controller</h1>
<p>This is what you can do now: </p>
<ul>
$html
</ul>
EOD;
    }

}
