<?php
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
$cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
//echo 'grupo id: '.$grupo_id;
$id_responsable = Yii::app()->user->getId();
?>
<?php if ($tipo_filtro == 'general'): ?>
    <?php
    $area_id = (int) Yii::app()->user->getState('area_id'); //echo $area_id;

    $cargo_id = (int) Yii::app()->user->getState('cargo_id');
    ?>
    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'casos-form',
            'method' => 'get',
            'action' => Yii::app()->createUrl($formaction),
            'enableAjaxValidation' => true,
            'htmlOptions' => array(
                'class' => 'form-horizontal form-search'
            ),
        ));
        ?>
        <h4>Búsqueda: (Seleccione solo uno de los filtros)</h4>
        <div class="row">
            <div class="col-md-6">
                <label for="GestionDiariafecha">Búsqueda General</label>
                <input type="text" name="GestionDiaria[general]" id="GestionDiaria_general" class="form-control"/>
            </div>
            <?php if ($cargo_id == 71 || $cargo_id == 70 || $cargo_id == 69 || $cargo_id == 46): ?>
                <div class="col-md-6">
                    <label for="">Categorización</label>
                    <select name="GestionDiaria[categorizacion]" class="form-control" id="gestion_diaria_categorizacion">
                        <option value="">--Seleccione categorización--</option>
                        <option value="Hot A (hasta 7 dias)">Hot A(hasta 7 dias)</option>
                        <option value="Hot B (hasta 15 dias)">Hot B(hasta 15 dias)</option>
                        <option value="Hot C (hasta 30 dias)">Hot C(hasta 30 dias)</option>
                        <option value="Warm (hasta 3 meses)">Warm(hasta 3 meses)</option>
                        <option value="Cold (hasta 6 meses)">Warm(hasta 6 meses)</option>
                        <option value="Very Cold(mas de 6 meses)">Very Cold(mas de 6 meses)</option>
                    </select>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label for="GestionNuevaCotizacion_fuente">Status</label>
                <select type="text" name="GestionDiaria[status]" class="form-control" id="gestion_diaria_status">
                    <option value="">--Seleccione status--</option>
                    <option value="PrimeraVisita">Primera Visita</option>
                    <option value="Cierre">Cierre</option>
                    <option value="Entrega">Entrega</option>
                    <option value="Vendido">Seguimiento-Paso 10</option>
                    <option value="Desiste">Desiste</option>
                    <?php if($cargo_id == 70 || $cargo_id == 71 || $tipo_seg == 'exhibicion' || $_GET['tipo_search'] == 'exhibicion' || $_GET['tipo_search'] == 'exh'){ ?>
                    <option value="qk">Campaña Quiero un Kia</option>
                    <option value="qktd">Campaña Quiero un Kia TD</option>
                    <?php } ?>
                    <?php if($cargo_id == 85 || $cargo_id == 86 || $cargo_adicional == 86 || $cargo_adicional == 85): ?>
                    <option value="Web">Web</option>
                    
                    <?php endif; ?>
                </select>
            </div>
            <?php if ($cargo_id == 70 || $cargo_id == 72 || $cargo_id == 85): ?>
                <?php
                // BUSQUEDA DE RESPONSABLE DE VENTAS CARGO ID 71 Y EL DEALER ID -> concesionarioid
                $mod = new GestionDiaria;
                $cre = new CDbCriteria();
                //echo $id_responsable = Yii::app()->user->getId().'<br>';
                //echo ('dealer id: '.$dealer_id);

                switch ($cargo_id) {
                    case 70: // JEFE SUCURSAL
                        $array_dealers = $this->getResponsablesVariosConc();
                        if(count($array_dealers) == 0){
                            $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable);
                        }
                        $dealerList = implode(', ', $array_dealers);
                        $cre->condition = " cargo_id IN (70,71) AND dealers_id IN ({$dealerList}) ";
                        break;
                    case 72: // JEFE BDC EXONERADOS
                        $array_dealers = $this->getDealerGrupoConc($grupo_id);
                        $dealerList = implode(',', $array_dealers);
                        if ($tipo == 'exo') {
                            $cre->condition = " cargo_id = 75 AND dealers_id IN ({$dealerList}) ";
                        }
                        if ($tipo == 'bdc') {
                            $cre->condition = " cargo_id = 73 AND dealers_id IN ({$dealerList}) ";
                        }

                        break;
                    case 85: // JEFE VENTAS EXTERNAS
                        $array_dealers = $this->getDealerGrupoConc($grupo_id);
                        $dealerList = implode(', ', $array_dealers);
                        $cre->condition = " cargo_id = 86 AND dealers_id IN ({$dealerList}) ";
                        break;    

                    default:
                        break;
                }
