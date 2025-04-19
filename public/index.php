<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Illuminate\Support\Collection;
use DI\Container;
use App\Validator;
use App\CourseRepository;


// Старт PHP сессии
session_start();

$repo = new CourseRepository();
$validator = new Validator();


$container = new Container();
// Настройка настроек приложения



$container->set('renderer', function () {
    // Параметром передается базовая директория, в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});

$app = AppFactory::createFromContainer($container);


$app->addErrorMiddleware(true, true, true);


/*
$app->get('/foo', function ($req, $res) {
    // Добавление флеш-сообщения. Оно станет доступным на следующий HTTP-запрос.
    // 'success' — тип флеш-сообщения. Используется при выводе для форматирования.
    // Например, можно ввести тип success и отражать его зеленым цветом (на Хекслете такого много)


// Получаем flash-сообщения из контейнера
    $flash = $this->get('flash');
    $flash->addMessage('Test', 'This is a message');

    return $res->withRedirect('/bar');
});

$app->get('/bar', function ($req, $res) {
    // Извлечение flash-сообщений, установленных на предыдущем запросе
    $messages = $this->get('flash')->getMessages();
    print_r($messages); // => ['success' => ['This is a message']]

    $params = ['flash' => $messages];
    return $this->get('renderer')->render($res, 'bar.phtml', $params);
});

*/




$app->get('/courses', function ($request, $response) use ($repo) {
    $messages = $this->get('flash')->getMessages();

    $params = [
       'courses' => $repo->all(),
       'messages' =>$messages,
   ];

    return $this->get('renderer')->render($response, 'courses/index.phtml' ,$params);
});


$app->get('/courses/new', function ($request, $response) {


    $messages = $this->get('flash')->getMessages();
    $params = [
        'course' => [],
        'errors' => [],
        'message' =>$messages,
    ];

    return $this->get('renderer')->render($response, 'courses/new.phtml' ,$params   );
});

$app->post('/courses', function ($request, $response) use ($repo,$validator) {

    $course = $request->getParsedBodyParam('course');
    $errors = $validator->validate($course);
    $flash = $this->get('flash');
    if (count($errors) === 0) {
        $repo->save($course);

        $flash->addMessage('success', 'Успешная запись!');
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


