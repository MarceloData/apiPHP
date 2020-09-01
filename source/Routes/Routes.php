<?php declare(strict_types=1);

namespace Source\Routes;

$request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$router = new \League\Route\Router;

require __DIR__.'/UsersRoutes.php';
require __DIR__.'/ContactsRoutes.php';
require __DIR__.'/ImagesRoutes.php';
require __DIR__.'/GroupsRoutes.php';
require __DIR__.'/PermissionsRoutes.php';
require __DIR__.'/HistorysRoutes.php';
require __DIR__.'/MessagesRoutes.php';

$router->dispatch($request);
