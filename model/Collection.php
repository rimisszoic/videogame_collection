<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
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
        require_once(dirname(__DIR__).'/config/const.php');
        require_once(MODELS."Connection.php");
    
        try {
            $conn = new Connection();
            $conn->connect();
            $stmt = $conn->prepare("
                SELECT c.id, c.usuario, u.nombre_usuario, COUNT(cj.juego) AS numero_juegos
                FROM colecciones c
                JOIN usuarios u ON c.usuario = u.id
                LEFT JOIN coleccion_juegos cj ON c.id = cj.coleccion
                GROUP BY c.id, c.usuario, u.nombre_usuario
            ");
            $stmt->execute();
            while($collectionData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $collection = [
                    'userId' => $collectionData['usuario'],
                    'username' => $collectionData['nombre_usuario'],
                    'numberOfGames' => $collectionData['numero_juegos']
                ];
                $this->collections[] = $collection;
            }
        } catch (Exception $e) {
            header('Location: /videogame_collection/collections.php?result=error&msg=' . $e->getMessage());
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
                echo '<div class="card-img-top" style="background-image: url(\'/videogame_collection/resources/uploads/collection_back.png\'); height: 200px; background-size: cover;"></div>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $collection['username'] . '</h5>';
                echo '<p class="card-text">Número de juegos: ' . $collection['numberOfGames'] . '</p>';
                $collectionRoute=VIEWS.'collections/view_collection.php?user=' . $collection['userId'];
                echo '<a href="'.$collectionRoute.'" class="btn btn-primary">Ver Colección</a>';
                echo '</div>'; // Fin de card-body
                echo '</div>'; // Fin de card
                echo '</div>'; // Fin de col
            }
            echo '</div>'; // Fin de row
        }
    }
}
?>