<section>
    <div>
        <?php if (Yii::app()->user->getState('area_id') == 17): ?>
            <img class="img_rs" id="imgmenu" src="<?php echo Yii::app()->request->baseUrl; ?>/images/perfil-intranet2.jpg">
        <?php else: ?>
            <img class="img_rs" id="imgmenu" src="<?php echo Yii::app()->request->baseUrl; ?>/images/perfil-intranet2.jpg">
        <?php endif; ?>
    </div>
</section>
<div class="row">
    <ul class="menu">
        <li class="wrapper">
            <div class="forma">
                <a href="<?php echo Yii::app()->createUrl('gestionFiles/admin', array('tipo' => '1', 'mes' => 'Abril')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/libro_prod.png" width="46" height="56"></div>
                <div class="txt_menu">Abril</div></a>
            </div>
        </li>
        <li class="wrapper">
            <div class="forma">
                <a href="<?php echo Yii::app()->createUrl('gestionFiles/admin', array('tipo' => '1', 'mes' => 'Mayo')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/libro_prod.png" width="46" height="56"></div>
                <div class="txt_menu">Mayo</div></a>
            </div>
        </li>
        <li class="wrapper">
            <div class="forma">
                <a href="<?php echo Yii::app()->createUrl('gestionFiles/admin', array('tipo' => '1', 'mes' => 'Junio')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/libro_prod.png" width="46" height="56"></div>
                <div class="txt_menu">Junio</div></a>
            </div>
        </li>
                                           
    </ul>
</div>