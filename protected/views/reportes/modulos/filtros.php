<div class="form">
    <h4>Filtros de búsqueda </h4>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gestion-nueva-cotizacion-form',
        'method' => 'get',
        'action' => Yii::app()->createUrl('gestionInformacion/reportes'),
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            //'onsubmit' => "return false;", /* Disable normal form submit */
            'onkeypress' => " if(event.keyCode == 13){ send(); } ", /* Do ajax call when user presses enter key */
            'class' => 'form-horizontal form-search',
            'autocomplete' => 'off'
        ),
    ));
    
    ?>

    <div class="row">
        <div class="col-md-6">
            <label for="">Fecha 1</label>
            <input type="text" name="GestionInformacion[fecha1]" class="form-control" id="fecha-range1"/>
        </div>
        <div class="col-md-6">
            <label for="">Fecha 2</label>
            <input type="text" name="GestionInformacion[fecha2]" class="form-control" id="fecha-range2"/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label for="">Modelo</label>
            <div class="panel panel-default">
                <div class="panel-body">                    
                    <!--Filtro modelos y versioness-->
                    <?php
                        $activos = array();                                
                        foreach ($modelos_car as $key => $value) {
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
                    <!--FIN Filtro modelos y versiones-->                                  
                </div>
            </div>
        </div>
    </div>
    <?php
    $cargo_id = (int) Yii::app()->user->getState('cargo_id');
    if ($cargo_id == 70):
        ?>
        <div class="row">
            <div class="col-md-6">
                <label for="">Asesor</label>
                <?php echo $form->dropDownList($mod, 'responsable', $usu, array('class' => 'form-control', 'empty' => 'Seleccione un responsable')); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php
    if ($cargo_id == 69):
        ?>
        <div class="row">
            <div class="col-md-6">
                <label for="">Concesionarios</label>
                <?php
                $info = new GestionInformacion;

                $con = Yii::app()->db;
                $sql = "SELECT * FROM gr_concesionarios WHERE id_grupo = {$grupo_id} ORDER BY nombre ASC";
                $requestr1 = $con->createCommand($sql);
                $requestr1 = $requestr1->queryAll();

                //$conc = CHtml::listData(GrConcesionarios::model()->findAll($criteria2), "id_grupo", "nombre");
                ?>
                <?php //echo $form->dropDownList($info, 'concesionario', $conc, array('class' => 'form-control', 'empty' => 'Seleccione un concesionario')); ?>
                <select name="GestionInformacion[concesionario]" id="GestionInformacionConcesionario" class="form-control">
                    <option value="">--Seleccione Concesionario--</option>
                    <?php
                    foreach ($requestr1 as $value) {
                        echo '<option value="' . $value['dealer_id'] . '">' . $value['nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label for="">Asesor</label>
                <select name="GestionDiaria[responsable]" id="GestionDiariaresponsable" class="form-control">
                    <option value="">--Seleccione--</option>
                </select>
            </div>
        </div>
    <?php endif; ?>
    <div class="row buttons">
        <div class="col-md-6">
            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>

            <?php
            if($cargo_id = 71){
                $user_valor = 1;
            }elseif($cargo_id = 70){
                $user_valor = 2;
            }elseif($cargo_id = 69){
                $user_valor = 3;
            }    
            echo '<input type="hidden" name="GestionInformacion[tipousuario]" id="GestionInformaciontipousuario" accept="" value="'.$user_valor.'" />';
            ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>