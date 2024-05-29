<?php
namespace Model;

include_once("Game.php");
include_once("Gallery.php");
include_once("Connection.php");

use Model\Game;
use Model\Connection;
use Model\Gallery;

class Collection
{
    private int $id;
    private array $games;
    private Gallery $gallery;

    public function __construct(int $id, array $games, Gallery $gallery)
    {
        $this->id = $id;
        $this->games = $games;
        $this->gallery = $gallery;
    }

    public function getCollections()
    {
        try{
            $conn=new Connection();
            $conn=$conn->connect();
            $stmt = $conn->prepare("SELECT c.id as id c.usuario as usuario, j.nombre as juego, j.nombre as nombre, jp.plataforma as plataforma, j.genero as genero, c.galeria as galeria j.fecha_lanzamiento as fecha_lanzamiento, jp.portada as portada FROM coleccion c join juegos_plataformas jp on c.juego=jp.id join juegos j on jp.juego=j.id");
            $stmt->execute();
            $games = $stmt->fetchAll();
            $this->games = [];
            foreach ($games as $game) {
                $this->games[] = new Game($game['nombre'], $game['genero'], $game['plataforma'], $game['fecha_lanzamiento'], $game['portada']);
            }
            $this->gallery = new Gallery($game['galeria']);

        } catch (Exception $e){
            header('Location: '.ROOT.'/collections?result=error&msg='.$e->getMessage());
        } finally{
            $conn->close();
        }
    }

    public function getCollection($id)
    {
        return $this->games[$id];
    }

    public function addCollection($game)
    {
        $this->games[] = $game;
    }

    public function updateCollection($id, $game)
    {
        $this->games[$id] = $game;
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
        $list = [];
        foreach ($this->games as $game) {
            $list[] = $game->export();
        }
        return $list;
    }
}
?>