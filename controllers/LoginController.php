<?php

class LoginController
{
    private $conn; // Propiedad para almacenar la conexión PDO

    public function __construct($conn)
    {
        $this->conn = $conn; // Almacenar la conexión en la propiedad $conn
    }

    public function index()
    {
        //Se carga la vista
        require 'views/LoginView.php';

        // Se verifica si el formulario fue enviado
        if (isset($_POST['catalogo']) && isset($_POST['password']) && isset($_POST['g-recaptcha-response'])) {
            // Se obtiene el token reCAPTCHA desde el formulario
            $recaptcha_token = $_POST['g-recaptcha-response'];

            // Se consulta el token reCAPTCHA con la API de Google
            $recaptcha_secret = RECAPTCHA_SECRET_KEY;
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_data = array(
                'secret' => $recaptcha_secret,
                'response' => $recaptcha_token
            );

            $recaptcha_options = array(
                'http' => array(
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'method' => 'POST',
                    'content' => http_build_query($recaptcha_data)
                )
            );

            $recaptcha_context = stream_context_create($recaptcha_options);
            $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
            $recaptcha_response = json_decode($recaptcha_result);

            // Se verifica la respuesta de la API y que el score esté dentro del paármetro admitido
            if ($recaptcha_response && $recaptcha_response->success && $recaptcha_response->score >= RECAPTCHA_SCORE) {

                // Sanitizamos los datos del formulario
                $catalogo = filter_var(trim($_POST['catalogo']), FILTER_SANITIZE_NUMBER_INT);
                $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

                // Se carga el modelo
                require_once 'models/LoginModel.php';

                // Instanciamos el modelo pasándole la conexión PDO
                $LoginModel = new LoginModel($this->conn);

                if($state = $LoginModel->login($catalogo,$password)){
                    // echo $state['error'];
                    echo '<script type="text/javascript">';
                    echo "Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error',
                            text: '".$state['error']."',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            toast: true
                        });";
                    echo '</script>';
                }
            } else {
                // Token reCAPTCHA inválido o puntuación insuficiente
                // $login_error = "Actividad sospechosa detectada. Por favor deshabilite cualquier proxy o extensión que esté en uso e inténtalo nuevamente.";
                var_dump($recaptcha_response);
                // $score = $recaptcha_response->score;
                echo '<script type="text/javascript">';
                echo 'Swal.fire("¡Alerta!", "Actividad sospechosa detectada. Por favor deshabilite cualquier proxy o extensión que esté en uso e inténtalo nuevamente. Score: <?php echo $score; ?>", "warning");';
                echo '</script>';
            }
        }
    }



    // Otras acciones (métodos) relacionadas con usuarios, como crear, editar, eliminar, etc.

    public function verify_login(){
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
        var_dump($_SESSION);

        if (!isset($_SESSION['user_id'])) {
            // El usuario no ha iniciado sesión, redirigir al formulario de inicio de sesión.
            header('Location: '.LOGIN_FORM);
            exit();
        } else {
            $stmt = $this->conn->prepare("SELECT secret_key FROM users WHERE catalogo = ?");
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
    }

    public function cerrar_sesion()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: '.LOGIN_FORM);
        exit;
    }
}


?>