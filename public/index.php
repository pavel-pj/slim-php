<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

 $companies = App\Generator::generate(100);

$links = [
    ['city'=>'Moscow', 'total'=>20000],
    ['city'=>'Paris', 'total'=>19843],
    ['city'=>'London', 'total'=>17890],
];


$app->get('/companies', function ($request, $response)  use ($companies)  {
    $page = $request->getQueryParam('page', 1);
    $per = $request->getQueryParam('per', 5);
    $info = array_splice($companies, $page * $per, $per);
    $json = json_encode($info);

    return $response->write($json);
});




$app->run();