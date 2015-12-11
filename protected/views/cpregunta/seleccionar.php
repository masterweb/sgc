<?php
/* @var $this CasosController */
/* @var $model Casos */

$this->breadcrumbs = array(
    'Casoses' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Casos', 'url' => array('index')),
    array('label' => 'Manage Casos', 'url' => array('admin')),
);
?>
<style>
.menu .wrapper{
    margin:15px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Selección de tipo de Pregunta</h1>
            <section>
                <ul class="menu">
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('cpregunta/create/c/'.$idc.'/opcion/'.$opcion); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/callcenter/text.png" width="46" height="56"></div>
                                <div class="txt_menu">Pregunta Abrierta</div></a>
                        </div>
                    </li>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('cpregunta/opciones/c/'.$idc.'/op/2'.'/opcion/'.$opcion); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/callcenter/check.png" width="46" height="56"></div>
                                <div class="txt_menu">Selecci&oacute;n M&uacute;ltiple</div></a>
                        </div>
                    </li>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('cpregunta/opciones/c/'.$idc.'/op/3'.'/opcion/'.$opcion); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/callcenter/radio.png" width="46" height="56"></div>
                                <div class="txt_menu">Pregunta Cerrada</div></a>
                        </div>
                    </li>
                    <li class="wrapper">
                        <div class="forma">
                            <a href="<?php echo Yii::app()->createUrl('cpregunta/matriz/c/'.$idc.'/op/4'.'/opcion/'.$opcion); ?>"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/callcenter/217-32.png" width="46" height="56"></div>
                                <div class="txt_menu">Matriz de opciones</div></a>
                        </div>
                    </li>
                </ul>
            </section>
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('cpregunta/admin/id/'.$idc); ?>" class="seguimiento-btn">Administrador de Preguntas</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>