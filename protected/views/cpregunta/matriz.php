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
<style>
    .fila-base,.fila-baseC{
        display: none;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Creaci&oacute;n de Matriz de opciones</h1>
            <!--FORMULARIO-->

            <div class="form">

                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'cpregunta-form',
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
                        <?php echo $form->labelEx($model,'descripcion', array('class' => 'col-sm-2 control-label')); ?>
                        <div class="col-sm-10">
                        <?php echo $form->textField($model,'descripcion',array('size'=>60,'maxlength'=>350, 'class' => 'form-control')); ?>
                        <?php echo $form->error($model,'descripcion'); ?>
                        </div>
                    </div>

                    <?php echo $form->hiddenField($model,'cquestionario_id',array('value'=>$idc)); ?>
                    <?php echo $form->hiddenField($model,'ctipopregunta_id',array('value'=>$op)); ?>
                     
                    <div id="opciones" class="row highlight">
                        <table class="table " id="tabla">
                            <thead>
                                <tr>
                                    <td>Ingrese las Filas de la matriz</td>
                                    <td><input id="agregar" type="button" class="btn btn-success" value="Agregar"></td>
                                </tr>
                            </thead>
                            <tbody>
                            <tr class="fila-base">
                                <td>
                                    <input type="text" placeholder="Ingrese la opción de la matriz" name="opciontxt[]" class="vl form-control">
                                </td>
                                <td class="eliminar">
                                    
                                    <button class="btn btn-danger ">x</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="opciontxt[]" placeholder="Ingrese la opción de la matriz" class="vl form-control">
                                </td>
                                <td class="eliminar">
                                    
                                    <button class="btn btn-danger ">x</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="opciontxt[]" placeholder="Ingrese la opción de la matriz" class="vl form-control">
                                </td>
                                <td class="eliminar">
                                    
                                    <button class="btn btn-danger ">x</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        
                    </div>   
                    <!--columnas-->
                     <div id="opciones" class="row highlight">
                        <table class="table" id="tablaC">
                            <thead>
                                <tr>
                                    <td>Columnas y valores de la matriz</td>
                                    <td><input id="agregarC" type="button" class="btn btn-success" value="Agregar"></td>
                                </tr>
                            </thead>
                            <tbody>
                            <tr class="fila-baseC">
                                <td>
                                    <input type="text" placeholder="Descripción de la opción" name="columnatxt[pregunta][]" class="form-control vr">
                                </td>
                                <td>
                                    <input type="text" placeholder="Valor de la Opción" name="columnatxt[valor][]" class="form-control vrv">
                                </td>
                                <td class="eliminarC">
                                    
                                    <button class="btn btn-danger ">x</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" placeholder="Descripción de la opción" name="columnatxt[pregunta][]" class="form-control vr">
                                </td>
                                <td>
                                    <input type="text" placeholder="Valor de la Opción"  name="columnatxt[valor][]" class="form-control vrv">
                                </td>
                                <td class="eliminarC">
                                    
                                    <button class="btn btn-danger ">x</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" placeholder="Descripción de la opción" name="columnatxt[pregunta][]" class="form-control vr">
                                </td>
                                <td>
                                    <input type="text" placeholder="Valor de la Opción"  name="columnatxt[valor][]" class="form-control vrv">
                                </td>
                                <td class="eliminarC">
                                    
                                    <button class="btn btn-danger ">x</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        
                    </div>
					 <div class="form-group pad-all">
                        <?php echo $form->labelEx($model,'orden', array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-9">
						<?php $nump =  Cpregunta::model()->count(array('condition'=>'cquestionario_id='.$idc))+1;?>
                        <?php echo $form->textField($model,'orden',array('size'=>60,'maxlength'=>350, 'class' => 'form-control','value'=>$nump)); ?>
                        <?php echo $form->error($model,'orden'); ?>
                        </div>
                    </div>
                    <div class="row buttons">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
                    </div>

                <?php $this->endWidget(); ?>

                </div><!-- form -->
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('cpregunta/seleccion/c/'.$idc); ?>" class="seguimiento-btn">Administrador de Preguntas</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
 
$(function(){
    // Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
    $("#agregar").on('click', function(){
        $("#tabla tbody tr:eq(0)").clone().removeClass('fila-base').appendTo("#tabla tbody");
        
    });

    $("#agregarC").on('click', function(){
        $("#tablaC tbody tr:eq(0)").clone().removeClass('fila-baseC').appendTo("#tablaC tbody");
        
    });
 
    // Evento que selecciona la fila y la elimina 
    $(document).on("click",".eliminar",function(){
        if(($('table#tabla tr:last').index() + 1)<=3){
            alert('Las opciones de respuesta no pueden tener menos de dos items.');
            return false;    
        }
        
        var parent = $(this).parents().get(0);
        $(parent).remove();
    });
    // Evento que selecciona la fila y la elimina 
    $(document).on("click",".eliminarC",function(){
        if(($('table#tablaC tr:last').index() + 1)<=3){
            alert('Las opciones de respuesta no pueden tener menos de dos items.');
            return false;    
        }
        
        var parent = $(this).parents().get(0);
        $(parent).remove();
    });
    $("#cpregunta-form").submit(function( event ) {
        var cont = 0;
        var c = 0;
        var vv = 0;
        if($('#Cpregunta_descripcion').val() == '' && $('#Cpregunta_descripcion').val().length == 0){
            alert('Debe ingresar una descripción de la Pregunta');
            return false; 
        }

        $( ".vl" ).each(function() {
          if(this.value != '' && this.value.length >0){
            cont++;
          }
        });
        $( ".vr" ).each(function() {
          if(this.value != '' && this.value.length >0){
            c++;
          }
        });
        $( ".vrv" ).each(function() {
          if(this.value != '' && this.value.length >0){
            vv++;
          }
        });
        if(cont <2){
            alert('Debe Ingresar mínimo dos filas de la matriz');
            return false;    
        }
        if(c <2){
            alert('Debe Ingresar mínimo dos columnas con sus valores de la matriz');
            return false;    
        }
        if(vv <2){
            alert('Debe Ingresar mínimo dos columnas con sus valores correspondientes en la matriz');
            return false;    
        }
        return true;
    });
});
 
</script>