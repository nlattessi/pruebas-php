<?php

require 'core/bootstrap.php';

// require Router::load('routes.php')
//     ->direct(Request::uri());

// $r = new R;

$app['router']->get('', function() {
    echo 'Not found';
});

$app['router']->get('/', function () {
    echo 'Home';
});

$app['router']->get('/users', function () use ($app) {
    $responseData = $app['database']->selectAll('users');

    header('Content-Type: application/json');
    echo json_encode($responseData);
});

$app['router']->post('/users', function () use ($app) {
    
    header('Content-Type: application/json');
    echo json_encode("post");
});

$app['router']->get('/users/(\d+)', function($id) use ($app) {
    $data = $app['database']->selectById('users', $id);
    
    header('Content-Type: application/json');
    echo json_encode($data);
});

try {
    $app['router']->execute();    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
