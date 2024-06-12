<?php
require_once(dirname(__DIR__).'/config/const.php');
require_once(dirname(__DIR__).'/config/Database.php');

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
        parent::__construct(DB_HOST,DB_USER,DB_PWD,"utf8mb4",DBNAME);
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