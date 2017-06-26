<?php

/** XX
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/call';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public function traercotizaciones() {
        date_default_timezone_set("America/Bogota");
        $sql = 'SELECT * FROM atencion_detalle WHERE id_atencion IN (1,5) and fecha_form >="2016-06-01" and encuestado = 0 and id_modelos is not null order by id_atencion_detalle desc';
        $datosC = Yii::app()->db2->createCommand($sql)->queryAll();
        $cargo_id = Cargo::model()->find(array('condition' => 'codigo = "' . Constantes::CDG . '"'));
        $usuarios = Usuarios::model()->findAll(array('condition' => 'estado = "ACTIVO" and cargo_id =' . $cargo_id->id));

        if (!empty($datosC)) {
            $maximo = number_format(count($datosC) / count($usuarios), 0);
            $actual = 0;
            $contactual = 0;
            $posicion = 0;
            $usuario_list = array();
            foreach ($usuarios as $u) {
                $usuario_list[$actual++] = $u->id;
            }


            foreach ($datosC as $d) {

                if ($contactual == $maximo) {
                    $contactual = 0;
                    $posicion++;
                }
                if ($posicion <= count($usuarios) && !empty($usuario_list[$posicion])) {
                    if (!empty($d['id_atencion'])) {
                        $cotizacion = new Cotizacionesnodeseadas();

                        $cotizacion->atencion_detalle_id = (int) $d['id_atencion_detalle'];
                        $po = array_rand($usuario_list);
                        $cotizacion->usuario_id = $usuario_list[$po];
                        //$cotizacion->usuario_id = $usuario_list[$posicion];
                        $cotizacion->fecha = $d['fecha_form'];
                        $cotizacion->realizado = '0';
                        $cotizacion->nombre = $d['nombre'];
                        $cotizacion->apellido = $d['apellido'];
                        $cotizacion->cedula = $d['cedula'];
                        $cotizacion->direccion = $d['direccion'];
                        $cotizacion->telefono = $d['telefono'];
                        $cotizacion->celular = $d['celular'];
                        $cotizacion->email = $d['email'];
                        $cotizacion->modelo_id = $d['id_modelos'];
                        $cotizacion->version_id = $d['id_version'];
                        $cotizacion->ciudadconcesionario_id = $d['cityid'];
                        $cotizacion->concesionario_id = $d['dealerid'];
                        $cotizacion->id_atencion = $d['id_atencion'];
                        if ($cotizacion->save()) {
                            //echo $usuario_list[$posicion];
                            Yii::app()->db2
                                    ->createCommand("UPDATE atencion_detalle SET encuestado=1,fechaencuesta='" . date("Y-m-d h:i:s") . "' WHERE id_atencion_detalle=:RListID")
                                    ->bindValues(array(':RListID' => $d['id_atencion_detalle']))
                                    ->execute();
                        }
                    }
                }
                $contactual++;
            }
        }
    }

    public function getTema($id) {
        $tema = Menu::model()->findByPk($id);
        return $tema->name;
    }

    public function getSubtema($id) {
        $tema = Submenu::model()->findByPk($id);
        return $tema->name;
    }

    public function getProvincia($id) {
        if($id == 1000){
            return 'Todas';
        }else{
            $provincia = Provincias::model()->findByPk($id);
            if (!is_null($provincia) && !empty($provincia)) {
                return $provincia->nombre;
            } else {
                return 'NA';
            }
        }
        
    }

    public function getProvinciaIdDomicilio($id_informacion) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id={$id_informacion}";
        $provincia = GestionInformacion::model()->find($criteria);
        return $provincia->provincia_domicilio;
    }

    public function getCiudad($id) {
        //die('id: '.$id);
        $ciudad = Dealercities::model()->findByPk($id);
        if (!is_null($ciudad) && !empty($ciudad)) {
            return $ciudad->name;
        } else {
            return 'NA';
        }
    }

    public function getCiudadConcesionario($id_informacion) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id={$id_informacion}";
        $gt = GestionInformacion::model()->find($criteria);
        $concesionario = $gt->concesionario;

        $cri = new CDbCriteria;
        $cri->condition = "id={$concesionario}";
        $ci = Dealers::model()->find($cri);
        $ciudad = $ci->cityid;

        return $this->getCiudad($ciudad);
    }

    public function getEmailCliente($id_informacion) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id={$id_informacion}";
        $email = GestionInformacion::model()->find($criteria);
        if ($email)
            return $email->email;
        else
            return false;
    }

    public function getTelefonoCliente($id_informacion) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id={$id_informacion}";
        $email = GestionInformacion::model()->find($criteria);
        if ($email)
            return $email->telefono_casa;
        else
            return false;
    }

    public function getCelularCliente($id_informacion) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id={$id_informacion}";
        $email = GestionInformacion::model()->find($criteria);
        if ($email)
            return $email->celular;
        else
            return false;
    }

    public function getTipoVenta($id_informacion) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id_informacion={$id_informacion}";
        $tipo = GestionConsulta::model()->find($criteria);
        if ($tipo)
            return $tipo->preg6;
        else
            return false;
    }

    public function getProvinciaDom($id) {
        $provincia = TblProvincias::model()->findByPk($id);
        if (!is_null($provincia) && !empty($provincia)) {
            return $provincia->nombre;
        } else {
            return 'NA';
        }
    }

    public function getCiudadDom($id) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id_ciudad={$id}";
        $ciudad = TblCiudades::model()->find($criteria);
        if (!is_null($ciudad) && !empty($ciudad)) {
            return $ciudad->nombre;
        } else {
            return 'NA';
        }
    }

    public function getConcesionario($id) {
        if($id == 1000){
            return 'Todos';
        }else{
            $dealers = Dealers::model()->findByPk($id);
            if (!is_null($dealers) && !empty($dealers)) {
                return $dealers->name;
            } else {
                return 'NA';

            }
        }
    }

    public function getConcesionarioFYI($id_informacion){
        $dealer_id = GestionInformacion::model()->findByPk($id_informacion);
        if (!is_null($dealer_id) && !empty($dealer_id)) {
            return $this->getConcesionario($dealer_id->dealer_id);
        } else {
            return 'NA';
        }

    }

    public function getConcesionarioTlf($id) {
        $dealers = Dealers::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->phone;
        } else {
            return 'NA';
        }
    }

    public function getConcesionarioDireccionById($id) {
        if (empty($id)) {
            return '';
        } else {
            $dealer = Dealers::model()->find(array('condition' => "id={$id}"));
            if ($dealer) {
                return $dealer->direccion;
            }
        }
    }

    public function getConcesionarioDireccion($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);
       //die ('dealer: '.$dealer->concesionario_id);
        if ($dealer->concesionario_id == 0) {
            $usuario = Grupoconcesionariousuario::model()->find(array('condition' => "usuario_id={$id}"));
            $id_conc = $usuario->concesionario_id;
            $criteria2 = new CDbCriteria(array(
                'condition' => "id={$id_conc}"
            ));
            $deal = Dealers::model()->find($criteria2);
            return $deal->direccion;
        } else {
            //$id_conc = $dealer->consecionario->dealer->id;
            $criteria2 = new CDbCriteria(array(
                'condition' => "id={$dealer->concesionario_id}"
            ));
            $deal = Dealers::model()->find($criteria2);
            return $deal->direccion;
        }
    }

    public function getNombreGrupo($id) {
        switch ($id) {
            case 1000:
                return 'Todos';
                break;
            case '':
                $cargo_id = (int) Yii::app()->user->getState('cargo_id');          
                $area_id = (int) Yii::app()->user->getState('area_id');
                if($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14){
                    return 'Todos';
                }else{
                    $dealer = GrGrupo::model()->find(array("condition" => "id={$grupo_id}"));
                    return $dealer->nombre_grupo;
                }

                
                break;    
            
            default:
                $criteria = new CDbCriteria(array(
                'condition' => "id={$id}"
                ));
                $dealer = GrGrupo::model()->find($criteria);
                return $dealer->nombre_grupo;
                break;
        }
        /*if($id == 1000 ||   $id == ''){
            return 'Todos';
        }else{
            $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
            ));
            $dealer = GrGrupo::model()->find($criteria);
            return $dealer->nombre_grupo;
        }*/
        
    }

    public function getAsesorCel($id) {
        $dealers = Usuarios::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->celular;
        } else {
            return 'NA';
        }
    }

    public function getAsesorEmail($id) {
        $dealers = Usuarios::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->correo;
        } else {
            return 'NA';
        }
    }

    public function getEmailAsesorCredito($dealers_id) {
        //die('id: '.$dealers_id);
        $dealers = Usuarios::model()->find(array('condition' => "dealers_id={$dealers_id} AND cargo_id = 74"));
        if (!is_null($dealers) && !empty($dealers)) {
            //die('enter not null');
            return $dealers->correo;
        }
    }

    public function getAsesorTelefono($id) {
        $dealers = Usuarios::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->telefono;
        } else {
            return 'NA';
        }
    }

    public function getAsesorCelular($id) {
        $dealers = Usuarios::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            //$params = explode('-', $dealers->celular);

            return $dealers->celular;
        } else {
            return 'NA';
        }
    }

    public function mailgetAsesorCodigo($id) {
        $dealers = Usuarios::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->codigo_asesor;
        } else {
            return 'NA';
        }
    }

    public function getCodigoConcesionario($id) {
        $criteria = new CDbCriteria;
        $criteria->condition = "concesionario_id={$id}";
        $usuario = GestionCodigocc::model()->find($criteria);
        if (!is_null($usuario) && !empty($usuario)) {
            return $usuario->codigo_concesionario;
        } else {
            return 'NA';
        }
    }

    public function getAsesorExtension($id) {
        $dealers = Usuarios::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->extension;
        } else {
            return 'NA';
        }
    }

    public function getEmailAsesorUsados($cargo_id, $grupo_id) {
        $criteria = new CDbCriteria;
        $criteria->condition = "cargo_id={$cargo_id} AND grupo_id = {$grupo_id}";
        $usuario = Usuarios::model()->find($criteria);
        if (!is_null($usuario) && !empty($usuario)) {
            return $usuario->correo;
        } else {
            return 'alkanware@gmail.com';
        }
    }

    public function getAsesorCodigo($id) {
        $dealers = Usuarios::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->codigo_asesor;
        } else {
            return 'NA';
        }
    }

    public function getEmailJefeConcesion($cargo_id, $grupo_id, $dealer_id) {

        $tipo_grupo = 1; // GRUPOS ASIAUTO, KMOTOR POR DEFECTO
        if ($grupo_id == 4 || $grupo_id == 5 || $grupo_id == 6 || $grupo_id == 7 || $grupo_id == 8 || $grupo_id == 9) {
            $tipo_grupo = 0; // GRUPOS MOTRICENTRO, MERQUIAUTO, AUTHESA, AUTOSCOREA, IOKARS
        }
        
        switch ($cargo_id) {
            case '71': // ASESOR DE VENTAS
                if ($tipo_grupo == 1) {
                    $us = Usuarios::model()->find(array('condition' => "cargo_id = 70 AND dealers_id = {$dealer_id} AND status_asesor = 'ACTIVO'"));
                    if (count($us) > 0) {
                        return $us->correo;
                    } else {
                        $sql = "SELECT gr.*, u.correo FROM grupoconcesionariousuario gr 
            INNER JOIN usuarios u ON u.id = gr.usuario_id 
            WHERE gr.concesionario_id = {$dealer_id}
            AND u.cargo_id = 70 AND status_asesor = 'ACTIVO'";

                        $con = Yii::app()->db;
                        $request = $con->createCommand($sql)->query();
                        //die('count request: '.count($request));
                        foreach ($request as $value) {
                            return $value['correo'];
                        }
                    }
                } 
                if ($tipo_grupo == 0) {
                    $us = Usuarios::model()->find(array('condition' => "(cargo_id = 70 OR cargo_adicional = 85) AND dealers_id = {$dealer_id} AND status_asesor = 'ACTIVO'"));
                    if (count($us) > 0) {
                        return $us->correo;
                    } 
                }


                break;
            case '86': // ASESOR WEB
                if ($tipo_grupo == 1) {
                    $sql = "SELECT * FROM usuarios WHERE grupo_id = {$grupo_id} AND cargo_id = 85 AND status_asesor = 'ACTIVO'";
                } 
                if ($tipo_grupo == 0) {
                    $sql = "SELECT * FROM usuarios WHERE grupo_id = {$grupo_id} AND (cargo_id = 85 OR cargo_adicional = 85) AND status_asesor = 'ACTIVO'";
                }
                $con = Yii::app()->db;
                $request = $con->createCommand($sql)->query();
                //die('count request: '.count($request));
                foreach ($request as $value) {
                    return $value['correo'];
                }

                break;

            default:
                break;
        }
    }

    public function getNombresJefeConcesion($cargo_id, $grupo_id, $dealer_id) {
        $cargo_id_agente = (int) Yii::app()->user->getState('cargo_id');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        // buscar en tabla usuarios el jefe de almacen con el dealer id
        $us = Usuarios::model()->find(array('condition' => "cargo_id={$cargo_id} AND dealers_id = {$dealer_id}"));

        if (count($us) > 0) {
            return $us->nombres . ' ' . $us->apellido;
        }
        if (count($us) == 0) {
            $us = Usuarios::model()->find(array('condition' => "cargo_id={$cargo_id} AND grupo_id = {$grupo_id}"));
            return $us->nombres . ' ' . $us->apellido;
        } else {
            $sql = "SELECT gr.*, u.correo FROM grupoconcesionariousuario gr 
            INNER JOIN usuarios u ON u.id = gr.usuario_id 
            WHERE gr.concesionario_id = {$dealer_id}";
            if ($cargo_id_agente == 71) {
                $sql .= " AND u.cargo_id = 70 ";
            }
            if ($cargo_id_agente == 86) {
                $sql .= " AND u.cargo_id = 85 ";
            }
            die($sql);
            $con = Yii::app()->db;
            $request = $con->createCommand($sql)->query();
            //die('count request: '.count($request));
            foreach ($request as $value) {
                return $value['nombres'] . ' ' . $value['apellido'];
            }
        }
    }

    public function setBotonCotizacion($paso, $id, $fuente, $id_informacion, $id_responsable, $resp) {
        //die('id: '.$id);
        $data = '';
        if ($id_responsable == $resp) {
            switch ($paso) {
                case 1:
                case 2:
                    $data = '<a href="' . Yii::app()->createUrl('gestionInformacion/update', array('id' => $id_informacion, 'tipo' => 'gestion')) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
                    break;
                case 3:
                    $data = '<a href="' . Yii::app()->createUrl('site/consulta', array('id_informacion' => $id_informacion, 'tipo' => 'gestion', 'fuente' => 'web')) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';

                    break;
                case 4:
                    $data = '<a href="' . Yii::app()->createUrl('gestionVehiculo/create', array('id' => $id_informacion)) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
                    break;
                case 5:
                    $data = '<a href="' . Yii::app()->createUrl('site/presentacion', array('id' => $id_informacion)) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
                    break;
                case 6:
                    $data = '<a href="' . Yii::app()->createUrl('site/demostracion', array('id' => $id_informacion)) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
                    break;
                case 7:
                    $data = '<a href="' . Yii::app()->createUrl('site/negociacion', array('id' => $id_informacion)) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
                    break;
                case 8:
                    $data = '<a href="' . Yii::app()->createUrl('site/negociacion', array('id' => $id_informacion)) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
                    break;
                case 9:
                    $data = '<a href="' . Yii::app()->createUrl('site/cierre', array('id' => $id_informacion)) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
                    break;
                case 10:
                    $data = '<a href="' . Yii::app()->createUrl('gestionInformacion/update', array('id' => $id_informacion, 'tipo' => 'gestion')) . '" class="btn btn-primary btn-xs btn-danger">Nueva Cotización</a>';
                    break;


                default:
                    break;
            }
        }
        return $data;
    }

    public function getResponsable($id) {
        //echo 'id: '.$id;
        $responsableid = Usuarios::model()->findByPk($id);
        /* echo '<pre>';
          print_r($dealers);
          echo '</pre>'; */
        //echo $dealers->responsable;
        //die('dddde');
        if (!is_null($responsableid) && !empty($responsableid)) {
            return $responsableid->nombres . ' ' . $responsableid->apellido;
        } else {
            return 'NA';
        }
    }

    public function getResponsableInformacion($id_informacion){
        $responsableid = GestionInformacion::model()->findByPk($id_informacion);
        if (!is_null($responsableid) && !empty($responsableid)) {
            return $this->getResponsable($responsableid->responsable);
        } else {
            return 'NA';
        }
    }

    public function getResponsableFyi($id) {
        //echo 'id: '.$id;
        $responsableid = Usuarios::model()->findByPk($id);
        /* echo '<pre>';
          print_r($dealers);
          echo '</pre>'; */
        //echo $dealers->responsable;
        //die('dddde');
        if (!is_null($responsableid) && !empty($responsableid)) {
            return $responsableid->nombres . ' ' . $responsableid->apellido;
        } else {
            return 'NA';
        }
    }

    public function getResponsableEditSc($id_informacion, $id_vehiculo){
        $sc = GestionSolicitudCredito::model()->find(array('condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"));
        if (!is_null($sc) && !empty($sc)) {
            return $sc->edit;
        }else{
            return 0;
        }
    }

    /**
     * Funcion que devuelve nombre de responsable asesor de 1800
     * @param type $id
     * @return string nombre del asesor 1800
     */
    public function getResponsable1800($id) {
        $responsableid = Users::model()->findByPk($id);
        /* echo '<pre>';
          print_r($dealers);
          echo '</pre>'; */
        //echo $dealers->responsable;
        //die();
        if (!is_null($responsableid) && !empty($responsableid)) {
            return $responsableid->first_name . ' ' . $responsableid->last_name;
        } else {
            return 'NA';
        }
    }

    public function getComentarioRGD($id_comentario) {
        //echo 'id: '.$id_comentario;
        $responsableid = GestionReasignamiento::model()->find(array('condition' => "id = {$id_comentario}"));
        if (!is_null($responsableid) && !empty($responsableid)) {
            return $responsableid->comentario;
        } else {
            return '';
        }
    }

    public function getComentarioAsignamiento($id) {
        $reas = GestionReasignamiento::model()->find(array('condition' => "id = {$id}"));
        if (!is_null($reas) && !empty($reas)) {
            return $reas->comentario . ' - ' . $reas->fecha;
        } else {
            return '';
        }
    }

    public function getEmailAsesor($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $emailSuper = Users::model()->find($criteria);
        return $emailSuper->email;
    }

    public function getEmailSP() {
        $criteria = new CDbCriteria(array(
            "condition" => "rol = 'super'",
        ));
        $emailSuper = Users::model()->find($criteria);
        return $emailSuper->email;
    }

    public function getEmailV() {
        $criteria = new CDbCriteria(array(
            "condition" => "username = 'userventa'",
        ));
        $emailSuper = Users::model()->find($criteria);
        return $emailSuper->email;
    }

    public function getEmailPV() {
        $criteria = new CDbCriteria(array(
            "condition" => "username = 'userpostventa'",
        ));
        $emailSuper = Users::model()->find($criteria);
        return $emailSuper->email;
    }

    public function getModelFin($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        $name_modelo = $this->getModel($modelo->modelo);
        return $name_modelo;
    }

    public function getVersionFin($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        $name_version = $this->getVersion($modelo->version);
        return $name_version;
    }

    public function getPrecioContado($id, $tipo, $id_modelo) {
        //echo 'tipo: '.$tipo;
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        $version = $modelo->version;
        $criteria2 = new CDbCriteria(array(
            "condition" => "id_versiones = {$version}",
        ));
        //    die ('version: '.$version);
        $modeloversion = Versiones::model()->find($criteria2);

        return $modeloversion->precio;
    }

    public function getPrecio($id, $tipo, $id_modelo) {
        //echo 'tipo: '.$tipo;
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        $version = $modelo->version;
        $criteria2 = new CDbCriteria(array(
            "condition" => "id_versiones = {$version}",
        ));
        //    die ('version: '.$version);
        $modeloversion = Versiones::model()->find($criteria2);
        if ($tipo == 1 && $id_modelo != 90 && $id_modelo != 93 && $flag == 1) {
            return $modeloversion->precio + 670; // precio de credito con accesorio Kit Satelital por defecto
        } else {
            return $modeloversion->precio;
        }
    }

    public function getinformacion($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        return $modelo->id_informacion;
    }

    public function getModel($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            "condition" => "id_modelos = '{$id}'",
        ));
        $modelo = Modelos::model()->find($criteria);
        if ($modelo) {
            return $modelo->nombre_modelo;
        } else {
            return 'NA';
        }
    }

    public function getMarcaPosee($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id}",
        ));
        $mod = '';
        $modelo = GestionConsulta::model()->find($criteria);
        if ($modelo->preg1_sec2 != '' && $modelo->preg1_sec3 != '') {
            return $modelo->preg1_sec1;
        } else {
            return $mod;
        }
    }

    public function getModeloPosee($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id}",
        ));
        $modelo = GestionConsulta::model()->find($criteria);
        $modelo_auto = '';
        if ($modelo->preg1_sec2 != '') {
            $params = explode('@', $modelo->preg1_sec2);
            $modelo_auto .= $params[1] . ' ' . $params[2];
        }
        return $modelo_auto;
    }

    public function getModeloYear($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id}",
        ));
        $modelo = GestionConsulta::model()->find($criteria);
        return $modelo->preg1_sec3;
    }

    public function getNecesidad($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id}",
        ));
        $gestion = GestionConsulta::model()->find($criteria);
        $str = '';
        if ($gestion->preg8 != '') {
            $array = $gestion->preg8;
            $str = $this->getArg($array);
        }
        return $str;
    }
    
    public function getStatusConsulta($id) {
        $gestion = GestionConsulta::model()->find(array('condition' => "id_informacion = {$id}"));
        $res = 0;
        if($gestion){
            if ($gestion->preg1_sec5 != '') { // si tiene vehiculo
                $res++;
            }
            if ($gestion->preg3 != '') { // para que utilizara el vehiculo
                $res++;
            }
            if ($gestion->preg3_sec1 != '') { // familiar o trabajo
                $res++;
            }
            if ($gestion->preg4 != '') { // quien participa en la decision de compra
                $res++;
            }
            if ($gestion->preg5 != '') { // cual es el presupuesto
                $res++;
            }
            if ($gestion->preg6 != '') { // forma de pago
                $res++;
            }
            if ($gestion->preg7 != '') { // tiempo estimado de compra
                $res++;
            }
        }else{
            $res = 0;
        }
        return $res;
        
    }

    public function getNombresInfo($id_informacion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id_informacion}",
        ));
        $gestion = GestionInformacion::model()->find($criteria);
        if ($gestion) {
            return ucfirst($gestion->nombres);
        } else {
            return 'NA';
        }
    }

    public function getModeloInfo($id_informacion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id_informacion}",
        ));
        $gestion = GestionVehiculo::model()->find($criteria);
        return $gestion->modelo;
    }

    public function getApellidosInfo($id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id_vehiculo}",
        ));
        $gestion = GestionInformacion::model()->find($criteria);
        if ($gestion) {
            return ucfirst($gestion->apellidos);
        } else {
            return 'NA';
        }
    }

    public function getIdentificacion($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionNuevaCotizacion::model()->find($criteria);
        $identificacion = $gestion->identificacion;
        switch ($identificacion) {
            case '':
            case 'ci':
                return 'ci';
                break;
            case 'pasaporte':
                return 'pasaporte';
                break;
            case 'ruc':
                return 'ruc';
                break;

            default:
                break;
        }
    }

    public function getIdentificacionTipoFYI($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionInformacion::model()->find($criteria);
        if(!empty($gestion->cedula)){
            return $gestion->cedula;
        }
        if(!empty($gestion->pasaporte)){
            return $gestion->pasaporte;
        }
        if(!empty($gestion->ruc)){
            return $gestion->ruc;
        }
    }

    public function getIdentificacionTipo($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionInformacion::model()->find($criteria);
        $identificacion = $gestion->identificacion;
        switch ($identificacion) {
            case '':
            case 'ci':
                return $gestion->cedula;
                break;
            case 'pasaporte':
                return $gestion->pasaporte;
                break;
            case 'ruc':
                return $gestion->ruc;
                break;

            default:
                break;
        }
    }

    public function getTipoIdentificacionInformacion($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionInformacion::model()->find($criteria);
        if ($gestion->cedula != '') {
            return 'ci';
        }
        if ($gestion->ruc != '') {
            return 'ruc';
        }
        if ($gestion->pasaporte != '') {
            return 'pasaporte';
        }
    }

    public function getIdentificacionCedula($id) {
        //echo 'id: '.$id;
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionNuevaCotizacion::model()->find($criteria);
        return $gestion->cedula;
    }

    public function getIdentificacionRuc($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionNuevaCotizacion::model()->find($criteria);
        return $gestion->ruc;
    }

    public function getIdentificacionPasaporte($id) {
        //echo ('id: '.$id);
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionNuevaCotizacion::model()->find($criteria);
        //die('pasaporte: '.$gestion->pasaporte);
        return $gestion->pasaporte;
    }

    public function getVersion($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            "condition" => "id_versiones = '{$id}'",
        ));
        $modelo = Versiones::model()->find($criteria);
        if ($modelo) {
            return $modelo->nombre_version;
        } else {
            return 'NA';
        }
    }
    
    public function getVersionesIds($cat) {
        if($cat != 5){
            $versiones = GestionModelos::model()->findAll(array('condition' => "categoria = {$cat}"));
        }else{
            $versiones = GestionModelos::model()->findAll();
        }
        
        $id_versiones = '';
        if ($versiones) {
            foreach ($versiones as $value) {
                $id_versiones .= $value['id_versiones'].',';
                
            }
            $id_versiones = substr($id_versiones, 0, -1);
            return $id_versiones;
        } else {
            return $id_versiones;
        }
    }

    public function getStatus($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $status = GestionStatus::model()->find($criteria);
        return $status->name;
    }

    public function getStatusSolicitud($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}",
            'limit' => 1,
            'order' => 'id DESC'
        ));
        $count = GestionStatusSolicitud::model()->count($criteria);
        if ($count > 0) {
            $status = GestionStatusSolicitud::model()->find($criteria);
            return $status->status;
        } else {
            return 'na';
        }
    }

    public function getComentarioStatus($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}",
            'limit' => 1,
            'order' => 'id DESC'
        ));
        $count = GestionStatusSolicitud::model()->count($criteria);
        if ($count > 0) {
            $status = GestionStatusSolicitud::model()->find($criteria);
            return $status->observaciones;
        } else {
            return '';
        }
    }

    public function getStatusSolicitudAll($id_informacion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion} and (status <> 'En Análisis')",
        ));
        $res = FALSE;
        $count = GestionStatusSolicitud::model()->count($criteria);
        if ($count > 0) {
            $res = TRUE;
        }
        return $res;
    }

    public function getStatusGD($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $status = GestionDiaria::model()->find($criteria);
        return $status->status;
    }

    public function getStatusGestion($id, $tipo) {
        $status = '';
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $st = GestionDiaria::model()->find($criteria);
        switch ($tipo) {
            case 1:
                $status = 'prospeccion';
                return $st->prospeccion;
                break;
            case 2:
                $status = 'primera visita';
                return $st->primera_visita;
                break;
            case 3:
                $status = 'seguimiento';
                return $st->seguimiento;
                break;
            case 4:
                $status = 'cierre';
                return $st->cierre;
                break;
            case 5:
                $status = 'entrega';
                return $st->entrega;
                break;
            case 6:
                $status = 'desiste';
                return $st->desiste;
                break;

            default:
                break;
        }
    }

    /** Get pdf of vehicles'id 
     *  @param integer $id the ID of the vehicle
     *  @return name of pdf file
     */
    public function getPdf($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_modelos={$id}",
            'limit' => 1,
        ));
        $pdf = Versiones::model()->find($criteria);
        return $pdf->pdf;
    }

    /**
     * Get cedula of Cotizacion id
     * @param integer $id Id od Cotizacion
     * @return string Cedula of Cotizacion
     */
    public function getCedula($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $cedula = GestionNuevaCotizacion::model()->find($criteria);
        //$sql = "select * from gestion_nueva_cotizacion where id = {$id}";
        //die('<h4>'.$sql.'</h4>');
        //die('before ced');
        return $cedula->cedula;
    }

    public function getTestDrive($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo} AND test_drive = 1"
        ));
        $test = GestionTestDrive::model()->count($criteria);
        return $test;
    }

    public function getFactura($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}"
        ));
        $test = GestionFactura::model()->count($criteria);
        return $test;
    }

    public function getTestDemostracion($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            'select' => 'preg1, preg1_observaciones',
            'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}"
        ));
        $resp['preg1'] = GestionDemostracion::model()->findAll($criteria);

        $criteria = new CDbCriteria(array(
            'select' => 'fecha',
            'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}"
        ));
        $fecha = GestionTestDrive::model()->findAll($criteria);

        $resp['fecha'] = $fecha;

        return $resp;
    }

    public function getNegociacion($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}"
        ));
        $test = GestionFinanciamiento::model()->count($criteria);
        return $test;
    }

    public function getTestDriveOnly($id_informacion) {
        //die($id_informacion);
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion} AND (test_drive = 1 OR test_drive = 0)"
        ));
        $test = GestionTestDrive::model()->count($criteria);
        return $test;
    }

    public function getTestDriveYesNot($id_informacion, $id_vehiculo, $inline) {
        //echo 'id informacion: '.$id_informacion.', id vehiculo: '.$id_vehiculo;
        $data = '';
        $test = GestionDemostracion::model()->findAll(array('condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo} AND preg1 = 'No'"));

        foreach ($test as $value) {
            $data .= '<div class="btn-group" role="group" aria-label="..."><a class="btn btn-tomate btn-xs btn-rf" target="_blank">No</a>';
            switch ($value['preg1_observaciones']) {
                case 0:
                    $obs = 'No tiene licencia';
                    break;
                case 1:
                    $obs = 'No tiene tiempo';
                    break;
                case 2:
                    $obs = 'No desea';
                    break;
                case 4:
                    $obs = 'No, pero realizará en el futuro';
                    break;
                case 5:
                    $obs = 'Modelo no disponible';
                    break;

                default:
                    break;
            }
            $data .= '<a class="btn btn-default btn-xs btn-rf">' . $obs . '</a><br /><br />';
        }
        $test = GestionTestDrive::model()->findAll(array('condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo} AND test_drive = 1"));
        $in = 1;
        foreach ($test as $value) {
            //echo $value['img'];
            $data .= '<div class="btn-group" role="group" aria-label="..."><a class="btn btn-warning btn-xs btn-rf" target="_blank">Si</a><a class="btn btn-default btn-xs btn-rf">' . $value['observacion'] . '</a><a class="fancybox btn btn-success btn-xs" href="#' . $in . '">Licencia</a>'
                    . '<a href="' . Yii::app()->createUrl('site/pdf', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)) . '" class="btn btn-warning btn-xs" target="_blank" style="margin-left:2px;">PDF Prueba Manejo</a></div><br /><br />'
                    . '<div id="' . $in . '" style="width:auto;display: none;"><img src="' . Yii::app()->request->baseUrl . '/images/uploads/' . $value['img'] . '"/></div>';
            $in++;
        }
        return $data;
    }

    /**
     * Sabe numero de test drive si testdrive es 1(foto) o test drive 2(repite)
     * @param type $id_informacion
     * @param type $id_vehiculo
     * @return type
     */
    public function getTestDriveRep($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            'condition' => "(id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}) AND (test_drive = 1 OR test_drive = 2)"
        ));
        $test = GestionTestDrive::model()->count($criteria);
        return $test;
    }

    public function getTestObs($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo} AND test_drive = 0"
        ));
        $test = GestionTestDrive::model()->count($criteria);
        return $test;
    }

    public function getModeloTestDrive($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        $nombre = $this->getModel($modelo->modelo);
        return $nombre;
    }

    public function getVehiculoid($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $vec = GestionVehiculo::model()->find($criteria);
        return $vec->id;
    }

    public function getVersionTestDrive($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        $nombre_version = $this->getVersion($modelo->version);
        return $nombre_version;
    }

    public function getDealerId($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);

        if ($dealer->concesionario_id == 0) {
            $criteria2 = new CDbCriteria(array(
                'condition' => "usuario_id={$id}"
            ));
            $usuario = Grupoconcesionariousuario::model()->find($criteria2);
            return $usuario->concesionario_id;
        } else {
            return $dealer->dealers_id;
        }
    }
    
    public function getDealerIdUnique($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);
        return $dealer->concesionario_id;
    }

    public function getDealerIdAC($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);

        if ($dealer->concesionario_id == 0) {
            $criteria2 = new CDbCriteria(array(
                'condition' => "usuario_id={$id}"
            ));
            $usuario = Grupoconcesionariousuario::model()->find($criteria2);
            return $usuario->concesionario_id;
        } else {
            return $dealer->dealers_id;
        }
    }

    public function getResponsablesByGrupo($grupo_id, $cargo_id) {
        $array_dealers = array();
        $criteria = new CDbCriteria(array(
            'condition' => "usuario_id={$usuario_id}"
        ));
        $dealers = Usuarios::model()->findAll($criteria);
        $counter = 0;
        foreach ($dealers as $value) {
            //echo 'asdasd'.$value['concesionario_id'];
            $array_dealers[$counter] = $value['concesionario_id'];
            $counter++;
        }
        return $array_dealers;
    }

    public function getDealerGrupo($usuario_id) {
        $array_dealers = array();
        $criteria = new CDbCriteria(array(
            'condition' => "usuario_id={$usuario_id}"
        ));
        $dealers = Grupoconcesionariousuario::model()->findAll($criteria);
        $counter = 0;
        foreach ($dealers as $value) {
            //echo 'asdasd'.$value['concesionario_id'];
            $array_dealers[$counter] = $value['concesionario_id'];
            $counter++;
        }
        return $array_dealers;
    }

    public function getDealerGrupoConcesionario($usuario_id) {
        $array_dealers = array();
        $criteria = new CDbCriteria(array(
            'condition' => "usuario_id={$usuario_id}"
        ));
        $dealers = Grupoconcesionariousuario::model()->findAll($criteria);
        $counter = 0;
        foreach ($dealers as $value) {
            //echo 'asdasd'.$value['concesionario_id'];
            $array_dealers[$counter] = $value['concesionario_id'];
            $counter++;
        }
        return $array_dealers;
    }

    public function getDealerGrupoConc($grupo_id) {
        $array_dealers = array();
        if($grupo_id == 1000 || $grupo_id == ''){
            $cargo_id = (int) Yii::app()->user->getState('cargo_id');
            $grupo = (int) Yii::app()->user->getState('grupo_id');

            switch ($cargo_id) {
                case 61: # AEKIA
                    $dealers = GrConcesionarios::model()->findAll();
                    break;
                case 69: # GERENTE COMERCIAL GRUPO CONCESIONARIOS
                    $dealers = GrConcesionarios::model()->findAll(array("condition" => "id_grupo = {$grupo}"));
                    break;
                case 85: # JEFE DE VENTAS WEB
                    $dealers = GrConcesionarios::model()->findAll(array("condition" => "id_grupo = {$grupo}"));
                    break;    
                
                default:
                    # code...
                    break;
            }
            
        }else{
            $dealers = GrConcesionarios::model()->findAll(array("condition" => "id_grupo = {$grupo_id}"));
        }
        $counter = 0;
        foreach ($dealers as $value) {
            //echo 'asdasd'.$value['concesionario_id'];
            $array_dealers[$counter] = $value['dealer_id'];
            $counter++;
        }
        return $array_dealers;
    }
    
    public function getGrupoUsuario($usuario_id) {
        $user = Usuarios::model()->find(array('condition' => "id = {$usuario_id}"));
        if ($user != NULL) {
            return $user->grupo_id;
        }else{
            return 0;
        }
    }

    /**
     * 
     * @return array arraydealers con las ids de los concesionarios
     */
    public function getResponsablesVariosConc($tipo) {
        $id_responsable = Yii::app()->user->getId();
        $array_dealers = array();
        $dealers = Grupoconcesionariousuario::model()->findAll(array('condition' => "usuario_id={$id_responsable} AND tipo_id = {$tipo}"));
        $counter = 0;
        foreach ($dealers as $value) {
            //echo 'asdasd'.$value['concesionario_id'];
            $array_dealers[$counter] = $value['concesionario_id'];
            $counter++;
        }
        return $array_dealers;
    }

    public function getDealerGrupoConcUsuario($id_responsable,$tipo) {

        $array_dealers = array();
        $criteria = new CDbCriteria(array(
            'condition' => "usuario_id={$id_responsable} AND tipo_id = {$tipo}"
        ));
        $dealers = Grupoconcesionariousuario::model()->findAll($criteria);
        $counter = 0;
        if (count($dealers) > 0) {
            foreach ($dealers as $value) {
                //echo 'asdasd'.$value['concesionario_id'];
                $array_dealers[$counter] = $value['concesionario_id'];
                $counter++;
            }
        } else {
            // sacar grupo id del asesor segun id responsable
            $grupo = Usuarios::model()->find(array('condition' => "id =  {$id_responsable}"));
            //die('id grupo: '.$grupo->grupo_id);
            // armar array con los concecionarios del grupo
            $deal = GrConcesionarios::model()->findAll(array('condition' => "id_grupo = {$grupo->grupo_id} AND provincia <> 0"));
            foreach ($deal as $value) {
                //echo 'asdasd'.$value['concesionario_id'];
                $array_dealers[$counter] = $value['dealer_id'];
                $counter++;
            }
        }
        return $array_dealers;
    }

    public function getNombreConcesionario($id) {
        //die('id-----: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $usuarios = Dealers::model()->find($criteria);
        if ($usuarios) {
            return $usuarios->name;
        } else {
            return 'NA';
        }
    }

    public function getNameConcesionario($id) {
        //die('id: '.$id);
        $dealer = Usuarios::model()->findByPk(array('condition' => "id={$id}"));
        if ($dealer->concesionario_id == 0) {
            //die('enter no conc');
            $criteria2 = new CDbCriteria(array(
                'condition' => "usuario_id={$id}"
            ));
            $usuario = Grupoconcesionariousuario::model()->findByPk($criteria2);
            $cri = new CDbCriteria(array(
                'condition' => "id={$usuario->concesionario_id}"
            ));
            $deal = Dealers::model()->findAll($cri);
            return $deal->name;
        } else {
            $criteria2 = new CDbCriteria(array(
                'condition' => "id={$dealer->concesionario_id}"
            ));
            $deal = Dealers::model()->find($criteria2);
            return $deal->name;
        }



        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);
        //return $dealer->concesionario_id;
        $id_conc = $dealer->consecionario->dealer->id;
        $criteria2 = new CDbCriteria(array(
            'condition' => "id={$id_conc}"
        ));
        $deal = Dealers::model()->find($criteria2);
        return $deal->name;
    }

    public function getNameConcesionarioById($id) {
        //die('id: '.$id);
        if($id == 1000){
            return 'Todos';
        }
        if (empty($id)) {
            $grupo_id = (int) Yii::app()->user->getState('grupo_id');
            $area_id = (int) Yii::app()->user->getState('area_id');
            if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14){
                return 'Todos';
            }else{
                $grupo = GrGrupo::model()->find(array('condition' => "id={$grupo_id}"));
                return $grupo->nombre_grupo;
            }
            
        } else {
            $criteria = new CDbCriteria(array(
                'condition' => "id={$id}"
            ));
            $dealer = Dealers::model()->find($criteria);
            //return $dealer->concesionario_id;
            if ($dealer) {
                return $dealer->name;
            } else {
                return 0;
            }
        }
    }

    public function getConcesionarioId($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = GestionInformacion::model()->find($criteria);
        return $dealer->dealer_id;
    }

    public function getResponsableId_gerent($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = GestionInformacion::model()->find($criteria);
        return $dealer->responsable;
    }

    public function getConcesionarioDealerId($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $usuarios = Usuarios::model()->find($criteria);
        if ($usuarios) {
            return $usuarios->dealers_id;
        } else {
            return 0;
        }
    }

    public function getConcesionarioDealerIdIngresado($id_informacion){
        //die($id_informacion);
        $info = GestionInformacion::model()->find(array('condition' => "id = {$id_informacion}"));
        if($info){
            return $info->dealer_id;
        }else{
            return 0;
        }
    }

    public function getConcesionarioGrupoRuc($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $usuarios = Usuarios::model()->find($criteria);
        $grupo_id = $usuarios->grupo_id;

        $criteria2 = new CDbCriteria(array(
            'condition' => "id={$grupo_id}"
        ));
        $grupo = GrGrupo::model()->find($criteria2);
        return $grupo->ruc;
    }

    public function getNombreProforma($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "concesionario_id={$id}"
        ));
        $codigo = GestionCodigocc::model()->find($criteria);
        return $codigo->nombre_proforma;
    }

    public function getResponsableId($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = GestionInformacion::model()->find($criteria);
        return $dealer->responsable;
    }

    public function getResponsableIdByIdInformacion($id_informacion) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id_informacion}"
        ));
        $dealer = GestionInformacion::model()->find($criteria);
        return $dealer->responsable;
    }

    public function getResponsableNombres($id) {
        //die($id);
        if($id == 10000){
            return 'Todos';
        }
        if ($id == null) {
            return 'NA';
        }
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);
        if ($dealer != NULL) {
            return ucfirst($dealer->nombres) . ' ' . ucfirst($dealer->apellido);
        } else {
            return 'NA';
        }
    }

    public function getCityId($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $city = Dealers::model()->find($criteria);
        return $city->cityid;
    }

    public function getProvinciaId($id) {
        //echo 'id en get provincia: '.$id.'<br />';
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $prov = Dealercities::model()->find($criteria);
        return $prov->id_provincia;
    }

    public function getCedulaCotizacion($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $prov = GestionNuevaCotizacion::model()->find($criteria);
        return $prov->cedula;
    }

    public function getCategorizacion($id_informacion) {
        //die('id:'.$id_informacion);
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion}"
        ));
        $prov = GestionConsulta::model()->find($criteria);
        if ($prov) {
            return $prov->preg7;
        } else {
            return 'NA';
        }
    }
    
    public function getCargo($id) {
        $cargo = Usuarios::model()->find(array('condition' => "id = {$id}"));
        if($cargo){
            return $cargo->cargo_id; 
        }else{
            return 0;
        }
    }

    public function getCargoAdicional($id) {
        $cargo = Usuarios::model()->find(array('condition' => "id = {$id}"));
        if($cargo){
            return $cargo->cargo_adicional; 
        }else{
            return 0;
        }
    }

    public function getPaso($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->paso;
    }

    /**
     * Funcion que retorna el paso donde esta el cliente
     * @param type $id_informacion del cliente
     * @return int paso 
     */
    public function getPasoInt($id_informacion) {
        $ps = GestionDiaria::model()->find(array('condition' => "id_informacion = {$id_informacion}"));
        $paso = 0;
        $str = strlen($ps->paso);
        if ($str == 3) {
            $paso = 3;
        } else {
            $paso = (int) $ps->paso;
        }
        return $paso
        ;
    }

    public function getPasoGestionDiaria($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->paso;
    }

    public function getMedioContacto($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->medio_contacto;
    }

    public function getDesiste($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->desiste;
    }

    public function getSeguimiento($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->proximo_seguimiento;
    }

    public function getFechaRegistroAgendamiento($id_informacion){
        $ag = GestionAgendamiento::model()->find(array('condition' => "id_informacion = {$id_informacion}",'limit' => "1"));
        return $ag->fecha;
    }

    public function getCategorizacionSGC($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionConsulta::model()->find($criteria);
        return $ps->preg7;
    }

    public function getFuenteSGC($id_informacion) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion = {$id_informacion}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->fuente_contacto;
    }

    public function getStatusSGC($id_informacion) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->status;
    }

    public function getPasoNotificacion($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $ps = GestionNotificaciones::model()->find($criteria);
        return $ps->paso;
    }

    public function getPasoNotificacionDiaria($id_informacion) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion = {$id_informacion}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->paso;
    }

    public function getPasoSeguimiento($id) {
        switch ($id) {
            case '1-2':
                $paso = '1/2 - Prospección';
                break;
            case '3':
                $paso = '3 - Recepción';
                break;
            case '4':
                $paso = '4 - Consulta';
                break;
            case '5':
                $paso = '5 - Presentación';
                break;
            case '6':
                $paso = '6 - Demostración';
                break;
            case '7':
                $paso = '7 - Negociación';
                break;
            case '8':
                $paso = '8 - Cierre';
                break;
            case '9':
                $paso = '9 - Entrega';
                break;
            case '10':
                $paso = '10 - Seguimiento';
                break;

            default:
                break;
        }
        return $paso;
    }

    public function getFuente($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $ps = GestionNuevaCotizacion::model()->find($criteria);
        return $ps->fuente;
    }

    public function getFuenteExonerados($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $ps = GestionInformacion::model()->find($criteria);
        return $ps->tipo_form_web;
    }

    public function getIdGestion($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        if ($ps)
            return $ps->id;
        else
            return false;
    }

    public function getFecha($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionProspeccionRp::model()->find($criteria);
        return $ps->preg5_sec1;
    }

    public function getGestion($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionDiaria::model()->count($criteria);
        if ($ps > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getConsulta($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id}"
        ));
        $ps = GestionConsulta::model()->count($criteria);
        if ($ps > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getFinanciamiento($id_informacion, $id_vehiculo) {
        $tipo = GestionVehiculo::model()->find(array("condition" => "id_informacion = {$id_informacion} AND id = {$id_vehiculo}"));
        if ($tipo) {
            return $tipo->tipo_credito;
        } else {
            return 0;
        }
    }

    public function getFotoEntregaDetail($id_informacion) {
        $con = Yii::app()->db;
        $sql = "SELECT g.id_informacion, gd.foto_entrega, gd.id_gestion FROM gestion_paso_entrega g 
                INNER JOIN gestion_paso_entrega_detail gd ON g.id = gd.id_gestion 
                WHERE g.id_informacion = {$id_informacion} AND gd.id_paso = 10 LIMIT 0,1 ";
        $request = $con->createCommand($sql)->query();
        $foto_entrega = '';
        foreach ($request as $value) {
            $foto_entrega = $value['foto_entrega'];
        }
        return $foto_entrega;
    }

    public function getPrecioNormal($id_version) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_versiones = {$id_version}",
        ));
        $version = Versiones::model()->find($criteria);
        return $version->precio;
    }

    public function getArg($ar) {
        $params = explode('@', $ar);
        $cadena = '';
        foreach ($params as $value) {
            $cadena .= $value . ', ';
        }
        return $cadena;
    }

    public function getIdConsulta($id_informacion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion}",
        ));
        $version = GestionConsulta::model()->find($criteria);
        return $version->id;
    }

    public function getIdSolicitudCredito($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}",
        ));
        $version = GestionSolicitudCredito::model()->find($criteria);
        return $version->id;
    }

    /**
     * Search in the table gestion_solicitud_credito by id_informacion and id_vehiculo
     * @param int $id_informacion the ID of client id_informacion
     * @param int $id_vehiculo the ID of vehicle's client
     * @return int number of matches
     */
    public function getNumSolicitudCredito($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}",
        ));
        $c = GestionSolicitudCredito::model()->count($criteria);
        return $c;
    }

    public function getAnswer($tipo, $id) {
        $preg = FALSE;
        switch ($tipo) {
            case 1: // preguntas paso 1-2 prospeccion
                /* $criteria = new CDbCriteria(array(
                  'condition' => "id_informacion={$id}"
                  ));
                  $pr = GestionProspeccionRp::model()->count($criteria);
                  if ($pr > 0) {
                  return TRUE;
                  } */
                $con = Yii::app()->db;
                $sqlpr = "SELECT * FROM gestion_diaria WHERE id_informacion = {$_GET['id']} AND prospeccion = 1";
                //die($sql);            
                $request = $con->createCommand($sqlpr);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    return TRUE;
                }
                break;
            case 2: // preguntas paso 3-4 recepcion consulta
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id} AND status = 'ACTIVO' AND preg7 <> ''"
                ));
                $pr = GestionConsulta::model()->count($criteria);
                if ($pr > 0) {
                    return TRUE;
                } else {
                    return FALSE;
                }
                break;

            case 3: // preguntas paso 5 - demostracion
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id}"
                ));
                $pr = GestionPresentacion::model()->count($criteria);
                if ($pr > 0) {
                    return TRUE;
                }
                break;
            case 4: // pregunta 6 
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id}"
                ));
                $pr = GestionDemostracion::model()->count($criteria);
                if ($pr > 0) {
                    return TRUE;
                }
                break;
            case 5:
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id}"
                ));
                $pr = GestionFinanciamiento::model()->count($criteria);
                if ($pr > 0) {
                    return TRUE;
                }
                break;
            case 6:
                $cr = GestionFactura::model()->count(array('condition' => "id_informacion=:match", 'params' => array(':match' => $id)));
                if ($cr > 0) {
                    return TRUE;
                }
                break;
            case 7:
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id}"
                ));
                $pr = GestionPasoEntrega::model()->count($criteria);
                if ($pr > 0) {
                    return TRUE;
                }
                break;
            case 8:
                $criteria = new CDbCriteria(array(
                    'condition' => "paso=10 AND id_informacion = {$id}"
                ));
                $pr = GestionDiaria::model()->count($criteria);
                if ($pr > 0) {
                    return TRUE;
                }
                break;

            default:
                break;
        }
    }

    public function getNoTest($id) {
        switch ($id) {
            case 0:
                return 'No tiene licencia';
                break;
            case 1:
                return 'No tiene tiempo';
                break;
            case 2:
                return 'No desea';
                break;

            default:
                break;
        }
    }

    public function getLastEntrega() {
        $criteria = new CDbCriteria(array(
            'limit' => 1,
            'order' => 'id DESC'
        ));
        $ent = GestionHojaEntrega::model()->find($criteria);
        return $ent->id;
    }

    public function getLastSolicitudCotizacion($id_informacion, $id_vehiculo) {
        $ent = GestionSolicitud::model()->find(array('condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"));
        return $ent->id;
    }

    public function getLastProforma($id_informacion, $id_vehiculo) {
        $ent = GestionProforma::model()->find(array('condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"));
        return $ent->id;
    }

    public function getProformaCliente($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_vehiculo = {$id_vehiculo} AND id_informacion = {$id_informacion}",
            'order' => 'id DESC'
        ));
        $ent = GestionFinanciamiento::model()->find($criteria);
        return $ent->id_pdf;
    }
    
    public function getSolicitudCliente($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_vehiculo = {$id_vehiculo} AND id_informacion = {$id_informacion}",
            'order' => 'id DESC'
        ));
        $ent = GestionProforma::model()->find($criteria);
        return $ent->id;
    }

    public function getHojaEntregaCliente($id_informacion, $id_vehiculo) {
        //die('id informacion: '.$id_vehiculo);
        $criteria = new CDbCriteria(array(
            "condition" => "id_vehiculo = {$id_vehiculo}"
        ));
        $ent = GestionHojaEntrega::model()->find($criteria);
        if (!is_null($ent) && !empty($ent)) {
            return $ent->id;
        } else {
            return 'NA';
        }
    }

    public function getNumProforma($id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_vehiculo = {$id_vehiculo}"
        ));
        $ent = GestionProforma::model()->find($criteria);
        return $ent->id;
    }

    public function getIdModelo($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        return $modelo->modelo;
    }

    public function getIdVersion($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        return $modelo->version;
    }

    public function getNombreModelo($id_informacion, $id_vehiculo) {
        //die('id vehiculo: '.$id_vehiculo);
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id_vehiculo}",
        ));

        $version = GestionVehiculo::model()->find($criteria);
        $id_modelo = $version->modelo;
        $id_version = $version->version;
        $criteria2 = new CDbCriteria(array(
            "condition" => "id_modelos = {$id_modelo}",
        ));
        $modelo = Modelos::model()->find($criteria2);
        $nombre_modelo = $modelo->nombre_modelo;
        $criteria3 = new CDbCriteria(array(
            "condition" => "id_versiones = {$id_version}",
        ));
        $ve = Versiones::model()->find($criteria3);
        $nombre_version = $ve->nombre_version;

        return $nombre_modelo . ' ' . $nombre_version;
    }

    public function getFirma($id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_vehiculo = {$id_vehiculo}",
        ));
        $modelo = GestionTestDrive::model()->find($criteria);
        return $modelo->firma;
    }

    public function getHojaStatus($id_informacion, $id_vehiculo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}",
        ));
        $modelo = GestionHojaEntregaSolicitud::model()->find($criteria);
        if (count($modelo) > 0) {
            return $modelo->status;
        } else {
            return false;
        }
    }

    public function getIfVehicle($id_informacion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion}",
        ));
        $modelo = GestionConsulta::model()->find($criteria);
        return $modelo->preg1_sec2;
    }

    public function getCategotizacion($id_agendamiento) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id_agendamiento}",
        ));
        $modelo = GestionAgendamiento::model()->find($criteria);
        return $modelo->agendamiento;
    }

    public function getIdFinOp($id_financiamiento, $num_cotizacion) {
        //die('num cotizacion: '.$num_cotizacion.', id finan: '.$id_financiamiento);
        $criteria = new CDbCriteria(array(
            "condition" => "id_financiamiento = {$id_financiamiento} AND num_cotizacion = {$num_cotizacion}",
        ));
        $modelo = GestionFinanciamientoOp::model()->find($criteria);
        if ($modelo) {
            return $modelo->id;
        } else {
            return false;
        }
    }

    public function getGestionDiariaId($id_informacion) {
        //die('id: '.$id_informacion);
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion}",
        ));
        $modelo = GestionDiaria::model()->find($criteria);
        return $modelo->id;
    }

    public function getGestionDiariaPaso($id_informacion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion}",
        ));
        $modelo = GestionDiaria::model()->find($criteria);
        return $modelo->paso;
    }

    public function getFinanOp($id_financiamiento, $num_cotizacion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_financiamiento = {$id_financiamiento} AND num_cotizacion = {$num_cotizacion}"
        ));
        $modelo = GestionFinanciamientoOp::model()->count($criteria);
        if ($modelo > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getNumFinOp($id_financiamiento) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_financiamiento = {$id_financiamiento}"
        ));
        $modelo = GestionFinanciamientoOp::model()->count($criteria);
        if ($modelo > 0) {
            return $modelo;
        } else {
            return false;
        }
    }

    public function getStatusUsados($id_informaccion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informaccion}",
            'limit' => 1,
            'order' => 'id DESC'
        ));
        $modelo = GestionAgendamiento::model()->find($criteria);
        if ($modelo) {
            return $modelo->observaciones;
        } else {
            return 'NA';
        }
    }

    public function getGaleriaUsados($id_informaccion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informaccion}"
        ));
        $modelo = GestionConsulta::model()->find($criteria);
        if ($modelo) {
            return $modelo->preg2_sec1;
        } else {
            return 'NA';
        }
    }

    public function getObsevacionesTest($id_informacion) {
        //die('id info: '.$id_informacion);
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion}"
        ));
        $modelo = GestionDemostracion::model()->find($criteria);
        if ($modelo) {
            return $modelo->preg1_observaciones;
        } else {
            return 'NA';
        }
    }

    public function getObsevacionesTestYes($id_informacion) {
        //die('id info: '.$id_informacion);
        $criteria = new CDbCriteria(array(
            "condition" => "id_informacion = {$id_informacion} AND test_drive = 1"
        ));
        $modelo = GestionTestDrive::model()->find($criteria);
        if ($modelo) {
            return $modelo->observacion;
        } else {
            return 'NA';
        }
    }

    public function sendMail($html, $subject, $to, $ccToFrom = NULL, $from = NULL, $fromName = NULL, &$result = NULL) {
        $phpExcelPath = Yii::getPathOfAlias('ext.mandrill');
        require_once $phpExcelPath . '/src/Mandrill.php';

        try {

            if ($ccToFrom) { /* Es cuando envio a varios el mismo email */
                $mails[] = array('email' => $to);
                foreach ($ccToFrom as $ccEmail) {
                    $mails[] = array('email' => $ccEmail);
                }
            } else /* Es cuando es un envio normal */
                $mails = array(array('email' => $to));

            $from = (empty($from)) ? Constantes::FROM_EMAIL : $from;
            $fromName = (empty($fromName)) ? Constantes::FROM_NAME_EMAIL : $fromName;


            $mandrill = new Mandrill(Constantes::KEY_API_EMAIL);
            $message = array(
                'html' => $html,
                'text' => '',
                'subject' => $subject,
                'from_email' => $from,
                'from_name' => $fromName,
                'to' => $mails,
                'headers' => array('Reply-To' => $from),
                'track_opens' => TRUE,
                'track_clicks' => TRUE
            );

            $rs = $mandrill->messages->send($message);
            $result = json_encode($rs);
            return TRUE;
        } catch (Mandrill_Error $e) {

            $result = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            echo $result;
            return FALSE;
        }

        return true;
    }

    public function traerConcesionariosU($usuario, $solodatos) {
        $model = Grupoconcesionariousuario::model()->findAll(array("condition" => 'usuario_id = ' . (int) $usuario));
        if (!empty($model)) {
            /* VERIFICAMOS SI PIDE SOLO DATOS EN LA VARIABLE $solodatos == 1 */
            if ($solodatos == 1) {
                $imp = '';
                foreach ($model as $m) {

                    $d = Dealers::model()->find(array("condition" => "id =" . $m->concesionario_id));
                    //$imp.=$m->consecionario->nombre . ' -- ';
                    if (!empty($d))
                        $imp.=$d->name . ' -- ';
                }
                return $imp;
            } else {
                return $model;
            }
            /* FIN */
        }
    }

    public function traerConcesionariosGR($usuario, $solodatos) {
        $model = GrConcesionarios::model()->findAll(array("condition" => 'id = ' . (int) $usuario));
        if (!empty($model)) {
            /* VERIFICAMOS SI PIDE SOLO DATOS EN LA VARIABLE $solodatos == 1 */
            if ($solodatos == 1) {
                $imp = '';
                foreach ($model as $m) {
                    $d = Dealers::model()->find(array("condition" => "id =" . $m->dealer_id));
                    //$imp.=$m->consecionario->nombre . ' -- ';
                    if (!empty($d))
                        $imp.=$d->name . ' -- ';
                }
                return $imp;
            } else {
                return $model;
            }
            /* FIN */
        }
    }

    public function redondear_dos_decimal($valor) {
        $float_redondeado = round($valor * 100) / 100;
        return $float_redondeado;
    }

    public function traernocompradores() {
        date_default_timezone_set("America/Bogota");

        $datosC = GestionDiaria::model()->findAll(array('condition' => 'desiste = 1 and encuestado=0'));
        $cargo_id = Cargo::model()->find(array('condition' => 'codigo = "' . Constantes::CDG . '"'));
        $usuarios = Usuarios::model()->findAll(array('condition' => 'estado = "ACTIVO" and cargo_id =' . $cargo_id->id));

        if (!empty($datosC)) {
            $maximo = number_format(count($datosC) / count($usuarios), 0);
            $actual = 0;
            $contactual = 0;
            $posicion = 0;
            $usuario_listO = array();
            $usuario_list = array();

            foreach ($usuarios as $u) {
                $usuario_list[$actual++] = $u->id;
            }

            // print_r($usuario_list);



            foreach ($datosC as $d) {

                if ($contactual == $maximo) {

                    $contactual = 0;
                    $posicion++;
                }

                if ($posicion <= count($usuarios) && !empty($usuario_list[$posicion]) && !empty($d->gestioninformacion->telefono_casa)) {
                    //echo $usuario_list[$posicion].'<br>';
                    $cotizacion = new Nocompradores();
                    $cotizacion->gestiondiaria_id = (int) $d->id;
                    $po = array_rand($usuario_list);
                    $cotizacion->usuario_id = $usuario_list[$po];
                    $cotizacion->nombre = $d->gestioninformacion->nombres;
                    $cotizacion->apellido = $d->gestioninformacion->apellidos;
                    if (!empty($d->gestioninformacion->cedula)) {
                        $id = $d->gestioninformacion->cedula;
                    } else if (!empty($d->gestioninformacion->ruc)) {
                        $id = $d->gestioninformacion->ruc;
                    } else if (!empty($d->gestioninformacion->pasaporte)) {
                        $id = $d->gestioninformacion->pasaporte;
                    }
                    $cotizacion->cedula = $id;
                    $cotizacion->email = $d->gestioninformacion->email;
                    $cotizacion->ceular = $d->gestioninformacion->celular;
                    $cotizacion->convencional = $d->gestioninformacion->telefono_casa;

                    if ($cotizacion->save()) {
                        $d->encuestado = 1;
                        $d->realizado = date('Y-m-d h:i:s');
                        $d->update();
                    }
                }
                $contactual++;
            }
        }
    }

    public function traernocompradores2() {
        date_default_timezone_set("America/Bogota");

        $datosC = GestionDiaria::model()->findAll(array('condition' => 'desiste = 1 and encuestado=0'));
        $cargo_id = Cargo::model()->find(array('condition' => 'codigo = "' . Constantes::CDG . '"'));
        $usuarios = Usuarios::model()->findAll(array('condition' => 'estado = "ACTIVO" and cargo_id =' . $cargo_id->id));

        if (!empty($datosC)) {
            $maximo = number_format(count($datosC) / count($usuarios), 0);
            $actual = 0;
            $contactual = 0;
            $posicion = 0;
            $usuario_list = array();
            foreach ($usuarios as $u) {
                $usuario_list[$actual++] = $u->id;
            }


            foreach ($datosC as $d) {

                if ($contactual == $maximo) {

                    $contactual = 0;
                    $posicion++;
                }

                if ($posicion <= count($usuarios) && !empty($usuario_list[$posicion]) && !empty($d->gestioninformacion->telefono_casa) && !empty($d->gestioninformacion->cedula)) {
                    //echo $usuario_list[$posicion].'<br>';
                    $cotizacion = new Nocompradores();
                    $cotizacion->gestiondiaria_id = (int) $d->id;
                    // $cotizacion->usuario_id = $usuario_list[$posicion];
                    $po = array_rand($usuario_list);
                    $cotizacion->usuario_id = $usuario_list[$po];
                    $cotizacion->nombre = $d->gestioninformacion->nombres;
                    $cotizacion->apellido = $d->gestioninformacion->apellidos;
                    $cotizacion->cedula = $d->gestioninformacion->cedula;
                    $cotizacion->email = $d->gestioninformacion->email;
                    $cotizacion->ceular = $d->gestioninformacion->celular;

                    if ($cotizacion->save()) {
                        $d->encuestado = 1;
                        $d->realizado = date('Y-m-d h:i:s');
                        $d->update();
                    }
                }
                $contactual++;
            }
        }
    }

    public function getCheckedAcc($nombre, $stringAccesorios) {
        // echo $stringAccesorios;
        $paramAcc = explode('@', $stringAccesorios);
        if (in_array($nombre, $paramAcc)) {
            echo 'checked="checked"';
        } else {
            echo 'none';
        }
//           echo '<pre>';
//           print_r($paramAcc);
//           echo '</pre>';
    }

    public function getLabelAcc($nombre, $stringAccesorios) {
        // echo $stringAccesorios;
        $paramAcc = explode('@', $stringAccesorios);
        if (in_array($nombre, $paramAcc)) {
            echo 'label-price';
        }
//           echo '<pre>';
//           print_r($paramAcc);
//           echo '</pre>';
    }

    /**
     * Funcion que devuelve el array de accesorios indexados por numeros
     * @param int $id_vehiculo
     * @param int $id_version
     * @param string $string_accesorios
     * @return array
     */
    public function getListAccesorios($id_vehiculo, $id_version, $string_accesorios) {
        //die($string_accesorios);
        $criteria2 = new CDbCriteria(array('condition' => "id_vehiculo = {$id_vehiculo} AND id_version = {$id_version} AND status = 1 AND opcional = 0",
            'order' => 'accesorio'));
        $cn = GestionAccesorios::model()->count($criteria2);
        $listAcc = array();
        $count = 1;
        if ($cn > 0) {
            $acc = GestionAccesorios::model()->findAll($criteria2);
            foreach ($acc as $value) {
                $pos = strpos($string_accesorios, $value['accesorio']);
                if ($pos) {
                    array_push($listAcc, $count);
                }
                $count++;
            }
        }
        return $listAcc;
    }

    public function getFinanciamientoExo($id_informacion) {
        $dealers = GestionInformacion::model()->findByPk($id_informacion);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->tipo_form_web;
        } else {
            return 'NA';
        }
    }

    public function getTipoExo($id) {
        $dealers = GestionNuevaCotizacion::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->tipo;
        } else {
            return 'NA';
        }
    }

    public function getTipoExoInfo($id) {
        $dealers = GestionInformacion::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->tipo_ex;
        } else {
            return 'NA';
        }
    }

    public function getTipoExoPorcentaje($id) {
        $dealers = GestionInformacion::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->porcentaje_discapacidad;
        } else {
            return 'NA';
        }
    }

    public function getExonerado($id) {
        //die('id: '.$id);
        $tipo = '';
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $dealers = GestionInformacion::model()->find($criteria);
        if (!empty($dealers->tipo_form_web)) {
            switch ($dealers->tipo_form_web) {
                case 'exonerados':
                    $tipo = 'exo';
                    break;
                case 'usadopago':
                case 'usado':
                    $tipo = 'usado';
                    break;

                default:
                    break;
            }
        } else {
            if ($dealers->bdc == 1) {
                $tipo = 'bdc';
            } else {
                $tipo = 'seg';
            }
        }
        return $tipo;
    }

    // profile
    public function getSelectProfile($cargo_id, $dealer_id) {
        $id_responsable = Yii::app()->user->getId();
        $criteria2 = new CDbCriteria(array(
            'condition' => "id={$id_responsable}"
        ));
        $idgr = Usuarios::model()->find($criteria2);
        $id_grupo = $idgr->grupo_id;
        $data_select = array();
        switch ($cargo_id) {
            case 69: // gerente comercial
                // select de grupo
                $grupo = Grupo::model()->findAll();
                // select de concesionarios
                $datas = '<option value="">--Seleccione grupo--</option>';
                foreach ($grupo as $value) {
                    $datas .= '<option value="' . $value['id'] . '"';
                    if ($value['id'] == $id_grupo) {
                        $datas .= ' selected >';
                    } else {
                        $datas .= '>';
                    }
                    $datas .= $value['nombre_grupo'] . '</option>';
                }
                $data_select[0] = $datas;
                $criteria = new CDbCriteria(array(
                    'condition' => "id_grupo={$id_grupo}",
                    'order' => 'nombre asc'
                ));
                $conc = Concesionarios::model()->findAll($criteria);
                $data = '<option value="">--Seleccione concesionario--</option>';
                foreach ($conc as $ciudad) {
                    $data .= '<option value="' . $ciudad['dealer_id'] . '">' . $ciudad['nombre'] . '</option>';
                }
                $data_select[1] = $data;

                break;
            case 46: // super administrador
            case 4:
            case 45:
            case 48:
            case 57:
            case 58:
            case 60:
            case 61:
            case 62:
                // select de grupo
                $grupo = Grupo::model()->findAll();
                // select de concesionarios
                $datas = '<option value="">--Seleccione grupo--</option>';
                foreach ($grupo as $value) {
                    $datas .= '<option value="' . $value['id'] . '">';
                    $datas .= $value['nombre_grupo'] . '</option>';
                }
                $data_select[0] = $datas;
                $criteria = new CDbCriteria(array(
                    'condition' => "id_grupo={$id_grupo}",
                    'order' => 'nombre asc'
                ));
                $conc = Concesionarios::model()->findAll($criteria);
                $data = '<option value="">--Seleccione concesionario--</option>';
                foreach ($conc as $ciudad) {
                    $data .= '<option value="' . $ciudad['dealer_id'] . '">' . $ciudad['nombre'] . '</option>';
                }
                $data_select[1] = $data;
                break;


            default:
                break;
        }
        return $data_select;
    }

    public function getRandomKey($cargo_id, $dealer_id) {
        //echo 'dealer id: '.$dealer_id;
        // GENERACION Y ASIGNACION DE USUARIOS EXONERADOS DE LOS CLIENTES GENERADOS
        $array_ids = array();
        $id_responsable = Yii::app()->user->getId();
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        //$dealer_id = $this->getConcesionarioDealerId($id_responsable);
        $con = Yii::app()->db;
        $sql = "SELECT gr.*,u.status_asesor FROM grupoconcesionariousuario gr 
                    INNER JOIN usuarios u ON u.id = gr.usuario_id 
                    WHERE u.cargo_id = {$cargo_id}  AND u.status_asesor = 'ACTIVO' AND gr.concesionario_id = {$dealer_id}";
        //die($sql);            
        $request = $con->createCommand($sql);
        $posts = $request->queryAll();
        //die('count: ' . count($posts));
        if (count($posts) > 0) {
            foreach ($posts as $value) {
                $array_ids[] = $value['usuario_id'];
            }
            // ID DEL ASESOR A SER ASIGNADO CLIENTE
            $random_key = $array_ids[array_rand($array_ids)];
            // ID DEL ASESOR EN TABLA usuarios PONEMOS ESTADO INACTIVO
            Yii::app()->db
                    ->createCommand("UPDATE usuarios SET status_asesor='INACTIVO' WHERE id=:ID")
                    ->bindValues(array(':ID' => $random_key))
                    ->execute();
        } else {
            //die('enter else');
            $sql2 = "SELECT gr.*,u.status_asesor FROM grupoconcesionariousuario gr 
                    INNER JOIN usuarios u ON u.id = gr.usuario_id 
                    WHERE u.cargo_id = {$cargo_id}  AND u.status_asesor = 'INACTIVO' AND gr.concesionario_id = {$dealer_id}";
            $request = $con->createCommand($sql2);
            $post = $request->queryAll();
            foreach ($post as $value) {
                Yii::app()->db
                        ->createCommand("UPDATE usuarios SET status_asesor='ACTIVO' WHERE id=:ID")
                        ->bindValues(array(':ID' => $value['usuario_id']))
                        ->execute();
            }
            $sql = "SELECT gr.*,u.status_asesor FROM grupoconcesionariousuario gr 
                    INNER JOIN usuarios u ON u.id = gr.usuario_id 
                    WHERE u.cargo_id = {$cargo_id}  AND u.status_asesor = 'ACTIVO' AND gr.concesionario_id = {$dealer_id}";
            $request = $con->createCommand($sql);
            $posts = $request->queryAll();
            foreach ($posts as $value) {
                $array_ids[] = $value['usuario_id'];
            }
            // ID DEL ASESOR A SER ASIGNADO CLIENTE
            $random_key = $array_ids[array_rand($array_ids)];
            // ID DEL ASESOR EN TABLA usuarios PONEMOS ESTADO INACTIVO
            Yii::app()->db
                    ->createCommand("UPDATE usuarios SET status_asesor='INACTIVO' WHERE id=:ID")
                    ->bindValues(array(':ID' => $random_key))
                    ->execute();
        }
        return $random_key;
    }

    public function getEntregaPaso($id_informacion, $id_vehiculo, $paso) {
        $campo = '';
        switch ($paso) {
            case 1:
                $campo = 'envio_factura';
                break;
            case 2:
                $campo = 'emision_contrato';
                break;
            case 3:
                $campo = 'agendar_firma';
                break;
            case 4:
                $campo = 'alistamiento_unidad';
                break;
            case 5:
                $campo = 'pago_matricula';
                break;
            case 6:
                $campo = 'recepcion_contratos';
                break;
            case 7:
                $campo = 'recepcion_matricula';
                break;
            case 8:
                $campo = 'vehiculo_revisado';
                break;
            case 9:
                $campo = 'entrega_vehiculo';
                break;
            case 10:
                $campo = 'foto_entrega';
                break;

            default:
                break;
        }
        $criteria2 = new CDbCriteria(array(
            'condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo} AND {$campo} = 1"
        ));
        $entre = GestionPasoEntrega::model()->count($criteria2);
        if ($entre > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getPasoEntregaCon($id_informacion, $id_vehiculo) {
        $criteria2 = new CDbCriteria(array(
            'condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"
        ));
        $entre = GestionPasoEntrega::model()->find($criteria2);
        if (!is_null($entre) && !empty($entre)) {
            return $entre->paso;
        } else {
            return 0;
        }
    }

    public function getFechaObsEntrega($id_gestion, $id_paso) {
        $data = array();
        $criteria2 = new CDbCriteria(array(
            'condition' => "id_gestion = {$id_gestion} AND id_paso = {$id_paso}"
        ));
        $entre = GestionPasoEntregaDetail::model()->find($criteria2);
        if (!is_null($entre) && !empty($entre)) {
            if ($id_paso == 7) {
                $data['fecha'] = $entre->fecha_paso;
                $data['placa'] = $entre->placa;
            }
            if ($id_paso == 8) {
                $data['fecha'] = $entre->fecha_paso;
                $data['responsable'] = $entre->responsable;
            }
            if ($id_paso == 10) {
                $data['foto_entrega'] = $entre->foto_entrega;
                $data['foto_hoja_entrega'] = $entre->foto_hoja_entrega;
            } else {
                $data['fecha'] = $entre->fecha_paso;
                $data['observaciones'] = $entre->observaciones;
            }
            return $data;
        } else {
            return $data;
        }
    }

    public function getIdPasoEntrega($id_informacion, $id_vehiculo) {
        $criteria2 = new CDbCriteria(array(
            'condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"
        ));
        $entre = GestionPasoEntrega::model()->find($criteria2);
        if (!is_null($entre) && !empty($entre)) {
            return $entre->id;
        } else {
            return 0;
        }
    }

    public function getFormatFecha($fecha) {
        //die('fecha: '.$fecha);
        $params = explode(" ", $fecha);
        //die('dddd'.$params[1]);
        $data_fecha = '';
        switch ((string) $params[1]) {
            case '01':
                $mes = 'enero';
                break;
            case '02':
                $mes = 'febrero';
                break;
            case '03':
                $mes = 'marzo';
                break;
            case '04':
                $mes = 'abril';
                break;
            case '05':
                $mes = 'mayo';
                break;
            case '06':
                $mes = 'junio';
                break;
            case '07':
                $mes = 'julio';
                break;
            case '08':
                $mes = 'agosto';
                break;
            case '09':
                $mes = 'septiembre';
                break;
            case '10':
                $mes = 'octubre';
                break;
            case '11':
                $mes = 'noviembre';
                break;
            case '12':
                $mes = 'diciembre';
                break;

            default:
                break;
        }
        $data_fecha = $params[0] . ' de ' . $mes . ' del ' . $params[2];
        return $data_fecha;
    }

    public function getAsesoresByGrupo($grupo_id) {
        $con = Yii::app()->db;
        $sql = "SELECT * FROM usuarios WHERE grupo_id = {$grupo_id} AND cargo_id IN (77) ORDER BY nombres ASC";
        //die($sql);
        $requestr1 = $con->createCommand($sql);
        $requestr1 = $requestr1->queryAll();
        $data = '<option value="">--Seleccione Asesor--</option>';
        $data .= '<option value="all">Todos</option>';
        foreach ($requestr1 as $value) {
            $data .= '<option value="' . $value['id'] . '">';
            $data .= $this->getResponsableNombres($value['id']);
            $data .= '</option>';
        }

        echo $data;
    }

    public function getNameBanco($id) {
        if($id != null){
            $banco = GestionBancos::model()->find(array('condition' => "id = {$id}"));
            return $banco->nombre;
        }else{
            return '';
        }
        
    }

    public function getResponsableFirma($id_informacion) {
        $firma = GestionInformacion::model()->find(array('condition' => "id = {$id_informacion}"));
        return $firma->responsable;
    }

    public function getPrice($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_versiones='{$id}'"
        ));
        $vec = Versiones::model()->find($criteria);
        return $vec->precio;
    }

    public function getNombreCliente($id_informacion) {
        $gestion = GestionInformacion::model()->find(array("condition" => "id = {$id_informacion}"));
        if ($gestion) {
            return ucfirst($gestion->nombres) . '-' . ucfirst($gestion->apellidos);
        } else {
            return 'NA';
        }
    }

    public function getNombreClienteRGD($id_informacion) {
        $gestion = GestionInformacion::model()->find(array("condition" => "id = {$id_informacion}"));
        if ($gestion) {
            return ucfirst($gestion->nombres) . ' ' . ucfirst($gestion->apellidos);
        } else {
            return 'NA';
        }
    }

    /*     * **
     * Funcion para devolver los responsables de agencia para reasignacion de clientes
     */

    public function getResponsablesAgencia($id_responsable) {
        $dealer_id = $this->getConcesionarioDealerId($id_responsable);
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');

        if (empty($dealer_id)) {
            $array_dealers = $this->getDealerGrupoConc($grupo_id);
            $dealer_id = implode(', ', $array_dealers);
        }

        /*$cre = new CDbCriteria();
        $cre->condition = " cargo_id = 71 AND dealers_id IN ({$dealer_id}) ";

        if ($grupo_id == 4)// IOKARS
            $cre->condition = " cargo_id IN (71,73) AND dealers_id IN ({$dealer_id}) ";
        if ($grupo_id == 9)// MOTRICENTRO
            $cre->condition = " cargo_id IN (71,86) AND dealers_id IN ({$dealer_id}) ";
        if(($grupo_id ==  2 || $grupo_id ==  3) && $cargo_id == 85) // ASIAUTO Y KMOTOR - CARGO JEFE WEB  
            $cre->condition = " cargo_id = 86 AND dealers_id IN ({$dealer_id}) ";
        if($grupo_id == 2 && $cargo_adicional == 85){ // ASIAUTO - JEFE SHOWROOM/JEFE WEB
            $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,2);
            $dealerList = implode(', ', $array_dealers);
            $cre->condition = " cargo_id IN (71,86) AND dealers_id IN ({$dealerList}) ";  

        }
              
            
        $cre->order = " nombres ASC";
        $asesores = Usuarios::model()->findAll($cre);
        $data = '';
        foreach ($asesores as $value) {
            if($value['cargo_id'] == 86 || $value['cargo_adicional'] == 86){
                $data .= '<li><a id="' . $value['id'] . '" onclick="asignar(' . $value['id'] . ');" class="asign-lt lt-web">' . $value['nombres'] . ' ' . $value['apellido'] . '</a></li>';
            }else{
                $data .= '<li><a id="' . $value['id'] . '" onclick="asignar(' . $value['id'] . ');" class="asign-lt">' . $value['nombres'] . ' ' . $value['apellido'] . '</a></li>';
            }
            
        }
        return $data;*/
        $cre = new CDbCriteria();
        $cre->condition = " cargo_id = 71 AND dealers_id IN ({$dealer_id}) ";

        if ($grupo_id == 4)// IOKARS
            $cre->condition = " cargo_id IN (71,73) AND dealers_id IN ({$dealer_id}) ";
        if ($grupo_id == 9)//
            $cre->condition = " cargo_id IN (71,86) AND dealers_id IN ({$dealer_id}) ";
        if(($grupo_id ==  2 || $grupo_id ==  3) && $cargo_id == 85)  
            $cre->condition = " cargo_id = 86 AND dealers_id IN ({$dealer_id}) ";
            
        $cre->order = " nombres ASC";
        $asesores = Usuarios::model()->findAll($cre);
        $data = '';
        foreach ($asesores as $value) {
            $data .= '<li><a id="' . $value['id'] . '" onclick="asignar(' . $value['id'] . ');" class="asign-lt">' . $value['nombres'] . ' ' . $value['apellido'] . '</a></li>';
        }
        return $data;
    }

    public function getConcesionariosli($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_grupo={$id}",
            'order' => 'nombre asc'
        ));
        $conc = Concesionarios::model()->findAll($criteria);
        $data = '';
        foreach ($conc as $ciudad) {
            $data .= '<li><a id="' . $ciudad['dealer_id'] . '" class="asign-lt" onclick="asesor(' . $ciudad['dealer_id'] . ')">' . $ciudad['nombre'] . '</a></li>';
        }
        echo $data;
    }

    public function getStatusGestionDiaria($id_informacion) {
        //echo 'id: '.$id;
        $array_status = array();
        $st = GestionDiaria::model()->findAll(array("condition" => "id_informacion = {$id_informacion}"));

        if (!is_null($st) && !empty($st)) {
            foreach ($st as $value) {
                $array_status['prospeccion'] = $value['prospeccion'];
                $array_status['primera_visita'] = $value['primera_visita'];
                $array_status['seguimiento'] = $value['prospeccion'];
            }
        } else {
            return 'NA';
        }
    }

    /**
     * Function gets the last Paso 10 + 1
     * @param type $id_informacion
     * return $string with last register
     */
    public function getPasoDiez($id_informacion) {
        $paso = GestionPasoOnce::model()->find(array("condition" => "id_informacion = {$id_informacion}", "limit" => "1", 'order' => "id DESC"));
        if (!is_null($paso) && !empty($paso)) {
            if ($paso->tipo == 1) {
                return 'SI';
            } else {
                return 'NO';
            }
        } else {
            return 'NO';
        }
    }

    public function getListaTD($id_informacion) {
        $tdsi = 0;
        $tdno = 0;
        $datatd = '';
        $modelos = GestionVehiculo::model()->findAll(array('condition' => "id_informacion = {$id_informacion}", 'order' => 'id desc'));
        foreach ($modelos as $m) {
            $tds = GestionTestDrive::model()->findAll(array('condition' => "id_vehiculo = {$m['id']}", 'order' => 'test_drive desc'));
            $datatd .= $this->getModel($m['modelo']) . ' - ';
            foreach ($tds as $t) {
                if ($t['test_drive'] == 1) {
                    //echo 'enter td1';
                    $tdsi++;
                    if ($tdsi == 1) {
                        $datatd .= '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                    }
                }
                if ($t['test_drive'] == 0) {
                    $tdno++;
                    if ($tdsi == 0) {
                        $datatd .= '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
                    }
                }
            }
            $tdsi = 0;
            $tdno = 0;
            $datatd .= '<br />';
        }

        return $datatd;
    }

    public function getListaTDExcel($id_informacion) {
        $tdsi = 0;
        $tdno = 0;
        $datatd = '';
        $modelos = GestionVehiculo::model()->findAll(array('condition' => "id_informacion = {$id_informacion}", 'order' => 'id desc'));
        foreach ($modelos as $m) {
            $tds = GestionTestDrive::model()->findAll(array('condition' => "id_vehiculo = {$m['id']}", 'order' => 'test_drive desc'));
            //$datatd .= $this->getModel($m['modelo']) . ' - ';
            foreach ($tds as $t) {
                if ($t['test_drive'] == 1) {
                    //echo 'enter td1';
                    $tdsi++;
                    if ($tdsi == 1) {
                        $datatd .= 'Si ';
                    }
                }
                if ($t['test_drive'] == 0) {
                    $tdno++;
                    if ($tdsi == 0) {
                        $datatd .= 'No ';
                    }
                }
            }
            $tdsi = 0;
            $tdno = 0;
        }

        return $datatd;
    }

    /**
     * Construye select con el nombre del modelo y version de los vehiculos del cliente
     * @param int $id_informacion int id del cliente registrado
     * @return string Datos con el select construido con modelos y versiones
     */
    public function getModelosVehiculos($id_informacion) {
        $data = '';
        $vehiculos = GestionVehiculo::model()->findAll(array('condition' => "id_informacion = {$id_informacion}"));
        foreach ($vehiculos as $vc) {
            $data .= '<option value="' . $vc['id'] . '">' . $this->getModel($vc['modelo']) . '-' . $this->getVersion($vc['version']) . '</option>';
        }

        return $data;
    }

    /**
     * Retorna el nombre de Jefe de Agencia segun la id del asesor de ventas
     * @param int $id_responsable
     * @return string data con el select con nombres y apellidos del Jefe de Sucursal
     */
    public function getJefeAgencia($id_responsable) {
        $data = '';
        $dealer_id = $this->getConcesionarioDealerId($id_responsable);
        $cre = new CDbCriteria();
        $cre->condition = " cargo_id = 70 AND dealers_id = {$dealer_id} ";
        $cre->order = " nombres ASC";
        $asesores = Usuarios::model()->findAll($cre);
        $data = '';
        foreach ($asesores as $value) {
            $data .= '<option value="' . $value['id'] . '">' . $value['nombres'] . ' ' . $value['apellido'] . '</option>';
        }
        return $data;
    }

    public function getFuenteContacto($id_informacion) {
        $ft = GestionDiaria::model()->find(array("condition" => "id_informacion = {$id_informacion}"));
        return $ft->fuente_contacto;
    }
    
    public function getFuenteContactoHistorial($id_informacion) {
        $ft = GestionDiaria::model()->find(array("condition" => "id_informacion = {$id_informacion}"));
        return $ft->fuente_contacto_historial;
    }
    
    

    /**
     * Funcion que retorna las provincias a las que pertenece un grupo de concesionarios
     * @param int $grupo_id
     * @return array de ids de provincias
     */
    public function getProvinciasGrupo($grupo_id) {
        $array_dealers = array();

        $dealers = GrConcesionarios::model()->findAll(array("condition" => "id_grupo = {$grupo_id}"));
        $counter = 0;
        foreach ($dealers as $value) {
            //echo 'asdasd'.$value['concesionario_id'];
            $array_dealers[$counter] = $value['provincia'];
            $counter++;
        }
        return $array_dealers;
    }

    public function getFechaGestionDiaria($id) {
        //die($id);
        $fgd = GestionDiaria::model()->find(array('condition' => "id = {$id}"));
        return $fgd->fecha;
    }

    public function getModeloVehiculoByGdId($id) {
        $fgd = GestionDiaria::model()->find(array('condition' => "id = {$id}"));
        $id_informacion = $fgd->id_informacion;
        $vec = GestionVehiculo::model()->find(array('condition' => "id_informacion = {$id_informacion}"));
        if (!is_null($vec) && !empty($vec)) {
            $id_modelo = $vec->modelo;
            $modelo = Modelos::model()->find(array('condition' => "id_modelos = {$id_modelo}"));
            if (!is_null($modelo) && !empty($modelo)) {
                return $modelo->nombre_modelo;
            } else {
                return "No Modelo";
            }
        } else {
            return 'Modelo no Disponible';
        }
    }

    public function getCita($id_informacion, $cargo_id, $cargo_adicional, $fuente) {
//        $ga = GestionAgendamiento::model()->find(array(
//            'condition' => "id_informacion = {$id_informacion}",
//            'limit' => 1,
//            'order' => 'id DESC'        
//            ));
        $ga = GestionCita::model()->find(array('condition' => "id_informacion = {$id_informacion} AND `order` = 1"));    
        if (count($ga) > 0 && ($cargo_id == 86 || $cargo_adicional == 86) && $fuente === 'web' ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    /**
     * Funcion que retorna las fechas actuales en el reporte de trafico
     * @param type $mes_actual
     * @param type $dia_inicial Dia incial de inicio
     * @param type $dia_actual Dia final 
     * @return array $fechas fechas a ser mostradas
     */
    public function getNumeroMeses($mes_actual, $dia_inicial, $dia_actual) {
        //echo 'dia_actual: ('.$dia_inicial.'-'.$dia_actual.')';
        $fechas = array();
        switch ($mes_actual) {
            case 1:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 2:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 3:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 4:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 5:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'may-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 6:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'may-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jun-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 7:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'may-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jun-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jul-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 8:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'may-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jun-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jul-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'ago-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 9:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'may-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jun-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jul-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'ago-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'sep-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 10:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'may-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jun-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jul-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'ago-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'sep-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'oct-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 11:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'may-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jun-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jul-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'ago-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'sep-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'oct-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'nov-('.$dia_inicial.'-'.$dia_actual.')';
                break;
            case 12:
                $fechas[] = 'ene-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'feb-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'mar-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'abr-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'may-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jun-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'jul-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'ago-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'sep-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'oct-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'nov-('.$dia_inicial.'-'.$dia_actual.')';
                $fechas[] = 'dic-('.$dia_inicial.'-'.$dia_actual.')';
                break;

            default:
                break;
        }
        return $fechas;
    }
    
    /**
     * Funcion que retorna las fechas actuales en el reporte de trafico
     * @param type $mes_actual
     * @param type $dia_actual
     * @return array $fechas fechas a ser mostradas
     */
    public function getNumeroMesesDate($mes_actual, $dia_actual) {
        $fechas = array();
        switch ($mes_actual) {
            case 1:
                $fechas[] = '01';
                break;
            case 2:
                $fechas[] = '01';
                $fechas[] = '02';
                break;
            case 3:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                break;
            case 4:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                break;
            case 5:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                $fechas[] = '05';
                break;
            case 6:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                $fechas[] = '05';
                $fechas[] = '06';
                break;
            case 7:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                $fechas[] = '05';
                $fechas[] = '06';
                $fechas[] = '07';
                break;
            case 8:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                $fechas[] = '05';
                $fechas[] = '06';
                $fechas[] = '07';
                $fechas[] = '08';
                break;
            case 9:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                $fechas[] = '05';
                $fechas[] = '06';
                $fechas[] = '07';
                $fechas[] = '08';
                $fechas[] = '09';
                break;
            case 10:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                $fechas[] = '05';
                $fechas[] = '06';
                $fechas[] = '07';
                $fechas[] = '08';
                $fechas[] = '09';
                $fechas[] = '10';
                break;
            case 11:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                $fechas[] = '05';
                $fechas[] = '06';
                $fechas[] = '07';
                $fechas[] = '08';
                $fechas[] = '09';
                $fechas[] = '10';
                $fechas[] = '11';
                break;
            case 12:
                $fechas[] = '01';
                $fechas[] = '02';
                $fechas[] = '03';
                $fechas[] = '04';
                $fechas[] = '05';
                $fechas[] = '06';
                $fechas[] = '07';
                $fechas[] = '08';
                $fechas[] = '09';
                $fechas[] = '10';
                $fechas[] = '11';
                $fechas[] = '12';
                break;

            default:
                break;
        }
        return $fechas;
    }
    
    public function getModelosTrafico($categoria) {
        if($categoria == 5){
            $res = GestionModelos::model()->findAll(array('condition' => "status = 'ACTIVO' AND categoria IN (1,2,3,4)", 'order' => "nombre_modelo ASC", 'order' => "orden"));
        }else{
            $res = GestionModelos::model()->findAll(array('condition' => "status = 'ACTIVO' AND categoria = {$categoria}", 'order' => "nombre_modelo ASC", 'order' => "orden"));
        }
        return $res;
    }
    
    public function getModelosTraficoVersion($categoria) {
        //die('categoria: '.$categoria);
        if($categoria == 5){
            $res = GestionModelos::model()->findAll(array('condition' => "status = 'ACTIVO' AND categoria IN (1,2,3,4)", 'order' => "nombre_modelo ASC", 'order' => "orden"));
        }else{
            $res = GestionModelos::model()->findAll(array('condition' => "status = 'ACTIVO' AND categoria = {$categoria}", 'order' => "nombre_modelo ASC", 'order' => "orden"));
        }
        //$res = Versiones::model()->findAll(array("condition" => "status = 1 AND categoria = 1", 'group' => "id_modelos", 'order' => "orden"));
        //echo '<pre>';
        //print_r($res);
        //echo '</pre>';
        //die();
        $versiones = array();
        
        $ct = 0;
        
        foreach ($res as $value) {
            //echo 'id modelo: '.$value['id_modelo'].', tipo: '.$value['tipo'].'<br />';
            $str_versiones = '';
            if($value['id_modelo'] == 85 && $value['tipo'] == 0){ // rio r sedan
                $vrh = Versiones::model()->findAll(array("condition" => "id_modelos = {$value['id_modelo']} AND `status` IN(1,3) AND tipo = 0 AND orden = 2"));
                foreach ($vrh as $valh) {
                    $str_versiones .= $valh['id_versiones'].',';
                }
            }
            elseif($value['id_modelo'] == 85 && $value['tipo'] == 1){ // rio r hb
                $vrh = Versiones::model()->findAll(array("condition" => "id_modelos = {$value['id_modelo']} AND `status`  IN(1,3) AND tipo = 1 AND orden = 3"));
                foreach ($vrh as $valh) {
                    $str_versiones .= $valh['id_versiones'].',';
                }
            }else{ // otros modelos
                $vrh = Versiones::model()->findAll(array("condition" => "id_modelos = {$value['id_modelo']} AND `status`  IN(1,3)"));
                foreach ($vrh as $valh) {
                    $str_versiones .= $valh['id_versiones'].',';
                }
            }
            $str_versiones = substr($str_versiones, 0, -1);
            $versiones[$ct] = $str_versiones;
            $ct++;
        }
        
        /*echo '<pre>';
        print_r($versiones);
        echo '</pre>';
        die();*/
        return $versiones;
    }
    /**
     * Devuelve listado de provincias para Jefe de Concesion
     * @param array $vartrf
     * @return string $data options dropdown
     */
    public function getProvincias($vartrf) {
        $data = '<option value="">--Seleccione provincia--</option><option value="1000">Todos</option>';
        // AEKIA - carga todas las provincias
        if($vartrf['area_id'] == 4 || $vartrf['area_id'] == 12 || $vartrf['area_id'] == 13 || $vartrf['area_id'] == 14 ){
            $data .= '<option value="1">Azuay</option>
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
                    <option value="23">Tungurahua</option>';
        }
        // GERENTE COMERCIAL DE GRUPO - carga provincias de concesion
        if($vartrf['cargo_id'] == 69){
            $sql = "SELECT tp.* FROM tbl_provincias tp 
                    INNER JOIN gr_concesionarios gr ON gr.provincia = tp.id_provincia
                    WHERE gr.id_grupo = {$vartrf['grupo_id']} GROUP BY tp.id_provincia ORDER BY tp.nombre";
            $prov = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($prov as $value) {
                $data .= '<option value="'.$value['id_provincia'].'">'.$value['nombre'].'</option>';
            }
        }
        return $data;
    }

    public function getAllProvincias() {
        $data = '<option value="">--Seleccione provincia--</option><option value="1000">Todos</option>';
    
            $data .= '<option value="1">Azuay</option>
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
                    <option value="23">Tungurahua</option>';
        
     
        return $data;
    }
    
    public function getConcesionarios($vartrf) {
        $data = '<option value="">--Seleccione concesionario--</option><option value="1000">Todos</option>';
        if($vartrf['cargo_id'] == 69){
            $sql = "SELECT gr.* FROM gr_concesionarios gr
                WHERE gr.id_grupo = {$vartrf['grupo_id']} AND dealer_id <> 0 ORDER BY gr.nombre 
                ";
            $conc = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($conc as $value) {
                $data .= '<option value="'.$value['dealer_id'].'">'.$value['nombre'].'</option>';
            }
        }
        return $data;
    }
    
    public function getConcesionariosSelected($vartrf) {
        
        if($vartrf['cargo_id'] == 70){
            $sql = "SELECT gr.* FROM gr_concesionarios gr
                WHERE gr.dealer_id = {$vartrf['dealer_id']}";
            $conc = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($conc as $value) {
                $data .= '<option value="'.$value['dealer_id'].'">'.$value['nombre'].'</option>';
            }
        }
        return $data;
    }
    
    public function getResponsables($vartrf) {
        $sql = "SELECT * FROM usuarios WHERE dealers_id = {$vartrf['dealer_id']} AND cargo_id IN (71,70) ORDER BY nombres ASC";
        $conc = Yii::app()->db->createCommand($sql)->queryAll();
        $data = '<option value="">--Seleccione Asesor--</option><option value="10000">Todos</option>';
        //$data .= '<option value="all">Todos</option>';
        foreach ($conc as $value) {
            $data .= '<option value="' . $value['id'] . '">';
            $data .= $this->getResponsableNombres($value['id']);
            $data .= '</option>';
        }
        return $data;
    }
    
    /**
     * Returns count of vehicle's version in date range
     * @param int $mes Mes actual
     * @param string $versiones Lista de versiones del modelo de auto
     * @param int $year Year en curso
     * @param string $dia Dia actual
     * @param boolean $flag Busqueda entre fechas 1 o individual 0
     * @param array $search Array con parametros de busqueda
     * @param int $cargo_id cargo de usuario
     * @param int $dealer_id id de concesionario de usuario
     * @param int $id_responsable id del usuario responsable
     * @return int $count Numero de coincidencias
     */
    public function getTraficoVersion($mes, $versiones, $year, $dia, $flag, $search, $cargo_id, $dealer_id, $id_responsable) {
        //echo "<pre>";
        //print_r($versiones);
        //echo '</pre>';
        //echo 'year:'.$year.'<br />';
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        //echo 'srf: '.$srf.'<br />';
        
        $criteria = new CDbCriteria;
        $criteria->select = "DISTINCT gi.id, gv.version";
        $criteria->alias = 'gi';
        $criteria->join = "INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ";
        $criteria->join .= "LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        if($flag){
            $criteria->addCondition("DATE(gi.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gi.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        switch ($cargo_id) {
            case 71: // JEFE DE ALMACEN
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                break;
            case 70: // JEFE DE ALMACEN
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                break;
            case 69: // JEFE CONCESION O GERENTE COMERCIAL
                $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                break;
            default:
                break;
        }
        //$criteria->addCondition("DATE(gi.fecha) BETWEEN '2016-05-01' AND '2016-05-15' ");
        # SI NO ES VERSION PARA SUMATORIA TOTAL DETALLE
        if($versiones != 'all')
            $criteria->addCondition("gv.version IN (".$versiones.")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        $criteria->addCondition("gv.orden = 1");
    //    echo '<pre>';
    //    print_r($criteria);
    //    echo '</pre>';
        $count = GestionInformacion::model()->findAll($criteria);
        
        //return $cogit stautunt.', versiones: '.$versiones;
        return $count;
    }
    
    /**
     * Returns count of vehicle's version in date range
     * @param int $mes Mes actual
     * @param int $year Year en curso
     * @param string $dia Dia actual
     * @param boolean $flag Busqueda entre fechas 1 o individual 0
     * @param array $search Array con parametros de busqueda
     * @param int $cargo_id cargo de usuario
     * @param int $dealer_id id de concesionario de usuario
     * @param int $id_responsable id del usuario responsable
     * @return int $count Numero de coincidencias
     */
    public function getTraficoVersionTotal($mes, $year, $dia, $flag, $search, $cargo_id, $dealer_id, $id_responsable, $ckd, $categoria) {
        //echo 'year:'.$year.'<br />';
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        //echo 'srf: '.$srf.'<br />';
        
        $criteria = new CDbCriteria;
        $criteria->select = "DISTINCT gi.id, gv.version";
        $criteria->alias = 'gi';
        $criteria->join = "INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ";
        $criteria->join .= "LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        if($flag){
            $criteria->addCondition("DATE(gv.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gv.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        switch ($cargo_id) {
            case 71: // JEFE DE ALMACEN
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                break;
            case 70: // JEFE DE ALMACEN
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                break;
            case 69: // JEFE CONCESION O GERENTE COMERCIAL
                $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                break;
            default:
                break;
        }
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        
        if($ckd == 1){
            if($categoria == 5)
                $categoria = '1,2,3,4';
            $CKDs = Yii::app()->db->createCommand()->select('id_modelos')->from('modelos')->where("ensamblaje = 'CKD' AND active = 1 AND id_categoria IN ({$categoria})")->queryAll();
            $CKDsRender = '';
            foreach ($CKDs as $key => $value) {
                $CKDsRender .= $value['id_modelos'] . ', ';
            }
            $CKDsRender = rtrim($CKDsRender, ", ");
            $criteria->addCondition("gv.orden = 1");
            if(count($CKDs) > 0)
                $criteria->addCondition("(gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")");
            else
                return 0;
        }
        
    //    echo '<pre>';
    //    print_r($criteria);
    //    echo '</pre>';
    //    die();
        $count = GestionInformacion::model()->findAll($criteria);
        
        //return $count.', versiones: '.$versiones;
        return $count;
    }
    /**
     * Devuelve el numero de proformas en un rango de fechas o fecha individual
     * @param int $mes Mes actual
     * @param string $versiones Lista de versiones del modelo de auto
     * @param int $year Year en curso
     * @param string $dia Dia actual
     * @param boolean $flag Busqueda entre fechas 1 o individual 0
     * @param array $search Array con parametros de busqueda
     * @return int $count Numero de proformas generadas
     */
    public function getProformaVersion($mes, $versiones, $year, $dia, $flag, $search, $cargo_id, $dealer_id, $id_responsable) {
        if($search['fecha'])    
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        //echo 'srf: '.$srf;
        // SELECT COUNT(*)  from gestion_financiamiento gf INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
        //WHERE gi.responsable = 406 AND (gi.bdc = 1 OR gi.bdc = 0)  AND (DATE(gf.fecha) BETWEEN '2016-11-01' AND '2016-11-15') AND ((gv.modelo IN (21, 24, 95)) OR gi.modelo IN (21, 24, 95)) AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico')
        $criteria = new CDbCriteria;
        $criteria->select = "COUNT(DISTINCT gf.id)";
        $criteria->alias = 'gf';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id   ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo";
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        if($flag){
            $criteria->addCondition("DATE(gf.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gf.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        switch ($cargo_id) {
            case 71: // JEFE DE ALMACEN
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                break;
            case 70: // JEFE DE ALMACEN
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                break;
            case 69: // JEFE CONCESION O GERENTE COMERCIAL
                $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                break;
            default:
                break;
        }
        if($versiones != 'all')
            $criteria->addCondition("gv.version IN (".$versiones.")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
    //    echo '<pre>';
    //    print_r($criteria);
    //    echo '</pre>';        
        $count = GestionFinanciamiento::model()->count($criteria);
        return $count;
    }
    
    /**
     * Devuelve el numero de proformas en un rango de fechas o fecha individual
     * @param int $mes Mes actual
     * @param int $year Year en curso
     * @param string $dia Dia actual
     * @param boolean $flag Busqueda entre fechas 1 o individual 0
     * @param array $search Array con parametros de busqueda
     * @return int $count Numero de proformas generadas
     */
    public function getProformaVersionTotal($mes,$year, $dia, $flag, $search, $cargo_id, $dealer_id, $id_responsable, $ckd, $categoria) {
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        //echo 'srf: '.$srf;
        // SELECT COUNT(*)  from gestion_financiamiento gf INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
        //WHERE gi.responsable = 406 AND (gi.bdc = 1 OR gi.bdc = 0)  AND (DATE(gf.fecha) BETWEEN '2016-11-01' AND '2016-11-15') AND ((gv.modelo IN (21, 24, 95)) OR gi.modelo IN (21, 24, 95)) AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico')
        $criteria = new CDbCriteria;
        $criteria->select = "COUNT(DISTINCT gf.id)";
        $criteria->alias = 'gf';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id   ";
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        if($flag){
            $criteria->addCondition("DATE(gv.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gv.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        switch ($cargo_id) {
            case 71: // JEFE DE ALMACEN
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                break;
            case 70: // JEFE DE ALMACEN
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                break;
            case 69: // JEFE CONCESION O GERENTE COMERCIAL
                $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                break;
            default:
                break;
        }
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");

        if($ckd == 1){
            if($categoria == 5)
                $categoria = '1,2,3,4';
            $CKDs = Yii::app()->db->createCommand()->select('id_modelos')->from('modelos')->where("ensamblaje = 'CKD' AND active = 1 AND id_categoria IN ({$categoria})")->queryAll();
            $CKDsRender = '';
            foreach ($CKDs as $key => $value) {
                $CKDsRender .= $value['id_modelos'] . ', ';
            }
            $CKDsRender = rtrim($CKDsRender, ", ");
            if(count($CKDs) > 0)
                $criteria->addCondition("(gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")");
            else
                return 0;
        } 
//        echo '<pre>------------getProformaVersionTotal----------------------------';
//        print_r($criteria);
//        echo '</pre>';       
        $count = GestionFinanciamiento::model()->count($criteria);
        return $count;
    }
    
    public function getTestDriveVersion($mes, $versiones, $year, $dia, $flag, $search, $cargo_id, $dealer_id, $id_responsable) {
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        $criteria = new CDbCriteria;
        //$criteria->select = "*";
        $criteria->alias = 'gt';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $criteria->join .= "LEFT JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ";
        
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        $criteria->addCondition("gt.test_drive = 1 AND gt.order = 1");
        if($flag){
            $criteria->addCondition("DATE(gt.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gt.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        switch ($cargo_id) {
            case 71: // JEFE DE ALMACEN
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                break;
            case 70: // JEFE DE ALMACEN
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                break;
            case 69: // JEFE CONCESION O GERENTE COMERCIAL
                $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                break;
            default:
                break;
        }
        //$criteria->addCondition("DATE(gt.fecha) ".$srf);
        if($versiones != 'all')
            $criteria->addCondition("gv.version IN (".$versiones.")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
    //    echo '<pre>';
    //    print_r($criteria);
    //    echo '</pre>';
        $count = GestionTestDrive::model()->count($criteria);
        return $count;
    }
    
    public function getTestDriveVersionTotal($mes, $year, $dia, $flag, $search, $cargo_id, $dealer_id, $id_responsable, $ckd, $categoria) {
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        $criteria = new CDbCriteria;
        //$criteria->select = "*";
        $criteria->alias = 'gt';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $criteria->join .= "LEFT JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ";
        
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        $criteria->addCondition("gt.test_drive = 1 AND gt.order = 1");
        if($flag){
            $criteria->addCondition("DATE(gt.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gt.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        switch ($cargo_id) {
            case 71: // JEFE DE ALMACEN
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                break;
            case 70: // JEFE DE ALMACEN
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                break;
            case 69: // JEFE CONCESION O GERENTE COMERCIAL
                $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                break;
            default:
                break;
        }
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        if($ckd == 1){
            if($categoria == 5)
                $categoria = '1,2,3,4';
            $CKDs = Yii::app()->db->createCommand()->select('id_modelos')->from('modelos')->where("ensamblaje = 'CKD' AND active = 1 AND id_categoria IN ({$categoria})")->queryAll();
            $CKDsRender = '';
            foreach ($CKDs as $key => $value) {
                $CKDsRender .= $value['id_modelos'] . ', ';
            }
            $CKDsRender = rtrim($CKDsRender, ", ");
            if(count($CKDs) > 0)
                $criteria->addCondition("(gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")");
            else
                return 0;
        } 
//        echo '<pre>-------------------------TD';
//        print_r($criteria);
//        echo '</pre>';
        $count = GestionTestDrive::model()->count($criteria);
        return $count;
    }
    
    public function getTestDriveTotal($mes, $year, $dia, $flag, $search, $categoria) {
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        $criteria = new CDbCriteria;
        //$criteria->select = "*";
        $criteria->alias = 'gt';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ";
        
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        $criteria->addCondition("gt.test_drive = 1 AND gt.order = 1");
        if($flag){
            $criteria->addCondition("DATE(gt.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gt.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        //$criteria->addCondition("DATE(gt.fecha) ".$srf);
        $versiones = $this->getVersionesIds($categoria);
        $criteria->addCondition("gv.version IN (".$versiones.")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        
        $count = GestionTestDrive::model()->count($criteria);
        return $count;
    }
    /**
     * Returns count of sales vehicle's version in date range
     * @param int $mes Mes actual
     * @param string $versiones Lista de versiones del modelo de auto
     * @param int $year Year en curso
     * @param string $dia Dia actual
     * @param boolean $flag Busqueda entre fechas 1 o individual 0
     * @param array $search Array con parametros de busqueda
     * @return int $count Numero de coincidencias
     */
    public function getVentasVersion($mes, $versiones, $year, $dia, $flag, $search, $cargo_id, $dealer_id, $id_responsable) {
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        // SELECT COUNT(*)  from gestion_financiamiento gf INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
        //WHERE gi.responsable = 406 AND (gi.bdc = 1 OR gi.bdc = 0)  AND (DATE(gf.fecha) BETWEEN '2016-11-01' AND '2016-11-15') AND ((gv.modelo IN (21, 24, 95)) OR gi.modelo IN (21, 24, 95)) AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico')
        $criteria = new CDbCriteria;
        $criteria->select = "COUNT(DISTINCT gf.id_vehiculo)";
        $criteria->alias = 'gf';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        
        
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        $criteria->addCondition("gd.cierre = 1 AND gf.status = 'ACTIVO'");
        if($flag){
            $criteria->addCondition("DATE(gf.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gf.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        switch ($cargo_id) {
            case 71: // JEFE DE ALMACEN
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                break;
            case 70: // JEFE DE ALMACEN
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                break;
            case 69: // JEFE CONCESION O GERENTE COMERCIAL
                $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                break;
            default:
                break;
        }
        //$criteria->addCondition("DATE(gf.fecha) ".$srf);
        if($versiones != 'all')
            $criteria->addCondition("gv.version IN (".$versiones.")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
    //    echo '<pre>';
    //    print_r($criteria);
    //    echo '</pre>'; 
        $count = GestionFactura::model()->count($criteria);
        return $count;
    }
    
    /**
     * Returns count of sales vehicle's version in date range
     * @param int $mes Mes actual
     * @param int $year Year en curso
     * @param string $dia Dia actual
     * @param boolean $flag Busqueda entre fechas 1 o individual 0
     * @param array $search Array con parametros de busqueda
     * @return int $count Numero de coincidencias
     */
    public function getVentasVersionTotal($mes, $year, $dia, $flag, $search, $cargo_id, $dealer_id, $id_responsable, $ckd, $categoria) {
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        // SELECT COUNT(*)  from gestion_financiamiento gf INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
        //WHERE gi.responsable = 406 AND (gi.bdc = 1 OR gi.bdc = 0)  AND (DATE(gf.fecha) BETWEEN '2016-11-01' AND '2016-11-15') AND ((gv.modelo IN (21, 24, 95)) OR gi.modelo IN (21, 24, 95)) AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico')
        $criteria = new CDbCriteria;
        $criteria->select = "COUNT(DISTINCT gf.id_vehiculo)";
        $criteria->alias = 'gf';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        
        
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        $criteria->addCondition("gd.cierre = 1 AND gf.status = 'ACTIVO'");
        if($flag){
            $criteria->addCondition("DATE(gf.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gf.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        switch ($cargo_id) {
            case 71: // JEFE DE ALMACEN
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                break;
            case 70: // JEFE DE ALMACEN
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                break;
            case 69: // JEFE CONCESION O GERENTE COMERCIAL
                $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                break;
            default:
                break;
        }
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        if($ckd == 1){
            if($categoria == 5)
                $categoria = '1,2,3,4';
            $CKDs = Yii::app()->db->createCommand()->select('id_modelos')->from('modelos')->where("ensamblaje = 'CKD' AND active = 1 AND id_categoria IN ({$categoria})")->queryAll();
            $CKDsRender = '';
            foreach ($CKDs as $key => $value) {
                $CKDsRender .= $value['id_modelos'] . ', ';
            }
            $CKDsRender = rtrim($CKDsRender, ", ");
            if(count($CKDs) > 0)
                $criteria->addCondition("(gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")");
            else
                return 0;
        } 
//        echo '<pre>';
//        print_r($criteria);
//        echo '</pre>'; 
        $count = GestionFactura::model()->count($criteria);
        return $count;
    }
    
    /**
     * Returns count of sales vehicle's version in date range
     * @param int $mes Mes actual
     * @param int $year Year en curso
     * @param string $dia Dia actual
     * @param boolean $flag Busqueda entre fechas 1 o individual 0
     * @param array $search Array con parametros de busqueda
     * @param int $categoria Categoria de grupo de autos
     * @return int $count Numero de coincidencias
     */
    public function getVentasTotal($mes, $year, $dia, $flag, $search, $categoria) {
        
        if($search['fecha'])
           $srf = $this->getBetweenfecha($mes, $year, $search['dia_anterior'], $search['dia_actual']); 
        else
           $srf = $this->getBetweenfecha($mes, $year, '01',$dia);
        // SELECT COUNT(*)  from gestion_financiamiento gf INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
        //WHERE gi.responsable = 406 AND (gi.bdc = 1 OR gi.bdc = 0)  AND (DATE(gf.fecha) BETWEEN '2016-11-01' AND '2016-11-15') AND ((gv.modelo IN (21, 24, 95)) OR gi.modelo IN (21, 24, 95)) AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico')
        $criteria = new CDbCriteria;
        $criteria->select = "COUNT(DISTINCT gf.id_vehiculo)";
        $criteria->alias = 'gf';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        
        
        if($search['grupo']){
            
        }
        $criteria->condition = "gi.bdc = 0 ".$search['where'];
        $criteria->addCondition("gd.cierre = 1 AND gf.status = 'ACTIVO'");
        if($flag){
            $criteria->addCondition("DATE(gf.fecha) ".$srf);
        }else{
            $criteria->addCondition("DATE(gf.fecha) = '" . $year . "-" . $mes . "-" . $dia . "' ");
        }
        //$criteria->addCondition("DATE(gf.fecha) ".$srf);
        $versiones = $this->getVersionesIds($categoria);
        $criteria->addCondition("gv.version IN (".$versiones.")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
//        echo '<pre>';
//        print_r($criteria);
//        echo '</pre>'; 
        $count = GestionFactura::model()->count($criteria);
        return $count;
    }
    
    public function getNameCategoria($categoria) {
        switch ($categoria) {
            case 1:
                return 'Autos';
                break;
            case 2:
                return 'SUV';
                break;
            case 3:
                return 'MPV';
                break;
            case 4:
                return 'Comerciales';
                break;

            default:
                return 'Todos';
                break;
        }
        
    }
    
    public function getBetweenfecha($mes, $year, $dia_inicial, $dia_final) {
        //echo 'dia inicial: '.$dia_inicial.'<br />';
        $srf = '';
        switch ($mes) {
            case 0:
                $srf = "BETWEEN '".$year."-01-".$dia_inicial."' AND '".$year."-01-".$dia_final."'";
                break;
            case 1:
                $srf = "BETWEEN '".$year."-02-".$dia_inicial."' AND '".$year."-02-".$dia_final."'";
                break;
            case 2:
                $srf = "BETWEEN '".$year."-03-".$dia_inicial."' AND '".$year."-03-".$dia_final."'";
                break;
            case 3:
                $srf = "BETWEEN '".$year."-04-".$dia_inicial."' AND '".$year."-04-".$dia_final."'";
                break;
            case 4:
                $srf = "BETWEEN '".$year."-05-".$dia_inicial."' AND '".$year."-05-".$dia_final."'";
                break;
            case 5:
                $srf = "BETWEEN '".$year."-06-".$dia_inicial."' AND '".$year."-06-".$dia_final."'";
                break;
            case 6:
                $srf = "BETWEEN '".$year."-07-".$dia_inicial."' AND '".$year."-07-".$dia_final."'";
                break;
            case 7:
                $srf = "BETWEEN '".$year."-08-".$dia_inicial."' AND '".$year."-08-".$dia_final."'";
                break;
            case 8:
                $srf = "BETWEEN '".$year."-09-".$dia_inicial."' AND '".$year."-09-".$dia_final."'";
                break;
            case 9:
                $srf = "BETWEEN '".$year."-10-".$dia_inicial."' AND '".$year."-10-".$dia_final."'";
                break;
            case 10:
                $srf = "BETWEEN '".$year."-11-".$dia_inicial."' AND '".$year."-11-".$dia_final."'";
                break;
            case 11:
                $srf = "BETWEEN '".$year."-12-".$dia_inicial."' AND '".$year."-12-".$dia_final."'";
                break;
            default:
                break;
        }
        return $srf;
    }
    
    public function getTasaTD($testdrive,$trafico) {
        //echo 'testdrive: '.$testdrive.', trafico: '.$trafico.' ';
        if($trafico != 0){
            $td = ($testdrive / $trafico) * 100;
            $td = round($td, 2);
            return $td . ' %';
        }else{
            return '0 %';
        }
    }
    
    public function getTasaCierre($ventas,$trafico) {
        //echo 'ventas: '.$ventas.', '.$trafico.'<br />';
        if($trafico != 0){
            $vt = ($ventas / $trafico) * 100;
            $vt = round($vt, 2);
            return $vt . ' %';
        }else{
            return '0 %';
        }
    }
    
    public function getTasaCierreNormal($ventas,$trafico) {
        //echo 'ventas: '.$ventas.', '.$trafico.'<br />';
        if($trafico != 0){
            $vt = ($ventas / $trafico) * 100;
            $vt = round($vt, 2);
            return $vt ;
        }else{
            return 0;
        }
    }
    
    public function getPorcentaje($trafico_modelo, $trafico_total) {
        //die('trafico modelo: '.$trafico_modelo.', trafico total: '.$trafico_total);
        if($trafico_total != 0){
            $vt = ($trafico_modelo / $trafico_total) * 100;
            return $vt ;
        }else{
            return 0;
        }
    }
    /**
     * 
     * @param int $cargo_id
     * @param type $grupo_id
     * @param type $id_responsable
     * @param type $fechaPk
     * @param type $get_array
     * @param type $tipo_search
     * @return \CPagination|array
     */
    public function searchSql($cargo_id, $grupo_id, $id_responsable, $fechaPk, $get_array,$tipo_search) {
//        echo '<pre>';
//        print_r($_GET);
//        echo '</pre>';
//        echo 'fechapk: '.$fechaPk;
//        die();
        //echo 'cargo id: '.$cargo_id;
        $cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $dt_hoy = date('Y-m-d'); // Fecha actual
        $dt_unasemana_antes = date('Y-m-d', strtotime('-1 week')); // Fecha resta 1 semanas
        $dt_unmes_antes = date('Y-m-d', strtotime('-4 week')); // Fecha resta 1 mes

        $title = '';
        $data = array();
        $area_id = (int) Yii::app()->user->getState('area_id');
        //die ('area id: '.$area_id);
        if ($cargo_id != 46)
            $dealer_id = $this->getDealerId($id_responsable);
        $criteria = new CDbCriteria;
        $criteria->select = "gi.id , gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable,gi.tipo_form_web,gi.fecha, gi.bdc, gi.dealer_id, gi.id_cotizacion,
            gi.reasignado,gi.responsable_cesado,gi.id_comentario";
        $criteria->alias = 'gi';
        $criteria->join = 'INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion';
        $criteria->join .= ' LEFT JOIN gestion_consulta gc ON gi.id = gc.id_informacion';
        $criteria->join .= ' INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
        $criteria->join .= ' INNER JOIN usuarios u ON u.id = gi.responsable';
        if($_GET['GestionDiaria']['status'] == 'exhibicion_automundo_uio'){
            $criteria->join .= ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
        }  
       



      
        
        switch ($cargo_id) {
            case 46: // SUPER ADMINISTRADOR

                $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                break;
            case 69: // GERENTE COMERCIAL
                if ($tipo_search == '') {
                    //$criteria->join .= " INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id";
                    $criteria->condition = "gr.id_grupo = {$grupo_id} AND u.cargo_id IN(70,71)";
                    $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
                }
                if ($tipo_search == 'web') {
                    //$criteria->join .= " INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id";
                    $criteria->condition = "gr.id_grupo = {$grupo_id} AND (u.cargo_id IN(85,86) OR u.cargo_adicional IN(85,86))";
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                }
                if ($tipo_search == 'exhibicion') {
                    //$criteria->join .= " INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id";
                    $criteria->condition = "gr.id_grupo = {$grupo_id} AND u.cargo_id IN(70,71)";
                    if($_GET['GestionDiaria']['status'] != 'exhibicion_automundo_uio'){
                        $criteria->addCondition("gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd' OR gd.fuente_contacto = 'exhibicion quierounkiatd'");
                }
                    
                }
                if ($tipo_search == 'exo') {
                    //$criteria->join .= " INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id";
                    $criteria->condition = "gr.id_grupo = {$grupo_id} AND u.cargo_id IN(75)";
                }
                if ($tipo_search == 'pro') {
                    //$criteria->join .= " INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id";
                    $criteria->condition = "gr.id_grupo = {$grupo_id} AND u.cargo_id IN(70,71)";
                }
                
                break;
            case 70: // JEFE DE SUCURSAL
                $array_dealers = $this->getResponsablesVariosConc(1);
                if(count($array_dealers) == 0){
                    $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                }
                $dealerList = implode(', ', $array_dealers);
                $criteria->condition = "gi.dealer_id IN ({$dealerList})";
                if($cargo_adicional == 85){
                    $criteria->addCondition("(u.cargo_id IN(71,70,85,86))");
                }else{
                    $criteria->addCondition("(u.cargo_id IN(71,70))");
                }
                break;
            case 71: // ASESOR DE VENTAS
                $criteria->condition = "gi.responsable = {$id_responsable}";
                break;
            case 85: // JEFE WEB - VENTAS EXTERNAS
                $array_dealers = $this->getResponsablesVariosConc(1);
                if(count($array_dealers) == 0){
                    $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                }
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                $criteria->addCondition("gi.bdc = 1");
                //$criteria->addCondition("gd.desiste = 0");
                $criteria->addCondition("u.cargo_id IN (85,86)");
                $criteria->addCondition("gd.fuente_contacto = 'web' OR gd.fuente_contacto = 'prospeccion'");
                break;
            case 86: // ASESOR WEB O VENTAS EXTERNAS
                $array_dealers = $this->getResponsablesVariosConc(1);
                if(count($array_dealers) == 0){
                    $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable,1);
                }
                $dealerList = implode(', ', $array_dealers);
                $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                $criteria->addCondition("gi.bdc = 1");
                //$criteria->addCondition("gd.desiste = 0");
                $criteria->addCondition("u.cargo_id IN (85,86)");
                $criteria->addCondition("gi.responsable = {$id_responsable}");
                $criteria->addCondition("gd.fuente_contacto = 'web' OR gd.fuente_contacto = 'prospeccion'");
                break;    
            default:
                break;
        }

        if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14 || $cargo_id == 69) { // AEKIA USERS
            //die('enter areas');
            if ($get_array == 'exo') {
                $criteria->join .= ' INNER JOIN gr_concesionarios grc ON grc.dealer_id = gi.dealer_id';
                $criteria->condition = "u.cargo_id IN (75)";
            }
            if ($tipo_search == 'web') {
                $criteria->join .= ' INNER JOIN gr_concesionarios grc ON grc.dealer_id = gi.dealer_id';
                $criteria->condition = "u.cargo_id IN(85,86,70,71) OR u.cargo_adicional IN(85,86)";
            }
            if ($tipo_search == '') {
                $criteria->join .= ' INNER JOIN gr_concesionarios grc ON grc.dealer_id = gi.dealer_id';
                $criteria->condition = "u.cargo_id IN (70,71)";
            }
        }


       /*  if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
             $criteria->join .= ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
           // echo $_GET['GestionDiaria']['modelo'];
            //die();
             $modelo=$_GET['GestionDiaria']['modelo'];            
             $criteria->addCondition("gv.modelo = {$modelo} ");
        }
        else if($_GET['GestionDiaria']['modelo']==999){
            $criteria->join .= ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
             //echo 'toods';
            //die();
        }
        if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999) {
          //  echo $_GET['GestionDiaria']['version'];
            //die();
              $version=$_GET['GestionDiaria']['version'];   
            $criteria->addCondition("gv.version = {$version} ");
        }*/



        $search_type = 0;   
    //    echo '<pre>';
    //    print_r($criteria);
    //    echo '</pre>';
    //    die();

        //die('search type combined: '.$search_type);
        // BUSQUEDA GENERAL CEDULA, NOMBRES, ID
        if (!empty($_GET['GestionDiaria']['general']) && empty($_GET['GestionDiaria']['categorizacion']) &&
                $fechaPk == 1 && empty($_GET['GestionDiaria']['status']) && empty($_GET['GestionDiaria']['fuente'])) {
            $search_type = 1;
        }
        // BUSQUEDA POR CATEGORIZACION
        if (!empty($_GET['GestionDiaria']['categorizacion']) && $fechaPk == 1 && empty($_GET['GestionDiaria']['general'])) {
            $search_type = 2;
        }
        // BUSQUEDA POR STATUS
        if (!empty($_GET['GestionDiaria']['status']) && $fechaPk == 1 && empty($_GET['GestionDiaria']['responsable']) &&
                empty($_GET['GestionDiaria']['tipo_fecha'])) {
            $search_type = 3;
        }
        // BUSQUEDA POR FECHA
        if (empty($_GET['GestionDiaria']['status']) && $fechaPk == 0 &&
                empty($_GET['GestionDiaria']['responsable']) &&
                !empty($_GET['GestionDiaria']['fecha'])) {
            $search_type = 4;
        }
        // BUSQUEDA POR FUENTE
        if (!empty($_GET['GestionDiaria']['fuente']) && empty($_GET['GestionDiaria']['general']) && empty($_GET['GestionDiaria']['categorizacion']) &&
                empty($_GET['GestionDiaria']['status']) && $fechaPk == 1 && empty($_GET['GestionDiaria']['tipo_fecha'])) {
            $search_type = 5;
        }
        // BUSQUEDA POR CONCESIONARIO
        if ($fechaPk == 1 && empty($_GET['GestionDiaria']['general']) && empty($_GET['GestionDiaria']['status']) &&
                !empty($_GET['GestionDiaria']['grupo']) &&
                !empty($_GET['GestionDiaria']['concesionario']) && empty($_GET['GestionDiaria']['responsable'])) {
            $search_type = 12;
        }
        
        // BUSQUEDA POR CONCESIONARIO GERENTE COMERCIAL
        if ($fechaPk == 1 && empty($_GET['GestionDiaria']['general']) && empty($_GET['GestionDiaria']['status']) &&
                empty($_GET['GestionDiaria']['categorizacion']) && empty($_GET['GestionDiaria']['seguimiento']) && 
                !empty($_GET['GestionDiaria']['concesionario']) && empty($_GET['GestionDiaria']['responsable'])) {
            $search_type = 12;
        }
        // BUSQUEDA POR RESPONSABLE
        if (!empty($_GET['GestionDiaria']['responsable']) && $fechaPk == 1 && empty($_GET['GestionDiaria']['general']) &&
                empty($_GET['GestionDiaria']['categorizacion']) && empty($_GET['GestionDiaria']['fuente']) && empty($_GET['GestionDiaria']['grupo']) &&
                !empty($_GET['GestionDiaria']['concesionario'])) {
            $search_type = 13;
        }
        // BUSQUEDA POR RESPONSABLE JEFE SUCURSAL
        if (!empty($_GET['GestionDiaria']['responsable']) && $fechaPk == 1 && empty($_GET['GestionDiaria']['general']) &&
                empty($_GET['GestionDiaria']['categorizacion']) && empty($_GET['GestionDiaria']['fuente']) && empty($_GET['GestionDiaria']['grupo'])) {
            $search_type = 6;
        }
        // BUSQUEDA POR GRUPO
        if ($fechaPk == 1 && empty($_GET['GestionDiaria']['general']) &&
                //empty($_GET['GestionDiaria']['categorizacion']) &&
                empty($_GET['GestionDiaria']['status']) &&
                //empty($_GET['GestionDiaria']['tipo_fecha']) &&
                empty($_GET['GestionDiaria']['fuente']) &&
                !empty($_GET['GestionDiaria']['grupo']) &&
                empty($_GET['GestionDiaria']['concesionario']) &&
                empty($_GET['GestionDiaria']['responsable'])) {
            $search_type = 14;
        }

        // BUSQUEDA CAMPOS VACIOS ASESOR VENTAS
        if (empty($_GET['GestionDiaria']['general']) && empty($_GET['GestionDiaria']['categorizacion']) &&
                $fechaPk == 1 && empty($_GET['GestionDiaria']['status']) && empty($_GET['GestionDiaria']['fuente']) &&
                empty($_GET['GestionDiaria']['grupo']) && empty($_GET['GestionDiaria']['concesionario']) &&
                empty($_GET['GestionDiaria']['responsable'])) {
            $search_type = 16;
        }
        if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) { // AEKIA USERS
            // POR RESPONSABLE
            if (empty($_GET['GestionDiaria']['general']) && $fechaPk == 1 && empty($_GET['GestionDiaria']['status']) &&
                    !empty($_GET['GestionDiaria']['grupo']) && !empty($_GET['GestionDiaria']['concesionario']) && !empty($_GET['GestionDiaria']['responsable'])) {
                $search_type = 17;
            }
            if (empty($_GET['GestionDiaria']['general']) && $fechaPk == 0 && empty($_GET['GestionDiaria']['status']) && !empty($_GET['GestionDiaria']['fecha']) &&
                    empty($_GET['GestionDiaria']['grupo']) && empty($_GET['GestionDiaria']['concesionario']) && empty($_GET['GestionDiaria']['responsable'])) {
                $search_type = 18;
            }
        }
        // BUSQUEDA POR SEGUIMIENTO
        if (empty($_GET['GestionDiaria']['general']) && empty($_GET['GestionDiaria']['categorizacion']) &&
                empty($_GET['GestionDiaria']['status']) && $fechaPk = 1 &&
                !empty($_GET['GestionDiaria']['seguimiento'])) {
            $search_type = 19;
        }
        // END OF SEARCH ONLY
        // START BUSQUEDAS COMBINADAS-----------------------------------------------------------------
        $params = explode('-', $_GET['GestionDiaria']['rango_fecha']);
        $fechaActual = date("Y/m/d");
        $fechaPk2 = 0;
        if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
            $fechaPk2 = 1;
        }
        //echo '<br />fechaPk: '.$fechaPk.', fechaPk2: '.$fechaPk2;
        // STATUS - CATEGORIZACION
        if ($_GET['busqueda_general'] == 0 && $_GET['categorizacion'] == 1 && $_GET['status'] == 1 && $_GET['responsable'] == 0 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 20;
        }
        // STATUS - CATEGORIZACION - RESPONSABLE
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 1 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 21;
        }
        
        // STATUS - CATEGORIZACION - RESPONSABLE
        if ($_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 45;
        }
        // CATEGORIZACION - RESPONSABLE
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 22;
        }
        // CATEGORIZACION - RESPONSABLE - SEGUIMIENTO
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 1 && $fechaPk2 == 1) {
            $search_type = 23;
        }
        // CATEGORIZACION - RESPONSABLE - SEGUIMIENTO - SEGUIMIENTO FECHA
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 1 && $fechaPk2 == 0) {
            $search_type = 24;
        }
        // CATEGORIZACION - FECHA DE REGISTRO
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 0 && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 25;
        }
        // STATUS - FECHA DE REGISTRO
        if ($_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 0 && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 26;
        }
        // CATEGORIZACION - RESPONSABLE - FECHA DE REGISTRO
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 27;
        }
        // RESPONSABLE - SEGUIMIENTO
        if ($_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 1 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 28;
        }
        // RESPONSABLE - SEGUIMIENTO - SEGUIMIENTO FECHA
        if ($_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 1 && $_GET['fecha_segumiento'] == 1) {
            $search_type = 29;
        }
        // BUSQUEDAS PARA GERENTE GENERAL O USUARIO AEKIA=========================================================================================================
        // GENERAL - GRUPO - CONCESIONARIO - RESPONSABLE
        if ($_GET['busqueda_general'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 33;
        }
        
        // GENERAL - GRUPO - CONCESIONARIO
        if ($_GET['busqueda_general'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 0 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 40;
        }
        
        // GRUPO - RESPONSABLE
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 33;
        }
        
        // GENERAL - GRUPO - CONCESIONARIO - RESPONSABLE - STATUS
        if ($_GET['busqueda_general'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 1 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 34;
        }
        
        // GENERAL - GRUPO - CONCESIONARIO - RESPONSABLE - STATUS -FECHA DE REGISTRO
        if ($_GET['busqueda_general'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 1 
                && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 35;
        }
        
        // GRUPO - CONCESIONARIO - STATUS
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 0 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 36;
        }
        
        // GRUPO - CONCESIONARIO - STATUS - RESPONSABLE
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 1 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 42;
        }
        
        //  FECHA REGISTRO - GRUPO - CONCESIONARIO
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 0 
                && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 38;
        }
        
        //  FECHA REGISTRO - GRUPO - CONCESIONARIO AND STATUS
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 0 
                && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 43;
        }
        
        //  FECHA REGISTRO - GRUPO - CONCESIONARIO - RESPONSABLE
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 
                && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 39;
        }
        
        // RESPONSABLE - FECHA DE REGISTRO
        if ($_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 44;
        }
        
        //  FECHA REGISTRO - CONCESIONARIO AND STATUS
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 0 
                && $_GET['fecha'] == 1 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 46;
        }
        // CONCESIONARIO - STATUS
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 0 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 47;
        }
        // STATUS - CONCESIONARIO - RESPONSABLE
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 48;
        }
        // SEGUIMIENTO - CONCESIONARIO
        if ($_GET['busqueda_general'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 0 && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 1 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 49;
        }
        
        // GENERAL - CONCESIONARIO - GERENTE COMERCIAL
        if ($_GET['busqueda_general'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 0 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 50;
        }
        
        // GENERAL - GRUPO - CONCESIONARIO - RESPONSABLE - GERENTE COMERCIAL
        if ($_GET['busqueda_general'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 
                && $_GET['fecha'] == 0 && $_GET['seguimiento_rgd'] == 0 && $_GET['fecha_segumiento'] == 0) {
            $search_type = 51;
        }
        
        // REVISAR VARIABLE $tipo_seg PARA SUMAR UNA CONDICION AL CRITERIA
        //die ('get: '.$_GET['tipo_search']);
        //if(isset($_GET['tipo_search']) && !empty($_GET['tipo_search'])){
            switch ($_GET['tipo_search']) {
                case 'web':
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                    break;
                case 'exhibicion':
                case 'exh':    
                    if($_GET['GestionDiaria']['status'] != 'exhibicion_automundo_uio'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                    }  
                    
                    break;
                case 'exhqk':    
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd'");
                    break;    
                case 'prospeccion':
                    $criteria->addCondition("gd.fuente_contacto = 'prospeccion'");
                    break;
                default:
                    if(($_GET['GestionDiaria']['status'] != 'qk' && $_GET['GestionDiaria']['status'] != 'qktd') && $cargo_id == 71 && $cargo_adicional == 0 && ($grupo_id == 2 || $grupo_id == 3)){
                       //$criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'"); 
                    } 
                        
                    break;
            }







        //}
        //    echo '<pre>';
        //    print_r($criteria);
        //    echo '</pre>';
        
        // END COMBINADAS-----------------------------------------------------------------
        //$search_type = $this->getSqlCombined($fechaPk);
        $stat = $_GET['GestionDiaria']['status'];
        if($_GET['GestionDiaria']['status'] == 'qk'){
            $stat = 'Quiero un Kia';
        }
        if($_GET['GestionDiaria']['status'] == 'qktd'){
            $stat = 'Quiero un Kia TD';
        }
        if($_GET['GestionDiaria']['status'] == 'exhibicion_automundo_uio'){
            $stat = 'Exhibición Automundo Quito';
        }
        else if($_GET['GestionDiaria']['modelo']==999){
            $criteria->join .= ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
        }
        if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
        {
              $version=$_GET['GestionDiaria']['version'];   
            $criteria->addCondition("gv.version = {$version} ");
        }

        switch ($search_type) {


            case 0:
                $title = "No existen resultados. Para realizar la búsqueda utilice sólo uno de los filtros";
                $data['title'] = $title;
                $data['users'] = $users = array();
                return $data;
                break;
            case 1: // BUSQUEDA GENERAL POR NOMBRES, APELLIDOS, CEDULA, ID
                $count = 0;
                $criteria->addCondition("(gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.ruc LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.pasaporte LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.id = '{$_GET['GestionDiaria']['general']}')", 'AND');
                
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
//              echo '<pre>';
//              print_r($criteria);
//              echo '</pre>';
//              die();

                $count = $pages->itemCount;
                //die('before render nombre:'.$count);
                if ($count > 0) {
                    //die('fef');
                    $title = "Busqueda general por apellidos, nombres, cedula, ruc, pasaporte o id: <strong>{$_GET['GestionDiaria']['general']}</strong>";
                    $data['title'] = $title;
                    $data['users'] = $users;
                    $data['pages'] = $pages;
                    return $data;
                    //die('after data');-
                }
                if ($count == 0) { // no existen resultados para ninguna opcion
                    $title = "No existen resultados para: <strong>{$_GET['GestionDiaria']['general']}</strong>";
                    $data['title'] = $title;
                    $data['users'] = $users = array();
                    return $data;
                }
                break;
            case 2: // BUSQUEDA POR CATEGORIZACION
                $sql .= $sql_cargos;
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'");
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Busqueda por Categorización: <strong>{$_GET['GestionDiaria']['categorizacion']}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 3: // BUSQUEDA POR STATUS
                
                $sql .= $sql_cargos;
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                //$title = "Busqueda por Status: <strong>{$_GET['GestionDiaria']['status']}</strong>";
                $title = "Busqueda por Status: <strong>".$stat."</strong> - <strong>Cantidad: ".$pages->itemCount."</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                //echo print_r($data['pages']);
                return $data;
                break;
            case 4: // BUSQUEDA POR FECHA
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //die($sql);
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
                //  die();
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Busqueda por Fecha: Entre <strong>{$params1} y {$params2}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 5: // BUSQUEDA POR FUENTE
                
                $criteria->addCondition(" gn.fuente = '{$_GET['GestionDiaria']['fuente']}'", 'AND');
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Busqueda por Status: <strong>{$_GET['GestionDiaria']['fuente']}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 6: // BUSQUEDA POR RESPONSABLE JEFE SUCURSAL
                
                if ($cargo_id == 69 && $_GET['GestionDiaria']['responsable'] == 'all') { // GERENTE COMERCIAL
                    $nombre_concesionario = $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                    $criteria->condition = "gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}  AND u.cargo_id IN (71,70)";
                    $criteria->group = "gi.id";
                    $criteria->order = "gi.id DESC";
                    $title = "Busqueda Total Concesionario: <strong>{$nombre_concesionario}</strong>";
                } else {
                    $criteria->addCondition("gi.responsable = '{$_GET['GestionDiaria']['responsable']}'");
                    $criteria->addCondition("gd.desiste = 0");
                    $criteria->group = "gi.id";
                    $criteria->order = "gi.id DESC";
                    $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                    $title = "Busqueda por Responsable: <strong>{$responsable}</strong>";
                }



                  if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                       
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                       
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }   


                //WHERE gi.responsable = {$_GET['GestionDiaria']['responsable']} 
                if ($_GET['GestionDiaria']['tipo'] == 'exo') {
                    $criteria->addCondition('gd.desiste = 0', 'AND');
                    $criteria->order = "gi.id DESC";
                }
                if ($_GET['GestionDiaria']['tipo'] == 'bdc') {
                    $criteria->addCondition('gi.bdc = 1', 'AND');
                    $criteria->order = "gi.id DESC";
                }
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
                //die();
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title .= ". <strong> Total: </strong>".(GestionInformacion::model()->count($criteria));
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 7:
                break;
            case 12: // BUSQUEDA POR CONCESIONARIO
                //die('get array: '.$get_array);
                $nombre_concesionario = $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                if ($cargo_id == 69 && $get_array == 'seg') { // GERENTE COMERCIAL
                    $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}");
                    $criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'");
                }
                
                if ($cargo_id == 69 && $get_array == 'web') { // GERENTE COMERCIAL
                    $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}");
                    $criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'");
                }
                if ($cargo_id == 69 && $get_array == '') { // GERENTE COMERCIAL
                    $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}");
                    $criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'");
                }
                
                if ($cargo_id == 61 && $get_array == 'web') { // JEFE DE RED AEKIA
                    $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}");
                    $criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'");
                }
                if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) { // AEKIA
                    $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}");
                    if($tipo_search == 'web'){
                        $criteria->addCondition("gd.fuente_contacto = 'web'");
                    }
                    if($tipo_search == 'pro'){
                        $criteria->addCondition("gd.fuente_contacto = 'prospeccion'");
                    }
                    if($tipo_search == 'exhibicion'){
                        $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                    }
                    $criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unmes_antes}' and '{$dt_hoy}'");
                }
                
                $criteria->addCondition('gd.desiste = 0', 'AND');
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                //die($sql);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Busqueda por Concesionario: <strong>{$nombre_concesionario}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 13: // BUSQUEDA POR RESPONSABLE
                
                if ($cargo_id == 70) { // jefe de almacen
                    $criteria->condition = "gi.dealer_id = {$dealer_id}";
                } else {
                    $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                }
                $criteria->addCondition('gd.desiste = 0', 'AND');


                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                       
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                       
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  
                 


                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Busqueda por Responsable: <strong>{$responsable}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 14: // BUSQUEDA POR GRUPO SUPER ADMINISTRADOR
                
                //$criteria->join .= " usuarios u ON u.id = gi.responsable ";
                $criteria->condition = "gd.desiste = 0";

                if (!empty($_GET['GestionDiaria']['grupo']) && !empty($_GET['GestionDiaria']['concesionario'])) {
                    $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}", 'AND');
                    $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'", 'AND');
                    $nombre_concesionario = $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                    $title = "Busqueda por Concesionario: <strong>{$nombre_concesionario}</strong>";
                }
                if (!empty($_GET['GestionDiaria']['grupo']) && empty($_GET['GestionDiaria']['concesionario'])) {
                    $criteria->addCondition("u.grupo_id = {$_GET['GestionDiaria']['grupo']}", 'AND');
                    if($tipo_search == 'web'){
                        $criteria->addCondition("gd.fuente_contacto = 'web'");
                    }
                    if($tipo_search == 'pro'){
                        $criteria->addCondition("gd.fuente_contacto = 'prospeccion'");
                    }
                    if($tipo_search == 'exhibicion'){
                        $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                    }
                    if($tipo_search == 'exhqk'){
                        $criteria->addCondition("gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd'");
                    }

                    $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'", 'AND');
                    $nombre_grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                    $title = "Busqueda por Grupo: <strong>{$nombre_grupo}</strong>";
                }
                if (!empty($_GET['GestionDiaria']['responsable']) && ($_GET['GestionDiaria']['responsable'] != 'all')) {
                    $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}", 'AND');
                    $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'", 'AND');
                    $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                    $title = "Busqueda por Responsable: <strong>{$responsable}</strong>";
                }
                if (!empty($_GET['GestionDiaria']['responsable']) && ($_GET['GestionDiaria']['responsable'] == 'all')) {
                    $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}", 'AND');
                    $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unmes_antes}' and '{$dt_hoy}'", 'AND');
                    $title = "Busqueda por Concesionario Total: <strong>{$_GET['GestionDiaria']['concesionario']}</strong>";
                }


                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                         
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                       
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //die($sql);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                //$request = $con->createCommand($sql);
                //$users = $request->queryAll();

                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 15: // BUSQUEDA POR CAMPOS VACIOS
                $criteria = new CDbCriteria;
                $criteria->select = "gi.id , gi.nombres, gi.apellidos, gi.cedula, 
                    gi.ruc,gi.pasaporte,gi.email, gi.responsable,gi.tipo_form_web,gi.fecha, gi.bdc, gi.dealer_id, gi.id_cotizacion,
                    gi.reasignado,gi.responsable_cesado,gi.id_comentario";
                $criteria->alias = 'gi';
                $criteria->join = 'INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion';
                $criteria->join .= ' LEFT JOIN gestion_consulta gc ON gi.id = gc.id_informacion';
                $criteria->join .= ' INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
                $criteria->join .= ' INNER JOIN usuarios u ON u.id = gi.responsable';

                if ($cargo_id == 70) {
                    $criteria->condition = "gi.bdc = 0 AND gi.dealer_id = {$dealer_id} AND gd.desiste = 0 ";
                    $criteria->group = "gi.id";
                    $criteria->order = "gi.id DESC";
                }
                if ($cargo_id = 71) {
                    $criteria->condition = "gi.bdc = 0 AND gi.dealer_id = {$dealer_id} AND gd.desiste = 0 ";
                    $criteria->group = "gi.id";
                    $criteria->order = "gi.id DESC";
                }
                if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) {
                    $criteria->join .= ' gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gi.bdc = 0 AND gi.dealer_id = {$dealer_id} AND gd.desiste = 0 ";
                    $criteria->group = "gi.id";
                    $criteria->order = "gi.id DESC";
                }


                 if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                        
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                       
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  


                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);

                //$request = $con->createCommand($sql);
                //$users = $request->queryAll();
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 16:
                //die('get array: '.$get_array);
                if ($cargo_id === 46) { // super administrador
                    //echo 'asdqedfwef';
                    $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $title = "Busqueda Total País";
                }
                if ($cargo_id === 69 && $get_array === '') { // gerente comercial
                    //$criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gr.id_grupo = {$grupo_id}";
                    $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
                    $title = "Busqueda por Grupo Total: <strong>" . $this->getNombreGrupo($grupo_id) . "</strong>";
                }
                if ($cargo_id === 69 && $get_array === 'bdc') { // gerente comercial
                    //$criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gr.id_grupo = {$grupo_id}";
                    $criteria->addCondition('u.cargo_id IN (72,73)', 'AND');
                    $title = "Busqueda por Grupo Total: <strong>" . $this->getNombreGrupo($grupo_id) . "</strong>";
                }
                if ($cargo_id === 69 && $get_array === 'web') { // gerente comercial
                    //$criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gr.id_grupo = {$grupo_id}";
                    $criteria->addCondition('u.cargo_id IN (85,86)', 'AND');
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                    $title = "Busqueda por Grupo Total: <strong>" . $this->getNombreGrupo($grupo_id) . "</strong>";
                }
                if ($cargo_id === 69 && ($get_array === 'exh' || $get_array === 'exhibicion')) { // gerente comercial
                    //$criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gr.id_grupo = {$grupo_id}";
                    $criteria->addCondition('u.cargo_id IN (70,71)', 'AND');
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                    $title = "Busqueda por Grupo Total: <strong>" . $this->getNombreGrupo($grupo_id) . "</strong>";
                }
                if ($cargo_id === 69 && ($get_array === 'exhqk')) { // gerente comercial
                    //$criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gr.id_grupo = {$grupo_id}";
                    $criteria->addCondition('u.cargo_id IN (70,71)', 'AND');
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia'");
                    $title = "Busqueda por Grupo Total: <strong>" . $this->getNombreGrupo($grupo_id) . "</strong>";
                }
                if ($cargo_id === 69 && $get_array === 'exo') { // gerente comercial
                    //$criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gr.id_grupo = {$grupo_id}";
                    $criteria->addCondition('u.cargo_id IN (75)', 'AND');
                    $title = "Busqueda por Grupo Total: <strong>" . $this->getNombreGrupo($grupo_id) . "</strong>";
                }
                if ($cargo_id === 69 && $get_array === 'seg') { // gerente comercial
                    //$criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gr.id_grupo = {$grupo_id}";
                    $criteria->addCondition('u.cargo_id IN (71)', 'AND');
                    $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
                    $title = "Busqueda por Grupo Total: <strong>" . $this->getNombreGrupo($grupo_id) . "</strong>";
                }
                if ($cargo_id === 70) { // jefe de almacen
                    //$criteria->addCondition("gi.dealer_id = {$dealer_id}");
                    //if($tipo_search == ''){
                    //    $criteria->addCondition("gi.bdc = 0");
                    //    $criteria->addCondition("gd.fuente_contacto = 'showroom'");
                    //}
                    if($tipo_search == 'web'){
                        $criteria->addCondition("gd.fuente_contacto = 'web'");
                    }
                    if($tipo_search == 'pro'){
                        $criteria->addCondition("gd.fuente_contacto = 'prospeccion'");
                    }
                    if($tipo_search == 'exhibicion'){
                        $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                    }
                    if($tipo_search == 'exhqk'){
                        $criteria->addCondition("gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia'");
                    }
                    $title = "Busqueda por Total Concesionario : <strong>{$dealer_id}</strong>";
                }
                if ($cargo_id === 72) { // jefe bdc
                    $array_dealers = $this->getDealerGrupoConc($grupo_id);
                    $dealerList = implode(', ', $array_dealers);
                    if ($_GET['GestionDiaria']['tipo'] == 'exo') {
                        $sql .= " INNER JOIN usuarios u ON u.id = gi.responsable 
                                 WHERE gi.tipo_form_web = 'exonerados' AND gi.dealer_id IN ({$dealerList}) AND u.cargo_id = 75 ";
                    }
                    if ($_GET['GestionDiaria']['tipo'] == 'bdc') {
                        $sql .= " INNER JOIN usuarios u ON u.id = gi.responsable 
                                WHERE gi.bdc = 1 AND gi.dealer_id IN ({$dealerList}) AND u.cargo_id = 73 ";
                    }

                    $title = "Busqueda por Total BDC : <strong>{$dealer_id}</strong>";
                }
                if ($cargo_id === 71) { // asesor de ventas
                    $criteria->condition = "gi.responsable = {$id_responsable}";
                    $title = "Busqueda por Total de Asesor Ventas: <strong>{$id_responsable}</strong>";
                }
                if ($cargo_id === 73) { // asesor bdc
                    $array_dealers = $this->getDealerGrupoConc($grupo_id);
                    $dealerList = implode(', ', $array_dealers);
                    $criteria->condition = "gi.bdc = 1";
                    $criteria->addCondition("gi.dealer_id IN ({$dealerList})", 'AND');
                    $criteria->addCondition("gi.responsable = {$id_responsable}", 'AND');
                    $title = "Busqueda por Total de Asesor Ventas: <strong>{$id_responsable}</strong>";
                }
                if ($cargo_id === 75) { // asesor exonerados
                    $array_dealers = $this->getDealerGrupoConc($grupo_id);
                    $dealerList = implode(', ', $array_dealers);
                    $criteria->condition = "gi.tipo_form_web = 'exonerados'";
                    $criteria->addCondition("gi.dealer_id IN ($dealerList)", 'AND');
                    $criteria->addCondition("gi.responsable = {$id_responsable}", 'AND');
                    $title = "Busqueda por Total de Asesor Ventas: <strong>{$id_responsable}</strong>";
                }
                if ($area_id === 4 || $area_id === 12 || $area_id === 13 || $area_id === 14) { // AEKIA USERS
                    $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    if($tipo_search == ''){
                        $criteria->addCondition("gi.bdc = 0");
                        $criteria->addCondition("gd.fuente_contacto = 'showroom'");
                    }
                    if($tipo_search == 'web'){
                        $criteria->addCondition("gd.fuente_contacto = 'web'");
                    }
                    if($tipo_search == 'pro'){
                        $criteria->addCondition("gd.fuente_contacto = 'prospeccion'");
                    }
                    if($tipo_search == 'exhibicion'){
                        $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                    }
                    if($tipo_search == 'exhqk'){
                        $criteria->addCondition("gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia'");
                    }
                    

                    $title = "Busqueda por Total País";
                }
                if($cargo_id === 85){ // JEFE DE VENTAS WEB
                    $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->condition = "gr.id_grupo = {$grupo_id}";
                    $criteria->addCondition('u.cargo_id IN (86)', 'AND');
                }

                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  
                

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //die($sql);
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 17: // BUSQUEDA POR RESPONSABLE AEKIA
                if ($_GET['GestionDiaria']['responsable'] == 'all') {
                    $criteria->condition = "gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}";
                }
                if ($tipo_search == 'web') {
                    $criteria->condition = "gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}";
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                }
                else{
                    $criteria->condition = "gi.responsable = '{$_GET['GestionDiaria']['responsable']}'";
                }
                if ($get_array == ''){
                    $criteria->addCondition("gd.fuente_contacto = 'showroom'");
                }
                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                       
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                       
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                if($_GET['GestionDiaria']['responsable'] != 'all'){
                    $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                }
                $title = "Busqueda por Responsable: <strong>{$responsable}</strong>";
                //die($sql);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);

                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;

            case 18: // BUSQUEDA POR FECHA
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                
                $criteria->addCondition("gd.fecha BETWEEN '{$params1}' AND '{$params2}'");
                $criteria->group = "gi.cedula, gi.ruc, gi.pasaporte";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Busqueda por Fecha: Entre <strong>{$params1} y {$params2}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 19: // SEARCH BY SEGUIMIENTO
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}'", 'AND');
                        $title = "Busqueda por Agendamiento para hoy: <strong>{$fecha_actual}</strong>";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = ''", 'AND');
                        $title = "Busqueda por Agendamiento: <strong>Vacío</strong>";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}'", 'AND');
                        $title = "Busqueda por Agendamiento para entre: <strong>{$fecha1} - {$fecha2} </strong>";
                        break;

                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
                //die('fecha actual: '.$fecha_actual);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);

                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 20: // SEARCH BY CATEGORIZACION AND STATUS
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'", 'AND');
                

                


                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);

                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 21: // SEARCH BY CATEGORIZACION, STATUS AND RESPONSABLE
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'", 'AND');


                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                       
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                       
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Categorización: <strong>{$_GET['GestionDiaria']['categorizacion']}</strong>, Status: <strong>{$stat}</strong>, Responsable: <strong>{$responsable}</strong> - <strong>Cantidad: ".$pages->itemCount."</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;

            case 22: // SEARCH BY CATEGORIZACION AND RESPONSABLE
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'", 'AND');
                $criteria->addCondition('gd.desiste = 0', 'AND');


                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                       
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                       
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  


                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Categorización: <strong>{$_GET['GestionDiaria']['categorizacion']}</strong> y Responsable: <strong>{$responsable}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 23: // SEARCH BY CATEGORIZACION, RESPONSABLE AND SEGUIMIENTO
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'", 'AND');
                $criteria->addCondition('gd.desiste = 0', 'AND');
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}'", 'AND');
                        $title_ag = "Agendamiento para hoy: <strong>{$fecha_actual}</strong>";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = ''", 'AND');
                        $title_ag = "Agendamiento: <strong>Vacío</strong>";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}'", 'AND');
                        $title_ag = "Agendamiento para entre: <strong>{$fecha1} - {$fecha2} </strong>";
                        break;

                    default:
                        break;
                }

                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                         
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                        
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Categorización: <strong>{$_GET['GestionDiaria']['categorizacion']}</strong>, Responsable: <strong>{$responsable}</strong>, {$title_ag} ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 24: // SEARCH BY CATEGORIZACION, RESPONSABLE AND SEGUIMIENTO WITH RANGE DATE
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'", 'AND');
                $criteria->addCondition('gd.desiste = 0', 'AND');
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}'", 'AND');
                        $title_ag = "Agendamiento para hoy: <strong>{$fecha_actual}</strong>";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = ''", 'AND');
                        $title_ag = "Agendamiento: <strong>Vacío</strong>";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}'", 'AND');
                        $title_ag = "Agendamiento para entre: <strong>{$fecha1} - {$fecha2} </strong>";
                        break;
                    default:
                        break;
                }

                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                         
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                        
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Categorización: <strong>{$_GET['GestionDiaria']['categorizacion']}</strong>, Responsable: <strong>{$responsable}</strong>, {$title_ag} ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 25: // SEARCH BY CATEGORIZACION AND FECHA DE REGISTRO
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'", 'AND');
                $criteria->addCondition('gd.desiste = 0', 'AND');

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Categorización: <strong>{$_GET['GestionDiaria']['categorizacion']}</strong>, Fecha: <strong>{$_GET['GestionDiaria']['fecha']}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 26: // SEARCH BY STATUS AND FECHA DE REGISTRO
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Status: <strong>{$stat}</strong>, Fecha: <strong>{$_GET['GestionDiaria']['fecha']}</strong>- <strong>Cantidad: ".$pages->itemCount."</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 27: // SEARCH BY CATEGORIZACION, RESPONSABLE AND REGISTER DATE
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'", 'AND');
                $criteria->addCondition('gd.desiste = 0', 'AND');
                $fecha_actual = (string) date("Y/m/d");


                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                         
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                        
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Categorización: <strong>{$_GET['GestionDiaria']['categorizacion']}</strong>, Responsable: <strong>{$responsable}</strong>, Fecha de Registro: <strong>{$_GET['GestionDiaria']['fecha']}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 28: // SEARCH BY RESPONSABLE AND SEGUIMIENTO
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                $criteria->addCondition('gd.desiste = 0', 'AND');
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}'", 'AND');
                        $title_ag = "Agendamiento para hoy: <strong>{$fecha_actual}</strong>";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = ''", 'AND');
                        $title_ag = "Agendamiento: <strong>Vacío</strong>";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}'", 'AND');
                        $title_ag = "Agendamiento para entre: <strong>{$fecha1} - {$fecha2} </strong>";
                        break;

                    default:
                        break;
                }


                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                         
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                        
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  


                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Responsable: <strong>{$responsable}</strong>, {$title_ag} ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 29: // SEARCH BY RESPONSABLE, SEGUIMIENTO AND DATE RANGE
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                $criteria->addCondition('gd.desiste = 0', 'AND');
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}'", 'AND');
                        $title_ag = "Agendamiento para hoy: <strong>{$fecha_actual}</strong>";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = ''", 'AND');
                        $title_ag = "Agendamiento: <strong>Vacío</strong>";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}'", 'AND');
                        $title_ag = "Agendamiento para entre: <strong>{$fecha1} - {$fecha2} </strong>";
                        break;

                    default:
                        break;
                }

            if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                         
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                        
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Responsable: <strong>{$responsable}</strong>, {$title_ag} ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 33: // SEARCH BY BUSQUEDA GENERAL, GRUPO , CONCESIONARIO Y RESPONSABLE
                
                $criteria->addCondition("(gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%')", 'AND');
                $criteria->addCondition("(gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.ruc LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.pasaporte LIKE '%{$_GET['GestionDiaria']['general']}%')",'OR');
                $criteria->addCondition("gi.id = '{$_GET['GestionDiaria']['general']}'",'OR');
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}",'AND');
                if($tipo_search == 'web'){
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                }
                if($tipo_search == 'exhibicion'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                }
                if($tipo_search == 'exhqk'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion quierounkiatd' OR gd.fuente_contacto = 'exhibicion quierounkiatd'");
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Búsqueda por Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong>, Responsable: <strong>{$responsable}</strong>, {$title_ag} ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 34: // SEARCH BY BUSQUEDA GENERAL, GRUPO , CONCESIONARIO, STATUS Y RESPONSABLE
                $criteria->addCondition("(gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%')", 'AND');
                $criteria->addCondition("(gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.ruc LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.pasaporte LIKE '%{$_GET['GestionDiaria']['general']}%')",'OR');
                $criteria->addCondition("gi.id = '{$_GET['GestionDiaria']['general']}'",'OR');
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}",'AND');
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Búsqueda por Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong>, Status: <strong>{$stat}</strong> ,Responsable: <strong>{$responsable}</strong>, {$title_ag} - <strong>Cantidad: ".$pages->itemCount."</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 35: // SEARCH BY BUSQUEDA GENERAL, GRUPO , CONCESIONARIO, STATUS, RESPONSABLE AND FECHA DE REGISTRO
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("(gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%')", 'AND');
                $criteria->addCondition("(gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.ruc LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.pasaporte LIKE '%{$_GET['GestionDiaria']['general']}%')",'OR');
                $criteria->addCondition("gi.id = '{$_GET['GestionDiaria']['general']}'",'OR');
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}",'AND');
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Búsqueda por Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong>, Status: <strong>{$stat}</strong> ,Responsable: <strong>{$responsable}</strong>, {$title_ag} - <strong>Cantidad: ".$pages->itemCount."</strong> ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 36: // SEARCH BY GRUPO , CONCESIONARIO AND STATUS
                // SEARCH BY ONE MONTH BESIDE
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}", 'AND');
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unmes_antes}' and '{$dt_hoy}'", 'AND');
                $concesionario = $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Búsqueda por Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong>, Status: <strong>{$stat}</strong>, {$title_ag} - <strong>Cantidad: ".$pages->itemCount."</strong> ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 38: // SEARCH BY FECHA DE REGISTRO, GRUPO AND CONCESIONARIO
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}",'AND');
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Fecha de Registro: <strong>{$_GET['GestionDiaria']['fecha']}</strong>, Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 39: // SEARCH BY FECHA DE REGISTRO, GRUPO, CONCESIONARIO AND RESPONSABLE
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}",'AND');
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}",'AND');
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Fecha de Registro: <strong>{$_GET['GestionDiaria']['fecha']}</strong>, Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong>, Responsable <strong>{$responsable}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 40: // SEARCH BY BUSQUEDA GENERAL, GRUPO AND CONCESIONARIO
                
                $criteria->addCondition("(gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%')", 'AND');
                $criteria->addCondition("(gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.ruc LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.pasaporte LIKE '%{$_GET['GestionDiaria']['general']}%')",'OR');
                $criteria->addCondition("gi.id = '{$_GET['GestionDiaria']['general']}'",'OR');
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}",'AND');
                if($tipo_search == 'web'){
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                }
                if($tipo_search == ''){
                    $criteria->addCondition("gd.fuente_contacto = 'showroom'");
                }
                if($tipo_search == 'exhibicion'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                }
                if($tipo_search == 'exhqk'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd'");
                }
                
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Busqueda general : <strong>{$_GET['GestionDiaria']['general']}</strong>, Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 41: // SEARCH BY BUSQUEDA GENERAL AND GRUPO
                $criteria->addCondition("(gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%')", 'AND');
                $criteria->addCondition("(gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.ruc LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.pasaporte LIKE '%{$_GET['GestionDiaria']['general']}%')",'OR');
                $criteria->addCondition("gi.id = '{$_GET['GestionDiaria']['general']}'",'OR');
                $criteria->addCondition("u.grupo_id = {$_GET['GestionDiaria']['grupo']}",'AND');
                if($tipo_search == 'web'){
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                }
                if($tipo_search == 'exhibicion'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                }
                if($tipo_search == 'exhqk'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd'");
                }
                
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Busqueda general : <strong>{$_GET['GestionDiaria']['general']}</strong>, Grupo: <strong>{$grupo}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 42: // SEARCH BY GRUPO , CONCESIONARIO, STATUS AND RESPONSABLE
                // SEARCH BY ONE MONTH BESIDE
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}", 'AND');
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unmes_antes}' and '{$dt_hoy}'", 'AND');
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}",'AND');
                $concesionario = $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Búsqueda por Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong>, Status: <strong>{$stat}</strong>, {$title_ag} - <strong>Cantidad: ".$pages->itemCount."</strong> ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break; 
            case 43: // SEARCH BY STATUS, FECHA DE REGISTRO, GRUPO AND CONCESIONARIO
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}",'AND');
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                
                $grupo = $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
