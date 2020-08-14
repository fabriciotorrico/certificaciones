<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$fecha_hoy = date("Y-m-d");

	//Verificamos si se ha creado un registro de cotizacion dolar y ufv para la fecha de hoy
	$sql  = "SELECT *
					 FROM dolarufv
					 WHERE fecha = '$fecha_hoy'";
	$result = pg_query($link, $sql);

	//Si ya existe el registro, establecemos la variable $tipo_registro en actualizacion, para usarlo como bandera en la vista
	if ($row = pg_fetch_array($result, NULL, PGSQL_BOTH)){
		$tipo_registro = "actualizacion";
	}
	//Caso contrario, establecemos la variable $tipo_registro en nuevo
	else {
		$tipo_registro = "nuevo";
	}
?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!--link rel=”stylesheet” href=”css/font-awesome.min.css“-->
</head>
<link type="text/css" rel="stylesheet" href="calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=<? echo $fecha_junto?>" media="screen"></link>
<script type="text/javascript" src="calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=<? echo $fecha_junto?>"></script>
<script language="javascript" src="jquery.min.js"></script>

<title>SIIM WEB</title>
<style type="text/css">
	<!--
	.Estilo25 {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 18px;
		font-weight: bold;
		color: #003399;
	}
	.Estilo26 {
		color: #000000;
		font-size: 16px;
	}
	.Estilo30 {font-size: 16px; color: #003399;}
	.Estilo31 {font-size: 16px}
	.Estilo32 {font-size: 16; }
	.Estilo33 {font-size: 14px; color: #003399; }
	.Estilo4 {color: #0033CC}
	-->
</style>
</head>

<body>
	<table width="900" border="0" align="center" bgcolor="#f5f0e4">
		<tr>
			<?php
			if ($tipo_registro == "nuevo") {
			?>
				<td><img src="imagenes/encabezado_siim_web.jpg" alt="banner" width="900" height="250" longdesc="banner"></td>
			<?php
			}
			else {
			?>
				<td> <?php include("menu.php");?></td>
			<?php
			}
			?>
		</tr>
  	<tr>
    	<td>
				<div align="center" class="Estilo25">
					<p>COTIZACIÓN DE DOLAR Y UFV</p>
					<form name="form" action="cotizacion_dolar_ufv.php" method="post">
						<table width="500" border="0">
							<tr>
								<td>
									<div align="right" class="Estilo4">Fecha: </div>
								</td>
								<td>
									<input name="fecha" type="text" id="fecha" onClick="displayCalendar(document.form.fecha,'yyyy-mm-dd',this)" value="<?php echo $fecha_hoy;?>" size="10" readonly=true required>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Cotización Dólar: </div>
								</td>
								<td>
									<input name="dolar" type="number" min="0" step="0.01" value="<?php if (isset($row['dolar'])){echo $row['dolar'];}else {echo "6.96";}?>" required>
									<a href="https://www.bcb.gob.bo/?q=cotizaciones_tc" target="_blank"><i class="fa fa-question-circle-o"></i></a>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Cotización UFV: </div>
								</td>
								<td>
									<input name="ufv" type="number" min="0" step="0.00001" value="<?php if (isset($row['ufv'])){echo $row['ufv'];}else {echo "2.33636";}?>" required>
									<a href="https://www.bcb.gob.bo/?q=servicios/ufv/datos_estadisticos" target="_blank"><i class="fa fa-question-circle-o"></i></a>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
									<td colspan=2>
										<div align="center" class="Estilo4">
											<button type="submit"><i class="fa fa-save"> Guardar</i></button>
										</div>
									</td>
								</form>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<form name="form" action="home.php" method="post">
										<div align="center" class="Estilo4">
											<button type="submit"><i class="fa fa-print"> Imprmir</i></button>
										</div>
									</form>
								</td>
								<td>
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
