<?php
namespace Model;

require_once('Connection.php');

use Model\Connection;

class Platform
{
    private int $id;
    private string $name;
    private string $cover;

    public function __construct(int $id, string $name, string $cover)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cover = $cover;
    }

    /**
     * Obtiene todas las plataformas desde la base de datos.
     * @return array Arreglo de objetos Platform
     * @throws Exception Si hay un error al conectar a la base de datos
     */
    public static function getPlatforms()
    {
        try {
            $connection = new Connection();
            $sql = "SELECT * FROM plataformas";
            $platformsData = $connection->query($sql);
            $platforms = [];

            foreach ($platformsData as $platformData) {
                $platforms[] = new Platform(
                    $platformData['id'],
                    $platformData['nombre'],
                    $platformData['portada']
                );
            }

            return $platforms;
        } catch (Exception $e) {
            // Redirige a una página de error en caso de excepción
            header('Location: '.ROOT.'/platforms?result=error&msg='.$e->getMessage());
            exit;
        } finally{
            $connection->close();
        }
    }

    /**
     * Obtiene una plataforma por su ID desde la base de datos.
     * @param int $id ID de la plataforma
     * @return Platform Objeto Platform
     * @throws Exception Si hay un error al conectar a la base de datos o si la plataforma no existe
     */
    public static function getPlatform(int $id)
    {
        try {
            $connection = new Connection();
            $sql = "SELECT * FROM plataformas WHERE id=:id";
            $params = [':id' => $id];
            $platformData = $connection->query($sql, $params)->fetch(PDO::FETCH_ASSOC);

            if (!$platformData) {
                throw new Exception("Plataforma no encontrada.");
            }

            return new Platform(
                $platformData['id'],
                $platformData['nombre'],
                $platformData['portada']
            );
        } catch (Exception $e) {
            // Redirige a una página de error en caso de excepción
            header('Location: '.ROOT.'/platforms?result=error&msg='.$e->getMessage());
            exit;
        } finally{
            $connection->close();
        }
    }

    // Getters y setters

    /**
     * Obtiene el ID de la plataforma.
     * @return int ID de la plataforma
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Establece el ID de la plataforma.
     * @param int $id ID de la plataforma
     * @return void No devuelve nada
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Obtiene el nombre de la plataforma.
     * @return string Nombre de la plataforma
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Establece el nombre de la plataforma.
     * @param string $name Nombre de la plataforma
     * @return void No devuelve nada
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Obtiene la portada de la plataforma.
     * @return string Portada de la plataforma
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * Establece la portada de la plataforma.
     * @param string $cover Portada de la plataforma
     * @return void No devuelve nada
     */
    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }
}
?>