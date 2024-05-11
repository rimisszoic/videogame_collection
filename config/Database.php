<?php
// Clase base para manejar la conexión y las operaciones con la base de datos
class Database
{
    private $servername;
    private $username;
    private $password;
    private $charset;
    private $dbname;
    private $conn;

    // Constructor de la clase
    public function __construct($servername, $username, $password, $charset, $dbname)
    {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
        $this->dbname = $dbname;
    }

    /**
     * Método para establecer la conexión con la base de datos utilzando PDO
     * @throws Exception Si la conexión falla
     */
    public function connect()
    {
        try{
            $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=$this->charset";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            throw new Exception("Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.");
        }
    }
    
    /**
     * Método para ejecutar una consulta SQL
     * @param string $query Consulta SQL a ejecutar
     * @return PDOStatement Resultado de la consulta
     * @throws Exception Si la consulta falla
     */
    public function query($query)
    {
        try {
            return $this->conn->query($query);
        } catch (PDOException $e) {
            error_log("Query failed: " . $e->getMessage());
            throw new Exception("Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.");
        }
    }
}
?>