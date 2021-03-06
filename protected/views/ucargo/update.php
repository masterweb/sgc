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
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Actualizaci&oacute;n de Cargo o Perfil: <?php echo ($model->area->tipo==1)?'AEKIA':'CONCESIONARIO';?>&nbsp;-&nbsp; <?php echo $model->area->descripcion?></h1>
            <?php echo $this->renderPartial('_form', array('model' => $model,'umodulos'=>$umodulos,'ufuentes'=>$ufuentes,)); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
              <li><a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>" class="seguimiento-btn">Administrador de Cargos</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
				<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>
<script>
	if("<?php echo $model->area->tipo?>" >0){
		$('#slUbicacion').val('<?php echo $model->area->tipo?>');
		traerArea('<?php echo $model->area->tipo?>');
		$('#Cargo_area_id').val('<?php echo $model->area->id?>');
	}
</script>