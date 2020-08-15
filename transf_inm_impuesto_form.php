<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$fecha_hoy = date("Y-m-d");
	$ano_hoy = date("Y");

	//Tomamos los datos necesarios e inicializamos variables
	$id_inmueble = $_POST['id_inmueble'];
	$ci_vendedor = $_POST['ci_vendedor'];
	$ci_comprador = $_POST['ci_comprador'];
	$dolares = $_POST['dolares'];
	$bolivianos = $_POST['bolivianos'];
	$ddrr = $_POST['ddrr'];
	$minuta = $_POST['minuta'];
	$base_imponible = 0;

	//Tomamos los datos del inmueble
	$sql  = "SELECT *
					 FROM transferencia2
					 WHERE id = '$id_inmueble'";
	$result = pg_query($link, $sql);
	$row = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Tomamos los datos para las edificaciones para calcular la base imponible
	$sql  = "SELECT *
					 FROM edificacion
					 WHERE idinm = '$id_inmueble'";
	$edificaciones = pg_query($link, $sql);

	//Tomamos los datos para mostrar los bloques
	$sql  = "SELECT *
					 FROM edificacion
					 WHERE idinm = '$id_inmueble'";
	$bloques = pg_query($link, $sql);

	//Caclulamos el valor del terreno
	if (isset($row['superficie']) && isset($row['valunitario']) && isset($row['k1']) && isset($row['k2']) && isset($row['k3'])
			&& isset($row['k4']) && isset($row['k5_1']) && isset($row['k5_2']) && isset($row['k5_3']) && isset($row['k5_4'])
			&& isset($row['k5_5'])){
				//Calculamos k5
				$k5 = 1 - $row['k5_1'] - $row['k5_2'] - $row['k5_3'] - $row['k5_4'] - $row['k5_5'];
				//Valor del terreno
				$valor_terreno = $row['superficie'] * $row['valunitario'] * $row['k1'] * $row['k2'] * $row['k3'] * $row['k4'] * $k5;
				//Redondeamos el valor del terreno a 2 decimales
				$valor_terreno_round = round($valor_terreno,2);
				//Le damos formato , para miles . para decimales
				$valor_terreno_formato = number_format($valor_terreno_round, 2, '.', ',');
				//Sumamos a base imponible, el valor del terreno
				$base_imponible = $base_imponible + $valor_terreno_round;
	}

	//Para calcular la base imponible, iteramos todas las edificaciones
	while ($edificacion = pg_fetch_array($edificaciones, NULL, PGSQL_BOTH)) {
		if (isset($edificacion['superficie']) && isset($edificacion['valor']) && isset($edificacion['kk1_val']) && isset($edificacion['kk2_val']) && isset($edificacion['kk3'])){
					$valor_edificacion = $edificacion['superficie'] * $edificacion['valor'] * $edificacion['kk1_val'] * $edificacion['kk2_val'] * $edificacion['kk3'];
					//Redondeamos el valor de la edificacion a 2 decimales
					$valor_edificacion_round = round($valor_edificacion,2);
					//Le damos formato , para miles . para decimales
					$valor_edificacion_formato = number_format($valor_edificacion_round, 2, '.', ',');
					//Sumamos a base imponible, el valor del terreno
					$base_imponible = $base_imponible + $valor_edificacion_round;
		}
	}

	//Calculamos el impuesto_neto
	if (isset($row['alicuota'])) {
		$impuesto_neto = round($base_imponible, 2) * $row['alicuota'];
		$impuesto_neto_round = round($impuesto_neto,2);
	}

	//Tomamos los datos de cotizaciones
	$sql  = "SELECT *
					 FROM dolarufv
					 WHERE fecha = '$fecha_hoy'";
	$result = pg_query($link, $sql);
	$cotizaciones = pg_fetch_array($result, NULL, PGSQL_BOTH);

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
	$fecha_vencimiento = $fecha['fecha'];

	//Caclulamos el monto de la transferencia
	//Se pagarà el 3% del monto mayor entre base imponible y el valor introducido por el usuario en bs en el form anterior
	if ($base_imponible >= $bolivianos) {
		$monto_transferencia = round($base_imponible * 0.03, 2);
	}
	else {
		$monto_transferencia = round($bolivianos * 0.03, 2);
	}

	//Tomamos los datos de la transferencia para determinar si habilitar o no el boton parte 2
  $sql  = "SELECT idtransf
           FROM transferencia
           WHERE idinm = '$id_inmueble'
             AND ci_vendedor = '$ci_vendedor'
             AND ci_comprador = '$ci_comprador'
             AND montobs = '$bolivianos'
             AND montosus = '$dolares'
             AND ddrr = '$ddrr'
             AND fechminuta = '$minuta'
             AND montotranfe = '$monto_transferencia'
             AND activo = '1'
           ORDER BY idtransf DESC
           LIMIT 1";
  $result = pg_query($link, $sql);
  $existe_transferencia = pg_fetch_array($result, NULL, PGSQL_BOTH);
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
					<p>TRANSFERIR INMUEBLE</p>

						<table width="750" border="0">
							<tr>
								<td valign="bottom">
									<table width="300" border="0">
										<tr>
											<td width="60%">
												<div align="right" class="Estilo4">Superficie del Terreno: </div>
											</td>
											<td>
												<input name="superficie" id="superficie" type="text" value="<?php if (isset($row['superficie'])){echo $row['superficie'];}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td width="60%">
												<div align="right" class="Estilo4">Valor Unitario del Terreno: </div>
											</td>
											<td>
												<input name="valunitario" id="valunitario" type="text" value="<?php if (isset($row['valunitario'])){echo number_format($row['valunitario'], 2, '.', ',');}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Valor del Terreno: </div>
											</td>
											<td>
												<input name="valor_terreno" id="valor_terreno" type="text" value="<?php if (isset($valor_terreno_formato)){echo $valor_terreno_formato;}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td colspan="2">
												<?php
												$nro_bloque=1;
												while ($bloque = pg_fetch_array($bloques, NULL, PGSQL_BOTH)){
													?>
													<button class="accordion">Bloque <?php echo $nro_bloque ?></button>
													<div class="panel">
														<table border="0" width="390">
															<tr>
																<td>
																	<div align="right" class="Estilo4">Superficie de la Construcción: </div>
																</td>
																<td>
																	<input name="superficie" id="superficie" type="text" value="<?php if (isset($bloque['superficie'])){echo $bloque['superficie'];} else {echo " -------------";}?>" readonly>
																</td>
															</tr>

															<tr>
																<td>
																	<div align="right" class="Estilo4">Valor Unit. de la Construcción: </div>
																</td>
																<td>
																	<input name="valor" id="valor" type="text" value="<?php if (isset($bloque['valor'])){echo $bloque['valor'];} else {echo " -------------";}?>" readonly>
																</td>
															</tr>

															<tr>
																<td>
																	<div align="right" class="Estilo4">Valor de la Construcción: </div>
																</td>
																<td>
																	<?php
																		//Caclulamos el valor de la Construcción
																		if (isset($bloque['superficie']) && isset($bloque['valor']) && isset($bloque['kk1_val']) && isset($bloque['kk2_val']) && isset($bloque['kk3'])){
																			$valor_edificacion = $bloque['superficie'] * $bloque['valor'] * $bloque['kk1_val'] * $bloque['kk2_val'] * $bloque['kk3'];
																			//Redondeamos el valor de la edificacion a 2 decimales
																			$valor_edificacion_round = round($valor_edificacion,2);
																			//Le damos formato , para miles . para decimales
																			$valor_edificacion_formato = number_format($valor_edificacion_round, 2, '.', ',');
																		}
																	?>
																	<input name="" id="" type="text" value="<?php if (isset($valor_edificacion_formato)){echo $valor_edificacion_formato;} else {echo " -------------";}?>" readonly>
																	<?php
																		//Tras mostrar, ponemos los valores en cero para el siguiente bloque
																		unset($valor_edificacion);
																		unset($valor_edificacion_round);
																		unset($valor_edificacion_formato);
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

										<tr>
											<td>&nbsp;</td>
										</tr>
									</table>
								</td>

								<td valign="bottom">
									<table width="300" border="0">
										<tr>
											<td width="60%">
												<div align="right" class="Estilo4">Impuesto Neto: </div>
											</td>
											<td>
												<input name="impuesto_neto" id="impuesto_neto" type="text" value="<?php if (isset($impuesto_neto_round)){echo number_format($impuesto_neto_round, 2, '.', ',');}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Mantenimiento Valor: </div>
											</td>
											<td>
												<input name="mantenimiento" id="mantenimiento" type="text" value="<?php if (isset($row['mantevalor'])){echo $row['mantevalor'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
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
												<input name="multa" id="multa" type="text" value="<?php if (isset($row['multamora'])){echo $row['multamora'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Deberes Formales: </div>
											</td>
											<td>
												<input name="deberes" id="deberes" type="text" value="<?php if (isset($row['debformales'])){echo $row['debformales'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Sanción Administrativa: </div>
											</td>
											<td>
												<input name="sancion" id="sancion" type="text" value="<?php if (isset($row['sancionadm'])){echo $row['sancionadm'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<?php /*
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
										*/?>

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
												<input name="otros_documentos" id="otros_documentos" type="text" value="<?php if (isset($row['otros'])){echo $row['otros'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
											</td>
										</tr>

										<tr>
											<td>
												<div align="right" class="Estilo4">Saldo a Favor: </div>
											</td>
											<td>
												<input name="saldo_favor" id="saldo_favor" type="text" value="<?php if (isset($row['saldofavor'])){echo $row['saldofavor'];}else {echo "0.00";}?>" style="width : 100%;" readonly>
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
									<input name="base_imponible" id="base_imponible" type="text" value="<?php if (isset($base_imponible)) {echo number_format(round($base_imponible,2), 2, '.', ',');}?>" style="width : 100%;" readonly>
								</td>

								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>

								<td width="22%">
									<div align="right" class="Estilo4">Total Parcial: </div>
								</td>
								<td width="20%">
									<?php
										//Calculamos el total_parcial
										if (isset($impuesto_neto_round)) {
											//$total_parcial = $impuesto_neto_round - $pago_termino_round;
											$total_parcial = $impuesto_neto_round;
											$total_parcial_round = round($total_parcial, 2);
										}
									?>
									<input name="total_parcial" id="total_parcial" type="text" value="<?php if (isset($total_parcial_round)){echo number_format($total_parcial_round, 2, '.', ',');}?>" style="width : 95%;" readonly>
								</td>
							</tr>

							<tr style="background-color: #cac5c5;">
								<td>
									<div align="right" class="Estilo4">Monto Transferencia: </div>
								</td>
								<td width="20%">
									<input name="monto_transferencia" id="monto_transferencia" type="text" value="<?php if (isset($monto_transferencia)) {echo number_format($monto_transferencia, 2, '.', ',');}?>" style="width : 100%;" readonly>
								</td>

								<td colspan="3">
									&nbsp;
								</td>
							</tr>

							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>

						<table width="500" border="0">
							<tr>
								<td width="10%">
									<div align="right" class="Estilo4">Fecha de Vencimiento: </div>
								</td>
								<td colspan=3 width="25%">
									<input name="fecha_vencimiento" id="fecha_vencimiento" type="text" value="<?php if (isset($fecha_vencimiento)){echo $fecha_vencimiento;}?>" style="width : 100%;" readonly>
								</td>

								<?php /*
								<td width="20%">
									<div align="right" class="Estilo4"><b>Parcial: </b></div>
								</td>
								<td width="25%">
									<input name="parcial" id="parcial" type="text" value="<?php if (isset($total_parcial_round)){echo number_format($total_parcial_round, 2, '.', ',');}?>" style="width : 100%; background-color: #f7ffa9;" readonly>
								</td>
								*/?>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Fecha de Liquidación: </div>
								</td>
								<td colspan="3">
									<input name="fecha_liquidacion" id="fecha_liquidacion" type="text" value="<?php if (isset($row['fechliqui'])){echo $row['fechliqui'];}?>" style="width : 100%;" readonly>
								</td>

								<?php /*
								<td>
									<div align="right" class="Estilo4"><b>Formulario: </b></div>
								</td>
								<td>
									<input name="formulario" id="formulario" type="text" value="<?php if (isset($precio_formulario)){echo number_format($precio_formulario, 2, '.', ',');}?>" style="width : 100%; background-color: #f7ffa9;" readonly>
								</td>
								*/?>
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

								<?php /*
								<td>
									<div align="right" class="Estilo4"><b>Total: </b></div>
								</td>
								<td>
									<input name="total" id="total" type="text" value="<?php if (isset($total_parcial_round) && isset($precio_formulario)){echo number_format($total_parcial_round + $precio_formulario, 2, '.', ',');}?>" style="width : 100%; background-color: #ff7f7f;" readonly>
								</td>
								*/?>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="500" border="0">
							<tr>
								<td width="33%" colspan="2">
									<form name="form" action="#" method="post">
										<div align="center" class="Estilo4">
											<button type="button"><i class="fa fa-file-zip-o"> Boleta Resumen</i></button>
										</div>
									</form>
								</td>

								<td width="33%" colspan="2">
									<form name="form" action="#" method="post">
										<div align="center" class="Estilo4">
											<input name="id_inmueble" type="hidden" value="<?php echo $id_inmueble;?>">
											<input name="ci_vendedor" type="hidden" value="<?php echo $ci_vendedor;?>">
											<input name="ci_comprador" type="hidden" value="<?php echo $ci_comprador;?>">
											<input name="bolivianos" type="hidden" value="<?php echo $bolivianos;?>">
											<input name="dolares" type="hidden" value="<?php echo $dolares;?>">
											<input name="ddrr" type="hidden" value="<?php echo $ddrr;?>">
											<input name="minuta" type="hidden" value="<?php echo $minuta;?>">
											<input name="valor_terreno" type="hidden" value="<?php echo $valor_terreno;?>">
											<input name="base_imponible" type="hidden" value="<?php echo $base_imponible;?>">
											<input name="impuesto_neto" type="hidden" value="<?php echo $impuesto_neto;?>">
											<input name="monto_transferencia" type="hidden" value="<?php echo $monto_transferencia;?>">
											<input name="fecha_vencimiento" type="hidden" value="<?php echo $fecha_vencimiento;?>">
											<input name="cotizacion_dolar" type="hidden" value="<?php echo $cotizacion_dolar;?>">
											<input name="cotizacion_ufv" type="hidden" value="<?php echo $cotizacion_ufv;?>">
											<button type="button"><i class="fa fa-file-pdf-o"> Formulario 502</i></button>
										</div>
									</form>
								</td>

								<td colspan="2">
									<form name="form" action="home.php" method="post">
										<div align="center" class="Estilo4">
											<button type="submit"><i class="fa fa-home"> Inicio</i></button>
										</div>
									</form>
								</td>
							</tr>

							<tr>
								<td colspan="3">
									<form name="form" action="pdf_formulario_transf_inm_parte_1.php" method="post">
										<div align="center" class="Estilo4">
											<input name="id_inmueble" type="hidden" value="<?php echo $id_inmueble;?>">
											<input name="ci_vendedor" type="hidden" value="<?php echo $ci_vendedor;?>">
											<input name="ci_comprador" type="hidden" value="<?php echo $ci_comprador;?>">
											<input name="bolivianos" type="hidden" value="<?php echo $bolivianos;?>">
											<input name="dolares" type="hidden" value="<?php echo $dolares;?>">
											<input name="ddrr" type="hidden" value="<?php echo $ddrr;?>">
											<input name="minuta" type="hidden" value="<?php echo $minuta;?>">
											<input name="valor_terreno" type="hidden" value="<?php echo $valor_terreno;?>">
											<input name="base_imponible" type="hidden" value="<?php echo $base_imponible;?>">
											<input name="impuesto_neto" type="hidden" value="<?php echo $impuesto_neto;?>">
											<input name="monto_transferencia" type="hidden" value="<?php echo $monto_transferencia;?>">
											<input name="fecha_vencimiento" type="hidden" value="<?php echo $fecha_vencimiento;?>">
											<input name="cotizacion_dolar" type="hidden" value="<?php echo $cotizacion_dolar;?>">
											<input name="cotizacion_ufv" type="hidden" value="<?php echo $cotizacion_ufv;?>">
											<button type="submit" formtarget="_blank"><i class="fa fa-hand-pointer-o"> Parte 1</i></button>
										</div>
									</form>
								</td>

								<td colspan="3">
									<form name="form_parte_2" id="form_parte_2" action="pdf_formulario_transf_inm_parte_2.php" method="post">
										<div align="center" class="Estilo4">
											<input name="id_inmueble" id="id_inmueble" type="hidden" value="<?php echo $id_inmueble;?>">
											<input name="ci_vendedor" id="ci_vendedor" type="hidden" value="<?php echo $ci_vendedor;?>">
											<input name="ci_comprador" id="ci_comprador" type="hidden" value="<?php echo $ci_comprador;?>">
											<input name="bolivianos" id="bolivianos" type="hidden" value="<?php echo $bolivianos;?>">
											<input name="dolares" id="dolares" type="hidden" value="<?php echo $dolares;?>">
											<input name="ddrr" id="ddrr" type="hidden" value="<?php echo $ddrr;?>">
											<input name="minuta" id="minuta" type="hidden" value="<?php echo $minuta;?>">
											<input name="valor_terreno" type="hidden" value="<?php echo $valor_terreno;?>">
											<input name="base_imponible" type="hidden" value="<?php echo $base_imponible;?>">
											<input name="impuesto_neto" type="hidden" value="<?php echo $impuesto_neto;?>">
											<input name="monto_transferencia" id="monto_transferencia_valor" type="hidden" value="<?php echo $monto_transferencia;?>">
											<input name="fecha_vencimiento" type="hidden" value="<?php echo $fecha_vencimiento;?>">
											<input name="cotizacion_dolar" type="hidden" value="<?php echo $cotizacion_dolar;?>">
											<input name="cotizacion_ufv" type="hidden" value="<?php echo $cotizacion_ufv;?>">
											<button type="submit" formtarget="_blank" id="boton_parte_2" onClick="return verificar_boton_parte_2();"><i class="fa fa-hand-peace-o"> Parte 2</i></button>
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
<script type="text/javascript">
	function verificar_boton_parte_2() {
		$.get("buscar_si_existe_transferencia.php", {id_inmueble: $("#id_inmueble").val(), ci_vendedor: $("#ci_vendedor").val(), ci_comprador: $("#ci_comprador").val(), bolivianos: $("#bolivianos").val(), dolares: $("#dolares").val(), ddrr: $("#ddrr").val(), minuta: $("#minuta").val(), monto_transferencia: $("#monto_transferencia_valor").val()})
      .done(function(respuesta){
				if (respuesta == "si") {
					document.getElementById("form_parte_2").setAttribute("target", "_blank");
					document.getElementById("form_parte_2").submit();
				}
				else {
					alert("Primero debe realizar la Parte 1");
				}
				//alert(respuesta);
      });
			return false;
	}
</script>
</html>
