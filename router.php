<?php
// router.php

// Incluye los archivos necesarios
include_once('config/const.php');
include_once(CONTROLLERS . 'UserController.php');
include_once(CONTROLLERS.'UserProfileController.php');

// Obtiene la ruta solicitada y elimina los parámetros de consulta
$request_uri=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Verifica el método de solicitud
$method = $_SERVER['REQUEST_METHOD'];

function handle_request($controller, $method){
    if(class_exists($controller)){
        $controller_instance = new $controller();
        if(method_exists($controller_instance, $method)){
            $controller_instance->$method();
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    } else {
        http_response_code(404);
        echo '404 Not Found';
    }
}

// Maneja la ruta solicitada
switch($request_uri){
    case '/login':
        if($method == 'POST'){
            handle_request('UserController', 'login');
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    case '/register':
        if($method == 'POST'){
            handle_request('UserController', 'register');
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    case '/user/profile':
        if($method == 'GET'){
            handle_request('UserProfileController', 'index');
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    case '/user/profile/update':
        if($method == 'POST'){
            handle_request('UserProfileController', 'updateProfile');
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    case '/user/logout':
        if($method == 'GET'){
            handle_request('UserController', 'logout');
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    case '/collections':
        if($method == 'GET'){
            handle_request('CollectionsController', 'index');
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    default:
        http_response_code(404);
        echo '404 Not Found';
        break;
}