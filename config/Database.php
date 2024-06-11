<?php
require_once('const.php');
require_once(dirname(__DIR__) . '/model/Mailer.php');

class Database
{
    protected $servername;
    protected $username;
    protected $password;
    protected $charset;
    protected $dbname;
    protected $conn;
    protected $logFile;
    protected $adminEmail;
    protected $mailer;

    public function __construct($servername, $username, $password, $charset, $dbname, $logFile = LOGS . 'errors.log', $adminEmail = "rimiss@rimisszoic.live")
    {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
        $this->dbname = $dbname;
        $this->logFile = $logFile;
        $this->adminEmail = $adminEmail;
        $this->mailer = new Mailer();
    }

    public function connect()
    {
        try {
            $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=$this->charset";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET time_zone = '+01:00'");
        } catch (PDOException $e) {
            $this->handleError("Connection failed: " . $e->getMessage());
            throw new Exception("Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.");
        }
    }

    public function query($query)
    {
        try {
            return $this->conn->query($query);
        } catch (PDOException $e) {
            $this->handleError("Query failed: " . $e->getMessage());
            throw new Exception("Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.");
        }
    }

    public function prepare($query)
    {
        return $this->conn->prepare($query);
    }

    public function returnConnection()
    {
        return $this->conn;
    }

    public function lastInsertId(): int
    {
        try {
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $this->handleError("Last insert ID failed: " . $e->getMessage());
            throw new Exception("Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.");
        }
    }

    private function handleError($message)
    {
        $log = "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
        error_log($log, 3, $this->logFile);

        try {
            $subject = "Error en la aplicación";
            $template = $this->mailer->loadTemplate(RESOURCES . 'templates/error_email.html', ['errorMessage' => $message]);
            $this->mailer->sendMail($this->adminEmail, $subject, $template);
        } catch (Exception $e) {
            $log = "[" . date('Y-m-d H:i:s') . "] Error sending email: " . $e->getMessage() . PHP_EOL;
            error_log($log, 3, $this->logFile);
        }

        throw new Exception("Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.");
    }
}
?>
