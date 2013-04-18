<?php
//
// PHASE: BOOTSTRAP
//

define('LIBRA_INSTALL_PATH', dirname(__FILE__));
define('LIBRA_SITE_PATH', LIBRA_INSTALL_PATH.'/site');

require(LIBRA_INSTALL_PATH.'/src/CLibra/bootstrap.php');

$li = CLibra::Instance();

//
// PHASE: FRONTCONTROLLER ROUTE
//
$li->FrontControllerRoute();

//
// PHASE: THEME ENGINE RENDER
//
$li->ThemeEngineRender();