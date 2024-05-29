<?php
// router.php

// Inicia la sesión si no está iniciada
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

// Incluye los archivos necesarios
include_once('config/const.php');
include_once(CONTROLLERS . 'UserController.php');
include_once(CONTROLLERS.'UserProfileController.php');

// Obtiene la ruta solicitada y elimina los parámetros de consulta
$request_uri=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Verifica el método de solicitud
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Maneja la solicitud de la ruta solicitada y llama al método correspondiente
 * @param string $controller Nombre del controlador
 * @param string $method Nombre del método
 */
function handle_request($controller, $method){
    if(class_exists($controller)){
        $controller_instance = new $controller();
        if(method_exists($controller_instance, $method)){
            $controller_instance->$method();
        } else {
            show_404();
        }
    } else {
        show_404();
    }
}

/**
 * Muestra la página de error 404
 * @return void No devuelve nada
 */
function show_404(){
    http_response_code(404);
    include(RESOURCES.'/templates/404.html');
    exit();
}

/**
 * Verifica si el usuario está autenticado
 * @return bool Devuelve true si el usuario está autenticado, de lo contrario, false
 */
function isAuthenticated(){
    return isset($_SESSION['user_id']);
}

// Maneja la ruta solicitada
switch($request_uri){
    case '/videogame_collection/':
    case '/videogame_collection/index.php':
    case '/':
        break;
    case '/login':
        if($method == 'POST' && ($_POST['action'] == 'login')){
            handle_request('UserController', 'login');
        } else {
            show_404();
        }
        break;
    case '/register':
        if($method == 'POST' && ($_POST['action'] == 'register')){
            handle_request('UserController', 'register');
        } else {
            show_404();
        }
        break;
    case '/user/profile':
        if($method == 'GET' && isAuthenticated()){
            handle_request('UserProfileController', 'index');
        } else {
            show_404();
        }
        break;
    case '/user/profile/update':
        if($method == 'POST' && isAuthenticated()){
            handle_request('UserProfileController', 'updateProfile');
        } else {
            show_404();
        }
        break;
    case '/user/logout':
        if($method == 'GET' && isAuthenticated()){
            handle_request('UserController', 'logout');
        } else {
            show_404();
        }
        break;
    case '/collections':
        if($method == 'GET'){
            handle_request('CollectionsController', 'index');
        } else {
            show_404();
        }
        break;
    default:
        show_404();
        break;
}