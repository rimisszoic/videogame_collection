<?php
session_start();
require_once('config/const.php');
require_once('router.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videojuegos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo BOOTSTRAP; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo JS; ?>bootstrap-notify/bootstrap-notify.min.js">
</head>
<body>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Videojuegos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $_SESSION['nick']; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Perfil</a></li>
                                <li><a class="dropdown-item" href="#">Colección</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <button type="button" class="btn btn-link nav-link" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesión</button>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo BASE_URL . '?route=login' ?>" method="post">
                        <div class="mb-3">
                            <label for="loginNick" class="form-label">Nick</label>
                            <input type="text" class="form-control" id="loginNick" name="nick" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>¿No tienes cuenta? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Regístrate aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Registrarse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo BASE_URL . '?route=register' ?>" method="post">
                        <div class="mb-3">
                            <label for="registerName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="registerName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerNick" class="form-label">Nick</label>
                            <input type="text" class="form-control" id="registerNick" name="nick" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerDob" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="registerDob" name="dob" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="registerPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>¿Ya tienes cuenta? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Inicia sesión aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="<?php echo BOOTSTRAP; ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo JS; ?>jquery/jquery-3.7.1.min.js"></script>
    <script src="<?php echo JS; ?>bootstrap-notify/bootstrap-notify.min.js"></script>
    <script>
    $(document).ready(function() {
        <?php if(isset($_GET['result']) && isset($_GET['msg'])): ?>
            <?php if($_GET['result'] === 'ok'): ?>
                showNotification("<?php echo $_GET['msg']; ?>", "success");
            <?php elseif($_GET['result'] === 'error'): ?>
                showNotification("<?php echo $_GET['msg']; ?>", "error");
            <?php endif; ?>
        <?php endif; ?>

        function showNotification(message, type) {
            var title = (type && type === 'success') ? 'Éxito' : 'Error';
            $.notify({
                title: title,
                message: message,
                icon: type === 'success' ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove'
            },{
                type: type,
                element: 'body',
                showProgressbar: false,
                placement: {
                    from: "top",
                    align: "right"
                },
                offset: 20,
                spacing: 10,
                z_index: 1031,
                delay: 3300,
                timer: 1000,
                url_target: '_blank',
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutRight'
                },
                onShow: null,
                onShown: null,
                onClose: null,
                onClosed: null,
                icon_type: 'class'
            });
        }
    });
    </script>
</body>
</html>