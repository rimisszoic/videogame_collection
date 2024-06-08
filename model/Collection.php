<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require_once("Connection.php");

class Collection
{
    private int $userId;
    private string $username;
    private array $games;

    public function __construct(int $userId=0, string $username="", array $games=[])
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->games = $games;
    }

    public function getCollections()
    {
        try{
            $conn=new Connection();
            $conn=$conn->connect();
            $stmt = $conn->prepare("SELECT cj.colecion, cj.juego, j.nombre, j.plataforma, j.genero, ");
            $stmt->execute();
            $collections=$stmt->fetch(PDO::FETCH_ASSOC);

            $this->games = [];
            foreach ($collections as $collection) {
                $this->id = $collection['id'];
                $this->name = $collection['nombre'];
                
                $game=new Game($collection['juego'], $collection['nombre'], $collection['plataforma'], $collection['genero'], $collection['fecha_lanzamiento'], $collection['portada']);
                $this->addCollection($game);
            }

            // Si la galería está definida en la base de datos, la creamos
            if($collections['galeria'] != null){
                $this->gallery = new Gallery($collections['galeria']);
                $this->gallery->getPictures();
            } else {
                $this->gallery = null;
            }
        } catch (Exception $e){
            header('Location: '.ROOT.'/collections?result=error&msg='.$e->getMessage());
        } finally{
            if($conn!=null){
                $conn->close();
            }
        }
    }

    public function getUserCollection(){
        try{
            $conn=new Connection();
            $conn=$conn->connect();
            $stmt = $conn->prepare("SELECT c.id, u.username, c.galeria, c.juego, j.nombre, jp.plataforma, j.genero, j.fecha_lanzamiento, jp.portada FROM coleccion c JOIN juegos_plataformas jp ON c.juego=jp.id JOIN juegos j ON jp.juego=j.id JOIN usuarios u ON c.usuario=u.id WHERE c.usuario=:user_id");
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();
            $collections=$stmt->fetch(PDO::FETCH_ASSOC);

            $this->games = [];
            foreach ($collections as $collection) {
                $game=new Game($collection['juego'], $collection['nombre'], $collection['plataforma'], $collection['genero'], $collection['fecha_lanzamiento'], $collection['portada']);
                $this->addGame($game);
            }

            // Si la galería está definida en la base de datos, la creamos
            if($collections['galeria'] != null){
                $this->gallery = new Gallery($collections['galeria']);
                $this->gallery->getPictures();
            } else {
                $this->gallery = null;
            }
        } catch (Exception $e){
            header('Location: '.ROOT.'/collections?result=error&msg='.$e->getMessage());
        } finally{
            if($conn!=null){
                $conn->close();
            }
        }
    }

    public function getCollection($id)
    {
        try{
            $conn=new Connection();
            $conn=$conn->connect();
            $stmt = $conn->prepare("SELECT c.id, u.username, c.galeria, c.juego, j.nombre, jp.plataforma, j.genero, j.fecha_lanzamiento, jp.portada FROM coleccion c JOIN juegos_plataformas jp ON c.juego=jp.id JOIN juegos j ON jp.juego=j.id JOIN usuarios u ON c.usuario=u.id WHERE c.id=:collection_id");
            $stmt->bindParam(':collection_id', $id);
            $stmt->execute();
            $collection=$stmt->fetch(PDO::FETCH_ASSOC);

            $this->games = [];
            foreach ($collection as $game) {
                $game=new Game($game['juego'], $game['nombre'], $game['plataforma'], $game['genero'], $game['fecha_lanzamiento'], $game['portada']);
                $this->addGame($game);
            }

            // Si la galería está definida en la base de datos, la creamos
            if($collection['galeria'] != null){
                $this->gallery = new Gallery($collection['galeria']);
                $this->gallery->getPictures();
            } else {
                $this->gallery = null;
            }
        } catch (Exception $e){
            header('Location: '.ROOT.'/collections?result=error&msg='.$e->getMessage());
        } finally{
            if($conn!=null){
                $conn->close();
            }
        }
    }

    public function addGame($game)
    {
        $this->games[] = $game;
    }

    public function deleteGame($id)
    {
        $found = false;
        $this->games = array_filter($this->games, function($game) use ($id, &$found){
            if($game->getId() == $id && !$found){
                $game->deleteGame($id);
                $found = true;
                return false;
            }
            return true;
        });
    }

    public function deleteCollection($id)
    {
        unset($this->games[$id]);
    }

    public function filterGames($platform, $genre)
    {
        $filteredGames = [];
        foreach ($this->games as $game) {
            if ($game->getPlatform() == $platform && $game->getGenre() == $genre) {
                $filteredGames[] = $game;
            }
        }
        return $filteredGames;
    }

    public function exportList(){
        if(this->games > 0){
            $delimiter=",";
            $filename="collection".date('Y-m-d').".csv";

            // Creamos un puntero de archivo
            $f = fopen('php://output', 'wb');

            // Establecemos los nombres de encabezado de las columnas
            $header = array("ID", "Nombre", "Plataforma", "Género", "Fecha de lanzamiento", "Portada");
            fputcsv($f, $header, $delimiter);

            // Escribimos los datos de cada juego en  el archivo CSV
            foreach ($this->games as $game) {
                $lineData = array($game->getId(), $game->getName(), $game->getPlatform(), $game->getGenre(), $game->getReleaseDate(), $game->getCover());
                fputcsv($f, $lineData, $delimiter);
            }

            // Descargar el archivo CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            exit();
        }
    }
}
?>