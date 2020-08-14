<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$fecha_hoy = date("Y-m-d");

	//Seleccionamos los parametros configurados
	$sql  = "SELECT *
					 FROM parametros_siim
					 WHERE activo = '1'";
	$result = pg_query($link, $sql);
	$row = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Tomamos los gobiernos municipales
	$sql  = "SELECT M.id_municipio, M.municipio, D.departamento
					 FROM municipio AS M
					 LEFT JOIN departamento AS D
					 	ON M.id_departamento = D.id_departamento
					 WHERE M.activo = 1
					 ORDER BY D.departamento, M.municipio";
	$municipios = pg_query($link, $sql);
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
					<p>PARÁMETROS SIIM WEB</p>

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

					<form name="form" action="personalizar_siim.php" method="post">
						<table width="500" border="0">
							<tr>
								<td>
									<div align="right" class="Estilo4">Gobierno Municipal: </div>
								</td>
								<td>
									<select name="id_municipio" style="width : 100%;" required>
										<option value="">Seleccione un Municipio</option>
										<?php
										while ($municipio = pg_fetch_array($municipios, NULL, PGSQL_BOTH)){
										?>
											<option value="<?php echo $municipio['id_municipio'];?>" <?php if (isset($row['id_municipio']) && $municipio['id_municipio'] == $row['id_municipio']){echo "selected";}?>><?php echo $municipio['municipio']." - ".$municipio['departamento'];?></option>
										<?php
										}
										?>
									</select>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Tasa de Interés: </div>
								</td>
								<td>
									<input name="tasa_interes" type="number" min="0" step="0.01" value="<?php if (isset($row['tasa_interes'])){echo $row['tasa_interes'];}else {echo "0.00";}?>" style="width : 20%;" required>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Gestiones a Liquidar: </div>
								</td>
								<td>
									<input name="gestion_inicio" type="number" min="1900" step="1" value="<?php if (isset($row['gestion_inicio'])){echo $row['gestion_inicio'];}else {echo "1900";}?>" style="width : 20%;" required> -
									<input name="gestion_fin" type="number" min="1901" step="1" value="<?php if (isset($row['gestion_fin'])){echo $row['gestion_fin'];}?>" style="width : 20%;" required>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Rep. Formulario: </div>
								</td>
								<td>
									<input name="precio_formulario" type="number" min="0" step="0.01" value="<?php if (isset($row['precio_formulario'])){echo $row['precio_formulario'];}else {echo "0.00";}?>" style="width : 20%;" required>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Descuento: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input name="descuento" type="number" min="0" step="0.01" value="<?php if (isset($row['descuento'])){echo $row['descuento'];}else {echo "0.00";}?>" style="width : 20%;" required> % Deberes Formales
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Recaudaciones: </div>
								</td>
								<td>
									<select name="recaudacion" style="width : 100%;" required>
										<option value="cajeros">Cajeros HAM</option>
									</select>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">
										<input name="imprimir_qr" type="checkbox" value="1" <?php if (isset($row['imprimir_qr']) && $row['imprimir_qr'] == 1){echo "checked";}?>>
									</div>
								</td>
								<td>
									<div align="left" class="Estilo4">Imprimir Código QR</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">
										<input name="fuc_preimpreso" type="checkbox" value="1" <?php if (isset($row['fuc_preimpreso']) && $row['fuc_preimpreso'] == 1){echo "checked";}?>>
									</div>
								</td>
								<td>
									<div align="left" class="Estilo4">Form. FUC Preimpreso</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">
										<input name="fuc_tam_legal" type="checkbox" value="1" <?php if (isset($row['fuc_tam_legal']) && $row['fuc_tam_legal'] == 1){echo "checked";}?>>
									</div>
								</td>
								<td>
									<div align="left" class="Estilo4">Form. FUC Tamaño Legal</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">
										<input name="sancion_administrativa" type="checkbox" value="1" <?php if (isset($row['sancion_administrativa']) && $row['sancion_administrativa'] == 1){echo "checked";}?>>
									</div>
								</td>
								<td>
									<div align="left" class="Estilo4">(+) Sanción Administrativa</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td colspan=2>
									<div align="center" class="Estilo4"><b>Comisiones Bancarias</b></div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">* F-1980: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input name="comision_f1980" type="number" min="0" step="0.01" value="<?php if (isset($row['comision_f1980'])){echo $row['comision_f1980'];}else {echo "0.00";}?>" style="width : 20%;" required> U$
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">* F-2160: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input name="comision_f2160" type="number" min="0" step="0.01" value="<?php if (isset($row['comision_f2160'])){echo $row['comision_f2160'];}else {echo "0.00";}?>" style="width : 20%;" required>
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">* F-0501: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input name="comision_f0501" type="number" min="0" step="0.01" value="<?php if (isset($row['comision_f0501'])){echo $row['comision_f0501'];}else {echo "0.00";}?>" style="width : 20%;" required>
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">* Mil: </div>
								</td>
								<td>
									<div align="left" class="Estilo4">
										<input name="mil" type="number" min="0" step="0.00001" value="<?php if (isset($row['mil'])){echo $row['mil'];}else {echo "0.00";}?>" style="width : 20%;" required>
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td colspan="2">
									<div align="center" class="Estilo4">
											<button type="submit"><i class="fa fa-save"> Guardar</i></button>
										</form>
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td colspan="2">
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
	<?php include 'scripts.php'; ?>
</body>
</html>
