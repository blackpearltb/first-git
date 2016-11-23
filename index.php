<?php
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
        return false;
}
// Setup autoloading
require 'init_autoloader.php';
Zend\Mvc\Application::init(require 'config/application.config.php')->run();