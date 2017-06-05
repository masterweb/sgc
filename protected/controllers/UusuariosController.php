<?php

class UusuariosController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/call';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('admin', 'view', 'create', 'confirmar', 'update', 'eliminar', 'traerconsesionario', 'search', 'searchC', 'contactos', 'perfil', 'cambiar', 'celebraciones','getExcelContactos'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $cargados = Permiso::model()->findAll(array('condition' => "cargoId=:match", 'params' => array(':match' => $model->cargo_id)));
        $rol = Cargo::model()->find(array('order' => 'id DESC', 'condition' => "id=:match", 'params' => array(':match' => $model->cargo_id)));

       $cargados = Permiso::model()->findAll(array('condition' => "cargoId=:match", 'params' => array(':match' => $model->cargo_id)));
        /* if($rol->area->modulo->descripcion == "TOTAL"){
          $modulos = Modulo::model()->findAll(array('order' => 'descripcion DESC'));
          $accesos = Accesosistema::model()->findAll(array('order' => 'controlador ASC, modulo_id DESC', 'condition' => "estado=:match", 'params' => array(':match' =>"ACTIVO")));
          }else{ */

        $values = array();
        $modulosA = array();
        $results = Cargomodulos::model()->findAll(array("condition" => 'cargo_id =' . $model->cargo_id));
        if (!empty($results)) {

            foreach ($results as $r) {
                $values[] = $r->cargo_id;
                $modulosA[] = $r->modulo_id;
            }
        }

        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', $modulosA);
        $criteria->order = 'descripcion DESC';
        $modulos = Modulo::model()->findAll($criteria);

        $criteria = new CDbCriteria;
        $criteria->condition = "estado='ACTIVO'";
        $criteria->addInCondition('modulo_id', $modulosA);
        $criteria->order = 'controlador ASC, modulo_id DESC';
        $accesos = Accesosistema::model()->findAll($criteria);
        //}
        $this->render('view', array(
            'model' => $model,
            'cargados' => $cargados
            , 'modulos' => $modulos, "accesos" => $accesos,
        ));
    }

    public function actionConfirmar($id) {
        $model = $this->loadModel($id);
        $confirmado = Accesoregistro::model()->find(array('condition' => "usuarios_id=:match", 'params' => array(':match' => (int) $id)));
        $acceso = new Accesoregistro;

        if (isset($_POST['Accesoregistro'])) {
            $acceso = new Accesoregistro;
            $acceso->attributes = $_POST['Accesoregistro'];
            $motivo = "";
            if ($acceso->estado == "CONFIRMADO") {
                $acceso->descripcion = "El administrador ha confirmado el registro.";
            } else {
                $motivo = ", por el siguiente motivo: <b>" . $acceso->descripcion . '</b>';
            }
            $acceso->usuarios_id = $id;
            $acceso->administrador = (int) Yii::app()->user->id;
            $acceso->fecha = date("Y-m-d h:i:s");
            $pass = "";
            if ($acceso->save()) {
                $usuario = Usuarios::model()->find(array('condition' => "id=:match", 'params' => array(':match' => (int) $id)));
                if ($acceso->estado == "RECHAZADO") {
                    $usuario->estado = 'RECHAZADO';
                    $estad = "RECHAZADO";
                } else {
                    $usuario->estado = 'ACTIVO';
                    $usuario->fechaactivacion = date("Y-m-d h:i:s");
                    $estad = "ACEPTADO";
                    $pass = "Kia_" . date('s_i');
                    $usuario->password = md5($pass);
                }

                if ($usuario->update()) {
                    $url = "https://" . $_SERVER['HTTP_HOST'];
                    require_once 'email/mail_func.php';
                    $asunto = 'Kia Motors Ecuador SGC - Confirmaci&oacute;n de Cuenta';
                    $token = md5($model->correo . '--' . $model->id . '--' . $model->usuario);
                    $general = '<body style="margin: 10px;">
                            <div style="width:1; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;"><img src="images/header_mail.jpg"/></div>';
                    $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">Señor(a): <b>' . utf8_decode(utf8_encode($usuario->nombres)) . ' ' . utf8_decode(utf8_encode($usuario->apellido)) . '</b> su registro en el SGC ha sido ' . $estad . '  por parte del administrador ' . $motivo . '</div>';
                    if ($acceso->estado != "RECHAZADO") {
                        $general .= '<div style="width:600px;  margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">'
                                . '<br /><br />Recuerde que sus credenciales de acceso son las siguientes:</div>';
                        $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">'
                                . '<ul  style="text-align:left"><li>Usuario: ' . $usuario->usuario . '</li>';
                        $general .= '<li>Contrase&ntilde;a: ' . $pass . '</li></ul></div>';
                        $general .= '<br /><br /><div  style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto;text-align:left">La contraseña es temporal y la debe cambiar al ingresar a la plataforma. <br><br>Para acceder al sistema realice clic <a href="' . $url . Yii::app()->request->baseUrl . '/index.php/site/login">aqu&iacute;.</a>.</div>';
                        $general .= '<div  style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">'
                                . '</div>';
                        $general .= '<div  style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">'
                                . '<br /><br />Saludos cordiales,<br />SGC <br />Kia Motors Ecuador <br /></div>';
                        $general .= '<div  style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">'
                                . '<br /><br />Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</div>';
                        
                    }
                    $general.=' <div  style="width:600px;  margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left"><img src="images/footer_mail.jpg"/></div>
								</div>
							</body>';
                    $codigohtml = $general;
                    $tipo = 'informativo';
                    $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";
                    $email = $usuario->correo; //email administrador

                    if (sendEmailInfoD('servicioalcliente@kiamail.com.ec', html_entity_decode("Kia -  Sistema de Prospecci&oacute;n"), $email, html_entity_decode($asunto), $codigohtml, $tipo)) {
                        Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                        $eliminar = Usuarios::model()->findAll(array('condition' => "estado=:match", 'params' => array(':match' => "RECHAZADO")));
                        if (!empty($eliminar)) {
                            $eliminar = Usuarios::model()->deleteAll(array('condition' => "estado=:match", 'params' => array(':match' => "RECHAZADO")));
                        }
                    }
                }
                //$this->redirect(array('confirmar','id'=>$acceso->usuarios_id));
            }
        }
        //$modulos = Modulo::model()->findAll(array('order' => 'descripcion DESC'));
        //$accesos = Accesosistema::model()->findAll(array('order' => 'controlador ASC, modulo_id DESC', 'condition' => "estado=:match", 'params' => array(':match' =>"ACTIVO")));

        $cargados = Permiso::model()->findAll(array('condition' => "cargoId=:match", 'params' => array(':match' => $model->cargo_id)));
        /* if($rol->area->modulo->descripcion == "TOTAL"){
          $modulos = Modulo::model()->findAll(array('order' => 'descripcion DESC'));
          $accesos = Accesosistema::model()->findAll(array('order' => 'controlador ASC, modulo_id DESC', 'condition' => "estado=:match", 'params' => array(':match' =>"ACTIVO")));
          }else{ */

        $values = array();
        $modulosA = array();
        $results = Cargomodulos::model()->findAll(array("condition" => 'cargo_id =' . $model->cargo_id));
        if (!empty($results)) {

            foreach ($results as $r) {
                $values[] = $r->cargo_id;
                $modulosA[] = $r->modulo_id;
            }
        }

        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', $modulosA);
        $criteria->order = 'descripcion DESC';
        $modulos = Modulo::model()->findAll($criteria);

        $criteria = new CDbCriteria;
        $criteria->condition = "estado='ACTIVO'";
        $criteria->addInCondition('modulo_id', $modulosA);
        $criteria->order = 'controlador ASC, modulo_id DESC';
        $accesos = Accesosistema::model()->findAll($criteria);
        //}
        $this->render('confirmar', array(
            'model' => $model,
            'acceso' => $acceso,
            'cargados' => $cargados
            , 'modulos' => $modulos, "accesos" => $accesos,
        ));
    }

    public function actionEliminar($id) {

        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        date_default_timezone_set("America/Bogota");
        $model = new Usuarios;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Usuarios'])) {
            $model->attributes = $_POST['Usuarios'];
            $model->fecharegistro = date("Y-m-d h:i:s");
            $password = "Kia_" . date('s_i');
            $model->password = md5($password);
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('uusuarios/create'));
            }
        }
        $ciudades = Dealercities::model()->findAll();
        $area = Area::model()->findAll();
        $this->render('create', array(
            'model' => $model,
            'ciudades' => $ciudades
            , 'area' => $area
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        date_default_timezone_set("America/Bogota");
        $model = $this->loadModel($id);

        if (isset($_POST['Usuarios'])) {
            $model->attributes = $_POST['Usuarios'];
            $model->ultimaedicion = date("Y-m-d h:i:s");
			$model->concesionario_id = 0;
			/*VERIFICAMOS SI TIENE ASOCIADO CONCESIONARIOS*/
			$concesionarios = Grupoconcesionariousuario::model()->count(array('condition'=>'usuario_id='.$model->id));
			if($concesionarios >0){
				$concesionarios = Grupoconcesionariousuario::model()->deleteAll(array('condition'=>'usuario_id='.$model->id));
			}
			
			
			/*FIN*/
            if ($model->save()) {
				/*
				REGISTRAR TODOS LOS CONCESIONARIOS SELECCIONADOS EN LA TABLA DE GRUPOCONCESIONARIOUSUARIO
			*/
				$grupo_concesionarios = $_POST['Usuarios']['concesionario_id'];
				if(!empty($grupo_concesionarios)){
					for($i = 0 ; $i <= count($grupo_concesionarios) ; $i++){
						if(!empty($grupo_concesionarios[$i])){
							$gconcesionario = new Grupoconcesionariousuario();
							$gconcesionario->usuario_id = $model->id;
							$gconcesionario->concesionario_id = $grupo_concesionarios[$i];
							$gconcesionario->save();
						}
					}
				}
				
				
			/*
				FIN DEL REGISTRO DE CONCESIONARIOS
			*/
                Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('uusuarios/update/' . $model->id));
            }
        }
        $ciudades = Dealercities::model()->findAll();
        $area = Area::model()->findAll();
        $this->render('update', array(
            'model' => $model,
            'ciudades' => $ciudades
            , 'area' => $area
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Usuarios');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAdmin() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'estado !="RECHAZADO" and id !=' . (int) Yii::app()->user->id;
        $criteria->order = 'id desc';
        // Count total records
        $pages = new CPagination(Usuarios::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Usuarios::model()->findAll($criteria);

        $cargo = Cargo::model()->findAll();
        $concesionarios = Dealers::model()->findAll(array('order' => 'name ASC'));

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'apellidos' => '',
            'email' => '',
            'cargo' => $cargo,
            'concesionarios' => $concesionarios,
            'cargos' => 'A',
            'concesionarioss' => 'A',
                )
        );
    }

    public function actionContactos() {
       // $criteria = new CDbCriteria;
        //$criteria->condition = 'estado ="ACTIVO" and id !='.(int)Yii::app()->user->id;
       // $criteria->condition = 'estado ="ACTIVO" ';
      //  $criteria->order = 'id desc';
        // Count total records
      //  $pages = new CPagination(Usuarios::model()->count($criteria));

        // Set Page Limit
      //  $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
       // $pages->applyLimit($criteria);

        // Grab the records
       // $posts = Usuarios::model()->findAll($criteria);

        $cargo = Cargo::model()->findAll();
        $concesionarios = Dealers::model()->findAll(array('order' => 'name ASC'));

        // Render the view
        $this->render('contactos', array(
            'model' =>null,
            'pages' => null,
            'busqueda' => '',
            'apellidos' => '',
            'email' => '',
            'cargo' => $cargo,
            'concesionarios' => $concesionarios,
            'cargos' => 'A',
            'concesionarioss' => 'A',
                )
        );
    }

    public function actionCelebraciones() {
        $criteria = new CDbCriteria;
        $mes = date("m");
        $criteria->condition = ' MONTH( fechanacimiento ) =' . $mes . ' AND estado ="ACTIVO" and id !=' . (int) Yii::app()->user->id;
        $criteria->order = 'DAYOFYEAR( fechanacimiento ) < DAYOFYEAR( CURDATE( ) ) , DAYOFYEAR( fechanacimiento )';
        // Count total records
        $pages = new CPagination(Usuarios::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Usuarios::model()->findAll($criteria);

        $cargo = Cargo::model()->findAll();
        $concesionarios = Dealers::model()->findAll(array('order' => 'name ASC'));

        // Render the view
        $this->render('celebraciones', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'email' => '',
            'cargo' => $cargo,
            'concesionarios' => $concesionarios,
            'cargos' => 'A',
            'concesionarioss' => 'A',
                )
        );
    }

    public function actionSearch() {
        $p = new CHtmlPurifier();
        if (!empty($_GET['Usuarios']['nombres']) || !empty($_GET['Usuarios']['email']) || $_GET['Usuarios']['grupo_id'] !='--') {
            
            $nombres = '';
            $apellidos = '';
            $cargos = '';
            $concesionarioss = '';
            $email = '';
            $cargo_id = '';
            $grupo_id = '';
            $area_id = '';
            $patronBusqueda='';

            $criteria=new CDbCriteria;

            if(!empty($_GET['Usuarios']['nombres'])){
                $nombres = $p->purify($_GET['Usuarios']['nombres']);
                $criteria->condition = "nombres LIKE '%$nombres%'";
                //$criteria->condition = "nombres ='$nombres' ";
                /*$criteria->addCondition('nombres LIKE "%nombre"','OR');
                $criteria->addCondition('apellido LIKE :nombre');
                $criteria->params=array(
                    ':nombre'=>'%$nombres%',
                );*/
            }
            if(!empty($_GET['Usuarios']['apellidos']) && !empty($_GET['Usuarios']['nombres'])){
                $apellidos = $p->purify($_GET['Usuarios']['apellidos']);
                $criteria->addCondition("apellido LIKE '%$apellidos%'");
                //$criteria->condition = "nombres ='$nombres' ";
                /*$criteria->addCondition('nombres LIKE "%nombre"','OR');
                $criteria->addCondition('apellido LIKE :nombre');
                $criteria->params=array(
                    ':nombre'=>'%$nombres%',
                );*/
            }else  if(!empty($_GET['Usuarios']['apellidos'])){
                 $apellidos = $p->purify($_GET['Usuarios']['apellidos']);
                 $criteria->condition = "apellido LIKE '%$apellidos%'";
            }
            
            if(!empty($_GET['Usuarios']['email'])){
                $email = $p->purify($_GET['Usuarios']['email']);
                $criteria->addCondition ('correo = "'.$email.'"');
            }

            if(!empty($_GET['Usuarios']['cargo_id']) && $_GET['Usuarios']['cargo_id']>0){
                $cargo_id = $p->purify($_GET['Usuarios']['cargo_id']);
                $criteria->addCondition ('cargo_id = '.$cargo_id);
            }

            if(!empty($_GET['Usuarios']['grupo_id'])  && $_GET['Usuarios']['grupo_id']>0){
                $grupo_id = $p->purify($_GET['Usuarios']['grupo_id']);
                $criteria->addCondition ( 'grupo_id = '.$grupo_id);
            }

            /*if(!empty($_GET['Cargo']['area_id']) && $_GET['Cargo']['area_id']>0 ){
                $area_id = $p->purify($_GET['Cargo']['area_id']);
                $criteria->condition = 'area_id = '.$area_id;
            }*/

            $posts = Usuarios::model()->findAll($criteria);



            if (!empty($posts)) {
                $pages = new CPagination(count($posts));
                $pages->pageSize = 10;
                $cargo = Cargo::model()->findAll();
                $concesionarios = Dealers::model()->findAll(array('order' => 'name ASC'));
                $this->render('admin', array(
                    'model' => $posts,
                    'pages' => $pages,
                    'busqueda' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'cargo' => $cargo,
                    'concesionarios' => $concesionarios,
                    'cargos' => $cargos,
                    'concesionarioss' => $grupo_id,
                        )
                );
            }else {
                Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
                $this->redirect(array('uusuarios/admin/'));
            }
        } else {
            Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
            $this->redirect(array('uusuarios/admin/'));
        }
    }

    public function actionSearchC() {
        
    
        $p = new CHtmlPurifier();
       
        if (!empty($_GET['Usuarios']['nombres']) || !empty($_GET['Usuarios']['email']) || $_GET['Usuarios']['grupo'] !='--') {
            
           
            $nombres = '';
            $apellidos = '';
            $cargos = '';
            $concesionarioss = '';
            $email = '';
            $cargo_id = '';
            $grupo_id = '';
            $area_id = '';
            $patronBusqueda='';
            $ubicacion='';
            $area='';

            $criteria=new CDbCriteria;
            
            if(!empty($_GET['Usuarios']['nombres'])){
                $nombres = $p->purify($_GET['Usuarios']['nombres']);
                $criteria->condition = "nombres LIKE '%$nombres%'";
            }
            if(!empty($_GET['Usuarios']['apellidos']) && !empty($_GET['Usuarios']['nombres'])){
                $apellidos = $p->purify($_GET['Usuarios']['apellidos']);
                $criteria->addCondition("apellido LIKE '%$apellidos%'");
            }else  if(!empty($_GET['Usuarios']['apellidos'])){
             
                 $apellidos = $p->purify($_GET['Usuarios']['apellidos']);
                 $criteria->condition = "apellido LIKE '%$apellidos%'";
            }
            
            if(!empty($_GET['Usuarios']['email'])){
              
                $email = $p->purify($_GET['Usuarios']['email']);
                $criteria->addCondition ('correo = "'.$email.'"');
            }

            if(!empty($_GET['Usuarios']['cargo_id']) && $_GET['Usuarios']['cargo_id']>0 && $_GET['Usuarios']['cargo_id']!=999){
               
                $cargo_id = $p->purify($_GET['Usuarios']['cargo_id']);
                $criteria->addCondition ('cargo_id = '.$cargo_id);
            }
            else if ( $_GET['Usuarios']['cargo_id']==999){
                 $cargo_id = $p->purify($_GET['Usuarios']['cargo_id']);
            }

            if(!empty($_GET['Usuarios']['grupo'])  && $_GET['Usuarios']['grupo']>0 && $_GET['Usuarios']['grupo']!=999){
                   
                $grupo_id = $p->purify($_GET['Usuarios']['grupo']);
                $criteria->addCondition ( 'grupo_id = '.$grupo_id);
            }
            else if($_GET['Usuarios']['grupo']==999){
                 $grupo_id = $p->purify($_GET['Usuarios']['grupo']);
            }

            if(!empty($_GET['Usuarios']['concesionario'])  && $_GET['Usuarios']['concesionario']>0 && $_GET['Usuarios']['concesionario']!=1000){
              
                $concesionario_id = $p->purify($_GET['Usuarios']['concesionario']);
                $criteria ->addCondition ( 'dealers_id = '.$concesionario_id);
            }
            else if( $_GET['Usuarios']['concesionario']==1000){
                    $concesionario_id = $p->purify($_GET['Usuarios']['concesionario']);
            }
             if(!empty($_GET['Usuarios']['responsable'])  && $_GET['Usuarios']['responsable']>0 && $_GET['Usuarios']['responsable']!=10000){
               
                $responsable_id = $p->purify($_GET['Usuarios']['responsable']);
                $criteria->addCondition ( 'id = '.$responsable_id);
            }
            else if( $_GET['Usuarios']['responsable']==10000){

                $responsable_id = $p->purify($_GET['Usuarios']['responsable']);
            }

            if(!empty($_GET['Usuarios']['ubicacion'])  && $_GET['Usuarios']['ubicacion']>0){
                $ubicacion = $p->purify($_GET['Usuarios']['ubicacion']);

            }

            if(!empty($_GET['Cargo']['area_id'])  && $_GET['Cargo']['area_id']>0){
                $area = $p->purify($_GET['Cargo']['area_id']);

            }



           

                $criteria->addCondition ( 'estado ="ACTIVO" ');
                $criteria->order = 'id desc';

                $posts = Usuarios::model()->findAll($criteria);

            if (!empty($posts)) {
                  
              /* $pages = new CPagination(Usuarios::model()->count($criteria));
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);*/
                $cargo = Cargo::model()->findAll();
                $concesionarios = Dealers::model()->findAll(array('order' => 'name ASC'));
                $this->render('contactos', array(
                    'model' => $posts,
                    'pages' => null,//$pages,
                    'busqueda' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'cargo' => $cargo,
                    'concesionarios' => $concesionarios,
                    'cargos' => $cargos,
                    'concesionarioss' => $grupo_id,
                    'concesionario_selected' => $concesionario_id,
                    'responsable_selected' => $responsable_id,
                    'cargo_id' => $cargo_id,
                    'ubicacion_selected' => $ubicacion,
                    'area_selected' => $area
                    )
                );
            } 
            else {
                Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
                //$this->redirect(array('uusuarios/contactos/'));
                 $this->render('contactos', array(
                    'model' => null,
                    'pages' => null,
                    'busqueda' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'cargo' => $cargo,
                    'concesionarios' => $concesionarios,
                    'cargos' => $cargos,
                    'concesionarioss' => $grupo_id,
                    'concesionario_selected' => $concesionario_id,
                    'responsable_selected' => $responsable_id,
                    'cargo_id' => $cargo_id,
                    'ubicacion_selected' => $ubicacion,
                    'area_selected' => $area
                    )
                );
            }
        } else {
            Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
           // $this->redirect(array('uusuarios/contactos/'));
            $this->render('contactos', array(
                    'model' => null,
                    'pages' => null,
                    'busqueda' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'cargo' => $cargo,
                    'concesionarios' => $concesionarios,
                    'cargos' => $cargos,
                    'concesionarioss' => $grupo_id,
                    'concesionario_selected' => $concesionario_id,
                    'responsable_selected' => $responsable_id,
                    'cargo_id' => $cargo_id,
                    'ubicacion_selected' => $ubicacion,
                    'area_selected' => $area
                    )
                );
        }
    }


    public function actionGetExcelContactos() {
        
    
        $p = new CHtmlPurifier();
        date_default_timezone_set('America/Guayaquil');
       
        if (!empty($_GET['nombres']) || !empty($_GET['email']) || $_GET['grupo'] !='--') {
            
           
            $nombres = '';
            $apellidos = '';
            $cargos = '';
            $concesionarioss = '';
            $email = '';
            $cargo_id = '';
            $grupo_id = '';
            $area_id = '';
            $patronBusqueda='';

            $criteria=new CDbCriteria;
            
            if(!empty($_GET['nombres'])){
                $nombres = $p->purify($_GET['nombres']);
                $criteria->condition = "nombres LIKE '%$nombres%'";

              
            }
            if(!empty($_GET['apellidos']) && !empty($_GET['nombres'])){
                $apellidos = $p->purify($_GET['apellidos']);
                $criteria->addCondition("apellido LIKE '%$apellidos%'");
            }
            else  if(!empty($_GET['apellidos'])){
             
                 $apellidos = $p->purify($_GET['apellidos']);
                 $criteria->condition = "apellido LIKE '%$apellidos%'";

              
            }
            
            if(!empty($_GET['email'])){
              
                $email = $p->purify($_GET['email']);
                $criteria->addCondition ('correo = "'.$email.'"');

              
            }

           /* if(!empty($_GET['cargo']) && $_GET['cargo']>0){
               
                $cargo_id = $p->purify($_GET['cargo']);
                $criteria->addCondition ('cargo_id = '.$cargo_id);

              
            }*/


            if(!empty($_GET['cargo']) && $_GET['cargo']>0 && $_GET['cargo']!=999){
               
                $cargo_id = $p->purify($_GET['cargo']);
                $criteria->addCondition ('cargo_id = '.$cargo_id);
            }
            else if ( $_GET['cargo']==999){
                 $cargo_id = $p->purify($_GET['cargo']);
            }



           /* if(!empty($_GET['grupo'])  && $_GET['grupo']>0){
                   
                $grupo_id = $p->purify($_GET['grupo']);
                $criteria->addCondition ( 'grupo_id = '.$grupo_id);

              
            }*/

            if(!empty($_GET['grupo'])  && $_GET['grupo']>0 && $_GET['grupo']!=999){
                   
                $grupo_id = $p->purify($_GET['grupo']);
                $criteria->addCondition ( 'grupo_id = '.$grupo_id);
            }
            else if($_GET['grupo']==999){
                 $grupo_id = $p->purify($_GET['grupo']);
            }



           /* if(!empty($_GET['concesionario'])  && $_GET['concesionario']>0){
              
                $concesionario_id = $p->purify($_GET['concesionario']);
                $criteria ->addCondition ( 'dealers_id = '.$concesionario_id);

              
            }*/

             if(!empty($_GET['concesionario'])  && $_GET['concesionario']>0 && $_GET['concesionario']!=1000){
              
                $concesionario_id = $p->purify($_GET['concesionario']);
                $criteria ->addCondition ( 'dealers_id = '.$concesionario_id);
            }
            else if($_GET['concesionario']==1000){
                    $concesionario_id = $p->purify($_GET['concesionario']);
            }






         /*    if(!empty($_GET['responsable'])  && $_GET['responsable']>0){
               
                $responsable_id = $p->purify($_GET['responsable']);
                $criteria->addCondition ( 'id = '.$responsable_id);

            
            }*/


            if(!empty($_GET['responsable'])  && $_GET['responsable']>0 && $_GET['responsable']!=10000){
               
                $responsable_id = $p->purify($_GET['responsable']);
                $criteria->addCondition ( 'id = '.$responsable_id);
            }
            else if( $_GET['responsable']==10000){

                $responsable_id = $p->purify($_GET['responsable']);
            }



                $criteria->addCondition ( 'estado ="ACTIVO" ');
                $criteria->order = 'id desc';

                $posts = Usuarios::model()->findAll($criteria);

               

            if (!empty($posts)) {

                 



                 Yii::import('ext.phpexcel.XPHPExcel');
            $objPHPExcel = XPHPExcel::createPHPExcel();

                $objPHPExcel->getProperties()->setCreator("SGC Kia Ecuador")
                    ->setLastModifiedBy("SGC")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");

                    
                     $objPHPExcel->setActiveSheetIndex(0)
                    ->mergeCells('A1:J1');


                    $estiloTituloReporte = array(
                        'font' => array('name' => 'Tahoma','bold' => true,'italic' => false,'strike' => false,'size' => 11,'color' => array('rgb' => 'B6121A')),
                        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation' => 0,'wrap' => TRUE)
                    ); 



                    $estiloTituloColumnas = array(
                        'font' => array('name' => 'Arial','bold' => true,'size' => 9,'color' => array('rgb' => '333333')),
                        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'F1F1F1')),
                        'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '143860')),
                            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '143860')),
                            'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '143860')),
                            'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '143860'))
                        ),
                        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'wrap' => TRUE
                        )
                    );



                  


                         $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Contactos de Usuarios') // Titulo del reporte
                    ->setCellValue('A2', 'Área')
                    ->setCellValue('B2', 'Cargo')
                    ->setCellValue('C2', 'Nombres')
                    ->setCellValue('D2', 'Apellidos')
                    ->setCellValue('E2', 'Grupo')
                    ->setCellValue('F2', 'Concesionario')
                    ->setCellValue('G2', 'Teléfono')
                    ->setCellValue('H2', 'Extensión')
                    ->setCellValue('I2', 'Email')
                    ->setCellValue('J2', 'Celular');

                   $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($estiloTituloReporte);     
                   $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($estiloTituloColumnas);

                   $i = 3;
            foreach ($posts as $row) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i,  strtoupper($row->cargo->area->descripcion))
                        ->setCellValue('B' . $i, strtoupper($row->cargo->descripcion))
                        ->setCellValue('C' . $i, $row['nombres'])
                        ->setCellValue('D' . $i, $row['apellido'])
                        ->setCellValue('E' . $i, (!empty($row->grupo_id))?$row->grupo->nombre_grupo:'--')
                        ->setCellValue('F' . $i, ($row->concesionario_id>=1)?$this->traerConcesionariosGR($row->concesionario_id,1):$this->traerConcesionariosU($row->id,1))
                        ->setCellValue('G' . $i, $row['telefono'])
                        ->setCellValue('H' . $i, $row['extension'])
                        ->setCellValue('I' . $i, $row['correo'])
                        ->setCellValue('J' . $i, $row['celular'],PHPExcel_Cell_DataType::TYPE_STRING);
                       

                        $i++;
          }
                   


                   

                     $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);




                     $objPHPExcel->getActiveSheet()->setTitle('Contactos');
                      $objPHPExcel->setActiveSheetIndex(0);

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="ReporteContactos.xls"');
                header('Cache-Control: max-age=0');
                //      If/ you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                //      If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

              //  $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 

                $objWriter->save('php://output');

                Yii::app()->end();



                /*$html='';
                   foreach ($posts as $c) {
                        $html .="<option value='" . $c->id . "'>" . $c->nombres . "</option>";
                    }
                    echo ('data:'.$html);
                die();*/
              /* $pages = new CPagination(count($posts));
                $pages->pageSize = 10;
                $cargo = Cargo::model()->findAll();
                $concesionarios = Dealers::model()->findAll(array('order' => 'name ASC'));
                $this->render('contactos', array(
                    'model' => $posts,
                    'pages' => $pages,
                    'busqueda' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'cargo' => $cargo,
                    'concesionarios' => $concesionarios,
                    'cargos' => $cargos,
                    'concesionarioss' => $grupo_id,
                    'concesionario_selected' => $concesionario_id,
                    'responsable_selected' => $responsable_id,
                    'cargo_id' => $cargo_id
                    )
                );*/
            } 
            else {
                Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
                $this->redirect(array('uusuarios/contactos/'));
            }
        } else {
            Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
            $this->redirect(array('uusuarios/contactos/'));
        }


        $this->render('contactos', array(
                    'model' => $posts,
                    'pages' => $pages,
                    'busqueda' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'cargo' => $cargo,
                    'concesionarios' => $concesionarios,
                    'cargos' => $cargos,
                    'concesionarioss' => $grupo_id,
                    'concesionario_selected' => $concesionario_id,
                    'responsable_selected' => $responsable_id,
                    'cargo_id' => $cargo_id
                    )
                );
    }


    public function loadModel($id) {
        //die('id: '.$id);
        $model = Usuarios::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Usuarios $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'usuarios-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionTraerconsesionario() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);
        $concesionarios = Dealers::model()->findAll(array('order' => 'name ASC', 'condition' => "cityid=:match", 'params' => array(':match' => (int) $valor)));
        $html = "";
        if (!empty($concesionarios)) {
            $html .='<select name="Usuarios[dealers_id]" id="Usuarios_dealers_id" class="form-control">';
            $html .="<option>Seleccione >></option>";
            foreach ($concesionarios as $c) {
                $html .="<option value='" . $c->id . "'>" . $c->name . "</option>";
            }
            $html .="</select>";
            echo $html;
        } else {
            echo 0;
            die();
        }
    }

     public function actionPerfil() {
        $model = $this->loadModel((int) Yii::app()->user->id);
        $fotoAnterior = $model->foto;
        if (isset($_POST['Usuarios'])) {
            if (!empty($_POST["repetirpass"]) && md5($_POST["repetirpass"]) == $model->password) {
                date_default_timezone_set("America/Bogota");
                $model->attributes = $_POST['Usuarios'];
                $model->ultimaedicion = date("Y-m-d h:i:s");
                $uploadedFile = CUploadedFile::getInstance($model, 'foto');

                if (!empty($uploadedFile) && $uploadedFile != '') {

                    $rnd = rand(0, 9999);
                    $date = date("Ymdhis");
                    $extension = explode('.', $uploadedFile);
                    $paso = 0;
                    /*switch ($extension[1]) {
                        case "gif":
                            $paso = 1;
                            break;
                        case "jpg":
                            $paso = 1;
                            break;
                        case "jpeg":
                            $paso = 1;
                            break;
                        case "png":
                            $paso = 1;
                            break;
                    }
                    if ($paso == 0) {

                        Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  Solamente puedes subir im�genes con la extensi�n GIF, JPG, JPEG, PJPEG o PNG.</div>');
                        $this->redirect(array('uusuarios/perfil/'));
                        die();
                    }*/
					$paso = 1;
                    if (!empty($uploadedFile) && $paso == 1) {
                        $mime = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png');
                        # Buscamos si el archivo que subimos tiene el MIME type que permitimos en nuestra subida
                        if (!in_array($uploadedFile->type, $mime)) {
                            Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  Solamente puedes subir im�genes con la extensi�n GIF, JPG, JPEG, PJPEG o PNG.</div>');
                            $this->redirect(array('uusuarios/perfil/'));
                            die();
                        }
                        if ($uploadedFile->size > 5048576) {

                            Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  La imagen que ha ingresado tiene un peso mayor a 1MB, lo cual no esta permitido.</div>');
                            $this->redirect(array('uusuarios/perfil/'));
                            die();
                        }

                        $fileName = md5($rnd . $date) . '.' . $extension[1];
                        $model->foto = $fileName;

                        /*                         * *****SUBIR IMAGEN************ */
                        $uploadedFile->saveAs(Yii::app()->basePath . '/../upload/perfiles/' . $fileName);

                        /* OBTENER EL IMAGEN DE 100X100 */
                        $origen = Yii::app()->basePath . '/../upload/perfiles/' . $fileName;
                        $destino = Yii::app()->basePath . '/../upload/perfiles/thumb/' . $fileName;
                        if (copy($origen, $destino)) {
                            $path = Yii::app()->basePath . '/../upload/perfiles/thumb/' . $fileName;
                        }

                        $x = getimagesize($path);
                        $width = $x['0'];
                        $height = $x['1'];

                        $rs_width = 80;
                        $rs_height = 80;

                        switch ($x['mime']) {
                            case "image/gif":
                                $img = imagecreatefromgif($path);
                                break;
                            case "image/jpeg":
                                $img = imagecreatefromjpeg($path);
                                break;
                            case "image/png":
                                $img = imagecreatefrompng($path);
                                break;
                        }

                        $img_base = imagecreatetruecolor($rs_width, $rs_height);
                        imagecopyresized($img_base, $img, 0, 0, 0, 0, $rs_width, $rs_height, $width, $height);
                        $path_info = pathinfo($path);

                        switch ($path_info['extension']) {
                            case "gif":
                                imagegif($img_base, $path);
                                break;
                            case "jpg":
                                imagejpeg($img_base, $path);
                                break;
                            case "jpeg":
                                imagejpeg($img_base, $path);
                                break;
                            case "png":
                                imagepng($img_base, $path);
                                break;
                        }
                        /* FIN DE OBTENER EL LODO */
                        $path = Yii::app()->basePath . '/../upload/perfiles/' . $fileName;

                        $x = getimagesize($path);
                        $width = $x['0'];
                        $height = $x['1'];
                        if ($width > 800) {
                            $rs_width = $width / 2;
                            $rs_height = $height / 2;

                            switch ($x['mime']) {
                                case "image/gif":
                                    $img = imagecreatefromgif($path);
                                    break;
                                case "image/jpeg":
                                    $img = imagecreatefromjpeg($path);
                                    break;
                                case "image/png":
                                    $img = imagecreatefrompng($path);
                                    break;
                            }

                            $img_base = imagecreatetruecolor($rs_width, $rs_height);
                            imagecopyresized($img_base, $img, 0, 0, 0, 0, $rs_width, $rs_height, $width, $height);
                            $path_info = pathinfo($path);

                            switch ($path_info['extension']) {
                                case "gif":
                                    imagegif($img_base, $path);
                                    break;
                                case "jpg":
                                    imagejpeg($img_base, $path);
                                    break;
                                case "jpeg":
                                    imagejpeg($img_base, $path);
                                    break;
                                case "png":
                                    imagepng($img_base, $path);
                                    break;
                            }
                        }
                    }
                } else {
                    $model->foto = $fotoAnterior;
                }
                if(!empty($_POST['Usuarios']['firma'])){
                    $model->firma = $_POST['Usuarios']['firma'];
                }
                if ($model->update()) {
                    Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                    $this->redirect(array('uusuarios/perfil'));
                }
            } else {
                Yii::app()->user->setFlash('error', '<div class="alert alert-danger">
                    <strong>Error!</strong> La contrase&ntilde;a es incorrecta o no puede estar en blanco.
                </div>');
                $this->redirect(array('uusuarios/perfil/'));
            }
        }
        $this->render('perfil', array(
            'model' => $model,
        ));
    }

    public function actionCambiar() {

        $model = $this->loadModel((int) Yii::app()->user->id);

        if (!empty($user->ultimavisita)) {
            $this->redirect($this->createUrl('site/menu'));
        }
        if (!empty($_POST["password"])) {
            if ($_POST["password"] == $_POST["repetir_password"]) {
                $pass = ($_POST["password"]);
                $model->password = md5($_POST["password"]);
                $model->ultimavisita = date("Y-m-d");
                if ($model->update()) {
                    $url = "https://" . $_SERVER['HTTP_HOST'];
                    require_once 'email/mail_func.php';
                    $asunto = 'Kia Motors Ecuador SGC - Actualizaci&oacute;n de Contrase&ntilde;a';
                    $general = '<body style="margin: 10px;">
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;"><img src="images/header_mail.jpg"/></div>';
                    $general .= '<div style="width:600px;  margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">Señor(a): <b>' . utf8_decode(utf8_encode($model->nombres)) . ' ' . utf8_decode(utf8_encode($model->apellido)) . '</b> su contrase&ntilde;a ha sido actualizada exitosamente.</div>';
                    $general .= '<div style="width:600px;  margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">Recuerde que sus credenciales de acceso son las siguientes:</div>';
                    $general .= '<div style="width:600px;  margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left"><ul  style="text-align:left"><li>Usuario: ' . $model->usuario . '</li>';
                    $general .= '<li>Contrase&ntilde;a: ' . $pass . '</li></ul></div>';
                    $general .= '<div style="width:600px;  margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">Para acceder al sistema realice clic <a href="' . $url . Yii::app()->request->baseUrl . '/index.php/site/login">aqu&iacute;.</a></div>';
                    $general .= '<div  style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">'
                                . 'Saludos cordiales,<br />SGC <br />Kia Motors Ecuador <br /></div>';
                        $general .= '<div  style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left">'
                                . '<br /><br />Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</div>';
                    $general.=' <div style="width:600px;  margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto;text-align:left"><img src="images/footer_mail.jpg"/></div>
									</div>
								</body>';
                    $codigohtml = $general;
                    $tipo = 'informativo';
                    $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";
                    $email = $model->correo; //email administrador 

                    if (sendEmailInfoD('servicioalcliente@kiamail.com.ec', html_entity_decode("Kia -  Sistema de Prospecci&oacute;n"), $email, html_entity_decode($asunto), $codigohtml, $tipo)) {
                        Yii::app()->user->setFlash('success', '<div class="exitoRegistro" style="text-align:justify"><h1>Actualizaci&oacute;n de contrase&ntilde;a</h1><p>Estimad@ <b>' . $model->nombres . ' ' . $model->apellido . '</b> la actualizaci&oacute;n de contrase&ntilde;a ha sido realizada exitosamente.</div>');
                        $this->redirect(array("uusuarios/cambiar"));
                    }
                }
            }
        }



        $this->render('cambiar', array("model" => $model));
    }

}
