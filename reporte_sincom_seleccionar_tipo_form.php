<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$mes_ano = date("m/Y");
	$fecha_hoy = date("d/m/Y");

	/*//Seleccionamos los parametros configurados
	$sql  = "SELECT gestion_inicio,gestion_fin
					 FROM parametros_siim
					 WHERE activo = '1'";
	$parametros = pg_query($link, $sql);
	$parametro = pg_fetch_array($parametros, NULL, PGSQL_BOTH);*/
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
					<p>REPORTE CONSOLIDADO DE INGRESOS</p>
					<form name="form" action="pdf_reporte_sincom.php" method="post">
						<table width="400" border="0">
							<tr>
								<td>
									<div align="left" class="Estilo4">
										<input type="radio" name="tipo" value="al_dia" checked> Al día
									</div>
								</td>
								<td>
									<div align="left" class="Estilo4">
									 <?php echo $fecha_hoy;?>
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="left" class="Estilo4">
										<input type="radio" name="tipo" value="rango"> Rango de Fechas
									</div>
								</td>
								<td>
									<div align="left" class="Estilo4">
									 <?php echo "01/".$mes_ano." al ".$fecha_hoy;?>
									</div>
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<div align="left" class="Estilo4">
										<input type="checkbox" name="soi" value="1"> Reporte de Recaudaciones Exclusivo del SOI
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
