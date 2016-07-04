<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<?php
$accesosUser = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>(int)Yii::app()->user->getState('cargo_id'))));

$case = '';
$rol = Yii::app()->user->getState('roles');
?>
<script type="text/javascript">
    var abrir=0;
    $(function() {
        $("#keywords").tablesorter();
    });
    function verN(num){
        if(num > 0){
            if(abrir == 0){
                $("#lNotificaciones").show();
                abrir=1;
            }else{
                $("#lNotificaciones").hide();
                abrir=0;
	
            }
        }
        
    }
</script>    
<style>
ul.yiiPager .first, ul.yiiPager .last {
  display: inline !important;
}
    .form-search{
        padding: 0;
    }
</style>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'Qir-form',
    'method' => 'get',
    'action' => Yii::app()->createUrl('pvQir/search'),
    'enableAjaxValidation' => true,
    'htmlOptions' => array('class' => 'form-horizontal form-search')
        ));
?>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">QIR</h1>
    </div>
    <div class="row">
        <!-- FORMULARIO DE BUSQUEDA -->
        <div class="col-md-12">
            <div class="highlight">
                <div class="form">
                    <h4>Filtrar por:</h4>

                    <label class="col-sm-2 control-label" for="Casos_estado">Coincidencia</label>
                    <div class="col-md-3">
                        <?php
                        echo $form->textField($modelPost, 'descripcion', array('class' => 'form-control'));
                        ?>
                        <div class="row col-md-19"></div>
                    </div>
                    <label class="col-sm-1 control-label" for="Casos_estado">Modelo</label>
                    <div class="col-md-2">
                        <?php
                        $modelos = CHtml::listData(Modelosposventa::model()->findAll(array('order' => 'descripcion asc')), 'id', 'descripcion');
                        echo $form->dropDownList($modelPost, 'modeloPostVentaId', $modelos, array('class' => 'form-control', 'prompt' => ''));
                        ?>
                        <div class="row col-md-19"></div>
                    </div>
                    <label class="col-sm-2 control-label" for="Casos_estado">Concesionario</label>
                    <div class="col-md-2">
                        <?php
                        $dealers = CHtml::listData(Dealers::model()->findAll(array('order' => 'name asc')), 'id', 'name');
                        echo $form->dropDownList($modelPost, 'dealerId', $dealers, array('class' => 'form-control', 'prompt' => ''));
                        ?>
                        <div class="row col-md-19"></div>
                    </div>
                    <label class="col-sm-2 control-label" for="Casos_estado">Fecha Registro Inicio</label>
                    <div class="col-md-3">
                        <input size="10" maxlength="10" class="form-control datepicker" name="Qir[fechaInicio]" id="qirFechaInicio" type="text">
                        <div class="row col-md-19"></div>
                    </div>
                    <label class="col-sm-1 control-label" for="Casos_estado">Fecha Registro Fin</label>
                    <div class="col-md-2">
                        <input size="10" maxlength="10" class="form-control datepicker" name="Qir[fechaFin]" id="qirFechaFin" type="text">
                        <div class="row col-md-19"></div>
                    </div>
                    <label class="col-sm-2 control-label" for="Casos_estado">Estado</label>
                    <div class="col-md-2">
                        <?php
                        $estados = array(
                            'Pendiente' => 'Pendiente',
                            'En Estudio' => 'En Estudio',
                            'Mas Informacion Requerida' => 'Más Información Requerida',
                            'Mejorado' => 'Mejorado',
                            'Cerrado' => 'Cerrado',
                        );
                        echo $form->dropDownList($modelPost, 'estado', $estados, array('class' => 'form-control', 'prompt' => ''));
                        ?>
                        <div class="row col-md-19"></div>
                    </div>


                </div>

                <div class="row buttons">
                    <?php //echo CHtml:submitButton($model->isNewRecord ? 'Buscar' : 'Save', array('class' => 'btn btn-danger'));      ?>
                    <input class="btn btn-danger" type="submit" name="yt0" value="Buscar">
                </div>

            </div>

            <div class="row col-md-6 " id="msnError">
                <?php
                foreach (Yii::app()->user->getFlashes() as $key => $message) {
                    echo '<div class="row flash-error">' . $message . "</div>\n";
                }
                ?>
                <div class="row col-md-19"></div>
            </div>
        </div>
        <br>
    </div>

    <div class="row">
        <div class="col-md-9">
            <?php
            if (isset($title_busqueda)):
                echo '<h4 style="color:#AA1F2C;border-bottom: 1px solid;">' . $title_busqueda . '</h4>';
            endif;
            ?>
        </div>
    </div>

    <?php
    if (Yii::app()->user->hasFlash('message')) {
        ?>
        <div class="message">
            <?php
            echo Yii::app()->user->getFlash('message');
            ?>
        </div>
        <?php
    }
    ?>


    <div class="row">
        <div class="table-responsive">
            <div>
			<?php 
			if(!empty($accesosUser)){
				foreach($accesosUser as $a){
			?>
				<?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'create'){?>	
					<a href="<?php echo Yii::app()->createUrl('pvQir/create'); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Agregar Qir</a>
				<?php } ?>
                <!--<a href="<?php // echo Yii::app()->createUrl('pvQir/adjuntoEXS'); ?>" class="btn btn-primary btn-xs btn-danger btnCrear"></a>-->
				<?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'qirExcel'){?>	               
					<?php echo CHtml::link('Exportar Excel',"#", array("submit"=>array('pvQir/qirExcel'),'class'=>'btn btn-primary btn-xs btn-danger btnCrear','target'=>'blank')); ?>                
				<?php } ?>
                <?php // echo CHtml::link('Exportar PDF',"#", array("submit"=>array('pvQir/qirPDF'),'class'=>'btn btn-primary btn-xs btn-danger btnCrear','target'=>'blank')); ?>                
				<?php
				}
			}
		?>
            </div>
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Concesionario</span></th>
                        <th><span># Reporte</span></th>
                        <th><span>Fecha Registro</span></th>
                        <th><span>Modelo</span></th>
                        <th><span>Titular</span></th>
						<th colspan="6">Opciones</th>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //$model = new Qir();
                    if ($model) {
                        foreach ($model as $c):
                            ?>
                            <tr>
                                <td><?php echo $c->id; ?> </td>
                                <td><?php echo strtoupper($c->dealer['name']) ?> </td>
                                <td><?php echo $c->num_reporte ?> </td>
                                <td><?php echo $c->fecha_registro ?> </td>
                                <td><?php echo strtoupper($c->modeloPostVenta['descripcion']) ?> </td>
                                <td><?php echo $c->titular ?> </td>
								
								<?php 
								if(!empty($accesosUser)){
									foreach($accesosUser as $a){
							?>
								<?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'view'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('pvQir/view', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
                                <?php } ?>
							
								<?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'update'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('pvQir/update', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Modificar</a></td>
								<?php } ?>
													
								<?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'eliminar'){?>	
							       <td><?php echo CHtml::link('Eliminar', array('pvQir/eliminar', 'id' => $c->id), array('class' => 'btn btn-primary btn-xs btn-danger', 'onclick' => "return confirm('Esta seguro que desea eliminar este Código Causal?')")) ?> 
								<?php } ?>
							
								<?php if( ($a->accesoSistema->controlador) == 'pvQiradicional' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
							       <td><a href="<?php echo Yii::app()->createUrl('pvQiradicional/admin', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Ver Vin adicional</a></td>
								<?php } ?>
							
								<?php if( ($a->accesoSistema->controlador) == 'pvQircomentario' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('pvQircomentario/admin', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Ver Comentarios</a></td>
								<?php } ?>
							
								<?php if( ($a->accesoSistema->controlador) == 'pvQirfiles' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
								   <td><a href="<?php echo Yii::app()->createUrl('pvQirfiles/admin', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Ver Adjuntos</a></td>
								<?php } ?>
							
							
							<?php
									}
								}
							?>
                            </tr>
                            <?php
                        endforeach;
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
    <div class="row">
        <div class="col-md-10">
            <?php $this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 5, 'firstPageLabel'=>'<< Primera',
    'lastPageLabel'=>'Última>>')); ?>
        </div>
       <div class="col-md-12 links-tabs links-footer">
            
            <div class="col-md-2"><p>Tambi&eacute;n puedes ir a:</p></div>
			<?php 
				if(!empty($accesosUser)){
					foreach($accesosUser as $a){
			?>
					<?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="qir-btn"><span class="textoFEnlace">QIR</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>" class="boletines-btn"><span class="textoFEnlace">Boletines</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvvinMotor' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>" class="vin-btn"><span class="textoFEnlace">Vin-Motor</span></a></div>
					<?php } ?>
								
					<?php if( ($a->accesoSistema->controlador) == 'pvmodelosposventa' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvmodelosposventa/admin'); ?>" class="modelospv-btn"><span class="textoFEnlace">Modelos</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvcodigoCausal' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoCausal/admin'); ?>" class="causal-btn"><span class="textoFEnlace">C&oacute;digo Causal</span></a></div>
					<?php } ?>
							
							
					<?php if( ($a->accesoSistema->controlador) == 'pvcodigoNaturaleza' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoNaturaleza/admin'); ?>" class="naturaleza-btn"><span class="textoFEnlace">C&oacute;digo Naturaleza</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uaccesosistema' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uaccesosistema/admin'); ?>" class="accesos-btn"><span class="textoFEnlace">Accesos al Sistema</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'ucargo' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>" class="cargos-btn"><span class="textoFEnlace">Cargos y Perfiles</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uusuarios' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>" class="usuarios-btn"><span class="textoFEnlace">Usuarios Kia</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uusuarios' &&  ($a->accesoSistema->accion) ==  'contactos'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="contactos-btn"><span class="textoFEnlace">Cont&aacute;ctos</span></a></div>
					<?php } ?>
				<?php if( ($a->accesoSistema->controlador) == 'uarea' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uarea/admin'); ?>" class="contactos-btn"><span class="textoFEnlace">&Aacute;reas</span></a></div>
					<?php } ?>
					
			<?php
					}
				}
			?>
			<div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>	
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $(function () {
        $( ".datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
</script>