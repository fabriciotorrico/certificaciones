<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	//Tomamos los datos necesarios
	$ci_comprador = $_POST['ci_comprador'];
	$ci_vendedor = $_POST['ci_vendedor'];

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

	//Tomamos los inmuebles del vendedor
	$sql  = "SELECT L1.id, L1.idtipoinm, L1.barrio, L1.nombrevia, L1.codigo
					 FROM buscar AS B
					 LEFT JOIN liquidacion1 AS L1
					  ON B.id = L1.id
					 WHERE B.ci = '$ci_vendedor'";
	$inmuebles_vendedor = pg_query($link, $sql);
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
							<p>Inmuebles</p>
							<tr>
								<td width="10%">
									<div align="center" class="Estilo4">Seleccione un Inmueble</div>
								</td>
								<td>
									<select size="4" name="id_inmueble" id="id_inmueble" onchange="mostrar_datos()" style="width: 100%" required>
										<?php
										$correlativo = 1;
										while ($inmueble = pg_fetch_array($inmuebles_vendedor, NULL, PGSQL_BOTH)) {
										?>
											<option value="<?php echo $inmueble['id'];?>" <?php if ($correlativo == 1) {echo "selected";}?>> <?php echo $correlativo.".- ".$inmueble['idtipoinm'].", ".$inmueble['barrio'].", ".$inmueble['nombrevia']." (".$inmueble['codigo'].")";?></option>
										<?php
										$correlativo = $correlativo + 1;
										}
										?>
									</select>
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
									<input name="idtipoinm" id="idtipoinm" type="text" value=" --------------------" style="width : 100%;" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Barrio: </div>
								</td>
								<td colspan=5>
									<input name="barrio" id="barrio" type="text" value=" --------------------" style="width : 100%;" readonly>
								</td>
							</tr>

							<tr>
								<td width="18%" >
									<div align="right" class="Estilo4">Nombre de Vía: </div>
								</td>
								<td width="45	%" colspan="3">
									<input name="nombrevia" id="nombrevia" type="text" value=" --------------------" style="width : 100%;" readonly>
								</td>

								<td width="15%" >
									<div align="right" class="Estilo4">Nro de Puerta: </div>
								</td>
								<td>
									<input name="numeroinm" id="numeroinm" type="text" value=" --------------------" style="width : 100%;" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Depto: </div>
								</td>
								<td>
									<input name="depto" id="depto" type="text" value=" --------------------" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Bloque: </div>
								</td>
								<td>
									<input name="bloque" id="bloque" type="text" value=" --------------------" style="width : 100%;" readonly>
								</td>

								<td>
									<div align="right" class="Estilo4">Piso: </div>
								</td>
								<td>
									<input name="piso" id="piso" type="text" value=" --------------------" style="width : 100%;" readonly>
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
										<div id="con_agua_potable">
											<i class="fa fa-check-square-o">Agua Potable</i>
										</div>
										<div id="sin_agua_potable">
											<i class="fa fa-square-o">Agua Potable</i>
										</div>
									</div>
								</td>

								<td>
									<div align="right" class="Estilo4">
										<div id="con_alcantarillado">
											<i class="fa fa-check-square-o">Alcantarillado</i>
										</div>
										<div id="sin_alcantarillado">
											<i class="fa fa-square-o">Alcantarillado</i>
										</div>
									</div>
								</td>

								<td>
									<div align="right" class="Estilo4">
										<div id="con_energia_electrica">
											<i class="fa fa-check-square-o">Energía Eléctrica</i>
										</div>
										<div id="sin_energia_electrica">
											<i class="fa fa-square-o">Energía Eléctrica</i>
										</div>
									</div>
								</td>

								<td>
									<div align="right" class="Estilo4">
										<div id="con_telefono">
											<i class="fa fa-check-square-o">Teléfono</i>
										</div>
										<div id="sin_telefono">
											<i class="fa fa-square-o">Teléfono</i>
										</div>
									</div>
								</td>

								<td>
									<div align="right" class="Estilo4">
										<div id="con_transporte">
											<i class="fa fa-check-square-o">Transporte</i>
										</div>
										<div id="sin_transporte">
											<i class="fa fa-square-o">Transporte</i>
										</div>
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
									<input name="codigo" id="codigo" type="text" value=" --------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Superficie: </div>
								</td>
								<td>
									<input name="superficie" id="superficie" type="text" value=" --------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Valor Unitario ZH: </div>
								</td>
								<td>
									<input name="zona" id="zona" type="text" value=" --------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K1) Tipo de Vía: </div>
								</td>
								<td>
									<input name="tipovia" id="tipovia" type="text" value=" --------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K2) Topográfico: </div>
								</td>
								<td>
									<input name="topografia" id="topografia" type="text" value=" --------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K3) Forma: </div>
								</td>
								<td>
									<input name="forma" id="forma" type="text" value=" --------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K4) Ubicación: </div>
								</td>
								<td>
									<input name="ubicacion" id="ubicacion" type="text" value=" --------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K5) Servicios: </div>
								</td>
								<td>
									<input name="servicios" id="servicios" type="text" value=" --------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">(K6) Frente Fondo: </div>
								</td>
								<td>
									<input name="frentefondo" id="frentefondo" type="text" value=" --------------------" readonly>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Revalorización Técnica: </div>
								</td>
								<td>
									<input id="revalorizacion_tecnica" type="text" value=" ---------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Superficie en Hectáreas: </div>
								</td>
								<td>
									<input id="superficie_hectareas" type="text" value=" ---------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Valor en Hectáreas: </div>
								</td>
								<td>
									<input id="valor_hectareas" type="text" value=" ---------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>
									<div align="right" class="Estilo4">Exento : </div>
								</td>
								<td>
									<input id="exento" type="text" value=" ---------------------" readonly>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table width="800" border="0">
							<tr>
								<form name="form" action="transf_inm_importe_form.php" method="post">
									<input name="id_inm" id="id_inm" type="hidden">
									<input name="ci_vendedor" type="hidden" value="<?php echo $ci_vendedor;?>">
									<input name="ci_comprador" type="hidden" value="<?php echo $ci_comprador;?>">
									<td>
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
								<td>
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

