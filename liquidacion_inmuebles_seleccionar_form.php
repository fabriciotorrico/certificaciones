<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$ano = date("Y");

	//Seleccionamos los parametros configurados
	$sql  = "SELECT gestion_inicio,gestion_fin
					 FROM parametros_siim
					 WHERE activo = '1'";
	$parametros = pg_query($link, $sql);
	$parametro = pg_fetch_array($parametros, NULL, PGSQL_BOTH);
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
					<p>LIQUIDACIÓN DE INMUEBLES</p>
					<form name="form" action="liquidacion_inmuebles_datos_tecnicos_form.php" method="post">
						<table width="500" border="0">

							<tr>
								<td>
									<div align="right" class="Estilo4">Código Catastral: </div>
								</td>
								<td>
									<input name="id" type="text" value="<?php if (isset($_POST['id'])){echo $_POST['id'];}?>" required>
									<a href="buscar_codigo_catastral_form.php?ruta_vuetla=liquidacion_inmuebles_seleccionar_form"><i class="fa fa-search"></i></a>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Gestión: </div>
								</td>
								<td>
									<select name="gestion" required>
										<option value="">Seleccione una Gestión</option>
										<?php
										for ($i=$parametro['gestion_inicio']; $i <= $parametro['gestion_fin'] ; $i++) {
										?>
											<option value="<?php echo $i;?>" selected><?php echo $i;?></option>
										<?php
										}
										?>
									</select>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Liquidación: </div>
								</td>
								<td>
									<select name="liquidacion" required>
										<option value="">Seleccione un tipo de liquidación</option>
										<option value="Original">Original</option>
										<option value="Rectificatoria">Rectificatoria</option>
									</select>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Forma de Pago: </div>
								</td>
								<td>
									<select name="forma_pago" required>
										<option value="Contado">Contado</option>
									</select>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
									<td colspan=2>
										<div align="center" class="Estilo4">
											<button type="submit"><i class="fa fa-arrow-circle-right"> Siguiente</i></button>
										</div>
									</td>
								</form>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td colspan=2>
									<form name="form" action="home.php" method="post">
										<div align="center" class="Estilo4">
											<button type="submit"><i class="fa fa-home"> Inicio</i></button>
										</div>
									</form>
								</td>
							</tr>
						</table>
      	</div>
			</td>
  	</tr>
	</table>
</body>
</html>
