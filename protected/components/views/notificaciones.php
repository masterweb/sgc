<?php
Yii::import("application.protected.components.Controller.php");
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
$id_responsable = Yii::app()->user->getId();
$dealer_id = Controller::getConcesionarioDealerId($id_responsable);
$array_dealers = Controller::getDealerGrupoConc($grupo_id);
$dealerList = implode(', ', $array_dealers);
?>
<div class="cont_notificaciones">
    <?php
    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador`
    $dt = time();
    $fecha_actual = (string) strftime("%Y-%m-%d", $dt);
    //echo 'lenght fecha actual: '.strlen($fecha_actual);
    $responsable_id = Yii::app()->user->getId();
    $sql = "SELECT * FROM gestion_notificaciones WHERE leido = 'UNREAD' AND id_asesor = {$responsable_id}";
    $notificaciones = Yii::app()->db->createCommand($sql)->query();
    $count = count($notificaciones);
    $fecha_actual = date("Y/m/d");

    $abierto = "";
    $abiertoSQL = "SELECT gn.id as id_not,gn.id_agendamiento, ga.agendamiento, gi.nombres, gi.apellidos, gd.* FROM gestion_notificaciones gn 
                    INNER JOIN gestion_agendamiento ga ON ga.id = gn.id_agendamiento 
                    INNER JOIN gestion_informacion gi on gi.id = gn.id_informacion 
                    INNER JOIN gestion_diaria gd ON gd.id_informacion =  gn.id_informacion 
                    WHERE gn.leido = 'UNREAD' AND gn.tipo = 1 ";
    switch ($cargo_id) {
        case 71: // asesor de ventas
        case 70: // jefe de sucursal    
            $abiertoSQL .= " AND gn.id_asesor = {$responsable_id} AND (DATE(ga.agendamiento) = '$fecha_actual' AND DATE(gd.proximo_seguimiento) = '$fecha_actual' )";
            break;
        //case 70: // jefe de sucursal
        //    $abiertoSQL .= " AND gn.id_dealer = {$dealer_id} AND (DATE(ga.agendamiento) = '$fecha_actual' OR DATE(gd.proximo_seguimiento) = '$fecha_actual' )";
        //    break;
        default:
            break;
    }
    $abiertoSQL .= ' GROUP BY gd.id_informacion';

    //die('sql: '.$abiertoSQL);
    $notificacionesAbiertas = Yii::app()->db->createCommand($abiertoSQL)->query();
    $abierto2 = "";
    $abiertoSQL2 = "SELECT gt.*, gi.nombres, gi.apellidos, gs.`status` FROM gestion_notificaciones gt 
                    INNER JOIN gestion_status_solicitud gs ON gs.id_informacion = gt.id_informacion 
                    INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion "
            . " WHERE gt.leido = 'UNREAD' AND gt.tipo = 2 AND gt.id_asesor = {$responsable_id}";
            //die($abiertoSQL2);
    $notificacionesAbiertas2 = Yii::app()->db->createCommand($abiertoSQL2)->query();

    $abierto3 = "";
    $abiertoSQL3 = "SELECT gi.id, gi.nombres, gi.apellidos, gc.preg7, gc.fecha FROM gestion_informacion gi 
INNER JOIN gestion_consulta gc ON gc.id_informacion = gi.id
WHERE gi.responsable = {$responsable_id} AND gc.leido = 'UNREAD'";
    $notificacionesAbiertas3 = Yii::app()->db->createCommand($abiertoSQL3)->query();
    $dias = 0;
    //$fecha_caducidad = '';
    $data = '';
    $count_cat = 0;
    foreach ($notificacionesAbiertas3 as $value3) {
        $fecha_array = explode('-', $value3['fecha']);
        $mes = (int) $fecha_array[1];
        $dia_array = explode(' ', $fecha_array[2]);
        $dia = (int) $dia_array[0];
        //echo '<h1>Fecha: ' . $value3['fecha'] . '</h1>';
        switch ($value3['preg7']) {
            case 'Hot A (hasta 7 dias)':
                $dias = 7;
                $dia = (string) $dia + 7;
                $mes = (string) $mes;
                $dias_mes = getMesDias($fecha_array[1]);
                if ($dia >= $dias_mes && $dias_mes == 30) {
                    $dia = $dia - 30;
                    $mes = (string) $mes + 1;
                }
                if ($dia >= $dias_mes && $dias_mes >= 31) {
                    $dia = $dia - 31;
                    $mes = (string) $mes + 1;
                }
                if (strlen($mes) == 1) {
                    $mes = '0' . $mes;
                }
                if (strlen($dia) == 1) {
                    $dia = '0' . $dia;
                }
                $fecha_caducidad = $fecha_array[0] . '/' . $mes . '/' . $dia;
                //echo $fecha_caducidad.'<br />';
                if ($fecha_actual == $fecha_caducidad) {
                    $data .= '<ul id="lAbierto">';
                    $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => 0, 'id_informacion' => $value3['id'], 'cid' => $cargo_id, 'tipo' => 4));

                    $data .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                            . '<a href="' . $url . '">' . $value3["nombres"] . ' ' . $value3['apellidos'] . ' - HOT A Caduca hoy</a>'
                            . '</li>';

                    $data .= '</ul>';
                    $data .= '<input type="hidden" id="actualAbierto" value="10">';
                    $count_cat++;
                }
                break;
            case 'Hot B (hasta 15 dias)':
                $dias = 15;
                $dia = (string) $dia + 15;

                $mes = (string) $mes;
                $dias_mes = getMesDias($fecha_array[1]);
                if ($dia >= $dias_mes && $dias_mes == 30) {
                    $dia = $dia - 30;
                    $mes = (string) $mes + 1;
                }
                if ($dia >= $dias_mes && $dias_mes >= 31) {
                    $dia = $dia - 31;
                    $mes = (string) $mes + 1;
                }
                if (strlen($mes) == 1) {
                    $mes = '0' . $mes;
                }
                if (strlen($dia) == 1) {
                    $dia = '0' . $dia;
                }
                $fecha_caducidad = $fecha_array[0] . '/' . $mes . '/' . $dia;
                //echo $fecha_caducidad.'<br />';
                if ($fecha_actual == $fecha_caducidad) {
                    $data .= '<ul id="lAbierto">';
                    $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => 0, 'id_informacion' => $value3['id'], 'tipo' => 4));

                    $data .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                            . '<a href="' . $url . '">' . $value3["nombres"] . ' ' . $value3['apellidos'] . ' - HOT B Caduca hoy</a>'
                            . '</li>';

                    $data .= '</ul>';
                    $data .= '<input type="hidden" id="actualAbierto" value="10">';
                    $count_cat++;
                }
                break;
            case 'Hot C (hasta 30 dias)':
                //echo 'Enter 30 dias';
                $dias = 30;
                $mes = (string) $mes + 1;
                if (strlen($mes) == 1) {
                    $mes = '0' . $mes;
                }
                $fecha_caducidad = $fecha_array[0] . '/' . $mes . '/' . $dia_array[0];
                //echo $fecha_caducidad.'<br />';
                //echo 'fecha caducidad: '.$fecha_caducidad . '<br />';
                //echo 'fecha actual: '.$fecha_actual. '<br />';
                if ($fecha_actual == $fecha_caducidad) {
                    $data .= '<ul id="lAbierto">';
                    $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => 0, 'id_informacion' => $value3['id'], 'tipo' => 4));

                    $data .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                            . '<a href="' . $url . '">' . $value3["nombres"] . ' ' . $value3['apellidos'] . ' - HOT C Caduca hoy</a>'
                            . '</li>';

                    $data .= '</ul>';
                    $data .= '<input type="hidden" id="actualAbierto" value="10">';
                    $count_cat++;
                }

                break;
            case 'Warm (hasta 3 meses)':
                $dias = 90;
                $mes = (string) $mes + 3;
                if (strlen($mes) == 1) {
                    $mes = '0' . $mes;
                }
                $fecha_caducidad = $fecha_array[0] . '/' . $mes . '/' . $dia_array[0];
                //echo $fecha_caducidad.'<br />';
                if ($fecha_actual == $fecha_caducidad) {
                    $data .= '<ul id="lAbierto">';
                    $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => 0, 'id_informacion' => $value3['id'], 'tipo' => 4));

                    $data .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                            . '<a href="' . $url . '">' . $value3["nombres"] . ' ' . $value3['apellidos'] . ' - WARM 3 meses Caduca hoy</a>'
                            . '</li>';

                    $data .= '</ul>';
                    $data .= '<input type="hidden" id="actualAbierto" value="10">';
                    $count_cat++;
                }
                break;
            case 'Cold (hasta 6 meses)':
                $dias = 180;
                $mes = (string) $mes + 6;
                if (strlen($mes) == 1) {
                    $mes = '0' . $mes;
                }
                $fecha_caducidad = $fecha_array[0] . '/' . $mes . '/' . $dia_array[0];
                //echo $fecha_caducidad.'<br />';
                if ($fecha_actual == $fecha_caducidad) {
                    $data .= '<ul id="lAbierto">';
                    $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => 0, 'id_informacion' => $value3['id'], 'tipo' => 4));

                    $data .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                            . '<a href="' . $url . '">' . $value3["nombres"] . ' ' . $value3['apellidos'] . ' - COLD 6 meses Caduca hoy</a>'
                            . '</li>';

                    $data .= '</ul>';
                    $data .= '<input type="hidden" id="actualAbierto" value="10">';
                    $count_cat++;
                }
                break;

            default:
                break;
        }
    }

    $sqlCerrados = "SELECT gt.*, gi.nombres, gi.apellidos FROM gestion_notificaciones gt "
            . "INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion"
            . " WHERE gt.tipo = 3 AND gt.leido = 'UNREAD' AND gt.id_dealer = {$dealer_id}";
    $notificacionesCierre = Yii::app()->db->createCommand($sqlCerrados)->query();
    foreach ($notificacionesCierre as $nt) {
        $datac .= '<ul id="lAbierto">';
        $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $nt['id'], 'id_informacion' => $nt['id_informacion'], 'tipo' => 3));

        $datac .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                . '<a href="' . $url . '">' . $nt["nombres"] . ' ' . $nt['apellidos'] . ' - Cierre de Venta</a>'
                . '</li>';

        $datac .= '</ul>';
        $datac .= '<input type="hidden" id="actualAbierto" value="10">';
    }
    
    // COMENTARIOS ENVIADOS AL JEFE DE SUCURSAL O AGENTE DE VENTAS==================================================
    $sqlComentarios = "SELECT * FROM gestion_notificaciones WHERE tipo = 5 AND id_asesor = {$id_responsable} AND leido = 'UNREAD'";
    $notComentarios = Yii::app()->db->createCommand($sqlComentarios)->query();
    foreach ($notComentarios as $nc) {
        $dc .= '<ul id="lAbierto">';
        $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $nc['id'], 'id_informacion' => $nc['id_informacion'], 'tipo' => 5,'id_agendamiento' => $nc['id_agendamiento']));

        $dc .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                . '<a href="' . $url . '">Comentario de '.Controller::getResponsableNombres($nc['id_asesor_envia']).'</a>'
                . '</li>';

        $dc .= '</ul>';
        $dc .= '<input type="hidden" id="actualAbierto" value="10">';
    }


    $num_noficicaciones = count($notificacionesAbiertas) + count($notificacionesAbiertas2) + $count_cat + count($notComentarios);
    ?>
    <div class="cont_tl_notificaciones" onclick="verN(<?php echo $num_noficicaciones; ?>)">
        <?php
        if ($num_noficicaciones > 0):
            ?>
            <div class="no_notificaciones"><?php echo $num_noficicaciones; ?></div>
        <?php else: ?>
            <div class="no_notificaciones no-not"><?php echo $num_noficicaciones; ?></div>
        <?php endif; ?>

        <div class="tl_notificaciones">Notificaciones</div>
        <div class="clear"></div>
    </div>
    <div id="lNotificaciones" style="display: none;">
        <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#seguimiento" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Seguimiento</a></li>
                <?php if ($cargo_id == 71): ?>
                    <li role="presentation"><a href="#solicitudes" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Crédito</a></li>
                    <li role="presentation"><a href="#caducidad" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Caducidad</a></li>
                <?php endif; ?>
                <?php if ($cargo_id == 70): ?>
                    <li role="presentation"><a href="#cierre" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Cierre</a></li>
                <?php endif; ?>
                    <li role="presentation"><a href="#comentarios" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Comentarios</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="seguimiento" aria-labelledby="abierto-tab">
                    <?php
                    echo '<ul id="lAbierto">';
                    if (count($notificacionesAbiertas) > 0) {
                        //die('enter');
                        foreach ($notificacionesAbiertas as $value) {
                            $criteria = new CDbCriteria(array(
                                "condition" => "id = {$value['id_agendamiento']}",
                            ));
                            $modelo = GestionAgendamiento::model()->find($criteria);
                            $seg = $modelo->agendamiento;
                            //$id_gd = $this->getGestionDiariaId($value['id_informacion']);
                            //$paso= $this->getGestionDiariaPaso($value['id_informacion']);
                            $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $value['id_not'], 'id_informacion' => $value['id_informacion'], 'cid' => $cargo_id, 'tipo' => 1));
                            //$url = Yii::app()->createUrl('gestionDiaria/create', array('id' => $value['id_informacion'], 'paso' => $paso, 'id_gt' => $id_gd));

                            $abierto .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="' . utf8_decode(utf8_encode(utf8_decode(substr(ucfirst(strtolower($value["descripcion"])), 0, 380)))) . '">'
                                    . '<a href="' . $url . '">' . $value["nombres"] . ' - ' . $value["apellidos"] . ' - ' . $seg . '</a>'
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
                    echo '<ul id="lAbierto">';
                    if (count($notificacionesAbiertas2) > 0) {
                        foreach ($notificacionesAbiertas2 as $value) {
                            $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $value['id'], 'id_informacion' => $value['id_informacion'], 'cid' => $cargo_id, 'tipo' => 2));

                            $abierto2 .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                                    . '<a href="' . $url . '">' . $value["nombres"] . ' ' . $value['apellidos'] . ' - ' . $value['descripcion'] . '</a>'
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
                <div role="tabpanel" class="tab-pane fade" id="caducidad" aria-labelledby="abierto-tab">
                    <?php
                    if ($count_cat > 0) {
                        echo $data;
                    }
                    ?>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="cierre" aria-labelledby="abierto-tab">
                    <?php
                    echo $datac;
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="comentarios" aria-labelledby="abierto-tab">
                    <?php
                    echo $dc;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

function getMesDias($mes) {
    $dias = 0;
    switch ($mes) {
        case '01':
            $dias = 31;
            break;
        case '02':
            $dias = 29;
            break;
        case '03':
            $dias = 31;
            break;
        case '04':
            $dias = 30;
            break;
        case '05':
            $dias = 31;
            break;
        case '06':
            $dias = 30;
            break;
        case '07':
            $dias = 31;
            break;
        case '08':
            $dias = 31;
            break;
        case '09':
            $dias = 30;
            break;
        case '10':
            $dias = 30;
            break;
        case '11':
            $dias = 31;
            break;
        case '12':
            $dias = 31;
            break;


        default:
            break;
    }
    return $dias;
}
?>
