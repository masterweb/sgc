<?php
//echo '<pre>';
//print_r($data);
//echo '</pre>';
//echo 'num proforma: '.$id_hoja;
$fin = $this->getTipoExoInfo($id_informacion);
//echo '<strong>'.$fin.'</strong><br>';
$id_vehiculo = $_GET['id_vehiculo'];
$id_asesor = Yii::app()->user->getId();
$emailAsesor = $this->getAsesorEmail($responsable_id);
$concesionarioid = $this->getConcesionarioDealerId($responsable_id);
//die('concesionario id: '.$concesionarioid);

$telefono = $this->getAsesorTelefono($responsable_id);
$celular = $this->getAsesorCelular($responsable_id);
$codigo_asesor = $this->getAsesorCodigo($responsable_id);
//echo $this->getResponsable($id_asesor);
$mpdf = Yii::app()->ePdf->mpdf();
$codigoconcesionario = $this->getCodigoConcesionario($concesionarioid);
?>
<style>
    /*.container{width: 800px;}*/
    .row{margin-bottom: 1px !important;margin-left: 20px !important;margin-right: 20px !important;}
    h4{font-weight: bold;margin-top: 5px !important;}
    hr{margin-top: 3px !important;margin-bottom: 3px !important;}
    .target{font-size: 12px !important;}
    .target strong{color:#911714 !important;}
    .target p{margin: 0 !important;}
    .container{margin-left: -3% !important;}
    .title .col-xs-3{width: 24% !important;}
    .img-logo{position: relative; right: 150px;}
</style>

<div class="container cont-print">
    <?php foreach ($data as $key => $value) : ?>
        <?php
        $credito = $this->getFinanciamiento($id_informacion,$id_vehiculo);
        // echo 'credito: '.$credito;
        ?>
        <div class="row title">
            <div class="col-xs-3"><img class="img-logo" src="<?php echo Yii::app()->request->baseUrl ?>/images/logo_pdf2.png" alt=""></div>
            <div class="col-xs-8" style="border-left:1px solid #888890;">
                <h4><?php echo strtoupper($this->getNombreConcesionario($concesionarioid)); ?></h4>
                <div class="target">

                    <div class="col-xs-12"><p><?php echo $nombre_responsable; ?></p></div>
                    <div class="col-xs-12"><strong>Dirección: <?php echo $this->getConcesionarioDireccion($id_asesor); ?></strong></div>
                    <div class="col-xs-5"><p><strong>T </strong> <?php echo $telefono; ?></p></div>
                    <div class="col-xs-5"><p><strong>M </strong> <?php echo $celular; ?></p></div>
                    <div class="col-xs-5"><p><strong>E </strong><?php echo $emailAsesor; ?> </p></div>
                    <div class="col-xs-5"><p><strong>W </strong> www.kia.com.ec</p></div>
                </div>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-5"><h5><strong>PROFORMA No. <?php echo $codigoconcesionario; ?>-P-<?php echo $codigo_asesor; ?>-<?php echo $id_hoja; ?></strong></h5></div>
        <div class="col-xs-4 col-xs-offset-3"><h5><strong>R.U.C. <?php echo $ruc; ?></h5></strong></div>
    </div>
    <div class="row">
        <div class="col-xs-4"><strong>CLIENTE: </strong><?php echo $value['nombres']; ?> <?php echo $value['apellidos']; ?></div>
        <div class="col-xs-3"><strong>FECHA: </strong><?php echo date("d") . "/" . date("m") . "/" . date("Y"); ?></div>
    </div>
    <div class="row">
        <div class="col-xs-12"><strong>DIRECCIÓN: </strong><?php echo $value['direccion']; ?></div>
    </div>
    <div class="row">
        <div class="col-xs-4"><strong>TELÉFONO: </strong><?php echo $value['telefono_casa']; ?></div>
        <div class="col-xs-4"><strong>CELULAR: </strong><?php echo $value['celular']; ?></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><strong>MODELO: </strong><?php echo $this->getModel($value['modelo']); ?></div>
        <div class="col-xs-7"><strong>VERSIÓN: </strong><?php echo $this->getVersion($value['version']); ?></div>
        <!--div class="col-xs-3"><strong>AÑO: </strong>2015</div-->
    </div>
    <div class="row">
        <div class="col-xs-6"><strong>FORMA DE PAGO: </strong><?php echo $value['forma_pago']; ?></div>
    </div>
    <?php if ($credito == 1): ?>    
        <div class="row"><div class="col-xs-3"><h4 style="color:#911714;">FINANCIAMIENTO</h4></div></div>
        <div class="row"><div class="col-md-12">Opción 1<hr style="color:#911714;"></div></div>
        <?php
        $strAcc = $value['accesorios'];
        $paramAutos = explode('@', $strAcc);
        //$co = count($paramAutos);
        //echo $co;

        foreach ($paramAutos as $val) {
            $strD = explode('-', $val);
            $strinAcc .= $strD[1] . ',';
        }
        $strinAcc = trim(substr($strinAcc, 0, -1));
        $precioNormal = $this->getPrecioNormal($value['version']);
        $precioAccesorios = $value['precio_vehiculo'] - $precioNormal;
        ?>

        <div class="row">
            <div class="col-xs-7"><strong>PRECIO DE VENTA VEHÍCULO <?php echo $fin; ?> </strong> $ <?php echo number_format($precioNormal); ?></div>
        </div>
        <?php
        if ($fin == 'Exonerado Diplomatico' || $fin == 'Exonerado Conadis') {
            $pr = $this->getTipoExoPorcentaje($id_informacion);
            echo '<div class="row">
            <div class="col-xs-7"><strong>PORCENTAJE DE BENEFICIO: ' . $pr * 10 . '% </strong></div></div>';
        }
        ?>

        <div class="row">
            <div class="col-xs-12"><strong>ACCESORIOS: </strong><?php echo $strinAcc; ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12"><strong>ACCESORIOS TOTAL: </strong> $ <?php echo number_format($precioAccesorios); ?></div>    
        </div>
        <div class="row">
            <div class="col-xs-8"><strong>PRECIO DE VENTA INCLUÍDO ACCESORIOS (INC. I.V.A): </strong> $ <?php echo number_format($value['precio_vehiculo']); ?></div>

        </div>
        <div class="row">
            <div class="col-xs-4"><strong>SEGURO / AÑOS:</strong></div><div class="col-xs-4"> $ <?php echo number_format($value['seguro']); ?> / <?php echo $value['ts']; ?></div>
        </div>

        <div class="row">
            <div class="col-xs-4"><strong>TOTAL: </strong></div><div class="col-xs-4"> $ <?php echo number_format($value['precio_vehiculo']); ?></div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>VALOR DE ENTRADA: </strong></div><div class="col-xs-4"> $ <?php echo number_format($value['cuota_inicial']); ?></div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>SALDO A FINANCIAR: </strong></div><div class="col-xs-4"> $ <?php echo number_format($value['valor_financiamiento']); ?></div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>PLAZO MESES: </strong><?php echo $value['plazos']; ?></div><div class="col-xs-4"><strong>CUOTA MENSUAL APROX: </strong> $ <?php echo number_format($value['cuota_mensual']); ?></div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <strong>ENTIDAD FINANCIERA: </strong><?php echo $value['entidad_financiera']; ?></div>
        </div>
        <?php if ($value['observaciones'] != ''): ?>
            <div class="row">
                <div class="col-xs-12"><strong>OBSERVACIONES: </strong><?php echo $value['observaciones']; ?></div>
            </div>
        <?php endif; ?>
        <br />
    <?php else: ?>
        <div class="row"><div class="col-xs-6"><h4 style="color:#911714;">VENTA AL CONTADO</h4></div></div>
        <div class="row"><div class="col-md-12">Opción 1<hr style="color:#911714;"></div></div>

        <?php
        $paramAutos = explode('@', $value['accesorios']);
        $strinAcc = '';
        $valAcc = 0;
        foreach ($paramAutos as $val) {
            $strD = explode('-', $val);
            $strinAcc .= $strD[1] . ',';
            $valAcc += (int) $strD[0];
        }
        $strinAcc = substr($strinAcc, 0, -1);
        $precioNormal = $this->getPrecioNormal($value['version']);
        if (count($paramAutos) > 0) {
            $precioAccesorios = $value['precio_vehiculo'] - $precioNormal;
        } else {
            $precioAccesorios = 0;
        }
        ?>

        <div class="row">
            <div class="col-xs-9"><strong>PRECIO DE VENTA VEHÍCULO <?php echo strtoupper($fin); ?> </strong> $ <?php echo number_format($value['precio_normal']); ?></div>
        </div>
        <?php
        if ($fin == 'Exonerado Diplomatico' || $fin == 'Exonerado Conadis') {
            $pr = $this->getTipoExoPorcentaje($id_informacion);
            echo '<div class="row">
            <div class="col-xs-7"><strong>PORCENTAJE DE BENEFICIO: </strong>' . $pr * 10 . '% </div></div>';
        }
        ?>

        <div class="row">
            <div class="col-xs-12"><strong>ACCESORIOS: </strong><?php echo $strinAcc; ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12"><strong>ACCESORIOS TOTAL: </strong> $ <?php echo number_format($valAcc); ?></div>    
        </div>
        <div class="row">
            <div class="col-xs-8"><strong>PRECIO DE VENTA INCLUÍDO ACCESORIOS (INC. I.V.A): </strong> $ <?php echo number_format($value['precio_vehiculo']); ?></div>

        </div>
        <div class="row">
            <div class="col-xs-4"><strong>SEGURO / AÑOS:</strong></div><div class="col-xs-4"> $ <?php echo number_format($value['seguro']); ?> / <?php echo $value['ts'] ?> </div>
        </div>
        <div class="row">
            <div class="col-xs-4"><strong>TOTAL: </strong></div><div class="col-xs-4"> $ <?php echo number_format($value['precio_vehiculo']); ?></div>
        </div>
        <?php if ($value['observaciones'] != ''): ?>
            <div class="row">
                <div class="col-xs-12"><strong>OBSERVACIONES: </strong><?php echo $value['observaciones']; ?></div>
            </div>
        <?php endif; ?>
        <br />
    <?php endif; ?>
    <?php
    $mpdf->AddPage();
    $criteria = new CDbCriteria(array(
        'condition' => "id_financiamiento='{$value['id_financiamiento']}' AND status = 'ACTIVO'"
    ));
    $accesorios = GestionFinanciamientoOp::model()->count($criteria);
    //die('accesorios: '.$accesorios);
    if ($accesorios > 0) {
        $accs = GestionFinanciamientoOp::model()->findAll($criteria);
        $c = 2;
        foreach ($accs as $vals) {
            $paramAutos2 = explode('@', $vals['accesorios']);
            $strinAcc2 = '';
            foreach ($paramAutos2 as $val) {
                $strD2 = explode('-', $val);
                $strinAcc2 .= $strD2[1] . ',';
            }
            $strinAcc2 = substr($strinAcc2, 0, -1);
            $precioAccesorios2 = $vals['precio_vehiculo'] - $precioNormal;
            ?>
            <?php if ($credito == 1): ?>
                <div class="row"><div class="col-md-12">Opción <?php echo $c; ?><hr style="color:#911714;"></div></div>
                <div class="row">
                    <div class="col-xs-7"><strong>PRECIO DE VENTA VEHÍCULO (INC. I.V.A): </strong> $ <?php echo number_format($precioNormal); ?></div>

                </div>
                <div class="row">
                    <div class="col-xs-12"><strong>ACCESORIOS: </strong><?php echo $strinAcc2; ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-12"><strong>ACCESORIOS TOTAL: </strong> $ <?php echo number_format($precioAccesorios2); ?></div>    
                </div>
                <div class="row">
                    <div class="col-xs-8"><strong>PRECIO DE VENTA INCLUÍDO ACCESORIOS (INC. I.V.A): </strong> $ <?php echo number_format($vals['precio_vehiculo']); ?></div>

                </div>
                <div class="row">
                    <div class="col-xs-4"><strong>SEGURO / AÑOS:</strong></div><div class="col-xs-4"> $ <?php echo number_format($vals['seguro']); ?>/ <?php echo $vals['ts']; ?></div>
                </div>

                <div class="row">
                    <div class="col-xs-4"><strong>TOTAL: </strong></div><div class="col-xs-4"> $ <?php echo number_format($vals['precio_vehiculo']); ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-4"><strong>VALOR DE ENTRADA: </strong></div><div class="col-xs-4"> $ <?php echo number_format($vals['cuota_inicial']); ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-4"><strong>SALDO A FINANCIAR: </strong></div><div class="col-xs-4"> $ <?php echo number_format($vals['saldo_financiar']); ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-4"><strong>PLAZO MESES: </strong><?php echo $vals['plazos']; ?></div><div class="col-xs-4"><strong>CUOTA MENSUAL APROX: </strong> $ <?php echo number_format($vals['cuota_mensual']); ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <strong>ENTIDAD FINANCIERA: </strong><?php echo $vals['entidad_financiera']; ?></div>
                </div>
                <?php if ($vals['observaciones'] != ''): ?>
                    <div class="row">
                        <div class="col-xs-12"><strong>OBSERVACIONES: </strong><?php echo $vals['observaciones']; ?></div>
                    </div>
                <?php endif; ?>
                <br />
                <?php
                $c++;
            else:
                $paramAutosContado = explode('@', $vals['accesorios']);
                $valAcc = 0;
                foreach ($paramAutosContado as $valc) {
                    $strC = explode('-', $valc);
                    $valAcc += (int) $strC[0];
                }
                $precioAcceContado = $vals['precio_vehiculo'] - $valAcc;
                ?>
                <div class="row"><div class="col-md-12">Opción <?php echo $c; ?><hr style="color:#911714;"></div></div>
                <div class="row">
                    <div class="col-xs-7"><strong>PRECIO DE VENTA VEHÍCULO (INC. I.V.A): </strong> $ <?php echo number_format($precioNormal); ?></div>

                </div>
                <div class="row">
                    <div class="col-xs-12"><strong>ACCESORIOS: </strong><?php echo $strinAcc2; ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-12"><strong>ACCESORIOS TOTAL: </strong> $ <?php echo number_format($valAcc); ?></div>    
                </div>
                <div class="row">
                    <div class="col-xs-8"><strong>PRECIO DE VENTA INCLUÍDO ACCESORIOS (INC. I.V.A): </strong> $ <?php echo number_format($vals['precio_vehiculo']); ?></div>

                </div>
                <div class="row">
                    <div class="col-xs-4"><strong>SEGURO / AÑOS:</strong></div><div class="col-xs-4"> $ <?php echo number_format($vals['seguro']); ?>/ <?php echo $vals['ts']; ?></div>
                </div>

                <div class="row">
                    <div class="col-xs-4"><strong>TOTAL: </strong></div><div class="col-xs-4"> $ <?php echo number_format($vals['precio_vehiculo']); ?></div>
                </div>
                <?php if ($vals['observaciones'] != ''): ?>
                    <div class="row">
                        <div class="col-xs-12"><strong>OBSERVACIONES: </strong><?php echo $vals['observaciones']; ?></div>
                    </div>
                <?php endif; ?>
                <br />
                <?php
                $c++;
            endif;
        }
    }
    ?>
    <?php if ($credito == 1): ?>
        <br />
        <div class="row">
            <div class="col-md-12"><hr /></div>
        </div>
        <div class="row">
            <div class="col-xs-12" style="text-align: justify;"><strong>NOTAS: </strong><br />
                * La proforma es referencial y sujeta a revisión y análisis por parte de nuestro Jefe de Crédito al momento de concretar la compra de su vehículo. Tiene una validez de 48 horas.
                Las especificaciones y precios pueden variar sin previo aviso. <br />
                * Valor de la cuota sujeta a revisión o confirmación por parte del asesor de crédito - 
                Cotización Referencial
            </div>
        </div>    
    <?php else: ?>
        <div class="row">
            <div class="col-md-12"><hr /></div>
        </div>
        <div class="row">
            <div class="col-xs-12" style="text-align: justify;"><strong>NOTAS: </strong><br />
                * La proforma es referencial y sujeta a revisión y análisis por parte de nuestro Jefe de Sucursal al momento de concretar la compra de su vehículo. Tiene una validez de 48 horas
                Las especificaciones y precios pueden variar sin previo aviso
                * Precios y/o observaciones sujetos a cambio sin previo aviso <br />
            </div>
        </div>
    <?php endif; ?>


    <div class="row"><div class="col-md-12"><hr style="border-top: 4px solid #911714;"></div></div>
    <?php if ($credito == 1): ?>
        <div class="row">
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
            </div>
        <?php else: ?>
            
        <?php endif; ?>    
        <?php if (isset($tipo_exonerado)) { ?>
        <div class="row">
            <div class="col-xs-12"><h4>PROCESO PARA VEHÍCULO <?php echo strtoupper($fin); ?></h4></div>
            <?php
            switch ($tipo_exonerado) {
                case 'Exonerado Taxi':
                    echo '<div class="col-xs-12">
                            <ol>
                                <li>Pertenecer mínimo un año a una cooperativa o compañía de transporte público, legalmente constituida y registrada en la ANT (Agencia Nacional de Tránsito). </li>
                                <li>Factura proforma emitida por su concesionario Kia Motors.</li>
                                <li>Obtención de Informe Técnico o Certificado Favorable en la ANT (Agencia Nacional de Tránsito).</li>
                                <li>Para vehículos salientes mayores a 10 años se requiere presentar el bono de chatarrización.</li>
                            </ol>
                        </div>';
                    break;
                case 'Exonerado Conadis':
                    echo '<div class="col-xs-12">'
                    . '<ol>
                        <li>Porcentaje de discapacidad superior al 40% .</li>

                        <li>Cédula y carnet de discapacidad actualizados.</li>

                        <li>Licencia de conducir F si va a conducir la persona con discapacidad.</li>

                        <li>Poder especial original en el caso de requerirlo.</li>

                        <li>Certificado único de calificación de discapacidad para importación de vehículos emitido por la DND (Dirección Nacional de Discapacidad del Ministerio de Salud Pública), mismo que tiene que ser transmitido en el buzón de discapacidades.</li>

                        <li>Acercarse al concesionario Kia de su preferencia para la adquisición del vehículo.</li>
                    </ol>'
                    . '</div>';
                    break;
                case 'Exonerado Diplomatico':
                    echo '<div class="col-xs-12">'
                    . '<strong>Diplomáticos:</strong><ol>

                            <li>Copia pasaporte diplomático (2 copias a color).</li>

                            <li>Copia Carné Diplomático (1 copia blanco y negro).</li>
                            </ol>

                            <strong>Organismos Internacionales:</strong>
                            <ol>
                            <li>Copia del convenio entre el Ministerio de Relaciones Exteriores y el Gobierno del País que pertenece el Organismo Internacional.</li>

                            <li>Copia a color de la Cédula o Pasaporte del Representante Legal del Organismo.</li>

                            <li>Nombramiento del Representante Legal.</li>

                            <li>Copia del RUC del Organismo Internacional.</li></ol>'
                    . '</div>';
                    break;
                default:
                    break;
            }
            ?> 
        </div>    
        <?php } ?>    
        <div class="row"><div class="col-xs-12"><strong>NOTA: </strong>Si no posee bienes o el vehículo es para trabajo es indispensable garante.</div></div>
        <div class="row"><div class="col-xs-12"><em>Proforma válida por 48 horas, precios sujetos a cambios sin previo aviso</em></div></div>
    </div>
    <?php
endforeach;
