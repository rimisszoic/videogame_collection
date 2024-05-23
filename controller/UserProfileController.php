<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include_once(MODELS.'/User.php');

class UserProfile{
    public function __construct(){
        $this->user = new User();
    }

    public function index(){
        if(isset($_SESSION['user_id'])){
            $user = $this->user->getUserObject();
            require_once(VIEWS.'/UserProfile.php');
        }else{
            header('Location: '.BASE_URL.'?result=error&msg='.urlencode('No tienes permisos para acceder a esta página.'));
        }
    }

    public function updateProfile(){
        if(isset($_SESSION['user_id'])){
            $name=$_POST['name'];
            $nick=$_POST['nick'];
            $dob=$_POST['dob'];
            $email=$_POST['email'];
            $password=$_POST['current_password'];
            $new_password=$_POST['new_password'];
            $confirm_password=$_POST['confirm_password'];
            $result = $this->user->updateUser($name, $nick, $dob, $email, $password);

            if(!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])){
                $user = $this->user->getUser();
                if(password_verify($_POST['current_password'], $user->getPassword())){
                    if($new_password === $confirm_password){
                        $result = $this->user->updateUser($name, $email, $new_password);
                        if($result){
                            header('Location: '.BASE_URL.'?result=ok&msg='.urlencode('Perfil actualizado correctamente.'));
                        }else{
                            header('Location: '.BASE_URL.'?result=error&msg='.urlencode('No se pudo actualizar el perfil.'));
                        }
                    }else{
                        header('Location: '.BASE_URL.'?result=error&msg='.urlencode('Las contraseñas no coinciden.'));
                    }
                }else{
                    header('Location: '.BASE_URL.'?result=error&msg='.urlencode('La contraseña actual es incorrecta.'));
                }
            } else {
                if($result){
                    header('Location: '.BASE_URL.'?result=success&msg='.urlencode('Perfil actualizado correctamente.'));
                }else{
                    header('Location: '.BASE_URL.'?result=error&msg='.urlencode('No se pudo actualizar el perfil.'));
                }
            }
        }
    }
}
?>