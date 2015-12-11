<?php
/* @var $this UaccesoregistroController */
/* @var $data Accesoregistro */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idconfirmado')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idconfirmado), array('view', 'id'=>$data->idconfirmado)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('usuarios_id')); ?>:</b>
	<?php echo CHtml::encode($data->usuarios_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('administrador')); ?>:</b>
	<?php echo CHtml::encode($data->administrador); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descripcion')); ?>:</b>
	<?php echo CHtml::encode($data->descripcion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />


</div>