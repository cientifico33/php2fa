<?php

class RegisterController
{
    private $conn; // Propiedad para almacenar la conexión PDO

    public function __construct($conn)
    {
        $this->conn = $conn; // Almacenar la conexión en la propiedad $conn
    }

    public function index()
    {
        //Se carga la vista
        require 'views/RegisterView.php';

        // Se verifica si el formulario fue enviado
        if (isset($_POST['catalogo']) && isset($_POST['password']) && isset($_POST['nombre'])) {
            

                // Sanitizamos los datos del formulario
                $catalogo = filter_var(trim($_POST['catalogo']), FILTER_SANITIZE_NUMBER_INT);
                $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
                $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);

                // Se carga el modelo
                require_once 'models/RegisterModel.php';

                // Instanciamos el modelo pasándole la conexión PDO
                $RegisterModel = new RegisterModel($this->conn);

                if($state = $RegisterModel->Registrar($nombre,$catalogo,$password)){
                    if (str_contains($state,"Registro exitoso") )
                    {
                        header('Location: login');
                    } else {
                    echo '<script type="text/javascript">';
                    echo "Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error',
                            text: '".$state."',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            toast: true
                        });";
                    echo '</script>';
                }
            } else {                
                echo '<script type="text/javascript">';
                echo 'Swal.fire("¡Alerta!", "Ingrese todos los datos e intente de nuevo.", "warning");';
                echo '</script>';
            }
        }
    }
}


?>