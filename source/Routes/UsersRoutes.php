<?php declare(strict_types=1);

namespace Source\Routes;

use Source\Controllers\UsersController;
use Source\Controllers\ErrorController;

$router->group('/users', function ($router) {
    $users = new UsersController();
    $router->get('/', [$users , 'getAll']);
    $router->get('/{id:number}', [$users, 'get']);
    $router->get('/create', [$users, 'show']);
    $router->post('/create', [$users, 'post']);
    $router->get('/update/{id:number}', [$users, 'edit']);
    $router->put('/update/{id:number}', [$users, 'update']);
    $router->delete('/delete/{id:number}', [$users, 'delete']);
});