//                echo '<pre>';s
//                print_r($criteria);
//                echo '</pre>';
                $title = "Búsqueda por Status: <strong>{$stat}</strong>, Fecha de Registro: <strong>{$_GET['GestionDiaria']['fecha']}</strong>, Grupo: <strong>{$grupo}</strong>, Concesionario: <strong>{$concesionario}</strong> - <strong>Cantidad: ".$pages->itemCount."</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 44: // SEARCH BY RESPONSABLE AND REGISTER DATE
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria']['responsable']}";
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $criteria->addCondition('gd.desiste = 0', 'AND');
                $fecha_actual = (string) date("Y/m/d");


                if (!empty($_GET['GestionDiaria']['modelo']) && $_GET['GestionDiaria']['modelo']>0 && $_GET['GestionDiaria']['modelo']!=999) {
                         $criteria->join .= ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
                         $modelo=$_GET['GestionDiaria']['modelo'];            
                         $criteria->addCondition("gv.modelo = {$modelo} ");
                    }
                    else if($_GET['GestionDiaria']['modelo']==999){
                        $criteria->join .= ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
                    }
                    if (!empty($_GET['GestionDiaria']['version']) && $_GET['GestionDiaria']['version']>0 && $_GET['GestionDiaria']['version']!=999)
                    {
                          $version=$_GET['GestionDiaria']['version'];   
                        $criteria->addCondition("gv.version = {$version} ");
                    }  

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Responsable: <strong>{$responsable}</strong>, Fecha de Registro: <strong>{$_GET['GestionDiaria']['fecha']}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 45: // SEARCH BY CATEGORIZACION, STATUS AND RESPONSABLE
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}");
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por Status: <strong>{$stat}</strong>, Responsable: <strong>{$responsable}</strong> - <strong>Cantidad: ".$pages->itemCount."</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 46: // SEARCH BY STATUS, FECHA DE REGISTRO, CONCESIONARIO
                $params = explode('-', $_GET['GestionDiaria']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}",'AND');
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}'", 'AND');
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                
                
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
//                echo '<pre>';s
//                print_r($criteria);
//                echo '</pre>';
                $title = "Búsqueda por Status: <strong>{$stat}</strong>, Fecha de Registro: <strong>{$_GET['GestionDiaria']['fecha']}</strong>, Concesionario: <strong>{$concesionario}</strong> - <strong>Cantidad: ".$pages->itemCount."</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 47: // SEARCH BY CONCESIONARIO AND STATUS
                // SEARCH BY ONE MONTH BESIDE
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}", 'AND');
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unmes_antes}' and '{$dt_hoy}'", 'AND');
                $concesionario = $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Búsqueda por Concesionario: <strong>{$concesionario}</strong>, Status: <strong>{$stat}</strong>, {$title_ag} - <strong>Cantidad: ".$pages->itemCount."</strong> ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 48: // SEARCH BY CONCESIONARIO, STATUS AND RESPONSABLE 
                // SEARCH BY ONE MONTH BESIDE
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}", 'AND');
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}");
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unmes_antes}' and '{$dt_hoy}'", 'AND');
                $concesionario = $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                $condition = self::setStatusCriteria($_GET['GestionDiaria']['status']);
                $criteria->addCondition($condition);
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Búsqueda por Concesionario: <strong>{$concesionario}</strong>, Status: <strong>{$stat}</strong>, {$title_ag} Responsable: <strong>{$responsable}</strong> - <strong>Cantidad: ".$pages->itemCount."</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
            case 49: // SEGUIMIENTO - CONCESIONARIO
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}", 'AND');
                $criteria->addCondition('gd.desiste = 0', 'AND');
                $fecha_actual = (string) date("Y/m/d");
                $concesionario = $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                switch ($_GET['GestionDiaria']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}'", 'AND');
                        $title_ag = "Agendamiento para hoy: <strong>{$fecha_actual}</strong>";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = ''", 'AND');
                        $title_ag = "Agendamiento: <strong>Vacío</strong>";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}'", 'AND');
                        $title_ag = "Agendamiento para entre: <strong>{$fecha1} - {$fecha2} </strong>";
                        break;

                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $title = "Búsqueda por ".$title_ag.", Concesionario: <strong>{$concesionario}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;

                break;
            case 50: // SEARCH BY BUSQUEDA GENERAL AND CONCESIONARIO
                
                $criteria->addCondition("(gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%')", 'AND');
                $criteria->addCondition("(gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.ruc LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.pasaporte LIKE '%{$_GET['GestionDiaria']['general']}%')",'OR');
                $criteria->addCondition("gi.id = '{$_GET['GestionDiaria']['general']}'",'OR');
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}",'AND');
                if($tipo_search == 'web'){
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                }
                if($tipo_search == ''){
                    $criteria->addCondition("gd.fuente_contacto = 'showroom'");
                }
                if($tipo_search == 'exhibicion'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion'");
                }
                if($tipo_search == 'exhqk'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd'");
                }

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Busqueda general : <strong>{$_GET['GestionDiaria']['general']}</strong>, Concesionario: <strong>{$concesionario}</strong>";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;
                
            case 51: // SEARCH BY BUSQUEDA GENERAL, CONCESIONARIO Y RESPONSABLE - GERENTE COMERCIAL
                
                $criteria->addCondition("(gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%')", 'AND');
                $criteria->addCondition("(gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.ruc LIKE '%{$_GET['GestionDiaria']['general']}%' OR gi.pasaporte LIKE '%{$_GET['GestionDiaria']['general']}%')",'OR');
                $criteria->addCondition("gi.id = '{$_GET['GestionDiaria']['general']}'",'OR');
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria']['responsable']}",'AND');
                $criteria->addCondition("gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}",'AND');
                if($tipo_search == 'web'){
                    $criteria->addCondition("gd.fuente_contacto = 'web'");
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $pages = new CPagination(GestionInformacion::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                $users = GestionInformacion::model()->findAll($criteria);
                $concesionario = $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $title = "Búsqueda por Concesionario: <strong>{$concesionario}</strong>, Responsable: <strong>{$responsable}</strong>, {$title_ag} ";
                $data['title'] = $title;
                $data['users'] = $users;
                $data['pages'] = $pages;
                return $data;
                break;    
            default:
                break;
        }


    }
    
    private static function setStatusCriteria($status){
        $condition = "";
        switch ($status) {
            case 'Cierre':
                $condition = "gd.paso = 9 OR gd.cierre = 1";
                break;
            case 'Desiste':
                $condition = "gd.desiste = 1";
                break;
            case 'Entrega':
                $condition = "gd.entrega = 1 AND gd.paso = 9";
                break;
            case 'PrimeraVisita':
                $condition = "gd.paso = '1-2'";
                break;
            case 'Seguimiento':
                $condition = "gd.seguimiento = 1";
                break;
            case 'SeguimientoEntrega':
                $condition = "gd.seguimiento_entrega = 1 AND gd.paso = 10";
                break;
            case 'Vendido':
                $condition = "gd.seguimiento = 1 AND gd.paso = 10 AND gd.status = 1";
                break;
            case 'Web':
                $condition = "gd.medio_contacto = 'web'";
                break;
            case 'qk':
                $condition = "gd.medio_contacto = 'exhquk' AND gd.status = 1";
                break;
            case 'qktd':
                $condition = "gd.medio_contacto = 'exhquktd' AND gd.status = 1";
                break;
            case 'exhibicion_automundo_uio':
                $condition = "gd.fuente_contacto = 'exhibicion_automundo_uio' AND gd.status = 1";
                break;  
            case 'exhibicion_automundo_gye':
                $condition = "gd.fuente_contacto = 'exhibicion_automundo_gye' AND gd.status = 1";
                break;       
            default:
                break;
        }
        return $condition;        
    }
    
    public function getNombreMesGraficas($mes_actual) {
        $fmes = '';
        switch ($mes_actual) {
            case 0:
                $fmes = 'ene';
                break;
            case 1:
                $fmes = 'feb';
                break;
            case 2:
                $fmes = 'mar';
                break;
            case 3:
                $fmes = 'abr';
                break;
            case 4:
                $fmes = 'may';
                break;
            case 5:
                $fmes = 'jun';
                break;
            case 6:
                $fmes = 'jul';
                break;
            case 7:
                $fmes = 'ago';
                break;
            case 8:
                $fmes = 'sep';
                break;
            case 9:
                $fmes = 'oct';
                break;
            case 10:
                $fmes = 'nov';
                break;
            case 11:
                $fmes = 'dic';
                break;
            default:
                break;
        }
        return $fmes;
    }
    
    public function getNombreMesGraficasG($mes_actual) {
        $fmes = '';
        switch ($mes_actual) {
            case 1:
                $fmes = 'ene';
                break;
            case 2:
                $fmes = 'feb';
                break;
            case 3:
                $fmes = 'mar';
                break;
            case 4:
                $fmes = 'abr';
                break;
            case 5:
                $fmes = 'may';
                break;
            case 6:
                $fmes = 'jun';
                break;
            case 7:
                $fmes = 'jul';
                break;
            case 8:
                $fmes = 'ago';
                break;
            case 9:
                $fmes = 'sep';
                break;
            case 10:
                $fmes = 'oct';
                break;
            case 11:
                $fmes = 'nov';
                break;
            case 12:
                $fmes = 'dic';
                break;
            default:
                break;
        }
        return $fmes;
    }
    /**
     * 
     * @param int $mes Mes actual del año
     * @return array
     */
    public function getFechasActivas($mes) {
        $data = array();
        $data = '';
        // SI MES ACTUAL ES ENERO, FEBRERO O MARZO
        if($mes == 1){ // enero
            $meses = [5,6,7,8,9,10,11,12,1];
            $years = [2016,2016,2016,2016,2016,2016,2016,2016,2017];
        }
        if($mes == 2){ // febrero
            $meses = [5,6,7,8,9,10,11,12,1,2];
            $years = [2016,2016,2016,2016,2016,2016,2016,2016,2017,2017];
        }
        if($mes == 3){ // marzo
            $meses = [5,6,7,8,9,10,11,12,1,2,3];
            $years = [2016,2016,2016,2016,2016,2016,2016,2016,2017,2017,2017];
        }
        $data['meses'] = $meses;
        $data['years'] = $years;
        return $data;
    }

    public function getDireccionKmotor($id_asesor){
        $dir = Usuarios::model()->find(array('condition' => "id = {$id_asesor}"));
        if($dir){
            return $dir->direccion;
        }else{
            return 'NA';
        }
    }

    public function getTipoVehiculo($id_modelo){
        $tipo = Versiones::model()->find(array("condition" => "id_modelos = {$id_modelo}"));
        if($tipo){
            switch ($tipo->tipo_vehiculo) {
                case 'LP':
                    return 'LP';
                    break;
                case 'LC':
                    return 'LC';
                    break; 
                case 'LC':
                    return 'LC';
                    break;          
                
                default:
                    # code...
                    break;
            }
            return $tipo->categoria;
        }else{
            return 'NA';
        }
        
        
    }

    public function getConcesionariosDobleCargo($id_responsable){
        $conc = Grupoconcesionariousuario::model()->findAll(array("condition" => "usuario_id = {$id_responsable} AND tipo_id = 2"));

    }

}
