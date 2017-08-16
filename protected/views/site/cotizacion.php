<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */
/* @var $form CActiveForm */
//echo 'id vehiculo: '.$id_vehiculo;
//$id_asesor = Yii::app()->user->getId();
$id_asesor = $this->getResponsableId($id_informacion);
$dealer_id = $this->getConcesionarioId($id_informacion);
//$id_responsable = $this->getResponsableId($id_informacion);
$id_modelo = $this->getIdModelo($id_vehiculo);
$emailAsesor = $this->getAsesorEmail($id_asesor);
$concesionarioid = $this->getConcesionarioDealerId($id_asesor);
//die('id informacion: '.$id_informacion);

$telefono = $this->getAsesorTelefono($id_asesor);
$celular = $this->getAsesorCelular($id_asesor);
$codigo_asesor = $this->getAsesorCodigo($id_asesor);
//echo $this->getResponsable($id_asesor);
$mpdf = Yii::app()->ePdf->mpdf();
$codigoconcesionario = $this->getCodigoConcesionario($concesionarioid);
?>
<?php
$criteria = new CDbCriteria(array(
    'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}",
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
    h4{font-weight: bold;margin-top: 5px !important;font-size: 11pt;}
    hr{margin-top: 0px !important;margin-bottom: 3px !important;}
    .target{font-size: 12px !important;}
    .target strong{color:#911714 !important;}
    .target p{margin: 0 !important;}
    .container{margin-left: -3% !important;}
    .title .col-xs-3{width: 24% !important;}
    .img-logo{position: relative; right: 150px;}
    .lbl-item{font-size: 5pt !important;}
</style>
<div class="container cont-print" style="font-size: 9pt;">
    <!--<div class="row title">
        <div class="col-xs-3"><img class="img-logo" src="<?php echo Yii::app()->request->baseUrl ?>/images/logo_pdf2.png" alt=""></div>
        <div class="col-xs-8" style="border-left:1px solid #888890;">
            <h4><?php echo strtoupper($this->getNombreConcesionario($concesionarioid)); ?></h4>
            <div class="target">

                <div class="col-xs-12"><p><?php echo $nombre_responsable; ?></p></div>
                <div class="col-xs-12"><strong>Dirección: <?php echo $this->getConcesionarioDireccion($id_asesor); ?></strong></div>
                <div class="col-xs-5"><p><strong>T </strong> (593) <?php echo $telefono; ?></p></div>
                <div class="col-xs-5"><p><strong>M </strong> (593 9) <?php echo $celular; ?></p></div>
                <div class="col-xs-5"><p><strong>E </strong><?php echo $emailAsesor; ?> </p></div>
                <div class="col-xs-5"><p><strong>W </strong> www.kia.com.ec</p></div>
            </div>
        </div>
    </div>-->
    <div class="row">
        <div class="col-xs-12" style="text-align: center;"><h3><strong>SOLICITUD DE CRÉDITO</strong></h3></div>
    </div>
    


    <div class="row">
        
        <div class="col-xs-4">
            <label for="GestionSolicitudCredito_concesionario">Asesor Comercial: </label>
        </div>  
        <div class="col-xs-6">
            <?php echo $this->getResponsable($id_asesor); ?>
        </div>    
    </div>  
    <div class="row">
        <div class="col-xs-4">
            <label for="GestionSolicitudCredito_concesionario">Concesionario:</label>
        </div>

         <div class="col-xs-6">
            <?php echo ($this->getNameConcesionario($id_asesor)); ?>      
        </div>
        
    </div>  

    <div class="row">
        <div class="col-xs-4 tit-lab"><h4>Solicitud No.:</h4></div>

        <div class="col-xs-6 it-lab"><h4><?php echo $codigoconcesionario; ?>-SC-<?php echo $codigo_asesor; ?>-<?php echo $id_hoja; ?></h4></div>
    </div>
       
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">DATOS DEL VEHÍCULO</h4></div> 
    </div> 
   
    
    <?php foreach ($sol as $value) { ?>
        <div class="row">
            <div class="col-xs-4">
                <label for="">Modelo:</label>
            </div>
            <div class="col-xs-6">
                <?php echo $value['modelo']; ?>
            </div>
            <div class="col-xs-4">
                <label for="">Valor:</label>

            </div>
            <div class="col-xs-6">
                <?php echo '$. '.number_format($value['valor']); ?>
            </div>
            <div class="col-xs-4">
                <label for="">Monto a Financiar:</label>
            </div>
            <div class="col-xs-6">
                <?php echo '$. '.number_format($value['monto_financiar']); ?>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-4">
                <label for="">Entrada:</label>
            </div>
            <div class="col-xs-6">
                <?php echo '$. '.number_format($value['entrada']); ?>

            </div>
            <div class="col-xs-4">
                <label for="">Año:</label>
            </div>
             <div class="col-xs-6">
                <?php echo $value['year']; ?>
            </div>
            <div class="col-xs-4">
                <label for="">Plazo:</label>
            </div>
            <div class="col-xs-6">
                <?php echo $value['plazo']; ?>
            </div>
            <div class="col-xs-4">
                <label for="">Taza:</label>
            </div>
            <div class="col-xs-6">
                <?php echo $value['taza'].'%'; ?>
            </div>
            <div class="col-xs-4">
                <label for="">Cuota Mensual:</label>
            </div>
            <div class="col-xs-6">
                <?php echo '$. '.number_format($value['cuota_mensual']); ?>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">DATOS DEL SOLICITANTE</h4></div>
    </div>
    
    <div class="row">
        <div class="col-xs-4">
            <label for="">Apellido Paterno:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['apellido_paterno']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Apellido Materno:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['apellido_materno']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Nombres:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['nombres']; ?>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-4">
            <label for="">Cédula:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['cedula']; ?>
        </div>
        <div class="col-xs-4"><label for="">Fecha de Nacimiento:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['fecha_nacimiento']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Nacionalidad:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['nacionalidad']; ?>
        </div>

    </div>
    <div class="row">
        <div class="col-xs-4">
            <label for="">Estado Civil:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['estado_civil']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">EMPLEO ACTIVIDAD ACTUAL DEL SOLICITANTE</h4></div>
    </div> 
    
    <div class="row">
        <div class="col-xs-4">
            <label for="">Empresa Trabajo:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['empresa_trabajo']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Teléfonos Trabajo:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['telefonos_trabajo']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Años de Trabajo:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['tiempo_trabajo'].' años'; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Meses de Trabajo:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['meses_trabajo'].' meses'; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Cargo:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['cargo']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <label for="">Dirección de la Empresa:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['direccion_empresa']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Tipo de Relación Laboral:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['tipo_relacion_laboral']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <label for="">Email:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['email']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Actividad de la Empresa:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['actividad_empresa']; ?>
        </div>
    </div>
    <?php if($value['estado_civil'] == 'Casado' || $value['estado_civil'] == 'Casado sin separación de bienes'): ?>
    
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">DATOS DEL CÓNYUGUE</h4></div>
    </div> 
    
    <div class="row">
        <div class="col-xs-4">
            <label for="">Apellido Paterno Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['apellido_paterno_conyugue']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Apellido Materno Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['apellido_materno_conyugue']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Nombres Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['nombres_conyugue']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <label for="">Cédula Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['cedula_conyugue']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Fecha de Nacimiento Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['fecha_nacimiento_conyugue']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Nacionalidad Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['nacionalidad_conyugue']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">EMPLEO ACTIVIDAD ACTUAL DEL CÓNYUGUE</h4></div>
    </div>
    
    <div class="row">
        <div class="col-xs-4">
            <label for="">Empresa Trabajo Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['empresa_trabajo_conyugue']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Teléfono Trabajo Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['telefono_trabajo_conyugue']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Años de Trabajo Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['tiempo_trabajo_conyugue'].' años'; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Meses de Trabajo Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['meses_trabajo_conyugue'].' meses'; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Cargo Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['cargo_conyugue']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <label for="">Dirección Empresa Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['direccion_empresa_conyugue']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Tipo Relación Laboral Cónyugue:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['tipo_relacion_laboral_conyugue']; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">DOMICILIO ACTUAL</h4></div>
    </div>
    
    <div class="row">
        <div class="col-xs-4">
            <label for="">Tipo de Propiedad: </label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['habita']; ?>
        </div>

        <?php if ($value['habita'] == 'Rentada'): ?>
            <div class="col-xs-4">
                <label for="">Valor de Arriendo: </label>
            </div>
             <div class="col-xs-6">
                <?php echo '$. ' . number_format($value['valor_arriendo']); ?>
             </div>

        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <label for="">Calle:</label>
        </div>
        <div class="col-xs-6">
                 <?php echo $value['calle']; ?>
        </div>


        <div class="col-xs-4">
            <label for="">Barrio:</label>
        </div>
        <div class="col-xs-6">
                  <?php echo $value['barrio']; ?>
        </div>

        <div class="col-xs-4">
            <label for="">Número:</label>
            
        </div>
         <div class="col-xs-6">
                <?php echo $value['numero']; ?>
        </div>
    </div>



    <div class="row">
            <div class="col-xs-4">
                <label for="">Referencia Domicilio:</label>
            </div>
            <div class="col-xs-6">
                <?php echo $value['referencia_domicilio']; ?>
            </div>
            <div class="col-xs-4">
                <label for="">Teléfono Residencia:</label>
            </div>
            <div class="col-xs-6">
                <?php echo $value['telefono_residencia']; ?>
            </div>
            <div class="col-xs-4">
                <label for="">Celular:</label>
            </div>
            <div class="col-xs-6">
                <?php echo $value['celular']; ?>
            </div>
    </div>

    <div class="row">
        <div class="col-xs-10"><h4 class="tl_seccion_rf">INGRESOS MENSUALES FAMILIARES</h4></div>
     </div>   
    <div class="row">
        <div class="col-xs-4">
            <label for="">Sueldo mensual:</label>
        </div>
        <div class="col-xs-6">
                <?php echo number_format($value['sueldo_mensual']); ?>
        </div>
        
    </div>   

    <div class="row">
        <?php if($value['estado_civil'] == 'Casado' || $value['estado_civil'] == 'Casado sin separación de bienes'){ ?>
        <div class="col-xs-4">
            <label for="">Sueldo Cónyugue:</label>
        </div>
        <div class="col-xs-6">
                <?php echo empty($value['sueldo_mensual_conyugue']) ? 0 : number_format($value['sueldo_mensual_conyugue']); ?>
        </div>
        <?php } ?>
    </div>

    <div class="row">
             
            <div class="col-xs-4">
                <label for="">Otros Ingresos:</label>            
            </div>
            <div class="col-xs-6">
                <?php echo  number_format($value['otros_ingresos']); ?>
            </div>
            
    </div>

    <div class="row">
        <div class="col-xs-4">
            <label for="">TOTAL INGRESOS: </label>
        </div>
        <div class="col-xs-6">
            <?php echo number_format($value['total_ingresos']); ?>
        </div>   
    </div>
     <div class="row">
        <div class="col-xs-10"><h4 class="tl_seccion_rf">GASTOS MENSUALES FAMILIARES</h4></div>
    </div>
    <div class="row">
           <div class="col-xs-4">
                    <label for="">Arriendo:</label>
            </div>
            <div class="col-xs-6">
                    <?php echo number_format($value['gastos_arriendo']); ?>
            </div>
    </div> 

    
    
    <div class="row">
       
        <?php if (!empty($value['gastos_alimentacion_otros'])) { ?>
        
            
            <div class="col-xs-3">
                <label for="">Alimentación, Agua, Luz y Otros: </label>
            </div>
            <div class="col-xs-2">
                <?php number_format($value['gastos_alimentacion_otros']); ?>
            </div>
        
        <?php } ?>
    </div>

    

  
             
    <div class="row">
         <div class="col-xs-4">
               <label for="">Préstamos:</label>
         </div>
          <div class="col-xs-6">
                    <?php echo  number_format($value['gastos_prestamos']); ?>
          </div>
    </div>
      
   



    <div class="row">     
            <div class="col-xs-4">
                <label for="">Tarjetas de Crédito: </label>
            </div>
            <div class="col-xs-6">
                <?php echo  number_format($value['gastos_tarjetas_credito']); ?>
            </div> 
    </div>
    
    

    <div class="row">
       
    
            <div class="col-xs-4">
                <label for="">TOTAL EGRESOS: </label>
            </div>
            <div class="col-xs-6">
                <?php echo number_format($value['total_egresos']); ?>
            </div>
        
    </div>



    <?php if(!empty($value['banco1']) || !empty($value['banco2'])): ?>
    
    <div class="row">
            <div class="col-xs-12"><h4 class="tl_seccion_rf">REFERENCIAS BANCARIAS</h4></div>
        </div>
        
        <div class="row">
            <div class="col-xs-4">
                <label for="">Banco 1: </label>
            </div>

            <div class="col-xs-6">
                <?php echo $this->getNameBanco($value['banco1']); ?>
            </div>

            <div class="col-xs-4">
                <label for="">Cuenta 1: </label>
            </div>
            <div class="col-xs-6">
                <?php echo $value['cuenta_ahorros1']; ?>
            </div>
        </div>
        <?php if (!empty($value['banco2'])){?>
        <div class="row">
            <div class="col-xs-4">
                <label for="">Banco 2: </label>
            </div>

            <div class="col-xs-6">
                <?php echo $this->getNameBanco($value['banco2']); ?>
            </div>

            <div class="col-xs-4">
                <label for="">Cuenta 2: </label>
            </div>
            <div class="col-xs-6">
                <?php echo $value['cuenta_ahorros2']; ?>
            </div>
        </div>
        <?php } ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">REFERENCIAS PERSONALES</h4></div>
    </div>
  
    <div class="row">
        <div class="col-xs-4">
            <label for="">Referencia Personal 1:</label>
        </div>
         <div class="col-xs-6">
            <?php echo $value['referencia_personal1']; ?>
        </div>

        <div class="col-xs-4">
            <label for="">Parentesco 1:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['parentesco1']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Teléfono Referencia 1:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['telefono_referencia1']; ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-4">
            <label for="">Referencia Personal 2:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['referencia_personal2']; ?>
        </div>

        <div class="col-xs-4">
            <label for="">Parentesco 2:</label>
        </div>

        <div class="col-xs-6">
            <?php echo $value['parentesco2']; ?>
        </div>

        <div class="col-xs-4">
            <label for="">Teléfono Referencia 2:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['telefono_referencia2']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12"><h4 class="tl_seccion_rf">ACTIVOS Y PROPIEDADES</h4></div>
    </div>
   
    <div class="row">
        <?php if($value['tipo_activo1']): ?>
        <div class="col-xs-4">
            <label for="">Activo 1:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['tipo_activo1']; ?></p>
        </div>

        <div class="col-xs-4">
            <label for="">Dirección:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['direccion_activo1']; ?></p>
        </div>

        <div class="col-xs-4">
            <label for="">Sector:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['direccion_sector1']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Valor:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['direccion_valor_comercial1']; ?>
        </div>
        <?php endif; ?>
    </div>
    <br />
    <div class="row">
        <?php if($value['tipo_activo2']): ?>
        <div class="col-xs-4">
            <label for="">Activo 2:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['tipo_activo2']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Dirección:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['direccion_activo2']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Sector:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['direccion_sector2']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Valor:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['direccion_valor_comercial2']; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if($value['vehiculo_marca1']): ?>
        <div class="col-xs-4">
            <label for="">Vehículo: Marca</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['vehiculo_marca1']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Modelo:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['vehiculo_modelo1']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Año:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['vehiculo_year1']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Valor Comercial:</label>
        </div>
        <div class="col-xs-6">
            <?php echo $value['vehiculo_valor1']; ?>
        </div>
        <?php endif; ?>
    </div>
    <br />
    <div class="row">
        <?php if($value['vehiculo_marca2']): ?>
        <div class="col-xs-4">
            <label for="">Vehículo: Marca</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['vehiculo_marca2']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Modelo:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['vehiculo_modelo2']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Año:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['vehiculo_year2']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Valor Comercial:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['vehiculo_valor2']; ?>
        </div>
        <?php endif; ?>
    </div>
    <br />
    <div class="row">
        <?php if($value['tipo_inversion']): ?>
        <div class="col-xs-4">
            <label for="">Tipo de inversión:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['tipo_inversion']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Institución:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['institucion_inversion']; ?>
        </div>
        <div class="col-xs-4">
            <label for="">Valor:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['valor_inversion']; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if($value['otros_activos']): ?>
        <div class="col-xs-4">
            <label for="">Otros activos:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['otros_activos']; ?></p>
        </div>
        <div class="col-xs-4">
            <label for="">Descripción:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['descripcion1']; ?></p>
        </div>
        <div class="col-xs-4">
            <label for="">Valor:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['valor_otros_activos1']; ?></p>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if($value['otros_activos2']): ?>
        <div class="col-xs-4">
            <label for="">Otros activos:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['otros_activos2']; ?></p>
        </div>
        <div class="col-xs-4">
            <label for="">Descripción:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['descripcion2']; ?></p>
        </div>
        <div class="col-xs-4">
            <label for="">Valor:</label>
        </div>
        <div class="col-xs-6">
            <p><?php echo $value['valor_otros_activos2']; ?></p>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-xs-5"><h4 class="tl_seccion_rf">TOTAL ACTIVOS: <?php echo $value['total_activos']; ?></h4></div>
    </div>
    <div class="row">
        <div class="col-xs-5">
            <h4 class="tl_seccion_rf">FIRMA SOLICITANTE</h4>
            <?php 
            $firma = GestionFirma::model()->count(array('condition' => "id_informacion={$id_informacion} AND tipo = 2"));
            if ($firma > 0):
                $fr = GestionFirma::model()->find(array('condition' => "id_informacion={$id_informacion} AND tipo = 2"));
                $imgfr = $fr->firma;
            ?>

                            
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/upload/firma/<?php echo $imgfr; ?>" alt="" width="200" height="100">
                <hr>
               
                                
            <?php endif; ?>
        </div>
    </div>
</div>