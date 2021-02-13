<?php
    
    //Indicamos que requerimos la clase de la base de datos
    require ("../libs/Database.php");
    session_start();
    $tipo_consulta='';
    $preguntas = 0;
    $ingreso = 'Error al ingresar datos';
    $error = '';
    //Vemos si hemos recibido un metodo post al darle clic en enviar
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //Si el paciente tiene problemas para respirar directo a urgencias
        if(((int) trim($_POST["respirar"])) == 1){
            $tipo_consulta='El paciente debe de ir a urgencias';
            $preguntas = 14;
        }
        else{
            //Sumamos los valores de cada uno de los radio buttons que no corresponden a la intensidad (enviados en método post con el indice del valor name del radio button)
            $preguntas = ((int) trim($_POST["fiebre"])) + ((int) trim($_POST["cabeza"])) + ((int) trim($_POST["respirar"])) + ((int) trim($_POST["hueso"])) + ((int) trim($_POST["cansancio"])) + ((int) trim($_POST["flujo"])) + ((int) trim($_POST["alergia"]));
            //Checamos la existencia de la seleccion de los radio buttons de intensidad y que se haya seleccionado el radio button que indica la presencia del malestar y sumamos valores
            if(isset($_POST["fiebreC"]) && $_POST["fiebre"] != "0"){
                $preguntas += ((int) trim($_POST["fiebreC"]));
            }
            if(isset($_POST["cabezaC"]) && $_POST["cabeza"] != "0"){
                $preguntas += ((int) trim($_POST["cabezaC"]));
            }
            if(isset($_POST["respirarC"]) && $_POST["respirar"] != "0"){
                $preguntas += ((int) trim($_POST["respirarC"]));
            }
            if(isset($_POST["huesoC"]) && $_POST["hueso"] != "0"){
                $preguntas += ((int) trim($_POST["huesoC"]));
            }
            if(isset($_POST["cansancioC"]) && $_POST["cansancio"] != "0"){
                $preguntas += ((int) trim($_POST["cansancioC"]));
            }
            if(isset($_POST["flujoC"]) && $_POST["flujo"] != "0"){
                $preguntas += ((int) trim($_POST["flujoC"]));
            }
            if(isset($_POST["alergiaC"]) && $_POST["alergia"] != "0"){
                $preguntas += ((int) trim($_POST["alergiaC"]));
            }
        }

        //Checamos la suma de las respuestas para ver donde enviarlo
        if($preguntas >= 14){
            $tipo_consulta='urgencias';
        }
        else if($preguntas >= 7){
            $tipo_consulta='consulta externa';
        }
        else{
            $tipo_consulta='no';
            $ingreso = 'El paciente no debe de ser ingresado';
        }

        // Si el paciente muestra enfermedad procedemos a ingresarlo
        if($preguntas >= 7){
            try{
                // Creamos instancia del objeto Database y obtenemos su conexion
                $instancia = Database::getInstance();
                $conexion = $instancia->getConnection();

                // Creamos el query de insercion de los datos demograficos del usuario con comodines para las variables
                $sql = "INSERT INTO usuarios (nombre, fecha_nacimiento, direccion_calle, telefono, ocupacion, genero, estado_civil, fkEstado, fkMunicipio, correo, RFC) values(?,?,?,?,?,?,?,?,?,?,?)";
                // Preparamos la statement para el ingreso de datos
                $stmt = $conexion->prepare($sql);
                // Creamos variables de tipo int (bind_param no permite el casteo como parametro)
                $fkEstado = (int) $_POST["estado"];
                $fkMunicipio = (int) $_POST["municipio"];
                //Agregamos todas las variables del usuario y ejecutamos
                $stmt->bind_param("sssssssiiss",$_POST["name"],$_POST["fecha"],$_POST["direccion"],$_POST["telefono"],$_POST["ocupacion"],$_POST["genero"],$_POST["civil"],$fkEstado,$fkMunicipio,$_POST["email"],$_POST["rfc"]);
                $exito=$stmt->execute();

                //En caso de haber ingresado
                if($exito){
                    //Obtenemos los datos del usuario recien ingresado
                    $sql = "SELECT pkUsuarios FROM usuarios WHERE nombre = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("s",$_POST["name"]);
                    $stmt->execute();
                    $usuario = $stmt->get_result();

                    $resultado = array();

                    //Obtenemos las filas resultantes del query
                    while($row = $usuario -> fetch_assoc()){
                        //Iteramos las filas con la tupla (columna, valor)
                        foreach($row as $cname => $cvalue){
                            //Guardamos en arreglo
                            $resultado[$cname] = $cvalue;
                        }
                    }

                    // Creamos el perfil del usuario creado con la llave primaria del usuario (perfil tipo 3 es de pacientes)
                    $sql = "INSERT INTO perfilesUsuarios (fkUsuarios,fkPerfiles) values(?,3)";
                    //Preparamos, agregamos la variable de la llave del usuario y ejecutamos el statement
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("i",$resultado["pkUsuarios"]);
                    $exito = $stmt->execute();

                    //Creamos el query para ingreso de datos del formulario
                    $sql = "INSERT INTO formulario (fiebre, intensidad_fiebre, dolor_de_cabeza, dolor_de_cabeza_intensidad, dificultad_respirar, dificultad_respirar_intensidad, dolor_huesos, dolor_huesos_intensidad, cansancio, cansancio_intensidad, flujo_nasal, flujo_nasal_intensidad, alergias, alergias_tipo, fkUsuarios, fecha, tipoPaciente, tipo_consulta) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $conexion->prepare($sql);
                    $tipo_alergia = "";

                    // Checamos que se haya seleccionado una cantidad de alergia y que se haya afirmado la presencia de alergias para elegir su tipo
                    if(isset($_POST["alergiaC"]) && $_POST["alergia"] != "0"){
                        if((int) $_POST["alergiaC"] == 1){
                            $tipo_alergia = "Alimentos";
                        }else{
                            $tipo_alergia = "Medicamentos";
                        }
                    }
                    //Obtenemos la fecha de hoy en formato YYYY-MM-DD
                    $current_date = date("Y-m-d H:i:s");
                    //Obtenemos la variable fiebre y checamos si existe la variable cantidad de fiebre en el contexto adecuado
                    $fiebre = ((int) $_POST["fiebre"]);
                    $fiebreC = 0;
                    if(isset($_POST["fiebreC"]) && $_POST["fiebre"] != "0"){
                        $fiebreC = ((int) $_POST["fiebreC"]);
                    }
                    //Obtenemos la variable dolor de cabeza y checamos si existe la variable cantidad de dolor de cabeza en el contexto adecuado
                    $cabeza = ((int) $_POST["cabeza"]);
                    $cabezaC = 0;
                    if(isset($_POST["cabezaC"]) && $_POST["cabeza"] != "0"){
                        $cabezaC = ((int) $_POST["cabezaC"]);
                    }
                    //Obtenemos la variable dificultad para respirar y checamos si existe la variable cantidad de dificultad para respirar en el contexto adecuado
                    $respirar = ((int) $_POST["respirar"]);
                    $respirarC = 0;
                    if(isset($_POST["respirarC"]) && $_POST["respirar"] != "0"){
                        $respirarC = ((int) $_POST["respirarC"]);
                    }
                    //Obtenemos la variable dolor de huesos y checamos si existe la variable cantidad de dolor de huesos en el contexto adecuado
                    $hueso = ((int) $_POST["hueso"]);
                    $huesoC = 0;
                    if(isset($_POST["huesoC"]) && $_POST["hueso"] != "0"){
                        $huesoC = ((int) $_POST["huesoC"]);
                    }
                    //Obtenemos la variable cansancio y checamos si existe la variable cantidad de cansancio en el contexto adecuado
                    $cansancio = ((int) $_POST["cansancio"]);
                    $cansancioC = 0;
                    if(isset($_POST["cansancioC"]) && $_POST["cansancio"] != "0"){
                        $cansancioC = ((int) $_POST["cansancioC"]);
                    }
                    //Obtenemos la variable flujo nasal y checamos si existe la variable cantidad de flujo nasal en el contexto adecuado
                    $flujo = ((int) $_POST["flujo"]);
                    $flujoC = 0;
                    if(isset($_POST["flujoC"]) && $_POST["flujo"] != "0"){
                        $flujoC = ((int) $_POST["flujoC"]);
                    }
                    //Obtenemos la variable alergia
                    $alergia = ((int) $_POST["alergia"]);
                    $fkUsuarios = ((int) $resultado["pkUsuarios"]);
                    $true = true;

                    // Ingresamos las variables al statement y ejecutamos
                    $stmt->bind_param("iiiiiiiiiiiiisisbs",$fiebre,$fiebreC,$cabeza,$cabezaC,$respirar,$respirarC,$hueso,$huesoC,$cansancio,$cansancioC,$flujo,$flujoC, $alergia, $tipo_alergia, $fkUsuarios, $current_date, $true, $tipo_consulta);
                    $exito = $stmt->execute();

                    // Checamos si el ingreso fue exitoso
                    if($exito){
                        $ingreso='Ingreso exitoso';
                    }
                }else{
                    //Indicamos que el usuario no pudo ser creado en la base de datos
                    $error = "Los datos ingresados son erróneos o ya existen en la base de datos";
                    $tipo_consulta = '';    
                }

            }catch(Exception $err){
                $ingreso = $err->getMessage();
                $error = "Los datos ingresados son erróneos o ya existen en la base de datos";
                $tipo_consulta = '';
            }
        }
    }
    
