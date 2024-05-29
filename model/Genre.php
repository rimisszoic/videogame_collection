<?php
namespace Model;

require_once('Connection.php');

use Model\Connection;

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