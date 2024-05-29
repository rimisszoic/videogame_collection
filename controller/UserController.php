<?php
namespace Controller;

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

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar y procesar datos de registro
            $user = new User();
            $result = $user->createUser($_POST['name'], $_POST['nick'], $_POST['dob'], $_POST['email'], $_POST['registerPassword'], $_POST['confirmPassword']);
            if ($result) {
                // Redirigir al inicio de sesión si el registro es exitoso
                header('Location: ' . BASE_URL . '?result=ok&msg='.urlencode('El usuario se ha registrado correctamente'));
                exit();
            } else {
                // Mostrar mensaje de error en la vista
                header('Location: ' . BASE_URL . '?result=error&msg='.urlencode('Error al registrar el usuario'));
                exit();
            }
        } else {
            // Si la solicitud no es POST, redirigir a la página principal
            header('Location: ' . BASE_URL);
            exit();
        }
    }

    public function logout()
    {
        $user= new User();
        if($user->getUser() === true){
            $user->unlogUser();
        }
    }
    
    public function deleteAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar la solicitud de darse de baja
            $user = new User();
            $user->deleteUser($_POST['id']);
            
            // Redirigir a la página de inicio
            header('Location: ' . BASE_URL.'?result=ok&msg='.urlencode('El usuario se ha eliminado correctamente'));
        }
    }
}
?>