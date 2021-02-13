<?php

    // Indicamos que requerimos la clase de base de datos
    require ("../libs/Database.php");
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        ///Obtenemos la fecha actual y le restamos 18 años
        $date = new DateTime('now');
        date_sub($date,date_interval_create_from_date_string("18 years"));

        $tipo = '';
        $genero = '';
        $menorEdad = $date;
        $comparacionMenor = $menorEdad;

        //Obtenemos la fecha actual y le restamos 65 años
        $date = new DateTime('now');
        date_sub($date,date_interval_create_from_date_string("65 years"));

        $terceraEdad = $date;
        $comparacionTercera = $terceraEdad;
        $consulta = '';
        $tipo_consulta = '';

        $matriz = array();

        //Revisamos que exista un tipo de consulta
        if(isset($_POST["consulttype"])){
            $tipo = trim($_POST["consulttype"]);
        }
        try{
            //Creamos la conexion a la base de datos
            $instancia = Database::getInstance();
            $conexion = $instancia->getConnection();
            //Revisamos si el tipo de consulta es demografico
            if($tipo == "1"){
                $tipo_consulta = "demo";
                $genero = "";
                //Obtenemos el genero seleccionado
                if(isset($_POST["genero"])){
                    $genero = $_POST["genero"];
                }
                $edad = "";
                //Obtenemos el intervalo de edad seleccionado
                if(isset($_POST["edad"])){
                    $edad = $_POST["edad"];    
                }
                $sql = "";
                //Si se eligio un genero realizaremos la query basado en el genero
                if($genero != ""){
                    //Query para obtencion de todos los usuarios que cumplan con el parametro de genero seleccionado
                    $sql = "SELECT nombre, fecha_nacimiento, direccion_calle, telefono, ocupacion, genero, rfc, correo FROM usuarios as u JOIN perfilesUsuarios as p on u.pkUsuarios = p.fkUsuarios where u.genero = ? and p.fkPerfiles = 3";
                    //Preparamos el statement
                    $stmt = $conexion->prepare($sql);
                    //Agregamos variables al statement
                    $stmt->bind_param("s",$genero);
                }else{ // Si no se eligio un genero obtenemos todos los usuarios para su posterior clasificacion
                    $sql = "SELECT nombre, fecha_nacimiento, direccion_calle, telefono, ocupacion, genero, rfc, correo FROM usuarios as u JOIN perfilesUsuarios as p on u.pkUsuarios = p.fkUsuarios where p.fkPerfiles = 3";
                    //Preparamos el statement
                    $stmt = $conexion->prepare($sql);
                }
                //Ejecutamos el statement
                $exito = $stmt->execute();

                //Chequemos no haya muerto el proceso
                if($exito){
                    //Obtenemos los resultados
                    $usuarios = $stmt->get_result();

                    //Si hay resultados
                    if($usuarios != null){
                        //Obtenemos cada una de las filas
                        while($row = $usuarios -> fetch_assoc()){
                            $resultado = array();
                            //Iteramos las filas con cada tupla (columna, valor)
                            foreach($row as $cname => $cvalue){
                                //Guardamos en el arreglo con indice nombre de columna
                                $resultado[$cname] = $cvalue;
                            }
                            //Guardamos el arreglo en la matriz
                            array_push($matriz,$resultado);
                        }
                    }
                }

                $matriz2 = array();
                //Iteramos la matriz
                foreach($matriz as $valor){
                    //Obtenemos la fecha de naciemiento del paciente
                    $fecha_nacimiento = new DateTime($valor["fecha_nacimiento"]);
                    if($edad == "0"){ //Revisamos si se busco que fuera menor de edad
                        if($comparacionMenor < $fecha_nacimiento){
                            //Si si y la edad se cumple se ingresa a la nueva matriz
                            array_push($matriz2,$valor);
                        }
                    }else if($edad == "1"){ //Revisamos si se busco a la poblacion economicamente activa
                        if($comparacionMenor > $fecha_nacimiento && $comparacionTercera < $fecha_nacimiento){
                            //Si si y la edad se cumple se ingresa a la nueva matriz
                            array_push($matriz2,$valor);
                        }
                    }else if($edad == "2"){ //Revisamos si se busco a la poblacion de la tercera edad
                        if($comparacionTercera > $fecha_nacimiento){
                            //Si si y la edad se cumple se ingresa a la nueva matriz
                            array_push($matriz2,$valor);
                        }
                    }
                }

                //Si si hay edad
                if($edad != ""){
                    $matriz = array();
                    //Ingresamos los valores de la matriz filtrada a la matriz original
                    foreach($matriz2 as $valor){
                        array_push($matriz,$valor);
                    }
                }

    

            }else if($tipo == "0"){
                // Obtenemos el tipo de consulta
                $tipo_consulta = "consulta";
                $consulta = trim($_POST["consulta"]);
                
                //Hacemos el query para obtener a los usuarios por su tipo de consulta
                $sql = "SELECT nombre, fecha_nacimiento, direccion_calle, telefono, ocupacion, genero, rfc, correo FROM usuarios AS u JOIN formulario AS f on u.pkUsuarios = f.fkUsuarios JOIN perfilesUsuarios as p on u.pkUsuarios = p.fkUsuarios where f.tipo_consulta = ? and p.fkPerfiles = 3";
                //Preparamos la query e ingresamos la variable
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("s",$consulta);
                //Ejecutamos
                $exito = $stmt->execute();

                if($exito){ //Si obtenemos los valores de la tabla
                    $usuarios = $stmt->get_result();

                    //Si si hay usuarios
                    if($usuarios != null){
                        //Obtenemos las filas de la consulta
                        while($row = $usuarios -> fetch_assoc()){
                            $resultado = array();
                            //Iteramos las filas por tupla (columna, valor)
                            foreach($row as $cname => $cvalue){
                                //Guardamos en el arreglo con indice igual a nombre de columna
                                $resultado[$cname] = $cvalue;
                            }
                            //Agregamos a matriz
                            array_push($matriz,$resultado);
                        }
                    }
                }
                else{
                    echo "Error al realizar la consulta";
                }

            }
        }catch(Exception $err){
            echo $err;
        }
    }
