<?php

require __DIR__ . '/../../../bootstrap.php';
\umask(0007);

use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\TestKernel;
use Symfony\Component\HttpFoundation\Request;

$_SERVER['PHP_AUTH_USER'] = 'user';
$_SERVER['PHP_AUTH_PW'] = 'password';

$kernel = new TestKernel();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
