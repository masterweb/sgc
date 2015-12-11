<?php
if(Yii::app()->user->getState('roles')=== 'asesor'){
    $this->redirect(array('casos/seguimiento'));
}
?>
<style>
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: none;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1 class="tl_seccion">Seguimiento de Casos</h1>
            <div class="form">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'casos-form',
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('class' => 'form-horizontal readonly-form')
                        ));
                ?>
                <?php
                $criteria = new CDbCriteria(array(
                            'order' => 'name'
                        ));
                $menu = CHtml::listData(Menu::model()->findAll($criteria), "id", "name");
                ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'tema', array('class' => 'col-sm-2 control-label')); ?>
                    <div class="col-sm-4">
                        <?php echo $form->dropDownList($model, 'tema', $menu, array('empty' => 'Selecciona un tema', 'class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'tema'); ?>
                    </div>
                    <?php echo $form->labelEx($model, 'subtema', array('class' => 'col-sm-2 control-label')); ?>
                    <div class="col-sm-4">
                        <?php echo $form->dropDownList($model, 'subtema', array('' => 'Selecciona un subtema'), array('class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'subtema'); ?>
                    </div>
                </div>
                <div class="highlight">
                    <div class="row">
                        <label for="" class="col-md-2 control-label">Nombres:</label>
                        <div class="col-sm-4">
                            <?php echo $form->textField($model, 'nombres', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control', 'readonly' => true)); ?>
                        </div>
                        
                        <div class="col-md-3 col-md-offset-3">
                            <button type="button" class="btn btn-primary btn-xs" id="edit-btn">Editar</button>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-md-2 control-label">Apellidos:</label>
                        <div class="col-sm-4">
                            <?php echo $form->textField($model, 'apellidos', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control', 'readonly' => true)); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-md-2 control-label">Provincia:</label>
                        <div class="col-sm-4">
                            <?php
                            $criteria = new CDbCriteria(array(
                                        'condition' => "estado='s'",
                                        'order' => 'nombre'
                                    ));
                            $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                            ?>
                            <?php echo $form->dropDownList($model, 'provincia', $provincias, array('class' => 'form-control', 'empty' => 'Selecciona una provincia', 'readonly' => true)); ?>
                            <?php echo $form->error($model, 'provincia'); ?>
                        </div>
                        <label for="" class="col-md-2 control-label">Ciudad:</label>
                        <div class="col-sm-4">
                            <?php echo $form->dropDownList($model, 'ciudad', array('value' => 'Seleccione'), array('class' => 'form-control', 'readonly' => true)); ?>
                            <?php echo $form->error($model, 'ciudad'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-md-2 control-label">Teléfono:</label>
                        <div class="col-sm-4">
                            <?php echo $form->textField($model, 'telefono', array('size' => 50, 'maxlength' => 9, 'class' => 'form-control', 'readonly' => true)); ?>
                            <?php echo $form->error($model, 'telefono'); ?>
                        </div>
                        <label for="" class="col-md-2 control-label">Celular:</label>
                        <div class="col-sm-4">
                            <?php echo $form->textField($model, 'celular', array('size' => 50, 'maxlength' => 10, 'class' => 'form-control', 'readonly' => true)); ?>
                            <?php echo $form->error($model, 'celular'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-md-2 control-label">Email:</label>
                        <div class="col-sm-4">
                            <?php echo $form->textField($model, 'email', array('size' => 50, 'maxlength' => 9, 'class' => 'form-control', 'readonly' => true)); ?>
                            <?php echo $form->error($model, 'email'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-md-2 control-label">Comentario:</label>
                        <div class="col-sm-8">
                            <?php echo $form->textArea($model, 'comentario', array('rows' => 6, 'cols' => 50, 'class' => 'form-control', 'readonly' => true)); ?>
                            <?php echo $form->error($model, 'comentario'); ?>
                        </div>
                    </div>
                </div>
                <?php
                $accountStatus = array('Abierto' => 'Abierto', 'Proceso' => 'En Proceso', 'Cerrado' => 'Cerrado');
                echo $form->radioButtonList($model, 'estado', $accountStatus, array('separator' => ' '));
                ?>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">Observación:</label>
                    <div class="col-sm-12">
                        <textarea rows="6" cols="50" class="form-control" name="Casos[obervaciones]" id="Casos_obervaciones"></textarea>
                    </div>
                </div>
                <div class="row buttons">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
            <p>También puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('casos/seguimiento'); ?>" class="seguimiento-btn">Seguimiento de Casos</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>" class="reportes-btn">Reportes</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
            </ul>
        </div>
    </div>
</div>