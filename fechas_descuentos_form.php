<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$ano = date("Y");

	//Tomamos las gestiones existentes
	$sql  = "SELECT DISTINCT(gestion)
					 FROM fechas
					 WHERE activo=1
					 ORDER BY gestion";
	$gestiones = pg_query($link, $sql);

	//Si se agrego, edito o elimino un registro, volvera la variable gestion como variable de sesion, tomamos la misma
	if (isset($_SESSION['fechas_descuentos_gestion_seleccionada'])) {
		$_POST['gestion'] = $_SESSION['fechas_descuentos_gestion_seleccionada'];

		//Borramos la asignacion a la variable
		unset($_SESSION['fechas_descuentos_gestion_seleccionada']);
	}
	//Si se seleccionó una gestion, tomamos las fechas de la misma
	if (isset($_POST['gestion'])) {
		$gestion_seleccionada = $_POST['gestion'];
		//Tomamos los registros con la gestion buscada
		$sql  = "SELECT *
						 FROM fechas
						 WHERE gestion = '$gestion_seleccionada'
						  AND activo=1
						 ORDER BY fecha";
		$fechas = pg_query($link, $sql);
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
					<p>DESCUENTOS POR GESTIÓN</p>
						<table width="700" border="0">
							<tr>
								<td valign="top">
									<div align="center" class="Estilo4">Seleccione la Gestión</div>
									<form action="fechas_descuentos_form.php" method="post" id="fechas_descuentos_form">
										<div align="center" class="Estilo4">
											<select name="gestion" id="gestion" size="4" style="width: 80px" onchange="buscar_fechas()" required>
												<?php
												while ($gestion = pg_fetch_array($gestiones, NULL, PGSQL_BOTH)) {
												?>
													<option value="<?php echo $gestion['gestion'];?>" <?php if (isset($_POST['gestion']) && $gestion['gestion'] == $_POST['gestion']){echo "selected";}?>><?php echo $gestion['gestion'];?></option>
												<?php
												}
												?>
											</select>
										</div>
									</form>
								</td>

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

							<?php
							//Si se introdujo un criterio de busqueda, mostramos los restulados
							if (isset($fechas)){
							?>
								<td>
									<div align="center" class="Estilo4">
										<table width="450" border="1" style="border-collapse: collapse;">
											<tr>
												<th>Gestión</th>
												<th>Fecha</th>
												<th>Porcentaje de Descuento</th>
												<th>Acción</th>
											</tr>

											<?php
											while ($fecha = pg_fetch_array($fechas, NULL, PGSQL_BOTH)) {
											?>

											<form name="form_editar" id="form_editar" action="fechas_descuentos_agregar_editar_eliminar.php" method="post">
												<tr>
													<td width="20%" style="text-align: center">
														<?php echo $fecha['gestion']; ?>
													</td>
													<td width="30%" style="text-align: center">
														<input type="date" name="fecha" id="fecha" value="<?php echo $fecha['fecha'];?>" min="<?php echo $fecha['gestion']."-01-01";?>" max="<?php echo $fecha['gestion']."-12-31";?>" style="width: 90%" required>
													</td>
													<td width="20%" style="text-align: center">
														<input type="number" min="0" step="0.01" name="porcentaje" value="<?php echo $fecha['porcentaje'];?>" style="width:70%" required> %
													</td>
													<td width="25%" style="text-align: center">
															<input type="hidden" name="accion" value="editar">
															<input type="hidden" name="gestion" value="<?php echo $fecha['gestion']; ?>">
															<input type="hidden" name="id_fecha" value="<?php echo $fecha['id_fecha']; ?>">
															<button type="submit"><i class="fa fa-edit"> &nbsp; Editar &nbsp;</i></button>
														</form>
														<form name="form_eliminar" id="form_eliminar" action="fechas_descuentos_agregar_editar_eliminar.php" method="post">
															<input type="hidden" name="accion" value="eliminar">
															<input type="hidden" name="gestion" value="<?php echo $fecha['gestion']; ?>">
															<input type="hidden" name="id_fecha" value="<?php echo $fecha['id_fecha']; ?>">
															<button type="submit" onclick="return pregunta_eliminar_fecha()"><i class="fa fa-trash"> Eliminar</i></button>
														</form>
													</td>
												</tr>
											<?php
											}
											?>

											<tr>
												<form name="form_agregar" action="fechas_descuentos_agregar_editar_eliminar.php" method="post">
													<td style="text-align: center">
														<input type="number" name="gestion" value="" style="width: 80%" required>
													</td>
													<td style="text-align: center">
														<input type="date" name="fecha" id="fecha" value="" style="width: 90%" required>
													</td>
													<td style="text-align: center">
														<input type="number" min="0" step="0.01" name="porcentaje" value="" style="width:70%" required> %
													</td>
													<td width="25%" style="text-align: center">
														<input type="hidden" name="accion" value="agregar">
														<button type="submit"><i class="fa fa-plus-square"> &nbsp; Agregar &nbsp;</i></button>
													</td>
												</form>
											</tr>
										</table>
									</div>
								</td>
							<?php
							}
							?>
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

	<script>
		function buscar_fechas() {
			document.getElementById("fechas_descuentos_form").submit();
		}
	</script>

	<script language="JavaScript">
		function pregunta_eliminar_fecha(){
				if (confirm('Esta acción eliminará el registro de seleccionado.')){
					 document.getElementById("form_eliminar").submit();
				}
				return false;
		}
	</script>
</body>
</html>
