<?php
// Modelo

class LoginModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $state = [];
    }

    // Función para verificar el inicio de sesión utilizando PDO y consultas preparadas
    public function login($catalogo, $password) {

        try {
            $query = "SELECT id, catalogo, nombre, password, cuenta_bloqueada, intentos_fallidos FROM users WHERE catalogo = :catalogo";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':catalogo', $catalogo, PDO::PARAM_INT);
            $statement->execute();

            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // VERIFICAR USUARIO
            if ($user){
                //VERIFICAR BLOQUEO TEMPORAL
                if($user['cuenta_bloqueada']){
                    if ($this->check_block($user['id'])){
                        //USUARIO BLOQUEADO
                        $state['result'] = false;
                        $state['error'] = "Cuenta bloqueada temporalmente, inténtelo más tarde.";
                        return $state;
                    } else {
                        $state = $this->check_password($password,$user);
                        return $state;
                    }
                } else {
                    $state = $this->check_password($password,$user);
                    return $state;
                }                
            } else {
                //NO EXISTE EL USUARIO
                $state['result'] = false;
                $state['error'] = "Credenciales inválidas. Verifique sus datos.";
                return $state; // Credenciales inválidas
            }
        } catch (PDOException $e) {
            // Manejo de errores de la consulta
            $state['result'] = false;
            $state['error'] = "Error en la consulta: " . $e->getMessage();
            return $state;
        }
    }



    private function check_block($id){
        $stmt = $this->conn->prepare("SELECT bloqueada_hasta FROM usuarios_bloqueados WHERE user_id = :id ORDER BY bloqueada_hasta DESC LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $bloqueo = $stmt->fetch();
        // Comprobar hora de expiración de bloqueo
        if ($bloqueo && $bloqueo['bloqueada_hasta'] >= date('Y-m-d H:i:s')) {            
            return true; //La cuenta está temporalmente bloqueada
        } elseif ($bloqueo && $bloqueo['bloqueada_hasta'] < date('Y-m-d H:i:s')){
            return false; //Ya se cumplió el tiempo de bloqueo temporal
        }                  
    }

    private function check_password($password,$user){
        //USUARIO NO BLOQUEADO, VERIFICAR CONTRASEÑA
        if (password_verify($password, $user['password'])){
            $this->update_user($user['id'],0,true); //Contraseña coincide
            $this->iniciar_sesion($user);
        } else {
            $this->update_user($user['id'],$user['intentos_fallidos'],false); //Contraseña NO coincide
            $state['result'] = false;
            $state['error'] = "Credenciales inválidas. Verifique sus datos.";
            return $state;
        }
    }

    private function update_user($id, $intentosFallidos, $correcta){
        if ($correcta) {
            $stmt = $this->conn->prepare("UPDATE users SET intentos_fallidos = 0, cuenta_bloqueada = false  WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $intentosFallidos = $intentosFallidos + 1;
            $stmt = $this->conn->prepare("UPDATE users SET intentos_fallidos = ? WHERE id = ?");
            $stmt->execute([$intentosFallidos, $id]);
            if ($intentosFallidos != 0) { echo 'Intentos fallidos: ' . $intentosFallidos . '<br>'; }
            if ($intentosFallidos >= INTENTOS_ADMITIDOS) {
                // Obtén la dirección IP del usuario
                $ip = $_SERVER['REMOTE_ADDR'];
                // Bloquear la cuenta temporalmente
                $bloqueadaHasta = date('Y-m-d H:i:s', strtotime('+'.BLOCKED_MINS.' minutes'));
                $stmt = $this->conn->prepare("UPDATE users SET cuenta_bloqueada = 1 WHERE id = ?");
                $stmt->execute([$id]);
                // Guardar el registro del bloqueo
                $stmt = $this->conn->prepare("INSERT INTO usuarios_bloqueados (user_id, ip, bloqueada_hasta) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE bloqueada_hasta = ?");
                $stmt->execute([$id, $ip, $bloqueadaHasta, $bloqueadaHasta]);
            }
        }
    }

    private function iniciar_sesion($user)
    {
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

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['catalogo'] = $user['catalogo'];
        $_SESSION['user_nombre'] = $user['nombre'];
        session_regenerate_id(true);
        header('Location: '. VERIFY_LOGIN);
        die();
    }
}



?>
