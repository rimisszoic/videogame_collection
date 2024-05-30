<?php
require_once(MODELS.'Collection.php');
require_once(MODELS.'Game.php');

class CollectionsController {
    // Método para mostrar todas las colecciones
    public function showAllCollections() {
        // Instanciar el modelo de colecciones
        $collectionModel = new Collection();

        // Obtener todas las colecciones de los usuarios
        $collections = $collectionModel->getCollections(); // Modificado

        // Determinar si el usuario está autenticado o es visitante
        $isAuthenticated = isset($_SESSION['user_id']);

        // Incluir la vista para mostrar todas las colecciones
        require(VIEWS.'collections/index.php');
    }

    // Método para mostrar una colección específica
    public function showCollection($collectionId) {
        // Instanciar el modelo de colecciones
        $collectionModel = new Collection();

        // Obtener la colección específica
        $collection = $collectionModel->getCollection($collectionId); // Modificado

        // Determinar si el usuario está autenticado o es visitante
        $isAuthenticated = isset($_SESSION['user_id']);

        // Verificar si el usuario es propietario de la colección
        $isOwner = false;
        if ($isAuthenticated) {
            $userId = $_SESSION['user_id'];
            // Implementa la lógica para verificar si el usuario es propietario de la colección
        }

        // Obtener los juegos de la colección si el usuario es propietario o está autenticado
        $games = [];
        if ($isOwner || $isAuthenticated) {
            // Implementa la lógica para obtener los juegos de la colección
        }

        // Incluir la vista para mostrar la colección
        require(VIEWS.'collections/view_collection.php');
    }
}
?>