?>

<!DOCTYPE html>
<html>

<?php
    include ("head.php");
?>

<body>

    <div id="wrapper">

        <?php
            include ("navigation.php");
        ?>
 
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">

        </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                
                <h2>Reportes</h2>
                </div>
                
                <form class="m-t" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <table width="100%" style="margin: 0px;">
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input radium-b" type="radio" name="consulttype" value="1" onclick="desplegarreporte(this)" required>
                                    <label class="form-check-label"  for="fiebresi">
                                        <b>Género</b>
                                    </label>
                                <div class:"row">    
                                    <div id="opt" style="display: none;">
                                        <table>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="genero" id="hombre" value="H" onclick="buscar(this)">
                                                        <label class="form-check-label" for="hombre">
                                                            Hombre
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="genero" id="mujer" value="M" onclick="buscar(this)">
                                                        <label class="form-check-label" for="mujer">
                                                            Mujer
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="genero" id="otro" value="O" onclick="buscar(this)">
                                                        <label class="form-check-label" for="otro">
                                                            Otro
                                                        </label>
                                                    </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="edad" id="edad1" value="0" onclick="buscar(this)">
                                                        <label class="form-check-label" for="edad1">
                                                            Menor a 18 años
                                                        </label>
                                                    </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="edad" id="edad2" value="1" onclick="buscar(this)">
                                                        <label class="form-check-label" for="edad2">
                                                            Adultos
                                                        </label>
                                                    </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="edad" id="edad3" value="2" onclick="buscar(this)">
                                                        <label class="form-check-label" for="edad3">
                                                            Tercera edad
                                                        </label>
                                                    </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input radium-b" type="radio"  name="consulttype" value="0" onclick="desplegarreporte(this)" required>
                                    <label class="form-check-label"  for="fiebresi">
                                        <b>Consulta</b>
                                    </label>
                                <div class:"row">   
                                    <div id="opt1" style="display: none;">
                                        <table>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="consulta" id="urgencias" value="urgencias" onclick="buscar(this)">
                                                        <label class="form-check-label" for="urgencias">
                                                            Urgencias
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="consulta" id="externa" value="consulta externa" onclick="buscar(this)">
                                                        <label class="form-check-label" for="consulta externa">
                                                            Consulta Externa
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                            <td>
                            <div id="bton" style="display: none;">
                                <button type="submit" class="btn btn-primary block full-width m-b">Buscar</button>
                            </div>
                            </td>
                        </tr>
                    </table>
                </form>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                   
                    <div class="ibox-content">

                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Ocupación</th>
                            <th>Genero</th>
                            <th>RFC</th>
                            <th>Correo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php   
                            // Revisamos si hay valores de la matriz
                            if(!empty($matriz))
                            {
                                foreach($matriz as $fila){ //Iteramos cada una de las filas de la matriz
                                    echo "<tr>";
                                    foreach($fila as $value){ //Iteramos los valores de cada fila
                                        //Revisamos si tratamos de genero para imprimir el nombre
                                        if($value == "H"){
                                            echo "<td>Hombre</td>";    
                                        } else if($value == "M"){
                                            echo "<td>Mujer</td>";    
                                        }else if($value == "O"){
                                            echo "<td>Otro</td>";    
                                        }
                                        else{ // Imprimimos el valor dentro de una tabla
                                            echo "<td>".$value."</td>";
                                        }
                                    }
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                    </table>
                        </div>

                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="footer">
            <div>
                <strong>Copyright</strong> ESERDI &copy; 2021                            
            </div>
        </div>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="../js/plugins/dataTables/datatables.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../js/inspinia.js"></script>
    <script src="../js/plugins/pace/pace.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>

        // Upgrade button class name
        $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-white btn-sm';

        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Consulta Pacientes'},
                    {extend: 'pdf', title: 'Consulta Pacientes'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

    </script>

</body>

</html>
