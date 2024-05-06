<?php
require_once(CONFIG.'Database.php');

// Clase para manejar la conexión con la base de datos específicamente
class Connection extends Database
{
    // Constructor de la clase
    public function __construct()
    {
        // Llamar al constructor de la clase padre
        parent::__construct("localhost", "gam3Coll@bUs3r", "J4nR#9!pQz_23Ld", "videogame_collection", "utf8");
    }
}
?>