<?php
session_start();
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//echo dirname(__FILE__);
//die();
error_reporting(0);
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Setup autoloading
require 'init_autoloader.php';
require './vendor/Classes/PHPExcel.php'; 
require './config/function.php';

//require './googlemail/phpmailer/class.phpmailer.php';
require './PHPMailer-master/PHPMailerAutoload.php';
// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
