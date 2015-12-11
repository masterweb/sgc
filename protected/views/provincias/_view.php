<?php
/* @var $this ProvinciasController */
/* @var $data Provincias */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_provincia')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_provincia), array('view', 'id'=>$data->id_provincia)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />


</div>