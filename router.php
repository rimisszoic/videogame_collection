<?php
// router.php

// Incluye los archivos necesarios
require_once 'config/const.php';
require_once 'controller/UserController.php';

// Obtiene la ruta de la solicitud actual
$route = isset($_GET['route']) ? $_GET['route'] : '';

// Verifica si la solicitud es POST y proviene de un formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['HTTP_REFERER'])) {
    // URL del formulario de inicio de sesión
    $loginFormUrl = BASE_URL . '?route=login';
    // URL del formulario de registro
    $registerFormUrl = BASE_URL . '?route=register';
    
    // Verifica si la solicitud proviene del formulario de inicio de sesión o de registro
    if ($_SERVER['HTTP_REFERER'] === $loginFormUrl) {
        // Instancia el controlador de usuario y ejecuta el inicio de sesión
        $userController = new UserController();
        $userController->login();
        exit(); // Termina la ejecución después de procesar el inicio de sesión
    } elseif ($_SERVER['HTTP_REFERER'] === $registerFormUrl) {
        // Instancia el controlador de usuario y ejecuta el registro
        $userController = new UserController();
        $userController->register();
        exit(); // Termina la ejecución después de procesar el registro
    }
}

// Si la solicitud no proviene de un formulario o no coincide con las rutas esperadas, se permite que la página se cargue normalmente
?>