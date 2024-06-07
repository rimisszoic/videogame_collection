<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require_once("Connection.php");

class Collection
{
    private int $userId;
    private string $username;
    private array $collections;
    private int $numberOfGames;

    public function __construct(int $userId=0, string $username="", array $collections=[], int $numberOfGames=0)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->collections = $collections;
        $this->numberOfGames = $numberOfGames;
    }

    public function getCollections()
    {
        try {
            $conn = new Connection();
            $conn = $conn->connect();
            $stmt = $conn->prepare("
                SELECT c.id, c.usuario, u.nombre_usuario, count(cj.juego) as numero_juegos
                FROM colecciones c
                JOIN usuarios u ON c.usuario = u.id
                JOIN coleccion_juegos cj ON c.id = cj.coleccion
                GROUP BY c.id, c.usuario, u.nombre_usuario
            ");
            $stmt->execute();
            while($collectionData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $collection = [
                    'id' => $collectionData['id_usuario'],
                    'userId' => $collectionData['usuario'],
                    'username' => $collectionData['nombre_usuario'],
                    'numberOfGames' => $collectionData['numero_juegos']
                ];
                $this->collections[] = $collection;
            }
        } catch (Exception $e) {
            header('Location: /videogame_collection/collections?result=error&msg=' . $e->getMessage());
        } finally {
            if ($conn != null) {
                $conn->close();
            }
        }
    }

    public function renderCollections()
    {
        if (empty($this->collections)) {
            echo '
            <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
                <div class="card text-center" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Sin Colecciones</h5>
                        <p class="card-text">No hay colecciones disponibles en este momento. ¡Crea una nueva colección para empezar a añadir juegos!</p>
                    </div>
                </div>
            </div>';
        } else {
            echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
            foreach ($this->collections as $collection) {
                echo '<div class="col">';
                echo '<div class="card h-100">';
                // Cambia la URL por la ruta de tu imagen de fondo
                echo '<div class="card-img-top" style="background-image: url(\'ruta_de_la_imagen\'); height: 200px; background-size: cover;"></div>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $collection['username'] . '</h5>';
                echo '<p class="card-text">Número de juegos: ' . $collection['numberOfGames'] . '</p>';
                echo '<a href="view_collection.php?user=' . $collection['id_usario'] . '" class="btn btn-primary">Ver Colección</a>';
                echo '</div>'; // Fin de card-body
                echo '</div>'; // Fin de card
                echo '</div>'; // Fin de col
            }
            echo '</div>'; // Fin de row
        }
    }
}
?>
