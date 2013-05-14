<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php.
 */

/**
 * Get list of tools.
 */
function get_tools() {
    global $li;
    return <<<EOD
<p>Tools:
<a href="http://validator.w3.org/check/referer">html5</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">css3</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css21">css21</a>
<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">unicorn</a>
<a href="http://validator.w3.org/checklink?uri={$li->request->current_url}">links</a>
<a href="http://qa-dev.w3.org/i18n-checker/index?async=false&amp;docAddr={$li->request->current_url}">i18n</a>
<!-- <a href="link?">http-header</a> -->
<a href="http://csslint.net/">css-lint</a>
<a href="http://jslint.com/">js-lint</a>
<a href="http://jsperf.com/">js-perf</a>
<a href="http://www.workwithcolor.com/hsl-color-schemer-01.htm">colors</a>
<a href="http://dbwebb.se/style">style</a>
</p>

<p>Docs:
<a href="http://www.w3.org/2009/cheatsheet">cheatsheet</a>
<a href="http://dev.w3.org/html5/spec/spec.html">html5</a>
<a href="http://www.w3.org/TR/CSS2">css2</a>
<a href="http://www.w3.org/Style/CSS/current-work#CSS3">css3</a>
<a href="http://php.net/manual/en/index.php">php</a>
<a href="http://www.sqlite.org/lang.html">sqlite</a>
<a href="http://www.blueprintcss.org/">blueprint</a>
</p>
EOD;
}

/**
 * Print debuginformation from the framework 
 */
function get_debug() {
    // Only if debug is wanted.
    $li = CLibra::Instance();
    if (empty($li->config['debug'])) {
        return;
    }
    
    // Get the debug output.
    $html = null;
    if (isset($li->config['debug']['db-num-queries']) && $li->config['debug']['db-num-queries'] && isset($li->db)) {
        $flash = $li->session->GetFlash('database_numQueries');
        $flash = $flash ? "$flash + " : null;
        $html .= "<p>Database made $flash" . $li->db->GetNumQueries() . " queries.</p>";
    }
    if (isset($li->config['debug']['db-queries']) && $li->config['debug']['db-queries'] && isset($li->db)) {
        $flash = $li->session->GetFlash('database_queries');
        $queries = $li->db->GetQueries();
        if ($flash) {
            $queries = array_merge($flash, $queries);
        }
        $html .= "<p>Database made the following queries</p><pre>" . implode('<br><br>', $queries) . "</pre>";
    }
    if (isset($li->config['debug']['libra']) && $li->config['debug']['libra']) {
        $html = '<h2>Debuginformation</h2><hr>
                 <p>The content of the config array:</p><pre>'      . htmlentities(print_r($li->config, true))  . '</pre>'; 
        $html .= '<hr><p>The content of the data array: </p><pre>'  . htmlentities(print_r($li->data, true))    . '</pre>';
        $html .= '<hr><p>The content of the request array:</p><pre>'. htmlentities(print_r($li->request, true)) . '</pre>';
    }
    if (isset($li->config['debug']['session']) && $li->config['debug']['session']) {
        $html .= "<hr><h3>SESSION</h3><p>The content of CLibra->session:</p><pre>" . htmlent(print_r($li->session, true)) . "</pre>";
        $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
    }
    if (isset($li->config['debug']['timer']) && $li->config['debug']['timer']) {
        $html .= '<p>Page was loaded in ' . round(microtime(true) - $li->timer['first'], 5)*1000 . ' msecs</p>';
    }
    return $html;
}

/**
 * Check if region has views. Accepts variable amount of arguments as regions.
 * 
 * @param type $region
 */
function region_has_content($region = 'default' /*...*/) {
    return CLibra::Instance()->views->RegionHasView(func_get_args());
}

/**
 * Render all views
 * 
 * @param string $region The region to draw the content in.
 */
function render_views($region = 'default') {
    return CLibra::Instance()->views->Render($region);
}

/**
 * Get messages stored in flash session.
 */
function get_messages_from_session() {
    $messages = CLibra::Instance()->session->GetMessages();
    $html = null;
    if (!empty($messages)) {
        foreach ($messages as $val) {
            $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
            $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
            $html .= "<div class='$class'>{$val['message']}</div>\n";
        }
    }
    return $html;
}

/**
 * Create a url by prepending the base_url.
 */
function base_url($url = null) {
    return CLibra::Instance()->request->base_url . trim($url, '/');
}

/**
 * Create a url to an internal resource.
 * 
 * @param string $urlOrController The whole url to the controller. Leave empty for current controller.
 * @param string $method The method when specifying controller as first argument, else leave empty.
 * @param string $arguments The extra arguments to the method, leave empty if not using the method.
 */
function create_url($urlOrController = null, $method = null, $arguments = null) {
    return CLibra::Instance()->request->CreateUrl($urlOrController, $method, $arguments);
}

/**
 * Prepends the theme_url, which is the url to the current theme directory.
 * 
 * @param string $url The url-part to prepend
 * @return string The absolute url.
 */
function theme_url($url) {
    return create_url(CLibra::Instance()->themeUrl . "/{$url}");
}

function theme_parent_url($url) {
    return create_url(CLibra::Instance()->themeParentUrl . "/{$url}"); 
}

/**
 * Login menu. Creates a menu which reflects if user is logged in or not.
 */
function login_menu() {
    $li = CLibra::Instance();
    if ($li->user->IsAuthenticated()) {
        $items = '<a href="' . create_url('user/profile') . '"><img class="gravatar" src="'. get_gravatar(20) . '" alt="">' . $li->user['acronym'] . '</a> ';
        if ($li->user->IsAdministrator()) {
            $items .= '<a href="' . create_url('acp') . '">acp</a> ';
        }
        $items .= '<a href="' . create_url('user/logout') . '">logout</a>';
    } else {
        $items = '<a href="' . create_url('user/login') . '">login</a>';
    }
    return "<nav id='loginMenu'>$items</nav>";
}

/**
 * Get a gravatar based on the user's email.
 */
function get_gravatar($size = null) {
    return 'http://www.gravatar.com/avatar/' . 
    md5(strtolower(trim(CLibra::Instance()->user['email']))) . '.jpg' . ($size ? "?s=$size" : null);
}

/**
 * Escape data to make it safe to write to in the browser.
 * 
 * @param string $str String to escape.
 * @return string The escaped string.
 */
function esc($str) {
    return htmlent($str);
}

/**
 * Filter data according to a filter. Uses CMContent::Filter()
 * 
 * @param string $data The data-string to filter.
 * @param string $filter The filter to use.
 * @return string The filtered string.
 */
function filter_data($data, $filter) {
    return CMContent::Filter($data, $filter);
}