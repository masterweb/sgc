<?php 
$area_id = (int) Yii::app()->user->getState('area_id'); 
$cargo_id = (int) Yii::app()->user->getState('cargo_id'); 
?>
<?php if($area_id != 4 &&  $area_id != 12 &&  $area_id != 13 &&  $area_id != 14){ ?>
<div class="col-md-4">
            <div class="highlight">
<h4>Registro de cliente</h4>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gestion-nueva-cotizacion-form',
        'action' => Yii::app()->createUrl('gestionNuevaCotizacion/create'),
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'onsubmit' => "return false;", /* Disable normal form submit */
            'onkeypress' => " if(event.keyCode == 13){ send(); } ", /* Do ajax call when user presses enter key */
            'class' => 'form-horizontal form-search',
            'autocomplete' => 'off'
        ),
    ));
    ?>
    <div class="row">
        <div class="col-md-12" style="position: relative;">
            <?php echo $form->labelEx($model, 'fuente'); ?>
            <?php
            $tipo_array = array();
            $data_tipo = '';
            if($cargo_id == 75){ // asesor exonerado
                $tipo_array =  array('showroom' => 'Tráfico');
                $data_tipo = '<option value="">--Seleccione--</option>
                <option value="Exonerado Conadis">Exonerado Conadis</option>
                <option value="Exonerado Diplomatico">Exonerado Diplomático</option>';
            }
            if($cargo_id == 71 || $cargo_id == 70){ // asesor ventas
                $tipo_array =  array('' => '--Seleccione--',
                    'prospeccion' => 'Prospección',
                    'showroom' => 'Tráfico',
                    'exhibicion' => 'Exhibición'
                    );
                $data_tipo = '<option value="">--Seleccione--</option><option value="Nuevo">Nuevo</option>
                <option value="Usado">Usado</option>
                <option value="Exonerado Conadis">Exonerado Conadis</option>
                <option value="Exonerado Diplomatico">Exonerado Diplomático</option>
                <option value="Flota">Flota</option>';
            }
            if($cargo_id == 73 || $cargo_id == 72){ // asesor bdc y jefe bdc
                $tipo_array =  array('prospeccion' => 'Prospección');
                $data_tipo = '<option value="">--Seleccione--</option><option value="Nuevo">Nuevo</option>';
            }
            echo $form->dropDownList($model, 'fuente', $tipo_array, array('class' => 'form-control'));
            ?>
            <?php echo $form->error($model, 'fuente'); ?>
            <button type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="Info" id="toolinfo">Info</button>
        </div>
    </div>
    <div class="row tipo-cont">
        <div class="col-md-12">
            <label for="GestionNuevaCotizacion_fuente">Tipo</label>
            <select name="GestionNuevaCotizacion[tipo]" id="GestionNuevaCotizacion_tipo" class="form-control">
                <?php echo $data_tipo?>
            </select>
        </div>
    </div>
    <div class="row empresa-cont" style="display: none;">
        <div class="col-md-12">
            <label for="GestionNuevaCotizacion_fuente">Empresa</label>
            <input size="40" maxlength="20" class="form-control" name="GestionNuevaCotizacion[empresa]" id="GestionNuevaCotizacion_empresa" type="text">

        </div>
    </div>
    <div class="row exh-cont" style="display: none;">
        <div class="col-md-12">
            <label for="GestionNuevaCotizacion_fuente">Lugar</label>
            <input size="40" maxlength="80" class="form-control" name="GestionNuevaCotizacion[lugar]" id="GestionNuevaCotizacion_lugar" type="text" value="FERIA DE GUAYAQUIL">
        </div>
    </div>
    <div class="row otro-cont" style="display: none;">
        <div class="col-md-12">
            <label for="GestionNuevaCotizacion_fuente">Otro</label>
            <input size="40" maxlength="20" class="form-control" name="GestionNuevaCotizacion[otro]" id="GestionNuevaCotizacion_otro" type="text">
        </div>
    </div>
    <div class="row motivo-cont" style="display:none;">
        <div class="col-sm-12">
            <?php echo $form->labelEx($model, 'motivo_exonerados'); ?>
            <?php
            echo $form->dropDownList($model, 'motivo_exonerados', array('' => '--Escoja un motivo--',
                'diplomáticos' => 'Vehículos Diplomáticos',
                'renova' => 'Plan Renova',
                'discapacitados' => 'Personas Capacidades Diferentes'
                    ), array('class' => 'form-control'));
            ?>
            <?php echo $form->error($model, 'motivo_exonerados'); ?>
        </div>
    </div>

    <?php if ($identificacion == 'ci'): ?>
        <div id="cont-ident">
            <div class="row">
                <div class="col-sm-12">
                    <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                    <?php echo $form->dropDownList($model, 'identificacion', array('' => '--Seleccione--', 'ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'identificacion'); ?>
                </div>
            </div>
            <div class="row" id="cont-doc">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                    <label class="error" id="cedula2" style="display: none;">Ingrese correctamente el número de cédula</label>
                    <?php echo $form->error($model, 'cedula'); ?>
                </div>
            </div>
            <div class="row" id="cont-ruc" style="display: none;">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                    <?php echo $form->error($model, 'ruc'); ?>
                </div>
            </div>
            <div class="row" id="cont-pasaporte" style="display: none;">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'pasaporte', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'pasaporte'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($identificacion == 'ruc'): ?>
        <div id="cont-ident">
            <div class="row">
                <div class="col-sm-12">
                    <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                    <?php echo $form->dropDownList($model, 'identificacion', array('' => '--Seleccione--', 'ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'identificacion'); ?>
                </div>
            </div>
            <div class="row" id="cont-doc" style="display: none;">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número 1</label>
                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                    <label class="error" id="cedula2" style="display: none;">Ingrese correctamente el número de cédula</label>
                    <?php echo $form->error($model, 'cedula'); ?>
                </div>
            </div>
            <div class="row" id="cont-ruc">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número 2</label>
                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                    <?php echo $form->error($model, 'ruc'); ?>
                </div>
            </div>
            <div class="row" id="cont-pasaporte" style="display: none;">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número 3</label>
                    <?php echo $form->textField($model, 'pasaporte', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'pasaporte'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($identificacion == 'pasaporte'): ?>
        <div id="cont-ident">
            <div class="row">
                <div class="col-sm-12">
                    <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                    <?php echo $form->dropDownList($model, 'identificacion', array('' => '--Seleccione--', 'ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'identificacion'); ?>
                </div>
            </div>
            <div class="row" id="cont-doc" style="display: none;">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                    <label class="error" id="cedula2" style="display: none;">Ingrese correctamente el número de cédula</label>
                    <?php echo $form->error($model, 'cedula'); ?>
                </div>
            </div>
            <div class="row" id="cont-ruc" style="display: none;">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                    <?php echo $form->error($model, 'ruc'); ?>
                </div>
            </div>
            <div class="row" id="cont-pasaporte">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'pasaporte', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'pasaporte'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($identificacion == ''): ?>
        <div id="cont-ident">
            <div class="row">
                <div class="col-sm-12">
                    <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                    <?php echo $form->dropDownList($model, 'identificacion', array('' => '--Seleccione--', 'ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'identificacion'); ?>
                </div>
            </div>
            <div class="row" id="cont-doc">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                    <label class="error" id="cedula2" style="display: none;">Ingrese correctamente el número de cédula</label>
                    <?php echo $form->error($model, 'cedula'); ?>
                </div>
            </div>
            <div class="row" id="cont-ruc" style="display: none;">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                    <?php echo $form->error($model, 'ruc'); ?>
                </div>
            </div>
            <div class="row" id="cont-pasaporte" style="display: none;">
                <div class="col-md-12">
                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                    <?php echo $form->textField($model, 'pasaporte', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'pasaporte'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row buttons">
        <div class="col-md-12">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Save', array('class' => 'btn btn-danger', 'onclick' => 'send();')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
</div>
    </div>
<?php } ?>