//        $cre->condition = " cargo_id =71 AND dealers_id = {$dealer_id} ";
//                echo '<pre>';
//                print_r($cre);
//                echo '</pre>';
//                die();
                $cre->order = " nombres ASC";
                $usu = CHtml::listData(Usuarios::model()->findAll($cre), "id", "fullname");
                ?>
                <div class="col-md-6">
                    <label for="">Responsable</label>
                    <?php echo $form->dropDownList($mod, 'responsable', $usu, array('class' => 'form-control', 'empty' => 'Seleccione un responsable')); ?>

                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label for="">Fecha de Registro</label>
                <input type="text" name="GestionDiaria[fecha]" id="fecha-range" class="form-control"/>
            </div>
            <div class="col-md-6">
                <label for="">Seguimiento</label>
                <select name="GestionDiaria[seguimiento]" id="GestionDiaria_seguimiento" class="form-control">
                    <option value="">--Seleccione tipo--</option>
                    <option value="1">Hoy</option>
                    <option value="2">Vacío</option>
                    <option value="3">Rango de Fecha</option>
                </select>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6" id="rango_fecha" style="display: none;">
                <label for="">Rango de Fecha</label>
                <input type="text" name="GestionDiaria[rango_fecha]" id="rango_fecha_seguimiento" class="form-control"/>
            </div>
        </div>
        <!--    <div class="row">
                <div class="col-md-6">
                    <label for="">Fuente</label>
                    <select name="GestionDiaria[fuente]" id="GestionDiaria_fuente" class="form-control">
                        <option value="">--Seleccione fuente--</option>
                        <option value="showroom">Tráfico</option>
                        <option value="prospeccion">Prospección</option>
                        <option value="exhibicion">Exhibición</option>
                        <option value="exonerados">Exonerados</option>
                        <option value="web">Web</option>
                    </select>
                </div>
            </div>-->

        <?php if ($cargo_id == 69 || $cargo_id == 46 || $area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) { ?>
            <?php $select = $this->getSelectProfile($cargo_id, $dealer_id); ?>
            <hr />
            <h4>Búsqueda:</h4>
            <div class="row">
                <div class="col-md-6">
                    <label for="">Grupo</label>
                    <select name="GestionDiaria[grupo]" id="GestionDiaria_grupo" class="form-control" <?php echo $cargo_id == 69 ? 'disabled="true"' : ''; ?> >
                        <?php echo $select[0]; ?>
                    </select>
                </div>
                <?php //if(empty($tipo_seg)): ?>
                <div class="col-md-6">
                    <label for="">Concesionario</label>
                    <select name="GestionDiaria[concesionario]" id="GestionDiaria_concesionario" class="form-control">
                        <?php echo $select[1]; ?>
                    </select>
                </div>
                <?php //endif; ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="">Responsable</label>
                    <select name="GestionDiaria[responsable]" id="GestionDiaria_responsable" class="form-control">
                        <option value="">--Seleccione responsable--</option>
                    </select>
                </div>    
            </div>
            <!--hr />
            <div class="row">
                <div class="col-md-6">
                    <label for="">Provincia</label>
                    <select name="GestionDiaria[provincia]" id="GestionDiaria_provincia" class="form-control">
                        <option value="">---Seleccione una provincia---</option>
                        <option value="1">Azuay</option>
                        <option value="5">Chimborazo</option>
                        <option value="7">El Oro</option>
                        <option value="8">Esmeraldas</option>
                        <option value="10">Guayas</option>
                        <option value="11">Imbabura</option>
                        <option value="12">Loja</option>
                        <option value="13">Los Ríos</option>
                        <option value="14">Manabí</option>
                        <option value="16">Napo</option>
                        <option value="18">Pastaza</option>
                        <option value="19">Pichincha</option>
                        <option value="21">Tsachilas</option>
                        <option value="23">Tungurahua</option>
                    </select>      
                </div>   
            </div-->
        <?php } ?>
        <div class="row">
            <div class="col-md-6">
                <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
                <input type="hidden" name="busqueda_general" id="busqueda_general" value="0" />
                <input type="hidden" name="categorizacion" id="categorizacion" value="0" />
                <input type="hidden" name="status" id="status" value="0" />
                <input type="hidden" name="responsable" id="responsable" value="0" />
                <input type="hidden" name="fecha" id="fecha" value="0" />
                <input type="hidden" name="search_type" id="search_type" value="0" />
                <input type="hidden" name="seguimiento_rgd" id="seguimiento_rgd" value="0" />
                <input type="hidden" name="fecha_seguimiento" id="fecha_seguimiento" value="0" />
                <input type="hidden" name="grupo" id="grupo" value="0" />
                <input type="hidden" name="concesionario" id="concesionario" value="0" />
                <?php
                //if(isset($tipo_seg)){
                    echo '<input type="hidden" name="tipo_search" value="'.$tipo_seg.'" id = "tipo_search"/>';
                //}
                ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
        <div class="row">
            <?php if ($cargo_id == 70 || $cargo_id == 46): ?>
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'casos-form',
                    'method' => 'get',
                    'action' => Yii::app()->createUrl('gestionInformacion/seguimientoexcel'),
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal form-search'
                    ),
                ));
                ?>
                <div class="col-md-6">
                    <input type="submit" name="" id="" value="Descargar Excel" class="btn btn-warning"/>
                    <input type="hidden" value="<?php print_r($_GET); ?>" name="Seguimiento[search]" />
                    <input type="hidden" name="GestionDiaria2[general]" id="GestionDiaria2_general" value="<?php echo isset($_GET["GestionDiaria"]['general']) ? $_GET["GestionDiaria"]['general'] : "" ?>"/>
                    <input type="hidden" name="GestionDiaria2[categorizacion]" id="" value="<?php echo isset($_GET["GestionDiaria"]['categorizacion']) ? $_GET["GestionDiaria"]['categorizacion'] : "" ?>">
                    <input type="hidden" name="GestionDiaria2[status]" id="" value="<?php echo isset($_GET["GestionDiaria"]['status']) ? $_GET["GestionDiaria"]['status'] : "" ?>">
                    <input type="hidden" name="GestionDiaria2[responsable]" id="" value="<?php echo isset($_GET["GestionDiaria"]['responsable']) ? $_GET["GestionDiaria"]['responsable'] : "" ?>">
                    <input type="hidden" name="GestionDiaria2[fecha]" id="" value="<?php echo isset($_GET["GestionDiaria"]['fecha']) ? $_GET["GestionDiaria"]['fecha'] : "" ?>">
                    <input type="hidden" name="GestionDiaria2[fuente]" id="" value="<?php echo isset($_GET["GestionDiaria"]['fuente']) ? $_GET["GestionDiaria"]['fuente'] : "" ?>">
                    <input type="hidden" name="GestionDiaria2[grupo]" id="" value="<?php echo isset($_GET["GestionDiaria"]['grupo']) ? $_GET["GestionDiaria"]['grupo'] : "" ?>">
                    <input type="hidden" name="GestionDiaria2[concesionario]" id="" value="<?php echo isset($_GET["GestionDiaria"]['concesionario']) ? $_GET["GestionDiaria"]['concesionario'] : "" ?>">
                    <input type="hidden" name="GestionDiaria2[seguimiento]" id="" value="<?php echo isset($_GET["GestionDiaria"]['seguimiento']) ? $_GET["GestionDiaria"]['seguimiento'] : "" ?>">
                    <input type="hidden" name="GestionDiaria2[rango_fecha]" id="" value="<?php echo isset($_GET["GestionDiaria"]['rango_fecha']) ? $_GET["GestionDiaria"]['rango_fecha'] : "" ?>">
                    <input type="hidden" name="busqueda_general2" id="busqueda_general2" value="0" />
                    <input type="hidden" name="categorizacion2" id="categorizacion2" value="<?php echo isset($_GET["categorizacion"]) ? $_GET["categorizacion"] : "" ?>" />
                    <input type="hidden" name="status2" id="status2" value="<?php echo isset($_GET["status"]) ? $_GET["status"] : "" ?>" />
                    <input type="hidden" name="responsable2" id="responsable2" value="<?php echo isset($_GET["responsable"]) ? $_GET["responsable"] : "" ?>" />
                    <input type="hidden" name="fecha2" id="fecha2" value="<?php echo isset($_GET["fecha"]) ? $_GET["fecha"] : "" ?>" />
                    <input type="hidden" name="search_type2" id="search_type2" value="0" />
                    <input type="hidden" name="seguimiento_rgd2" id="seguimiento_rgd2" value="<?php echo isset($_GET["seguimiento_rgd"]) ? $_GET["seguimiento_rgd"] : "" ?>" />
                    <input type="hidden" name="fecha_seguimiento2" id="fecha_seguimiento2" value="<?php echo isset($_GET["fecha_seguimiento"]) ? $_GET["fecha_seguimiento"] : "" ?>" />
                    
                </div>
                <?php $this->endWidget(); ?>
            <?php endif; ?>   
        </div>
    </div>
<?php else: ?>


    <!--SOLUCIÓN TEMPORAL DE FILTRO PARA USADOS POR REUNIÓN-->
    <div class="form">
        <h4>Búsqueda: (Seleccione solo uno de los filtros)</h4>
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
            <?php if ($cargo_id == 76 || $cargo_id == 69): ?>
                <div class="col-md-6">
                    <label for="">Responsable</label>
                    <select name="GestionSolicitudCredito[responsable]" id="" class="form-control">
                        <?php echo $this->getAsesoresByGrupo($grupo_id); ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>


        <div class="row">
            <div class="col-md-6">
                <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
                
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
<?php endif; ?>