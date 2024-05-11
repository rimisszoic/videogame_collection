<?php
// router.php

// Incluye los archivos necesarios
require_once 'config/const.php';
require_once 'controller/UserController.php';

// Verifica si se ha especificado una acción
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    // Maneja la acción según el valor del parámetro 'action'
    switch ($_POST['action']) {
        case 'login':
            // Instancia el controlador de usuario y ejecuta el inicio de sesión
            $userController = new UserController();
            $userController->login();
            break;
        case 'register':
            // Instancia el controlador de usuario y ejecuta el registro
            $userController = new UserController();
            $userController->register();
            break;
    }
}
?>