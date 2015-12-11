<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/estilosUsuarios.css" type="text/css" />
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
                <h1 class="tl_seccion">Recuperar contrase&ntilde;a de usuario</h1>
                <div class="alert alert-info">
                    <strong>Importante!</strong> Ingrese su USUARIO o CORREO ELECTR&Oacute;NICO, para buscar su identidad.
                </div>
                <div class="form">

                    <form id="recuperarfrm" class="form-horizontal" method="post" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/recuperar">
                        <div class="form-group">
                            <!--<label class = 'col-sm-2 control-label'>Usuario</label>
                            <div class="col-sm-4">
                                    <input type="text" class="form-control" name="usuario" id="usuario">
                            </div>-->
                            <label class = 'col-sm-2 control-label'>Correo Electr&oacute;nico</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="Usuarios_correo" id="Usuarios_correo">
                                <div id="errorEmail" style="display:none;color: red;position: relative;top: 0px;left: 2px;font-size:11px">Complete al menos un campo para buscar su cuenta.</div>
                            </div>
                        </div>
                        <?php if (Yii::app()->user->hasFlash('error')) { ?>
                            <div class="infos">
                                <?php echo Yii::app()->user->getFlash('error'); ?>
                            </div>
                        <?php } ?>

                        <div class="g-recaptcha" data-sitekey="6LdpfAYTAAAAAH5QImM0Uzy3Hn1uyF6EAWMbWb89"></div>

                        <div class="row buttons">
                            <input type="submit"  class='btn btn-danger' value="Enviar">
                        </div>
                    </form>
                </div><!-- form -->
            <?php endif; ?>
        </div>
    </div>
</div>
<script>

    var eee = 1;
    $(function () {


        $('#recuperarfrm').submit(function () {
            //verificaNick($("#Usuarios_usuario").val());
            //if ($("#Usuarios_correo").val() != "" && validateEmail($("#Usuarios_correo").val())) {
            if ($("#Usuarios_correo").val() != "") {
                $("#errorEmail").hide();
                eee = 0;
            } else {
                $("#errorEmail").show();
                eee = 1;
            }
            if (eee == 0) {
                return true;

            } else {
                return false;
            }
            alert('Ingrese su usuario o correo');
            return false;
        });
    });


    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!emailReg.test($email)) {
            return false;
        } else {
            return true;
        }
    }
    $("#Usuarios_correo").change(function () {
        if (validateEmail($("#Usuarios_correo").val())) {
            $("#errorEmail").hide();
            eee = 0;
        } else {
            $("#errorEmail").show();
            eee = 1;
        }
    });

    function CedulaValida(cedula) {

        //Si no tiene el gui?n, se lo pone para la validaci?n
        if (cedula.match(/\d{10}/)) {
            cedula = cedula.substr(0, 9) + "-" + cedula.substr(9);
        }

        //Valida que la c?dula sea de la forma ddddddddd-d
        if (!cedula.match(/^\d{9}-\d{1}$/))
            return false;

        //Valida que el # formado por los dos primeros d?gitos est? entre 1 y 24
        var dosPrimerosDigitos = parseInt(cedula.substr(0, 2), 10);
        if (dosPrimerosDigitos < 1 || dosPrimerosDigitos > 24)
            return false;
        //Valida que el valor acumulado entre los primeros 9 n?meros coincida con el ?ltimo
        var acumulado = 0, digito, aux;
        for (var i = 1; i <= 9; i++) {
            digito = parseInt(cedula.charAt(i - 1));
            if (i % 2 == 0) { //si est? en una posici?n par
                acumulado += digito;
            } else { //si est? en una posici?n impar
                aux = 2 * digito;
                if (aux > 9)
                    aux -= 9;
                acumulado += aux;
            }
        }
        acumulado = 10 - (acumulado % 10);
        if (acumulado == 10)
            acumulado = 0;
        var ultimoDigito = parseInt(cedula.charAt(10));
        if (ultimoDigito != acumulado)
            return false;
        //				alert('asd');

        //La c?dula es v?lida
        return true;
        //		alert('bien');
    }
</script>