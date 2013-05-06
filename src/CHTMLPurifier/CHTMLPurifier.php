<?php
/**
 * A wrapper for HTMLPurifier by Edward Z. Yang, http://htmlpurifier.org/
 * 
 * @package LibraCore
 */
class CHTMLPurifier {
    
    /**
     * Properties
     */
    public static $instance = null;
    
    /**
     * Purify it. Create an instance of HTMLPurifier if it doesn't exists.
     * 
     * @param string $text The dirty HTML.
     * @return string The clean HTML.
     */
    public static function Purify($text) {
        if (!self::$instance) {
            require_once(__DIR__.'/htmlpurifier-4.5.0-standalone/HTMLPurifier.standalone.php');
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Cache.DefinitionImpl', null);
            self::$instance = new HTMLPurifier($config);
        }
        return self::$instance->purify($text);
    }
}