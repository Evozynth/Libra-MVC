<?php
//
// PHASE: BOOTSTRAP
//

define('LIBRA_INSTALL_PATH', dirname(__FILE__));
define('LIBRA_SITE_PATH', LIBRA_INSTALL_PATH.'/site');

require(LIBRA_INSTALL_PATH.'/src/bootstrap.php');

$li = CLibra::Instance();

// Disable Admin Control Panel if not logged in as admin
if (!$li->user['hasRoleAdmin']) {
    $li->config['controllers']['acp']['enabled'] = false;
}

//
// PHASE: FRONTCONTROLLER ROUTE
//
$li->FrontControllerRoute();

//
// PHASE: THEME ENGINE RENDER
//
$li->ThemeEngineRender();