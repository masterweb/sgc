<div class="form">
    <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'gestion-nueva-cotizacion-form',
            'method' => 'get',
            'action' => Yii::app()->createUrl('Reportes/inicio'),
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'class' => 'form-horizontal form-search',
                'autocomplete' => 'off'
            ),
        ));
    ?>

    <!-- FILTRO FECHAS -->
    <div class="row">
        <div class="col-md-6">
            <label for="">Fecha Inicial</label>
            <input type="text" name="GI[fecha1]" class="form-control" id="fecha-range1" value="<?= $varView['fecha_inicial_actual'].' - '.$varView['fecha_actual'] ?>"/>
            <i class="fa fa-calendar glyphicon glyphicon-calendar cal_out"></i>
        </div>
        <div class="col-md-6">
            <label for="">Fecha Final</label>
            <input type="text" name="GI[fecha2]" class="form-control fecha_rep" id="fecha-range2" value="<?= $varView['fecha_inicial_anterior'].' - '.$varView['fecha_anterior'] ?>"/>
            <i class="fa fa-calendar glyphicon glyphicon-calendar cal_out"></i>
        </div>
    </div>

    <div class="row">

    <!-- FILTRO GRUPOS O PROVINCIA-->
    <?php if ($varView['AEKIA'] == true): ?>
        <div class="row text-center">
            <h4>Seleccione el tipo de busqueda</h4>
            <label class="radio-inline"><input type="radio" name="GI[tipo]" value="general" class="tipo_busqueda"
            <?php if($varView['checked_ge'] == true){echo 'checked';} ?>
            >General</label>
            <label class="radio-inline"><input type="radio" name="GI[tipo]" value="usados" class="tipo_busqueda"
            <?php if($varView['checked_us'] == true){echo 'checked';} ?>
            >Usados</label>
            <label class="radio-inline"><input type="radio" name="GI[tipo]" value="bdc" class="tipo_busqueda"
            <?php if($varView['checked_bdc'] == true){echo 'checked';} ?>
            >BDC</label>
            <label class="radio-inline"><input type="radio" name="GI[tipo]" value="exonerados" class="tipo_busqueda"
            <?php if($varView['checked_ex'] == true){echo 'checked';} ?>
            >Exonerados</label>
            <label class="radio-inline"><input type="radio" name="GI[tipo]" value="traficoacumulado" class="tipo_busqueda"
            <?php if($varView['checked_ta'] == true){echo 'checked';} ?>
            >Tráfico Histórico hasta el 2015</label>
            <hr/>
        </div>
        <div id="trafico_todo">
             
            <div class="row text-center">
                <!--h4>Por</h4-->
                <label class="radio-inline"><input type="radio" name="GI[tipo_t]" value="grupos" class="tipo_busqueda" 
                <?php if($varView['checked_g'] == true){echo 'checked';} ?>
                >Por Grupos</label>
                <label class="radio-inline"><input type="radio" name="GI[tipo_t]" value="provincias" class="tipo_busqueda"
                <?php if($varView['checked_p'] == true){echo 'checked';} ?>
                >Por Provincias</label>
                <hr/>
            </div>
            <!-- PROVINCIAS -->
            <div class="col-md-6 cont_prov">
                <label for="">Provincias</label>
                <select name="GI[provincias]" id="GestionInformacionProvincias" class="form-control">
                    <option value="">--Seleccione Provincias--</option>
                    <?php
                    if($varView['lista_provincias']){
                        foreach ($varView['lista_provincias'] as $value) {
                            echo '<option value="' . $value['id_provincia'] . '"';

                            if($value['id_provincia'] == $varView['id_provincia']){
                                echo 'selected';
                            }                       
                            echo'>' . $value['nombre'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        
            <!-- GRUPOS -->
            <div class="col-md-6 cont_grup">
                <label for="">Grupos</label>
                <select name="GI[grupo]" id="GestionInformacionGrupo" class="form-control">
                    <option value="">--Seleccione Grupo--</option>
                    <?php
                    if($varView['lista_grupo']){
                        foreach ($varView['lista_grupo'] as $value) {
                            echo '<option value="' . $value['id'] . '"';
                            if($value['id'] == $varView['id_grupo']){
                                echo 'selected';
                            }                       
                            echo'>' . $value['nombre_grupo'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
    <?php endif; ?>

    <!-- FILTRO CONCESIONARIOS -->
    <?php if ($varView['cargo_id'] == 69 || $varView['AEKIA'] == true): ?>
        <div class="col-md-6">
            <label for="">Concesionarios</label>
            <select name="GI[concesionario]" id="GestionInformacionConcesionario" class="form-control">
                <option value="">--Seleccione Concesionario--</option>
                <?php
                if($varView['lista_conce'] != 'null'){
                    foreach ($varView['lista_conce'] as $value) {
                        if($value['nombre'] != 'TODOS'){
                            echo '<option value="' . $value['dealer_id'] . '"';
                            if($value['dealer_id'] == $varView['$concesionario']){
                                echo 'selected';
                            }                        
                            echo'>' . $value['nombre'] . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </div>
    <?php endif; ?>

    <!-- FILTRO ASESORES -->
    <?php if ($varView['cargo_id'] == 69 || $varView['cargo_id'] == 70 || $varView['AEKIA'] == true): ?>
        <?php 
        $usuariosBajos = array(71, 77, 75, 73 , 70, 76, 72, 69);
        if (in_array($varView['cargo_id'], $usuariosBajos)):?>
            <input type="hidden"  name="GI[concesionario]" id="GestionInformacionConcesionario" class="form-control" value="<?= $varView['dealer_id'] ?>"/>
        <?php endif; ?>
            <div class="col-md-6">
                <label for="">Asesor</label>
                <select name="GI[responsable]" id="GestionDiariaresponsable" class="form-control">
                    <option value="">--Seleccione--</option>
                </select>
            </div>
    <?php endif; ?>
    <div id="traficoGeneral">
        <!-- FILTRO MODELOS -->
        <div class="row">
            <div class="col-md-12">
                <label for="">Modelos</label>
                <div class="panel panel-default">
                    <div class="panel-body modelos_filtros">                   
                        <?= $varView['filtro_modelos'] ?> 
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- #traficogeneral fin-->
    <div id="traficousados">        
        <!-- FILTRO MODELOS -->
        <div class="row">
            <div class="col-md-12">
                <label for="">Modelos Usados</label>
                <div class="panel panel-default">
                    <div class="panel-body modelos_filtros_usados">                   
                        <?= $varView['filtro_modelos_us'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- #traficousados fin-->
    <div id="traficobdc">
        <!-- FILTRO MODELOS -->
        <div class="row">
            <div class="col-md-12">
                <label for="">Modelos BDC</label>
                <div class="panel panel-default">
                    <div class="panel-body modelos_filtros">                   
                        <?= $varView['filtro_modelos'] ?> 
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- #traficoBDC fin-->
    <div id="traficoexonerados">
        <!-- FILTRO MODELOS -->
        <div class="row">
            <div class="col-md-12">
                <label for="">Modelos Exonerados</label>
                <div class="panel panel-default">
                    <div class="panel-body modelos_filtros">                   
                        <?= $varView['filtro_modelos'] ?> 
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- #traficoexonerados fin-->
    <!-- TRIGER -->
<div class="row buttons">
    <div class="col-md-6">
        <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
    </div>
</div>
<?php if ($varView['AEKIA'] == true): ?>    
</div><!-- #trafico-todo fin-->
<?php endif; ?>
</div>

<?php $this->endWidget(); ?>

<?php if ($varView['AEKIA'] == true): ?>
<?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gestion-nueva-cotizacion-form',
        'method' => 'get',
        'action' => Yii::app()->createUrl('Reportes/inicio'),
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-horizontal form-search',
            'autocomplete' => 'off'
        ),
    ));
?>
<div id="traficoacumulado"> <!-- #traficoacumulado fin-->
    <div class="row text-center">
        <!--h4>Seleccione el tipo de busqueda de Tráfico Histórico</h4-->
        <label class="radio-inline"><input type="radio" name="TA[tipo]" value="TA_grupos" id="TA_grupos" class="tipo_busqueda_TA" 
        <?php if($varView['TAchecked_gp'] == 'g'){echo 'checked';} ?>>Por Grupos</label>
        <label class="radio-inline"><input type="radio" name="TA[tipo]" value="TA_provincias" id="TA_provincias" class="tipo_busqueda_TA"
        <?php if($varView['TAchecked_gp'] == 'p'){echo 'checked';} ?>>Por Provincias</label>
        <hr/>
    </div>
    <div class="row">
        <input type="hidden"  name="GI[traficoacumulado]" id="traficoacumuladoControl" value="false"/>
        <?= $varView['traficoAcumulado']['ini_filtros']['provincia'] ?>
        <?= $varView['traficoAcumulado']['ini_filtros']['grupo'] ?>
        <div class="col-md-6" id="cont_TAconcesionarios">
            <label for="">Concesionarios</label>
            <select name="TA[concesionarios]" id="TAconcesionarios" class="form-control" required>
                <option value="">-- Concesionarios --</option>
            </select>
        </div>
    </div>
    <div class="row">
        <?= $varView['traficoAcumulado']['ini_filtros']['modelo'] ?>
    </div>
    <!-- TRIGER -->
    <div class="row buttons">
        <div class="col-md-6">
            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
        </div>
    </div>
</div> <!-- #traficoacumulado fin-->
<?php $this->endWidget(); ?>
<?php endif; ?>
</div>