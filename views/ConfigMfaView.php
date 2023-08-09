<?php require_once("views/ModalsView.php"); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Vincular aplicación</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cons/estilos/estilos.css">

	<!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">

    <!-- SweetAlert -->
    <script src="vendor/sweet-alert/sweetalert2.all.min.js"></script>
    <link href="vendor/sweet-alert/sweetalert2.min.css" rel="stylesheet">

	<script src="vendor/components/jquery/jquery.min.js"></script>

</head>
<body>
	<div id="container">
		<div id="logos">
            <img src="cons/images/CIT.png" class="logo">
            <img src="cons/images/CRIC.png" class="logo">
        </div>
        <div id="login-form" action="" method="post" class="row g-3 needs-validation">
        	
		<div id="FormContainer" class="shadow p-4 mb-5 bg-body rounded border bg-opacity-50">
			<section>
				<h2>Configuración del doble factor de autenticación</h2>
				<span class="instrucciones">

					<p>Es necesario vincular su cuenta de Autocom con una aplicación compatible en su teléfono móvil. Para ver las instrucciones detalladas del procedimiento de descarga y configuración puede hacer clic sobre los íconos de las apicaciones compatibles que se muestran a continuación.</p>
					
					<p><b>Necesita vincular una aplicación para poder utilizar el sistema.</b></p>
				</span>
			</section>
			<section>
				    <table id="apps">
				    	<tr>
				    		<td>
				    			<img src="cons/images/apps/1_google.webp" class="shadow-lg" type="button" data-bs-toggle="modal" data-bs-target="#GoogleModal">
				    		</td>
				    		<td>
				    			<img src="cons/images/apps/2_microsoft.svg" class="shadow-lg" type="button" data-bs-toggle="modal" data-bs-target="#MicrosoftModal">
				    		</td>
				    		<td>
				    			<img src="cons/images/apps/3_authy.png" class="shadow-lg" type="button" data-bs-toggle="modal" data-bs-target="#AuthyModal">
				    		</td>
				    	</tr>
				    	<tr>
				    		<td>Google Authenticator</td>
				    		<td>Microsoft Authenticator</td>
				    		<td>Twilio Authy</td>
				    	</tr>
				    </table>
			</section>		
			<section>
				<?php echo '<img id="qr_code" src="'.$qr.'" />'; ?>
			</section>
			<section>
				<!-- <label for="2fa">Código:</label>
				<input type="number" name="2fa" id="2fa"> -->

				<div class="form-floating mb-3 col-5">
                    <input type="tel" class="form-control" id="2fa" name="2fa" placeholder="Código de único uso" autocomplete="one-time-code" required>
                    <label for="2fa">Código</label>
                </div>

				<!-- <button id="test2fa">Comprobar configuración</button> -->

				<button type="button" class="shadow m-3 p-3 col-5 btn btn-primary btn-lg" id="test2fa">Vincular</button>
			</section>
        </div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
            var secret_key = <?php echo json_encode($secret_key); ?>;
			$('#test2fa').click(function() {
                var code = $('#2fa').val();
            	var DEFAULT_PAGE = <?php echo json_encode(DEFAULT_PAGE); ?>;
                $.ajax({
                    url: <?php echo "'" . CHECK_CONFIG . "'"; ?>,
                    type: 'POST',
                    data: {
                    	code: code,
                    	secret_key: secret_key
                    },
                    success: function(response) {
                        console.log(response);
                        if(response.includes('Ha finalizado correctamente el proceso de vinculación')){
                        	// alert(response);
                        	Swal.fire({
	                            position: 'center',
	                            icon: 'success',
	                            title: 'Aplicación vinculada',
	                            text: response,
	                            showConfirmButton: true,
	                            confirmButtonText: 'Continuar',
	                            timerProgressBar: false,
	                            toast: false
	                        }).then((result) => {
							  /* Read more about isConfirmed, isDenied below */
							  if (result.isConfirmed) {
                        		window.location.href = DEFAULT_PAGE;
							  }
							})
                        } else {
                        	// alert(response);
                        	Swal.fire({
	                            position: 'top-end',
	                            icon: 'error',
	                            title: 'Error',
	                            text: response,
	                            showConfirmButton: false,
	                            timer: 2500,
	                            timerProgressBar: true,
	                            toast: true
	                        })
                        }
                    },
                    error: function() {
                        alert('Ha ocurrido un error en la solicitud.');
                    }
                });
            });
        });
	</script>
</body>
</html>

