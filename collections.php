<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require_once('config/const.php');
require_once(MODELS . 'Collection.php');
require_once(MODELS . 'Connection.php');

$collectionModel = new Collection();
$collectionModel->getCollections();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Archive - Colecciones</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo BOOTSTRAP; ?>css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo CSS; ?>passwords.css">
    <link rel="stylesheet" href="<?php echo CSS; ?>collections.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">Game Archive</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navbar items -->
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/videogame_collection/collections.php">Colecciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/videogame_collection/resources/views/contact.php">Contacto</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo ucfirst($_SESSION['user_nick']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?php echo ROUTER; ?>?action=user-profile"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Perfil</a></li>
                                <li><a class="dropdown-item" href="<?php echo VIEWS.'collections/view_collection.php?user='.$_SESSION['user_id']; ?>"><i class="fa fa-folder"></i>&nbsp;Colección</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="router.php?action=logout"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-auto">
                            <button type="button" class="btn btn-success rounded" data-bs-toggle="modal" data-bs-target="#loginModal">Entrar</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo ROUTER; ?>" method="post">
                        <div class="mb-3">
                            <label for="loginNick" class="form-label">Nick</label>
                            <input type="text" class="form-control" id="loginNick" name="nick" required aria-label="Nombre de usuario">
                            <div id="loginNickHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="loginPassword" name="password" required aria-label="Contraseña" onpaste="return false">
                                <button class="btn btn-outline-secondary password-toggle-icon" type="button" id="loginPasswordToggle" aria-label="Mostrar contraseña">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div id="loginPasswordHelp" class="form-text"></div>
                        </div>
                        <input type="hidden" name="action" value="login">
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <?php $dobInvalidCookie=isset($_COOKIE['dob_invalid']) ? true : false; ?>
                    <form action="<?php echo ROUTER; ?>" method="post" <?php if ($dobInvalidCookie) echo 'onsubmit="return false"'; ?>>
                        <div class="mb-3">
                            <label for="registerName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="registerName" name="name" required aria-label="Nombre">
                            <div id="registerNameHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="registerNick" class="form-label">Nick</label>
                            <input type="text" class="form-control" id="registerNick" name="nick" required aria-label="Nombre de usuario">
                            <div id="registerNickHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="registerDob" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="registerDob" name="dob" required aria-label="Fecha de nacimiento" max="<?php echo date('Y-m-d', strtotime('-14 years')); ?>">
                            <div id="registerDobHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required aria-label="Correo electrónico">
                            <div id="registerEmailHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="registerPassword" name="registerPassword" required aria-label="Contraseña" onpaste="return false">
                                <button class="btn btn-outline-secondary password-toggle-icon" type="button" id="registerPasswordToggle" aria-label="Mostrar contraseña">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength mb-3">
                                <div class="strength-bar"></div>
                                <div class="strength-text"></div>
                            </div>
                            <div class="pswd_info">
                                <div class="notify error">La contraseña debe cumplir los siguientes requisitos:</div>
                                <ul>
                                    <li id="letter" class="invalid">Al menos <strong>una letra</strong></li>
                                    <li id="capital" class="invalid">Al menos <strong>una letra mayúscula</strong></li>
                                    <li id="number" class="invalid">Al menos <strong>un número</strong></li>
                                    <li id="length" class="invalid">Al menos <strong>8 caracteres</strong></li>
                                    <li id="match" class="invalid">Las contraseñas <strong>deben coincidir</strong></li>
                                    <li id="blank" class="invalid">Las contraseñas <strong>no deben tener espacios</strong></li>
                                </ul>
                            </div>
                            <div id="registerPasswordHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required aria-label="Confirmar contraseña" onpaste="return false">
                                <button class="btn btn-outline-secondary password-toggle-icon" type="button" id="confirmPasswordToggle" aria-label="Mostrar contraseña">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div id="confirmPasswordHelp" class="form-text"></div>
                        </div>
                        <input type="hidden" name="action" value="register">
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>¿Ya tienes cuenta? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Inicia sesión aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collections -->
    <div class="container mt-4">
        <h1 class="text-center">Colecciones</h1>
        <?php $collectionModel->renderCollections(); ?>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="<?php echo BOOTSTRAP; ?>/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="<?php echo JS; ?>jquery/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Notify JavaScript -->
    <script src="<?php echo JS; ?>bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="<?php echo JS; ?>notifications.js"></script>
</body>
</html>