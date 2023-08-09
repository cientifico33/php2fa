<?php

// Carga del archivo de configuración y otras inclusiones necesarias
require_once 'cons/php/constantes.php';
require_once 'cons/php/conexion.php';
require_once 'cons/php/functions.php';
include ("vendor/autoload.php");

// Obtiene la ruta de la solicitud del usuario desde $_GET['url'])
$url = isset($_GET['url']) ? $_GET['url'] : 'login';


// Mapeo de rutas a controladores y acciones
$routes = [
    'home' => ['controller' => 'HomeController', 'action' => 'index'],
    'registro' => ['controller' => 'RegisterController', 'action' => 'index'],
    'login' => ['controller' => 'LoginController', 'action' => 'index'],
    'verify' => ['controller' => 'LoginController', 'action' => 'verify_login'],
    'mfa' => ['controller' => 'MfaController', 'action' => 'check_user_mfa'],
    'config_mfa' => ['controller' => 'MfaController', 'action' => 'config_mfa'],
    'check_config' => ['controller' => 'MfaController', 'action' => 'check_config'],
    'exit' => ['controller' => 'LoginController', 'action' => 'cerrar_sesion'],
];

// Verifica si la ruta solicitada existe en el mapeo
if (array_key_exists($url, $routes)) {
    $controllerName = $routes[$url]['controller'];
    $actionName = $routes[$url]['action'];
    // Incluye el archivo del controlador adecuado
    require_once 'controllers/' . $controllerName . '.php';
    // Crea una instancia del controlador y llama a la acción
    $controller = new $controllerName($conn);
    $controller->$actionName();
} else {
    // Manejar página no encontrada
    echo 'Página no encontrada.<br>';
    echo $url;
}
