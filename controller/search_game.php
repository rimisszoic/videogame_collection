<?php
// Importar configuración y clases necesarias
require_once(dirname(__DIR__).'/config/const.php');
require_once(dirname(__DIR__).'/model/Connection.php');

// Verificar si se recibió un término de búsqueda
if(isset($_POST['search_query'])) {
    // Sanitizar el término de búsqueda
    $search_query = trim($_POST['search_query']);
    try {
        // Crear una nueva instancia de la clase Connection
        $conn=new PDO('mysql:host='.DB_HOST.';charset=utf8mb4;dbname='.DBNAME, DB_USER, DB_PWD);

        // Preparar la consulta SQL para buscar juegos que coincidan con el término de búsqueda
        $sql = "SELECT j.id, j.nombre as juego_nombre, p.nombre as plataforma, g.nombre as genero, j.fecha_lanzamiento, j.portada 
        FROM juegos j join plataformas p on j.plataforma = p.id join generos g on j.genero = g.id
        WHERE j.nombre LIKE :search_query";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados de la consulta como un array asociativo
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $game = new stdClass();
            $game->id = $row['id'];
            $game->juego_nombre = $row['juego_nombre'];
            $game->plataforma = $row['plataforma'];
            $game->genero = $row['genero'];
            $game->fecha_lanzamiento = $row['fecha_lanzamiento'];
            $game->portada = $row['portada'];
            $results[] = $game;
        }
        if($stmt->rowCount() == 0) {
            // Si no se encontraron resultados, devolver un mensaje de error
            echo json_encode(array('error' => 'No se encontraron resultados para la búsqueda: ' . $search_query));
            exit();
        }

        // Devolver los resultados en formato JSON
        echo json_encode($results);
    } catch (Exception $e) {
        // Manejar errores
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(array('error' => 'Error al realizar la búsqueda: ' . $e->getMessage()));
    } finally {
        // Cerrar la conexión
        if($conn != null) {
            $conn = null;
        }
    }
} else {
    // Si no se recibió un término de búsqueda, devolver un error
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => 'Se requiere un término de búsqueda.'));
}
?>
