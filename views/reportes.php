<?php

    require ("../libs/Database.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $tipo = '';
        $genero = '';
        $menorEdad = '2003-01-01';
        $comparacionMenor = strtotime($menorEdad);
        $terceraEdad = '1956-01-01';
        $conparacionTercera = strtotime($mayorEdad);
        $consulta = '';
        $tipo_consulta = '';

        $matriz = array();

        if(isset($_POST["consulttype"])){
            $tipo = trim($_POST["consulttype"]);
        }
        try{
            $instancia = Database::getInstance();
            $conexion = $instancia->getConnection();
            if(tipo == "1"){
                $tipo_consulta = "demo";
                $genero = $_POST["genero"];
                $edad = $_POST["edad"];

            }else if(tipo == "0"){
                $tipo_consulta = "consulta";
                $consulta = $_POST["consulta"];

                $sql = "SELECT * FROM usuarios AS u JOIN formulario AS f on u.pkUsuarios = f.fkUsuarios where f.tipo_consulta = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("s",$consulta);
                $stmt->execute();
                $usuarios = $stmt->get_result();

                while($row = $usuario -> fetch_assoc()){
                    $resultado = array();
                    foreach($row as $cname => $cvalue){
                        //print "$cname: $cvalue\t";
                        $resultado[$cname] = $cvalue;
                    }
                    array_push($matriz,$resultado);
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
                
                <h2>Registro</h2>
                </div>
                
                <form class="m-t" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <table width="100%" style="margin: 0px;">
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input radium-b" type="radio" name="consulttype" value="1" onclick="desplegarreporte(this)" required>
                                    <label class="form-check-label"  for="fiebresi">
                                    Genero
                                    </label>
                                <div class:"row">    
                                    <div id="opt" style="display: none;">
                                        <table>
                                            <tr>
                                                <th>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="genero" id="hombre" value="H">
                                                        <label class="form-check-label" for="hombre">
                                                            Hombre
                                                        </label>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="genero" id="mujer" value="M">
                                                        <label class="form-check-label" for="mujer">
                                                            Mujer
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="genero" id="otro" value="O">
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
                                                        <input class="form-check-input" type="radio" name="edad" id="edad1" value="0">
                                                        <label class="form-check-label" for="edad1">
                                                            Menor a 18 años
                                                        </label>
                                                    </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="edad" id="edad2" value="1">
                                                        <label class="form-check-label" for="edad2">
                                                            Adultos
                                                        </label>
                                                    </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="edad" id="edad3" value="2">
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
                                    Consulta
                                    </label>
                                <div class:"row">   
                                    <div id="opt1" style="display: none;">
                                        <table>
                                            <tr>
                                                <th>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="consulta" id="urgencias" value="urgencias">
                                                        <label class="form-check-label" for="urgencias">
                                                            Urgencias
                                                        </label>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="consulta" id="externa" value="consulta externa">
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
                            if(!empty($matriz))
                            {
                                foreach($matriz as $fila){
                                    echo "<tr>";
                                    foreach($fila as $value){
                                        echo "<td>".$value."</td>";
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
