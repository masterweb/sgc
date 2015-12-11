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
                <h1 class="tl_seccion">Update QIR adicional <?php echo $model->id; ?></h1>
                <?php $this->renderPartial('_form', array('model' => $model)); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">

            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvQiradicional/admin/id/'.$model->qirId); ?>" class="seguimiento-btn">Administrador de QIR Adicional</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
               <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>