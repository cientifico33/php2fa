<?php 

class MfaModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $state = [];
    }

    public function CheckUserMfa($catalogo,$OneTimeCode)
    {
        // echo "Catálogo: ".$catalogo;
        try {
            $query = "SELECT secret_key FROM users WHERE catalogo = :catalogo";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':catalogo', $catalogo, PDO::PARAM_INT);
            $statement->execute();

            $user = $statement->fetch(PDO::FETCH_ASSOC);
            $secret_key = $user['secret_key'];

            if ($user){
                $OneTimeCode = filter_var(trim($_POST['OneTimeCode']), FILTER_SANITIZE_NUMBER_INT);
                // echo "<br>OneTimeCode: ".$OneTimeCode;
                // echo "<br>secret_key: ".$secret_key."<br><br>";
                if ($this->check($secret_key,$OneTimeCode)) {
                    $_SESSION['2fa'] = true;
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            // Manejo de errores de la consulta
            echo "Error en la consulta: " . $e->getMessage();
            die();
        }
    }

    public function ConfigMfa($secret_key,$code)
    {
        if (isset($code) AND isset($secret_key)) {
            $code = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
            $secret_key = htmlspecialchars($secret_key, ENT_QUOTES, 'UTF-8');

            //Verificar código
            if ($this->check($secret_key,$code)){
                $_SESSION['2fa'] = true;
                if ( $this->guardar_secret_key($_SESSION['user_id'], $secret_key) ) {
                    // echo "TODO COMPLETO ";
                    return true;
                } else {
                    // echo "NO SE PUDO GUARDAR ";
                    return false;
                }
            } else {
                // echo "NO SE PUDO VALIDAR ";
                return false;
            }
        } else {
            echo "No se recibieron datos.";
            return false;
        }
    }

    // Guardar el código secreto del usuario en la tabla 'users'
    private function guardar_secret_key($user_id, $secret_key){
        try {
            $sql = "UPDATE users SET secret_key = :secretKey WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':secretKey', $secret_key);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            // Cerrar la conexión a la base de datos
            $this->conn = null;
            return true;
        } catch (PDOException $e) {
            echo "Error al conectar a la base de datos: " . $e->getMessage();
            return false;
        }
    }

    private function check($secret_key,$OneTimeCode){
        if (preg_match("/^\d{6}$/", $OneTimeCode)) {

            //Verificar código
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $valid = $google2fa->verifyKey($secret_key, $OneTimeCode, 8);
            if ($google2fa->verifyKey($secret_key, $OneTimeCode)) {
                // Código válido
                return true;
            } else {
                return false;
            }                    
        } else {
            return false;
        }
    }

    public function ConsultarSecretKey(){
        try
        {
            $stmt = $this->conn->prepare("SELECT secret_key FROM users WHERE catalogo = ?");
            $stmt->execute([$_SESSION['catalogo']]);
            $user = $stmt->fetch();
            if ($user && $user['secret_key'] != ''){
                return false;
            } else { return true; }
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>