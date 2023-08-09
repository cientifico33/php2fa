<!DOCTYPE html>
<html>
<head>
    <title>Registro de usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cons/estilos/estilos.css">
    
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">

    <!-- SweetAlert -->
    <script src="vendor/sweet-alert/sweetalert2.all.min.js"></script>
    <link href="vendor/sweet-alert/sweetalert2.min.css" rel="stylesheet">
    
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LeKSSknAAAAAIqujJ09ztZxpZZVVHA906wQBSMV&hl=es"></script>
    <script>
        // Función para enviar el formulario después de obtener el token reCAPTCHA
        function onSubmit() {
            grecaptcha.execute('6LeKSSknAAAAAIqujJ09ztZxpZZVVHA906wQBSMV', { action: 'main_login_form' }).then(function(token) {
                document.getElementById("g-recaptcha-response").value = token;
                document.getElementById("login-form").submit();
            });
        }
    </script>
</head>
<body>
    <div id="container">
        <div id="logos">
            <img src="cons/images/CIT.png" class="logo">
            <img src="cons/images/CRIC.png" class="logo">
        </div>
        <form id="register-form" action="" method="post" class="row g-3 needs-validation">
            <div id="FormContainer" class="shadow p-4 mb-5 bg-body rounded border bg-opacity-50">

                <img src="cons/images/autocom-new2.png" class="w-100" alt="logo">
                
                <div class="form-floating mb-3 col-5">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" autocomplete="one-time-code" required>
                    <label for="nombre">Nombre</label>
                </div>

                <!-- <label for="catalogo">Catálogo:</label> -->
                <!-- <input type="text" name="catalogo" autocomplete="one-time-code" required><br> -->
                <div class="form-floating mb-3 col-5">
                    <input type="number" class="form-control" id="catalogo" name="catalogo" placeholder="Catálogo" autocomplete="one-time-code" required>
                    <label for="catalogo">Catálogo</label>
                </div>


                <!-- <label for="password">Contraseña:</label>
                <input type="password" name="password" autocomplete="one-time-code" required><br> -->
                <div class="form-floating mb-3 col-5">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                    <label for="password">Contraseña</label>
                </div>

                <!-- Agregar un botón con evento onclick -->
                <button type="submit" class="shadow m-3 p-3 col-5 btn btn-dark btn-lg"><i class="bi-check-circle-fill"></i> Registrar </button>

                <a href="login">Iniciar Sesión</a>

            </div>
        </form>
    </div>
</body>
</html>
