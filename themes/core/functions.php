<?php
/**
 * Helpers for the template file.
 */
$li->data['header'] = '<h1>Header: Libra</h1>';
$li->data['footer'] = '<p>Footer: &copy; Libra by Stanley Svensson (stanleysvensson.com)</p>';

/**
 * Print debuginformation from the framework 
 */
function get_debug() {
	$li = CLibra::Instance();
	$html = '<h2>Debuginformation</h2><hr>
             <p>The content of the config array:</p><pre>'      . htmlentities(print_r($li->config, true))  . '</pre>'; 
    $html .= '<hr><p>The content of the data array: </p><pre>'  . htmlentities(print_r($li->data, true))    . '</pre>';
    $html .= '<hr><p>The content of the request array:</p><pre>'. htmlentities(print_r($li->request, true)) . '</pre>';
    return $html;
}
