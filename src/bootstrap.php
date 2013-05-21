<?php

/**
 * Bootstrapping, setting up and loading the core.
 *
 * @package LibraCore
 */

/**
 * Enable auto-load of class declarations
 */
function autoload($aClassName) {
	$classFile = "/src/{$aClassName}/{$aClassName}.php";
	$file1 = LIBRA_SITE_PATH . $classFile;
	$file2 = LIBRA_INSTALL_PATH . $classFile;
	if (is_file($file1)) {
		require_once($file1);
	} elseif (is_file($file2)) {
		require_once($file2);
	}
}
spl_autoload_register('autoload');


/**
 * Set a default exception handler and enable logging in it.
 */
function exception_handler($exception) {
    echo "Libra: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString() . "</pre>";
}
set_exception_handler('exception_handler');

/**
 * Helper, wrap html_entities with correct character encoding
 */
function htmlent($str, $flags = ENT_COMPAT) {
    return htmlentities($str, $flags, CLibra::Instance()->config['character_encoding']);
}

/**
 * Helper, make clickable links from URLs in text.
 */
function makeClickable($text) {
    return preg_replace_callback(
            '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', create_function(
                    '$matches', 'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
            ), $text
    );
}

/**
 * Helper, BBCode formatting converting to HTML.
 *
 * @param string $text The text to be converted.
 * @return string The formatted text.
 */
function bbcode2html($text) {
    $search = array(
        '/\[b\](.*?)\[\/b\]/is',
        '/\[i\](.*?)\[\/i\]/is',
        '/\[u\](.*?)\[\/u\]/is',
        '/\[img\](https?.*?)\[\/img\]/is',
        '/\[url\](https?.*?)\[\/url\]/is',
        '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
    );
    $replace = array(
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<u>$1</u>',
        '<img src="$1" />',
        '<a href="$1">$1</a>',
        '<a href="$1">$2</a>'
    );
    return preg_replace($search, $replace, $text);
}

/**
 * Used in install process of .htaccess file to check session state.
 * 
 * @return array That holds information on style class and message
 */
function check_htaccess() {
    if (isset($_SESSION['htaccess']) && $_SESSION['htaccess'][0] == true) {
            $result = array('htaccess' => array('message' => 'Successfully created .htaccess in ' . $_SESSION['htaccess'][1] . '.', 'class' => 'success'));
        } elseif (isset($_SESSION['htaccess']) && $_SESSION['htaccess'][0] == false) {
            $result = array('htaccess' => array('message' => 'Failed to create .htaccess, is ' . $_SESSION['htaccess'][1] . ' write protected?', 'class' => 'error'));
        } else {
            $result = array();
        }
        unset($_SESSION['htaccess']);
        return $result;
}