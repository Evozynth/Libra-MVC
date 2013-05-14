<?php
/**
 * Site configuration, this file is changed by user per site.
 *
 */

/*
 * Set level of error reporting
 */
error_reporting(-1);
ini_set('display_errors', 1);


/**
 * Set what to show as debug or developer information in the get_debug() theme helper.
 */
$li->config['debug']['libra'] = false;
$li->config['debug']['session'] = false;
$li->config['debug']['db-num-queries'] = true;
$li->config['debug']['db-queries'] = true;
$li->config['debug']['timer'] = true;


/**
 * What type of urls should be used?
 * 
 * default      = 0     => index.php/controller/method/arg1/arg2/arg3
 * clean        = 1     => controller/method/arg1/arg2/arg3
 * querystring  = 2     => index.php?q=controller/method/arg1/arg2/arg3
 */
$li->config['url_type'] = 1;

/**
 * Set a base_url to use another than the default calculated
 */
$li->config['base_url'] = null;

/**
 * Define a routing table for urls.
 * 
 * Route custom urls to a defined controller/method/arguments
 */
$li->config['routing'] = array(
    'home' => array('enabled' => true, 'url' => 'index/index'),
);

/*
 * Define session name
 */
$li->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
$li->config['session_key'] = 'libra';

/*
* Define server timezone
*/
$li->config['timezone'] = 'Europe/Stockholm';

/*
 * Define internal character encoding
 */
$li->config['character_encoding'] = 'UTF-8';

/*
 * Define language
 */
$li->config['language'] = 'en';

/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example:
 * the url 'developer/dump' would instatiate the controller with the key "developer", that is
 * CCDeveloper and call the method "dump" in that class. This process is managed in:
 * $li->FrontoControllerRoute();
 * which is called in the frontcontroller phase from index.php.
 */
$li->config['controllers'] = array(
    'index'        => array('enabled' => true, 'class' => 'CCIndex'),
    'theme'        => array('enabled' => true, 'class' => 'CCTheme'),
	'developer'    => array('enabled' => true, 'class' => 'CCDeveloper'),
	'guestbook'    => array('enabled' => true, 'class' => 'CCGuestbook'),
    'user'         => array('enabled' => true, 'class' => 'CCUser'),
    'acp'          => array('enabled' => true, 'class' => 'CCAdminControlPanel'),
    'content'      => array('enabled' => true, 'class' => 'CCContent'),
    'blog'         => array('enabled' => true, 'class' => 'CCBlog'),
    'page'         => array('enabled' => true, 'class' => 'CCPage'),
    'modules'      => array('enabled' => true, 'class' => 'CCModules'),
    'my'           => array('enabled' => true, 'class' => 'CCMycontroller')
);

/**
 * Define menus.
 * 
 * Create hardcoded menus and map them to a theme region through $li->config['theme'].
 */
$li->config['menus'] = array(
    'navbar' => array(
        'home'      => array('label' => 'Home', 'url' => 'home'),
        'modules'   => array('label' => 'Modules', 'url' => 'modules'),
        'content'   => array('label' => 'Content', 'url' => 'content'),
        'guestbook' => array('label' => 'Guestbook', 'url' => 'guestbook'),
        'blog'      => array('label' => 'Blog', 'url' => 'blog'),
    ),
    'my-navbar' => array(
        'home'      => array('label' => 'About Me', 'url' => 'my'),
        'blog'      => array('label' => 'My Blog', 'url' => 'my/blog'),
        'guestbook' => array('label' => 'Guestbook', 'url' => 'my/guestbook'),
    ),
);

/**
 * Settings for theme. The theme may have a parent theme.
 * 
 * When a parent theme is used the parent's functions.php will be included before the current
 * theme's functions.php. The parent stylesheet can be included in the current stylesheet
 * by an @import clause. See site/themes/mytheme for an example of a child/parent theme.
 * Template files can reside in the parent or current theme, the CLibra::ThemeEngineRender()
 * looks for the template-file in the current theme first, then it looks in the parent theme.
 * 
 * There are two useful theme helpers defined in themes/functions.php.
 *  theme_url($url): Prepends the current theme url to $url to make an absolute url.
 *  theme_parent_url($url): Prepends the parent theme url to $url to make an absolute url.
 * 
 * path: Path to current theme, relativily LIBRA_INSTALL_PATH, for example themes/grid or
 * site/themes/mytheme.
 * parent: Path to parent theme, same structure as 'path'. Can be left out or set to null.
 * stylesheet: The stylesheet to include, always part of the current theme, use @import to 
 * include the parent stylesheet.
 * template_file: Set the default template file, defaults to default.tpl.php.
 * regions: Array with all regions that the theme supports.
 * data: Array with data that is made available to the template file as variables.
 * 
 * The name of the stylesheet is also appended to the data-array, as 'stylesheet' and made
 * available to the template files.
 */
$li->config['theme'] = array(
    'path'          => 'site/themes/mytheme',
    //'path'          => 'themes/grid',
    'parent'        => 'themes/grid',
    'stylesheet'    => 'style.css',
    'template_file' => 'index.tpl.php',
    // A list of valid theme regions
    'regions' => array('navbar', 'flash', 'featured-first', 'featured-middle', 'featured-last',
                       'primary', 'sidebar', 'triptych-first', 'triptych-middle', 'triptych-last',
                       'footer-column-one', 'footer-column-two', 'footer-column-three', 'footer-column-four',
                       'footer',
                       ),
    'menu_to_region' => array('my-navbar' => 'navbar'),
    // Add static entries for use in the template file.
    'data' => array(
        'header'        => 'Libra',
        'slogan'        => 'A PHP-based MVC-inspired CMF',
        'favicon'       => 'logo_80x80.png',
        'logo'          => 'logo_80x80.png',
        'logo_width'    => 80,
        'logo_height'   => 80,
        'footer'        => '<p>Libra &copy; by Stanley Svensson (stanley.svensson(at)gmail.com) after an <a href="http://dbwebb.se/forum/viewtopic.php?f=14&t=46" target="_blank">extensive tutorial</a>.</p>',
    )
);

/**
 * Set database(s)
 */
$li->config['database'][0]['dsn'] = 'sqlite:' . LIBRA_SITE_PATH . '/data/.ht.sqlite';

/**
 * How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1
 */
$li->config['hashing_algorithm'] = 'sha1salt';

/**
 * Allow or dissallow creation of new user accounts.
 */
$li->config['create_new_users'] = true;
