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
	'developer'    => array('enabled' => true, 'class' => 'CCDeveloper'),
	'guestbook'    => array('enabled' => true, 'class' => 'CCGuestbook'),
    'user'         => array('enabled' => true, 'class' => 'CCUser'),
    'acp'          => array('enabled' => true, 'class' => 'CCAdminControlPanel'),
    'content'      => array('enabled' => true, 'class' => 'CCContent'),
    'blog'         => array('enabled' => true, 'class' => 'CCBlog'),
    'page'         => array('enabled' => true, 'class' => 'CCPage'),
);

/**
 * Settings for theme.
 */
$li->config['theme'] = array(
    // The name of the theme in the theme directory
    'name' => 'core',
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
