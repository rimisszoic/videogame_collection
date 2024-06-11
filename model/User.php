<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
require_once(MODELS.'Connection.php');
require_once(MODELS.'Mailer.php');

/**
 * Clase User
 * Rrepresenta un usuario y proporciona métodos para manejar usuarios.
 */
class User {
    // Propiedades de la clase User
    private string $fullName;
    private string $nickName;
    private ?string $dateOfBirth;
    private string $email;
    private string $password;
    private int $role;
    private ?DateTime $lastAccess;

    /**
     * Constructor
     * Inicializa las propiedades del usuario.
     */
    public function __construct() {
        $this->fullName = "";
        $this->nickName = "";
        $this->dateOfBirth = null;
        $this->email = "";
        $this->password = "";
        $this->role = 0;
        $this->lastAccess = null;
        date_default_timezone_set('Europe/Madrid');
    }

    /**
     * Inicia la sesión de un usuario.
     * @param string $nickName Nombre de usuario.
     * @param string $password Contraseña del usuario.
     * @return bool Resultado de la operación.
     */
    public function logUser(string $nickName, string $password): bool {
        try{
            $conn = new Connection();
            $conn->connect();
            $query = "SELECT * FROM usuarios WHERE nombre_usuario = :nickName";
            $stmt = $conn->returnConnection()->prepare($query);
            $stmt->bindParam(':nickName', $nickName, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['user_nick'] = $nickName;
                $_SESSION['result'] = true;
                $this->mapUser($user);
                $this->setLastAccess($user['id']);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return false;
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }    

    /**
     * Método para cerrar la sesión de un usuario.
     * @param string $msg Mensaje a mostrar.
     * @param string $result Resultado de la operación (ok por defecto).
     * @param bool $redirect Redirigir a la página de inicio (true por defecto). Si se establece a false, no se redirige.
     */
    public function unlogUser($msg='El usuario ha cerrado sesión correctamente', $result='ok', $redirect=true): void {
        $this->setLastAccess($_SESSION['user_id']);
        // Eliminar las variables de sesión
        session_unset();
        session_destroy();
        if($redirect){
            header('Location: '.BASE_URL.'?result='.$result.'&msg='.urlencode($msg));
            exit();
        }
    }

    /**
     * Método para crear un usuario.
     * @param string $name Nombre completo del usuario.
     * @param string $nick Nombre de usuario (nick).
     * @param string $dob Fecha de nacimiento del usuario en formato 'YYYY-MM-DD'.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @param string $confirmPassword Confirmación de la contraseña del usuario.
     * @return bool Resultado de la operación.
     */
    public function createUser(string $name, string $nick, string $dob, string $email, string $password, string $confirmPassword): bool {
        try{
            $conn = new Connection();
            $conn->connect();
            $query = "SELECT * FROM usuarios WHERE email = :email OR nombre_usuario = :nick";
            $stmt = $conn->returnConnection()->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':nick', $nick, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                return false;
            } else {
                if($password !== $confirmPassword){
                    header('Location: '.BASE_URL.'?result=error&msg='.urlencode('Las contraseñas no coinciden'));
                    return false;
                } else {
                    $this->setPassword(password_hash($password, PASSWORD_DEFAULT));
                }

                // Verificar si el nombre comoleto contiene un espacio en blanco
                if(strpos($name, ' ') !== false){
                    // Si el nombre completo contiene al menos un espacio en blanco
                    $parts=explode(" ", $name);

                    // Nombre y apellidos
                    $firstName = ucfirst($parts[0]); // Nombre
                    $lastName = implode(" ",array_map('ucfirst',array_slice($parts,1))); // Apellidos
                    $fullNameFormated = $lastName.", ".$firstName;
                    $this->setFullName($fullNameFormated);
                } else {
                    // Si el nombre completo no contiene espacios en blanco
                    $fullNameFormated = ucfirst($name);
                    $this->setFullName($fullNameFormated);
                }

                $this->setNickName($nick);
                $this->setEmail($email);
                $this->setDateOfBirth($dob);
                $queryRol="SELECT id FROM roles WHERE nombre='registrado'";
                $resultRol=$conn->query($queryRol);
                $rowRol=$resultRol->fetch(PDO::FETCH_ASSOC);
                $this->setLocalRole($rowRol['id']);
                $query = "INSERT INTO usuarios (nombre_completo, nombre_usuario, email, fecha_nacimiento, password, rol) VALUES (:name, :nickname, :email, :dob, :pwd, :role)";
                $stmt=$conn->returnConnection()->prepare($query);
                $stmt->bindParam(":name", $fullNameFormated, PDO::PARAM_STR);
                $stmt->bindParam(":nickname", $nick, PDO::PARAM_STR);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":dob", $dob, PDO::PARAM_STR);
                $stmt->bindParam(":pwd", $this->password, PDO::PARAM_STR);
                $stmt->bindParam(":role", $rowRol['id'], PDO::PARAM_INT);
                if($stmt->execute()){
                    $_SESSION['result'] = true;
                    $_SESSION['user_id'] = $conn->lastInsertId();
                    $_SESSION['user_role'] = $this->getLocalRole();
                    $_SESSION['user_nick'] = $this->getNickName();
                    $conn->close();
                    // Enviar correo electrónico de bienvenida
                    $mailer = new Mailer();
                    $template=$mailer->loadTemplate(RESOURCES.'templates/welcome_email.html',['username'=>$nick]);
                    $mailer->sendMail($email, 'Bienvenido a la plataforma', $template);
                    
                    header('Location: '.BASE_URL.'?result=ok&msg='.urlencode('El usuario se ha registrado correctamente'));
                    return true;
                } else {
                    header('Location: '.BASE_URL.'?result=error&msg='.urlencode('No se ha podido registrar el usuario'));
                    return false;
                }
            }
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return false;
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }

    /**
     * Método para actualizar un usuario.
     * @param string $name Nombre completo del usuario.
     * @param string $nick Nombre de usuario (nick).
     * @param string $dob Fecha de nacimiento del usuario en formato 'YYYY-MM-DD'.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @param string $confirmPassword Confirmación de la contraseña del usuario.
     */
    public function updateUser(string $name, string $nick, string $dob, string $email, string $password=''): bool {
        $this->setFullName($name);
        $this->setNickName($nick);
        $this->setDateOfBirth($dob);
        $this->setEmail($email);
        if($password !== ''){
            $this->setPassword(password_hash($password, PASSWORD_DEFAULT));
        } else {
            // Si no se ha especificado una contraseña, se mantiene la contraseña actual
            $this->setPassword($this->getPassword());
        }
        $this->setLastAccess($_SESSION['user_id']);

        try{
            $conn = new Connection();
            $conn->connect();
            $query = "UPDATE usuarios SET nombre_completo = :name, nombre_usuario = :nick, fecha_nacimiento= :dob, email = :email, password = :pwd WHERE id = :id";
            $stmt=$conn->prepare($query);
            $stmt->bindParam(":name", $this->fullName, PDO::PARAM_STR);
            $stmt->bindParam(":nick", $this->nickName, PDO::PARAM_STR);
            $stmt->bindParam(":dob", $this->dateOfBirth, PDO::PARAM_STR);
            $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
            $stmt->bindParam(":pwd", $this->password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $_SESSION['user_id'], PDO::PARAM_INT);
            header('Location: '.BASE_URL.'?result=ok&msg='.urlencode('El usuario se ha actualizado correctamente'));
            return true;
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return false;
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }

    /**
     * Método para eliminar un usuario.
     */
    public function deleteUser(): bool {
        $conn = new Connection();
        try{
            $conn->connect();
            $query = "DELETE FROM usuarios WHERE id = ".$_SESSION['user_id'];
            if($conn->query($query)){
                $this->unlogUser('','',false);
                header('Location: '.BASE_URL.'?result=ok&msg='.urlencode('El usuario se ha eliminado correctamente'));
                return true;
            } else {
                header('Location: '.BASE_URL.'?result=error&msg='.urlencode('No se ha podido eliminar el usuario'));
                return false;
            }
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return false;
        } finally{
            if($conn!==null){
                $conn->close();
            }
        }
    }

    /**
     * Método privado para asignar valores de usuario a las propiedades de la clase.
     * @param array $user Datos del usuario.
     */
    private function mapUser(array $user): void {
        $this->setFullName($user['nombre_completo']);
        $this->setNickName($user['nombre_usuario']);
        $this->setDateOfBirth($user['fecha_nacimiento']);
        $this->setEmail($user['email']);
        $this->setPassword($user['password']);
        $this->setLocalRole($user['rol']);
        $this->setLocalLastAccess(new DateTime($user['ultimo_acceso']));
    }


    /**
     * Método para obtener un usuario.
     * @return self|bool Usuario o false si no se encuentra el usuario.
     */
    public static function getUser(): self|bool{
        try{
            $conn = new Connection();
            $conn->connect();
            $query = "SELECT * FROM usuarios WHERE id = ".$_SESSION['user_id'];
            $result = $conn->query($query);
            if($result->rowCount() > 0){
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $user = new User();
                $user->setFullName($row['nombre_completo']);
                $user->setNickName($row['nombre_usuario']);
                $user->setDateOfBirth($row['fecha_nacimiento']);
                $user->setEmail($row['email']);
                $user->setPassword($row['password']);
                $user->setLocalRole($row['rol']);
                $user->setLocalLastAccess(new DateTime($row['ultimo_acceso']));
                return $user;
            } else {
                return false;
            }
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return false;
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }


    /**
     * Método estático para obtener todos los usuarios.
     * @return array Usuarios
     */
    public static function getUsers(): array {
        try {
            $conn = new Connection();
            $conn->connect();
            $query = "SELECT * FROM usuarios";
            $result = $conn->query($query);
            $users = array();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new User();
                $user->setFullName($row['nombre_completo']);
                $user->setNickName($row['nombre_usuario']);
                $user->setDateOfBirth($row['fecha_nacimiento']);
                $user->setEmail($row['email']);
                $user->setPassword($row['password']);
                $user->setLocalRole($row['rol']);
                $user->setLocalLastAccess(new DateTime($row['ultimo_acceso']));
                $users[] = $user;
            }
            $conn->close();
            return $users;
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return []; // Add a return statement here
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }

    /**
     * Método para obtener los roles.
     * @return array Roles
     */
    public function getRoles(): array {
        try {
            $conn = new Connection();
            $conn->connect();
            $query = "SELECT * FROM roles";
            $result = $conn->query($query);
            if($result->rowCount() > 0) {
                $roles = array();
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $roles[] = $row;
                }
                return $roles;
            } else {
                return [];
            }
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return [];
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }

    /**
     * Método para establecer el rol de un usuario.
     * @param int $userId ID del usuario.
     * @param int $roleId ID del rol.
     */
    public function setRole(int $userId, int $roleId): void {
        try{
            $conn = new Connection();
            $conn->connect();
            $query = "UPDATE usuarios SET rol = $roleId WHERE id = $userId";
            $conn->query($query);
            $this->setLocalRole($roleId);
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }

    /**
     * Método para obtener el rol de un usuario.
     * @param int $userId ID del usuario.
     * @return int ID del rol.
     */
    public function getRole(int $userId): int {
        try{
            $conn = new Connection();
            $conn->connect();
            $query = "SELECT role FROM usuarios WHERE id = $userId";
            $result = $conn->query($query);
            $role = 0; // Valor predeterminado
            if($result->rowCount() > 0) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $role = $row['rol'];
            }
            $conn->close();
            return $role;
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return 0;
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }

    /**
     * Método para obtener el último acceso del usuario.
     * @param int $userId ID del usuario.
     * @return string Fecha y hora del último acceso en formato 'YYYY-MM-DD HH:MM:SS'.
     */
    public function getLastAccess(int $userId): string {
        try{
            $conn = new Connection();
            $conn->connect();
            $query = "SELECT ultimo_acceso FROM usuarios WHERE id = $userId";
            $result = $conn->query($query);
            $lastAccess = ""; // Valor predeterminado si no se encuentra ningún acceso
            if($result->rowCount() > 0) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $lastAccess = $row['ultimo_acceso'];
            }
            $conn->close();
            return $lastAccess;
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
            return "";
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
    }

    /**
     * Método para establecer el último acceso del usuario.
     * @param int $userId ID del usuario.
     */
    public function setLastAccess(int $userId): void {
        try{
            $conn = new Connection();
            $conn->connect();
            $query = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = $userId";
            $conn->query($query);
        } catch (Exception $e) {
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode($e->getMessage()));
        } finally {
            if($conn!==null){
                $conn->close();
            }
        }
        $this->setLocalLastAccess(new DateTime());
    }

    // Getters y Setters

    /**
     * Método para obtener el nombre completo del usuario.
     * @return string Nombre completo del usuario.
     */
    public function getFullName(): string {
        return $this->fullName;
    }

    /**
     * Método para establecer el nombre completo del usuario.
     * @param string $fullName Nombre completo del usuario.
     */
    public function setFullName(string $fullName): void {
        $this->fullName = $fullName;
    }

    /**
     * Método para obtener el nombre de usuario (nick) del usuario.
     * @return string Nombre de usuario (nick) del usuario.
     */
    public function getNickName(): string {
        return $this->nickName;
    }

    /**
     * Método para establecer el nombre de usuario (nick) del usuario.
     * @param string $nickName Nombre de usuario (nick) del usuario.
     */
    public function setNickName(string $nickName): void {
        $this->nickName = $nickName;
    }

    /**
     * Método para obtener la fecha de nacimiento del usuario.
     * @return string Fecha de nacimiento del usuario en formato 'YYYY-MM-DD'.
     */
    public function getDateOfBirth(): string {
        return date('Y-m-d', strtotime($this->dateOfBirth));
    }

    /**
     * Método para establecer la fecha de nacimiento del usuario.
     * @param string $dateOfBirth Fecha de nacimiento del usuario en formato 'YYYY-MM-DD'.
     */
    public function setDateOfBirth(string $dateOfBirth): void {
        $this->dateOfBirth = date('Y-m-d', strtotime($dateOfBirth));
    }

    /**
     * Método para obtener el correo electrónico del usuario.
     * @return string Correo electrónico del usuario.
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Método para establecer el correo electrónico del usuario.
     * @param string $email Correo electrónico del usuario.
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    /**
     * Método para obtener la contraseña del usuario.
     * @return string Contraseña del usuario.
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Método para establecer la contraseña del usuario.
     * @param string $password Contraseña del usuario.
     */
    public function setPassword(string $password): void {
        $this->password = $password;
    }

    /**
     * Método para obtener el rol del usuario.
     * @return int ID del rol del usuario.
     */
    public function getLocalRole(): int {
        return $this->role;
    }

    /**
     * Método para establecer el rol del usuario.
     * @param int $role ID del rol del usuario.
     */
    public function setLocalRole(int $role): void {
        $this->role = $role;
    }

    /**
     * Método para obtener el último acceso del usuario.
     * @return DateTime|null Fecha y hora del último acceso en formato 'YYYY-MM-DD HH:MM:SS'.
     */
    public function getLocalLastAccess(): ?DateTime {
        return $this->lastAccess;
    }

    /**
     * Método para establecer el último acceso del usuario.
     * @param DateTime|null $lastAccess Fecha y hora del último acceso en formato 'YYYY-MM-DD HH:MM:SS'.
     */
    public function setLocalLastAccess(?DateTime $lastAccess): void {
        if($lastAccess !== null){
            $this->lastAccess = $lastAccess;
        }
    }
}
?>