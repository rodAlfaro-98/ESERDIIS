function desplegar(elemento,lugar) {
  var x = document.getElementById(lugar);
  if(elemento.value == 1){
    x.style.display = "block";
  }
  if(elemento.value == 0){
    x.style.display = "none"
  }

}