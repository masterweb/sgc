<?php
$area_id = (int) Yii::app()->user->getState('area_id');
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
//$con = Yii::app()->db;
//if ($cargo_id == 71644) {  // SOLO SE APLICA EL ASIGNAMIENTO A LOS ASESORES DE VENTAS
//    //die('enter cargo ventas');
//    $id_responsable = Yii::app()->user->getId();
//    $dealer_id = $this->getDealerId($id_responsable);
//    //echo 'dealer id: '.$dealer_id;
//    /* @var $this Controller */
////echo 'dealer id: '.Yii::app()->user->getState('dealer_id').'<br>';
//    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
////echo 'fecha actual: '.$fecha_actual;
//    $fecha = "2015/10/28 07:00:00";
////echo 'fecha actual: '.strtotime('now');
////echo '<br />';
////echo 'fecha seguimiento: '.strtotime($fecha);
////echo '<br />';
////$fecha="2015-10-31 11:00:00";
//    $segundos = strtotime('now') - strtotime($fecha);
////echo 'diferencia segundos: '.$segundos;
//    $diferencia_horas = intval($segundos / 60 / 60);
////echo '<br />'."La cantidad de horas entre el ".$fecha." y hoy es <b>".$diferencia_horas."</b>";
//// SACAR FECHAS AGENDAMIENTO DE TABLA GESTION AGENDAMIENTO
//
////    $cr = GestionAgendamiento::model()->findAll(array('condition' => "caducado = 0"));
////    foreach ($cr as $c) {
////        if ($c['agendamiento'] != '') {
////            // update gestion informacion BDC 1
////            $fecha = $c['agendamiento'];
////            //echo '<h3>FECHA AGENDAMIENTO: ' . $fecha . '</h3><br />';
////            $segundos = strtotime('now') - strtotime($fecha);
////            //echo '<h3>FECHA NOW: '.strtotime('now').'</h3><br />';
////            $diferencia_horas = intval($segundos / 60 / 60);
////            //echo "La cantidad de horas para id informacion: " . $c['id_informacion'] . ", entre el " . $fecha . " y hoy es <b>" . $diferencia_horas . "</b><br />";
////            $fuente = $this->getFuenteExonerados($c['id_informacion']);
////            //echo 'FUENTE: ' . $fuente . '<br />';
////            if ($diferencia_horas >= 24 && $fuente != 'usado' && $fuente != 'usadopago' && $fuente != 'exonerados') { // SI LA DIFERENCIA DE HORAS ES MAYOR A 12 Y FUENTE ES VACIO, ES DECIR NO ES USADO NI EXONERADO
////                //ASIGNAR A LOS ASESORES BDC DE ACUERDO A LA ID DEL CONCESIONARIO
////                $responsable = $this->getRandomKey(73, $dealer_id);
////                //echo 'id responsable: '.$responsable.'<br />';
////                if (!empty($responsable)) {
////                    $sql = "UPDATE gestion_informacion SET bdc = 1, responsable = {$responsable}, responsable_origen = {$id_responsable} WHERE id = {$c['id_informacion']}";
////                    //$request = $con->createCommand($sql)->query();
////                    $sql2 = "UPDATE gestion_agendamiento SET caducado = 1 WHERE id_informacion = {$c['id_informacion']}";
////                    //$request = $con->createCommand($sql2)->query();
////                    $sql3 = "UPDATE gestion_diaria SET medio_contacto = 'caduco' WHERE id_informacion = {$c['id_informacion']}";
////                    //$request = $con->createCommand($sql3)->query();
////                }
////            }
////        }
////    }
//}
?>
<?php
$accesosUser = Permiso::model()->findAll(array('condition' => "cargoId=:match", 'params' => array(':match' => (int) Yii::app()->user->getState('cargo_id'))));
$modulos = Cargomodulos::model()->findAll(array("condition" => "cargo_id=:match", 'params' => array(':match' => (int) Yii::app()->user->getState('cargo_id'))));
if (!empty($modulos)) {
    $vectorModulos = array();
    foreach ($modulos as $key) {
        array_push($vectorModulos, $key->modulo_id);
    }

    $criteria = new CDbCriteria();
    $criteria->addInCondition('id', $vectorModulos);
    $result = Modulo::model()->findAll($criteria);
}
$idasesor = Yii::app()->user->getState('concesionario_id');
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
//echo 'ID ASESOR: '.$idasesor;

