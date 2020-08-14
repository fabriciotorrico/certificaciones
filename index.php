<?php
		include("inc.config.php");
		session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>SIIM WEB</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="viewport" content="width=360px"/>
	<link href="css/estilos.css" rel="stylesheet">
</head>

<body>

<!--script language=javascript>
	function ventanaSecundaria (URL){
	   window.open(URL,"ventana1","width=550,height=350,scrollbars=NO,top=150, left=200")
	}
	ventanaSecundaria("importante.php");
</script-->

<center>
<div class="contenedor">
	<header>
  	<div>
  		<img src="imagenes/encabezado_siim_web.jpg" width="900" height="300"  alt="SIIM WEB">
    </div>
  </header>
	<br /> <br />

	<div>
		<p class="subtitulo">
			<label>SISTEMA INTEGRADO DE INGRESOS MUNICIPALES - WEB</label>
		</p>
	</div>
  <section>
  	<br /> <br />
    <form action="validacion.php" method="post" name="form1" id="form1">
      <div class="fondo">
				<p class="subtitulo" style="color:#FF0000">
				<?php
					//Si la variable de sesion establece que el usuario o pw es incorrecto, mostramos el mensaje correspondiente
					if (isset($_SESSION["usr_pw_incorrecto"])){
				      echo "Usuario o contraseña incorrectos";
							//Cerramos la session para borrar el mensaje de error de usuario o contraseña al actualizar
							session_destroy();
				  }
				?>
				</p>
				<p class="subtitulo">
        	<label>USUARIO</label>
        </p>
        <p>
        	<input name="username" type="text" id="usuario" required>
        </p>
        <p class="subtitulo">
      		<label>CONTRASEÑA</label>
        </p>
        <p>
        	<input name="contrasena" type="password" id="password" required>
        </p>
        <p>
        	<input name="Submit" type="submit" value="INGRESAR" />
        </p>
      </div>
		</form>
	  <br /> <br /><br /> <br /><br /> <br />
  </section>
  <footer>
  	<div>
      <p>Copyright - Todos los derechos reservados</p>
      <p>Gestión 2020</p>
    </div>
  </footer>
</div>
</center>
</body>
</html>
