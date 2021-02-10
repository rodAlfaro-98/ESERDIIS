function desplegar(elemento,lugar) {
  var x = document.getElementById(lugar);
  if(elemento.value == 1){
    x.style.display = "block";
  }
  if(elemento.value == 0){
    x.style.display = "none"
  }

}

function desplegarreporte(elemento) {
  var x = document.getElementById("opt");
  var y = document.getElementById("opt1");
  if(elemento.value == 1){
    x.style.display = "block";
    y.style.display = "none";
  }
  if(elemento.value == 0){
    x.style.display = "none";
    y.style.display = "block";
  }
}    
function buscar(elemento) {
  var x = document.getElementById("bton");
  if(elemento.value == 'H' || elemento.value == 'M' || elemento.value == 'O' || elemento.value == 0 || elemento.value == 1 || elemento.value == 2 || elemento.value == "urgencias" || elemento.value == "consulta externa"){
    x.style.display = "block";

  }

}