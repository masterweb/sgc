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
    .fila-base{
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
            <h1 class="tl_seccion">Creaci&oacute;n de Pregunta</h1>
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
                        <table class="table" id="tabla">
                            <thead>
                                <tr>
                                    <td>Opciones de respuesta</td>
                                    <td>&iquest;Por que?</td>
                                    <td><input id="agregar" type="button" class="btn btn-success" value="Agregar"></td>
                                </tr>
                            </thead>
                            <tbody>
                            <tr class="fila-base">
                                <td>
									<input type="hidden" data-control="true" value="0">
                                    <input type="text" placeholder="Ingrese la opción de respuesta" name="opciontxt[0][opcion]" class="form-control vl">
                                </td>
								<td>
                                    <input type="checkbox" value='1' name="opciontxt[0][justifica]" class="form-control vl">
                                </td>
                                <td class="eliminar">
                                    
                                    <button class="btn btn-danger ">x</button>
                                </td>
                            </tr>
                            <tr class="vvl">
                                <td>
									<input type="hidden" data-control="true" value="1">
                                    <input type="text" placeholder="Ingrese la opción de respuesta" name="opciontxt[1][opcion]" class="form-control vl">
                                </td>
								<td>
                                    <input type="checkbox" value='1' name="opciontxt[1][justifica]" class="form-control vl">
                                </td>
                                <td class="eliminar">
                                    
                                    <button class="btn btn-danger ">x</button>
                                </td>
                            </tr>
                            <tr class="vvl">
                                <td>
									<input type="hidden" data-control="true" value="2">
                                    <input type="text" placeholder="Ingrese la opción de respuesta" name="opciontxt[2][opcion]" class="form-control vl">
                                </td>
								<td>
                                    <input type="checkbox" value='1' name="opciontxt[2][justifica]" class="form-control vl">
                                </td>
                                <td class="eliminar">
                                    
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
        //$("#tabla tbody tr:eq(0)").clone().removeClass('fila-base').addClass('vvl').appendTo("#tabla tbody");
        var $baseRow = $('#tabla tbody tr.fila-base').clone().removeClass('fila-base').addClass('vvl');
		var value = $('#tabla tbody tr:last-child [data-control="true"]').val();
		value = parseInt(value) + 1;
		$baseRow.find('[data-control="true"]').val( value );
		$baseRow.find('[name="opciontxt[0][opcion]"]').attr( 'name', "opciontxt[" + value + "][opcion]" );
		$baseRow.find('[name="opciontxt[0][justifica]"]').attr( 'name', "opciontxt[" + value + "][justifica]" );
		$baseRow.appendTo("#tabla tbody");
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

     $("#cpregunta-form").submit(function( event ) {
        var cont = 0;
        if($('#Cpregunta_descripcion').val() == '' && $('#Cpregunta_descripcion').val().length == 0){
            alert('Debe ingresar una descripción de la Pregunta');
            return false; 
        }

        $( ".vl" ).each(function() {
          if(this.value != '' && this.value.length >0){
            cont++;
          }
        });
        if(cont <2){
            alert('Debe Ingresar mínimo dos opciones de respuesta');
            return false;    
        }
        return true;
    });
});
 
</script>