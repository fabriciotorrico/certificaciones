<?php
  include("verificar_login.php");
  include("inc.config.php");
?>

<?php include 'header.php'; ?>

<body>
	<table width="900" border="0" align="center" bgcolor="#f5f0e4">
    <tr>
      <td> <?php include("menu.php");?></td>
    </tr>

    <tr>
      <td>
        <div align="center">
          <p>&nbsp;</p>

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

          <p>&nbsp;</p>
          <p><img src="imagenes/logo_siim_web_home.png" alt="logo_home" width="352" height="447" longdesc="logo_home"></p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </div>
      </td>
    </tr>
  </table>
  <?php include 'scripts.php'; ?>
</body>
</html>
