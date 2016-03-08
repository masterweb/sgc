<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */
/* @var $form CActiveForm */
//echo 'id vehiculo: '.$id_vehiculo;
$id_asesor = Yii::app()->user->getId();
//die('id asesor: '.$id_asesor);
$dealer_id = $this->getConcesionarioId($id_informacion);
//$id_responsable = $this->getResponsableId($id_informacion);
$id_modelo = $this->getIdModelo($id_vehiculo);
$emailAsesor = $this->getAsesorEmail($id_asesor);
$concesionarioid = $this->getConcesionarioDealerId($id_asesor);
if (empty($concesionarioid)) {
    $id_asesor = $this->getResponsableId_gerent($id_informacion);
    $concesionarioid = $this->getConcesionarioDealerId($id_asesor);
}


$telefono = $this->getAsesorTelefono($id_asesor);
$celular = $this->getAsesorCelular($id_asesor);
$codigo_asesor = $this->getAsesorCodigo($id_asesor);
//echo $this->getResponsable($id_asesor);
$mpdf = Yii::app()->ePdf->mpdf();
$codigoconcesionario = $this->getCodigoConcesionario($concesionarioid);
?>
<?php
$criteria = new CDbCriteria(array(
    'condition' => "id_informacion={$id_informacion}",
    'order' => 'id DESC',
    'limit' => 1
        ));
$sol = GestionSolicitudCredito::model()->findAll($criteria);
/* echo '<pre>';
  print_r($vec);
  echo '</pre>'; */
