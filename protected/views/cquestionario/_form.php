<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl ?>/ckeditor/ckeditor.js"></script>
<script>
	
	 $(function() {
		
		 
        $( "#Cquestionario_fechainicio" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2016'
        });
        $( "#Cquestionario_fechafin" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2016'
        });
	 });
	 
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cquestionario-form',
	'enableAjaxValidation' => false,
        'clientOptions' => array(
        'validateOnSubmit'=>false,
        'validateOnChange'=>false,
        'validateOnType'=>false,
         ),
        'htmlOptions' => array('class' => 'form-horizontal')
            ));
    ?>
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'cbasedatos_id', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->dropDownList($model,'cbasedatos_id', CHtml::listData(Cbasedatos::model()->findAll(array('condition'=>'estado = "ACTIVO"')), 'id', 'nombre'), array('empty'=>'Seleccione >>','class'=>'form-control')) ?> 
			<?php echo $form->error($model,'cbasedatos_id'); ?>

		</div>
	</div>

	<div class="form-group">
			<?php echo $form->labelEx($model,'nombre', array('class' => 'col-sm-2 control-label')); ?>
			<div class="col-sm-4">
			<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>85, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'nombre'); ?>
		</div>
			<?php echo $form->labelEx($model,'descripcion', array('class' => 'col-sm-2 control-label')); ?>
			<div class="col-sm-4">
			<?php echo $form->textField($model,'descripcion',array('size'=>60,'maxlength'=>150, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'descripcion'); ?>
		</div>
	</div>

	<div class="form-group">
			<?php echo $form->labelEx($model,'fechainicio', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'fechainicio',array('class' => 'form-control','readonly'=>'true')); ?>
			<?php echo $form->error($model,'fechainicio'); ?>
		</div>

		
			<?php echo $form->labelEx($model,'fechafin', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'fechafin',array('class' => 'form-control','readonly'=>'true')); ?>
			<?php echo $form->error($model,'fechafin'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'guion', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->textArea($model,'guion',array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'guion'); ?>

		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'estado', array('class' => 'col-sm-2 control-label')); ?>
	<div class="col-sm-4">
		<?php echo $form->dropDownList($model,'estado',array("ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'estado'); ?>
	</div>
	
		<?php echo $form->labelEx($model,'automatico', array('class' => 'col-sm-2 control-label')); ?>
	<div class="col-sm-4">
		<?php echo $form->dropDownList($model,'automatico',array("NO"=>"NO","SI"=>"SI"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'automatico'); ?>
	</div>
</div>

	<?php echo $form->hiddenField($model,'ccampana_id',array('value'=>$idc)); ?>

	
	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->