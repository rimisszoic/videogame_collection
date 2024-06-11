<?php
if(session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión de PHP
    session_start();
}

// Importar configuración y clases necesarias
require_once(dirname(__DIR__).'/config/const.php');

if(!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location:" .BASE_URL);
    exit();
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_SESSION['user_collection_id'] == $_SESSION['user_id'])) {

        // Obtener los datos del formulario
        $gameTitle = $_POST["game_title"];
        $platform = $_POST["platform"];
        $genre = $_POST["genre"];
        $releaseDate = $_POST["release_date"];
        $cover = ""; // Variable para almacenar la ruta de la portada
    
        // Permitir ciertos formatos de imagen
        $allowedTypes = array("jpg", "jpeg", "png", "gif");
    
        // Manejar la subida de la portada
        if (isset($_FILES["cover"])) {
            $targetDir = dirname(__FILE__)."/../resources/uploads/";
            $coverName = basename($_FILES["cover"]["name"]);
            $targetFilePath = $targetDir . $coverName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["cover"]["tmp_name"], $targetFilePath)) {
                    $cover = "/videogame_collection/resources/uploads/" . $coverName;
                } else {
                    $errorMessage = "Error al subir la portada.";
                    header("Location: /videogame_collection/resources/views/collections/view_collection.php?user={$_SESSION['user_id']}&result=error&msg=" . urlencode($errorMessage));
                    exit();
                }
            } else {
                $errorMessage = "Formato de archivo no permitido para la portada.";
                header("Location: /videogame_collection/resources/views/collections/view_collection.php?user={$_SESSION['user_id']}&result=error&msg=" . urlencode($errorMessage));
                exit();
            }
        }

        // Crear una nueva instancia de la clase Connection
        $conn=new PDO('mysql:host='.DB_HOST.';charset=utf8mb4;dbname='.DBNAME, DB_USER, DB_PWD);
    
        // Preparar la consulta SQL para insertar el juego en la base de datos
        $sql = "INSERT INTO juegos (nombre, genero, plataforma, fecha_lanzamiento, portada) VALUES (?, ?, ?, ?, ?)";
    
        // Ejecutar la consulta preparada
        if ($stmt = $conn->prepare($sql)) {
            // Ejecutar la consulta con los valores proporcionados
            if ($stmt->execute([$gameTitle, $genre, $platform, $releaseDate, $cover])) {
                // Obtener el ID del juego recién insertado
                $gameId = $conn->lastInsertId();
                registerGameInCollection($_SESSION['user_id'],$gameId);
                // Redirigir a la página de la colección del usuario después de registrar el juego
                header("Location: /videogame_collection/resources/views/collections/view_collection.php?user={$_SESSION['user_id']}");
                exit();
            } else {
                $errorMessage = "Error al ejecutar la consulta para registrar el juego.";
                header("Location: /videogame_collection/resources/views/collections/view_collection.php?user={$_SESSION['user_id']}&result=error&msg=" . urlencode($errorMessage));
                exit();
            }
        } else {
            $errorMessage = "Error al preparar la consulta para registrar el juego.";
            header("Location: ".VIEWS."colletions/view_collection.php?user={$_SESSION['user_id']}&result=error&msg=" . urlencode($errorMessage));
            exit();
        }
    }
    
}

function registerGameInCollection($user,$game){
    // Crear una nueva instancia de la clase Connection
    $conn=new PDO('mysql:host='.DB_HOST.';charset=utf8mb4;dbname='.DBNAME, DB_USER, DB_PWD);

    $sqlCollection="SELECT * FROM colecciones WHERE usuario=?";
    $stmtCollection=$conn->prepare($sqlCollection);
    $stmtCollection->execute([$user]);
    $collection=$stmtCollection->fetch(PDO::FETCH_ASSOC);

    // Preparar la consulta SQL para insertar el juego en la colección del usuario
    $sql = "INSERT INTO coleccion_juegos (coleccion, juego) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$collection['id'], $game]);

}
?>