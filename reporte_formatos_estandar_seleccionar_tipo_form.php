<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$fecha_hoy = date("Y-m-d");

	//Seleccionamos los datos de los usuarios
	$sql  = "SELECT id, nombres, apellidos, ci, username
					 FROM usuario";
	$usuarios = pg_query($link, $sql);
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
					<p>CONTROL DE RECAUDACIONES</p>
					<form name="form" action="pdf_reporte_formatos_estandar.php" method="post">
						<table width="500" border="0">

							<tr>
								<td>
									<div align="right" class="Estilo4">Recaudaciones en: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input type="radio" name="recaudacion" value="inmuebles" checked> Cajeros Gobierno Municipal
										<br>
										<input type="radio" name="recaudacion" value="vehiculos" disabled> Entidades Bancarias
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Concepto / Rubro: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input type="radio" name="concepto" value="inmuebles" checked> Inmuebles
										<br>
										<input type="radio" name="concepto" value="vehiculos" disabled> Vehículos
										<br>
										<input type="radio" name="concepto" value="actividades" disabled> Actividades
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Cajero: </div>
								</td>
								<td>
									<select name="id_usuario" required>
										<option value="">Seleccione un Cajero</option>
										<?php
										while ($usuario = pg_fetch_array($usuarios, NULL, PGSQL_BOTH)) {
										?>
											<option value="<?php echo $usuario['id'];?>" selected><?php echo $usuario['nombres']." ".$usuario['apellidos']." (".$usuario['ci']." - ".$usuario['username'].")";?></option>
										<?php
										}
										?>
									</select>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Tipo de Reporte: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input type="radio" name="tipo" value="detalle" disabled> Detalle
										<br>
										<input type="radio" name="tipo" value="resumen" checked> Resumen
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Fecha: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input type="date" name="fecha" id="fecha" value="<?php echo $fecha_hoy;?>" required>
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
									<td colspan=2>
										<div align="center" class="Estilo4">
											<button type="submit" formtarget="_blank"><i class="fa fa-file-pdf"> Generar Reporte</i></button>
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
