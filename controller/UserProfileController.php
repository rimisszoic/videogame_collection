<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once(MODELS . '/User.php');

class UserProfileController {
    private $user;

    public function __construct(){
        $this->user = User::getUser();
    }

    public function index(){
        if (isset($_SESSION['user_id'])) {
            require_once(VIEWS.'UserProfile.php');
        } else {
            $this->redirectWithMessage('error', 'No tienes permisos para acceder a esta página.');
        }
    }
    public function updateProfile(){
        if (isset($_SESSION['user_id'])) {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $nick = filter_input(INPUT_POST, 'nick', FILTER_SANITIZE_STRING);
            $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            $result = $this->user->updateUser($name, $nick, $dob, $email);

            if (!empty($password) && !empty($new_password) && !empty($confirm_password)) {
                $user = $this->user->getUser();
                if (password_verify($password, $user->getPassword())) {
                    if ($new_password === $confirm_password) {
                        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $updateResult = $this->user->updateUser($name, $nick, $dob, $email, $hashed_new_password);
                        if ($updateResult) {
                            $this->redirectWithMessage('ok', 'Perfil actualizado correctamente.');
                        } else {
                            $this->redirectWithMessage('error', 'No se pudo actualizar el perfil.');
                        }
                    } else {
                        $this->redirectWithMessage('error', 'Las contraseñas no coinciden.');
                    }
                } else {
                    $this->redirectWithMessage('error', 'La contraseña actual es incorrecta.');
                }
            } else {
                if ($result) {
                    $this->redirectWithMessage('ok', 'Perfil actualizado correctamente.');
                } else {
                    $this->redirectWithMessage('error', 'No se pudo actualizar el perfil.');
                }
            }
        }
    }

    public function deleteProfile(){
        if (isset($_SESSION['user_id'])) {
            $this->user->deleteUser();
        }
    }
    private function redirectWithMessage($result, $message) {
        header('Location: ' . BASE_URL . '?result=' . $result . '&msg=' . urlencode($message));
        exit();
    }
}
?>