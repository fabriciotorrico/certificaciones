<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$ano = date("Y");

	//Si ya se introdujo el criterio de busqueda, realizamos la consulta
	if (isset($_POST['busqueda'])) {
		$busqueda = $_POST['busqueda'];
		//Tomamos los registros que coincidan con la busqueda
		$sql  = "SELECT B.id, B.propietario, B.ci
						 FROM buscar AS B
						 WHERE propietario LIKE UPPER('%$busqueda%')
						 	OR ci LIKE UPPER('%$busqueda%')";
		$result = pg_query($link, $sql);
	}

	//Establecemos la ruta de vuelta
	if (isset($_GET['ruta_vuetla'])) {
		$ruta_vuelta = $_GET['ruta_vuetla'];
	}
	else {
		$ruta_vuelta = $_POST['ruta_vuetla'];
	}
?>

<?php include("header.php");?>

<body>
	<table width="900" border="0" align="center" bgcolor="#f5f0e4">
		<tr>
			<td> <?php include("menu.php");?></td>
		</tr>
  	<tr>
    	<td>
				<div align="center" class="Estilo25">
					<p>BUSCAR CÓDIGO CATASTRAL</p>
						<table width="500" border="0">
							<tr>
								<td>
									<div align="center" class="Estilo4">Criterio de Búsqueda</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4">
										<form action="buscar_codigo_catastral_form.php" method="post">
											<input type="hidden" name="ruta_vuetla" value="<?php echo $ruta_vuelta; ?>">
											<input name="busqueda" type="text" placeholder="Introduzca su nombre, apellidos o cédula de identidad" style="width: 80%"required>
											<button type="submit"><i class="fa fa-search"> Buscar</i></button>
										</form>
									</div>
								</td>
							</tr>

					<?php
					//Si se introdujo un criterio de busqueda, mostramos los restulados
					if (isset($busqueda)){
						?>
						<tr>
							<td>
								<div align="center" class="Estilo4">Resultados de la búsqueda</div>
							</td>
						</tr>

						<form action="<?php echo $ruta_vuelta;?>.php" method="post">
						<tr>
							<td>
								<div align="center" class="Estilo4">
									<select name="id" size="4" id="id" onchange="mostrar_datos()" required>
										<?php
										while ($resultado = pg_fetch_array($result, NULL, PGSQL_BOTH)) {
										?>
											<option value="<?php echo $resultado['id'];?>"><?php echo $resultado['ci']." - ".$resultado['propietario'];?></option>
										<?php
										}
										?>
									</select>
								</div>
							</td>
						</tr>

						<tr>
							<td>&nbsp;</td>
						</tr>

						<tr>
							<td>
								<div align="center" class="Estilo4">
									<p id="mostrar_datos"></p>
								</div>
							</td>
						</tr>

						<tr>
							<td>&nbsp;</td>
						</tr>
						
						<tr>
							<td>
								<div align="center" class="Estilo4">
									<button type="submit"><i class="fa fa-arrow-circle-right"> Seleccionar</i></button>
								</div>
							</td>
						</tr>
						</form>
						<?php
					}
					?>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td colspan=2>
									<form name="form" action="<?php echo $ruta_vuelta;?>.php" method="post">
										<div align="center" class="Estilo4">
											<button type="submit"><i class="fa fa-arrow-circle-left"> Anterior</i></button>
										</div>
									</form>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
      	</div>
			</td>
  	</tr>
	</table>

	<script>
		function mostrar_datos() {
			$.get("buscar_codigo_catastral_por_id.php", {id: $("#id").val()})
        .done(function(respuesta){
          document.getElementById("mostrar_datos").innerHTML = respuesta;
        });
		}
	</script>
</body>
</html>
