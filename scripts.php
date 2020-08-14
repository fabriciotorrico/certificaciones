<!--Script para estilo acordeon-->
<script>
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  }
</script>

<!--Script para hacer desaparecer el texto del div exito en 3000 ms (3 segundos)-->
<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() {
        $(".div_exito").fadeOut(1500);
    },6000);
});
</script>
