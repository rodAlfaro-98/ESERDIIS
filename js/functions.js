function desplegar(elemento,lugar) {
  var x = document.getElementById(lugar);
  if(elemento.value == "yes"){
    x.style.display = "block";
  }
  if(elemento.value == "no"){
    x.style.display = "none"
  }

}