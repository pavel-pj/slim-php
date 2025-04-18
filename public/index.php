<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Illuminate\Support\Collection;
use DI\Container;
use App\Validator;
use App\CourseRepository;



$repo = new CourseRepository();
$validator = new Validator();


$container = new Container();
// Настройка настроек приложения

$container->set('settings', function() {
    return [
        'displayErrorDetails' => true, // Включаем отображение ошибок
        // Другие настройки...
    ];
});

$container->set('renderer', function () {
    // Параметром передается базовая директория, в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);





$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});



$app->get('/courses', function ($request, $response) use ($repo) {
    $params = [
         'courses' => $repo->all()
   ];
    return $this->get('renderer')->render($response, 'courses/index.phtml' ,$params);
});


$app->get('/courses/new', function ($request, $response) {

    $params = [
        'course' => [],
        'errors' => []
    ];

    return $this->get('renderer')->render($response, 'courses/new.phtml' ,$params   );
});

$app->post('/courses', function ($request, $response) use ($repo,$validator) {

    $course = $request->getParsedBodyParam('course');
    $errors = $validator->validate($course);

    if (count($errors) === 0) {
        $repo->save($course);
        return $response->withRedirect('/courses', 302);
    }

    $params = [
        'course' => $course,
        'errors' => $errors
    ];
   return $this->get('renderer')->render(
       $response->withStatus(422),
       'courses/new.phtml', $params);
});


/*
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



*/



$app->run();