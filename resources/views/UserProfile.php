<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo BOOTSTRAP; ?>bootstrap.min.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Videojuegos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text">Bienvenido, <?php echo $userName; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL . '?route=profile'; ?>">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL . '?route=collection'; ?>">Colección</a>
                    </li>
                    <li class="nav-item">
                        <form action="<?php echo BASE_URL . '?route=logout'; ?>" method="post">
                            <button type="submit" class="btn btn-link nav-link">Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Perfil de Usuario</h1>
        <!-- Formulario de perfil de usuario -->
        <form id="profileForm" action="<?php echo BASE_URL . '?route=updateProfile'; ?>" method="post">
            <!-- Campos del formulario -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $userFullName; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="nick" class="form-label">Apodo</label>
                <input type="text" class="form-control" id="nick" name="nick" value="<?php echo $userNick; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $userDOB; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $userEmail; ?>" disabled>
            </div>
            <!-- Botón para editar el perfil -->
            <button type="button" class="btn btn-primary" id="editProfileBtn" onclick="editProfile()">Editar Perfil</button>
            <!-- Botón para guardar los cambios -->
            <button type="submit" id="saveChangesBtn" class="btn btn-success" style="display: none;">Guardar Cambios</button>
            <!-- Botón para cancelar la edición -->
            <button type="button" class="btn btn-secondary" id="cancelEditBtn" style="display: none;" onclick="cancelEdit()">Cancelar</button>
        </form>

        <!-- Formulario para darse de baja -->
        <form action="<?php echo BASE_URL . '?route=deleteAccount'; ?>" method="post">
            <button type="submit" class="btn btn-danger mt-3">Darse de Baja</button>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="<?php echo BOOTSTRAP; ?>bootstrap.bundle.min.js"></script>
    <script>
    function editProfile() {
        // Habilitar los campos del formulario
        document.getElementById("name").disabled = false;
        document.getElementById("nick").disabled = false;
        document.getElementById("dob").disabled = false;
        document.getElementById("email").disabled = false;

        // Ocultar el botón de "Editar Perfil"
        document.getElementById("editProfileBtn").style.display = "none";
        // Mostrar los botones de "Guardar Cambios" y "Cancelar"
        document.getElementById("saveChangesBtn").style.display = "inline-block";
        document.getElementById("cancelEditBtn").style.display = "inline-block";
    }

    function cancelEdit() {
        // Deshabilitar los campos del formulario
        document.getElementById("name").disabled = true;
        document.getElementById("nick").disabled = true;
        document.getElementById("dob").disabled = true;
        document.getElementById("email").disabled = true;

        // Mostrar el botón de "Editar Perfil"
        document.getElementById("editProfileBtn").style.display = "inline-block";
        // Ocultar los botones de "Guardar Cambios" y "Cancelar"
        document.getElementById("saveChangesBtn").style.display = "none";
        document.getElementById("cancelEditBtn").style.display = "none";
    }
    </script>
</body>
</html>
