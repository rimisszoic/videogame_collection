<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require_once('/videogame_collection/config/const.php');

require_once(MODELS."Connection.php");

class ViewCollectionController
{
    public function registerGame()
    {
        global $pdo; // Variable global para acceder a la conexión PDO

        // Verificar si se ha enviado el formulario para registrar un juego
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Obtener los datos del formulario
            $gameTitle = $_POST["game_title"];
            $platform = $_POST["platform"];
            $genre = $_POST["genre"];
            $releaseDate = $_POST["release_date"];
            $cover = ""; // Variable para almacenar la ruta de la portada
            $gallery = []; // Array para almacenar las rutas de la galería de imágenes

            // Permitir ciertos formatos de imagen
            $allowedTypes = array("jpg", "jpeg", "png", "gif");

            // Manejar la subida de la portada
            if (isset($_FILES["cover"])) {
                $targetDir = "uploads/";
                $coverName = basename($_FILES["cover"]["name"]);
                $targetFilePath = $targetDir . $coverName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($_FILES["cover"]["tmp_name"], $targetFilePath)) {
                        $cover = $targetFilePath;
                    } else {
                        echo "Error al subir la portada.";
                        return;
                    }
                } else {
                    echo "Formato de archivo no permitido para la portada.";
                    return;
                }
            }

            // Manejar la subida de la galería de imágenes
            if (isset($_FILES["gallery"])) {
                $targetDir = "uploads/gallery/";
                foreach ($_FILES["gallery"]["tmp_name"] as $key => $tmp_name) {
                    $galleryName = basename($_FILES["gallery"]["name"][$key]);
                    $targetFilePath = $targetDir . $galleryName;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                    if (in_array($fileType, $allowedTypes)) {
                        if (move_uploaded_file($_FILES["gallery"]["tmp_name"][$key], $targetFilePath)) {
                            $gallery[] = $targetFilePath;
                        } else {
                            echo "Error al subir la imagen de la galería.";
                            return;
                        }
                    } else {
                        echo "Formato de archivo no permitido para la galería.";
                        return;
                    }
                }
            }

            // Preparar la consulta SQL para insertar el juego en la base de datos
            $sql = "INSERT INTO juegos (nombre, genero, plataforma, fecha_lanzamiento, portada) VALUES (?, ?, ?, ?, ?)";

            // Ejecutar la consulta preparada
            if ($stmt = $pdo->prepare($sql)) {
                // Ejecutar la consulta con los valores proporcionados
                if ($stmt->execute([$gameTitle, $genre, $platform, $releaseDate, $cover])) {
                    // Obtener el ID del juego insertado
                    $lastInsertedId = $pdo->lastInsertId();

                    $this->addGameToCollection($lastInsertedId);
                    // Redirigir a la página de la colección del usuario o mostrar un mensaje de éxito
                    header("Location: view_collection.php?user={$_SESSION['user_id']}");
                    exit();
                } else {
                    echo "Error al ejecutar la consulta para registrar el juego.";
                }
            } else {
                echo "Error al preparar la consulta para registrar el juego.";
            }
        }
    }

    public function getGames()
    {
        global $pdo; // Variable global para acceder a la conexión PDO

        // Preparar la consulta SQL para obtener los juegos de la base de datos
        $sql = "SELECT * FROM juegos";

        // Ejecutar la consulta
        $stmt = $pdo->query($sql);

        // Obtener los resultados de la consulta
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar los juegos en la página
        foreach ($games as $game) {
            echo "<div class='game'>";
            echo "<h2>" . $game['nombre'] . "</h2>";
            echo "<p>Plataforma: " . $game['plataforma'] . "</p>";
            echo "<p>Género: " . $game['genero'] . "</p>";
            echo "<p>Fecha de lanzamiento: " . $game['fecha_lanzamiento'] . "</p>";
            echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#gameModal{$game['id']}'>Ver detalles</button>";
            echo "</div>";

            // Modal para mostrar la información detallada del juego
            echo "<div class='modal fade' id='gameModal{$game['id']}' tabindex='-1' role='dialog' aria-labelledby='gameModalLabel{$game['id']}' aria-hidden='true'>";
            echo "<div class='modal-dialog' role='document'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='gameModalLabel{$game['id']}'>{$game['nombre']}</h5>";
            echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
            echo "<span aria-hidden='true'>&times;</span>";
            echo "</button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<img src='{$game['portada']}' alt='Portada del juego' style='max-width: 100%;'>";
            echo "<div class='gallery'>";

            // Obtener las imágenes de la galería del juego
            $sql = "SELECT ig.imagen FROM imagenes_galeria ig JOIN juegos_galeria jg ON ig.id = jg.imagen_id WHERE jg.juego_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$game['id']]);
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar las imágenes de la galería
            foreach ($images as $image) {
                echo "<img src='{$image['imagen']}' alt='Imagen de galería'>";
                echo "</div>"; // Cierre de la clase 'gallery'
                echo "</div>"; // Cierre de la clase 'modal-body'
                echo "<div class='modal-footer'>";
                echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>";
                echo "</div>"; // Cierre de la clase 'modal-footer'
                echo "</div>"; // Cierre de la clase 'modal-content'
                echo "</div>"; // Cierre de la clase 'modal-dialog'
                echo "</div>"; // Cierre de la clase 'modal fade'
            }
        }
    }

    public function addGameToCollection(){
    }
}

?>
