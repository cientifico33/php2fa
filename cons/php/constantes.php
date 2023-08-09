<?php 
	date_default_timezone_set('America/Guatemala');

	// Nombre de la aplicación
	define('APP_NAME', 'CIT-Autocom');
	

	// Cantidad de tiempo en segundos que puede durar una sesión válida. El tiempo por defecto es 1 hora.
	define("SESSION_TIMEOUT", '3600');


	// ********* RUTAS *********
   	// Se define la URL del host para crear las rutas
   	define('URL', $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER['HTTP_HOST'].'/'.explode('/', $_SERVER['PHP_SELF'])[1].'/');

	// Nombre de la carpeta con los ficheros PHP necesarios **Modificar en caso de ser renombrada**
	define('CONS', 'cons/');

	// Página a la cuál será redirigido el usuario después de vincular el QR con su aplicación.
	define('DEFAULT_PAGE', URL . 'home');

	// Formulario de inicio de sisión
	define('LOGIN_FORM', URL . 'login');

	// Página a la cuál será redirigido el usuario después de vincular el QR con su aplicación.
	define('QR_CONFIG', URL . 'config_mfa');

	// Página de apoyo para completar la configuración.
	define('CHECK_CONFIG', URL . 'check_config');	

	// Página a la cuál será redirigido un usuario logueado que ya tiene configurado su mfa.
	define('MFA_CHECK', URL . 'mfa');

	// Verificar usuarios logueados
	define('VERIFY_LOGIN', URL . 'verify');


	// ********** CAPTCHA **********
	define('RECAPTCHA_SITE_KEY', '6LeKSSknAAAAAIqujJ09ztZxpZZVVHA906wQBSMV');
	define('RECAPTCHA_SECRET_KEY', '6LeKSSknAAAAAJKETYJbmLTaLC3KNAu6fkF2Wunn');

	// Score mínimo admitido para validar la interacción humana, calificación va de 0.0 a 1.0. Google recomienda un mínimo de 0.8 par aplicaciones sensibles.
	define('RECAPTCHA_SCORE', '0.8');


	// ********** BLOQUEO TEMPORAL DE CUENTAS POR EXCESO DE INTENTOS FALLIDOS **********
	// Cantidad de intentos de inicio de sesión permitidos antes de bloquear la cuenta temporalmente.
	define("INTENTOS_ADMITIDOS", '3');

	// Cantidad de tiempo en minutos que será bloqueada la cuenta después de alcanzar la cantidad de intentos fallidos.
	define("BLOCKED_MINS", '3');
?>