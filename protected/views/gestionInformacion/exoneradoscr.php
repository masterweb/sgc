<?php
/* @var $this GestionInformacionController */
/* @var $model GestionInformacion */

$this->breadcrumbs=array(
	'Gestion Informacions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionInformacion', 'url'=>array('index')),
	array('label'=>'Manage GestionInformacion', 'url'=>array('admin')),
);
?>
<div class="container">
    <?php echo $this->renderPartial('exonerados', array('model'=>$model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        )); ?>
</div>

