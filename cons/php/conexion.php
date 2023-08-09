<?php
    date_default_timezone_set('America/Guatemala');

    $host = 'localhost'; // Cambia 'nombre_del_host' por el nombre o dirección IP del host de tu base de datos MySQL.
    $db = 'php2fa'; // Cambia 'nombre_de_la_base_de_datos' por el nombre de tu base de datos.
    $user = 'root'; // Cambia 'nombre_de_usuario' por el nombre de usuario de tu base de datos.
    $password = ''; // Cambia 'contraseña' por la contraseña de tu base de datos.

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Agrega cualquier otra configuración adicional que necesites, como el juego de caracteres o el modo de emulación.

    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
?>
