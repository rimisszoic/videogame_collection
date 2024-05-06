<?php
include_once(MODEL.'Connection.php');

/**
 * Clase para representar un usuario.
 */
class User {
    // Propiedades de la clase User
    private string $fullName;
    private string $nickName;
    private string $dateOfBirth;
    private string $email;
    private string $password;
    private int $role;
    private string $lastAccess;

    /**
     * Constructor de la clase User.
     * @param string $fullName Nombre completo del usuario.
     * @param string $nickName Nombre de usuario (nick).
     * @param string $dateOfBirth Fecha de nacimiento del usuario en formato 'YYYY-MM-DD'.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @param int $role Rol del usuario.
     * @param string $lastAccess Último acceso del usuario en formato 'YYYY-MM-DD HH:MM:SS'.
     */
    public function __construct(string $fullName, string $nickName, string $dateOfBirth, string $email, string $password, int $role, string $lastAccess) {
        $this->fullName = $fullName;
        $this->nickName = $nickName;
        $this->dateOfBirth = $dateOfBirth;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->lastAccess = $lastAccess;
    }

    /**
     * Método para loguear un usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     */
    public function logUser(string $nickName, string $password): void {
        $conn = new Connection();
        $conn->connect();
        $query = "SELECT id, role FROM user WHERE nickName = '$nickName' AND password = '$password'";
        $result = $conn->query($query);
        $conn->close();
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_role'] = $row['role'];
            $_SESSION['result'] = true;
            header(encode_url('Location: '.BASE_URL.'?result=ok&msg=El usuario se ha logueado correctamente'));
        } else {
            $_SESSION['result'] = false;
            header(encode_url('Location: '.BASE_URL.'?result=error&msg=Usuario o contraseña incorrectos'));
        }
    }

    /**
     * Método para desloguear un usuario.
     */
    public function unlogUser(): void {
        session_destroy();
        // Recargar la vista
        header(encode_url('Location: '.BASE_URL.'?result=ok&msg=El usuario se ha deslogueado correctamente'));
    }

    /**
     * Método para crear un usuario.
     * @param string $name Nombre completo del usuario.
     * @param string $nick Nombre de usuario (nick).
     * @param string $dob Fecha de nacimiento del usuario en formato 'YYYY-MM-DD'.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     */
    public function createUser(string $name, string $nick, string $dob, string $email, string $password): void {
        $conn = new Connection();
        $conn->connect();
        $query = "SELECT * FROM user WHERE email = '$email' OR nick = '$nick'";
        $result = $conn->query($query);
        if($result->num_rows > 0) {
            $_SESSION['result'] = false;
        } else {
            $query = "INSERT INTO user (name, nick, dob, email, password) VALUES ('$name', '$nick', '$dob', '$email', '$password')";
            $conn->query($query);
            $_SESSION['result'] = true;
        }
        $conn->close();
    }

    /**
     * Método para actualizar un usuario.
     * @param string $name Nombre completo del usuario.
     * @param string $nick Nombre de usuario (nick).
     * @param string $dob Fecha de nacimiento del usuario en formato 'YYYY-MM-DD'.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     */
    public function updateUser(string $name, string $nick, string $dob, string $email, string $password): void {
        $conn = new Connection();
        $conn->connect();
        $query = "UPDATE user SET name = '$name', nick = '$nick', dob = '$dob', email = '$email', password = '$password' WHERE id = ".$_SESSION['id'];
        $conn->query($query);
        $conn->close();
    }

    /**
     * Método para eliminar un usuario.
     */
    public function deleteUser(): void {
        $conn = new Connection();
        $conn->connect();
        $query = "DELETE FROM user WHERE id = ".$_SESSION['id'];
        $conn->query($query);
        $conn->close();
    }

    /**
     * Método para obtener un usuario.
     * @return array Usuario
     */
    public function getUser(): array {
        $conn = new Connection();
        $conn->connect();
        $query = "SELECT * FROM user WHERE id = ".$_SESSION['id'];
        $result = $conn->query($query);
        $conn->close();
        return $result;
    }

    /**
     * Método para obtener todos los usuarios.
     * @return array Usuarios
     */
    public function getUsers(): array {
        $conn = new Connection();
        $conn->connect();
        $query = "SELECT * FROM user";
        $result = $conn->query($query);
        $conn->close();
        return $result;
    }

    /**
     * Método para obtener los roles.
     * @return array Roles
     */
    public function getRoles(): array {
        $conn = new Connection();
        $conn->connect();
        $query = "SELECT * FROM role";
        $result = $conn->query($query);
        $conn->close();
        return $result;
    }

    /**
     * Método para establecer el rol de un usuario.
     * @param int $userId ID del usuario.
     * @param int $roleId ID del rol.
     */
    public function setRole(int $userId, int $roleId): void {
        $conn = new Connection();
        $conn->connect();
        $query = "UPDATE user SET role_id = $roleId WHERE id = $userId";
        $conn->query($query);
        $conn->close();
    }

    /**
     * Método para obtener el rol de un usuario.
     * @param int $userId ID del usuario.
     * @return int ID del rol.
     */
    public function getRole(int $userId): int {
        $conn = new Connection();
        $conn->connect();
        $query = "SELECT role_id FROM user WHERE id = $userId";
        $result = $conn->query($query);
        $role = 0; // Valor predeterminado
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $role = $row['role_id'];
        }
        $conn->close();
        return $role;
    }

    /**
     * Método para obtener el último acceso del usuario.
     * @param int $userId ID del usuario.
     * @return string Fecha y hora del último acceso en formato 'YYYY-MM-DD HH:MM:SS'.
     */
    public function getLastAccess(int $userId): string {
        $conn = new Connection();
        $conn->connect();
        $query = "SELECT last_access FROM user WHERE id = $userId";
        $result = $conn->query($query);
        $lastAccess = ""; // Valor predeterminado si no se encuentra ningún acceso
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastAccess = $row['last_access'];
        }
        $conn->close();
        return $lastAccess;
    }

    /**
     * Método para establecer el último acceso del usuario.
     * @param int $userId ID del usuario.
     */
    public function setLastAccess(int $userId): void {
        $conn = new Connection();
        $conn->connect();
        $query = "UPDATE user SET last_access = NOW() WHERE id = $userId";
        $conn->query($query);
        $conn->close();
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
        return $this->dateOfBirth;
    }

    /**
     * Método para establecer la fecha de nacimiento del usuario.
     * @param string $dateOfBirth Fecha de nacimiento del usuario en formato 'YYYY-MM-DD'.
     */
    public function setDateOfBirth(string $dateOfBirth): void {
        $this->dateOfBirth = $dateOfBirth;
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
     * @return string Fecha y hora del último acceso en formato 'YYYY-MM-DD HH:MM:SS'.
     */
    public function getLocalLastAccess(): string {
        return $this->lastAccess;
    }

    /**
     * Método para establecer el último acceso del usuario.
     * @param string $lastAccess Fecha y hora del último acceso en formato 'YYYY-MM-DD HH:MM:SS'.
     */
    public function setLocalLastAccess(string $lastAccess): void {
        $this->lastAccess = $lastAccess;
    }
}
?>