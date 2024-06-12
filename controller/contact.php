<?php
// controller/contact.php
require_once(dirname(__DIR__).'/resources/mailer/src/PHPMailer.php');
require_once(dirname(__DIR__).'/resources/mailer/src/Exception.php');
require_once(dirname(__DIR__).'/resources/mailer/src/SMTP.php');
require_once(dirname(__DIR__).'/resources/mailer/Mailer.php');

use PHPMailer\PHPMailer\Exception;

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
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    // Redirigir con el resultado
    if (isset($error)) {
        header("Location: ../view/contact.php?result=ok&msg" . urlencode($error));
    } else {
        header("Location: ../view/contact.php?result=error&msg" . urlencode($success));
    }
    exit();
}
?>
