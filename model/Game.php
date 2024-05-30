<?php
require_once('Connection.php');
require_once('Genre.php');
require_once('Platform.php');

class Game
{
    private int $id;
    private string $name;
    private Genre $genre;
    private Platform $platform;
    private string $launchDate;
    private string $cover;

    public function __construct(int $id, string $name, Genre $genre, Platform $platform, string $launchDate, string $cover)
    {
        $this->id = $id;
        $this->name = $name;
        $this->genre = $genre;
        $this->platform = $platform;
        $this->launchDate = $launchDate;
        $this->cover = $cover;
    }

    public function addGame()
    {
        $connection = new Connection();
        $sql = "INSERT INTO juegos (nombre, genero, plataforma, fecha_lanzamiento, portada) VALUES (:name, :genre, :platform, :launchDate, :cover)";
        $params = [
            ':name' => $this->name,
            ':genre' => $this->genre->getId(),
            ':platform' => $this->platform->getId(),
            ':launchDate' => $this->launchDate,
            ':cover' => $this->cover
        ];
        $connection->execute($sql, $params);

        // Obtener el id del juego recién insertado
        $connection->lastInsertId();

        // Insertar la relación entre el juego y la plataforma en la tabla juegos_plataformas
        $sql = "INSERT INTO juegos_plataformas (juego, plataforma) VALUES (:gameId, :platformId)";
        $params = [':gameId' => $gameId, ':platformId' => $this->platform->getId()];
        $connection->execute($sql, $params);
    }

    public function editGame(int $id)
    {
        $connection = new Connection();
        $sql = "UPDATE games SET name=:name, genre_id=:genre, platform_id=:platform, launch_date=:launchDate, cover=:cover WHERE id=:id";
        $params = [
            ':name' => $this->name,
            ':genre' => $this->genre->getId(),
            ':platform' => $this->platform->getId(),
            ':launchDate' => $this->launchDate,
            ':cover' => $this->cover,
            ':id' => $id
        ];
        $connection->execute($sql, $params);
    }

    public function deleteGame(int $id)
    {
        $connection = new Connection();
        $sql = "DELETE FROM games WHERE id=:id";
        $params = [':id' => $id];
        $connection->execute($sql, $params);
    }

    public static function getGame(int $id): Game
    {
        $connection = new Connection();
        $sql = "SELECT * FROM games WHERE id=:id";
        $params = [':id' => $id];
        $game = $connection->query($sql, $params);
        return new Game($game['nombre'], Genre::getGenre($game['genero']), Platform::getPlatform($game['platforma']), $game['fecha_lanzamiento'], $game['portada']);
    }

    public function addToCollection(int $userId)
    {
        $connection = new Connection();
        $sql = "INSERT INTO collections (usuario, juego) VALUES (:userId, :gameId)";
        $params = [':userId' => $userId, ':gameId' => $this->getId()];
        $connection->execute($sql, $params);
    }

    /**
     * Getters y Setters
     */

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGenre(): Genre
    {
        return $this->genre;
    }

    public function setGenre(Genre $genre): void
    {
        $this->genre = $genre;
    }

    public function getPlatform(): Platform
    {
        return $this->platform;
    }

    public function setPlatform(Platform $platform): void
    {
        $this->platform = $platform;
    }

    public function getLaunchDate(): string
    {
        return $this->launchDate;
    }

    public function setLaunchDate(string $launchDate): void
    {
        $this->launchDate = $launchDate;
    }

    public function getCover(): string
    {
        return $this->cover;
    }

    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }
}
?>