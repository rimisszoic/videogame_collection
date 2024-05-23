<?php
// router.php

// Incluye los archivos necesarios
include_once('config/const.php');
include_once(CONTROLLERS . 'UserController.php');
include_once(CONTROLLERS.'UserProfileController.php');

// Verifica si se ha especificado una acción
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
    // Maneja la acción según el valor del parámetro 'action'
    switch ($_REQUEST['action']) {
        case 'login':
            // Instancia el controlador de usuario y ejecuta el inicio de sesión
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $userController = new UserController();
                $userController->login();
            }
            break;
        case 'register':
            // Instancia el controlador de usuario y ejecuta el registro
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $userController = new UserController();
                $userController->register();
            }
            break;
        case 'logout':
            // Instancia el controlador de usuario y ejecuta el cierre de sesión
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $userController = new UserController();
                $userController->logout();
            }
            break;
        case 'user-profile':
            // Instancia el controlador de usuario y ejecuta la eliminación de cuenta
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $userController = new UserProfile();
                $userController->index();
            }
            break;
        case 'updateProfile':
            // Instancia el controlador de usuario y ejecuta la actualización de perfil
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $userController = new UserProfile();
                $userController->updateProfile();
            }
            break;
        case 'deleteAccount':
            // Instancia el controlador de usuario y ejecuta la eliminación de cuenta
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $userController = new UserController();
                $userController->deleteAccount();
            }
            break;
    }
}
?>