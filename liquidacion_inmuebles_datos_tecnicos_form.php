<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos los datos necesarios
	$id = $_POST['id'];
	$gestion = $_POST['gestion'];
	$liquidacion = $_POST['liquidacion'];
	$forma_pago = $_POST['forma_pago'];


		//Tomamos los registros de la tabla liquidacion para el id y la gestion dada
		$sql  = "SELECT id
						 FROM liquidacion
						 WHERE id = '$id'
						 	AND gestion = '$gestion'
						 	AND activo = 1";
		$result = pg_query($link, $sql);
		$liquidacion_existe = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Si la $liquidacion es "Original" y ya exite un registro en la tabla liquidacion para el id y gestion introducidos, solo mostramos de
	//las tablas con datos guardados previamente;
	//Caso contrario, si $liquidacion es "Rectificatoria" o es "Original" pero no se hizo previamente un registro en la tabla liquidacion,
	//se muestra de las vistas establecidas y se hace todo el procedimiento de para guardar
	if ($liquidacion == "Original" && isset($liquidacion_existe['id'])) {
		//Tomamos los datos del inmueble
		$sql  = "SELECT *
						 FROM liquidacion_1
	           WHERE id = '$id'
	           AND gestion = '$gestion'
	           AND activo = '1'";
		$result = pg_query($link, $sql);
		$row = pg_fetch_array($result, NULL, PGSQL_BOTH);

		//Tomamos los datos para los bloques
		$sql  = "SELECT *
						 FROM liquidacion_construccion
	           WHERE idinm = '$id'
	           AND gestion = '$gestion'
	           AND activo = 1";
		$bloques = pg_query($link, $sql);

		//Establecemos la variable $existe_liquidacion_previa en 1 para pasar a las siguientes vistas y no hacer el mismo procedimiento de manera repetitiva
		$existe_liquidacion_previa = 1;

		//Establecemos el mensaje a mostrar
		$mensaje_liquidacion_previa = "Los datos que ve e imprimirá son de una liquidación ralizada previamente. Si hubo algún error o desea que se
																		actualicen, seleccione la opción 'Liquidación -> Rectificatoria'";
	}
	else {
		//Tomamos los datos del inmueble
		$sql  = "SELECT *
						 FROM liquidacion1
						 WHERE id = '$id'";
		$result = pg_query($link, $sql);
		$row = pg_fetch_array($result, NULL, PGSQL_BOTH);

		//Tomamos los datos para los bloques
		$sql  = "SELECT *
						 FROM edificacion
						 WHERE idinm = '$id'";
		$bloques = pg_query($link, $sql);

		//Establecemos la variable $existe_liquidacion_previa en 0 para pasar a las siguientes vistas y no hacer el mismo procedimiento de manera repetitiva
		$existe_liquidacion_previa = 0;
	}
?>

<?php include("header.php");?>

