<?php
if (isset($_SERVER['COMPOSER_DIR'])) {

    /*require_once $_SERVER['COMPOSER_DIR'] . '/autoload.php';*/


    spl_autoload_register(function($class) {
        if (0 === strpos($class, 'Bumz\\ShortUrlBundle\\')) {
            $path = __DIR__.'/../'.implode('/', array_slice(explode('\\', $class), 2)).'.php';
            if (!stream_resolve_include_path($path)) {
                return false;
            }
            require_once $path;
            return true;
        }
    });

    require_once __DIR__ . '/Fixtures/app/autoload.php';


} elseif (isset($_SERVER['KERNEL_DIR'])) {
    $vendorDir = __DIR__.'/../../../../../vendor';
    require_once $vendorDir . '/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

    require_once $_SERVER['KERNEL_DIR'] . '/autoload.php';
}