<?= $this->renderPartial('//layouts/rgd/head');?>
<?php
$id_responsable = Yii::app()->user->getId();
$dealer_id = $this->getConcesionarioDealerId($id_responsable);
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$area_id = (int) Yii::app()->user->getState('area_id');
?>
<script type="text/javascript">
    $(function () {
        $('#fecha-range').daterangepicker(
                {
                    locale: {
                        format: 'YYYY/MM/DD'
                    }
                }
        );
        $('.range_inputs .applyBtn').click(function () {
            console.log('apply');
            $('#fecha-range').css("color", "#555555");
        });
    });
</script>
<style type="text/css">
    .daterangepicker .ranges, .daterangepicker .calendar {
        float: left !important;
    }
    #fecha-range{color: #DCD8D9;}
    #toolinfo{position: absolute;right: -20px;top: 24px;}
    .tool{font-size: 11px;margin: 1px 0;}
    @media (min-width: 992px){
        .container {
            max-width: 1170px;
        }
    }
</style>
<div class="container">
    <div class="modal fade" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">SGC</h4>
                        </div>
                        <div class="modal-body">
                            <h4>Tu proceso se ha ido a usados</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="closemodal">Cerrar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
    <div class="row">
        <h1 class="tl_seccion">Sistema de Gestión</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="highlight">
                <?= $this->renderPartial('//layouts/rgd/filtros', array('formaction' => 'gestionInformacion/seguimientoUsados', 'cargo_id' => $cargo_id, 'dealer_id' => $dealer_id, 'tipo_filtro' => 'usados'));?>
            </div>
        </div>
    </div>    
    <div class="row">
        <h1 class="tl_seccion">RGD Usados</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table tablesorter" id="keywords">
                    <thead>
                        <tr>
                            <th><span>ID</span></th>
                            <th><span>Status</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Identificación</span></th>
                            <th><span>Marca</span></th>
                            <th><span>Modelo</span></th>
                            <th><span>Próximo Seguimiento</span></th>
                            <th><span>Asesor Comercial Orígen</span></th>
                            <th><span>Email</span></th>
                            <th><span>Fuente</span></th>
                            <th><span>Edición</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $c): ?>
                        <tr>
                            <td><?php echo $c['id']; ?></td>
                            <td>
                                <?php 
                                $status = $this->getStatusUsados($c['id']);
                                switch ($status) {
                                    case 'Llamar en otro momento':
                                        echo '<img src="'.Yii::app()->request->baseUrl.'/images/icon_llamada.png" alt="Llamar en otro momento" />';
                                        break;
                                    case 'Crear Cita':
                                        echo '<img src="'.Yii::app()->request->baseUrl.'/images/icon_cita.png" alt="Crear cita" />';
                                        break;
                                    case 'Busca solo precio':
                                        echo '<img src="'.Yii::app()->request->baseUrl.'/images/icon_valor.png" alt="Busca solo precio" />';
                                        break;
                                    case 'Desiste':
                                        echo '<img src="'.Yii::app()->request->baseUrl.'/images/icon_desistir.png" alt="Desiste" />';
                                        break;
                                    case 'Otro':
                                        echo '<img src="'.Yii::app()->request->baseUrl.'/images/icon_otro.png" alt="Otro" />';
                                        break;

                                    default:
                                        break;
                                }
                                ?>
                            </td>
                            <td><?php echo $this->getConcesionario($c['dealer_id']); ?></td>
                            
                            <td><?php echo $c['nombres']; ?></td>
                            <td><?php echo $c['apellidos']; ?></td>
                            <td>
                                <?php 
                                if($c['cedula'] != ''){
                                   echo $c['cedula']; 
                                }
                                if($c['pasaporte'] != ''){
                                   echo $c['pasaporte'];
                                }
                                if($c['ruc'] != ''){
                                   echo $c['ruc']; 
                                }
                                ?>
                            </td>
                            <td><?php echo $c['marca_usado']; ?></td>
                            <td><?php
                            echo $c['modelo_usado'];
                            ?></td>
                            <td><?php echo $c['proximo_seguimiento']; ?></td>
                            <td><?php echo $this->getResponsable($c['responsable']); ?></td>
                            <td><?php echo $c['email']; ?></td>
                            <td><?php echo $c['tipo_form_web']; ?></td>
                            <td>
                                <a href="<?php echo Yii::app()->createUrl('gestionDiaria/usados', array('id_informacion' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a>
                            <?php if($area_id != 4 &&  $area_id != 12 &&  $area_id != 13 &&  $area_id != 14){ ?>    
                                <a href="<?php echo Yii::app()->createUrl('gestionDiaria/agendamiento', array('id_informacion' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>
                            <?php } ?>    
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
