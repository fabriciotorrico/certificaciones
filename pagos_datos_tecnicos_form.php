<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$fecha_hoy = date("Y-m-d");

	//Tomamos los datos necesarios
	//Si está volviendo de una acción, volverá con $_SESSION, caso contrario con POST
	if (isset($_SESSION['volviendo'])) {
		$id = $_SESSION['id'];
		$gestion = $_SESSION['gestion'];
		$pago = $_SESSION['pago'];
		unset($_SESSION['volviendo']);
		unset($_SESSION['id']);
		unset($_SESSION['gestion']);
		unset($_SESSION['pago']);
	}
	else {
		$id = $_POST['id'];
		$gestion = $_POST['gestion'];
		$pago = $_POST['pago'];
	}

	//Tomamos los datos del inmueble
	$sql  = "SELECT *
					 FROM liquidacion
					 WHERE id = '$id'
					 AND gestion = '$gestion'
					 AND activo = 1";
	$result = pg_query($link, $sql);
	$liquidacion = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Tomamos los datos para los bloques
	$sql  = "SELECT *
					 FROM liquidacion_construccion
					 WHERE idinm = '$id'
					 AND gestion = '$gestion'
					 AND activo = 1";
	$bloques = pg_query($link, $sql);

	//Tomamos los datos del pago para definir si mostrar o no el boton sello
	$sql  = "SELECT id_pagoinm
					 FROM pagoinm
					 WHERE idinm = '$id'
						AND gestionpago = '$gestion'
						AND activo = 1";
	$result = pg_query($link, $sql);
	$pagoinm = pg_fetch_array($result, NULL, PGSQL_BOTH);

?>

<?php include("header.php");?>

