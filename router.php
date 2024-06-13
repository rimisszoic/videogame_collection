<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require_once('config/const.php');
require_once(CONTROLLERS . 'UserController.php');
require_once(CONTROLLERS . 'UserProfileController.php');

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

// Verifica si se ha especificado una acción
if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET'){
    // Maneja la acción según el valor del parámetro 'action'
    switch($_REQUEST['action']){
        case 'login':
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                handle_request('UserController', 'login');
            } else {
                header('Location: '.BASE_URL);
            }
        case 'logout':
            if($_SERVER['REQUEST_METHOD'] == 'GET' && isAuthenticated()){
                handle_request('UserController', 'logout');
            }
            break;
        case 'register':
            if($_SERVER['REQUEST_METHOD'] == 'POST' && !isAuthenticated()){
                // handle_request('UserController', 'register');
                $userController = new UserController();
                $userController->register();
            } else {
                header('Location: '.BASE_URL);
            }
            break;
        case 'update-profile':
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isAuthenticated()){
                handle_request('UserProfileController', 'updateProfile');
            } else {
                header('Location: '.BASE_URL);
            }
            break;
        case 'delete-account':
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isAuthenticated()){
                handle_request('UserProfileController', 'deleteProfile');
                break;
            } else {
                header('Location: '.BASE_URL);
            }
            break;
        case 'user-profile':
            if($_SERVER['REQUEST_METHOD'] == 'GET' && isAuthenticated()){
                handle_request('UserProfileController', 'index');
            }
            break;
        case 'update-profile':
            if(isAuthenticated() && $_SERVER['REQUEST_METHOD'] == 'POST'){
                handle_request('UserProfileController', 'update');
            }
            break;
        case 'delete-account':
            if(isAuthenticated() && $_SERVER['REQUEST_METHOD'] == 'POST'){
                handle_request('UserProfileController', 'deleteProfile');
            }
            break;
        case 'user-profile':
            if(isAuthenticated() && $_SERVER['REQUEST_METHOD'] == 'GET'){
                handle_request('UserProfileController', 'index');
            } else {
                header('Location: '.BASE_URL);
            }
            break;
        default:
            show_404();
            break;
    }
}
?>