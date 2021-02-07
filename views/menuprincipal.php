<!DOCTYPE html>
<html>

<?php
    include ("head.php");
?>

<body>

    <div id="wrapper">

    <!-- AQUI VA EL NAV -->

        <?php
            include ("navigation.php");
        ?>
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <a href="../index.php">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
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

                            <form id="form" action="#" class="wizard-big">
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
                                                    <input class="form-check-input" type="radio" name="genero" id="genmujer">
                                                    <label class="form-check-label" for="genmujer">
                                                    Mujer
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="genero" id="genhombre">
                                                    <label class="form-check-label" for="genhombre">
                                                    Hombre
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="genero" id="genotro">
                                                    <label class="form-check-label" for="genotro">
                                                    Otro
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Estado Civil</label>
                                                <select class="form-control" id="exampleFormControlSelect1">
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

                                </fieldset>
                                <h1>Síntomas</h1>
                                <fieldset>
                                    <h2>Síntomas que presenta el paciente</h2>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta fiebre?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input radium-b" type="radio" name="fiebre" id="fiebresi" value="yes">
                                                    <label class="form-check-label"  for="fiebresi">
                                                    Sí
                                                    </label>
                                                        <div class="place">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="fiebreC" id="fiebremenos">
                                                                <label class="form-check-label" for="fiebremenos">
                                                                38°
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="fiebreC" id="fiebremas">
                                                                <label class="form-check-label" for="fiebremas">
                                                                39°
                                                                </label>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="fiebre" id="fiebreno">
                                                    <label class="form-check-label" for="fiebreno">
                                                    No
                                                    </label>
                                            </div>
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta dolor de cabeza?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="cabeza" id="cabezasi">
                                                    <label class="form-check-label" for="cabezasi">
                                                    Sí
                                                    </label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="fiebreC" id="fiebremenos">
                                                            <label class="form-check-label" for="fiebremenos">
                                                             Moderado
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="fiebreC" id="fiebremas">
                                                            <label class="form-check-label" for="fiebremas">
                                                             Intenso
                                                            </label>
                                                        </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="cabeza" id="cabezano">
                                                    <label class="form-check-label" for="cabezano">
                                                    No
                                                    </label>
                                            </div>
                                            <div class="form-group">
                                                <h3>El paciente ¿Presenta dificultad para respirar?</h3>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="respirar" id="respirarsi">
                                                    <label class="form-check-label" for="respirarsi">
                                                    Sí
                                                    </label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="respirar" id="respirarmenos">
                                                            <label class="form-check-label" for="respirarmenos">
                                                             Moderado
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="respirar" id="respirarmas">
                                                            <label class="form-check-label" for="respirarmas">
                                                             Intenso
                                                            </label>
                                                        </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="respirar" id="respirarno">
                                                    <label class="form-check-label" for="respirarno">
                                                    No
                                                    </label>
                                            </div>
                                        </div>
                                </fieldset>

                                <h1>Warning</h1>
                                <fieldset>
                                    <div class="text-center" style="margin-top: 120px">
                                        <h2>You did it Man :-)</h2>
                                    </div>
                                </fieldset>

                                <h1>Finish</h1>
                                <fieldset>
                                    <h2>Terms and Conditions</h2>
                                    <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">I agree with the Terms and Conditions.</label>
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
