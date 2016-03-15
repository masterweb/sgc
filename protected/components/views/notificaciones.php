<div class="cont_notificaciones">
    <?php
    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador`
    $responsable_id = Yii::app()->user->getId();
    $sql = "SELECT * FROM gestion_notificaciones WHERE leido = 'UNREAD' AND id_asesor = {$responsable_id}";
    $notificaciones = Yii::app()->db->createCommand($sql)->query();
    $count = count($notificaciones);
    $fecha_actual = date("Y/m/d");
    ?>
    <div class="cont_tl_notificaciones" onclick="verN(<?php echo $count; ?>)">
        <?php
        if ($count > 0):
            ?>
            <div class="no_notificaciones"><?php echo $count; ?></div>
        <?php else: ?>
            <div class="no_notificaciones no-not"><?php echo $count; ?></div>
        <?php endif; ?>

        <div class="tl_notificaciones">Notificaciones</div>
        <div class="clear"></div>
    </div>
    <div id="lNotificaciones" style="display: none;">
        <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#seguimiento" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Seguimiento</a></li>
                <li role="presentation"><a href="#solicitudes" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Crédito</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="seguimiento" aria-labelledby="abierto-tab">
                    <?php
                    $abierto = "";
                    //$abiertoSQL = "SELECT gt.*, ga.agendamiento FROM gestion_notificaciones gt "
                    //        . "INNER JOIN gestion_agendamiento ga ON ga.id_informacion = gt.id_informacion"
                    //        . " WHERE gt.leido = 'UNREAD' AND gt.tipo = 1";

                    $abiertoSQL = "SELECT gn.*, ga.agendamiento, gi.nombres, gi.apellidos FROM gestion_notificaciones gn 
                    INNER JOIN gestion_agendamiento ga ON ga.id = gn.id_agendamiento 
                    INNER JOIN gestion_informacion gi on gi.id = gn.id_informacion 
                    WHERE gn.leido = 'UNREAD' AND gn.tipo = 1 
                    AND gn.id_asesor = {$responsable_id} AND DATE(ga.agendamiento) = '$fecha_actual' ";
                    //die('sql: '.$abiertoSQL);
                    $notificacionesAbiertas = Yii::app()->db->createCommand($abiertoSQL)->query();
                    echo '<ul id="lAbierto">';
                    if (count($notificacionesAbiertas) > 0) {
                        //die('enter');
                        foreach ($notificacionesAbiertas as $value) {
                            $criteria = new CDbCriteria(array(
                                "condition" => "id = {$value['id_agendamiento']}",
                            ));
                            $modelo = GestionAgendamiento::model()->find($criteria);
                            $seg =  $modelo->agendamiento;
                            //$id_gd = $this->getGestionDiariaId($value['id_informacion']);
                            //$paso= $this->getGestionDiariaPaso($value['id_informacion']);
                            $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $value['id'], 'id_informacion' => $value['id_informacion']));
                            //$url = Yii::app()->createUrl('gestionDiaria/create', array('id' => $value['id_informacion'], 'paso' => $paso, 'id_gt' => $id_gd));

                            $abierto .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="' . utf8_decode(utf8_encode(utf8_decode(substr(ucfirst(strtolower($value["descripcion"])), 0, 380)))) . '">'
                                    . '<a href="' . $url . '">' . $value["nombres"] . ' - ' . $value["apellidos"] . ' - '. $seg . '</a>'
                                    . '</li>';
                        }
                        echo $abierto;

                        echo '</ul>';
                        echo '<input type="hidden" id="actualAbierto" value="10">';
                        //echo '<div id="vAbierto" class="mas" onclick=\'traerMas("Abierto")\'><span>VER M&Aacute;S</span></div>';
                    } else {
                        echo "No existen notificaciones para mostrar.";
                    }
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="solicitudes" aria-labelledby="abierto-tab">
                    <?php
                    $abierto2 = "";
                    $abiertoSQL2 = "SELECT gt.* FROM gestion_notificaciones gt "
                            . " WHERE gt.leido = 'UNREAD' AND gt.tipo = 2 AND gt.id_asesor = {$responsable_id}";
                    $notificacionesAbiertas2 = Yii::app()->db->createCommand($abiertoSQL2)->query();
                    echo '<ul id="lAbierto">';
                    if (count($notificacionesAbiertas2) > 0) {
                        foreach ($notificacionesAbiertas2 as $value) {
                            $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $value['id'], 'id_informacion' => $value['id_informacion']));

                            $abierto2 .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="' . utf8_decode(utf8_encode(utf8_decode(substr(ucfirst(strtolower($value["descripcion"])), 0, 380)))) . '">'
                                    . '<a href="' . $url . '">' . $value["id_informacion"] . ' - ' . $value['descripcion'] . '</a>'
                                    . '</li>';
                        }
                        echo $abierto2;

                        echo '</ul>';
                        echo '<input type="hidden" id="actualAbierto" value="10">';
                        //echo '<div id="vAbierto" class="mas" onclick=\'traerMas("Abierto")\'><span>VER M&Aacute;S</span></div>';
                    } else {
                        echo "No existen notificaciones para mostrar.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
