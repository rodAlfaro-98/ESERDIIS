<?php

session_start();

require ("./libs/Database.php");

$email = $rfc = '';
$username_err = $password_err = '';
$correo_erroneo = $rfc_erroneo = $database_exception = '';

$_SESSION["correo_erroneo"] = null;

try{

    $instancia = Database::getInstance();
    $conexion = $instancia->getConnection();

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Check if username is empty
        if(empty(trim($_POST["email"]))){
            $username_err = "Favor de ingresar su correo.";
        } else{
            $email = trim($_POST["email"]);
        }

    
        // Check if password is empty
        if(empty(trim($_POST["rfc"]))){
            $password_err = "Favor de ingresar su rfc.";
        } else{
            $rfc = trim($_POST["rfc"]);
        }

        if(empty($username_err) && empty($password_err)){
            // Prepare a select statement
            $sql = "SELECT pkUsuarios, nombre, correo, rfc FROM usuarios WHERE correo = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $usuario = $stmt->get_result();

            $resultado = array();

            while($row = $usuario -> fetch_assoc()){
                foreach($row as $cname => $cvalue){
                    //print "$cname: $cvalue\t";
                    $resultado[$cname] = $cvalue;
                }
            }

            if(empty($resultado)){
                $correo_erroneo = 'Correo ingresado no existe en la base de datos';
                $_SESSION["correo_erroneo"] = $correo_erroneo;
            }else{
                if($resultado["rfc"] == $rfc){

                    $sql = "SELECT fkPerfiles FROM perfilesUsuarios WHERE fkUsuarios = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("s",$resultado["pkUsuarios"]);
                    $res=$stmt->execute();
                    $perfil = $stmt->get_result();


                    $num_rows = 0;
                    while($row = $perfil -> fetch_assoc()){
                        foreach($row as $cname => $cvalue){
                            $resultado[$cname] = $cvalue;
                            $num_rows++;
                        }
                    }
                        
                    // Store data in session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $resultado['pkUsuarios'];
                    $_SESSION["nombreUsuario"] = $resultado['nombre'];                            
                    $_SESSION["correo"] = $email;
                    
                    // Redirect user to welcome page

                    
                    if($num_rows == 2){
                        $_SESSION["perfil"]="ambos";
                        echo $num_rows++;
                        header("location: views/menuprincipal.php");
                    }
                    else if($resultado["fkPerfiles"] == 1){
                        $_SESSION["perfil"] = 1;
                        header("location: views/menuprincipal.php");
                        echo $num_rows++;
                    } else if($resultado["fkPerfiles"] >= 2){
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
            if(!empty($correo_erroneo)){
                echo "<h4>$correo_erroneo</h4>";
                $correo_erroneo='';
            }else if(!empty($rfc_erroneo)){
                echo "<h4>$rfc_erroneo</h4>";
                $rfc_erroneo='';
            }else if(!empty($database_exception)){
                echo "<h4>$database_exception</h4>";
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
                <!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
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



