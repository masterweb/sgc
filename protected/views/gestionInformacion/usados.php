<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
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
    <div class="row">
        <h1 class="tl_seccion">Sistema de Gestión</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Búsqueda:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('gestionInformacion/seguimientoUsados'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-search'
                        ),
                    ));
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="GestionDiariafecha">Búsqueda General</label>
                            <input type="text" name="GestionSolicitudCredito[general]" id="GestionSolicitudCredito_general" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label for="">Responsable</label>
                            <select name="GestionSolicitudCredito[responsable]" id="" class="form-control">
                                <option value="">--Seleccione responsable--</option>
                                <option value="Jorge Rodriguez">Jorge Rodriguez</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Fecha</label>
                            <input type="text" name="GestionSolicitudCredito[fecha]" id="fecha-range" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label for="">Tipo</label>
                            <select name="GestionSolicitudCredito[tipo_fecha]" id="GestionDiaria_tipo_fecha" class="form-control">
                                <option value="">--Seleccione tipo--</option>
                                <option value="proximoseguimiento">Próximo seguimiento</option>
                                <option value="fechsregistro">Fecha de registro</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
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
                            <th><span>Responsable</span></th>
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
<!--                                <a href="<?php echo Yii::app()->createUrl('gestionDiaria/usados', array('id_informacion' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a>-->
                                <a href="<?php echo Yii::app()->createUrl('gestionDiaria/agendamiento', array('id_informacion' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
