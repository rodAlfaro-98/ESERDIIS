<?php

session_start();

//Indicamos que requerimos de la clase encargada de la conexión a la base de datos
require ("./libs/Database.php");

//Inicializamos a nulo las variables de login y de log del sistema
$email = $rfc = '';
$username_err = $password_err = '';
$correo_erroneo = $rfc_erroneo = $database_exception = '';


try{

    //Creamos una instancia del objeto Database y obtenemos su conexion
    $instancia = Database::getInstance();
    $conexion = $instancia->getConnection();

    //Revisamos si la variable global server tiene un metodo request de tipo post activo tras darle clic en enviar
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Checamos si el usuario esta vacio
        if(empty(trim($_POST["email"]))){
            $username_err = "Favor de ingresar su correo.";
        } else{
            $email = trim($_POST["email"]);
        }

    
        // Checamos si se ingreso un rfc
        if(empty(trim($_POST["rfc"]))){
            $password_err = "Favor de ingresar su rfc.";
        } else{
            $rfc = trim($_POST["rfc"]);
        }

        //Caso en el que se ingresaron ambos
        if(empty($username_err) && empty($password_err)){
            
            //Creamos un sql statement encargado de obtener el usuario con el correo ingresado
            $sql = "SELECT pkUsuarios, nombre, correo, rfc FROM usuarios WHERE correo = ?";
            //Preparamos el statement para recibir variables
            $stmt = $conexion->prepare($sql);
            //Agregamos al statement una var de tipo string correspondiente al correo
            $stmt->bind_param("s",$email);
            //Ejecutamos la query y obtenemos el resultado
            $stmt->execute();
            $usuario = $stmt->get_result();

            $resultado = array();

            //Iteramos cada una de las filas del resultado haciendole fetch_assoc() a cada una
            while($row = $usuario -> fetch_assoc()){
                //Iteramos la fila basandonos en su par nombre columna, valor columna
                foreach($row as $cname => $cvalue){
                    //Guardamos los valores siendo el index el nombre de la columna y el valor el valor de la columna
                    $resultado[$cname] = $cvalue;
                }
            }

            //Si no obtuvimos resultados lo indicamos
            if(empty($resultado)){
                $correo_erroneo = 'Correo ingresado no existe en la base de datos';
            }else{
                //rfc a upper case
                $rfc = strtoupper($rfc);
                //Comparamos el rfc del correo con el rfc ingresado
                if($resultado["rfc"] == $rfc){

                    //Buscamos obtener el perfil del usuario ingresado con otra query
                    $sql = "SELECT fkPerfiles FROM perfilesUsuarios WHERE fkUsuarios = ?";
                    //Preparamos el statement y le agregamos la variable de la llave de usuario
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("s",$resultado["pkUsuarios"]);
                    //Ejectuamos y obtenemos el resultado
                    $res=$stmt->execute();
                    $perfil = $stmt->get_result();

                    //Obtenemos las filas correspondientes a cada registro
                    $num_rows = 0;
                    while($row = $perfil -> fetch_assoc()){
                        //Iteramos las filas como tupla cname,cvalue
                        foreach($row as $cname => $cvalue){
                            //Guardamos en arreglo
                            $resultado[$cname] = $cvalue;
                            $num_rows++;
                        }
                    }
                        
                    //Creamos las variables de sesión donde indicamos que el usuario ya está loggeado y los datos del usuario
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $resultado['pkUsuarios'];
                    $_SESSION["nombreUsuario"] = $resultado['nombre'];                            
                    $_SESSION["correo"] = $email;
                    
                    

                    //Revisamos si el usuario tiene mas de un perfil en el programa y lo redireccionamos a registro
                    if($num_rows >= 2){
                        $_SESSION["perfil"]=3;
                        
                        header("location: views/menuprincipal.php");
                    } //Checamos si es medico
                    else if($resultado["fkPerfiles"] == 1){
                        $_SESSION["perfil"] = 1;
                        header("location: views/menuprincipal.php");
                        echo $num_rows++;
                    }  //Checamos si es supervisor
                    else if($resultado["fkPerfiles"] == 2){
                        $_SESSION["perfil"] = 2;
                        header("location: views/reportes.php");
                    }

                }
                else{
                    $rfc_erroneo = 'El rfc ingresado es erróneo';
                }
            }

        }
    } 

} catch(Exception $err) {
    $database_exception = $err;
    echo $err;
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Proyecto | FI</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="gray-bg">

    <div>
        <?php
            //Si existe el error de correo
            if(!empty($correo_erroneo)){
                //Generamos un alert de JS diciendo que el correo es erroneo
                echo '<script>alert("'.$correo_erroneo.'")</script>'; 
                $correo_erroneo='';
            }else if(!empty($rfc_erroneo)){
                //Generamos un alert de JS diciendo que el rfc es erroneo
                echo '<script>alert("'.$rfc_erroneo.'")</script>'; 
                $rfc_erroneo='';
            }else if(!empty($database_exception)){
                //Generamos un alert de JS diciendo que fallo la base de datos
                echo '<script>alert("'.$database_exception.'")</script>'; 
                $database_exception='';
            }
        ?>
    </div>

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">Covid+</h1>
            </div>
            <h3>Bienvenido</h3>
            <p>Sistema de registro de pacientes con sospecha de COVID-19.
                
            </p>
            <form class="m-t" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Usuario" required="" name="email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="RFC" required="" name="rfc">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Entrar</button>

                <a href="https://www54.sat.gob.mx/curp/Consult"><normal>¿No recuerdas tu RFC?</normal></a>
            </form>
            <p class="m-t"> <small>ESERDI &copy; 2021</small> </p>
        </div>
    </div>



