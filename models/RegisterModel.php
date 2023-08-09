<?php
// Modelo

class RegisterModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $state = [];
    }

    // Función para verificar el inicio de sesión utilizando PDO y consultas preparadas
    public function Registrar($nombre, $catalogo, $password) {

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (catalogo, nombre, password) VALUES (:catalogo, :nombre, :password)");
        $stmt->bindParam(':catalogo', $catalogo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':password', $password);

        try {
            $stmt->execute();
            return ("Registro exitoso. ¡Bienvenido, $nombre!");
        } catch (PDOException $e) {
            return ("Error al registrar: " . $e->getMessage() );
        }
    }
}



?>
