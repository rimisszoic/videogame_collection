<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
require_once(dirname(__DIR__,3).'/config/const.php');
require_once(dirname(__DIR__,3).'/controller/ViewCollectionController.php');
require_once(dirname(__DIR__,3).'/model/Collection.php');
$collectionController = new ViewCollectionController();
$_SESSION['user_collection_id']=$_GET['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colección</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/passwords.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">Game Archive</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/videogame_collection/collections.php">Colecciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../contact.php">Contacto</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo ucfirst($_SESSION['user_nick']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" aria-current="page" href="/videogame_collection/router.php?action=user-profile"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Perfil</a></li>
                                <li><a class="dropdown-item" href="<?php VIEWS.'/collections/view_collection?user='.$_SESSION['user_id']; ?>"><i class="fa fa-folder"></i>&nbsp;Colección</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/videogame_collection/router.php?action=logout"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Cerrar Sesión</a></li>
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
                    <form action="/videogame_collection/router.php" method="post">
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
                    <form action="/videogame_collection/router.php" method="post" <?php if ($dobInvalidCookie) echo 'onsubmit="return false"'; ?>>
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
                            <div id="registerDobbHelp" class="form-text"></div>
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

    <?php if(isset($_SESSION['user_id']) && $_GET['user'] == $_SESSION['user_id']): ?>
    <!-- Formulario de búsqueda -->
    <div class="container-fluid mt-3">
        <div class="d-flex justify-content-center">
            <form id="search-form" method="POST">
                <input type="text" id="search-query" name="search_query" placeholder="Buscar juego...">
                <button type="submit">Buscar</button>
            </form>
        </div>
    </div>

    <!-- Lista de juegos encontrados -->
    <div class="container-fluid mt-3 mb-3" id="search-results-container" style="display: none;">
        <h2 class="mt-3 mb-3 text-center">Resultados de la Búsqueda</h2>
        <div id="search-results" class="container-fluid mt-3 mb-3"></div>
    </div>

    <!-- Formulario para registrar un juego -->
    <div class="container-fluid mt-3" id="register-game-form-container" style="display: none;">
        <h2 class="mb-3">Registrar Nuevo Juego</h2>
        <form id="register-game-form" method="POST" enctype="multipart/form-data" action="../../../controller/register_game.php">
            <div class="mb-3">
                <label for="game-title" class="form-label">Título del Juego</label>
                <input type="text" class="form-control" id="game-title" name="game_title" required aria-label="Título del juego">
            </div>
            <div class="mb-3">
                <label for="platform" class="form-label">Plataforma</label>
                <select class="form-select" id="platform" name="platform" required aria-label="Plataforma">
                    <?php
                    $collectionController->getPlatforms();
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Género</label>
                <select class="form-select" id="genre" name="genre" required aria-label="Género">
                    <?php
                    $collectionController->getGenres();
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="release-date" class="form-label">Fecha de Lanzamiento</label>
                <input type="date" class="form-control" id="release-date" name="release_date" required aria-label="Fecha de lanzamiento">
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label">Portada</label>
                <input type="file" class="form-control" id="cover" name="cover" required aria-label="Portada" onchange="previewImage(event)">
            </div>
            <!-- Vista previa de la imagen -->
            <div id="coverPreviewContainer" class="mb-3" style="display: none;">
                <label>Vista Previa:</label>
                <img id="coverPreview" src="#" alt="Vista previa de la portada del juego" class="img-fluid" style="max-width: 200px;">
            </div>
            <input type="hidden" name="action" value="register_game">
            <button type="submit" class="btn btn-primary">Registrar Juego</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Modal para mostrar la portada del juego -->
    <div class="modal fade" id="coverModal" tabindex="-1" aria-labelledby="coverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="coverModalLabel">Portada del Juego</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="coverImage" src="" class="img-fluid" alt="Portada del juego">
                </div>
            </div>
        </div>
    </div>

    <?php
    // Cargar los juegos de la colección
    $collectionController = new ViewCollectionController();
    $collectionController->getGames();
    ?>

    <!-- Bootstrap y scripts -->
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../js/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="../../js/notifications.js"></script>
    <script src="../../js/passwords.js"></script>
    <script>
        // Script para manejar la búsqueda de juegos
        $('#search-form').submit(function(event) {
            event.preventDefault(); // Evita el envío del formulario por defecto
            var formData = $(this).serialize(); // Serializa los datos del formulario
            $.ajax({
                type: 'POST',
                url: '../../../controller/search_game.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Limpia el contenedor de resultados de búsqueda
                    $('#search-results').empty();
                    if (response.error) {
                        // Si hay un error, muestra el mensaje de error
                        $('#search-results-container').hide();
                        $('#register-game-form-container').show();
                        $('#search-results-container').append('<div class="alert alert-danger" role="alert">' + response.error + '</div>');
                    } else {
                        // Muestra los juegos encontrados
                        $('#search-results-container').show();
                        $('#register-game-form-container').hide();
                        $.each(response, function(index, game) {
                            $('#search-results').append(
                                '<div class="card">' +
                                    '<div class="card-header">' + game.juego_nombre + '</div>' +
                                    '<div class="card-body">' +
                                        '<h5 class="card-title">Plataforma: ' + game.plataforma + '</h5>' +
                                        '<p class="card-text">Género: ' + game.genero + '</p>' +
                                        '<p class="card-text">Fecha de lanzamiento: ' + game.fecha_lanzamiento+ '</p>' +
                                        '<img src="' + game.portada + '" class="img-fluid cover-image" alt="Portada">' +
                                        '<br>'+
                                        '<a href="../../../controller/add_game.php?id=' + game.id + '" class="btn btn-primary add-game-btn mt-3">Añadir</a>' +
                                    '</div>' +
                                '</div>'
                            );
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Script para mostrar la portada del juego en el modal
        $(document).on('click', '.cover-image', function() {
            var coverSrc = $(this).attr('src');
            $('#coverImage').attr('src', coverSrc);
            $('#coverModal').modal('show');
        });

        // Script para previsualizar la imagen de la portada del juego
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var coverPreview = document.getElementById('coverPreview');
                coverPreview.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
            // Mostrar la vista previa
            document.getElementById('coverPreviewContainer').style.display = 'block';
        }
    </script>
</body>
</html>