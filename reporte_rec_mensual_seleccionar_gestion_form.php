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
					<p>CONSOLIDADO DE RECAUDACIONES - MENSUAL</p>
					<form name="form" action="pdf_reporte_rec_mensual.php" method="post">
						<table width="500" border="0">

							<tr>
								<td>
									<div align="right" class="Estilo4">Recaudación de: </div>
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
									<div align="right" class="Estilo4">Reporte Anual: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input type="checkbox" name="reporte_anual" id="reporte_anual" value="reporte_anual" onchange="habilitar_mes()"> Si
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Mes: </div>
								</td>
								<td>
									<select name="mes" id="mes" required>
										<option value="1">Enero</option>
										<option value="2">Febrero</option>
										<option value="3">Marzo</option>
										<option value="4">Abril</option>
										<option value="5">Mayo</option>
										<option value="6">Junio</option>
										<option value="7">Julio</option>
										<option value="8">Agosto</option>
										<option value="9">Septiembre</option>
										<option value="10">Octubre</option>
										<option value="11">Noviembre</option>
										<option value="12">Diciembre</option>
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
<script type="text/javascript">
	function habilitar_mes() {
		if (document.getElementById("reporte_anual").checked == true) {
			document.getElementById("mes").disabled = true;
		}
		else {
			document.getElementById("mes").disabled = false;
		}
	}
</script>
</html>
