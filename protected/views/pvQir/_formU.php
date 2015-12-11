<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<?php

function cleanString($in,$offset=null) 
{ 
    $out = trim($in); 
    if (!empty($out)) 
    { 
        $entity_start = strpos($out,'&',$offset); 
        if ($entity_start === false) 
        { 
            // ideal 
            return $out;    
        } 
        else 
        { 
            $entity_end = strpos($out,';',$entity_start); 
            if ($entity_end === false) 
            { 
                 return $out; 
            } 
            // zu lang um eine entity zu sein 
            else if ($entity_end > $entity_start+7) 
            { 
                 // und weiter gehts 
                 $out = cleanString($out,$entity_start+1); 
            } 
            // gottcha! 
            else 
            { 
                 $clean = substr($out,0,$entity_start); 
                 $subst = substr($out,$entity_start+1,1); 
                 // &scaron; => "s" / &#353; => "_" 
                 $clean .= ($subst != "#") ? $subst : "_"; 
                 $clean .= substr($out,$entity_end+1); 
                 // und weiter gehts 
                 $out = cleanString($clean,$entity_start+1); 
            } 
        } 
    } 
    return $out; 
}

?>

<script>
    $(function() {
        $("#qir-form").validate({
            submitHandler: function(form) {
                var error = 0;
                var formTp = '';
                //                var codigo = $("#CodigoNaturaleza_codigo").val();
                //                var descripcion = $('#CodigoNaturaleza_descripcion').val();
                //				if(codigo == ""){
                //					alert('Ingrese un <?php // echo utf8_encode("c�digo, es un campo obligatorio");       ?>');
                //					$('#CodigoNaturaleza_codigo').focus();
                //					error++;
                //				}
                //				if(descripcion == ""){
                //					alert('Ingrese una <?php // echo utf8_encode("descripci�n, es un campo obligatorio");       ?>');
                //					$('#CodigoNaturaleza_descripcion').focus();
                //					error++;
                //				}
                if (error == 0) {
                    form.submit();
                }

            }
        })
    });
