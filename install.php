<?php
header('Content-Type: application/json');

// Verificar si el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores enviados por el formulario
    $dbHost = $_POST['databaseHost'];
    $dbName = $_POST['databaseName'];
    $dbUser = $_POST['databaseUser'];
    $dbPassword = $_POST['databasePassword'];
    $emailHost = $_POST['emailHost'];
    $emailUser = $_POST['emailUser'];
    $emailPassword = $_POST['emailPassword'];

    // Ruta al archivo const.php
    $constFilePath = 'const.php';

    // Leer el contenido del archivo const.php
    $constFileContent = file_get_contents($constFilePath);

    // Reemplazar las constantes de base de datos
    $constFileContent = preg_replace("/define\('DB_HOST',\s*'[^']*'\);/", "define('DB_HOST', '$dbHost');", $constFileContent);
    $constFileContent = preg_replace("/define\('DB_USER',\s*'[^']*'\);/", "define('DB_USER', '$dbUser');", $constFileContent);
    $constFileContent = preg_replace("/define\('DB_PWD',\s*'[^']*'\);/", "define('DB_PWD', '$dbPassword');", $constFileContent);
    $constFileContent = preg_replace("/define\('DBNAME',\s*'[^']*'\);/", "define('DBNAME', '$dbName');", $constFileContent);

    // Reemplazar las constantes de correo electrónico
    $constFileContent = preg_replace("/define\('EMAIL_HOST',\s*'[^']*'\);/", "define('EMAIL_HOST', '$emailHost');", $constFileContent);
    $constFileContent = preg_replace("/define\('EMAIL_USER',\s*'[^']*'\);/", "define('EMAIL_USER', '$emailUser');", $constFileContent);
    $constFileContent = preg_replace("/define\('EMAIL_PWD',\s*'[^']*'\);/", "define('EMAIL_PWD', '$emailPassword');", $constFileContent);

    // Escribir el contenido modificado de vuelta al archivo const.php
    if (file_put_contents($constFilePath, $constFileContent) !== false) {
        try {
            // Conectar a la base de datos usando PDO
            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8";
            $pdo = new PDO($dsn, $dbUser, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Leer el contenido del archivo schema.sql
            $schemaFilePath = 'schema.sql';
            $schemaSql = file_get_contents($schemaFilePath);

            // Ejecutar el contenido del archivo schema.sql
            $pdo->exec($schemaSql);

            echo json_encode(['success' => true, 'message' => 'Las constantes y la base de datos se han actualizado correctamente.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión o ejecución en la base de datos: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar las constantes.']);
    }
}
?>