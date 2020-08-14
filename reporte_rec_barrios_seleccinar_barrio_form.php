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

	//Seleccionamos los barrios
	$sql  = "SELECT idbarrio, nombre
					 FROM barrio
					 ORDER BY nombre";
	$barrios = pg_query($link, $sql);
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
					<p>RECAUDACION POR BARRIOS</p>
					<form name="form" action="pdf_reporte_rec_barrios.php" method="post">
						<table width="500" border="0">
							<tr>
								<td>
									<div align="right" class="Estilo4">Rubro: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input type="radio" name="pago" value="inmuebles" checked> Inmuebles
										<br>
										<input type="radio" name="pago" value="vehiculos" disabled> Vehículos
										<br>
										<input type="radio" name="pago" value="actividades" disabled> Actividades
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Gestión: </div>
								</td>
								<td>
									<select name="gestion" required style="width: 90%">
										<option value="">Seleccione una Gestión</option>
										<?php
										for ($i=$parametro['gestion_inicio']; $i <= $parametro['gestion_fin']; $i++) {
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
									<div align="right" class="Estilo4">Barrio: </div>
								</td>
								<td>
									<select name="id_barrio" required style="width: 90%">
										<option value="0">Todos los Barrios</option>
										<?php
										while ($barrio = pg_fetch_array($barrios, NULL, PGSQL_BOTH)) {
										?>
											<option value="<?php echo $barrio['idbarrio'];?>"><?php echo $barrio['nombre'];?></option>
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
