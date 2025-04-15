<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Illuminate\Support\Collection;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    // Параметром передается базовая директория, в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);

//$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

 //$users = App\Generator::generate(100);


$links = [
    ['city'=>'Moscow', 'total'=>20000],
    ['city'=>'Paris', 'total'=>19843],
    ['city'=>'London', 'total'=>17890],
];

/*
$app->get('/companies', function ($request, $response)  use ($companies)  {
    $page = $request->getQueryParam('page', 1);
    $per = $request->getQueryParam('per', 5);
    $info = array_splice($companies, $page * $per, $per);
    $json = json_encode($info);

    return $response->write($json);
});

$app->get('/companies/{id}',function ($request,$response,$argument) use ($companies){
    $id = $argument['id'];
    $company =  collect($companies)->firstWhere('id',$id);

    if (!$company) {
        return $response->withStatus(404)->write('Page not found');
    }
    $json = json_encode($company);

    return $response->write($json);
});
*/

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/cities/{id}', function ($request, $response,$arguments) {
    $id = $arguments['id'];
    $query = $request->getQueryParams();
    $val = $query['val'] ?? null;

    $params = [
        'id' => $id,
       'val' => $val
    ];
    return $this->get('renderer')->render($response, 'cities/show.phtml',$params);
});


 $users = ['mike', 'mishel', 'adel', 'keks', 'kamila'];


$app->get('/users', function ($request, $response) use ($users) {

    $term = $request->getQueryParam('term');
    $filtered = array_filter($users, function ($item) use ($term) {
        if ($term !== '') {
            return str_contains($item, $term);
        }
        return true;
    });

    $usersPack = [
        'users'=> $filtered
    ];
    return $this->get('renderer')->render($response, 'users/index.phtml', $usersPack);
});
/*
$app->get('/users/{id}', function ($request, $response, $argument) use ($users) {

    $user = array_filter($users,function ($item) use ($argument){
        return $item['id'] == $argument['id'];
    });
    $key = array_keys($user)[0];

    $user = [
        'user' => $user[$key]
    ];


    return $this->get('renderer')->render($response, 'users/show.phtml', $user);
});
*/





$app->run();