?>
<!DOCTYPE html>
<html>

<?php
    include ("head.php");
?>

<body>
    <?php
        if($error != ''){
            //Si existe mensaje de error lo mostramos con un alert
            echo '<script>alert("'.$error.'")</script>'; 
        }else if($tipo_consulta != '' && $tipo_consulta != 'no'){
            //Indicamos de que forma fue ingresado el usuario a la base de datos (CE/Urgencias)
            echo '<script>alert("El paciente fue ingresado con exito a '.$tipo_consulta.'")</script>'; 
        }else if($tipo_consulta == 'no'){
            //Indicamos que el paciente está sano según la encuesta
            echo '<script>alert("El paciente está sano y no fue ingresado al sistema")</script>'; 
        }
    ?>  
    <div id="wrapper">

    <!-- AQUI VA EL NAV -->

        <?php
            require ("navigation.php");
        ?>
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                
            </ul>

        </nav>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        </div>
                        <div class="ibox-content">
                            <h2>
                                Ingrese nuevo paciente
                            </h2>

                            <form id="form" class="wizard-big" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <h1>Información</h1>
                                <fieldset>
                                    <h2>Información básica del paciente</h2>
                                    <div class="row">
                                        <div class="col-lg-8">
                                             <div class="form-group">
                                                <label>Nombre </label>
                                                <input id="name" name="name" type="text" class="form-control required">
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha de Nacimiento </label>
                                                <input id="date" name="fecha" type="date" class="form-control required">
                                            </div>
                                            <div class="form-group">
                                                <label>Dirección </label>
                                                <input id="direccion" name="direccion" type="text" class="form-control required">
                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono </label>
                                                <input id="telefono" name="telefono" type="tel" class="form-control required">
                                            </div>
                                            <div class="form-group">
                                                <label>Ocupación </label>
                                                <input id="ocupacion" name="ocupacion" type="text" class="form-control required">
                                            </div>
                                            <div class="form-group">
                                                <label>Género</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="genero" id="genmujer" value="M">
                                                    <label class="form-check-label" for="genmujer">
                                                    Mujer
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="genero" id="genhombre" value="H">
                                                    <label class="form-check-label" for="genhombre">
                                                    Hombre
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="genero" id="genotro" value="O">
                                                    <label class="form-check-label" for="genotro">
                                                    Otro
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Estado Civil</label>
                                                <select class="form-control" id="exampleFormControlSelect1" name="civil">
                                                <option>Soltero/a</option>
                                                <option>Casado/a</option>
                                                <option>Viudo/a</option>
                                                <option>Divorciado/a</option>
                                                <option>Unión libre o unión de hecho</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>RFC </label>
                                                <input id="rfc" name="rfc" type="text" class="form-control required" maxlength="13">
                                            </div>
                                            <div class="form-group">
                                                <label>Email </label>
                                                <input id="email" name="email" type="text" class="form-control required email">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="text-center">
                                                <div style="margin-top: 25px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <label for="exampleFormControlSelect1">Estado</label>
                                         <select class="form-control" id="exampleFormControlSelect1" name="estado">
                                        <option value="9">CDMX</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Municipio</label>
                                        <select class="form-control" id="exampleFormControlSelect1" name="municipio">
                                            <option value="274">Azcpotzalco</option>
                                            <option value="275">Coyoacan</option>
                                            <option value="276">Cuajimalpa de morelos</option>
                                            <option value="277">G.A.M</option>
                                            <option value="278">Iztacalco</option>
                                            <option value="279">Iztapalapa</option>
                                            <option value="280">La Magdalena Contreras</option>
                                            <option value="281">Milpa alta</option>
                                            <option value="282">Alvaro Obregon</option>
                                            <option value="283">Tlahuac</option>
                                            <option value="284">Tlalpan</option>
                                            <option value="285">Xochimilco</option>
                                            <option value="286">Benito Juarez</option>
                                            <option value="287">Cuauhtémoc</option>
                                            <option value="288">Miguel Hidalgo</option>
                                            <option value="289">Venustiano Carranza</option>
                                        </select>
                                    </div>
                                </fieldset>
                                <h1>Síntomas</h1>
                                <fieldset>
                                    <h2>Síntomas que presenta el paciente</h2>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta fiebre?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input radium-b" type="radio" name="fiebre" id="fiebresi" value="1" onclick="desplegar(this,'place')" required>
                                                    <label class="form-check-label"  for="fiebresi">
                                                    Sí
                                                    </label>
                                                    <div id="place" style="display: none;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="fiebreC" id="fiebremenos" value="1">
                                                            <label class="form-check-label" for="fiebremenos">
                                                            38°
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="fiebreC" id="fiebremas" value="2">
                                                            <label class="form-check-label" for="fiebremas">
                                                            39°
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="fiebre" id="fiebreno" value="0" onclick="desplegar(this,'place')">
                                                    <label class="form-check-label" for="fiebreno">
                                                    No
                                                    </label>
                                            </div>
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta dolor de cabeza?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="cabeza" id="cabezasi" value="1" onclick="desplegar(this,'place1')" required>
                                                    <label class="form-check-label" for="cabezasi">
                                                    Sí
                                                    </label>
                                                    <div id="place1" style="display: none;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="cabezaC" id="cabezamenos" value="1">
                                                            <label class="form-check-label" for="fiebremenos">
                                                             Moderado
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="cabezaC" id="cabezamas" value="2">
                                                            <label class="form-check-label" for="fiebremas">
                                                             Intenso
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="cabeza" id="cabezano" value="0" onclick="desplegar(this,'place1')">
                                                    <label class="form-check-label" for="cabezano">
                                                    No
                                                    </label>
                                            </div>
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta dificultad para respirar?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="respirar" id="respirarsi" value="1" onclick="desplegar(this,'place2')" required>
                                                    <label class="form-check-label" for="respirarsi">
                                                    Sí
                                                    </label>
                                                    <div id="place2" style="display: none;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="respirarC" id="respirarmenos" value="1">
                                                            <label class="form-check-label" for="respirarmenos">
                                                             Moderado
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="respirarC" id="respirarmas" value="2">
                                                            <label class="form-check-label" for="respirarmas">
                                                             Intenso
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="respirar" id="respirarno" value="0" onclick="desplegar(this,'place2')">
                                                    <label class="form-check-label" for="respirarno">
                                                    No
                                                    </label>
                                            </div>
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta dolor de huesos?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="hueso" id="huesosi" value="1" onclick="desplegar(this,'place3')" required>
                                                    <label class="form-check-label" for="huesosi">
                                                    Sí
                                                    </label>
                                                    <div id="place3" style="display: none;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="huesoC" id="huesomenos" value="1">
                                                            <label class="form-check-label" for="huesomenos">
                                                             Moderado
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="huesoC" id="huesomas" value="2">
                                                            <label class="form-check-label" for="huesomas">
                                                             Intenso
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="hueso" id="huesono" value="0" onclick="desplegar(this,'place3')">
                                                    <label class="form-check-label" for="huesono">
                                                    No
                                                    </label>
                                            </div>
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta cansancio?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="cansancio" id="cansanciosi" value="1" onclick="desplegar(this,'place4')" required>
                                                    <label class="form-check-label" for="cansanciosi">
                                                    Sí
                                                    </label>
                                                    <div id="place4" style="display: none;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="cansancioC" id="cansanciomenos" value="1">
                                                            <label class="form-check-label" for="cansanciomenos">
                                                             Moderado
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="cansancioC" id="cansanciomas" value="2">
                                                            <label class="form-check-label" for="cansanciomas">
                                                             Intenso
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="cansancio" id="cansanciono" value="0" onclick="desplegar(this,'place4')">
                                                    <label class="form-check-label" for="cansanciono">
                                                    No
                                                    </label>
                                            </div>
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta flujo nasal?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flujo" id="flujosi" value="1" onclick="desplegar(this,'place5')" required>
                                                    <label class="form-check-label" for="flujosi">
                                                    Sí
                                                    </label>
                                                    <div id="place5" style="display: none;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="flujoC" id="flujomenos" value="1">
                                                            <label class="form-check-label" for="flujomenos">
                                                             Moderado
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="flujoC" id="flujomas" value="2">
                                                            <label class="form-check-label" for="flujomas">
                                                             Intenso
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flujo" id="flujono" value="0" onclick="desplegar(this,'place5')">
                                                    <label class="form-check-label" for="flujono">
                                                    No
                                                    </label>
                                            </div>
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta alergias?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="alergia" id="alergiasi" value="1" onclick="desplegar(this,'place6')" required>
                                                    <label class="form-check-label" for="alergiasi">
                                                    Sí
                                                    </label>
                                                    <div id="place6" style="display: none;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="alergiaC" id="alergia-medicamentos" value="2">
                                                            <label class="form-check-label" for="alergia-medicamentos">
                                                             Medicamentos
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="alergiaC" id="alergia-alimentos" value="1">
                                                            <label class="form-check-label" for="alergia-alimentos">
                                                             Alimentos
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="alergia" id="alergiano" value="0" onclick="desplegar(this,'place6')">
                                                    <label class="form-check-label" for="alergiano">
                                                    No
                                                    </label>
                                            </div>
                                        </div>
                                </fieldset>

                               

                                <h1>Terminar</h1>
                                <fieldset>
                                    <h2>¿Acepta los términos y condiciones?</h2>
                                    <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis quos voluptatibus at quam sed a, qui laudantium, dolorum iure cumque iusto delectus cupiditate suscipit nemo quae, libero cum tempore ut.</label>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    </div>

                </div>
            </div>
        </div>
        </div>



    <?php include("scripts.php");?>


    <script>
        $(document).ready(function(){
            $("#wizard").steps();
            $("#form").steps({
                bodyTag: "fieldset",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Always allow going backward even if the current step contains invalid fields!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }

                    // Forbid suppressing "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age").val()) < 18)
                    {
                        return false;
                    }

                    var form = $(this);

                    // Clean up if user went backward before
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        $(".body:eq(" + newIndex + ") label.error", form).remove();
                        $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                    }

                    // Disable validation on fields that are disabled or hidden.
                    form.validate().settings.ignore = ":disabled,:hidden";

                    // Start validation; Prevent going forward if false
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Suppress (skip) "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age").val()) >= 18)
                    {
                        $(this).steps("next");
                    }

                    // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        $(this).steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    var form = $(this);

                    // Disable validation on fields that are disabled.
                    // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                    form.validate().settings.ignore = ":disabled";

                    // Start validation; Prevent form submission if false
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    var form = $(this);

                    // Submit form input
                    form.submit();
                }
            }).validate({
                        errorPlacement: function (error, element)
                        {
                            element.before(error);
                        },
                        rules: {
                            confirm: {
                                equalTo: "#password"
                            }
                        }
                    });
       });
    </script>

</body>

</html>
