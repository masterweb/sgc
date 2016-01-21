<?php

/**
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

    public function getTema($id) {
        $tema = Menu::model()->findByPk($id);
        return $tema->name;
    }

    public function getSubtema($id) {
        $tema = Submenu::model()->findByPk($id);
        return $tema->name;
    }

    public function getProvincia($id) {
        $provincia = Provincias::model()->findByPk($id);
        if (!is_null($provincia) && !empty($provincia)) {
            return $provincia->nombre;
        } else {
            return 'NA';
        }
    }

    public function getProvinciaIdDomicilio($id_informacion) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id={$id_informacion}";
        $provincia = GestionInformacion::model()->find($criteria);
        return $provincia->provincia_domicilio;
    }

    public function getCiudad($id) {
        $ciudad = Dealercities::model()->findByPk($id);
        if (!is_null($ciudad) && !empty($ciudad)) {
            return $ciudad->name;
        } else {
            return 'NA';
        }
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
        $dealers = Dealers::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->name;
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

    public function getConcesionarioDireccion($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);
        //return $dealer->concesionario_id;
        if ($dealer->concesionario_id == 0) {
            $criteria2 = new CDbCriteria(array(
                'condition' => "usuario_id={$id}"
            ));
            $usuario = Grupoconcesionariousuario::model()->find($criteria2);
            $id_conc = $usuario->concesionario_id;
            $criteria2 = new CDbCriteria(array(
                'condition' => "id={$id_conc}"
            ));
            $deal = Dealers::model()->find($criteria2);
            return $deal->direccion;
        } else {
            $id_conc = $dealer->consecionario->dealer->id;
            $criteria2 = new CDbCriteria(array(
                'condition' => "id={$id_conc}"
            ));
            $deal = Dealers::model()->find($criteria2);
            return $deal->direccion;
        }

    }

    public function getNombreGrupo($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = GrGrupo::model()->find($criteria);
        return $dealer->nombre_grupo;
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
    
   public function getAsesorCredito($id) {
        $dealers = Usuarios::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            $concesionario_id = $dealers->concesionario_id;
            if ($concesionario_id == 0) {
                $criteria2 = new CDbCriteria(array(
                    'condition' => "usuario_id={$id}"
                ));
                $usuario = Grupoconcesionariousuario::model()->find($criteria2);
                $concesionario_id = $usuario->concesionario_id;
                //die('conc: '.$concesionario_id);
                $criteria3 = new CDbCriteria(array(
                    'condition' => "cargo_id=74 AND dealers_id={$concesionario_id}"
                ));
                $asesor_credito =  Usuarios::model()->find($criteria3);
//                echo '<pre>';
//                print_r($asesor_credito);
//                echo '</pre>'; 
//                die();
                return $asesor_credito->correo;
            }else{
                $asesor_credito =  Usuarios::model()->find($criteria3);
                return $asesor_credito->correo;
            }
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
            $params = explode('-', $dealers->celular);
            return $params[1] . '-' . $params[2];
            //return $dealers->celular;
        } else {
            return 'NA';
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

    public function getEmailJefeConcesion($cargo_id, $grupo_id) {
        $criteria = new CDbCriteria;
        $criteria->condition = "cargo_id={$cargo_id} AND grupo_id = {$grupo_id}";
        $usuario = Usuarios::model()->find($criteria);
        if (!is_null($usuario) && !empty($usuario)) {
            return $usuario->correo;
        } else {
            return 'alkanware@gmail.com';
        }
    }

    public function setBotonCotizacion($paso, $id, $fuente, $id_informacion) {
        $data = '';
        switch ($paso) {
            case 1:
            case 2:
                $data = '<a href="' . Yii::app()->createUrl('gestionInformacion/update', array('id' => $id_informacion, 'tipo' => 'gestion')) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
                break;
            case 3:
                $data = '<a href="' . Yii::app()->createUrl('gestionVehiculo/create', array('id' => $id_informacion)) . '" class="btn btn-primary btn-xs btn-danger">Continuar</a>';
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
                $data = '<a href="' . Yii::app()->createUrl('gestionInformacion/create', array('tipo' => 'gestion', 'id' => $id, 'fuente' => $fuente)) . '" class="btn btn-primary btn-xs btn-danger">Nueva Cotización</a>';
                break;


            default:
                break;
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
        //die();
        if (!is_null($responsableid) && !empty($responsableid)) {
            return $responsableid->nombres . ' ' . $responsableid->apellido;
        } else {
            return 'NA';
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

    public function getPrecio($id, $tipo) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $modelo = GestionVehiculo::model()->find($criteria);
        $version = $modelo->version;
        $criteria2 = new CDbCriteria(array(
            "condition" => "id_versiones = {$version}",
        ));
        $modeloversion = Versiones::model()->find($criteria2);
        if ($tipo == 1) {
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

    public function getNombresInfo($id_informacion) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id_informacion}",
        ));
        $gestion = GestionInformacion::model()->find($criteria);
        return ucfirst($gestion->nombres);
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
        return $gestion->apellidos;
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

    public function getIdentificacionRuc($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionNuevaCotizacion::model()->find($criteria);
        return $gestion->ruc;
    }

    public function getIdentificacionPasaporte($id) {
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id}",
        ));
        $gestion = GestionNuevaCotizacion::model()->find($criteria);
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
            return 'na';
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

    public function getVehiculosInterados($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id}'"
        ));
        $vec = GestionVehiculo::model()->findAll($criteria);
        $count = count($vec);
        return $count;
    }

    public function getTestDriveOnly($id_informacion) {
        //die($id_informacion);
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion} AND (test_drive = 1 OR test_drive = 0)"
        ));
        $test = GestionTestDrive::model()->count($criteria);
        return $test;
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
            return $dealer->concesionario_id;
        }
    }

    public function getNameConcesionario($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);
        if ($dealer->concesionario_id == 0) {
            //die('enter no conc');
            $criteria2 = new CDbCriteria(array(
                'condition' => "usuario_id={$id}"
            ));
            $usuario = Grupoconcesionariousuario::model()->find($criteria2);
            $cri = new CDbCriteria(array(
                'condition' => "id={$usuario->concesionario_id}"
            ));
            $deal = Dealers::model()->find($cri);
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
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Dealers::model()->find($criteria);
        //return $dealer->concesionario_id;
        return $dealer->name;
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
        return $usuarios->dealers_id;
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

    public function getResponsableNombres($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);
        return ucfirst($dealer->nombres) . ' ' . ucfirst($dealer->apellido);
    }

    public function getCityId($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $city = Dealers::model()->find($criteria);
        return $city->cityid;
    }

    public function getProvinciaId($id) {
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

    public function getPaso($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $ps = GestionDiaria::model()->find($criteria);
        return $ps->paso;
    }

    public function getPasoNotificacion($id) {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $ps = GestionNotificaciones::model()->find($criteria);
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

    public function getFinanciamiento($id_informacion) {
        $con = Yii::app()->db;
        $sql = "SELECT gc.preg6 FROM gestion_consulta gc 
        INNER JOIN gestion_informacion gi ON gi.id = gc.id_informacion 
        WHERE gi.id = {$id_informacion} ";
        $request = $con->createCommand($sql)->query();
        $tipo = '';
        foreach ($request as $value) {
            $tipo = $value['preg6'];
        }
        return $tipo;
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

    public function getAnswer($tipo, $id) {
        $preg = FALSE;
        switch ($tipo) {
            case 1: // preguntas paso 1-2 prospeccion
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id}"
                ));
                $pr = GestionProspeccionRp::model()->count($criteria);
                if ($pr > 0) {
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
                $cr = GestionFactura::model()->count(array('condition' => "id_informacion=:match",'params' => array(':match' => $id)));
                if ($cr > 0) {
                    return TRUE;
                }
                break;
            case 7:
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id}"
                ));
                $pr = GestionEntrega::model()->count($criteria);
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

    public function getLastSolicitudCotizacion() {
        $criteria = new CDbCriteria(array(
            'limit' => 1,
            'order' => 'id DESC'
        ));
        $ent = GestionProforma::model()->find($criteria);
        return $ent->id;
    }

    public function getLastProforma() {
        $criteria = new CDbCriteria(array(
            'limit' => 1,
            'order' => 'id DESC'
        ));
        $ent = GestionProforma::model()->find($criteria);
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
					
					$d = Dealers::model()->find(array("condition"=>"id =".$m->concesionario_id));
                    //$imp.=$m->consecionario->nombre . ' -- ';
					if(!empty($d))
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
					$d = Dealers::model()->find(array("condition"=>"id =".$m->dealer_id));
                    //$imp.=$m->consecionario->nombre . ' -- ';
					if(!empty($d))
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

    public function traercotizaciones() {
        date_default_timezone_set("America/Bogota");
        $sql = 'SELECT * FROM atencion_detalle WHERE fecha_form >="2015-12-09" and encuestado = 0 and id_modelos is not null limit 100';
        $datosC = Yii::app()->db2->createCommand($sql)->queryAll();
        $cargo_id = Cargo::model()->find(array('condition' => 'codigo = "' . Constantes::CDG . '"'));
        $usuarios = Usuarios::model()->findAll(array('condition' => 'cargo_id =' . $cargo_id->id));

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

                    $cotizacion = new Cotizacionesnodeseadas();
                    $cotizacion->atencion_detalle_id = (int) $d['id_atencion'];

                    $cotizacion->usuario_id = $usuario_list[$posicion];
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
                    if ($cotizacion->save()) {
                        //echo $usuario_list[$posicion];
                        Yii::app()->db2
                                ->createCommand("UPDATE atencion_detalle SET encuestado=1,fechaencuesta='" . date("Y-m-d h:i:s") . "' WHERE id_atencion_detalle=:RListID")
                                ->bindValues(array(':RListID' => $d['id_atencion_detalle']))
                                ->execute();
                    }
                }
                $contactual++;
            }
        }
    }

    public function traernocompradores() {
        date_default_timezone_set("America/Bogota");

        $datosC = GestionDiaria::model()->findAll(array('condition' => 'desiste = 1 and encuestado=0'));




        $cargo_id = Cargo::model()->find(array('condition' => 'codigo = "' . Constantes::CDG . '"'));
        $usuarios = Usuarios::model()->findAll(array('condition' => 'cargo_id =' . $cargo_id->id));

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
                    //echo $usuario_list[$posicion].'<br>';
                    $cotizacion = new Nocompradores();
                    $cotizacion->gestiondiaria_id = (int) $d->id;
                    $cotizacion->usuario_id = $usuario_list[$posicion];
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
        }else{
            return 'NA';
        }
    }
    
    public function getTipoExo($id) {
        $dealers = GestionNuevaCotizacion::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->tipo;
        }else{
            return 'NA';
        }
    }
    
    public function getTipoExoInfo($id) {
        $dealers = GestionInformacion::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->tipo_ex;
        }else{
            return 'NA';
        }
    }
    public function getTipoExoPorcentaje($id) {
        $dealers = GestionInformacion::model()->findByPk($id);
        if (!is_null($dealers) && !empty($dealers)) {
            return $dealers->porcentaje_discapacidad;
        }else{
            return 'NA';
        }
    }
    
    public function getExonerado($id) {
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            "condition" => "id = {$id} AND tipo_form_web = 'exonerados'",
        ));
        $dealers = GestionInformacion::model()->count($criteria);
        return $dealers;
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
                    $datas .= '<option value="' . $value['id'].'"';
                    if($value['id'] == $id_grupo){
                        $datas .= ' selected >';
                    }else{
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
                
                break;

            default:
                break;
        }
        return $data_select;
    }

}
