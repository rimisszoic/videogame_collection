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

    // Método para establecer la conexión con la base de datos utilzando PDO
    protected function connect()
    {
        try{
            $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=$this->charset";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
    
    // Método para ejecutar una consulta SQL
    protected function query($query)
    {
        try {
            return $this->conn->query($query);
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
}
?>