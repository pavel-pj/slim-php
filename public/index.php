<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$links = [
    ['city'=>'Moscow', 'total'=>20000],
    ['city'=>'Paris', 'total'=>19843],
    ['city'=>'London', 'total'=>17890],
];


$app->get('/users', function ($request, $response) {
    return $response->write('GET /users');
});

$app->post('/users', function ($request, $response) {
    return $response->write('POST /users');
});

$app->get('/pages', function ($request, $response) use ($links) {
    return $response->write(json_encode($links));
});



$app->run();