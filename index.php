<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=width-device, initial-scale=1">
	<title>COVID_19</title>
	<!-- <link rel="stylesheet" href="css/normalssize.css"> -->
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/estilos.css">
	<script type="text/javascript" src="js/prefixfree.min.js"></script>
</head>
<body>
	<section class="principal">
		<h1>Instituto Geografico Militar - Bolivia</h1>
		<h2>SISTEMA ÚNICO DE CERTIFICACIOMES</h2>
		<p>Sistema para la emición de los siguientes Certificados: Frontera, Puntos GPS Estaciones Continuas y Cartografia</p>
		<a href="#" class="bt-home" id="activarLogin"><i class="fa fa-sign-in separar" aria-hidden="true"></i>Ingresar</a>
		<a href="" class="bt-home" id="activarRegistro"><i class="fa fa-check-circle separar" aria-hidden="true"></i>Verificador</a>
		<p></p>
		<p>© Copyright 2001-2020 Copyright.es - Todos los Derechos Reservados </p>
	</section>
	<div class="login" id="login">
		<a href="" class="cerrar" id="cerrar"><i class="fa fa-times" aria-hidden="true"></i></a>
		<form action="validacion.php" method="post">
			<input type="text" name="username" required placeholder="Usuario">
			<input type="password" name="contrasena" requered placeholder="********">
			<!-- <input type="button" value="Entrar" >
			<button onclick = href='../MapCArto.html'>Entrar</button>-->
			<button type="submit" name="button" class="btn btn-primary">Entrar</button>
			<!--a href="../MapCArto.html" class="btn btn-primary">Entrar</a-->
		</form>
	</div>
	<div class="oscurecer" id="oscurecer"></div>
	<div class="registrar" id="registrar">
		<div class= "cerrarRegistro" id="cerrarRegistro">X</div>
		<h1>Verificar Certificado</h1>
		<form action="verificacion_certificado_frontera.php" method="post">
			<input type="number" name="nro_certificado" placeholder="Nro. de certificado" required>
			<input type="number" name="ci" placeholder="Cédula de Identidad (Solo números)" required>
			<button type="submit" name="buscar">Buscar</button>
			<!--input type="button" value="Crear"-->
		</form>
	</div>



	<script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="js/acciones.js"></script>
</body>
</html>