?>
<section>
    <div>
        <?php //if(Yii::app()->user->getState('area_id') == 17):   ?>
<!--        <img class="img_rs" id="imgmenu" src="<?php echo Yii::app()->request->baseUrl; ?>/images/stage_ventas.jpg">-->
        <?php //else:  ?>
        <img class="img_rs" id="imgmenu" src="<?php echo Yii::app()->request->baseUrl; ?>/images/perfil-intranet2.jpg">
        <?php //endif;  ?>
    </div>
</section>
<section class="dashb">
        <!--<div><img class="img_rs" src="<?php //echo Yii::app()->request->baseUrl;     ?>/images/img_14.jpg"></div>-->
    <ul class="menu dashboard">
        <?php if($cargo_id == 85 || $cargo_id == 86 || $cargo_adicional == 85 || $cargo_adicional == 86): ?>
        <li class="wrapper">
            <div class="forma">
                <a href="<?php echo Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'externas')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/reportes_2.jpg" width="50"></div>
                    <div class="txt_menu">Reportes Ventas Web</div></a>
            </div>
        </li>
        <?php endif; ?>
        <?php 
        $restricted_reportes = ['74', '76', '77','86','85','59','83','82'];
        if(!in_array($cargo_id, $restricted_reportes)):?>
            <li class="wrapper">
                <div class="forma">
                    <a href="<?php echo Yii::app()->createUrl('Reportes/inicio'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/reportes_2.jpg" width="50"></div>
                        <div class="txt_menu">Reportes Ventas Nuevos</div></a>
                </div>
            </li>
        <?php endif ?>
        <?php
        if ($result) {
            foreach ($result as $r) {
                ?>
                <?php if ($r->descripcion == 'Ventas') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(2) . '/tipo/ventas'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_21.jpg" width="46" height="56"></div>
                                <div class="txt_menu">SGC</div></a>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($r->descripcion == 'Usuarios' && $cargo_id != 73) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(4) . '/tipo/usuarios'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">Usuarios</div></a>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($r->descripcion == 'Posventa' && $cargo_id != 73) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(3) . '/tipo/postventa'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/qir.png" width="46" height="56"></div>
                                <div class="txt_menu">Postventa</div></a>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($r->descripcion == 'Callcenter' && $cargo_id != 73) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(1) . '/tipo/callcenter'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/access.png" width="46" height="56"></div>
                                <div class="txt_menu">Callcenter</div></a>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($r->descripcion == '1800' && $cargo_id != 73) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(5) . '/tipo/1800'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/dashboard/1800l.png" width="46" height="56"></div>
                                <div class="txt_menu">1800</div></a>
                        </div>
                    </li>
                <?php } ?>

                <?php
            }
            ?>
            <li class="wrapper">
                <div class="forma">
                    <a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                        <div class="txt_menu">Directorio de Contactos</div></a>
                </div>
            </li>
            <?php if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) : // AEKIA USERS ?>
            <li class="wrapper">
                <div class="forma">
                    <a href="<?php echo Yii::app()->createUrl('trafico/reportes'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/base.png" width="46" height="56"></div>
                        <div class="txt_menu">Base de Datos</div></a>
                </div>
            </li>   
            <?php endif; ?>
            <?php
    }
        ?>
        <li class="wrapper">
            <div class="forma">
                    <a href="<?php echo Yii::app()->createUrl('site/biblioteca'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/biblioteca3.png" width="46" height="56"></div>
                        <div class="txt_menu">Biblioteca</div></a>
                </div>
        </li>
    </ul>
</section>
