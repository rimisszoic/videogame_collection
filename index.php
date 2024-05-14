<?php
session_start();
require_once('config/const.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videojuegos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo BOOTSTRAP; ?>css/bootstrap.min.css">
    <!-- Bootstrap Notify JavaScript -->
    <script src="<?php echo JS; ?>bootstrap-notify/bootstrap-notify.min.js"></script>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo CSS; ?>passwords.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Videojuegos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
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
                            <button type="button" class="btn btn-link nav-link" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesión
                            </button>
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
                    <form action="router.php" method="post">
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
                        <p>¿No tienes cuenta? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Regístrate aquí</a>
                        </p>
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
                    <form action="router.php" method="post">
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
                            <input type="date" class="form-control" id="registerDob" name="dob" required aria-label="Fecha de nacimiento">
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
                                    <li id="letter" class="invalid"><i class="fas fa-times"></i>Al menos <strong>una letra</strong></li>
                                    <li id="capital" class="invalid"><i class="fas fa-times"></i>Al menos <strong>una letra mayúscula</strong></li>
                                    <li id="number" class="invalid"><i class="fas fa-times"></i>Al menos <strong>un número</strong></li>
                                    <li id="length" class="invalid"><i class="fas fa-times"></i>Al menos <strong>8 caracteres</strong></li>
                                    <li id="null" class="invalid"><i class="fas fa-times"></i>Debe <strong>confirmar la contraseña</strong></li>
                                    <li id="match" class="invalid"><i class="fas fa-times"></i>Las contraseñas <strong>deben coincidir</strong></li>
                                    <li id="blank" class="invalid"><i class="fas fa-times"></i>Las contraseñas <strong>no deben tener espacios</strong></li>
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

    <!-- Bootstrap Bundle with Popper -->
    <script src="<?php echo BOOTSTRAP; ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo JS; ?>jquery/jquery-3.7.1.min.js"></script>
    <script src="<?php echo JS; ?>notifications.js"></script>
    <script src="<?php echo JS; ?>passwords.js"></script>
</body>
</html>