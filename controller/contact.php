<?php
// controller/contact.php
require_once(dirname(__DIR__).'/model/Mailer.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validar los datos
    if (empty($name) || empty($email) || empty($message)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico no válido.";
    } else {
        // Crear instancia de la clase Mailer y enviar el correo
        try {
            $mailer = new Mailer();
            $subject = 'Nuevo mensaje de contacto';
            $body = "Nombre: $name<br>Correo electrónico: $email<br>Mensaje:<br>$message";
            $altBody = "Nombre: $name\nCorreo electrónico: $email\nMensaje:\n$message";
            $mailer->sendMail('rimiss@rimisszoic.live',$subject,$body,$altBody);
            $success = "Gracias por tu mensaje. Nos pondremos en contacto contigo pronto.";
            header("Location: /videogame_collection/index.php?result=ok&msg" . urlencode($success));
            exit();
        } catch (Exception $e) {
            $error = $e->getMessage();
            header("Location: /videogame_collection/index.php?result=error&msg" . urlencode($error));
            exit();
        }
    }
}
?>