<script type="text/javascript">
	function mostrar_datos() {
		$.getJSON("buscar_datos_inmueble_por_id.php", {id_inmueble: $("#id_inmueble").val()})
      .done(function(respuesta){
				//Establecemos los valores de los campos
        document.getElementById("id_inm").value = respuesta.id;
        document.getElementById("idtipoinm").value = respuesta.idtipoinm;
        document.getElementById("barrio").value = respuesta.barrio;
        document.getElementById("nombrevia").value = respuesta.nombrevia;
        document.getElementById("numeroinm").value = respuesta.numeroinm;
        document.getElementById("codigo").value = respuesta.codigo;
        document.getElementById("superficie").value = respuesta.superficie;
        document.getElementById("zona").value = respuesta.zona;
        document.getElementById("tipovia").value = respuesta.tipovia;
        document.getElementById("topografia").value = respuesta.topografia;
        document.getElementById("forma").value = respuesta.forma;
        document.getElementById("ubicacion").value = respuesta.ubicacion;
        document.getElementById("servicios").value = respuesta.servicios;
        document.getElementById("frentefondo").value = respuesta.frentefondo;
        document.getElementById("revalorizacion_tecnica").value = respuesta.revalorizacion_tecnica;
        document.getElementById("superficie_hectareas").value = respuesta.superficie_hectareas;
        document.getElementById("valor_hectareas").value = respuesta.valor_hectareas;
        document.getElementById("exento").value = respuesta.exento;

				//Establecemos el check que corresponda para los servicios
				if (respuesta.agua_potable == 1) {
					document.getElementById("con_agua_potable").style.display = 'block';
					document.getElementById("sin_agua_potable").style.display = 'none';
				}
				else {
					document.getElementById("con_agua_potable").style.display = 'none';
					document.getElementById("sin_agua_potable").style.display = 'block';
				}

				if (respuesta.alcantarillado == 1) {
					document.getElementById("con_alcantarillado").style.display = 'block';
					document.getElementById("sin_alcantarillado").style.display = 'none';
				}
				else {
					document.getElementById("con_alcantarillado").style.display = 'none';
					document.getElementById("sin_alcantarillado").style.display = 'block';
				}

				if (respuesta.energia_electrica == 1) {
					document.getElementById("con_energia_electrica").style.display = 'block';
					document.getElementById("sin_energia_electrica").style.display = 'none';
				}
				else {
					document.getElementById("con_energia_electrica").style.display = 'none';
					document.getElementById("sin_energia_electrica").style.display = 'block';
				}

				if (respuesta.telefono == 1) {
					document.getElementById("con_telefono").style.display = 'block';
					document.getElementById("sin_telefono").style.display = 'none';
				}
				else {
					document.getElementById("con_telefono").style.display = 'none';
					document.getElementById("sin_telefono").style.display = 'block';
				}

				if (respuesta.transporte == 1) {
					document.getElementById("con_transporte").style.display = 'block';
					document.getElementById("sin_transporte").style.display = 'none';
				}
				else {
					document.getElementById("con_transporte").style.display = 'none';
					document.getElementById("sin_transporte").style.display = 'block';
				}
      });
	}
</script>
<?php include("scripts.php");?>
</html>
