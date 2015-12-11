<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/estilosUsuarios.css" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sketch.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Registro de nuevo usuario</h1>
			<div class="alert alert-info">
                <strong>Importante!</strong> Recuerda que los campos que contienen (*) son obligatorios y no pueden estar en blanco.
			</div>
            <?php echo $this->renderPartial('_form', array('model' => $model,'ciudades' => $ciudades,'area' => $area)); ?>
            <?php endif; ?>
        </div>
    </div>
</div>