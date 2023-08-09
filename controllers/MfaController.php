<?php

class MfaController
{
    private $conn; // Propiedad para almacenar la conexión PDO

    public function __construct($conn)
    {
        $this->conn = $conn; // Almacenar la conexión en la propiedad $conn
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

        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['user_id'])) {
            // El usuario no ha iniciado sesión, redirigir al formulario de inicio de sesión
            header('Location: '.LOGIN_FORM);
            exit();
        }
    }

    // Comprobar 2fa OneTimeCode
    public function check_user_mfa()
    {
        //Se carga la vista
        require 'views/MfaView.php';

        // Verificar si el formulario fue enviado
        if (isset($_POST['OneTimeCode']) && isset($_POST['g-recaptcha-response'])) {
            // Se obtiene el token reCAPTCHA desde el formulario
            $recaptcha_token = $_POST['g-recaptcha-response'];

            // Se verifica validez del token reCAPTCHA con la API de Google
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

            // Se verifica la respuesta de reCAPTCHA y el score válido
            if ($recaptcha_response && $recaptcha_response->success && $recaptcha_response->score >= RECAPTCHA_SCORE) {
                // Cargamos el modelo
                require_once 'models/MfaModel.php';

                // Instanciamos el modelo pasándole la conexión PDO
                $MfaModel = new MfaModel($this->conn);

                $catalogo = $_SESSION['catalogo'];
                $OneTimeCode = $_POST['OneTimeCode'];

                if($MfaModel->CheckUserMfa($catalogo,$OneTimeCode)){
                    header('Location: ' . DEFAULT_PAGE);
                } else {
                    // echo "Código inválido";
                    echo '<script type="text/javascript">';
                    echo "Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error',
                            text: 'Código inválido',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            toast: true
                        });";
                    echo '</script>';
                }
            } else {
                // Token reCAPTCHA inválido o puntuación insuficiente
                echo "Actividad sospechosa detectada. Por favor deshabilite cualquier proxy o extensión que esté en uso e inténtalo nuevamente.";
            }
        } else {
            echo "Sesión:<br>";
            var_dump($_SESSION);
            echo "<br>";
            echo "Datos:";
            echo "<br>";
            var_dump($_POST);
            echo "<br>";
        }
    }



    // Generar Secret Key aleatorio y generar QR
    public function config_mfa()
    {   
        // Impedir el ingreso a configuración de MFA a usuarios que ya lo hayan configurado
        require_once 'models/MfaModel.php';
        $MfaModel = new MfaModel($this->conn);
        if ($MfaModel->ConsultarSecretKey()){

            //Se genera una clave secreta para el usuario $username
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret_key = $google2fa->generateSecretKey();

            //Generar QR
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
                  
            $text = $google2fa->getQRCodeUrl(
                APP_NAME,
                $_SESSION['catalogo'],
                $secret_key
            );
                
            $qr = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl='.$text;

            //Se carga la vista
            require 'views/ConfigMfaView.php';            
        } else {
            header('Location: '.MFA_CHECK);
        }
    }


    // Función utilizada por consulta ajax para verificar la correcta configuración de la aplicación móvil
    public function check_config(){
        // Cargamos el modelo
        require_once 'models/MfaModel.php';
        $MfaModel = new MfaModel($this->conn);


        $code = $_POST['code'];
        $secret_key = $_POST['secret_key'];        

        if ($MfaModel->ConfigMfa($secret_key,$code)) {
            echo "Ha finalizado correctamente el proceso de vinculación ";
        } else {
            echo "Código incorrecto";
        }
    }

    
}

?>

