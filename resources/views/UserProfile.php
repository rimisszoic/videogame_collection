<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require_once(MODELS.'/User.php');
$user=new User();
$user=$user->getUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo BOOTSTRAP; ?>/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="<?php echo CSS; ?>animations.css">
    <link rel="stylesheet" href="<?php echo CSS; ?>user-profile.css">
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
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ROOT; ?>collections">Colecciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo ucfirst($_SESSION['user_nick']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" aria-current="page" href="<?php ROUTER; ?>?action=user-profile"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Perfil</a></li>
                                <li><a class="dropdown-item" href="<?php VIEWS.'/collections/view_collection?user='.$_SESSION['user_id']; ?>"><i class="fa fa-folder"></i>&nbsp;Colección</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php ROUTER; ?>?action=logout"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-5">
        <h1>Perfil de Usuario</h1>
        <!-- Formulario de perfil de usuario -->
        <form id="profileForm" action="<?php echo ROUTER ?>?action=updateProfile" method="post">
            <!-- Campos del formulario -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->user->getFullName(); ?>" disabled aria-disabled="true">
            </div>
            <div class="mb-3">
                <label for="nick" class="form-label">Apodo</label>
                <input type="text" class="form-control" id="nick" name="nick" value="<?php print $this->user->getNickName(); ?>" disabled aria-disabled="true">
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="dob" name="dob" value="<?php print $this->user->getDateOfBirth(); ?>" disabled aria-disabled="true">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php print $this->user->getEmail(); ?>" disabled aria-disabled="true">
            </div>

            <h2 class="mt-5">Cambiar Contraseña</h2>
            <div class="mb-3">
                <label for="current_password" class="form-label">Contraseña Actual</label>
                <input type="password" class="form-control" id="current_password" name="current_password" disabled aria-disabled="true">
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" id="new_password" name="new_password" disabled aria-disabled="true">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" disabled aria-disabled="true">
            </div>

            <!-- Botones para editar y guardar cambios -->
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-primary" id="editProfileBtn">Editar Perfil</button>
                <div>
                    <button type="submit" id="saveChangesBtn" class="btn btn-success">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" style="display: none;" id="cancelEditBtn">Cancelar</button>
                </div>
            </div>
            <input type="hidden" name="action" value="update-profile">
        </form>
    </div>

    <div class="container mt-5">
        <h2>Eliminar Cuenta</h2>
        <p>Si decides eliminar tu cuenta, todos tus datos y colecciones serán eliminados de forma permanente.</p>

        <!-- Botón para abrir la ventana modal -->
        <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            Eliminar Cuenta
        </button>
    </div>

    <!-- Ventana modal de confirmación de eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación de Cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.
                    Todos tus datos y colecciones serán eliminados de forma permanente.
                </div>
                <div class="modal-footer">
                    <!-- Botón para confirmar la eliminación -->
<<<<<<< HEAD
                    <form action="<?php echo ROUTER; ?>" method="post" class="d-flex justify-content-between w-100">
                        <input type="hidden" name="action" value="delete-account">
                        <button type="button" class="btn btn-success me-2" data-bs-dismiss="modal">Cancelar</button>
=======
                    <form action="<?php echo ROUTER; ?>" method="post">
                        <input type="hidden" name="action" value="delete-account">
>>>>>>> aed674e701dca1fe8b4cb1fa9fac086f377c54dd
                        <button type="submit" class="btn btn-danger">Eliminar Cuenta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="<?php echo BOOTSTRAP; ?>/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="<?php echo JS ?>notifications.js"></script>


<<<<<<< HEAD
    <script>
        // Prevenir el envío del formulario si no se han realizado cambios
        document.getElementById('profileForm').addEventListener('submit', function(event) {
            if (document.getElementById('name').disabled && document.getElementById('nick').disabled && document.getElementById('dob').disabled && document.getElementById('email').disabled && document.getElementById('current_password').disabled && document.getElementById('new_password').disabled && document.getElementById('confirm_password').disabled) {
                event.preventDefault();
            }
        });

        // // Anular el copiado de contraseñas y pegado en campos de contraseña
        // document.querySelectorAll('input[type="password"]').forEach(function(input) {
        //     input.addEventListener('paste', function(event) {
        //         event.preventDefault();
        //         return false;
        //     });
        //     input.addEventListener('copy', function(event) {
        //         event.preventDefault();
        //         return false;
        //     });
        // });

        function editProfile() {
            document.getElementById('name').disabled = false;
            document.getElementById('name').setAttribute('aria-disabled', 'false');
            document.getElementById('nick').disabled = false;
            document.getElementById('nick').setAttribute('aria-disabled', 'false');
            document.getElementById('dob').disabled = false;
            document.getElementById('dob').setAttribute('aria-disabled', 'false');
            document.getElementById('email').disabled = false;
            document.getElementById('email').setAttribute('aria-disabled', 'false');
            document.getElementById('current_password').disabled = false;
            document.getElementById('current_password').setAttribute('aria-disabled', 'false');
            document.getElementById('new_password').disabled = false;
            document.getElementById('new_password').setAttribute('aria-disabled', 'false');
            document.getElementById('confirm_password').disabled = false;
            document.getElementById('confirm_password').setAttribute('aria-disabled', 'false');
            document.getElementById('editProfileBtn').style.display = 'none';
            document.getElementById('saveChangesBtn').style.display = 'inline-block';
            document.getElementById('cancelEditBtn').style.display = 'inline-block';
        }

        function cancelEdit() {
            document.getElementById('name').disabled = true;
            document.getElementById('name').setAttribute('aria-disabled', 'true');
            document.getElementById('nick').disabled = true;
            document.getElementById('nick').setAttribute('aria-disabled', 'true');
            document.getElementById('dob').disabled = true;
            document.getElementById('dob').setAttribute('aria-disabled', 'true');
            document.getElementById('email').disabled = true;
            document.getElementById('email').setAttribute('aria-disabled', 'true');
            document.getElementById('current_password').disabled = true;
            document.getElementById('current_password').setAttribute('aria-disabled', 'true');
            document.getElementById('new_password').disabled = true;
            document.getElementById('new_password').setAttribute('aria-disabled', 'true');
            document.getElementById('confirm_password').disabled = true;
            document.getElementById('confirm_password').setAttribute('aria-disabled', 'true');
            document.getElementById('editProfileBtn').style.display = 'inline-block';
            document.getElementById('saveChangesBtn').style.display = 'none';
            document.getElementById('cancelEditBtn').style.display = 'none';
        }

        document.getElementById('editProfileBtn').addEventListener('click', editProfile);

        document.getElementById('saveChangesBtn').addEventListener('click', function() {
            swal({
                title: '¿Estás seguro?',
                text: '¿Deseas guardar los cambios realizados en tu perfil?',
                icon: 'warning',
                buttons: ['Cancelar', 'Guardar Cambios'],
                dangerMode: true
            }).then((willSave) => {
                if (willSave) {
                    document.getElementById('profileForm').submit();
                }
            });
        });
        document.getElementById('cancelEditBtn').addEventListener('click', cancelEdit);
    </script>
=======
    <script src="<?php echo JS; ?>/user-profile.js"></script>
>>>>>>> aed674e701dca1fe8b4cb1fa9fac086f377c54dd
</body>
</html>