<?php
$accesosUser = Permiso::model()->findAll(array('condition' => "cargoId=:match", 'params' => array(':match' => (int) Yii::app()->user->getState('cargo_id'))));
?>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<?php
$rol = Yii::app()->user->getState('roles');
$rol = Yii::app()->user->getState('roles');
?>

<section>
    <div><img class="img_rs" src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_14.jpg"></div>
    <ul class="menu">
        <?php
        if (!empty($accesosUser)) {
            foreach ($accesosUser as $a) {
                ?>

                <?php if (($a->accesoSistema->controlador) == 'casos' && ($a->accesoSistema->accion) == 'create') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('casos/create'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_21.jpg" width="46" height="56"></div>
                                <div class="txt_menu">Registro de llamada</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'casos' && ($a->accesoSistema->accion) == 'seguimiento') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('casos/seguimiento'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_23.jpg" width="46" height="56"></div>
                                <div class="txt_menu">Seguimiento</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'casos' && ($a->accesoSistema->accion) == 'reportes') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_25.jpg" width="58" height="56"></div>
                                <div class="txt_menu">Reportes</div></a> 
                        </div>

                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvQir' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/qir.png" width="46" height="56"></div>
                                <div class="txt_menu">QIR</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvboletinpostventa' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/boletines.png" width="46" height="56"></div>
                                <div class="txt_menu">Boletines Posventa</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvvinMotor' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/vin.png" width="46" height="56"></div>
                                <div class="txt_menu">Vin Motor</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvmodelosposventa' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvmodelosposventa/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/modelospv.png" width="46" height="56"></div>
                                <div class="txt_menu">Modelos</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvcodigoCausal' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvcodigoCausal/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/causal.png" width="46" height="56"></div>
                                <div class="txt_menu">C&oacute;digo Causal</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvcodigoNaturaleza' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvcodigoNaturaleza/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/naturaleza.png" width="46" height="56"></div>
                                <div class="txt_menu">C&oacute;digo Naturaleza</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'ucargo' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/roles.png" width="46" height="56"></div>
                                <div class="txt_menu">Cargos y Perfiles</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'uarea' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('uarea/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                                <div class="txt_menu">&Aacute;reas</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'uaccesosistema' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('uaccesosistema/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/access.png" width="46" height="56"></div>
                                <div class="txt_menu">Accesos al Sistema</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'uusuarios' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">Usuarios KIA</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimiento') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">RGD Asesor de Ventas</div></a>
                        </div>
                    </li>
        <?php } ?>
                    <?php if (($a->accesoSistema->controlador) == 'gestionDiaria' && ($a->accesoSistema->accion) == 'agendamiento') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoUsados'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">RGD Asesor de Ventas</div></a>
                        </div>
                    </li>
        <?php } ?>
                    
                <?php if (($a->accesoSistema->controlador) == 'gestionSolicitudCredito' && ($a->accesoSistema->accion) == 'status') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">RGD Asesor de Crédito</div></a>
                        </div>
                    </li>
        <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'ccampana' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('ccampana/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                                <div class="txt_menu">Campa&ntilde;as</div></a>
                        </div>
                    </li>


        <?php } ?>
                <?php if (($a->accesoSistema->controlador) == 'cencuestadoscquestionario' && ($a->accesoSistema->accion) == 'admin') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                                <div class="txt_menu">Encuestar</div></a>
                        </div>
                    </li>


        <?php } // ?>

                <?php }
            ?>
            <li class="wrapper">
                <div class="forma">
                    <a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                        <div class="txt_menu">Directorio</div></a>
                </div>
            </li><?php
    }
        ?>

    </ul>
</section>
