<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<?php
$accesosUser = Permiso::model()->findAll(array('condition' => "cargoId=:match", 'params' => array(':match' => (int) Yii::app()->user->getState('cargo_id'))));

$case = ''; // para busqueda por defecto
//$getParams = '';    // para busqueda por parametros de GET
//if (isset($getParams)) {
//    echo '<pre>';
//    print_r($getParams);
//    echo '</pre>';
//}
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';
//echo 'id call center: '.Yii::app()->user->getId().'<br>';
//echo 'rol: '.Yii::app()->user->getState('roles').'<br>';
$rol = Yii::app()->user->getState('roles');
?>
<script type="text/javascript">
    var abrir = 0;
    $(function () {
        $("#keywords").tablesorter();
    });
    function verN(num) {
        if (num > 0) {
            if (abrir == 0) {
                $("#lNotificaciones").show();
                abrir = 1;
            } else {
                $("#lNotificaciones").hide();
                abrir = 0;

            }
        }

    }
</script>    
<style>
    .form-search{
        padding: 0;
    }
</style>

<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Encuesta a No Compradores</h1>
    </div>
    <?php
    if (!empty($model)) {
        ?>
        <div class="col-md-12">
            <p class="intro_encuestas">
                Buenos d&iacute;as me  comunica por favor con el Sr./a <b><?php echo $model->nombre . ' ' . $model->apellido ?></b>, mucho gusto Sr/a <b><?php echo $model->nombre . ' ' . $model->apellido ?></b>., le saludo de KIA MOTORS, mi nombre es <b><?php echo Yii::app()->user->getState('first_name'); ?></b>,  el motivo de mi llamada es realizar una peque&ntilde;a encuesta por la visita que tuvo usted en nuestros concesionarios Kia.
            </p>
            <div class=" row contenido_no_compradores">
                <div class="col-md-12">
                    <h3>Encuesta a completar</h3>
                    <hr>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'nocompradores-form',
                    ));
                    ?>
                    <div class="">
                        <p class="pregunta_no_compradorespregunta_no_compradores">
                            1.- Tenemos registrado que usted cotizó uno de nuestros vehículos KIA, queremos saber ¿porque no se concretó la compra del mismo? <span>Responde el cliente y escogemos la opción</span>
                        </p>

                        <ul class="opciones_no_compradores">
                            <li>
                                <div class="radio"><input  type="radio" name="datos[motivo]" onclick="nover('subpreguntas_a');
                                        ver('div_experiencia', 'subpreguntas_a')" id="motivo_asesor" value="Atencion del asesor">Atención Seguimiento / Asesor</label></div>
                                <div id="div_experiencia" class="subpreguntas_a" style="display:none">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿Tuvo una mala experiencia con nuestro asesor?</p>
                                    <textarea name="datos[respuesta_experiencia]" id="txt_experiencia" class="form-control sb" placeholder="Ingrese la experiencia aquí"></textarea>
                                </div>
                            </li>
                            <li><div class="radio"><input onclick="nover('subpreguntas_a');
                                    verpregunta('div_compro')" type="radio" name="datos[motivo]" id="motivo_precio" value="Precio">Precio</label></div></li>
                            <li><div class="radio"><input onclick="nover('subpreguntas_a');
                                    verpregunta('div_compro')" type="radio" name="datos[motivo]" id="motivo_credito" value="Credito Rechazado">Credito Rechazado</label></div></li>
                            <li><div class="radio"><input onclick="nover('subpreguntas_a');
                                    verpregunta('div_compro')" type="radio" name="datos[motivo]" id="motivo_dinero" value="Falta de Dinero">Falta de Dinero</label></div></li>
                            <li>
                                <div class="radio"><input onclick="nover('subpreguntas_a');
                                        ver('div_caracteristicas', 'subpreguntas_a')" type="radio" name="datos[motivo]" id="motivo_caracteristicas" value="Características del vehículo o color no disponible">Características del vehículo o color no disponible</label></div>
                                <div id="div_caracteristicas" class="subpreguntas_a" style="display:none">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿Qué características no fue de su agrado?</p>
                                    <textarea name="datos[respuesta_caracteristicas]" id="txt_caracteristicas" class="form-control sb" placeholder="Ingrese las características aquí"></textarea>
                                </div>
                            </li>
                            <li><div class="radio"><input onclick="nover('subpreguntas_a')" type="radio" name="datos[motivo]" id="motivo_desicion" value="Aun no toma la decision">Aún no toma la decisión </label></div></li>
                            <li><div class="radio"><input onclick="nover('subpreguntas_a');
                                    verpregunta('div_compro')" type="radio" name="datos[motivo]" id="motivo_modelo" value="Modelo no disponible">Modelo no disponible</label></div></li>
                            <li>
                                <div class="radio"><input onclick="nover('subpreguntas_a');
                                        ver('div_otro', 'subpreguntas_a')" type="radio" name="datos[motivo]" id="motivo_otros" value="Otros">Otros</label></div>
                                <div id="div_otro" class="subpreguntas_a" style="display:none">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿Cual fue el motivo?</p>
                                    <textarea name="datos[otro]" id="txt_otro" class="form-control sb" placeholder="Ingrese el motivo aquí"></textarea>  
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="form-group" style="display:none" id="div_compro">
                        <p class="pregunta_no_compradorespregunta_no_compradores">
                            2.	¿Talvez Ud. compró otro vehículo?
                        </p>
                        <ul class="opciones_no_compradores" >
                            <li><div class="radio"><input onclick="nuevo();"   type="radio" name="datos[compro]" id="compro_si" value="SI">SI</label></div></li>
                            <li><div class="radio"><input onclick="nonuevo();" type="radio" name="datos[compro]" id="compro_no" value="NO">NO</label></div></li>
                        </ul>
                    </div>
                    <div class="form-group" style="display:none" id="div_nuevo">
                        <p class="pregunta_no_compradorespregunta_no_compradores">
                            3.	Nuevo o usado:
                        </p>
                        <ul class="opciones_no_compradores">
                            <li>
                                <div class="radio"><input onclick="razonnuevo('div_si_nuevo')"   type="radio" name="datos[nuevo]" id="nuevo_si" value="Nuevo">Nuevo</label></div>
                                <div id="div_si_nuevo" class="subpreguntas_a" style="display:none">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿Qué marca?</p>
                                    <input type="text" name="datos[txtmarca]" id="txt_marca" class="form-control nu" placeholder="Ingrese la marca aquí">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿Qué modelo?</p>
                                    <input type="text" name="datos[txtmodelo]" id="txt_modelo" class="form-control nu" placeholder="Ingrese el modelo aquí">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿Porque se decidió por este modelo?</p>
                                    <input type="text" name="datos[porquenuevo]" id="txt_porquenuevo" class="form-control nu" placeholder="Ingrese el motivo aquí">
                                </div>
                            </li>
                            <li>
                                <div class="radio"><input onclick="razonnuevo('div_usado')" type="radio" name="datos[nuevo]" id="nuevo_no" value="Usado">Usado</label></div>
                                <div id="div_usado" class="subpreguntas_a" style="display:none">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿Qué marca?</p>
                                    <input type="text" name="datos[txtmarcausado]" id="txt_marcausado" class="form-control nu" placeholder="Ingrese la marca aquí">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿Qué modelo?</p>
                                    <input type="text" name="datos[txtmodelousado]" id="txt_modelousado" class="form-control nu" placeholder="Ingrese el modelo aquí">
                                    <p class="pregunta_no_compradorespregunta_no_compradores">¿En qué lugar compró su vehículo usado?</p>
                                    <input type="text" name="datos[lugarcompra]" id="txt_lugarcompra" class="form-control nu" placeholder="Ingrese el lugar de compra aquí">
                                </div>
                            </li>
                        </ul>
                    </div>
                    <p class="pregunta_no_compradorespregunta_no_compradores">Le agradezco por su amable atención, que tenga un excelente día / tarde / noche. </p>
                    <input type="hidden" name="datos[id]" value="<?php echo $model->id ?>">
                    <input type="submit" value="Enviar" class="btn btn-danger">
        <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    <?php
} else {
    echo '<p>No existen personas para realizar la encuesta de encuesta</p>';
}
?>
    <div class="row">

        <div class="col-md-12 links-tabs links-footer">

            <div class="col-md-2"><p>Tambi&eacute;n puedes ir a:</p></div>
            <?php
            if (!empty($accesosUser)) {
                foreach ($accesosUser as $a) {
                    ?>
                    <?php if (($a->accesoSistema->controlador) == 'pvQir' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="qir-btn"><span class="textoFEnlace">QIR</span></a></div>
                    <?php } ?>

                    <?php if (($a->accesoSistema->controlador) == 'pvboletinpostventa' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>" class="boletines-btn"><span class="textoFEnlace">Boletines</span></a></div>
                    <?php } ?>

                    <?php if (($a->accesoSistema->controlador) == 'pvvinMotor' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>" class="vin-btn"><span class="textoFEnlace">Vin-Motor</span></a></div>
                    <?php } ?>

        <?php if (($a->accesoSistema->controlador) == 'pvmodelosposventa' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvmodelosposventa/admin'); ?>" class="modelospv-btn"><span class="textoFEnlace">Modelos</span></a></div>
                    <?php } ?>

                    <?php if (($a->accesoSistema->controlador) == 'pvcodigoCausal' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoCausal/admin'); ?>" class="causal-btn"><span class="textoFEnlace">C&oacute;digo Causal</span></a></div>
                    <?php } ?>


                    <?php if (($a->accesoSistema->controlador) == 'pvcodigoNaturaleza' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoNaturaleza/admin'); ?>" class="naturaleza-btn"><span class="textoFEnlace">C&oacute;digo Naturaleza</span></a></div>
                    <?php } ?>

                    <?php if (($a->accesoSistema->controlador) == 'uaccesosistema' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uaccesosistema/admin'); ?>" class="accesos-btn"><span class="textoFEnlace">Accesos al Sistema</span></a></div>
                    <?php } ?>

                    <?php if (($a->accesoSistema->controlador) == 'ucargo' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>" class="cargos-btn"><span class="textoFEnlace">Cargos y Perfiles</span></a></div>
                    <?php } ?>

                    <?php if (($a->accesoSistema->controlador) == 'uusuarios' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>" class="usuarios-btn"><span class="textoFEnlace">Usuarios Kia</span></a></div>
                    <?php } ?>

                    <?php if (($a->accesoSistema->controlador) == 'uusuarios' && ($a->accesoSistema->accion) == 'contactos') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="contactos-btn"><span class="textoFEnlace">Cont&aacute;ctos</span></a></div>
                    <?php } ?>
                    <?php if (($a->accesoSistema->controlador) == 'uarea' && ($a->accesoSistema->accion) == 'admin') { ?>	
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uarea/admin'); ?>" class="contactos-btn"><span class="textoFEnlace">&Aacute;reas</span></a></div>
        <?php } ?>

        <?php
    }
}
?>
            <div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>	
        </div>
    </div>
</div>
<script>
    function ver(vl, cl) {
        $("." + cl).hide();
        $("#" + vl).show();
    }
    function nover(vl) {
        $("." + vl).hide();
        $("#div_compro").hide();
        $("#div_nuevo").hide();
        $("#div_usado").hide();
        $("#div_si_nuevo").hide();
        $(".sb").val('');
    }
    function verpregunta(vl) {
        $("#div_compro").hide();
        $("#div_nuevo").hide();
        $("#" + vl).show();
    }
    function nuevo() {
        $("#div_nuevo").show();
    }
    function nonuevo() {
        $("#div_nuevo").hide();
    }
    function razonnuevo(vl) {
        $("#div_usado").hide();
        $("#div_si_nuevo").hide();
        $("#" + vl).show();
    }
</script>
