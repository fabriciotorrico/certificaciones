function aparecelogin (){
	event.preventDefault();
	$("#login").animate({
		'top':'0'
	}, 500);
}
function desaparecelogin (){
	event.preventDefault();
	$("#login").animate({
		'top':'-100'
	}, 500);
}
function desapareceRegistro (){
	$("#oscurecer").fadeOut();
}
function desapareceFormulario(){
	$("#registrar").fadeOut(300, desapareceRegistro);
}
function mostrarFormulario (){
	$("#registrar").fadeIn();
	$("#oscurecer").click(desapareceFormulario);
}
function apareceRegistro(e){
	e.preventDefault();
	$("#oscurecer").fadeIn(500, mostrarFormulario);
}		
function mostrarLoginYRegistro(){
	$("#activarLogin").click(aparecelogin);
	$("#cerrar").click(desaparecelogin);
	$("#activarRegistro").click(apareceRegistro);
}
$(document).ready (mostrarLoginYRegistro);