<?php

//echo '<pre>';
//print_r($data);
//echo '</pre>';
//echo 'num proforma: '.$id_hoja;
$id_asesor = Yii::app()->user->getId();
//echo $this->getResponsable($id_asesor);

?>
<style>
    /*.container{width: 800px;}*/
    .row{margin-bottom: 1px !important;margin-left: 20px !important;margin-right: 20px !important;}
    h4{font-weight: bold;margin-top: 5px !important;}
    .target{font-size: 12px !important;}
    .target strong{color:#911714 !important;}
    .target p{margin: 0 !important;}
    .container{margin-left: -3% !important;}
    .title .col-xs-3{width: 24% !important;}
    .img-logo{position: relative; right: 150px;}
</style>
<?php $mensaje = ''; ?>
<?php $mensaje .= '<div class="container cont-print">'; ?>
<?php

foreach ($data as $key => $value) :
    ?>
    <?php
    $credito = $this->getFinanciamiento($id_informacion);
    $mensaje .= '<div class="row title">
                    <div class="col-xs-3"><img src="' . Yii::app()->request->baseUrl . '/images/logo_pdf2.png" alt=""></div>
                    <div class="col-xs-8" style="border-left:1px solid #888890;">
                        <h4>ASIAUTO MARIANA DE JESÚS</h4>
                        <div class="target">
                        
                        <div class="col-xs-12"><p>'.$this->getResponsable($id_asesor).'</p></div>
                        
                        
                        <div class="col-xs-12"><strong>Dirección: Av. Mariana de Jesús y Gaspar de Carvajal Esq.</strong></div>
                        
                        <div class="col-xs-5"><p><strong>T </strong> (593) 2 2415878 ext. 451</p></div>
                        <div class="col-xs-5"><p><strong>M </strong> (593 9) 950-5485</p></div>
                        
                        <div class="col-xs-5"><p><strong>E </strong> asesor1@kia.com.ec</p></div>
                        <div class="col-xs-5"><p><strong>W </strong> www.kia.com.ec</p></div>
                        
                        </div>
                    </div>
                 </div>
        </div>
        <br />
        <div class="row">
            <div class="col-xs-5"><h5><strong>PROFORMA No. AS-MDJ-P-0000-'.$id_informacion.'-'.$id_hoja.'</strong></h5></div>
            <div class="col-xs-4 col-xs-offset-3"><h5><strong>R.U.C. 1701666187001</h5></strong></div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>CLIENTE: </strong>' . $value['nombres'] . ' ' . $value['apellidos'] . '</div>
            <div class="col-xs-3"><strong>FECHA: </strong>' . date("d") . "/" . date("m") . "/" . date("Y") . '</div>
        </div>
        <div class="row">
            <div class="col-xs-12"><strong>DIRECCIÓN: </strong>' . $value['direccion'] . '</div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>TELÉFONO: </strong>' . $value['telefono_casa'] . '</div>
            <div class="col-xs-4"><strong>CELULAR: </strong>' . $value['celular'] . '</div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>MODELO: </strong>' . $this->getModel($value['modelo']) . '</div>
            <div class="col-xs-6"><strong>VERSIÓN: </strong>' . $this->getVersion($value['version']) . '</div>
            
        </div>
        <div class="row">
            <!--div class="col-xs-3"><strong>AÑO: </strong>2015</div-->
        </div>
        <div class="row">
            <div class="col-xs-6"><strong>FORMA DE PAGO: </strong>' . $value['forma_pago'] . '</div>
        </div>';
        if($credito == 1):    
    $mensaje .= '<div class="row"><div class="col-xs-3"><h4 style="color:#911714;">FINANCIAMIENTO</h4></div></div>
        <div class="row"><div class="col-md-12"><hr style="color:#911714;"></div></div>';
    $paramAutos = explode('@', $value['accesorios']);
    $strinAcc = '';
    foreach ($paramAutos as $val) {
        $strD = explode('-', $val);
        $strinAcc .= $strD[1] . ',';
    }
    $strinAcc = substr($strinAcc, 0, -1);
    $precioNormal = $this->getPrecioNormal($value['version']);
    $precioAccesorios = $value['precio_vehiculo'] - $precioNormal;
    $mensaje .= '
        <div class="row">
            <div class="col-xs-7"><strong>PRECIO DE VENTA VEHÍCULO (INC. I.V.A): </strong> $ ' . number_format($precioNormal) . '</div>
        </div>
               
        <div class="row">
        <div class="col-xs-12"><strong>ACCESORIOS: </strong>' . $strinAcc . '</div>
        </div>
        <div class="row">
        <div class="col-xs-12"><strong>ACCESORIOS TOTAL: </strong> $ ' . number_format($precioAccesorios) . '</div>    
        </div>
        <div class="row">
            <div class="col-xs-8"><strong>PRECIO DE VENTA INCLUÍDO ACCESORIOS (INC. I.V.A): </strong> $ ' . number_format($value['precio_vehiculo']) . '</div>
            
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>SEGURO / AÑOS:</strong></div><div class="col-xs-4"> $ ' . number_format($value['seguro']) . ' / '.$value['ts'].'</div>
        </div>

        <div class="row">
            <div class="col-xs-4"><strong>TOTAL: </strong></div><div class="col-xs-4"> $ ' . number_format($value['precio_vehiculo']) . '</div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>ENTRADA: </strong></div><div class="col-xs-4"> $ ' . number_format($value['cuota_inicial']) . '</div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>SALDO A FINANCIAR: </strong></div><div class="col-xs-4"> $ ' . number_format($value['valor_financiamiento']) . '</div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>PLAZO MESES: </strong>' . $value['plazos'] . '</div><div class="col-xs-4"><strong>CUOTA MENSUAL APROX: </strong> $ ' . $value['cuota_mensual'] . '</div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <strong>ENTIDAD FINANCIERA: </strong>' . $value['entidad_financiera'] . '</div>
        </div>';
    else:
        $mensaje .= '<div class="row">
                        <div class="col-xs-6"><h4 style="color:#911714;">VENTA AL CONTADO</h4>
                        </div>
                    </div>
        <div class="row">
            <div class="col-md-12"><hr style="color:#911714;">
            </div>
        </div>';
    $paramAutos = explode('@', $value['accesorios']);
    $strinAcc = '';
    foreach ($paramAutos as $val) {
        $strD = explode('-', $val);
        $strinAcc .= $strD[1] . ',';
    }
    $strinAcc = substr($strinAcc, 0, -1);
    $precioNormal = $this->getPrecioNormal($value['version']);
    $precioAccesorios = $value['precio_vehiculo'] - $precioNormal;
    $mensaje .= '
        <div class="row">
            <div class="col-xs-7"><strong>PRECIO DE VENTA VEHÍCULO (INC. I.V.A): </strong> $ ' . number_format($precioNormal) . '</div>
        </div>
               
        <div class="row">
        <div class="col-xs-12"><strong>ACCESORIOS: </strong>' . $strinAcc . '</div>
        </div>
        <div class="row">
        <div class="col-xs-12"><strong>ACCESORIOS TOTAL: </strong> $ ' . number_format($precioAccesorios) . '</div>    
        </div>
        <div class="row">
            <div class="col-xs-8"><strong>PRECIO DE VENTA INCLUÍDO ACCESORIOS (INC. I.V.A): </strong> $ ' . number_format($value['precio_vehiculo']) . '</div>
            
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>SEGURO / AÑOS:</strong></div><div class="col-xs-4"> $ ' . number_format($value['seguro']) . ' / '.$value['ts'].' </div>
        </div>';
    endif;
    $criteria = new CDbCriteria(array(
        'condition' => "id_financiamiento='{$value['id_financiamiento']}'"
    ));
    $otraFin = GestionFinanciamientoOp::model()->count($criteria);
    if ($otraFin > 0) {
        $accs = GestionFinanciamientoOp::model()->findAll($criteria);
        foreach ($accs as $vals) {
            $mensaje .= '<div class="row"><div class="col-md-12"><hr style="color:#911714;"></div></div>'
                    . '<div class="row">
            <div class="col-xs-7"><strong>PRECIO DE VENTA VEHÍCULO (INC. I.V.A): </strong> $ ' . number_format($vals['precio_vehiculo']) . '</div>
            
        </div>
        <div class="row">
        <div class="col-xs-12"><strong>ACCESORIOS: </strong>' . $strinAcc . '</div>
        </div>
        <div class="row">
        <div class="col-xs-12"><strong>ACCESORIOS TOTAL: </strong> $ ' . number_format($precioAccesorios) . '</div>    
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>SEGURO / AÑOS:</strong></div><div class="col-xs-4"> $ ' . number_format($vals['seguro']) . ' / '.$vals['ts'].' </div>
        </div>

        <div class="row">
            <div class="col-xs-4"><strong>TOTAL: </strong></div><div class="col-xs-4"> $ ' . number_format($vals['valor_financiamiento']) . '</div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>ENTRADA: </strong></div><div class="col-xs-4"> $ ' . number_format($vals['cuota_inicial']) . '</div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>SALDO A FINANCIAR: </strong></div><div class="col-xs-4"> $ ' . number_format($vals['valor_financiamiento']) . '</div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>PLAZO MESES: </strong>' . $vals['plazos'] . '</div><div class="col-xs-4"><strong>CUOTA MENSUAL APROX: </strong> $ ' . $vals['cuota_mensual'] . '</div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <strong>ENTIDAD FINANCIERA: </strong>' . $vals['entidad_financiera'] . '</div>
        </div>';
        }
    }
    if($credito == 1):
        $mensaje .= '<div class="row">'
            . '         <div class="col-md-12"><hr /></div>'
            . '      </div>'
            . '      <div class="row">'
                    . '<div class="col-xs-11"><strong>NOTAS: </strong><br />'
                    . '* La proforma es referencial y sujeta a revisión y análisis por parte de nuestro Jefe de Sucursal al momento de concretar la compra de su vehículo. Tiene una validez de 48 horas.'
                    . ' Las especificaciones y precios pueden variar sin previo aviso. <br />'
                    . '* Valor de la cuota sujeta a revisión o confirmación por parte del asesor de crédito - '
                    . 'Cotización Referencial'
            . '      </div>';
    else:
        $mensaje .= '<div class="row">'
            . '<div class="col-md-12"><hr /></div>'
            . '</div>'
            . '<div class="row">'
                . '<div class="col-xs-8"><strong>NOTAS: </strong><br />'
                . '* La proforma es referencial y sujeta a revisión y análisis por parte de nuestro Jefe de Sucursal al momento de concretar la compra de su vehículo. Tiene una validez de 48 horas. '
                . 'Las especificaciones y precios pueden variar sin previo aviso.'
                . '* Precios y/o observaciones sujetos a cambio sin previo aviso <br />'
            . '   </div>'
            . '</div>';
    endif;
    $mensaje .= '<div class="row">
                    <div class="col-xs-12"><strong>OBSERVACIONES: </strong></div>
                </div>
    <div class="row">
        <div class="col-xs-12">' . $value['observaciones'] . '</div>
    </div>
    
    <hr style="border-top: 4px solid #911714;">';
    if($credito == 1):
    $mensaje .= '<div class="row">
        <div class="col-xs-12"><h4>REQUISITOS DE SOLICITUD DE CRÉDITO</h4></div>
        <div class="col-xs-8">
            <ol>
                
                <li>Fotocopia cédula de ciudadanía (deudor/cónyugue)</li>
                <li>Fotocopia del certificado de votación (deudor/cónyugue)</li>
                <li>Fotocopia de pago impuesto predial (si posee bienes inmuebles)</li>
                <li>Certificado de ingreso (deudor/cónyugue)</li>
                <li>Fotocopia de la matrícula del vehículo (si posee vehículo)</li>
                <li>Certificados bancarios o fotocopias de estados de cuenta</li>
                <li>Recibo de pago de luz, agua o teléfono</li>
                <li>En caso de poseer negocio propio: certificado de sus proveedores para justificar sus ingresos 
                <ol>
                <li>Copia de RUC</li>
                <li>Declaraciones de IVA</li>
                </ol>
                </li>
                
            </ol>

        </div>';
    else:
        $mensaje .= '<div class="row">
        <div class="col-xs-12"><h4>REQUISITOS PARA VENTA AL CONTADO</h4></div>
        <div class="col-xs-8">
            <ol>
                
                <li>Fotocopia cédula de ciudadanía </li>
                <li>Fotocopia del certificado de votación</li>
                <li>Recibo de pago de luz, agua o teléfono</li>
            </ol>

        </div>';
    endif;
    $mensaje .= '</div>
    <div class="row"><div class="col-xs-8"><strong>NOTA: </strong>Si no posee bienes o el vehículo es para trabajo es indispensable garante.</div></div>
    <div class="row"><div class="col-xs-8"><em>Proforma válida por 48 horas, precios sujetos a cambios sin previo aviso</em></div></div>
</div>';
    ?>
<?php endforeach; ?>
<?php echo $mensaje; ?>