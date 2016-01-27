<div class="form">
    <h4>Filtros de búsqueda </h4>
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
            <label for="">Fecha 1</label>
            <input type="text" name="GI[fecha1]" class="form-control" id="fecha-range1" value="<?= $varView['fecha_inicial_actual'].' - '.$varView['fecha_actual'] ?>"/>
        </div>
        <div class="col-md-6">
            <label for="">Fecha 2</label>
            <input type="text" name="GI[fecha2]" class="form-control" id="fecha-range2" value="<?= $varView['fecha_inicial_anterior'].' - '.$varView['fecha_anterior'] ?>"/>
        </div>
    </div>

    <!-- FILTRO MODELOS -->
    <div class="row">
        <div class="col-md-12">
            <label for="">Modelo</label>
            <div class="panel panel-default">
                <div class="panel-body">                    
                    <?php
                        $activos = array();                                
                        foreach ($varView['modelos_car'] as $key => $value) {
                            $checked = '';
                            if ($lista_datos) {
                                if (in_array($value['id_modelos'], $lista_datos[0]['modelos'])) {
                                    $activos[] = $value['id_modelos'];
                                    $checked = 'checked';
                                } 
                            }                                         
                            echo '<div class="col-md-4">
                                        <div class="checkbox contcheck">
                                            <label>
                                                <input class="checkboxmain" type="checkbox" value="'.$value['id_modelos'].'" name="modelo[]" id="cc'.$value['id_modelos'].'" '.$checked.'>
                                                '.$value['nombre_modelo'].'
                                            </label>
                                            <div id="result" class="result"></div>
                                        </div>
                                    </div>';                                             
                         }                         
                    ?> 
                </div>
            </div>
        </div>
    </div>

    <!-- FILTRO CONCESIONARIOS -->
    <?php if ($varView['cargo_id'] == 69): ?>
        <div class="row">
            <div class="col-md-6">
                <label for="">Concesionarios</label>
                <select name="GI[concesionario]" id="GestionInformacionConcesionario" class="form-control">
                    <option value="">--Seleccione Concesionario--</option>
                    <?php
                    foreach ($varView['lista_conce'] as $value) {
                        echo '<option value="' . $value['dealer_id'] . '"';
                        if($value['dealer_id'] == $varView['$concesionario']){
                            echo 'selected';
                        }                        
                        echo'>' . $value['nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    <?php endif; ?>

    <!-- FILTRO ASESORES -->
    <?php if ($varView['cargo_id'] == 69 || $varView['cargo_id'] == 70): ?>
        <?php if ($varView['cargo_id'] == 70):?>
            <input type="hidden"  name="GI[concesionario]" id="GestionInformacionConcesionario" class="form-control" value="<?= $varView['dealer_id'] ?>"/>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6">
                <label for="">Asesor</label>
                <select name="GI[responsable]" id="GestionDiariaresponsable" class="form-control">
                    <option value="">--Seleccione--</option>
                </select>
            </div>
        </div>
    <?php endif; ?>

    <!-- TRIGER -->
    <div class="row buttons">
        <div class="col-md-6">
            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>