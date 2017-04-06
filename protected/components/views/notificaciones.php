<?php
Yii::import("application.protected.components.Controller.php");
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
//echo 'cargo adicional: '.$cargo_adicional;
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
    $dt_unmes_antes = date('Y-m-d', strtotime('-4 week')); // Fecha resta 1 mes
    //echo 'lenght fecha actual: '.strlen($fecha_actual);
    $responsable_id = Yii::app()->user->getId();
    $sql = "SELECT * FROM gestion_notificaciones WHERE leido = 'UNREAD' AND id_asesor = {$responsable_id} ORDER BY fecha DESC LIMIT 20";
    $notificaciones = Yii::app()->db->createCommand($sql)->query();
    $count = count($notificaciones);
    $fecha_actual = date("Y/m/d");
    switch ($cargo_id) {
        case 85:
        case 86:
            
            //if($cargo_adicional == 85 || $cargo_adicional == 86){

                # NOTIFICACIONES PARA ASESORES DE VENTAS Y ASESORES WEB

                $webSql = "SELECT gi.id, gi.nombres, gi.apellidos, gi.fecha FROM gestion_informacion gi 
                inner JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
                INNER JOIN gestion_consulta gc ON gc.id_informacion = gi.id 
                WHERE fuente_contacto = 'web' AND gi.responsable = {$responsable_id} AND gc.leido = 'UNREAD' ORDER BY gi.fecha DESC LIMIT 200";
                $notificacionesWeb = Yii::app()->db->createCommand($webSql)->query();
                $dataWeb = '';
                $dataWeb .= '<ul id="lAbierto">';
                foreach ($notificacionesWeb as $wb) {
                    $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id_informacion' => $wb['id'], 'tipo' => 7));
                    $dataWeb .= '<li><a href="' . $url . '">Cliente ' . $wb['nombres'] . ' ' . $wb['apellidos'] . ' se ha registrado ' . $wb['fecha'] . '</a></li>';
                }
                $dataWeb .= '</ul>';

                # NOTIFICACIONES DE CITAS PARA SOLICITUDES WEB
                $citasWeb = "SELECT gi.id, gi.nombres, gi.apellidos, gi.fecha, gn.id as id_not, gn.id_informacion, ga.agendamiento FROM gestion_informacion gi 
                    INNER JOIN gestion_cita gc ON gc.id_informacion =  gi.id 
                    INNER JOIN gestion_notificaciones gn ON gn.id_informacion = gi.id 
                    INNER JOIN gestion_agendamiento ga ON ga.id = gn.id_agendamiento 
                    WHERE gi.responsable = {$responsable_id} AND gn.leido = 'UNREAD' LIMIT 200";
                    //die($citasWeb);
                    $notificacionesCitas = Yii::app()->db->createCommand($citasWeb)->query();
                    $dataCitas = '';
                    $dataCitas .= '<ul id="lAbierto">';
                    foreach ($notificacionesCitas as $ct) {
                        //$url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id_informacion' => $wb['id'], 'tipo' => 8));
                        $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $ct['id_not'], 'id_informacion' => $ct['id_informacion'], 'cid' => $cargo_id, 'tipo' => 8));
                        $dataCitas .= '<li><a href="' . $url . '">Cliente ' . $ct['nombres'] . ' ' . $ct['apellidos'] . ' - ' . $ct['agendamiento'] . '</a></li>';
                    }
                    $dataCitas .= '</ul>';

            //}
            if($cargo_id == 85){ // JEFE DE VENTAS WEB
                // NOTIFICACIONES PARA JEFE DE VENTAS WEB----------------------------------------------------------------------------------------------------
                $fecha_actual = date("Y/m/d");
                $array_dealers = Controller::getDealerGrupoConc($grupo_id);
                $dealerList = implode(', ', $array_dealers);
                $sqlExt = "SELECT gi.id, gi.nombres, gi.apellidos, gi.fecha, gd.proximo_seguimiento, gd.paso, gd.fuente_contacto FROM gestion_informacion gi "
                        . "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id INNER JOIN usuarios u ON u.id = gi.responsable "
                        . "INNER JOIN gestion_consulta gc ON gc.id_informacion = gi.id "
                        . " WHERE gi.dealer_id IN({$dealerList}) and gi.bdc = 1 AND u.cargo_id = 86 AND gc.leido = 'UNREAD' ORDER BY gi.fecha DESC LIMIT 2000";
                //die('sql: '.$sqlExt );        
                $notExt = Yii::app()->db->createCommand($sqlExt)->query();
                $dataExt = '';
                $dataExt .= '<ul id="lAbierto">';
                $countExt = 0;
                foreach ($notExt as $ext) {
                    $fecha_array = explode(' ', $ext['proximo_seguimiento']);
                    $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id_informacion' => $ext['id'], 'tipo' => 6));
                    if (strtotime($fecha_actual) > strtotime($fecha_array[0])) {
                        $dataExt .= '<li><a href="' . $url . '">' . $ext['nombres'] . ' ' . $ext['apellidos'] . ' - Cliente no Contactado (48 horas)</a></li>';
                        $countExt++;
                    }
                }
                $dataExt .= '</ul>';

                # NOTIFICACIONES DE AGENDAMIENTOS PARA ASESORES DE VENTAS
                $abierto = "";
                $abiertoSQL = "SELECT gn.id as id_not,gn.id_agendamiento, ga.agendamiento, gi.nombres, gi.apellidos, gd.* 
                FROM gestion_notificaciones gn 
                INNER JOIN gestion_agendamiento ga ON ga.id = gn.id_agendamiento 
                INNER JOIN gestion_informacion gi on gi.id = gn.id_informacion 
                INNER JOIN gestion_diaria gd ON gd.id_informacion =  gn.id_informacion 
                WHERE gn.leido = 'UNREAD' AND gn.tipo = 1 
                AND gn.id_dealer IN ({$dealerList}) AND (DATE(ga.agendamiento) = '$fecha_actual' OR DATE(gd.proximo_seguimiento) = '$fecha_actual' )";
                $abiertoSQL .= ' GROUP BY gd.id_informacion ORDER BY gd.id_informacion DESC LIMIT 200';
                //die($abiertoSQL);
                $notificacionesAbiertas = Yii::app()->db->createCommand($abiertoSQL)->query();
            }
            if ($cargo_id == 86) {

                # NOTIFICACIONES DE AGENDAMIENTOS PARA ASESORES DE VENTAS
                $abierto = "";
                $abiertoSQL = "SELECT gn.id as id_not,gn.id_agendamiento, ga.agendamiento, gi.nombres, gi.apellidos, gd.* 
                FROM gestion_notificaciones gn 
                INNER JOIN gestion_agendamiento ga ON ga.id = gn.id_agendamiento 
                INNER JOIN gestion_informacion gi on gi.id = gn.id_informacion 
                INNER JOIN gestion_diaria gd ON gd.id_informacion =  gn.id_informacion 
                WHERE gn.leido = 'UNREAD' AND gn.tipo = 1 
                AND gn.id_asesor = {$responsable_id} AND (DATE(ga.agendamiento) = '$fecha_actual' AND DATE(gd.proximo_seguimiento) = '$fecha_actual' )";
                $abiertoSQL .= ' GROUP BY gd.id_informacion ORDER BY gd.id_informacion DESC LIMIT 200';
                $notificacionesAbiertas = Yii::app()->db->createCommand($abiertoSQL)->query();
            }

            break;
        case 71: // ASESOR DE VENTAS
        case 70: // JEFE DE SUCURSAL
            # NOTIFICACIONES DE AGENDAMIENTOS PARA ASESORES DE VENTAS
            $abierto = "";
            $abiertoSQL = "SELECT gn.id as id_not,gn.id_agendamiento, ga.agendamiento, gi.nombres, gi.apellidos, gd.* 
            FROM gestion_notificaciones gn 
            INNER JOIN gestion_informacion gi on gi.id = gn.id_informacion 
            LEFT JOIN gestion_agendamiento ga ON ga.id_informacion = gi.id
            INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
            WHERE gn.tipo = 1 
            AND gn.id_asesor = {$responsable_id} AND (DATE(ga.agendamiento) = '$fecha_actual' AND DATE(gd.proximo_seguimiento) = '$fecha_actual' )";
            $abiertoSQL .= ' GROUP BY gd.id_informacion ORDER BY gd.id_informacion DESC LIMIT 200';
            $notificacionesAbiertas = Yii::app()->db->createCommand($abiertoSQL)->query();
            //die('abiertoSQL: '.$abiertoSQL);

            $abierto2 = "";
            $abiertoSQL2 = "SELECT gt.*, gi.nombres, gi.apellidos, gs.`status` FROM gestion_notificaciones gt 
                            INNER JOIN gestion_status_solicitud gs ON gs.id_informacion = gt.id_informacion 
                            INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion "
                    . " WHERE gt.leido = 'UNREAD' AND gt.tipo = 2 AND gt.id_asesor = {$responsable_id}";
            //die($abiertoSQL2);
            $notificacionesAbiertas2 = Yii::app()->db->createCommand($abiertoSQL2)->query();


            # NOTIFICACIONES DE CADUCIDAD PARA CATEGORIZACION---------------------------------------------------------------------------------------------------
            $abierto3 = "";
            $abiertoSQL3 = "SELECT gi.id, gi.nombres, gi.apellidos, gc.preg7, gc.fecha FROM gestion_informacion gi 
            INNER JOIN gestion_consulta gc ON gc.id_informacion = gi.id
            WHERE gi.responsable = {$responsable_id} AND gc.leido = 'UNREAD' ORDER BY gi.id DESC LIMIT 200";
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

            # NOTIFICACIONES 
            $sqlCerrados = "SELECT gt.*, gi.nombres, gi.apellidos FROM gestion_notificaciones gt "
                . "INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion"
                . " WHERE gt.tipo = 3 AND gt.leido = 'UNREAD' AND gt.id_dealer IN ({$dealerList}) ORDER BY gi.id DESC LIMIT 400";
            $notificacionesCierre = Yii::app()->db->createCommand($sqlCerrados)->query();
            $datac .= '<ul id="lAbierto">';
            foreach ($notificacionesCierre as $nt) {

                $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $nt['id'], 'id_informacion' => $nt['id_informacion'], 'tipo' => 3));

                $datac .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                        . '<a href="' . $url . '">' . $nt["nombres"] . ' ' . $nt['apellidos'] . ' - Cierre de Venta</a>'
                        . '</li>';
            }
            $datac .= '</ul>';
            $datac .= '<input type="hidden" id="actualAbierto" value="10">';

            // COMENTARIOS ENVIADOS AL JEFE DE SUCURSAL O AGENTE DE VENTAS------------------------------------------------------------------------------------
            $sqlComentarios = "SELECT id, id_informacion, id_agendamiento, id_asesor_envia FROM gestion_notificaciones WHERE tipo = 5 AND id_asesor = {$id_responsable} AND leido = 'UNREAD' ORDER BY id DESC LIMIT 300";
            $notComentarios = Yii::app()->db->createCommand($sqlComentarios)->query();
            $dc .= '<ul id="lAbierto">';
            foreach ($notComentarios as $nc) {
                
                $url = Yii::app()->createUrl('gestionNotificaciones/vernotificacion', array('id' => $nc['id'], 'id_informacion' => $nc['id_informacion'], 'tipo' => 5, 'id_agendamiento' => $nc['id_agendamiento']));

                $dc .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="">'
                        . '<a href="' . $url . '">Comentario de ' . Controller::getResponsableNombres($nc['id_asesor_envia']) . '</a>'
                        . '</li>';

                
                
            }
            $dc .= '</ul>';

            break;    
        
        default:
            # code...
            break;
    }

    

    $num_noficicaciones = count($notificacionesAbiertas) + count($notificacionesAbiertas2) + $count_cat + count($notComentarios);
    //die('num_noficicaciones: '.$num_noficicaciones);
    if ($cargo_id == 85 || $cargo_adicional == 85 || $cargo_id == 86 || $cargo_adicional == 86)
        $num_noficicaciones = count($notificacionesAbiertas) + count($notificacionesWeb) + count($notificacionesCitas);
    ?>
    <div class="cont_tl_notificaciones" onclick="verN(<?php echo $num_noficicaciones; ?>)">
    <?php if ($num_noficicaciones > 0): ?>
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
                <?php if (($cargo_id == 71 || $cargo_id == 70) || $cargo_adicional == 86): // asesor de ventas y jefe de sucursal  ?>
                    <li role="presentation" class="active"><a href="#seguimiento" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Seguimiento</a></li>
                    <li role="presentation"><a href="#solicitudes" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Crédito</a></li>
                    <li role="presentation"><a href="#caducidad" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Caducidad</a></li>
                <?php endif; ?>
                <?php if ($cargo_id == 70): // jefe de sucursal  ?>
                    <li role="presentation"><a href="#cierre" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Cierre</a></li>
                <?php endif; ?>
                <?php if ($cargo_id == 85): // jefe de ventas externas ?>
                    <li role="presentation"><a href="#seguimientoe" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Seguimiento</a></li>
                <?php endif; ?>
                <?php if (($cargo_id == 71 || $cargo_id == 70) && $cargo_adicional == 0) : // asesor de ventas y jefe de sucursal  ?>
                    <li role="presentation"><a href="#comentarios" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Comentarios</a></li>
                <?php endif; ?>  
                <?php if ($cargo_id == 85 || $cargo_adicional == 85 || $cargo_id == 86 || $cargo_adicional == 86): ?>
                    <li role="presentation"><a href="#web" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Web</a></li>
                    <li role="presentation"><a href="#citas" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Citas</a></li>
            <?php endif; ?>    
            </ul>
            <div id="myTabContent" class="tab-content">
            <?php if (($cargo_id == 71 || $cargo_id == 70) || $cargo_adicional == 86): // jefe de ventas externas ?>
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
                    <?php endif; ?>
                <?php if ($cargo_id !== 85): // jefe de ventas externas ?>
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
                    <?php endif; ?>
                <?php if ($cargo_id !== 85): // jefe de ventas externas?>
                    <div role="tabpanel" class="tab-pane fade" id="caducidad" aria-labelledby="abierto-tab">
                    <?php
                    if ($count_cat > 0) {
                        echo $data;
                    } else {
                        echo '<ul id="lAbierto">No existen notificaciones para mostrar.</ul>';
                    }
                    ?>

                    </div>
                <?php endif; ?>
                <?php if ($cargo_id !== 85): // jefe de ventas externas?>
                    <div role="tabpanel" class="tab-pane fade" id="cierre" aria-labelledby="abierto-tab">
                    <?php
                    echo $datac;
                    ?>
                    </div>
                    <?php endif; ?>
                <?php if ($cargo_id !== 85): // jefe de ventas externas ?>
                    <div role="tabpanel" class="tab-pane fade" id="comentarios" aria-labelledby="abierto-tab">
                    <?php
                    echo $dc;
                    ?>
                    </div>
                    <?php endif; ?>
                <?php if ($cargo_id == 85): ?>
                    <div role="tabpanel" class="tab-pane fade active in" id="seguimientoe" aria-labelledby="abierto-tab">
                    <?php
                    echo $dataExt;
                    ?>
                    </div>
                    <?php endif; ?>
                <div role="tabpanel" class="tab-pane fade " id="web" aria-labelledby="abierto-tab">
                <?php
                echo $dataWeb;
                ?>
                </div>
                <div role="tabpanel" class="tab-pane fade " id="citas" aria-labelledby="abierto-tab">
                <?php
                if (count($notificacionesCitas) > 0) {
                    echo $dataCitas;
                } else {
                    echo '<ul id="lAbierto">No existen notificaciones para mostrar.</ul>';
                }
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
