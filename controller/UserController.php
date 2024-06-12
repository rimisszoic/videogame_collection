<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
require_once(MODELS.'User.php');

/**
 * Clase controladora de usuarios
 */
class UserController {
    public function login()
    {
        if(isset($_SESSION['user_id'])){
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode('Ya has iniciado sesión'));
            exit();
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Validar y procesar datos de inicio de sesión
                $user = new User();
                $result = $user->logUser($_POST['nick'], $_POST['password']);
                if ($result) {
                    // Redirigir al dashboard si el inicio de sesión es exitoso
                    header('Location: ' . BASE_URL . '?result=ok&msg='.urlencode('El usuario se ha logueado correctamente'));
                    exit();
                } else {
                    // Mostrar mensaje de error en la vista
                    header('Location: ' . BASE_URL . '?result=error&msg='.urlencode('Usuario o contraseña incorrectos'));
                    exit();
                }
            } else {
                // Si la solicitud no es POST, redirigir a la página principal
                header('Location: ' . BASE_URL);
                exit();
            }
        }
    }

    public function register()
    {
        date_default_timezone_set('Europe/Madrid');
        // Verificar si la cookie de fecha de nacimiento está presente
        $dobInvalidCookie=isset($_COOKIE['blocked_dob']) ? true : false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar y procesar datos de registro
            
            // Validar que el usuario tiene al menos 18 años
            $minAge=18;
            $minDate=date('Y-m-d', strtotime("-$minAge years"));
            if($_POST['dob']>$minDate){
                setcookie('dob_invalid',$_POST['dob'],time()+86400,'/'); // Cookie para bloquear el registro
                header('Location: ' . BASE_URL . '?result=error&msg='.urlencode('Debes tener al menos 18 años para registrarte'));
                exit();
            } else {
                $user = new User();
                $result = $user->createUser($_POST['name'], $_POST['nick'], $_POST['dob'], $_POST['email'], $_POST['registerPassword'], $_POST['confirmPassword']);
                if ($result) {
                    setcookie('dob_invalid','',time()-3600,'/'); // Eliminar cookie de fecha de nacimiento inválida (si existe
                    // Redirigir al inicio de sesión si el registro es exitoso
                    header('Location: ' . BASE_URL . '?result=ok&msg='.urlencode('El usuario se ha registrado correctamente'));
                    exit();
                } else {
                    // Mostrar mensaje de error en la vista
                    header('Location: ' . BASE_URL . '?result=error&msg='.urlencode('Error al registrar el usuario'));
                    exit();
                }
            }
        } else {
            // Si la solicitud no es POST, redirigir a la página principal
            header('Location: ' . BASE_URL);
            exit();
        }
    }

    public function logout()
    {
        $user= User::getUser();
        if(!empty($user)){
            $user->unlogUser('El usuario ha cerrado sesión correctamente','ok',true);
        }
    }
    
    public function deleteAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar la solicitud de darse de baja
            $user = User::getUser();
            if(!empty($user)){
                $user->deleteUser();
            }
        }
    }
}
?>