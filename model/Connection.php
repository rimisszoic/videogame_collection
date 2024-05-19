<?php
require_once(CONFIG.'Database.php');

// Clase para manejar la conexión con la base de datos específicamente
class Connection extends Database
{
    /**
     * Constructor de la clase
     * Llama al constructor de la clase padre con los parámetros específicos
     */
    public function __construct()
    {
        // Llamar al constructor de la clase padre
        parent::__construct("localhost", "gam3Coll@bUs3r", "J4nR#9!pQz_23Ld", "utf8mb4","videogame_collection");
    }

    /**
     * Método para establecer la conexión con la base de datos
     * Llama al método connect de la clase padre
     */
    public function close()
    {
        $this->conn = null;
    }
}
?>