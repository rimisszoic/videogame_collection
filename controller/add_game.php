<?php
// Importar configuración y clases necesarias
require_once(dirname(__DIR__).'/config/const.php');
if(isset($_SESSION['user_id']) && isset($_GET['id'])) {
    // Obtener el ID del juego de la URL
    $gameId = $_GET['id'];
    // Crear una nueva instancia de la clase Connection
    $conn=new PDO('mysql:host='.DB_HOST.';charset=utf8mb4;dbname='.DBNAME, DB_USER, DB_PWD);
    $stmtCollection = $conn->prepare("SELECT id FROM colecciones WHERE usuario = :user_id");
    $stmtCollection->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmtCollection->execute();
    $collection = $stmtCollection->fetch(PDO::FETCH_ASSOC);
    // Preparar la consulta SQL para insertar el juego en la colección del usuario
    $sql = "INSERT INTO coleccion_juegos (coleccion, juego) VALUES (?, ?)";
    // Ejecutar la consulta preparada
    if ($stmt = $conn->prepare($sql)) {
        // Ejecutar la consulta con los valores proporcionados
        if ($stmt->execute([$collection['id'], $gameId])) {
            // Redirigir a la página de la colección del usuario después de registrar el juego
            header("Location: ".VIEWS."collections/view_collection.php?user={$_SESSION['user_id']}");
            exit();
        } else {
            // Manejar errores
            $errorMessage = "Error al registrar el juego en la colección.";
            header("Location: ".VIEWS."collections/view_collection.php?user={$_SESSION['user_id']}&result=error&msg=" . urlencode($errorMessage));
            exit();
        }
    } else {
        // Manejar errores
        $errorMessage = "Error al preparar la consulta para registrar el juego en la colección.";
        header("Location: ".VIEWS."collections/view_collection.php?user={$_SESSION['user_id']}&result=error&msg=" . urlencode($errorMessage));
        exit();
    }
} else {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location:" .BASE_URL);
    exit();
}
?>