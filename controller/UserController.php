<?php
require_once(MODEL.'User.php');
require_once(CONFIG.'const.php');

class UserController {
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar y procesar datos de inicio de sesión
            $user = new User();
            $result = $user->logUser($_POST['nick'], $_POST['password']);
            if ($result) {
                // Redirigir al dashboard si el inicio de sesión es exitoso
                header('Location: ' . BASE_URL . '?result=ok&msg=El usuario se ha logueado correctamente');
                exit();
            } else {
                // Mostrar mensaje de error en la vista
                header('Location: ' . BASE_URL . '?result=error&msg=Usuario o contraseña incorrectos');
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
                header('Location: ' . BASE_URL . '?result=ok&msg=El usuario se ha registrado correctamente');
                exit();
            } else {
                // Mostrar mensaje de error en la vista
                header('Location: ' . BASE_URL . '?result=error&msg=Error al registrar el usuario');
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
        $user->logout();
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Si se envió la solicitud de cancelación de la edición
            if(isset($_POST['cancelEdit'])) {
                // Redirigir a la página de perfil de usuario
                header('Location: ' . BASE_URL . '?route=profile');
            }
            // Si se enviaron datos para actualizar el perfil
            else {
                // Validar y procesar datos de actualización del perfil
                $user = new User();
                $user->updateUser($_POST['name'], $_POST['nick'], $_POST['dob'], $_POST['email'], $_POST['password'], $_POST['confirmPassword']);
                
                // Redirigir a la página de perfil de usuario
                header('Location: ' . BASE_URL . '?route=profile');
            }
        }
    }
    
    public function deleteAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar la solicitud de darse de baja
            $user = new User();
            $user->deleteUser($_POST['id']);
            
            // Redirigir a la página de inicio
            header('Location: ' . BASE_URL);
        }
    }
}
?>