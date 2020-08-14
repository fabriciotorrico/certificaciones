<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$ano = date("Y");

	//Tomamos las gestiones existentes
	$sql  = "SELECT *
					 FROM zonavalor
					 ORDER BY idzona";
	$zonas = pg_query($link, $sql);
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
					<p>ZONAS IDENTIFICADAS</p>
						<table width="800" border="0">
							<tr>
							<?php
							//Si hay un mensaje de error o exito, lo mostramos
							if (isset($_SESSION['msj_exito'])) {
							?>
								<div class="div_exito">
									<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
									<?php echo $_SESSION['msj_exito'];?>
								</div>
								<br>
							<?php
							//Quitamos el mensaje para futuras actualizaciones
							unset($_SESSION['msj_exito']);
							}
							?>

							<td>
								<div align="center" class="Estilo4">
									<table width="750" border="1" style="border-collapse: collapse;">
										<tr>
											<th>Zona</th>
											<th>Descripción</th>
											<th>Valor</th>
											<th>Acción</th>
										</tr>

										<?php
										while ($zona = pg_fetch_array($zonas, NULL, PGSQL_BOTH)) {
										?>

										<form name="form_editar" id="form_editar" action="zonas_identificadas_editar.php" method="post">
											<tr>
												<td width="35%" style="text-align: center">
													<input type="text" name="nombre" value='<?php echo $zona['nombre']; ?>' style="width:95%" required>
												</td>

												<td width="35%" style="text-align: center">
													<input type="text" name="descripcion" value='<?php echo $zona['descripcion']; ?>' style="width:95%" required>
												</td>

												<td width="15%" style="text-align: center">
													<input type="number" min="0" step="0.01" name="valorm2" value="<?php echo $zona['valorm2']; ?>" style="width:95%" required>
												</td>

												<td width="15%" style="text-align: center">
													<input type="hidden" name="idzona" value="<?php echo $zona['idzona']; ?>">
													<button type="submit"><i class="fa fa-edit"> &nbsp; Editar &nbsp;</i></button>
												</td>
											</tr>
										</form>
										<?php
										}
										?>
										</table>
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td colspan=2>
									<form name="form_2" action="home.php" method="post">
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

	<?php include 'scripts.php'; ?>
</body>
</html>
