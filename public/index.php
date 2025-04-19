<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Illuminate\Support\Collection;
use DI\Container;
use App\Validator;
use App\CourseRepository;
use App\PostRepository;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
 use Slim\Exception\HttpExceptionInterface;


// Старт PHP сессии
session_start();

$repo = new CourseRepository();
$repoPosts = new PostRepository();
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


$app->get('/all',function ($request, $response ) use ($repoPosts) {

    var_dump($repoPosts->all());

    $params =[
        'posts'=> $repoPosts->all(),
        'nextPage' => ['page' => 1],
        'previousPage' => ['page' => 1]
    ];

    return $this->get('renderer')->render($response, 'posts/index.phtml' ,$params);
});

/*

$app->get('/posts',function ($request, $response) use ($repoPosts)   {
    $page = 0;
    $per = 5;
    $params = $request->getQueryParams();
    if  (isset($params['page'])) {
        $page = (int)$params['page'];
    }
    if  (isset($params['per'])) {
        $per = (int)$params['per'];

    }



    $posts = $repoPosts->all();
    $newPosts = [
        'carry-post' =>[],
        'iter' => 1
    ];
    $result = array_reduce($posts, function ($carry, $post) use ($page,$per) {


        $currentIter = $carry['iter'];

        if ($currentIter > ($page * $per) && $currentIter <= (($page + 1) * $per)){
            $carry['carry-post'][] = $post;
        }
        $carry['iter'] += 1;
        return $carry;

    },$newPosts);

    $postsPerPage = $result['carry-post'];
    $nextPage = 0;
    $previousPage = 0;

    if ( ($page+1) * $per < count($posts) ) {
        ($page > 0) ? $nextPage += $page+1 : $nextPage = 2;
    }
    if ($page - 1 > 0 ) {
        $previousPage = $page - 1;
    }

    $params =[
      'posts'=> $postsPerPage,
      'nextPage' => ['page' => $nextPage],
      'previousPage' => ['page' => $previousPage]
    ];

    return $this->get('renderer')->render($response, 'posts/index.phtml' ,$params);
});


$app->get('/posts/{id}',function ($request, $response, $args) use ($repoPosts) {

    $params = [
        'post' => $repoPosts->find($args['id'])
    ];

    return $this->get('renderer')->render($response, 'posts/show.phtml' ,$params);
});





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

$app->get('/courses/{id}', function ($request, $response, $args) use ($repo) {

    $course = $repo->find($args['id']);

    $params = [
        'course' => $course,

    ];

    return $this->get('renderer')->render($response, 'courses/show.phtml' ,$params);
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


