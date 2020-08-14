<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();
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
						<table width="500" border="0">
							<tr>
								<td>
									<div align="center" class="Estilo4"><b>Vendedor o Cedente</b></div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4">
										Número de Documento:
										<input type="text" onKeyUp="buscar_vendedor()" name="ci_v" id="ci_v" placeholder="Introduzca el ci del vendedor o cedente buscado" style="width: 60%" required>
									</div>

								</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4">
										Nombre: <input type="text" name="nombre_vendedor" id="nombre_vendedor" style="width: 80%" readonly>
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4">
										Razon Social: <input type="text" name="razon_social_vendedor" id="razon_social_vendedor" readonly>
										NIT: <input type="text" name="nit_vendedor" id="nit_vendedor" readonly>
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4"><b>Comprador o Beneficiario</b></div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4">
										Número de Documento:
										<input type="text" onKeyUp="buscar_comprador()" name="ci_c" id="ci_c" placeholder="Introduzca el ci del comprador o beneficiario buscado" style="width: 60%" required>
									</div>

								</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4">
										Nombre: <input type="text" name="nombre_comprador" id="nombre_comprador" style="width: 80%" readonly>
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div align="center" class="Estilo4">
										Razon Social: <input type="text" name="razon_social_comprador" id="razon_social_comprador" readonly>
										NIT: <input type="text" name="nit_comprador" id="nit_comprador" readonly>
									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<form name="form" action="transf_inm_seleccionar_inmueble_form.php" method="post">
										<div align="center" class="Estilo4">
											<input type="hidden" name="ci_comprador" id="ci_comprador" required>
											<input type="hidden" name="ci_vendedor" id="ci_vendedor" required>
											<button type="submit"><i class="fa fa-arrow-circle-right"> Siguiente</i></button>
										</div>
									</form>
								</td>
							</tr>


							<tr>
								<td>&nbsp;</td>
							</tr>

							<tr>
								<td>
									<form name="form" action="home.php" method="post">
										<div align="center" class="Estilo4">
											<button type="submit"><i class="fa fa-home"> Inicio</i></button>
										</div>
									</form>
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
	function buscar_vendedor() {
		$.getJSON("buscar_persona_por_ci.php", {ci: $("#ci_v").val()})
      .done(function(respuesta){
        //document.getElementById("mostrar_vendedor").innerHTML = respuesta.ci;
        document.getElementById("ci_vendedor").value = respuesta.ci;
        document.getElementById("nombre_vendedor").value = respuesta.nombre;
      });
	}

	function buscar_comprador() {
		$.getJSON("buscar_persona_por_ci.php", {ci: $("#ci_c").val()})
      .done(function(respuesta){
        //document.getElementById("mostrar_vendedor").innerHTML = respuesta.ci;
        document.getElementById("ci_comprador").value = respuesta.ci;
        document.getElementById("nombre_comprador").value = respuesta.nombre;
      });
	}
</script>
</html>
