<?php
/* @var $this GestionFinanciamientoController */
/* @var $model GestionFinanciamiento */

$this->breadcrumbs=array(
	'Gestion Financiamientos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionFinanciamiento', 'url'=>array('index')),
	array('label'=>'Manage GestionFinanciamiento', 'url'=>array('admin')),
);
?>
<div class="container">
    <?php echo $this->renderPartial('_form', array('model'=>$model,'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>
</div>
