<?php
require_once('Connection.php');

class Gallery
{
    private int $id;
    private array $pictures;

    public function __construct(int $id=0, array $pictures=[])
    {
        $this->id = $id;
        $this->pictures = $pictures;

    }

    public function addPicture(string $picture): void
    {
        try{
            $connection = new Connection();
            $sql = "INSERT INTO galeria_imagenes (imagen) VALUES (:image)";
            $connection->query($sql, ['image' => $picture]);
            $this->pictures[] = $picture;
        } catch (Exception $e) {
            header('Location: '.ROOT.'/collections/view_collection?result=error&msg='.$e->getMessage());
            exit();
        } finally{
            if($connection!=null){
                $connection->close();
            }
        }
    }

    public function getPictures(): array
    {
        try{
            $connection = new Connection();
            $sql = "SELECT * FROM galeria_imagenes where id=$this->id";
            $picturesData = $connection->query($sql);
            $picturesData = $picturesData->fetch(PDO::FETCH_ASSOC);
            $pictures = [];

            foreach ($picturesData as $pictureData) {
                $this->addPicture($pictureData['imagen']);
            }
            return $pictures;
        } catch (Exception $e) {
            header('Location: '.ROOT.'/collections/view_collection?result=error&msg='.$e->getMessage());
            exit;
        } finally{
            if($connection!=null){
                $connection->close();
            }
        }
    }

    public function deleteGallery(int $id): void
    {
        try{
            $connection = new Connection();
            $sql = "DELETE FROM galeria_imagenes WHERE id=:id";
            $connection->query($sql, ['id' => $id]);
            $this->pictures = [];
        } catch (Exception $e) {
            header('Location: '.ROOT.'/collections/view_collection?result=error&msg='.$e->getMessage());
            exit();
        } finally{
            if($connection!=null){
                $connection->close();
            }
        }
    }
}
?>