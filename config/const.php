<?php

// Define las constantes del sistema
define('BASE_URL', '/videogame_collection/');
define('MODEL', 'model/');
define('VIEW', 'resources/views/'); // Corregido el nombre del directorio de vistas
define('CONTROLLER','controller/');
define('CSS','resources/css/');
define('JS','resources/js/');
define('IMG','resources/images/');
define('BOOTSTRAP','resources/bootstrap/'); // Modificada la constante BOOTSTRAP
define('CONFIG','config/');

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
