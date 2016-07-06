<?php
$cargo = Yii::app()->user->getState('usuario');
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$area_id = (int) Yii::app()->user->getState('area_id');
//echo 'cargo id: '.$cargo_id;
// vanessa17_ldu@hotmail.com, nutri_mas2@hotmail.com
//echo 'CARGO:  '.(int) Yii::app()->user->getState('cargo_id');
//echo 'AREA:  '.(int) Yii::app()->user->getState('area_id');

$accesosUser = Permiso::model()->findAll(array('condition' => "cargoId=:match", 'params' => array(':match' => (int) Yii::app()->user->getState('cargo_id'))));
//die('count '.count($accesosUser));
?>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<?php
$rol = Yii::app()->user->getState('roles');
$rol = Yii::app()->user->getState('roles');
?>

<section>
    <div>
        <?php if (Yii::app()->user->getState('area_id') == 17): ?>
            <img class="img_rs" id="imgmenu" src="<?php echo Yii::app()->request->baseUrl; ?>/images/stage_ventas.jpg">
        <?php else: ?>
            <img class="img_rs" id="imgmenu" src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_14.jpg">
        <?php endif; ?>
    </div>
    <ul class="menu">

        <?php
        if (!empty($accesosUser)) {
            foreach ($accesosUser as $a) {
                //echo 'controlador: '.$a->accesoSistema->controlador.'<br />';
                //echo 'accion: '.$a->accesoSistema->accion.'<br />';
                ?>
                <?php if (($a->accesoSistema->controlador) == 'casos' && ($a->accesoSistema->accion) == 'create' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('casos/create'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_21.jpg" width="46" height="56"></div>
                                <div class="txt_menu">Registro de llamada</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'casos' && ($a->accesoSistema->accion) == 'seguimiento' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('casos/seguimiento'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_23.jpg" width="46" height="56"></div>
                                <div class="txt_menu">Seguimiento</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'casos' && ($a->accesoSistema->accion) == 'reportes' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_25.jpg" width="58" height="56"></div>
                                <div class="txt_menu">Reportes</div></a> 
                        </div>

                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvQir' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/qir.png" width="46" height="56"></div>
                                <div class="txt_menu">QIR</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvboletinpostventa' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/boletines.png" width="46" height="56"></div>
                                <div class="txt_menu">Boletines Postventa</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvvinMotor' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/vin.png" width="46" height="56"></div>
                                <div class="txt_menu">Vin Motor</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvmodelosposventa' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvmodelosposventa/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/modelospv.png" width="46" height="56"></div>
                                <div class="txt_menu">Modelos</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvcodigoCausal' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvcodigoCausal/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/causal.png" width="46" height="56"></div>
                                <div class="txt_menu">C&oacute;digo Causal</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'pvcodigoNaturaleza' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('pvcodigoNaturaleza/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/naturaleza.png" width="46" height="56"></div>
                                <div class="txt_menu">C&oacute;digo Naturaleza</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'ucargo' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/roles.png" width="46" height="56"></div>
                                <div class="txt_menu">Cargos y Perfiles</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'uarea' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('uarea/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                                <div class="txt_menu">&Aacute;reas</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'uaccesosistema' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('uaccesosistema/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/access.png" width="46" height="56"></div>
                                <div class="txt_menu">Accesos al Sistema</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php if (($a->accesoSistema->controlador) == 'uusuarios' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">Usuarios KIA</div></a>
                        </div>
                    </li>
                <?php } // PERFIL ASESOR DE VENTAS --------------------------------------------- ?>
                <?php if($tipo == 'ventas'){ ?>
                    <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimiento' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                        <?php if ($cargo_id == 71 || $cargo_id == 67 || $cargo_id == 46) {?>
                            <li class="wrapper">
                                <div class="forma">
                                    <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                        <div class="txt_menu">RGD Asesor de Ventas</div></a>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14){ ?>
                            <li class="wrapper">
                                <div class="forma">
                                    <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                        <div class="txt_menu">RGD SGC</div></a>
                                </div>
                            </li>
                        <?php } ?>
                        
                    <?php }?>
                    <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimiento' && $opcion == md5(($a->accesoSistema->modulo_id)) && ($cargo_id == 70 || $cargo_id != 46)) { ?>
                        <?php if ($cargo_id == 69 || $cargo_id == 46) { ?>
                            <li class="wrapper">
                                <div class="forma">
                                    <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                        <div class="txt_menu">RGD Nuevos</div></a>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($cargo_id == 70) { ?>
                            <li class="wrapper">
                                <div class="forma">
                                    <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                        <div class="txt_menu">RGD Jefe Sucursal</div></a>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimiento' && $opcion == md5(($a->accesoSistema->modulo_id)) && ($cargo_id == 46) && $tipo == 'ventas') { ?>

                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">RGD Jefe Sucursal</div></a>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if($cargo_id == 77){ // ASESOR USADOS ?>
                    <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimientoUsados') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoUsados'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">RGD Asesor Usados</div></a>
                        </div>
                    </li>
                    <?php } ?>
                    <?php } ?>
                    <?php if($cargo_id == 76){ // JEFE USADOS ?>
                    <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimientoUsados') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoUsados'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">RGD Jefe Usados</div></a>
                        </div>
                    </li>
                    <?php } ?>
                    <?php } ?>
                    <?php if($cargo_id == 6900 || $area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14){ // JEFE USADOS ?>
                        <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimientoUsados') { ?>
                        <li class="wrapper">
                            <div class="forma">
                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoUsados'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                    <div class="txt_menu">RGD Usados</div></a>
                            </div>
                        </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if($cargo_id == 75){ // ASESOR EXONERADOS ?>    
                        <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimientoexonerados' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                        <li class="wrapper">
                            <div class="forma">
                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoexonerados'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                    <div class="txt_menu">RGD Asesor Exonerados</div></a>
                            </div>
                        </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if($cargo_id == 6900){ // JEFE COMERCIAL ?>    
                        <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimientoexonerados' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                        <li class="wrapper">
                            <div class="forma">
                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoexonerados'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                    <div class="txt_menu">RGD Exonerados</div></a>
                            </div>
                        </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if($cargo_id == 72){ // Jefe Bdc Y Exonerados ?>    
                        <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimientoexonerados' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                        <li class="wrapper">
                            <div class="forma">
                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoexonerados'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                    <div class="txt_menu">RGD Exonerados</div></a>
                            </div>
                        </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14){ ?>   
                        <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimientoexonerados' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                        <li class="wrapper">
                            <div class="forma">
                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoexonerados'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                    <div class="txt_menu">RGD Exonerados</div></a>
                            </div>
                        </li>
                        <?php } ?>
                    <?php } ?>    
                        
                    <?php if (($a->accesoSistema->controlador) == 'gestionInformacion' && ($a->accesoSistema->accion) == 'seguimientobdc' && $tipo == 'ventas') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientobdc'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">RGD BDC</div></a>
                        </div>
                    </li>
                    <?php } ?>
                <?php } // end tipo ventas ?> 
                
                
                <?php if (($a->accesoSistema->controlador) == 'gestionSolicitudCredito' && ($a->accesoSistema->accion) == 'status' && $opcion == md5(($a->accesoSistema->modulo_id)) && $cargo_id != 46) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">RGD Asesor de Crédito</div></a>
                        </div>
                    </li>
                <?php } ?>
                
                <?php if (($a->accesoSistema->controlador) == 'cquestionario' && ($a->accesoSistema->accion) == 'reportes' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                        <li class="wrapper">
                                <div class="forma">
                                        <a href="<?php echo Yii::app()->createUrl('cquestionario/reportes'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                                                <div class="txt_menu">Reportes de Encuestas</div></a>
                                </div>
                        </li>
                <?php } ?>    
                <?php if (($a->accesoSistema->controlador) == 'ccampana' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('ccampana/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                                <div class="txt_menu">Campa&ntilde;as</div></a>
                        </div>
                    </li>


                <?php } ?>
                <?php if (($a->accesoSistema->controlador) == 'cencuestadoscquestionario' && ($a->accesoSistema->accion) == 'admin' && $opcion == md5(($a->accesoSistema->modulo_id))) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/admin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                                <div class="txt_menu">Encuestar</div></a>
                        </div>
                    </li>
                <?php } // ?>
                <?php
                if (($a->accesoSistema->controlador) == 'cencuestadoscquestionario' && ($a->accesoSistema->accion) == 'atenciondetalle' && $opcion == md5(($a->accesoSistema->modulo_id))) {
                    $verifica = Cotizacionesnodeseadas::model()->count(array("condition" => 'realizado ="0" and usuario_id=' . Yii::app()->user->id));
                    $stylo = '';
                    $stylol = '';
                    $num = '';
                    if (!empty($verifica) && $verifica >= 0) {
                        $stylo = 'style="border: green solid 3px;"';
                        $stylol = 'style="color: green;"';
                        $num = '(' . $verifica . ')';
                    } else {
                        $stylo = '';
                        $stylol = '';
                        $num = '';
                    }
                    ?>
                    <li class="wrapper" <?php echo $stylo ?>>
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/atenciondetalle'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/access.png" width="46" height="56"></div>
                                <div <?php echo $stylol ?> class="txt_menu">Cotizaciones Web <?php echo $num ?></div></a>
                        </div>
                    </li>
                <?php } // ?>
                <?php
                if (($a->accesoSistema->controlador) == 'cencuestadoscquestionario' && ($a->accesoSistema->accion) == 'nocompradoresadmin' && $opcion == md5(($a->accesoSistema->modulo_id))) {

                    $verifica = Nocompradores::model()->count(array("condition" => 'preguntauno is null and usuario_id=' . Yii::app()->user->id));
                    $stylo = '';
                    $stylol = '';
                    $num = '';
                    if (!empty($verifica) && $verifica >= 0) {
                        $stylo = 'style="border: green solid 3px;"';
                        $stylol = 'style="color: green;"';
                        $num = '(' . $verifica . ')';
                    } else {
                        $stylo = '';
                        $stylol = '';
                        $num = '';
                    }
                    ?>
                    <li class="wrapper" <?php echo $stylo ?>>
                        <div class="forma">
                            <a  href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/nocompradoresadmin'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_21.jpg" width="46" height="56"></div>
                                <div class="txt_menu" <?php echo $stylol ?>>No compradores <?php echo $num ?></div></a>
                        </div>
                    </li>
                <?php } //   ?>

            <?php }
            ?>
            <?php } ?>

    </ul>
</section>