</script>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'qir-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => TRUE,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
            'validateOnType' => false
        ),
        'htmlOptions' => array(
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'dealerId'); ?>
            <?php
            $dealres = CHtml::listData(Dealers::model()->findAll(array('order' => 'name')), 'id', 'name');
            echo $form->dropDownList($model, 'dealerId', $dealres, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'dealerId'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'num_reporte'); ?>
            <?php echo $form->textField($model, 'num_reporte', array('class' => 'span3', 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'num_reporte'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'fecha_registro'); ?>
            <?php echo $form->textField($model, 'fecha_registro', array('class' => 'form-control datepicker')); ?>
            <?php echo $form->error($model, 'fecha_registro'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'modeloPostVentaId'); ?>
            <?php
            $modeloPostVenta = CHtml::listData(Modelosposventa::model()->findAll(array('order' => 'ordenar asc')), 'id', 'descripcion');
            echo $form->dropDownList($model, 'modeloPostVentaId', $modeloPostVenta, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'modeloPostVentaId'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'num_vehiculos_afectados'); ?>
            <?php
            $numV = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20, 21 => 21, 22 => 22, 23 => 23, 24 => 24, 25 => 25, 26 => 26, 27 => 27, 28 => 28, 29 => 29, 30 => 30);
            echo $form->dropDownList($model, 'num_vehiculos_afectados', $numV, array('class' => 'form-control'));
            ?>
            <?php echo $form->error($model, 'num_vehiculos_afectados'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'prioridad'); ?>
            <?php
            $estados = array(
                'Urgente' => 'Urgente',
                'Accion' => 'Acción',
                'Requerida' => 'Requerida',
                'General' => 'General'
            );
            echo $form->dropDownList($model, 'prioridad', $estados, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'prioridad'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'parte_defectuosa'); ?>
            <?php echo $form->textField($model, 'parte_defectuosa', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'parte_defectuosa'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'vin'); ?>
            <?php echo $form->textField($model, 'vin', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'vin'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'num_motor'); ?>
            <?php echo $form->textField($model, 'num_motor', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'OnFocus' => "this.blur()")); ?>
            <?php echo $form->error($model, 'num_motor'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'transmision'); ?>
            <?php
            $trans = array(
                'Manual' => 'Manual',
                'Automatica' => 'Automática'
            );
            echo $form->dropDownList($model, 'transmision', $trans, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'transmision'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'num_parte_casual'); ?>
            <?php echo $form->textField($model, 'num_parte_casual', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'num_parte_casual'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'detalle_parte_casual'); ?>
            <?php echo $form->textField($model, 'detalle_parte_casual', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'detalle_parte_casual'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'codigo_naturaleza'); ?>
            <?php
            $codNatu = CHtml::listData(CodigoNaturaleza::model()->findAll(), 'codigo', 'descripcion');
            echo $form->dropDownList($model, 'codigo_naturaleza', $codNatu, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'codigo_naturaleza'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'codigo_casual'); ?>
            <?php
            $codCasual = CHtml::listData(CodigoCausal::model()->findAll(), 'codigo', 'descripcion');
            echo $form->dropDownList($model, 'codigo_casual', $codCasual, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'codigo_casual'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'fecha_garantia'); ?>
            <?php echo $form->textField($model, 'fecha_garantia', array('class' => 'form-control datepicker')); ?>
            <?php echo $form->error($model, 'fecha_garantia'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'fecha_fabricacion'); ?>
            <?php echo $form->textField($model, 'fecha_fabricacion', array('class' => 'form-control datepicker')); ?>
            <?php echo $form->error($model, 'fecha_fabricacion'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'kilometraje'); ?>
            <?php echo $form->textField($model, 'kilometraje', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'kilometraje'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'fecha_reparacion'); ?>
            <?php echo $form->textField($model, 'fecha_reparacion', array('class' => 'form-control datepicker')); ?>
            <?php echo $form->error($model, 'fecha_reparacion'); ?>
        </div>

        <div class="col-sm-12">
            <?php echo $form->labelEx($model, 'titular'); ?>
            <?php echo $form->textField($model, 'titular', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'titular'); ?>
        </div>

        <div class="col-sm-12">
            <?php echo $form->labelEx($model, 'descripcion'); ?>
            <br>
            <span style="font-size: 11px;">1. Introducción<br>2. Análisis Síntoma<br>3. Investigación<br>4. Acciones correctivas<br>5. Comentarios/Recomendaciones</span>
            <?php
            echo $form->textArea($model, 'descripcion', array(
                'rows' => 10,
                'cols' => 50,
                'class' => 'form-control',
				'value'=>html_entity_decode(str_replace(array("<br/>","<br />","<br>"),array(" "),$model->descripcion), ENT_QUOTES, "UTF-8"),
				//'value'=>str_replace("<br>"," ",$model->descripcion),
                /*'value' => '1. Descripción.

2. Análisis Síntoma

3. Investigación

4. Acciones correctivas

5. Comentarios / Recomendaciones'*/));
            ?>
            <?php echo $form->error($model, 'descripcion'); ?>
        </div>
		 <div class="col-sm-12">
            <?php echo $form->labelEx($modelA, 'Adjuntos'); ?>
            <?php echo CHtml::activeFileField($modelA, 'nombre', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control file')); ?>
            <?php echo $form->error($modelA, 'nombre'); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'ingresado'); ?>
            <?php echo $form->textField($model, 'ingresado', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'ingresado'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'circunstancia'); ?>
            <?php
            $opcion = array(
                'Frecuente' => 'Frecuente',
                'A veces' => 'A veces'
            );
            echo $form->dropDownList($model, 'circunstancia', $opcion, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'circunstancia'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'periodo_tiempo'); ?>
            <?php
            $opcion = array(
                'Al arrancar' => 'Al arrancar',
                'Durante la conducción' => 'Durante la conducción'
            );
            echo $form->dropDownList($model, 'periodo_tiempo', $opcion, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'periodo_tiempo'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'rango_velocidad'); ?>
            <?php
            $opcion = array(
                'Velocidad Alta (80 Km/h)' => 'Velocidad alta (80 Km/h)',
                'Velocidad Media (40 - 60 Km/h)' => 'Velocidad media (40 - 60 Km/h)',
                'Velocidad Baja (bajo 30 Km/h)' => 'Velocidad media (40 - 60 Km/h)',
                'Ralenti' => 'Ralenti',
            );
            echo $form->dropDownList($model, 'rango_velocidad', $opcion, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'rango_velocidad'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'localizacion'); ?>
            <?php
            $opcion = array(
                'Centor' => 'Centor',
                'Frontal lado izquierdo' => 'Frontal lado izquierdo',
                'Frontal lado derecho' => 'Frontal lado derecho',
                'Posterior lado izquierdo' => 'Posterior lado izquierdo',
                'Posterior lado derecho' => 'Posterior lado derecho',
            );
            echo $form->dropDownList($model, 'localizacion', $opcion, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'localizacion'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'fase_manejo'); ?>
            <?php
            $opcion = array(
                'Acelerado' => 'Acelerado',
                'Manejando suavemente' => 'Manejando suavemente',
                'Arrancado' => 'Arrancado',
                'Al Frenar' => 'Al Frenar',
                'Al Acelerar' => 'Al Acelerar',
                'Al Desacelerar' => 'Al Desacelerar',
            );
            echo $form->dropDownList($model, 'fase_manejo', $opcion, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'fase_manejo'); ?>
        </div>
		 
        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'condicion_camino'); ?>
            <?php
            $opcion = array(
                'Mal camino' => 'Mal camino',
                'Camino Sinuoso' => 'Camino Sinuoso',
                'Bajada' => 'Bajada',
                'Subidas' => 'Subidas',
            );
            echo $form->dropDownList($model, 'condicion_camino', $opcion, array('class' => 'form-control', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'condicion_camino'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'etc'); ?>
            <?php echo $form->textField($model, 'etc', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'etc'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'vin_adicional'); ?>
            <?php echo $form->textField($model, 'vin_adicional', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'vin_adicional'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'num_motor_adi'); ?>
            <?php echo $form->textField($model, 'num_motor_adi', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'num_motor_adi'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'kilometraje_adic'); ?>
            <?php echo $form->textField($model, 'kilometraje_adic', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'kilometraje_adic'); ?>
        </div>

        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'estado'); ?>
            <?php
            $estados = array(
                'Pendiente' => 'Pendiente',
                'En Estudio' => 'En Estudio',
                'Mas Informacion Requerida' => 'Más Información Requerida',
                'Mejorado' => 'Mejorado',
                'Cerrado' => 'Cerrado',
				'Anulado' => 'Anulado',
            );
            echo $form->dropDownList($model, 'estado', $estados, array('class' => 'form-control span3', 'prompt' => ''));
            ?>
            <?php echo $form->error($model, 'estado'); ?>
        </div>

      

        <div class="row buttons col-sm-12">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', array('class' => 'btn btn-danger')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
    $(function() {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true
        });

        $("#Qir_vin").change(function() {
            cargarVim($(this).val())
        })
    });

    function cargarVim(val) {
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("pvQir/cargarVin"); ?>',
            data: {vin: val},
            dataType: "json",
            success: function(data) {
                if (data['script'] != "") {
                    eval(data['script'])
                }
                console.log(data['script'])
            }
        });
    }
</script>