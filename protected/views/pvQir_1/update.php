<?php
/* @var $this PvQirController */
/* @var $model Qir */

$this->breadcrumbs = array(
    'Qirs' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List Qir', 'url' => array('index')),
    array('label' => 'Manage Qir', 'url' => array('admin')),
);
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
                <h1 class="tl_seccion">Update Qir <?php echo $model->id; ?></h1>
                <?php $this->renderPartial('_formU', array('model' => $model,'modelA' => $modelA)); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">

            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="seguimiento-btn">Administrador de QIR</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
               <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>