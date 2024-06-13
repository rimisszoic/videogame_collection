<?php
require_once(dirname(__DIR__) . '/config/const.php');
require_once(dirname(__DIR__).'/model/Connection.php');

class ViewCollectionController {
    private $conn;

    public function __construct() {
        $this->conn = new Connection();
    }

    public function getGames() {
        try {
            // Verificar si se ha pasado el ID del usuario en la URL
            if (!isset($_GET['user'])) {
                echo "<div class='alert alert-danger' role='alert'>No se ha proporcionado el ID de usuario.</div>";
                return;
            }

            if($this->conn == null){
                $this->conn = new Connection();
            }
            $this->conn->connect();

            // Obtener el ID de usuario de la URL
            $user_id = $_GET['user'];

            
            // Preparar la consulta SQL para obtener los juegos de la colección del usuario
            $sql = "SELECT j.nombre AS juego, g.nombre AS genero, p.nombre AS plataforma, j.fecha_lanzamiento, j.portada
                    FROM juegos j
                    INNER JOIN generos g ON j.genero = g.id
                    INNER JOIN plataformas p ON j.plataforma = p.id
                    INNER JOIN coleccion_juegos cj ON j.id = cj.juego
                    INNER JOIN colecciones c ON cj.coleccion = c.id
                    WHERE c.usuario = :user_id";

            // Preparar la declaración
            $stmt = $this->conn->prepare($sql);

            // Vincular parámetros
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados de la consulta
            $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar si la colección está vacía
            if (empty($games)) {
                echo "<div class='container-fluid mt-3'>";
                echo "<div class='alert alert-info' role='alert'>La colección está vacía. ¡Agrega juegos para empezar!</div>";
                echo "</div>";
                return;
            }

            // Mostrar los juegos en forma de tabla
            echo "<h2 class='text-center mt-3 mb-3'>Colección de Juegos</h2>";
            echo "<div class='container-fluid d-flex justify-content-center mt-3 mb-3'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Juego</th>";
            echo "<th>Género</th>";
            echo "<th>Plataforma</th>";
            echo "<th>Fecha de Lanzamiento</th>";
            echo "<th>Portada</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($games as $game) {
                echo "<tr>";
                echo "<td>{$game['juego']}</td>";
                echo "<td>{$game['genero']}</td>";
                echo "<td>{$game['plataforma']}</td>";
                echo "<td>{$game['fecha_lanzamiento']}</td>";
                echo "<td><img src='{$game['portada']}' class='img-thumbnail cover-image' alt='Portada del juego' style='max-width: 100px;' data-toggle='modal' data-target='#gameModal'></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";

            // Modal para mostrar la imagen en tamaño completo
            echo "<div class='modal fade' id='gameModal' tabindex='-1' role='dialog' aria-labelledby='gameModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog' role='document'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='gameModalLabel'>Portada del Juego</h5>";
            echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
            echo "<span aria-hidden='true'>&times;</span>";
            echo "</button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<img id='modalCoverImage' src='' class='img-fluid' alt='Portada del juego'>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        } catch (Exception $e) {
            header('Location: '.VIEWS . 'collections/view_collection.php?user=' . $_GET['user'] . '&result=error&msg=' . urlencode($e->getMessage()));
            return false;
        } finally {
            if ($this->conn != null) {
                $this->conn = null;
            }
        }
    }

    public function getGenres() {
        try {
            if($this->conn == null) {
                $this->conn = new Connection();
            }
            $this->conn->connect();
            // Preparar la consulta SQL para obtener los géneros de los juegos
            $sql = "SELECT * FROM generos";

            // Preparar la declaración
            $stmt = $this->conn->prepare($sql);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados de la consulta
            $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar si la colección está vacía
            if (empty($genres)) {
                echo "<option value='' selected>No hay géneros disponibles</option>";
                return;
            } else {
                echo "<option value='' selected>Selecciona un género</option>";
                foreach ($genres as $genre) {
                    echo "<option value='{$genre['id']}'>{$genre['nombre']}</option>";
                }
            }
        } catch (Exception $e) {
            header('Location: '.VIEWS . 'collections/view_collection.php?result=error&msg=' . urlencode($e->getMessage()));
            return false;
        } finally {
            if ($this->conn != null) {
                $this->conn = null;
            }
        }
    }

    public function getPlatforms() {
        try {
            if($this->conn == null) {
                $this->conn = new Connection();
            }
            
            $this->conn->connect();
            // Preparar la consulta SQL para obtener las plataformas de los juegos
            $sql = "SELECT * FROM plataformas";

            // Preparar la declaración
            $stmt = $this->conn->prepare($sql);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados de la consulta
            $platforms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar si la colección está vacía
            if (empty($platforms)) {
                echo "<option value='' selected>No hay plataformas disponibles</option>";
                return;
            } else {
                echo "<option value='' selected>Selecciona una plataforma</option>";
                foreach ($platforms as $platform) {
                    echo "<option value='{$platform['id']}'>{$platform['nombre']}</option>";
                }
            }
        } catch (Exception $e) {
            header('Location: '.VIEWS . 'collections/view_collection.php?result=error&msg=' . urlencode($e->getMessage()));
            return false;
        } finally {
            if ($this->conn != null) {
                $this->conn = null;
            }
        }
    }
}
?>