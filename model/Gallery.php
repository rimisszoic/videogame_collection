<?php
namespace Model;

require_once('Connection.php');

use Model\Connection;

class Gallery
{
    private int $id;
    private array $pictures;

    public function __construct(int $id, array $pictures)
    {
        $this->id = $id;
        $this->pictures = $pictures;
    }

    public function addPicture(string $picture): void
    {
        $this->pictures[] = $picture;
        $connection = new Connection();
        $sql = "INSERT INTO galleries (name, description, cover) VALUES (:name, :description, :cover)";
        $params = [
            ':name' => $this->name,
            ':description' => $this->description,
            ':cover' => $this->cover
        ];
        $connection->execute($sql, $params);
    }

    public function getPictures(): array
    {
        try{
            $connection = new Connection();
            $sql = "SELECT * FROM galeria_imagenes";
            $picturesData = $connection->query($sql);
            $pictures = [];

            foreach ($picturesData as $pictureData) {
                $pictures[] = new Gallery(
                    $pictureData['id'],
                    $pictureData['imagen'],
                );
            }

            return $pictures;
        } catch (Exception $e) {
            header('Location: '.ROOT.'/galleries?result=error&msg='.$e->getMessage());
            exit;
        } finally{
            $connection->close();
        }
    }
}
?>