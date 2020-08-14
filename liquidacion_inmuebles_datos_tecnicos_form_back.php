<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos los datos necesarios
	$pmc = $_POST['pmc'];
	$gestion = $_POST['gestion'];
	$liquidacion = $_POST['liquidacion'];
	$forma_pago = $_POST['forma_pago'];

	//Tomamos los datos del inmueble
	$sql  = "SELECT *
					 FROM liquidacion1
					 WHERE id = '$pmc'";
	$result = pg_query($link, $sql);
	$row = pg_fetch_array($result, NULL, PGSQL_BOTH);
?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
	<table width="1100" border="0" align="center" bgcolor="#f5f0e4">
		<tr>
			<td> <?php include("menu.php");?></td>
		</tr>
  	<tr>
    	<td>
				<div align="center" class="Estilo25">
					<p>LIQUIDACIÓN DE INMUEBLES</p>
					<form name="form" action="liquidacion_inmuebles_pago_form.php" method="post">
						<table width="500" border="0">
							<p>Dirección y/o Ubicación</p>
							<tr>
								<td>
									<div align="right" class="Estilo4">Barrio: </div>
								</td>
								<td colspan=3>
									<input name="barrio" id="barrio" type="text" value="<?php if (isset($row['barrio'])){echo $row['barrio'];}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">ID: </div>
								</td>
								<td>
									<input name="id" id="id" type="text" value="<?php if (isset($row['id'])){echo $row['id'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Nombre de Vía: </div>
								</td>
								<td colspan="3">
									<input name="nombre_via" id="nombre_via" type="text" value="<?php if (isset($row['nombrevia'])){echo $row['nombrevia'];}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Nro de Puerta: </div>
								</td>
								<td>
									<input name="nro_puerta" id="nro_puerta" type="text" value="<?php if (isset($row['numeroinm'])){echo $row['numeroinm'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Depto: </div>
								</td>
								<td>
									<input name="depto" id="depto" type="text" value="<?php if (isset($row['depto'])){echo $row['depto'];}?>" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Bloque: </div>
								</td>
								<td>
									<input name="bloque" id="bloque" type="text" value="<?php if (isset($row['bloque'])){echo $row['bloque'];}?>" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Piso: </div>
								</td>
								<td>
									<input name="piso" id="piso" type="text" value="<?php if (isset($row['piso'])){echo $row['piso'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="800" border="0">
							<p>Coeficiente Servicio (K5)</p>
							<tr>
								<td>
									<div align="right" class="Estilo4">
									 	<?php
											if ($row['aguapotabl']==1){?>
												<i class="fa fa-check-square-o"></i>
											<?php
											}
											else {?>
												<i class="fa fa-square-o"></i>
											<?php
											}
										?>
										Agua Potable
									</div>
								</td>

								<td>
									<div align="right" class="Estilo4">
										<?php
											if ($row['alcantaril']==1){?>
												<i class="fa fa-check-square-o"></i>
											<?php
											}
											else {?>
												<i class="fa fa-square-o"></i>
											<?php
											}
										?>
										Alcantarillado
									</div>
								</td>

								<td>
									<div align="right" class="Estilo4">
										<?php
											if ($row['electricid']==1){?>
												<i class="fa fa-check-square-o"></i>
											<?php
											}
											else {?>
												<i class="fa fa-square-o"></i>
											<?php
											}
										?>
										Energía Eléctrica
									</div>
								</td>

								<td>
									<div align="right" class="Estilo4">
										<?php
											if ($row['telefonoin']==1){?>
												<i class="fa fa-check-square-o"></i>
											<?php
											}
											else {?>
												<i class="fa fa-square-o"></i>
											<?php
											}
										?>
										Teléfono
									</div>
								</td>

								<td>
									<div align="right" class="Estilo4">
										<?php
											if ($row['transporte']==1){?>
												<i class="fa fa-check-square-o"></i>
											<?php
											}
											else {?>
												<i class="fa fa-square-o"></i>
											<?php
											}
										?>
										Transporte
									</div>
								</td>

							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="500" border="0">
							<p>Datos Técnicos del Terreno</p>
							<tr>
								<td>
									<div align="right" class="Estilo4">Código Catastral: </div>
								</td>
								<td>
									<input name="codigo" id="codigo" type="text" value="<?php if (isset($row['codigo'])){echo $row['codigo'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Superficie: </div>
								</td>
								<td>
									<input name="superficie" id="superficie" type="text" value="<?php if (isset($row['superficie'])){echo $row['superficie'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Valor Unitario ZH: </div>
								</td>
								<td>
									<input name="zona" id="zona" type="text" value="<?php if (isset($row['zona'])){echo $row['zona'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K1) Tipo de Vía: </div>
								</td>
								<td>
									<input name="tipo_via" id="tipo_via" type="text" value="<?php if (isset($row['tipovia'])){echo $row['tipovia'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K2) Topográfico: </div>
								</td>
								<td>
									<input name="topografia" id="topografia" type="text" value="<?php if (isset($row['topografia'])){echo $row['topografia'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K3) Forma: </div>
								</td>
								<td>
									<input name="forma" id="forma" type="text" value="<?php if (isset($row['forma'])){echo $row['forma'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K4) Ubicación: </div>
								</td>
								<td>
									<input name="ubicacion" id="ubicacion" type="text" value="<?php if (isset($row['ubicacion'])){echo $row['ubicacion'];}?>" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K6) Frente Fondo: </div>
								</td>
								<td>
									<input name="frente_fondo" id="frente_fondo" type="text" value="<?php if (isset($row['frente_fondo'])){echo $row['frente_fondo'];}?>" readonly>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="800" border="0">
							<tr>
									<input name="gestion" type="hidden" value="<?php echo $gestion;?>">
									<input name="liquidacion" type="hidden" value="<?php echo $liquidacion;?>">
									<input name="forma_pago" type="hidden" value="<?php echo $forma_pago;?>">
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
									<div align="center" class="Estilo4">
										<button onclick="history.go(-1);"><i class="fa fa-arrow-circle-left"> Anterior</i></button>
									</div>
								</td>
							</tr>
						</table>
      	</div>
			</td>
  	</tr>
	</table>
</body>
</html>
