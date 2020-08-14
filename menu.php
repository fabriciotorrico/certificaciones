<?php
	//session_start();
?>

<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!--link rel=”stylesheet” href=”css/font-awesome.min.css“-->
</head>
<LINK rel=stylesheet type=text/css href="nav-h.css">
<SCRIPT type=text/javascript  src="nav-h.js"></SCRIPT>

<style type="text/css">
<!--
.Estilo1 {font-size: 25px}
.Estilo2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.Estilo7 {color: #FF6633; font-weight: bold;}
.Estilo10 {
	font-family: Arial, Helvetica, sans-serif;
	color: #333333;
}
.Estilo11 {color: #333333}
.Estilo12 {
	color: #0033CC;
	font-weight: bold;
}
.Estilo13 {
		color: #0033CC;
		font-weight: bold;
		font-size: 15px;
}
-->
</style>

<table width="900" border="0" align="center" cellpadding="0" cellspacing="0" >

	<tr>
		<td><img src="imagenes/encabezado_siim_web.jpg" alt="banner" width="900" height="250" longdesc="banner"></td>
	</tr>

	<tr>
		<td>
			<ul class="Estilo1" id=navmenu-h>
				<li>
					<a href="home.php">INICIO</a>
				</li>

				<li>
					<a href="#">MÓDULOS SIIM</a>
					<ul>
						<li>
							<a href="#">LIQUIDACIÓN</a>
								<ul>
									<li>
										<a href="liquidacion_inmuebles_seleccionar_form.php">INMUEBLES</a>
									</li>
									<li>
										<a href="#">TRANSFERENCIAS IMT</a>
										<ul>
											<li>
												<a href="transf_inm_seleccionar_vendedor_comprador_form.php">INMUEBLES</a>
											</li>
										</ul>
									</li>
								</ul>
						</li>
						<li>
							<a href="#">RECAUDACIÓN</a>
								<ul>
									<li>
										<a href="#">REGISTRAR PAGOS</a>
										<ul>
											<li>
												<a href="pagos_seleccionar_inmueble_form.php">CAJAS GOBIERNO MUNICIPAL</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="#">LISTADOS DE RECAUDACIÓN</a>
										<ul>
											<li>
												<a href="reporte_formatos_estandar_seleccionar_tipo_form.php">FORMATOS ESTÁNDAR</a>
											</li>
											<li>
												<a href="reporte_rec_mensual_seleccionar_gestion_form.php">REC. MENSUAL</a>
											</li>
											<li>
												<a href="reporte_periodos_fiscales_seleccionar_gestion_form.php">PERIODOS FISCALES</a>
											</li>
											<li>
												<a href="reporte_sincom_seleccionar_tipo_form.php">REPORTES SINCOM</a>
											</li>
											<li>
												<a href="reporte_rec_barrios_seleccinar_barrio_form.php">RECAUDACIÓN POR BARRIOS</a>
											</li>
										</ul>
									</li>
								</ul>
						</li>
					</ul>
				</li>

				<li>
					<a href="#">MANTENIMIENTO</a>
					<ul>
						<li>
							<a href="cotizacion_dolar_ufv_form.php">COTIZAR DOLAR / UFV</a>
						</li>
						<li>
							<a href="#">PARÁMETROS GLBALES</a>
								<ul>
									<li>
										<a href="personalizar_siim_form.php">PERSONALIZAR SIIM</a>
									</li>
									<li>
										<a href="zonas_identificadas_form.php">ZONAS - IDENTIFICADAS</a>
									</li>
								</ul>
						</li>
						<li>
							<a href="#">FECHAS DE VENCIMIENTO / DESCUENTOS</a>
								<ul>
									<li>
										<a href="fechas_descuentos_form.php">INMUEBLES</a>
									</li>
								</ul>
						</li>
					</ul>
				</li>

				<!--li>
					<a href="#">UTILITARIOS</a>
					<ul>
						<li>
							<a href="exportar_bd.php">EXPORTAR BASE DE DATOS</a>
						</li>
					</ul>
				</li-->

				<li>
					<a href="salir.php">SALIR</a>
				</li>
			</ul>
		</td>
	</tr>

  <tr>
    <td height="30">
			<p>
				<br/>
		    <span class="Estilo2">
					<strong>&nbsp;&nbsp;USUARIO: </strong><?php echo $_SESSION['nombres_ss']." ".$_SESSION['apellidos_ss'] ;?>
					<br/>
				</span>
			</p>
    </td>
  </tr>
</table>
