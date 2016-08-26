<?php

require 'core/bootstrap.php';


// require Router::load('routes.php')
//     ->direct(Request::uri());

// $r = new R;

$app['router']->add('', function() {
    echo 'Not found';
});

$app['router']->add('/', function () {
    echo 'Home';
});

$app['router']->add('/users', function () use ($app) {
    $users = $app['database']->selectAll('users');

    header('Content-Type: application/json');
    echo json_encode($users);
});

$app['router']->execute();
