<?php
use \Michelf\MarkdownExtra;

/**
 * CTextFilter provides functionality to filter user input from forms.
 * 
 * @package LibraCore
 */
class CTextFilter {
    
    /**
     * Properties
     */
    public static $instance = null;
    
    /**
     * Filters and formats data according to given filter options.
     * 
     * @param string $text The string to be filter and format.
     * @param array $filter The type of filters to use.
     * @return string The filtered data.
     */
    public static function Filter($text, $filter) {
        $markdown = false;
        foreach ($filter as $value) {
            switch (trim($value, ' ,')) {
                case 'markdown':
                    $text = self::Markdown($text);
                    $markdown = true;
                    break;
                case 'smartypants':
                    $text = self::SmartyPantsTypographer($text);
                    break;
                case 'clickable':
                    $text = self::MakeClickable($text);
                    break;
                case 'bbcode':
                    $text = self::Bbcode2html($text);
                    break;
                case 'htmlpurify':
                    $text = self::HtmlPurify($text);
                    break;
                case 'plain':
                default:  $text = htmlent($text);
                    break;
            }
        }
        return $markdown ? $text : nl2br($text);
    }
    
    /**
     * Make clickable links from URLs in text.
     *
     * @param string $text the text that should be formatted.
     * @return string with formatted anchors.
     */
    public static function MakeClickable($text) {
        return preg_replace_callback(
                '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', create_function(
                        '$matches', 'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
                ), $text
        );
    }
    
    /**
     * Convert markdown formatted text to html.
     * 
     * @param string $text The markdown text to format.
     * @return string as formatted html text.
     */
    public static function Markdown($text) {
        require_once(__DIR__.'/php-markdown/Michelf/Markdown.php');
        require_once(__DIR__.'/php-markdown/Michelf/MarkdownExtra.php');
        return MarkDownExtra::defaultTransform($text);
    }
    
    /**
     * Format text according to PHP SmartyPants Typographer.
     * 
     * @param string $text The text that should be formatted.
     * @return string as the formatted html-text.
     */
    public static function SmartyPantsTypographer($text) {
        require_once(__DIR__.'/php-smartypants-typographer/smartypants.php');
        return SmartyPants($text);
    }
    
    /**
     * Helper, BBCode formatting converting to HTML.
     *
     * @param string $text The text to be converted.
     * @return string The formatted text.
     */
    public static function Bbcode2html($text) {
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
     * Purify it. Create an instance of HTMLPurifier if it doesn't exists.
     * 
     * @param string $text The dirty HTML.
     * @return string The clean HTML.
     */
    public static function HtmlPurify($text) {
        if (!self::$instance) {
            require_once(__DIR__.'/htmlpurifier-4.5.0-standalone/HTMLPurifier.standalone.php');
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Cache.DefinitionImpl', null);
            $config->set('Attr.AllowedFrameTargets', array('_blank'));
            self::$instance = new HTMLPurifier($config);
        }
        return self::$instance->purify($text);
    }
   
}