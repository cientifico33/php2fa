<!DOCTYPE html>
    <html>
    <head>
	<title>CIT - MFA</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cons/estilos/estilos.css">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">

    <!-- SweetAlert -->
    <script src="vendor/sweet-alert/sweetalert2.all.min.js"></script>
    <link href="vendor/sweet-alert/sweetalert2.min.css" rel="stylesheet">

	<!-- Agregar la etiqueta de script de reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LeKSSknAAAAAIqujJ09ztZxpZZVVHA906wQBSMV&hl=es"></script>
    <script>
        // Función para enviar el formulario después de obtener el token reCAPTCHA
        function onSubmit() {
            grecaptcha.execute('6LeKSSknAAAAAIqujJ09ztZxpZZVVHA906wQBSMV', { action: 'OneTimeCode' }).then(function(token) {
                document.getElementById("g-recaptcha-response").value = token;
                document.getElementById("MfaForm").submit();
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
            <div id="FormContainer" class="shadow p-4 mb-5 bg-body rounded border bg-opacity-50">
                <h2>Confirmación de autenticación</h2>
                <span class="instrucciones rounded" style="margin-bottom: 1rem;">Ingrese el código de acceso generado por la aplicación.</span>
        		<section>
        			<form id="MfaForm" action="" method="post">
    	    			<!-- <label>Código de autenticación: </label>
    	    			<input type="number" name="OneTimeCode" id="OneTimeCode"> -->
                        <div class="form-floating mb-3 col-5">
                            <input type="tel" name="OneTimeCode" id="OneTimeCode" class="form-control" placeholder="Código de autenticación" required>
                            <label for="OneTimeCode">Código de autenticación</label>
                        </div>
    	    			<!-- <button type="button" onclick="onSubmit()">Iniciar Sesión</button> -->
                        <button type="button" onclick="onSubmit()" class="shadow m-3 p-3 col-5 btn btn-dark btn-lg"><i class="bi-check-circle-fill"></i> Iniciar Sesión</button>
            			<!-- Agregar un campo oculto para almacenar el token reCAPTCHA -->
            			<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
    	    		</form>
        		</section>
            </div>
    	</div>
    </body>
    </html>