<input type="text" id="data" placeholder="Ingresa un valor">
  <button type="button" id="generar_barcode">Generar código de barras</button>
  <div id="imagen"></div>
<img src="barcode\\barcode.php?text='+data+'&size=90&codetype=Code39&print=true"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script>
    $("#generar_barcode").click(function() {
    var data = $("#data").val();
    $("#imagen").html('<img src="barcode\\barcode.php?text='+data+'&size=90&codetype=Code39&print=true"/>');
    $("#data").val('');
    });
  </script>
