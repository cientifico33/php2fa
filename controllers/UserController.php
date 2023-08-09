// UserController.php
<?php

class UserController
{
    public function index()
    {
        // Aquí manejas la lógica para mostrar una lista de usuarios (lista de registros de la base de datos, por ejemplo)
        // y llamar a la vista adecuada para mostrarlos.
        // Puedes obtener los datos del modelo y enviarlos a la vista.
        // Por ejemplo:
        //$users = UserModel::getAllUsers(); // Método estático que obtiene todos los usuarios de la base de datos
        //require 'views/user/index.php'; // Muestra la vista con la lista de usuarios
        echo "test";
    }

    public function show()
    {
        // Aquí manejas la lógica para mostrar un usuario específico según su ID (o algún otro criterio).
        // Puedes obtener los datos del modelo y enviarlos a la vista.
        // Por ejemplo:
        $userId = $_GET['id'];
        $user = UserModel::getUserById($userId); // Método estático que obtiene un usuario de la base de datos según su ID
        require 'views/user/show.php'; // Muestra la vista con los detalles del usuario
    }

    // Otras acciones (métodos) relacionadas con usuarios, como crear, editar, eliminar, etc.
}
