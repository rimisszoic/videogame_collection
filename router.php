<?php
// router.php

// Incluye los archivos necesarios
require_once 'config/const.php';
require_once 'controller/UserController.php';

// Verifica si se ha especificado una acción
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    // Maneja la acción según el valor del parámetro 'action'
    switch ($_REQUEST['action']) {
        case 'login':
            // Instancia el controlador de usuario y ejecuta el inicio de sesión
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $userController = new UserController();
                $userController->login();
                break;
            }
        case 'register':
            // Instancia el controlador de usuario y ejecuta el registro
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $userController = new UserController();
                $userController->register();
                break;
            }
        case 'logout':
            // Instancia el controlador de usuario y ejecuta el cierre de sesión
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $userController = new UserController();
                $userController->logout();
                break;
            }
    }
}
?>