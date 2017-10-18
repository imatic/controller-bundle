<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

\error_reporting(E_ALL & ~E_USER_DEPRECATED);

if (\file_exists($loader_path = __DIR__ . '/../vendor/autoload.php')) {
    $loader = include $loader_path;
    AnnotationRegistry::registerLoader([$loader, 'loadClass']);
}
