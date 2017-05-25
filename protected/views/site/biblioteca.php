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
                            <a href="<?php echo Yii::app()->createUrl('gestionFiles/admin', array('tipo' => '1')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/libro_prod.png" width="46" height="56"></div>
                                <div class="txt_menu">Libros de Producto</div></a>
                        </div>
        </li>
        <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionFiles/admin', array('tipo' => '2')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/presen_pro.png" width="46" height="56"></div>
                                <div class="txt_menu">Presentación de Producto</div></a>
                        </div>
        </li>
        <!--<li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionFiles/admin', array('tipo' => '3')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/pro_ventas.png" width="46" height="56"></div>
                                <div class="txt_menu">Proceso de Ventas</div></a>
                        </div>
                    </li>-->
        <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionFiles/admin', array('tipo' => '4')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/prec_mercado.png" width="46" height="56"></div>
                                <div class="txt_menu">Precios de Mercado</div></a>
                        </div>
        </li>
        <!--<li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('gestionFiles/admin', array('tipo' => '5')); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuaruos/cuand_compa.png" width="46" height="56"></div>
                                <div class="txt_menu">Cuadros comparativos</div></a>
                        </div>
        </li>-->                                     
    </ul>
</div>