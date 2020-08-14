<?
  include("inc.config.php");
  $link=Conectarse();
  $idprovincia = $_POST["provincia"];

  //Si la provincia seleccionada es (id 0), mostramos ------ en provincia y municipio
  if ($idprovincia == '0') {
    echo "<option value='0'>-------------------</option>";
  }
  else {
    echo "<option value=''> Seleccionar Municipio</option>";
  }

  $sql = "SELECT idmunicipio, municipio
          FROM municipios
          WHERE idprovincia='$idprovincia'
          ORDER BY idmunicipio";
  $municipios = mysql_query($sql, $link);
  while ($municipio = mysql_fetch_array($municipios)) {
    echo "<option value=". $municipio[0]. ">". $municipio[1]." </option>";
  }
?>