<body>
	<table width="900" border="0" align="center" bgcolor="#f5f0e4">
		<tr>
			<td> <?php include("menu.php");?></td>
		</tr>
  	<tr>
    	<td>
				<?php
				if ($pago == "liquidacion") {
					if (isset($liquidacion['id'])) {
					?>
						<div align="center" class="Estilo25">
		          <?php
		          //Si hay un mensaje de error o exito, lo mostramos

		          if (isset($_SESSION['msj_exito'])) {
		          ?>
								<br>
		            <div class="div_exito">
		              <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
		              <?php echo $_SESSION['msj_exito'];?>
		            </div>
		            <br>
		          <?php
		          //Quitamos el mensaje para futuras actualizaciones
		          unset($_SESSION['msj_exito']);
		          }

		          if (isset($_SESSION['msj_error'])) {
		          ?>
								<br>
		            <div class="div_error">
		              <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
		              <?php echo $_SESSION['msj_error'];?>
		            </div>
		            <br>
		          <?php
		          //Quitamos el mensaje para futuras actualizaciones
		          unset($_SESSION['msj_error']);
		          }
		          ?>
							<p>PAGO DEL IPBI</p>
								<table width="700" border="0">
									<tr>
										<td width="15%">
											<div align="right" class="Estilo4">Gestión: </div>
										</td>
										<td width="10%">
											<input name="gestion" id="gestion" type="text" value="<?php if (isset($gestion)){echo $gestion;}?>" style="width : 100%;" readonly>
										</td>

										<td width="15%">
											<div align="right" class="Estilo4">Importe: </div>
										</td>
										<td width="15%">
					            <?php
					              $total = $liquidacion['impuestoneto'] - $liquidacion['pagotermino'] + $liquidacion['formulario'];
					            ?>
											<input name="importe" id="importe" type="text" value="<?php if (isset($total)){echo number_format($total, 2, '.', ',');}?>" style="width : 100%;" readonly>
										</td>

										<td width="15%">
											<div align="right" class="Estilo4">Fecha Pago: </div>
										</td>
										<td width="15%">
											<input name="fecha_pago" id="fecha_pago" type="text" value="<?php echo $fecha_hoy;?>" style="width : 100%;" readonly>
										</td>
									</tr>
								</table>
								<table width="750" border="0">
									<p>Dirección y/o Ubicación</p>
									<tr>
										<td>
											<div align="right" class="Estilo4">Inmueble: </div>
										</td>
										<td colspan=5>
											<input name="inmueble" id="inmueble" type="text" value="<?php if (isset($liquidacion['tipoinm'])){echo $liquidacion['tipoinm'];}?>" style="width : 100%;" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">Barrio: </div>
										</td>
										<td colspan=5>
											<input name="barrio" id="barrio" type="text" value="<?php if (isset($liquidacion['idbarrioinm'])){echo $liquidacion['idbarrioinm'];}?>" style="width : 100%;" readonly>
										</td>
									</tr>

									<tr>
										<td width="18%" >
											<div align="right" class="Estilo4">Nombre de Vía: </div>
										</td>
										<td width="45	%" colspan="3">
											<input name="nombre_via" id="nombre_via" type="text" value="<?php if (isset($liquidacion['nombreviainm'])){echo $liquidacion['nombreviainm'];}?>" style="width : 100%;" readonly>
										</td>

										<td width="15%" >
											<div align="right" class="Estilo4">Nro de Puerta: </div>
										</td>
										<td>
											<input name="nro_puerta" id="nro_puerta" type="text" value="<?php if (isset($liquidacion['numeroinm'])){echo $liquidacion['numeroinm'];}?>" style="width : 100%;" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">Depto: </div>
										</td>
										<td>
											<input name="depto" id="depto" type="text" value="<?php if (isset($liquidacion['depto'])){echo $liquidacion['depto'];}else {echo " ----------";}?>" style="width : 100%;" readonly>
										</td>

										<td>
											<div align="right" class="Estilo4">Bloque: </div>
										</td>
										<td>
											<input name="bloque" id="bloque" type="text" value="<?php if (isset($liquidacion['bloque'])){echo $liquidacion['bloque'];}else {echo " ----------";}?>" style="width : 100%;" readonly>
										</td>

										<td>
											<div align="right" class="Estilo4">Piso: </div>
										</td>
										<td>
											<input name="piso" id="piso" type="text" value="<?php if (isset($liquidacion['piso'])){echo $liquidacion['piso'];}else {echo " ----------";}?>" style="width : 100%;" readonly>
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
											<input name="codigo" id="codigo" type="text" value="<?php if (isset($liquidacion['codigo'])){echo $liquidacion['codigo'];}?>" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">Superficie: </div>
										</td>
										<td>
											<input name="superficie" id="superficie" type="text" value="<?php if (isset($liquidacion['superficie'])){echo $liquidacion['superficie'];}?>" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">Valor Unitario ZH: </div>
										</td>
										<td>
											<input name="zona" id="zona" type="text" value="<?php if (isset($liquidacion['zona'])){echo $liquidacion['zona'];}?>" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">(K1) Tipo de Vía: </div>
										</td>
										<td>
											<input name="tipo_via" id="tipo_via" type="text" value="<?php if (isset($liquidacion['k1'])){echo $liquidacion['k1'];}?>" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">(K2) Topográfico: </div>
										</td>
										<td>
											<input name="topografia" id="topografia" type="text" value="<?php if (isset($liquidacion['k2'])){echo $liquidacion['k2'];}?>" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">(K3) Forma: </div>
										</td>
										<td>
											<input name="forma" id="forma" type="text" value="<?php if (isset($liquidacion['k3'])){echo $liquidacion['k3'];}?>" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">(K4) Ubicación: </div>
										</td>
										<td>
											<input name="ubicacion" id="ubicacion" type="text" value="<?php if (isset($liquidacion['k4'])){echo $liquidacion['k4'];}?>" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">(K5) Servicios: </div>
										</td>
										<td>
											<input name="servicios" id="servicios" type="text" value="<?php if (isset($liquidacion['k5_val'])){echo $liquidacion['k5_val'];}?>" readonly>
										</td>
									</tr>

									<tr>
										<td>
											<div align="right" class="Estilo4">(K6) Frente Fondo: </div>
										</td>
										<td>
											<input name="frente_fondo" id="frente_fondo" type="text" value="<?php if (isset($liquidacion['k6'])){echo $liquidacion['k6'];}?>" readonly>
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
													if ($liquidacion['k5_1']==1){?>
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
													if ($liquidacion['k5_2']==1){?>
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
													if ($liquidacion['k5_3']==1){?>
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
													if ($liquidacion['k5_4']==1){?>
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
													if ($liquidacion['k5_5']==1){?>
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
																<input name="valor" id="valor" type="text" value="<?php if (isset($bloque['valunitario'])){echo $bloque['valunitario'];}?>" readonly>
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

								<table width="200" border="0">
									<tr>
										<form name="form_pagar" action="pagos_pagar.php" method="post">
											<input name="id" id="id" type="hidden" value="<?php echo $id;?>">
											<input name="gestion" type="hidden" value="<?php echo $gestion;?>">
											<input name="pago" type="hidden" value="<?php echo $pago;?>">
											<td>
												<div align="center" class="Estilo4">
													<button type="submit" onClick="return pregunta_pagar()"><i class="fas fa-donate"> Pagar</i></button>
												</div>
											</td>
										</form>

										<form name="form" action="pdf_sello_pago.php" method="post">
											<input name="id" id="id" type="hidden" value="<?php echo $id;?>">
											<input name="gestion" type="hidden" value="<?php echo $gestion;?>">
											<input name="pago" type="hidden" value="<?php echo $pago;?>">
											<td>
												<div align="center" class="Estilo4">
													<button type="submit" formtarget="_blank"
													<?php
													//Si no se registro el pago, deshabilitamos el boton sello
													if (!isset($pagoinm['id_pagoinm'])){echo "disabled";}?>
													><i class="fas fa-stamp"> Sello</i></button>
												</div>
											</td>
										</form>
									</tr>

									<tr>
										<td>&nbsp;</td>
									</tr>
								</table>
						</div>
					<?php
					}
					else {
						//Si no existe el registo en la tabla liquidacion
						?>
						<br>
						<div align="center">
							<div class="div_error">
	              <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
	              Estimado usuario, aún no se realizó la liquidación del inmueble con código catastral (<?php echo $id?>) y año (<?php echo $gestion; ?>) introducidos
							</div>
            </div>
						<br>
						<?php
					}
				}
				?>
				<div align="center" class="Estilo4">
					<form name="form" action="pagos_seleccionar_inmueble_form.php" method="post">
						<div align="center" class="Estilo4">
							<button type="submit"><i class="fa fa-arrow-circle-left"> Anterior</i></button>
						</div>
					</form>
				</div>
				<br>
			</td>
  	</tr>
	</table>
</body>
<?php include("scripts.php");?>
<script language="JavaScript">
function pregunta_pagar(){
		if (confirm('Esta acción realizará el pago del inmueble, con los datos de la última liquidación realizada')){
			 document.getElementById("form_pagar").submit();
		}
		return false;
}
</script>
</html>
