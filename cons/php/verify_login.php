<?php 
require_once 'constantes.php';
require_once 'conexion.php';

session_start([
    // Tiempo de vida de la cookie de sesión (en segundos)
    'cookie_lifetime' => SESSION_TIMEOUT,
    // Solo se permite la transmisión de cookies a través de conexiones seguras (HTTPS)
    'cookie_secure' => true,
    // Las cookies solo son accesibles a través de HTTP y no a través de JavaScript
    'cookie_httponly' => true,
    // Utilizar solo cookies para almacenar la identificación de sesión
    'use_only_cookies' => true
]);
session_regenerate_id(true);

if (!isset($_SESSION['user_id'])) {
    // El usuario no ha iniciado sesión, redirigir al formulario de inicio de sesión.
    header('Location: '.LOGIN_FORM);
    exit();
} else {
    $stmt = $conn->prepare("SELECT secret_key FROM users WHERE catalogo = ?");
    $stmt->execute([$_SESSION['catalogo']]);
    $user = $stmt->fetch();
}

if ($user['secret_key'] === ''){
    // El usuario no ha configurado el doble factor de autenticación se dirige a la página de configuración.
    header('Location: '.QR_CONFIG);
} else if (!isset($_SESSION['2fa'])) {
    // El usuario no ha introducido el código de autenticación, dirigir a la página de verificación de mfa.
    header('Location: '.MFA_CHECK);
    exit();
}

?>