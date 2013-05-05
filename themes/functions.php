<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php.
 */

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
 * Create a url by prepending the base_url.
 */
function base_url($url) {
    return CLibra::Instance()->request->base_url . trim($url, '/');
}


/**
 * Render all views
 */
function render_views() {
    return CLibra::Instance()->views->Render();
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