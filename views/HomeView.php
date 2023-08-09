<?php
	verificar_login();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>

	<!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">

    <style type="text/css">
    	html, body {
		  height: 100%;
		  margin: 0;
		  background-image: url("cons/images/3.jpg");
		  background-size: cover;
		  background-repeat: no-repeat;
		  background-position: center;
		}

		#container{
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			height: 100%;
		}

		h2{
			font-weight: bolder;
			color: white;
		}

		section{
			display: flex;
			flex-flow: column;
			justify-content: space-around;
			align-items: center;
		}

		#navbar{
			width: 100%;
			display: flex;
			flex-flow: row nowrap;
		}
    </style>
</head>
<body>
	<div id="container">
		<div id="navbar"></div>
		<div id="main">
			<section>
				<h2>¡Bienvenido <?php echo $_SESSION['user_nombre'] . '!'; ?> </h2>
				<a href="exit">Cerrar sesión</a>
			</section>
			<section>
				<h1>Something Awesome</h1>
				<h3>Is coming...</h3>
			</section>
		</div>
	</div>

</body>
</html>