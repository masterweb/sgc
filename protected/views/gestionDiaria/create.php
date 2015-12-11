<?php
/* @var $this GestionDiariaController */
/* @var $model GestionDiaria */

$this->breadcrumbs=array(
	'Gestion Diarias'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionDiaria', 'url'=>array('index')),
	array('label'=>'Manage GestionDiaria', 'url'=>array('admin')),
);
?>
<div class="container">
   <?php echo $this->renderPartial('_form', array('model'=>$model,'id_informacion' => $id_informacion,'id' => $id)); ?> 
</div>
