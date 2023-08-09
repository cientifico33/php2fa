<?php 

function verificar_login(){
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

    if (!isset($_SESSION) || !isset($_SESSION['catalogo']) || !isset($_SESSION['2fa'])) {
    	header('Location: '.LOGIN_FORM);
    	if (!$_SESSION['2fa'] === true){
    		header('Location: '.MFA_CHECK);
    	}
    }
}


 ?>