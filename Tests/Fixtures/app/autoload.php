<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$vendorDir = __DIR__.'/../../../vendor';
$loader = require_once $vendorDir . '/.composer/autoload.php';

// intl
if (!function_exists('intl_get_error_code')) {
    require_once $vendorDir . '/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';
    $loader->add(null, $vendorDir . '/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile($vendorDir . '/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once $vendorDir . '/swiftmailer/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload($vendorDir . '/swiftmailer/swiftmailer/lib/swift_init.php');