?>
<style type="text/css">
    /*.row{margin-bottom: 1px !important;margin-left: 20px !important;margin-right: 20px !important;}
    .tl_seccion_rf, label{font-weight: bold;}
    .tit-lab{font-weight: bold !important;font-style: italic !important;}
    @page :right {
        margin-left: 4cm;
        margin-right: 4cm;
        header: html_myHeader;
    }*/
    .tit-lab{font-weight: bold !important;font-style: italic !important;min-width: 129px !important;display: inline-block !important;margin-right: 3px !important;}
    .row{margin-bottom: 1px !important;margin-left: 20px !important;margin-right: 20px !important;}
    h4{font-weight: bold;margin-top: 5px !important;}
    hr{margin-top: 0px !important;margin-bottom: 3px !important;}
    .target{font-size: 12px !important;}
    .target strong{color:#911714 !important;}
    .target p{margin: 0 !important;}
    .container{margin-left: -3% !important;}
    .title .col-xs-3{width: 24% !important;}
    .img-logo{position: relative; right: 150px;}
    @page {
          size: auto;
          margin-top:4cm;
          margin-bottom:3cm;
          odd-header-name: html_myHeader1;
          odd-footer-name: html_myFooter1;
        }
</style>
<htmlpageheader name="myHeader1" style="display:none">
    <div class="row title">
        <div class="col-xs-3"><img class="img-logo" src="<?php echo Yii::app()->request->baseUrl ?>/images/logo_pdf2.png" alt=""></div>
        <div class="col-xs-8" style="border-left:1px solid #888890;">
            <h4><?php echo strtoupper($this->getNombreConcesionario($concesionarioid)); ?></h4>
            <div class="target">

                <div class="col-xs-12"><p><?php echo $this->getResponsableNombres($id_asesor); ?></p></div>
                <div class="col-xs-12"><strong>Dirección: <?php echo $this->getConcesionarioDireccion($id_asesor); ?></strong></div>
                <div class="col-xs-5"><p><strong>T </strong> <?php echo $telefono; ?></p></div>
                <div class="col-xs-5"><p><strong>M </strong> <?php echo $celular; ?></p></div>
                <div class="col-xs-5"><p><strong>E </strong><?php echo $emailAsesor; ?> </p></div>
                <div class="col-xs-5"><p><strong>W </strong> www.kia.com.ec</p></div>
            </div>
        </div>
    </div>
</htmlpageheader>
<htmlpagefooter name="myFooter1" style="display:none">
    <table width="100%" style="vertical-align: bottom; font-family: sans; font-size: 8pt; 
           color: #9A9A9A; font-weight: normal; font-style: italic; border-top:1px solid #9A9A9A; pading-top:0.3mm;">
        <tr>
            <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
            <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
            <td width="33%" style="text-align: right; ">Solicitud de Crédito</td>
        </tr>
    </table>
</htmlpagefooter>
<div class="container cont-print">

    <div class="row">
        <div class="col-xs-12"><h3><strong>SOLICITUD DE CRÉDITO EXPRESS</strong></h3></div>
    </div>
    <div class="row">
        <div class="col-xs-12"><h5><strong>Solicitud No. <?php echo $codigoconcesionario; ?>-SC-<?php echo $codigo_asesor; ?>-<?php echo $id_hoja; ?></strong></h5></div>
    </div>
<!--    <div class="row">
        <div class="col-xs-6">
            <?php //echo $form->label class="tit-lab"Ex($model, 'concesionario');   ?>
            <?php //echo $form->textField($model, 'concesionario', array('class' => 'form-control', 'value' => 'Asiauto Mariana de Jesús', 'disabled' => 'true')); ?>
            <?php //echo $form->error($model, 'concesionario'); ?>
            <em class="tit-lab" for="GestionSolicitudCredito_concesionario">Concesionario: </em>
            <?php echo ($this->getNombreConcesionario($concesionarioid)); ?>      
        </div>

        <div class="col-xs-6">
            <?php //echo $form->label class="tit-lab"Ex($model, 'vendedor');   ?>
            <?php //echo $form->textField($model, 'vendedor', array('class' => 'form-control')); ?>
            <?php //echo $form->error($model, 'vendedor'); ?>
            <em class="tit-lab" for="GestionSolicitudCredito_concesionario">Asesor de Ventas: </em>
            <?php echo $this->getResponsable($id_asesor); ?>
        </div>
    </div>     -->
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">Datos del Vehículo</h4></div> 
    </div> 
    <div class="row"><div class="col-xs-12"><hr /></div></div>


    <?php foreach ($sol as $value) { ?>
        <div class="row">
            <div class="col-xs-6">
                <em class="tit-lab" for="">Modelo: </em>
                <?php echo $value['modelo']; ?>
            </div>
            <div class="col-xs-3">
                <em class="tit-lab" for="">Valor: </em>
                <?php echo '$. ' . number_format($value['valor']); ?>

            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Monto a Financiar: </em>
                <?php echo '$. ' . number_format($value['monto_financiar']); ?>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-3">
                <em class="tit-lab" for="">Entrada: </em>
                <?php echo '$. ' . number_format($value['entrada']); ?>

            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Año: </em>
                <?php echo $value['year']; ?>

            </div>
            <div class="col-xs-3">
                <em class="tit-lab" for="">Plazo: </em>
                <?php echo $value['plazo'] . ' meses'; ?>

            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Tasa: </em>
                <?php echo $value['taza'] . '%'; ?>
            </div>
            <div class="col-xs-3">
                <em class="tit-lab" for="">Cuota Mensual: </em>
                <?php echo '$. ' . number_format($value['cuota_mensual']); ?>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">Datos del Solicitante</h4></div>
    </div>
    <div class="row"><div class="col-xs-12"><hr /></div></div>
    <div class="row">
        <div class="col-xs-4">
            <em class="tit-lab" for="">Apellido Paterno: </em>
            <?php echo $value['apellido_paterno']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Apellido Materno: </em>
            <?php echo $value['apellido_materno']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Nombres: </em>
            <?php echo $value['nombres']; ?>
        </div>

    </div>

    <div class="row">
        <?php if(!empty($value['cedula'])){ ?>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Cédula: </em>
            <?php echo $value['cedula']; ?>
        </div>
        <?php } ?>
        <?php if(!empty($value['ruc'])){ ?>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Ruc: </em>
            <?php echo $value['ruc']; ?>
        </div>
        <?php } ?>
        <?php if(!empty($value['pasaporte'])){ ?>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Pasaporte: </em>
            <?php echo $value['pasaporte']; ?>
        </div>
        <?php } ?>
        <div class="col-xs-4"><em class="tit-lab" for="">Fecha de Nacimiento: </em>
            <?php echo $value['fecha_nacimiento']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Nacionalidad: </em>
            <?php echo $value['nacionalidad']; ?>
        </div>

    </div>
    <div class="row">
        <div class="col-xs-6">
            <em class="tit-lab" for="">Estado Civil: </em>
            <?php echo $value['estado_civil']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">Empleo/Actividad Actual del Solicitante</h4></div>
    </div> 
    <div class="row"><div class="col-xs-12"><hr /></div></div>
    <div class="row">
        <div class="col-xs-4">
            <em class="tit-lab" for="">Empresa Trabajo: </em>
            <?php echo $value['nombres']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Teléfonos Trabajo: </em>
            <?php echo $value['telefonos_trabajo']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Años de Trabajo: </em>
            <?php echo $value['tiempo_trabajo'] . ' años'; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Meses de Trabajo: </em>
            <?php echo $value['meses_trabajo'] . ' meses'; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Cargo: </em>
            <?php echo $value['cargo']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-8">
            <em class="tit-lab" for="">Dirección de la Empresa: </em>
            <?php echo $value['direccion_empresa']; ?>
        </div>
        <div class="col-xs-6">
            <em class="tit-lab" for="">Tipo de Relación Laboral: </em>
            <?php echo $value['tipo_relacion_laboral']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <em class="tit-lab" for="">Email: </em>
            <?php echo $value['email']; ?>
        </div>
        <div class="col-xs-6">
            <em class="tit-lab" for="">Actividad de la Empresa: </em>
            <?php echo $value['actividad_empresa']; ?>
        </div>
    </div>
    <?php if ($value['estado_civil'] == 'Casado' || $value['estado_civil'] == 'Casado sin separación de bienes'): ?>

        <div class="row">
            <div class="col-xs-12"><h4 class="tl_seccion_rf">Datos del Cónyugue</h4></div>
        </div> 
        <div class="row"><div class="col-xs-12"><hr /></div></div>
        <div class="row">
            <div class="col-xs-5">
                <em class="tit-lab" for="">Apellido Paterno Cónyugue: </em>
                <?php echo $value['apellido_paterno_conyugue']; ?>
            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Apellido Materno Cónyugue: </em>
                <?php echo $value['apellido_materno_conyugue']; ?>
            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Nombres Cónyugue: </em>
                <?php echo $value['nombres_conyugue']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <em class="tit-lab" for="">Cédula Cónyugue: </em>
                <?php echo $value['cedula_conyugue']; ?>
            </div>
            <div class="col-xs-4">
                <em class="tit-lab" for="">Fecha de Nacimiento Cónyugue: </em>
                <?php echo $value['fecha_nacimiento_conyugue']; ?>
            </div>
            <div class="col-xs-4">
                <em class="tit-lab" for="">Nacionalidad Cónyugue: </em>
                <?php echo $value['nacionalidad_conyugue']; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12"><h4 class="tl_seccion_rf">Empleo/Actividad Actual del Cónyugue</h4></div>
        </div>
        <div class="row"><div class="col-xs-12"><hr /></div></div>
        <div class="row">
            <div class="col-xs-5">
                <em class="tit-lab" for="">Empresa Trabajo Cónyugue: </em>
                <?php echo $value['empresa_trabajo_conyugue']; ?>
            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Teléfono Trabajo Cónyugue: </em>
                <?php echo $value['telefono_trabajo_conyugue']; ?>
            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Años de Trabajo Cónyugue: </em>
                <?php echo $value['tiempo_trabajo_conyugue'] . ' años'; ?>
            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Meses de Trabajo Cónyugue: </em>
                <?php echo $value['meses_trabajo_conyugue'] . ' meses'; ?>
            </div>
            <div class="col-xs-5">
                <em class="tit-lab" for="">Cargo Cónyugue: </em>
                <?php echo $value['cargo_conyugue']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <em class="tit-lab" for="">Dirección Empresa Cónyugue: </em>
                <?php echo $value['direccion_empresa_conyugue']; ?>
            </div>
            <div class="col-xs-6">
                <em class="tit-lab" for="">Tipo Relación Laboral Cónyugue: </em>
                <?php echo $value['tipo_relacion_laboral_conyugue']; ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">Domicilio Actual</h4></div>
    </div>
    <div class="row"><div class="col-xs-12"><hr /></div></div>
    <div class="row">
        <div class="col-xs-5">
            <em class="tit-lab" for="">Tipo de Propiedad: </em>
            <?php echo $value['habita']; ?>
        </div>
        <?php if ($value['habita'] == 'Propia'): ?>
            <div class="col-xs-3">
                <em class="tit-lab" for="">Avalúo Propiedad: </em>
                <?php echo number_format($value['avaluo_propiedad']); ?>
            </div>
        <?php endif; ?>
        <?php if ($value['habita'] == 'Rentada'): ?>
            <div class="col-xs-3">
                <em class="tit-lab" for="">Valor de Arriendo: </em>
                <?php echo '$. ' . number_format($value['valor_arriendo']); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <em class="tit-lab" for="">Calle: </em>
            <?php echo $value['calle']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Barrio: </em>
            <?php echo $value['barrio']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Barrio: </em>
            <?php echo $value['numero']; ?>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-4">
            <em class="tit-lab" for="">Referencia Domicilio: </em>
            <?php echo $value['referencia_domicilio']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Teléfono Residencia: </em>
            <?php echo $value['telefono_residencia']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Celular: </em>
            <?php echo $value['celular']; ?>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">Ingresos</h4></div>
    </div>
    <div class="row"><div class="col-xs-12"><hr /></div></div>
    <div class="row">
        <div class="col-xs-4">
            <em class="tit-lab" for="">Sueldo mensual: </em>
            <?php echo '$. ' . number_format($value['sueldo_mensual']); ?>
        </div>
        <?php if ($value['estado_civil'] == 'Casado' || $value['estado_civil'] == 'Casado sin separación de bienes') { ?>
            <div class="col-xs-4">
                <em class="tit-lab" for="">Sueldo mensual Cónyugue: </em>
                <?php echo '$. ' . number_format($value['sueldo_mensual_conyugue']); ?>
            </div>
        <?php } ?>
    </div>
    <?php if (!empty($value['banco1']) || !empty($value['banco2'])): ?>

        <div class="row">
            <div class="col-xs-12"><h4 class="tl_seccion_rf">Referencias Bancarias</h4></div>
        </div>
        <div class="row"><div class="col-xs-12"><hr /></div></div>
        <div class="row">
            <div class="col-xs-4">
                <em class="tit-lab" for="">Banco 1: </em>
                <?php echo $this->getNameBanco($value['banco1']); ?>
            </div>
            <div class="col-xs-4">
                <em class="tit-lab" for="">Cuenta 1: </em>
                <?php echo $value['cuenta_ahorros1']; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <em class="tit-lab" for="">Banco 2: </em>
                <?php echo $value['banco2']; ?>
            </div>
            <div class="col-xs-4">
                <em class="tit-lab" for="">Cuenta 2: </em>
                <?php echo $value['cuenta_ahorros2']; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">Referencias Personales</h4></div>
    </div>
    <div class="row"><div class="col-xs-12"><hr /></div></div>
    <div class="row">
        <div class="col-xs-6">
            <em class="tit-lab" for="">Referencia Personal 1: </em>
            <?php echo $value['referencia_personal1']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Parentesco 1: </em>
            <?php echo $value['parentesco1']; ?>
        </div>
        <div class="col-xs-6">
            <em class="tit-lab" for="">Teléfono Referencia 1: </em>
            <?php echo $value['telefono_referencia1']; ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-6">
            <em class="tit-lab" for="">Referencia Personal 2: </em>
            <?php echo $value['referencia_personal2']; ?>
        </div>
        <div class="col-xs-4">
            <em class="tit-lab" for="">Parentesco 2: </em>
            <?php echo $value['parentesco2']; ?>
        </div>
        <div class="col-xs-6">
            <em class="tit-lab" for="">Teléfono Referencia 2: </em>
            <?php echo $value['telefono_referencia2']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">Activos y Propiedades</h4></div>
    </div>
    <div class="row"><div class="col-xs-12"><hr /></div></div>
    <div class="row">
        <?php if ($value['tipo_activo1']): ?>
            <div class="col-xs-3">
                <label for=""><em class="tit-lab">Activo 1: </em></label>
                <?php echo $value['tipo_activo1']; ?></p>
            </div>
            <div class="col-xs-3">
                <label for=""><em class="tit-lab">Dirección: </em></label>
                <?php echo $value['direccion_activo1']; ?></p>
            </div>
            <div class="col-xs-3">
                <label for=""><em class="tit-lab">Sector: </em></label>
                <?php echo $value['direccion_sector1']; ?>
            </div>
            <div class="col-xs-3">
                <label for=""><em class="tit-lab">Valor: </em></label>
                <?php echo $value['direccion_valor_comercial1']; ?>
            </div>
        <?php endif; ?>
    </div>
    <br />
    <div class="row">
        <?php if ($value['tipo_activo2']): ?>
            <div class="col-xs-3">
                <label for=""><em class="tit-lab">Activo 2: </em></label>
                <?php echo $value['tipo_activo2']; ?>
            </div>
            <div class="col-xs-3">
                <label for=""><em class="tit-lab">Dirección: </em></label>
                <?php echo $value['direccion_activo2']; ?>
            </div>
            <div class="col-xs-3">
                <label for=""><em class="tit-lab">Sector: </em></label>
                <?php echo $value['direccion_sector2']; ?>
            </div>
            <div class="col-xs-3">
                <label for=""><em class="tit-lab">Valor: </em></label>
                <?php echo $value['direccion_valor_comercial2']; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if ($value['vehiculo_marca2']): ?>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Vehículo: Marca: </em></label>
                <p><?php echo $value['vehiculo_marca1']; ?>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Modelo: </em></label>
                <p><?php echo $value['vehiculo_modelo1']; ?>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Año: </em></label>
                <p><?php echo $value['vehiculo_year1']; ?>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Valor Comercial: </em></label>
                <p><?php echo $value['vehiculo_valor1']; ?>
            </div>
        <?php endif; ?>
    </div>
    <br />
    <div class="row">
        <?php if ($value['tipo_inversion']): ?>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Tipo de inversión: </em></label>
                <p><?php echo $value['tipo_inversion']; ?>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Institución: </em></label>
                <p><?php echo $value['institucion_inversion']; ?>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Valor: </em></label>
                <p><?php echo $value['valor_inversion']; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if ($value['otros_activos']): ?>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Otros activos: </em></label>
                <p><?php echo $value['otros_activos']; ?></p>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Descripción: </em></label>
                <p><?php echo $value['descripcion1']; ?></p>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Valor: </em></label>
                <p><?php echo $value['valor_otros_activos1']; ?></p>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if ($value['otros_activos2']): ?>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Otros activos: </em></label>
                <p><?php echo $value['otros_activos2']; ?></p>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Descripción: </em></label>
                <p><?php echo $value['descripcion2']; ?></p>
            </div>
            <div class="col-xs-2">
                <label for=""><em class="tit-lab">Valor: </em></label>
                <p><?php echo $value['valor_otros_activos2']; ?></p>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-xs-5"><h4 class="tl_seccion_rf">Total Activos: <?php echo $value['total_activos']; ?></h4></div>
    </div>
</div>