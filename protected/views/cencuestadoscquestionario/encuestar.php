
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/mask.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
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
    .lblrespuesta{
        font-size: 12px !important;
        margin: 2px auto;
        font-weight: bold;
    }
    .row.pad-all {
        font-weight: bold;
        font-size: 13px;
        margin-bottom: 10px;
    }
	.tl_seccion {
		width: 95%;
	}
	.highlight {
		background-color: #fff;
		margin: auto 15px 20px;
		border: 10px;
	}
	.membrete h4{
		margin: 0;
		font-weight: 600;
		font-size: 16px;
	}
	.membrete .form .row {
		margin: 0;
	}
	div#formCerrar {
		margin: 15px auto;
		width: 95%;
		border: 1px solid #E7E7E7;
		background-color: #fff;
		border-radius: 5px;
	}
	form#grabarestado {
		background-color: #fff;
		width: 95%;
		margin: 15px auto;
		border: 1px solid #ECECEC;
		border-radius: 5px;
	}
	textarea#observacion {
		padding: 5px;
	}
	#GestionAgendamiento_agendamiento {
		padding: 5px;
		margin: 10px auto;
	}
	.update {
    width: 150px !important;
    font-size: 12px;
    margin-top: -6px;
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
            
        <h1 class="tl_seccion">Encuestado: <i><?php echo $model->cencuestados->nombre;?></i></h1>
            <div class="highlight membrete">
                <div class="form">
                    <h4>Datos Informativos:</h4>
					<hr>
                     <div class="row">
                      <div class="col-md-2 text-right"><h5><b>Nombres:</b></h5></div>
                      <div class="col-md-3"><h5><input type="text" id="txt__nombre" name="txt__nombre" class="update form-control" value="<?php echo $model->cencuestados->nombre;?>"></h5></div>
                      <div class="col-md-2 text-right"><h5><b>Tel&eacute;fono:</b></h5></div>
                      <div class="col-md-3"><h5><input type="text" id="txt__telefono" name="txt__telefono" class="update form-control" value="<?php echo $model->cencuestados->telefono;?>"></h5></div>
                  </div>
                  <div class="row">
                    <div class="col-md-2 text-right"><h5><b>Celular:</b></h4></div>
                      <div class="col-md-3"><h5><input type="text" id="txt__celular" name="txt__celular" class="update form-control" value="<?php echo $model->cencuestados->celular;?>"></h5></div>
                      <div class="col-md-2 text-right"><h5><b>Ciudad:</b></h5></div>
                      <div class="col-md-3"><h5><input type="text" id="txt__ciudad" name="txt__ciudad" class="update form-control" value="<?php echo $model->cencuestados->ciudad;?>"></h5></div>        
                    </div>   
					<div class="row">
                    <div class="col-md-2 text-right"><h5><b>Email:</b></h4></div>
                      <div class="col-md-3"><h5><input type="text" id="txt__email" name="txt__email" class="update form-control" value="<?php echo $model->cencuestados->email;?>"></h5></div>
                      <div class="col-md-2 text-right"><h5><b>Fecha Nacimiento:</b></h5></div>
                      <div class="col-md-3"><h5><input type="text" reandonly="true" id="txt__fecha" name="txt__fecha" class="datepicker update form-control" value="<?php echo $model->cencuestados->fechanacimiento;?>"></h5></div>        
                    </div>
					<input type="hidden" id="id__User" value="<?php echo $model->cencuestados->id;?>">
					<div class="row">
						<div class="alert alert-success" role="alert" id="mensajeUpdate" class="text-right" style="display:none">Datos actualizados exitosamente</div>
						<div class="col-md-12 text-right"><input type="button" id="btnU" value="Actualizar Datos" onclick="UpdateDataUser()" class="btn btn-info"></div>
					</div>
                </div>
            </div>
            <?php
                    $observaciones = Cobservacionesencuesta::model()->findAll(array('condition'=>'cencuestadoscquestionario_id ='.$model->id));
                    if(!empty($observaciones)){
            ?>
                <h4>Detalle de Observaciones realizadas</h4>
                <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>#</span></th>
                        <th><span>Descripci&oacute;n</span></th>
                        <th><span>Fecha</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $cont=1;
                        foreach ($observaciones as $value) {
                            echo '<tr>';
                            echo '<td>'.$cont.'</td>';
                            echo '<td>'.$value->descripcion.'</td>';
                            echo '<td>'.$value->fecha.'</td>';
                            echo '</tr>';
                            $cont++;
                        }
                    ?>
                </tbody>
            </table>
             <?php  
              
            }
             ?>  
             <?php if (Yii::app()->user->hasFlash('error')){ ?>
                    <div class="infos">
                        <?php echo Yii::app()->user->getFlash('error'); ?>
                    </div>
                <?php } ?> 
            <?php if($model->estado == 'PENDIENTE' || $model->estado == 'EN PROCESO'){ ?>
            <div class="form">
                <div class="row text-center">
                    <div class="col-md-4"><a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/encuestarusuario/id/'.$model->id); ?>"><input type="button" value="Encuestar al cliente" class="btn btn-success"></a></div>
                    <div class="col-md-4"><input type="button" value="Volver a llamar" class="btn btn-danger" onclick="desplegar('PENDIENTE')"></div>
                    <div class="col-md-4"><input type="button" value="Cliente no desea" class="btn btn-warning" onclick="desplegar('CERRADO')"></div>

                </div>
                <div class="row" id="formPosponer">
					
                   <!-- <form id="grabarestado" class="form-horizontal" method="post" action="<?php //echo Yii::app()->request->baseUrl; ?>/index.php/cencuestadoscquestionario/encuestar/<?php //echo $model->id?>">-->
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'action' => Yii::app()->createUrl('cencuestadoscquestionario/encuestar/'.$model->id),
                            'id' => 'grabarestado',
                            'enableAjaxValidation' => false,
							 'htmlOptions' => array('class' => 'form-horizontal')
                        ));
                        ?>
					   <h4>Registre la observación dada por el encuestado para volver a llamar</h4>
						<hr>
						<input type="hidden" name="txtestado" id="estado">
                        <textarea name="txtobservacion" id="observacion" class="form-control" placeholder="Ingrese una observación generada en la llamada"></textarea>
						<input type="text" id="GestionAgendamiento_agendamiento" name="GestionAgendamiento[agendamiento]" class="form-control" placeholder="Agendar la llamada" readonly="true">
						<input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
						<input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
						 <?php //echo CHtml::submitButton($form->isNewRecord ? 'Registrar Observación' : 'Registrar Observación', array('class' => 'btn btn-danger')); ?>
						
						<input type="submit" value="Registrar Observación" id="btb" class="btn btn-danger">
						<div id="calendar-content" style="display: none;">
							<a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
						</div>

                     <?php $this->endWidget(); ?>
                </div>
                <div class="row" id="formCerrar">
                    <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'cencuestadoscquestionario-form',
                        'enableAjaxValidation' => false,
                            'clientOptions' => array(
                            'validateOnSubmit'=>false,
                            'validateOnChange'=>false,
                            'validateOnType'=>false,
                             ),
                            'htmlOptions' => array('class' => 'form-horizontal','enctype' => 'multipart/form-data',)
                                ));
                        ?>

                        <?php echo $form->errorSummary($model); ?>
						<h4>Ingresar el motivo por el cuál no desea la encuenta</h4>
						<hr>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'audio', array('class' => 'col-sm-2 control-label text-right')); ?>
                            <div class="col-sm-10">
                                <?php echo CHtml::activeFileField($model, 'audio',array("class"=>"form-control subir")); ?>
                                <?php echo $form->error($model,'audio'); ?>
                            </div>
                            </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'observaciones', array('class' => 'col-sm-2 control-label text-right')); ?>
                            <div class="col-sm-10">
                            <?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50,'class' => 'form-control')); ?>
                            <?php echo $form->error($model,'observaciones'); ?>
                            </div>
                        </div>

                        <div class="row buttons">
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Registrar y Finalizar Llamada' : 'Registrar y Finalizar Llamada', array('class' => 'btn btn-danger')); ?>
                        </div>

                    <?php $this->endWidget(); ?>
                </div>
            </div>
           
             <?php 
                }else{
                    echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      <strong>Esta encuesta ya ha sido realizada!</strong> seleccione otra encuesta por favor.
    </div>';
                }
            ?>

           
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/encuestas/id/'.$model->cquestionario_id); ?>" class="seguimiento-btn">Administrador de Encuestas</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
		$('#GestionAgendamiento_agendamiento').datetimepicker({
            lang:'es',
            onGenerate:function( ct ){
                $(this).find('.xdsoft_date.xdsoft_weekend')
                .addClass('xdsoft_disabled');
            },
            weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
            minDate:'-1970/01/01',//yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates:['03.04.2015','01.05.2015','10.08.2015','09.10.2015','02.11.2015','03.11.2015','25.12.2015'], formatDate:'d.m.Y'
        });
		
		 $('#grabarestado').validate({
            rules:{
                'GestionAgendamiento[agendamiento]':{
                    required:true
                },
                'txtobservacion': {
                    required: true
                },
              
            },
            messages:{
                'GestionAgendamiento[agendamiento]':{
                    required:'Seleccione una fecha de agendamiento'
                },
                'txtobservacion':{
                    required:'Ingrese una observación'
                }
            },
            submitHandler: function(form) {
                var proximoSeguimiento = $('#GestionAgendamiento_agendamiento').val();
               
                var fechaSeguimiento = proximoSeguimiento.replace('/', '-');
                fechaSeguimiento = fechaSeguimiento.replace('/', '-');
                var fechaArray = fechaSeguimiento.split(' ');

             
                //console.log(proximoSeguimiento);
                var fechaActual = new Date().toJSON().slice(0, 10);
				
                //console.log('Fecha Actual: '+hoy);
               /* var diferencia = restaFechas(fechaActual, fechaArray[0]);
				
                if (diferencia <= dias) {
				*/	
                    if (proximoSeguimiento != '') {
                        //console.log('proximo: '+proximoSeguimiento);
                        if ($('#GestionInformacion_check').val() != 2) {
							 $("#btb").hide();
                            var cliente = '';
                            var params = proximoSeguimiento.split("/");
                            var fechaDate = params[0] + params[1] + params[2];
                            var secDate = params[2].split(" ");
                            var fechaStart = params[0] + params[1] + secDate[0];
                            var start = secDate[1].split(":");
                            var startTime = start[0] + start[1];
                            var params2 = fechaDate.split(":");
                            var endTime = parseInt(startTime) + 100;
                            //console.log('start time:'+fechaStart+startTime);
                            //console.log('fecha end:'+fechaStart+endTime);
                            var href = '<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento de llamada para encuesta&desc=Llamar al cliente para la encuesta&location=Por definir&to_name=' + cliente + '&conc=no';
                            //var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                            // cargar href en el boton Descargar Evento
                            $('#event-download').attr('href', href);
                            $('#calendar-content').show();
                            $("#event-download").click(function () {
                                $('#GestionInformacion_calendar').val(1);
                                $('#calendar-content').hide();
                                $('#GestionInformacion_check').val(2);
								$("#btb").show();
                            });
                            if ($('#GestionInformacion_calendar').val() == 1) {
                                form.submit();
                            } else {
                                alert('Debes descargar agendamiento y luego dar click en Continuar');
								$("#btb").hide();
                            }
                        } else {
                          // return false;
						    form.submit();
                        }
                    }
                /*}else{
                    alert('Seleccione una fecha menor o igual a la fecha de Categorización.');
                    return false;
                }*/
            }
        });
		
		
		
		
        $("#txt__fecha").mask('9999-99-99')
        $("#formPosponer").hide();
        $("#formCerrar").hide();
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '1970:2016'
        });
    });
    function desplegar(vl){
       
        $("#estado").val(vl);
        if(vl == 'CERRADO'){
            $("#formCerrar").hide();
            $("#formPosponer").hide();
            $("#formCerrar").show('slow');
            $('#observacion').attr('placeholder','Ingrese el motivo por el cual no se realizará la encuesta.');
        }else{
            $("#formPosponer").hide();
            $("#formCerrar").hide();
            $("#formPosponer").show('slow');
            $('#observacion').attr('placeholder','Ingrese el motivo por el cual la encuesta queda aún PENDIENTE.');
        }
    }
	function UpdateDataUser(){
		if(confirm("Esta seguro que desea actualizar los datos de esta persona?")){
			$("#btnU").hide();
			$("#mensajeUpdate").html("Actualizando datos...");
            $("#mensajeUpdate").show();
			$.ajax({
				url: '<?php echo Yii::app()->createUrl("site/updateuser")?>',
				type:'POST',
				async:true,
				data:{
					nombre : $("#txt__nombre").val(),
					telefono : $("#txt__telefono").val(),
					celular : $("#txt__celular").val(),
					ciudad : $("#txt__ciudad").val(),
					email : $("#txt__email").val(),
					fecha : $("#txt__fecha").val(),
					id : $("#id__User").val(),
				},
				success:function(result){
					if(result == 0){
						alert("No se pudo realizar la actualización de los datos.");
                        $("#mensajeUpdate").fadeOut('slow');
						
					}else{
						alert("Datos actualizados exitosamente.");
						$("#btnU").show();
                        $("#mensajeUpdate").fadeOut('slow');
					}
				}
			});
		}
	}
</script>