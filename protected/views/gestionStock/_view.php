<?php
/* @var $this GestionStockController */
/* @var $data GestionStock */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_w')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_w); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('embarque')); ?>:</b>
	<?php echo CHtml::encode($data->embarque); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bloque')); ?>:</b>
	<?php echo CHtml::encode($data->bloque); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('familia')); ?>:</b>
	<?php echo CHtml::encode($data->familia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('version')); ?>:</b>
	<?php echo CHtml::encode($data->version); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('equip')); ?>:</b>
	<?php echo CHtml::encode($data->equip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fsc')); ?>:</b>
	<?php echo CHtml::encode($data->fsc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('referencia')); ?>:</b>
	<?php echo CHtml::encode($data->referencia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aeade')); ?>:</b>
	<?php echo CHtml::encode($data->aeade); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('segmento')); ?>:</b>
	<?php echo CHtml::encode($data->segmento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grupo')); ?>:</b>
	<?php echo CHtml::encode($data->grupo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('concesionario')); ?>:</b>
	<?php echo CHtml::encode($data->concesionario); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('color_origen')); ?>:</b>
	<?php echo CHtml::encode($data->color_origen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('color_plano')); ?>:</b>
	<?php echo CHtml::encode($data->color_plano); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('my')); ?>:</b>
	<?php echo CHtml::encode($data->my); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('chasis')); ?>:</b>
	<?php echo CHtml::encode($data->chasis); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edad')); ?>:</b>
	<?php echo CHtml::encode($data->edad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rango')); ?>:</b>
	<?php echo CHtml::encode($data->rango); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fact')); ?>:</b>
	<?php echo CHtml::encode($data->fact); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cod_aeade')); ?>:</b>
	<?php echo CHtml::encode($data->cod_aeade); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pvc')); ?>:</b>
	<?php echo CHtml::encode($data->pvc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stock')); ?>:</b>
	<?php echo CHtml::encode($data->stock); ?>
	<br />

	*/ ?>

</div>