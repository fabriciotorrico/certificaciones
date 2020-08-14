<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos los datos necesarios
	$id_inm = $_POST['id_inm'];
	$ci_comprador = $_POST['ci_comprador'];
	$ci_vendedor = $_POST['ci_vendedor'];

	//Tomamos la fecha actual
	date_default_timezone_set('America/La_Paz');
	$fecha_hoy = date("Y-m-d");

	//Tomamos los datos del vendedor
	$sql  = "SELECT *
					 FROM buscar
					 WHERE ci = '$ci_vendedor'";
	$result = pg_query($link, $sql);
	$vendedor = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Tomamos los datos del comprador
	$sql  = "SELECT *
					 FROM buscar
					 WHERE ci = '$ci_comprador'";
	$result = pg_query($link, $sql);
	$comprador = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Tomamos el inmueble seleccionado
	$sql  = "SELECT id, idtipoinm, barrio, nombrevia, numeroinm, codigo
					 FROM transferencia1
					 WHERE id = '$id_inm'";
	$inmuebles = pg_query($link, $sql);
	$inmueble = pg_fetch_array($inmuebles, NULL, PGSQL_BOTH);

	//Tomamos el valor de tipo de cambio de hoy
	$sql  = "SELECT dolar
					 FROM dolarufv
					 WHERE fecha = '$fecha_hoy'";
	$result = pg_query($link, $sql);
	$dolarufv = pg_fetch_array($result, NULL, PGSQL_BOTH);
?>

<?php include("header.php");?>

<body onload="mostrar_datos()">
	<table width="900" border="0" align="center" bgcolor="#f5f0e4">
		<tr>
			<td> <?php include("menu.php");?></td>
		</tr>
  	<tr>
    	<td>
				<div align="center" class="Estilo25">
					<p>TRANSFERIR INMUEBLE</p>
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
							<p>Partes involucradas</p>
							<tr>
								<td width="25%">
									<div align="right" class="Estilo4">Vendedor o Cedente</div>
								</td>
								<td>
									<input type="text" value="<?php if (isset($vendedor['ci'])){echo $vendedor['ci']." - ".$vendedor['propietario'];}?>" style="width : 100%;" readonly>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right" class="Estilo4">Comprador o Beneficiario</div>
								</td>
								<td>
									<input type="text" value="<?php if (isset($comprador['ci'])){echo $comprador['ci']." - ".$comprador['propietario'];}?>" style="width : 100%;" readonly>
								</td>
							</tr>
						</table>

						<table width="750" border="0">
							<p>Dirección y/o Ubicación</p>
							<tr>
								<td>
									<div align="right" class="Estilo4">Inmueble: </div>
								</td>
								<td colspan=3>
									<input name="idtipoinm" id="idtipoinm" type="text" value="<?php if (isset($inmueble['idtipoinm'])){echo $inmueble['idtipoinm'];}?>" style="width : 100%;" readonly>
								</td>
								<td>
									<div align="right" class="Estilo4">Cod. Catastral: </div>
								</td>
								<td>
									<input name="codigo" id="codigo" type="text" value="<?php if (isset($inmueble['codigo'])){echo $inmueble['codigo'];}?>" style="width : 100%;" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Barrio: </div>
								</td>
								<td colspan=5>
									<input name="barrio" id="barrio" type="text" value="<?php if (isset($inmueble['barrio'])){echo $inmueble['barrio'];}?>" style="width : 100%;" readonly>
								</td>
							</tr>

							<tr>
								<td width="18%" >
									<div align="right" class="Estilo4">Nombre de Vía: </div>
								</td>
								<td width="45	%" colspan="3">
									<input name="nombre_via" id="nombre_via" type="text" value="<?php if (isset($inmueble['nombrevia'])){echo $inmueble['nombrevia'];}?>" style="width : 100%;" readonly>
								</td>

								<td width="15%" >
									<div align="right" class="Estilo4">Nro de Puerta: </div>
								</td>
								<td>
									<input name="nro_puerta" id="nro_puerta" type="text" value="<?php if (isset($inmueble['numeroinm'])){echo $inmueble['numeroinm'];}?>" style="width : 100%;" readonly>
								</td>
							</tr>

							<!--tr>
								<td>
									<div align="right" class="Estilo4">Depto: </div>
								</td>
								<td>
									<input name="depto" id="depto" type="text" value="<?php if (isset($inmueble['depto'])){echo $inmueble['depto'];}else {echo " --------------------";}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Bloque: </div>
								</td>
								<td>
									<input name="bloque" id="bloque" type="text" value="<?php if (isset($inmueble['bloque'])){echo $inmueble['bloque'];}else {echo " --------------------";}?>" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Piso: </div>
								</td>
								<td>
									<input name="piso" id="piso" type="text" value="<?php if (isset($inmueble['piso'])){echo $inmueble['piso'];}else {echo " --------------------";}?>" style="width : 100%;" readonly>
								</td>
							</tr-->

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="800" border="0">
							<p>Importe de la Transferencia</p>
							<form name="form_importe" action="transf_inm_impuesto_form.php" method="post">
							<tr>
								<td>
									<div align="right" class="Estilo4">Dólares ($us): </div>
								</td>
								<td>
									<input name="cambio_dolar" id="cambio_dolar" type="hidden" value="<?php echo $dolarufv['dolar']?>">
									<input name="dolares" id="dolares" type="number" min="0" step="0.01" style="width: 100%;" onKeyUp="dolares_a_bs()" required>
								</td>

								<td>
									<div align="right" class="Estilo4">Bolivianos (Bs.): </div>
								</td>
								<td>
									<input name="bolivianos" id="bolivianos" type="number" min="0" step="0.01" style="width: 100%;"  onKeyUp="bs_a_dolares()" required>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">DDRR: </div>
								</td>
								<td>
									<input name="ddrr" id="ddrr" type="int" style="width: 100%;" required>
								</td>

								<td>
									<div align="right" class="Estilo4">Minuta: </div>
								</td>
								<td>
									<input type="date" name="minuta" id="minuta" value="<?php echo $fecha_hoy;?>" style="width: 100%" required>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
									<td colspan="4">
										<div align="center" class="Estilo4">
											<input name="id_inmueble" id="id_inmueble" value="<?php echo $id_inm;?>" type="hidden">
											<input name="ci_vendedor" type="hidden" value="<?php echo $ci_vendedor;?>">
											<input name="ci_comprador" type="hidden" value="<?php echo $ci_comprador;?>">
											<button type="submit"><i class="fa fa-calculator"> Calcular Transferencia</i></button>
										</div>
									</td>
								</form>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td colspan="4">
									<div align="center" class="Estilo4">
										<button onclick="history.go(-2);"><i class="fa fa-arrow-circle-left"> Anterior</i></button>
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
      	</div>
			</td>
  	</tr>
	</table>
</body>
<script type="text/javascript">
	function dolares_a_bs() {
		var bs = document.getElementById("dolares").value * document.getElementById("cambio_dolar").value;
		var bs_redondeado = Number(bs.toFixed(2));
		document.getElementById("bolivianos").value = bs_redondeado;
	}

	function bs_a_dolares() {
		var dolares = document.getElementById("bolivianos").value / document.getElementById("cambio_dolar").value;
		var dolares_redondeado = Number(dolares.toFixed(2));
		document.getElementById("dolares").value = dolares_redondeado;
	}
</script>
<?php include("scripts.php");?>
</html>