<body>
	<table width="1100" border="0" align="center" bgcolor="#f5f0e4">
		<tr>
			<td> <?php include("menu.php");?></td>
		</tr>
  	<tr>
    	<td>
				<div align="center" class="Estilo25">
					<p>LIQUIDACIÓN DE INMUEBLES</p>
						<?php
						//Si hay un mensaje de error o exito, lo mostramos
						if (isset($mensaje_liquidacion_previa)) {
						?>
							<div class="div_exito">
								<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
								<?php echo $mensaje_liquidacion_previa;?>
							</div>
							<br>
						<?php
						}
						?>

						<table width="750" border="0">
							<p>Dirección y/o Ubicación</p>
							<tr>
								<td>
									<div align="right" class="Estilo4">Inmueble: </div>
								</td>
								<td colspan=5>
									<input name="idtipoinm" id="idtipoinm" type="text" value="<?php if (isset($row['idtipoinm'])){echo $row['idtipoinm'];}?>" style="width : 100%;" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Barrio: </div>
								</td>
								<td colspan=5>
									<input name="barrio" id="barrio" type="text" value="<?php if (isset($row['barrio'])){echo $row['barrio'];}?>" style="width : 100%;" readonly>
								</td>
							</tr>

							<tr>
								<td width="18%" >
									<div align="right" class="Estilo4">Nombre de Vía: </div>
								</td>
								<td width="45	%" colspan="3">
									<input name="nombre_via" id="nombre_via" type="text" value="<?php if (isset($row['nombrevia'])){echo $row['nombrevia'];}?>" style="width : 100%;" readonly>
								</td>

								<td width="15%" >
									<div align="right" class="Estilo4">Nro de Puerta: </div>
								</td>
								<td>
									<input name="nro_puerta" id="nro_puerta" type="text" value="<?php if (isset($row['numeroinm'])){echo $row['numeroinm'];}?>" style="width : 100%;" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Depto: </div>
								</td>
								<td>
									<input name="depto" id="depto" type="text" value="<?php if (isset($row['depto'])){echo $row['depto'];}else {echo " --------------------";}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Bloque: </div>
								</td>
								<td>
									<input name="bloque" id="bloque" type="text" value="<?php if (isset($row['bloque'])){echo $row['bloque'];}else {echo " --------------------";}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Piso: </div>
								</td>
								<td>
									<input name="piso" id="piso" type="text" value="<?php if (isset($row['piso'])){echo $row['piso'];}else {echo " --------------------";}?>" style="width : 100%;" readonly>
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
									<input name="frentefondo" id="frentefondo" type="text" value="<?php if (isset($row['frentefondo'])){echo $row['frentefondo'];}else {echo " --------------------";}?>" readonly>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="500" border="0">
							<p>Mejoras de la Construcción:</p>
							<tr>
								<td>
									<textarea name="mejoras" id="mejoras" rows="3" cols="60" readonly>
										<?php if (isset($row['mejoras'])){echo $row['mejoras'];}?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="750" border="0">
							<tr>
								<td>
									<?php
									$nro_bloque=1;
									while ($bloque = pg_fetch_array($bloques, NULL, PGSQL_BOTH)){
										?>
										<button class="accordion">Bloque <?php echo $nro_bloque ?></button>
										<div class="panel">
											<table width="800" border="0">
												<tr>
													<td>
														<div align="right" class="Estilo4">Tipología: </div>
													</td>
													<td>
														<input name="tipologia" id="tipologia" type="text" value="<?php if (isset($bloque['tipologia'])){echo $bloque['tipologia'];}?>" readonly>
													</td>

													<td>
														<div align="right" class="Estilo4">Superficie: </div>
													</td>
													<td>
														<input name="superficie" id="superficie" type="text" value="<?php if (isset($bloque['superficie'])){echo $bloque['superficie'];}?>" readonly>
													</td>
												</tr>

												<tr>
													<td>
														<div align="right" class="Estilo4">Valor Unitario (m2): </div>
													</td>
													<td>
														<input name="valor" id="valor" type="text" value="<?php if (isset($bloque['valor'])){echo $bloque['valor'];}?>" readonly>
													</td>

													<td>
														<div align="right" class="Estilo4">Depreciación (KK1): </div>
													</td>
													<td>
														<input name="kk1" id="kk1" type="text" value="<?php if (isset($bloque['kk1'])){echo $bloque['kk1'];}?>" readonly>
													</td>
												</tr>

												<tr>
													<td>
														<div align="right" class="Estilo4">Conservación (KK2): </div>
													</td>
													<td>
														<input name="kk2" id="kk2" type="text" value="<?php if (isset($bloque['kk2'])){echo $bloque['kk2'];}?>" readonly>
													</td>

													<td>
														<div align="right" class="Estilo4">Destino / Uso (KK3): </div>
													</td>
													<td>
														<input name="kk3" id="kk3" type="text" value="<?php if (isset($bloque['kk3'])){echo $bloque['kk3'];}?>" readonly>
													</td>
												</tr>

												<tr>
													<td>&nbsp;</td>
												</tr>

												<tr>
													<td colspan=2>
														<div align="center" class="Estilo4"><b>OBRA GRUESA</b></div>
													</td>

													<td colspan=2>
														<div align="center" class="Estilo4"><b>OBRA FINA</b></div>
													</td>
												</tr>

												<tr>
													<td>
														<div align="right" class="Estilo4">Cimientos: </div>
													</td>
													<td>
														<input name="cimientos" id="cimientos" type="text" value="<?php if (isset($bloque['cimientos'])){echo $bloque['cimientos'];}?>" readonly>
													</td>

													<td>
														<div align="right" class="Estilo4">Cielos: </div>
													</td>
													<td>
														<input name="cielos" id="cielos" type="text" value="<?php if (isset($bloque['cielos'])){echo $bloque['cielos'];}?>" readonly>
													</td>
												</tr>

												<tr>
													<td>
														<div align="right" class="Estilo4">Estructuras: </div>
													</td>
													<td>
														<input name="" id="" type="text" value="<?php if (isset($bloque['estructuras'])){echo $bloque['estructuras'];}?>" readonly>
													</td>

													<td>
														<div align="right" class="Estilo4">Pisos: </div>
													</td>
													<td>
														<input name="" id="" type="text" value="<?php if (isset($bloque['pisos'])){echo $bloque['pisos'];}?>" readonly>
													</td>
												</tr>

												<tr>
													<td>
														<div align="right" class="Estilo4">Muros / Tab.: </div>
													</td>
													<td>
														<input name="" id="" type="text" value="<?php if (isset($bloque['muros'])){echo $bloque['muros'];}?>" readonly>
													</td>

													<td>
														<div align="right" class="Estilo4">Ventanas: </div>
													</td>
													<td>
														<input name="" id="" type="text" value="<?php if (isset($bloque['ventanas'])){echo $bloque['ventanas'];}?>" readonly>
													</td>
												</tr>

												<tr>
													<td>
														<div align="right" class="Estilo4">Cubierta: </div>
													</td>
													<td>
														<input name="" id="" type="text" value="<?php if (isset($bloque['cubierta'])){echo $bloque['cubierta'];}?>" readonly>
													</td>

													<td>
														<div align="right" class="Estilo4">Muros Interior: </div>
													</td>
													<td>
														<input name="" id="" type="text" value="<?php if (isset($bloque['interior'])){echo $bloque['interior'];}?>" readonly>
													</td>
												</tr>

												<tr>
													<td colspan=2>
													</td>

													<td>
														<div align="right" class="Estilo4">Fachadas: </div>
													</td>
													<td>
														<input name="" id="" type="text" value="<?php if (isset($bloque['fachada'])){echo $bloque['fachada'];}?>" readonly>
													</td>
												</tr>

												<tr>
													<td>&nbsp;</td>
												</tr>

											</table>
										</div>
										<?php
										$nro_bloque=$nro_bloque+1;
									}
									?>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="800" border="0">
							<tr>
								<form name="form" action="liquidacion_inmuebles_pago_form.php" method="post">
									<input name="id" id="id" type="hidden" value="<?php echo $id;?>">
									<input name="gestion" type="hidden" value="<?php echo $gestion;?>">
									<input name="liquidacion" type="hidden" value="<?php echo $liquidacion;?>">
									<input name="forma_pago" type="hidden" value="<?php echo $forma_pago;?>">
									<input name="existe_liquidacion_previa" type="hidden" value="<?php echo $existe_liquidacion_previa;?>">
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
<?php include("scripts.php");?>
</html>
