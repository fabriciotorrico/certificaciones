<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$fecha_hoy = date("Y-m-d");
	$ano_hoy = date("Y");

	//Tomamos los datos necesarios e inicializamos variables
	$id = $_POST['id'];
	$gestion = $_POST['gestion'];
	$liquidacion = $_POST['liquidacion'];
	$forma_pago = $_POST['forma_pago'];
	$existe_liquidacion_previa = $_POST['existe_liquidacion_previa'];
	$base_imponible = 0;

	//Siguiendo la misma lógica que en el archivo liquidacion_inmebles_datos_tecnicos_form.php, verificamos si prviamente se hizo la liquidacion
	if ($existe_liquidacion_previa == 1) {
		//Tomamos los datos del inmueble
		$sql  = "SELECT *
						 FROM liquidacion_2
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

		//Establecemos el valor de cotizacion del dolar y ufv, parametros y $fecha_vencimiento
		$cotizacion_dolar = $row['dolar'];
		$cotizacion_ufv = $row['ufv'];
		$precio_formulario = $row['formulario'];
		$fecha_vencimiento = $row['fechvenci'];
	}
	else {
		//Tomamos los datos del inmueble
		$sql  = "SELECT *
						 FROM liquidacion2
						 WHERE id = '$id'";
		$result = pg_query($link, $sql);
		$row = pg_fetch_array($result, NULL, PGSQL_BOTH);

		//Tomamos los datos de cotizaciones
		$sql  = "SELECT *
						 FROM dolarufv
						 WHERE fecha = '$fecha_hoy'";
		$result = pg_query($link, $sql);
		$cotizaciones = pg_fetch_array($result, NULL, PGSQL_BOTH);

		//Tomamos los datos para los bloques
		$sql  = "SELECT *
						 FROM edificacion
						 WHERE idinm = '$id'";
		$bloques = pg_query($link, $sql);

		//Seleccionamos los parametros configurados
		$sql  = "SELECT precio_formulario
						 FROM parametros_siim
						 WHERE activo = '1'";
		$parametros = pg_query($link, $sql);
		$parametro = pg_fetch_array($parametros, NULL, PGSQL_BOTH);

		//Seleccionamos la fecha de Vencimiento
		$sql  = "SELECT fecha
						 FROM fechas
						 WHERE activo = '1'
							AND gestion = '$ano_hoy'
						 ORDER BY fecha DESC
						 LIMIT 1";
		$fechas = pg_query($link, $sql);
		$fecha = pg_fetch_array($fechas, NULL, PGSQL_BOTH);

		//Establecemos el valor de cotizacion del dolar y ufv, parametros y $fecha_vencimiento
		$cotizacion_dolar = $cotizaciones['dolar'];
		$cotizacion_ufv = $cotizaciones['ufv'];
		$precio_formulario = $parametro['precio_formulario'];
		$fecha_vencimiento = $fecha['fecha'];
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
						<table width="800" border="0" bgcolor= "#ff7f7f">
							<tr>
								<td>
									<div align="center" class="Estilo4">Gestión: <?php if (isset($gestion)){echo $gestion;}?> </div>
								</td>
								<!--td width="10%">
									<input name="gestion" id="gestion" type="text" value="<?php if (isset($gestion)){echo $gestion;}?>" style="width : 100%;" readonly>
								</td-->

								<td>
									<div align="center" class="Estilo4">Liquidación: <?php if (isset($liquidacion)){echo $liquidacion;}?> </div>
								</td>
								<!--td width="20%">
									<input name="liquidacion" id="liquidacion" type="text" value="<?php if (isset($liquidacion)){echo $liquidacion;}?>" style="width : 100%;" style="width : 100%;" readonly>
								</td-->

								<td>
									<div align="center" class="Estilo4">Forma de Pago: <?php if (isset($forma_pago)){echo $forma_pago;}?> </div>
								</td>
								<!--td width="20%">
									<input name="forma_pago" id="forma_pago" type="text" value="<?php if (isset($forma_pago)){echo $forma_pago;}?>" style="width : 100%;" readonly>
								</td-->
							</tr>
						</table>

						<br>

						<table width="750" border="0">
							<tr>
								<td valign="bottom">
									<table width="300" border="0">
										<p align="center" class="Estilo4"><b>Terreno</b></p>
										<tr>
											<td width="60%">
												<div align="right" class="Estilo4">Superficie: </div>
											</td>
											<td>
												<input name="superficie" id="superficie" type="text" value="<?php if (isset($row['superficie'])){echo $row['superficie'];}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td width="60%">
												<div align="right" class="Estilo4">Valor Unitario: </div>
											</td>
											<td>
												<input name="valunitario" id="valunitario" type="text" value="<?php if (isset($row['valunitario'])){echo number_format($row['valunitario'], 2, '.', ',');}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Coeficiente K1: </div>
											</td>
											<td>
												<input name="k1" id="k1" type="text" value="<?php if (isset($row['k1'])){echo $row['k1'];}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Coeficiente K2: </div>
											</td>
											<td>
												<input name="k2" id="k2" type="text" value="<?php if (isset($row['k2'])){echo $row['k2'];}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Coeficiente K3: </div>
											</td>
											<td>
												<input name="k3" id="k3" type="text" value="<?php if (isset($row['k3'])){echo $row['k3'];}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Coeficiente K4: </div>
											</td>
											<td>
												<input name="k4" id="k4" type="text" value="<?php if (isset($row['k4'])){echo $row['k4'];}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Coeficiente K5: </div>
											</td>
											<td>
												<?php
													//Caclulamos el valor de k5
													if (isset($row['k5_1']) && isset($row['k5_2']) && isset($row['k5_3']) && isset($row['k5_4']) && isset($row['k5_5'])){
														$k5 = 1-$row['k5_1']-$row['k5_2']-$row['k5_3']-$row['k5_4']-$row['k5_5'];
													}
												?>
												<input name="k5" id="k5" type="text" value="<?php if (isset($k5)){echo $k5;}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Coeficiente K6: </div>
											</td>
											<td>
												<input name="k6" id="k6" type="text" value="<?php if (isset($row['k6'])){echo $row['k6'];}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Valor del Terreno: </div>
											</td>
											<td>
												<?php
													//Caclulamos el valor del terreno
													if (isset($row['superficie']) && isset($row['valunitario']) && isset($row['k1']) && isset($row['k2']) && isset($row['k3'])
															&& isset($row['k4']) && isset($row['k5_1']) && isset($row['k5_2']) && isset($row['k5_3']) && isset($row['k5_4'])
															&& isset($row['k5_5'])){
																$valor_terreno = $row['superficie'] * $row['valunitario'] * $row['k1'] * $row['k2'] * $row['k3'] * $row['k4'] * $k5;
																//Redondeamos el valor del terreno a 2 decimales
																$valor_terreno_round = round($valor_terreno,2);
																//Le damos formato , para miles . para decimales
																$valor_terreno_formato = number_format($valor_terreno_round, 2, '.', ',');
																//Sumamos a base imponible, el valor del terreno
																$base_imponible = $base_imponible + $valor_terreno_round;
													}
												?>
												<input name="valor_terreno" id="valor_terreno" type="text" value="<?php if (isset($valor_terreno_formato)){echo $valor_terreno_formato;}?>" style="width : 100%;" readonly>
												<?php
													//Tras mostrar, ponemos los valores en cero
													$k5 = "";
													$valor_terreno = "";
													$valor_terreno_round = "";
													$valor_terreno_formato = "";
												?>
											</td>
										</tr>

										<tr>
											<td>&nbsp;</td>
										</tr>

										<tr>
											<td colspan="2">
												<?php
												$nro_bloque=1;
												while ($bloque = pg_fetch_array($bloques, NULL, PGSQL_BOTH)){
													?>
													<button class="accordion">Bloque <?php echo $nro_bloque ?></button>
													<div class="panel">
														<table border="0">
															<tr>
																<td>
																	<div align="right" class="Estilo4">Superficie: </div>
																</td>
																<td>
																	<input name="" id="" type="text" value="<?php if (isset($bloque['superficie'])){echo $bloque['superficie'];}?>" readonly>
																</td>
															</tr>

															<tr>
																<td>
																	<div align="right" class="Estilo4">Valor Unitario: </div>
																</td>
																<td>
																	<input name="" id="" type="text" value="<?php if (isset($bloque['valor'])){echo number_format($bloque['valor'], 2, '.', ',');}?>" readonly>
																</td>
															</tr>

															<tr>
																<td>
																	<div align="right" class="Estilo4">Coeficiente KK1: </div>
																</td>
																<td>
																	<input name="" id="" type="text" value="<?php if (isset($bloque['kk1_val'])){echo $bloque['kk1_val'];}?>" readonly>
																</td>
															</tr>

															<tr>
																<td>
																	<div align="right" class="Estilo4">Coeficiente KK2: </div>
																</td>
																<td>
																	<input name="" id="" type="text" value="<?php if (isset($bloque['kk2_val'])){echo $bloque['kk2_val'];}?>" readonly>
																</td>
															</tr>

															<tr>
																<td>
																	<div align="right" class="Estilo4">Coeficiente KK3: </div>
																</td>
																<td>
																	<input name="" id="" type="text" value="<?php if (isset($bloque['kk3'])){echo $bloque['kk3'];}?>" readonly>
																</td>
															</tr>

															<tr>
																<td>
																	<div align="right" class="Estilo4">Valor Edificación: </div>
																</td>
																<td>
																	<?php
																		//Caclulamos el valor del terreno
																		if (isset($bloque['superficie']) && isset($bloque['valor']) && isset($bloque['kk1_val']) && isset($bloque['kk2_val']) && isset($bloque['kk3'])){
																					$valor_edificacion = $bloque['superficie'] * $bloque['valor'] * $bloque['kk1_val'] * $bloque['kk2_val'] * $bloque['kk3'];
																					//Redondeamos el valor de la edificacion a 2 decimales
																					$valor_edificacion_round = round($valor_edificacion,2);
																					//Le damos formato , para miles . para decimales
																					$valor_edificacion_formato = number_format($valor_edificacion_round, 2, '.', ',');

																					//Sumamos a base imponible, el valor del terreno
																					$base_imponible = $base_imponible + $valor_edificacion_round;
																		}
																	?>
																	<input name="" id="" type="text" value="<?php if (isset($valor_edificacion_formato)){echo $valor_edificacion_formato;}?>" readonly>
																	<?php
																		//Tras mostrar, ponemos los valores en cero para el siguiente bloque
																		$valor_edificacion = "";
																		$valor_edificacion_round = "";
																		$valor_edificacion_formato = "";
																	?>
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
									</table>
								</td>

								<td width="20px">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>

								<td valign="bottom">
									<table width="400" border="0">
										<tr>
											<td width="60%">
												<div align="right" class="Estilo4">Impuesto Neto: </div>
											</td>
											<td>
												<?php
													//Calculamos el impuesto_neto
													$impuesto_neto = round($base_imponible, 2) * $row['alicuota'];
													$impuesto_neto_round = round($impuesto_neto,2);
												?>
												<input name="impuesto_neto" id="impuesto_neto" type="text" value="<?php if (isset($impuesto_neto_round)){echo number_format($impuesto_neto_round, 2, '.', ',');}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Mantenimiento Valor: </div>
											</td>
											<td>
												<input name="mantenimiento" id="mantenimiento" type="text" value="<?php if (isset($row['mantenimiento'])){echo $row['mantenimiento'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Interes: </div>
											</td>
											<td>
												<input name="interes" id="interes" type="text" value="<?php if (isset($row['interes'])){echo $row['interes'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Multa por Mora: </div>
											</td>
											<td>
												<input name="multa" id="multa" type="text" value="<?php if (isset($row['multa'])){echo $row['multa'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Deberes Formales: </div>
											</td>
											<td>
												<input name="deberes" id="deberes" type="text" value="<?php if (isset($row['deberes'])){echo $row['deberes'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Sanción Administrativa: </div>
											</td>
											<td>
												<input name="sancion" id="sancion" type="text" value="<?php if (isset($row['sancion'])){echo $row['sancion'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">[10%] Pago Termino: </div>
											</td>
											<td>
												<?php
													//Calculamos el 10% del impuesto_neto
													$pago_termino = $impuesto_neto_round * 0.1;
													$pago_termino_round = round($pago_termino, 2);
												?>
												<input name="pago_termino" id="pago_termino" type="text" value="<?php if (isset($pago_termino_round)){echo number_format($pago_termino_round, 2, '.', ',');}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Pagos Anteriores: </div>
											</td>
											<td>
												<input name="pagos_anteriores" id="pagos_anteriores" type="text" value="<?php if (isset($row['pagos_anteriores'])){echo $row['pagos_anteriores'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Total Créditos: </div>
											</td>
											<td>
												<input name="total_creditos" id="total_creditos" type="text" value="<?php if (isset($row['total_creditos'])){echo $row['total_creditos'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">{-}Crédito: </div>
											</td>
											<td>
												<input name="credito" id="credito" type="text" value="<?php if (isset($row['credito'])){echo $row['credito'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">{-} Otros Documentos: </div>
											</td>
											<td>
												<input name="otros_documentos" id="otros_documentos" type="text" value="<?php if (isset($row['otros_documentos'])){echo $row['otros_documentos'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Saldo a Favor: </div>
											</td>
											<td>
												<input name="saldo_favor" id="saldo_favor" type="text" value="<?php if (isset($row['saldo_favor'])){echo $row['saldo_favor'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
											</tr>
									</table>
								</td>
							</tr>
						</table>

						<table width="750" border="0">
							<tr style="background-color: #cac5c5;">
								<td>
									<div align="right" class="Estilo4">Base Imponible: </div>
								</td>
								<td width="20%">
									<input name="base_imponible" id="base_imponible" type="text" value="<?php echo number_format(round($base_imponible,2), 2, '.', ',');?>" style="width : 100%;" readonly>
								</td>

								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>

								<td width="25%">
									<div align="right" class="Estilo4">Total Parcial: </div>
								</td>
								<td width="25%">
									<?php
										//Calculamos el total_parcial
										$total_parcial = $impuesto_neto_round - $pago_termino_round;
										$total_parcial_round = round($total_parcial, 2);
									?>
									<input name="total_parcial" id="total_parcial" type="text" value="<?php if (isset($total_parcial_round)){echo number_format($total_parcial_round, 2, '.', ',');}?>" style="width : 100%;" readonly>
								</td>
							</tr>
						</table>

						<table width="750" border="0">
							<tr>
								<td>
									<div align="right" class="Estilo4">Fecha de Vencimiento: </div>
								</td>
								<td colspan=3 width="25%">
									<input name="fecha_vencimiento" id="fecha_vencimiento" type="text" value="<?php if (isset($fecha_vencimiento)){echo $fecha_vencimiento;}?>" style="width : 100%;" readonly>
								</td>

								<td width="20%">
									<div align="right" class="Estilo4"><b>Parcial: </b></div>
								</td>
								<td width="25%">
									<input name="parcial" id="parcial" type="text" value="<?php if (isset($total_parcial_round)){echo number_format($total_parcial_round, 2, '.', ',');}?>" style="width : 100%; background-color: #f7ffa9;" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Fecha de Liquidación: </div>
								</td>
								<td colspan="3">
									<input name="fecha_liquidacion" id="fecha_liquidacion" type="text" value="<?php if (isset($row['fechliqui'])){echo $row['fechliqui'];}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4"><b>Formulario: </b></div>
								</td>
								<td>
									<input name="formulario" id="formulario" type="text" value="<?php if (isset($precio_formulario)){echo number_format($precio_formulario, 2, '.', ',');}?>" style="width : 100%; background-color: #f7ffa9;" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Dólar: </div>
								</td>
								<td>
									<input name="dolar" id="dolar" type="text" value="<?php if (isset($cotizacion_dolar)){echo $cotizacion_dolar;}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">UFV: </div>
								</td>
								<td>
									<input name="ufv" id="ufv" type="text" value="<?php if (isset($cotizacion_ufv)){echo $cotizacion_ufv;}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4"><b>Total: </b></div>
								</td>
								<td>
									<input name="total" id="total" type="text" value="<?php if (isset($total_parcial_round) && isset($precio_formulario)){echo number_format($total_parcial_round + $precio_formulario, 2, '.', ',');}?>" style="width : 100%; background-color: #ff7f7f;" readonly>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="750" border="0">
							<tr>
								<td bgcolor= "#ff7f7f">
									<div align="center" class="Estilo4"><b>Pagos Anteriores </b></div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4">
										<textarea name="pagos_anteriores" id="pagos_anteriores" rows="3" cols="100" readonly>
											<?php if (isset($row['pagos_anteriores'])){echo $row['pagos_anteriores'];}?>
										</textarea>
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="750" border="0">
							<tr>
								<form name="form" action="#" method="post">
									<td width="33%">
										<div align="center" class="Estilo4">
											<button type="button"><i class="fa fa-file-o"> F.U.R.</i></button>
										</div>
									</td>
								</form>

								<form name="form" action="pdf_formulario_1980.php" method="post">
									<td width="33%">
										<div align="center" class="Estilo4">
											<input name="id" id="id" type="hidden" value="<?php echo $_POST['id'];?>">
											<input name="gestion" type="hidden" value="<?php echo $_POST['gestion'];?>">
											<input name="liquidacion" type="hidden" value="<?php echo $_POST['liquidacion'];?>">
											<input name="forma_pago" type="hidden" value="<?php echo $_POST['forma_pago'];?>">
											<input name="existe_liquidacion_previa" type="hidden" value="<?php echo $existe_liquidacion_previa;?>">
											<input name="impuesto_neto" type="hidden" value="<?php echo $impuesto_neto_round;?>">
											<input name="pago_termino" type="hidden" value="<?php echo $pago_termino_round;?>">
											<input name="parcial" type="hidden" value="<?php echo $total_parcial_round;?>">
											<input name="fecha_vencimiento" type="hidden" value="<?php echo $fecha_vencimiento;?>">
											<button type="submit" formtarget="_blank"><i class="fa fa-file-pdf-o"> Formulario 1980</i></button>
										</div>
									</td>
								</form>

								<form name="form" action="#" method="post">
									<td>
										<div align="center" class="Estilo4">
											<button type="button"><i class="fa fa-file-zip-o"> Boleta Resumen</i></button>
										</div>
									</td>
								</form>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td colspan=3>
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
<?php include("scripts.php");?>
</html>
