<?php
// Importar configuración y clases necesarias
require_once(dirname(__DIR__).'/config/const.php');

session_start(); // Asegurar que la sesión esté iniciada

if (isset($_SESSION['user_id']) && isset($_SESSION['user_collection_id']) && ($_SESSION['user_collection_id'] == $_SESSION['user_id'])) {
    // Validar y obtener el ID del juego de la URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $gameId = intval($_GET['id']);

        try {
            // Crear una nueva instancia de la clase PDO para la conexión a la base de datos
            $conn = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4;dbname=' . DBNAME, DB_USER, DB_PWD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Obtener la colección del usuario
            $stmtCollection = $conn->prepare("SELECT id FROM colecciones WHERE usuario = :user_id");
            $stmtCollection->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmtCollection->execute();
            $collection = $stmtCollection->fetch(PDO::FETCH_ASSOC);

            if ($collection) {
                // Preparar la consulta SQL para insertar el juego en la colección del usuario
                $sql = "INSERT INTO coleccion_juegos (coleccion, juego) VALUES (:coleccion, :juego)";
                $stmt = $conn->prepare($sql);

                // Ejecutar la consulta con los valores proporcionados
                $stmt->bindParam(':coleccion', $collection['id'], PDO::PARAM_INT);
                $stmt->bindParam(':juego', $gameId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    // Redirigir a la página de la colección del usuario después de registrar el juego
                    header("Location: /videogame_collection/resources/views/collections/view_collection.php?user={$_SESSION['user_id']}");
                    exit();
                } else {
                    // Manejar errores de ejecución
                    $errorMessage = "Error al registrar el juego en la colección.";
                }
            } else {
                // Manejar el caso donde la colección no se encuentra
                $errorMessage = "No se encontró la colección del usuario.";
            }
        } catch (PDOException $e) {
            // Manejar errores de conexión y de consulta
            error_log("Database Error: " . $e->getMessage(), 3, LOGS . 'errors.log');
            $errorMessage = "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        // Manejar el caso donde el ID del juego no es válido
        $errorMessage = "ID de juego inválido.";
    }

    // Redirigir con el mensaje de error si ocurrió algún problema
    header("Location: videogame_collection/resources/views/collections/view_collection.php?user={$_SESSION['user_id']}&result=error&msg=" . urlencode($errorMessage));
    exit();
} else {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: " . BASE_URL);
    exit();
}
?>
