<?php

// Define las constantes del sistema
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/videogame_collection/');
define('MODEL', ROOT . 'model/');
define('VIEW', ROOT . 'resources/views/'); // Corregido el nombre del directorio de vistas
define('CONTROLLER', ROOT . 'controller/');
define('CSS', '/videogame_collection/resources/css/');
define('JS', '/videogame_collection/resources/js/');
define('IMG', '/videogame_collection/resources/images/');
define('BASE_URL', '/videogame_collection/');
define('BOOTSTRAP', 'videogame_collection/resources/bootstrap/');
define('CONFIG', ROOT . 'config/');

// Función para codificar una URL (si es necesario)
function encode_url($url) {
    return urlencode($url);
}

// Función para redirigir a una URL
function redirect($url, $result = 'ok', $msg = '') {
    $location = BASE_URL . $url . '?result=' . $result . '&msg=' . urlencode($msg);
    header('Location: ' . encode_url($location));
    exit();
}

// Función para verificar si una sesión está iniciada
function is_logged_in() {
    return isset($_SESSION['id']);
}

// Función para obtener el ID del usuario actual
function get_user_id() {
    return $_SESSION['id'] ?? null;
}

// Función para obtener el nombre de usuario actual
function get_user_name() {
    return $_SESSION['name'] ?? '';
}

?>
