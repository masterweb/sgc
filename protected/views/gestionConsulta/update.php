<?php
/* @var $this GestionConsultaController */
/* @var $model GestionConsulta */

$this->breadcrumbs=array(
	'Gestion Consultas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionConsulta', 'url'=>array('index')),
	array('label'=>'Create GestionConsulta', 'url'=>array('create')),
	array('label'=>'View GestionConsulta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionConsulta', 'url'=>array('admin')),
);
?>


<div class="container">

<?php echo $this->renderPartial('_form', array('model'=>$model,'id_informacion' => $id_informacion)); ?>
</div>