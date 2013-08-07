<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__.'/../vendor/autoload.php';

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

$loader->add( 'PhpThumb_', __DIR__.'/../vendor/phpthumb/lib' );
$loader->add( 'QqFile_', __DIR__.'/../vendor/protonlabs/lib' );
$loader->add( 'ProtonLabs_', __DIR__.'/../vendor/protonlabs/lib' );
$loader->add( 'GoogleApi_', __DIR__.'/../vendor/protonlabs/lib' );
$loader->add( 'TmhOAuth_', __DIR__.'/../vendor/protonlabs/lib' );
$loader->add( 'PayPal_', __DIR__.'/../vendor/protonlabs/lib' );
$loader->add( 'MobileDetect_', __DIR__.'/../vendor/protonlabs/lib' );

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
