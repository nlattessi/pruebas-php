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
    $insertData = [];

    if (isset($_POST['name']) && $_POST['name'] != "") {

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

        if ($name == "") {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'name field has errors.']);
        }

        $insertData['name'] = $name;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'name field is missing.']);
    }

    if (isset($_POST['address']) && $_POST['address'] != "") {
        $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);

        if ($address == "") {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'address field has errors.']);
        }

        $insertData['address'] = $address;
    }

    $user = $app['database']->create('users', $insertData);

    header('Content-Type: application/json');
    echo json_encode($user);
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
