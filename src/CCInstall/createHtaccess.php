<?php
/**
 * A script to create a .htaccess file for the installation
 */
session_name(preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]));
session_start();
// Figure out the dir that points to the site base url. Used for path in .htaccess
$dir = dirname($_SERVER['REQUEST_URI']);
$dir = rtrim(str_replace('src/CCInstall', "", $dir), '/');
//echo $dir;
//die();
$fileContents = <<<EOD
<IfModule mod_rewrite.c>
    RewriteEngine on
    # Must use RewriteBase on www.student.bth.se, Rewritebase for url /~stsv13/test is /~stsv13/test/
    RewriteBase $dir/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule (.*) index.php/$1 [NC,L]
</IfModule>
EOD;

$success = false;
$path = rtrim(str_replace('src'. DIRECTORY_SEPARATOR .'CCInstall', "", __DIR__), '/');
$file = $path.'/.htaccess';
//die();
if (is_writable($path)) {
    $fh = fopen($file, 'w');
        fwrite($fh, $fileContents);
        $success = true;
} else {
    echo "You dont have permissions to write .htaccess file in $dir";
}

if ($success) {
    $_SESSION['htaccess'] = array(true, $dir);
    //die();
    header("Location: {$dir}/index.php");
} else {
    $_SESSION['htaccess'] = array(false, $dir);
    //die();
    header("Location: {$dir}/index.php");
}