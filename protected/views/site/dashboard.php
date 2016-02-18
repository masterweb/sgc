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
        <?php //if(Yii::app()->user->getState('area_id') == 17): ?>
<!--        <img class="img_rs" id="imgmenu" src="<?php echo Yii::app()->request->baseUrl; ?>/images/stage_ventas.jpg">-->
        <?php //else: ?>
        <img class="img_rs" id="imgmenu" src="<?php echo Yii::app()->request->baseUrl; ?>/images/perfil-intranet2.jpg">
        <?php //endif; ?>
    </div>
</section>
<section class="dashb">
        <!--<div><img class="img_rs" src="<?php //echo Yii::app()->request->baseUrl;  ?>/images/img_14.jpg"></div>-->
    <ul class="menu dashboard">
        <?php
        if ($result) {
            foreach ($result as $r) {
                ?>

                <?php if ($r->descripcion == 'Ventas') { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(2).'/tipo/ventas'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_21.jpg" width="46" height="56"></div>
                                <div class="txt_menu">SGC</div></a>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($r->descripcion == 'Usuarios' && $cargo_id != 73) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(4).'/tipo/usuarios'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/usuarios.png" width="46" height="56"></div>
                                <div class="txt_menu">Usuarios</div></a>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($r->descripcion == 'Posventa' && $cargo_id != 73) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(3).'/tipo/postventa'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/posventa/qir.png" width="46" height="56"></div>
                                <div class="txt_menu">Posventa</div></a>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($r->descripcion == 'Callcenter' && $cargo_id != 73) { ?>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(1).'/tipo/callcenter'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/access.png" width="46" height="56"></div>
                                <div class="txt_menu">Callcenter</div></a>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($r->descripcion == '1800' && $cargo_id != 73) { ?>
<!--                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('site/menu/opcion/' . md5(5).'/tipo/1800'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/dashboard/1800l.png" width="46" height="56"></div>
                                <div class="txt_menu">1800</div></a>
                        </div>
                    </li>-->
                <?php } ?>

                <?php
            }
            ?>
            <li class="wrapper">
                <div class="forma">
                    <a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/contactos.png" width="46" height="56"></div>
                        <div class="txt_menu">Directorio de Contactos</div></a>
                </div>
            </li><?php
        }
        ?>
    </ul>
</section>
