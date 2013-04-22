<?php

/**
 * Main class for Libra, holds everything
 *
 * @package LibraCore
 */
class CLibra implements ISingleton {

	private static $instance = null;
	
	/**
	 * Constructor
	 */
	protected function __construct() {
		// include the site specific config.php and create a ref to $li to be used by config.php
		$li = &$this;
		require(LIBRA_SITE_PATH.'/config.php');
        
        // Start a named session
        session_name($this->config['session_name']);
        session_start();
        
        date_default_timezone_set($this->config['timezone']);
        
        if (isset($this->config['database'][0]['dsn'])) {
            $this->db = new CDatabase($this->config['database'][0]['dsn']);
        }
        
        // Create a container for all views and theme data
        $this->views = new CViewContainer();
	}
	
	/**
	 * Singleton pattern. Get the instance of the latest created object or create a new one.
	 * @return CLibra The instance of this class.
	 */
	public static function Instance() {
		if (self::$instance == null) {
			self::$instance = new CLibra();
		}
		return self::$instance;
	}

	/**
	 * Frontcontroller, check url and route to controllers.
	 */
	public function FrontControllerRoute() {
		// Take current url and divide it in controller, method and parameters
		$this->request = new CRequest();
		$this->request->Init($this->config['base_url']);
		$controller = $this->request->controller;
		$method = $this->request->method;
		$arguments = $this->request->arguments;
		
		$formatedMethod = str_replace(array('_','-'), '', $method);
		
		// Is the controller enabled in config.php?
		$controllerExists = isset($this->config['controllers'][$controller]);
		$controllerEnabled = false;
		$className = false;
		$classExists = false;
		
		if ($controllerExists) {
			$controllerEnabled = ($this->config['controllers'][$controller]['enabled'] == true);
			$className = $this->config['controllers'][$controller]['class'];
			$classExists = class_exists($className);
		}
		
		// Check if controller has a callable method in the controller class, if then call it
		if ($controllerExists && $controllerEnabled && $classExists) {
			$rc = new ReflectionClass($className);
			if($rc->implementsInterface('IController')) {
			    // Check if there is a callable method in the controller class, if then call it.
				if($rc->hasMethod($formatedMethod)) {
					$controllerObj = $rc->newInstance();
					$methodObj = $rc->getMethod($formatedMethod);
					$methodObj->invokeArgs($controllerObj, $arguments);
				} else {
					die("404. " . get_class() . " error: Controller does not contain method.");
				}
			}	else {
				die("404. " . get_class() . " error: Controller does not implement interface IController.");
			}
		} else {
			die('404. Page is not found');
		}
	
	
		$this->data['debug']  = htmlentities("REQUEST_URI - {$_SERVER['REQUEST_URI']}\n");
		$this->data['debug'] .= htmlentities("SCRIPT_NAME - {$_SERVER['SCRIPT_NAME']}\n");
	}
	
	/**
	 * Theme Engine Render, renders the views using the selected theme.
	 */
	public function ThemeEngineRender() {
	    // Get the paths and settings for the theme
	    $themeName = $this->config['theme']['name'];
        $themePath = LIBRA_INSTALL_PATH . "/themes/{$themeName}";
        $themeUrl  = $this->request->base_url . "themes/{$themeName}"; 
        
        // Add the stylesheet path to the $li->data array
        $this->data['stylesheet'] = "{$themeUrl}/style.css";
        
        // Include the global functions.php and the functions.php that are part of the theme
        $li = &$this;
        include(LIBRA_INSTALL_PATH . '/themes/functions.php');
        $functionsPath = "{$themePath}/functions.php";
        if (is_file($functionsPath)) {
            include $functionsPath;
        }
        
        // Extract $li->data to own variables and handover to the template file
        extract($this->data);
        extract($this->views->GetData());
        include("{$themePath}/default.tpl.php");
        
	}
	
}