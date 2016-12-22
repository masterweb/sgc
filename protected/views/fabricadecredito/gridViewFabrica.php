<?= $this->renderPartial('//layouts/rgd/head'); ?>
<style type="text/css">
	body{
		overflow-x: hidden;
	}
	table th{
		border-right: 1px groove  #FFF;
	}
	table td{
		border-right: 1px solid #FFF;
	}

</style>
<h2 align="center">Fábrica de Crédito</h2>

<br>
<!--FILTROS -->
<script type="text/javascript">
$(function () {
    $('#fecha-range').daterangepicker(
        {
            locale: {
            format: 'YYYY/MM/DD'
            }
        }
    );
    $('#fabrica_credito_gconcesionario').change(function () {
        var valuenc = $(this).attr('value');
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getConcesionarios"); ?>',
            beforeSend: function (xhr) {
            //$('#info-3').show();  // #bg_negro must be defined somewhere
            },
            type: 'POST',
            //dataType: 'json', 
            data: {id: valuenc},
            success: function (data) {
            //$('#info-3').hide();
            //alert(data);
            $('#fabrica_credito_concesionario').html(data);
            }
        });
    });

    /*$('#fabrica_credito_concesionario').change(function () {
        var valuenc = $(this).attr('value');
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getConcesionarios"); ?>',
            beforeSend: function (xhr) {
            //$('#info-3').show();  // #bg_negro must be defined somewhere
            },
            type: 'POST',
            //dataType: 'json', 
            data: {dealers_id: valuenc},
            success: function (data) {
            //$('#info-3').hide();
            //alert(data);
            $('#fabrica_credito_asesor').html(data);
            }
        });
    });*/
});
</script>
<div class="form" >
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'filtros-form',
        'method' => 'get',
        'action' => Yii::app()->createUrl('fabricadeCredito/GridViewCreditoFiltro'),
        'enableAjaxValidation' => true,
        'htmlOptions' => array(
            'class' => 'form-horizontal form-search'
        ),
    ));
    ?>
    <!--<h4>Búsqueda:</h4>-->
    <div class="row">

        <div class="col-md-2">
            <label for="FabricadeCreditofecha">Fecha Ingreso</label>
            <input type="text" name="FabricadeCredito_fecha" id="fecha-range" class="form-control"/>
        </div>

        <div class="col-md-2">
            <label for="FabricadeCreditostatus">Status</label>
            <select type="text" name="FabricadeCredito_status" class="form-control" id="fabrica_credito_status">
                <option value="0">--Seleccione status--</option>
                <option value="1">Solicitud en movimiento</option>
                <option value="2">Solicitud Aprobada</option>
                <option value="3">Negada</option>
                <option value="4">Solicitud Condicionada</option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="FabricadeCreditoconcesionario">Grupo de Concesionario</label>
            <select type="text" name="FabricadeCredito_gconcesionario" class="form-control" id="fabrica_credito_gconcesionario">
                <option> -- Seleccione -- </option>
                <?php
                    $grupo = GrGrupo::model()->findAll(array('condition'=>'ruc<>""'));
                    if(!empty($grupo)){
                        foreach($grupo as $c){
                            echo '<option value="'.$c->id.'">'.$c->nombre_grupo.'</option>';
                        }
                    }
                ?>
            </select>
        </div>

        <div class="col-md-2">
            <label for="FabricadeCreditoconcesionario">Concesionario</label>
            <select type="text" name="FabricadeCredito_concesionario" class="form-control" id="fabrica_credito_concesionario">
                <option value="0">--Seleccione Concesionario--</option>
            </select>
        </div>

        <!--<div class="col-md-2">
            <label for="">Asesor de credito</label>
            <select name="FabricadeCredito_asesor" id="fabrica_credito_asesor" class="form-control">
                <option value="0">--Seleccione Asesor--</option>
                <option value="1">prueba</option>
            </select>
        </div>-->

        <div class="col-md-2" style="margin-top: 15px">
            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
        </div>
        <div class="col-md-2" style="margin-top: 15px">
            <a href="<?php echo Yii::app()->createUrl('fabricadeCredito/GridViewCredito'); ?>" class="btn btn-warning">Ver Todos</a>
        </div>
    </div>    
    <?php $this->endWidget(); ?>
</div>
<!--FIN FILTROS -->
<br>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" style="margin:0px 10px 0 10px !important">
            <table class="table tablesorter table-striped" id="keywords">
                <thead>
                    <tr>
                    	<th><span>ID</span></th>
                        <th><span>Nombre y Apellido</span></th>
                        <th><span>Cedula</span></th>
                        <th><span>Celular</span></th>
                        <th><span>Modelo</span></th>
                        <th><span>Grupo Concesionario</span></th>
                        <th><span>Fecha</span></th>
                        <th><span>--</span></th>
                    </tr>
                </thead>
                <tbody>
                <?php //print_r('<pre>'); print_r($data); die();?>
                <?php foreach ($data as $c): ?>
                	<tr>
                		<td>
                			<?php echo $c['id'];?>
                		</td>

                		<td>
                		<?php echo $c['nombres']." ".$c['apellidos'];?>
                		</td>

                		<td>
                		<?php echo $c['cedula'];?>
                		</td>

                		<td>
                		<?php echo $c['celular'];?>
                		</td>

                		<td>
                		<?php echo $c['modelo'];?>
                		</td>

                		<td>
                		<?php echo $this->getNameConcesionarioById($c['concesionario']);?>
                		</td>

                		<td>
                		<?php echo $c['fecha'];?>
                		</td>

                		<td>
                		<a href="<?php echo Yii::app()->createUrl('fabricadeCredito/detalle', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-warning">Ver Detalle</a> 
                		</td>
                	</tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div align="center"><?php $this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 10)); ?></div>
        </div>
    </div>
</div>