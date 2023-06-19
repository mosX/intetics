<?php
    use App\Facade;
    use App\Controllers\Controller;

    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
    @date_default_timezone_set('Europe/Kiev');

    const DS = DIRECTORY_SEPARATOR;
    const BASE_DIR = __DIR__;

    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_NAME = 'intetics';

    spl_autoload_register(function ($class) {    
        include './'.str_replace('\\', '/', $class) . '.php';    
    });

    $facade = new Facade();
    $facade->run();

    function redirect( $url) {
        header("Location: ". $url);
        exit();
    }
?>