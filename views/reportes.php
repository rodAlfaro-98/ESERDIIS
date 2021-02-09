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
                
                <table>
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input radium-b" type="radio" name="fiebre" id="fiebresi" value="1" onclick="desplegar(this,'place')" required>
                                <label class="form-check-label"  for="fiebresi">
                                Genero
                                </label>
                            <div class:"row">    
                                <div id="place" style="display: none;">
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
                                                        menor a 18 años
                                                    </label>
                                                </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="edad" id="edad2" value="1">
                                                    <label class="form-check-label" for="edad2">
                                                        adultos
                                                    </label>
                                                </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="edad" id="edad3" value="2">
                                                    <label class="form-check-label" for="edad3">
                                                        tercera edad
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
                                <input class="form-check-input radium-b" type="radio" name="fiebre" id="fiebresi" value="1" onclick="desplegar(this,'place1')" required>
                                <label class="form-check-label"  for="fiebresi">
                                Consulta
                                </label>
                            <div class:"row">    
                                <div id="place1" style="display: none;">
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
                    </tr>
                </table>
            
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
            <div class="float-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2018
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
