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
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Finalizar Encuesta</h1>
            <?php if (Yii::app()->user->hasFlash('error')){ ?>
                    <div class="infos">
                        <?php echo Yii::app()->user->getFlash('error'); ?>
                    </div>
                <?php } ?>
                <?php if (Yii::app()->user->hasFlash('error')){ ?>
                    <div class="infos">
                        <?php echo Yii::app()->user->getFlash('error'); ?>
                    </div>
                <?php } ?> 
            <?php echo $this->renderPartial('_form', array('model' => $model)); ?>

            <?php endif; ?>
        </div>
        <!--<div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php //echo Yii::app()->createUrl('cpregunta/admin/id/'.$idc); ?>" class="seguimiento-btn">Administrador de Preguntas</a></li>
                <li><a href="<?php //echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>-->
    </div>
</div>