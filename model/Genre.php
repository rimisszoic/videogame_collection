<?php
require_once('Connection.php');

class Genre
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getGenres()
    {
        try {
            $connection = new Connection();
            $sql = "SELECT * FROM generos";
            $genres = $connection->execute($sql);
            $list = [];
            foreach ($genres as $genre) {
                $list[] = new Genre($genre['id'], $genre['nombre']);
            }
            return $list;
        } catch (Exception $e) {
            header('Location: '.ROOT.'/genres?result=error&msg='.$e->getMessage());
        }
    }

    public function getGenre($id)
    {
        try {
            $connection = new Connection();
            $sql = "SELECT * FROM generos WHERE id=:id";
            $params = [':id' => $id];
            $genre = $connection->execute($sql, $params);
            return new Genre($genre['id'], $genre['nombre']);
        } catch (Exception $e) {
            header('Location: '.ROOT.'/collection/view_collection?result=error&msg='.$e->getMessage());
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
?>