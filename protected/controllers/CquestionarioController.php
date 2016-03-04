<?php

class CquestionarioController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/call';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('graficar','index','view','getencuestas','getUsuario',"traerRespuestas",'abrirMatriz'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('detallereporteusuario','reportes','create','update','admin','eliminar','search','adjunto','duplicar','reportesv','detallereporte'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($c)
	{
		$model=new Cquestionario;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cquestionario']))
		{
			$model->attributes=$_POST['Cquestionario'];
			$model->fecha = date("Y-m-d");
			if($model->save())
				{
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('Cquestionario/create/c/'.$model->ccampana_id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'idc'=>$c,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cquestionario']))
		{
			$model->attributes=$_POST['Cquestionario'];
			if($model->save()){
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('cquestionario/update/'.$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionEliminar($id)
	{
	
		$models = Cquestionario::model()->findByPk($id);

		$this->loadModel($id)->delete();
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('cquestionario/admin/'.$models->ccampana_id));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Cquestionario');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($id)
	{
		$rol = Yii::app()->user->getState('roles');
        $criteria = new CDbCriteria;
        $criteria->condition = "ccampana_id=$id";
        $criteria->order = 'id desc';
   


        // Count total records
        $pages = new CPagination(Cquestionario::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Cquestionario::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'idc'=>$id,
           )
        );
	}
	public function actionSearch($id)
	{
		$p = new CHtmlPurifier();
		if(!empty($_GET['Modelos']['Descripcion'])){
			$patronBusqueda =  $p->purify($_GET['Modelos']['Descripcion']);
			
			$posts = Cquestionario::model()->findAll(array('order' => 'id DESC', 'condition' => "descripcion LIKE :match OR nombre LIKE :match", 'params' => array(':match' =>"%$patronBusqueda%")));
		   
		   if(!empty($posts)){
				$pages = new CPagination(count($posts));
				$pages->pageSize = 10;
	
				$this->render('admin', array(
					'model' => $posts,
					'pages' => $pages,
					'busqueda' => $patronBusqueda,
					 'idc'=>$id,
					)
				);
		   }else{
				Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
				 $this->redirect(array('cquestionario/admin/'.$id));
		   }
		}else{
				Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
				 $this->redirect(array('cquestionario/admin/'.$id));
		   }
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cquestionario the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cquestionario::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cquestionario $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cquestionario-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionDuplicar($id,$c=null){
		$p = new CHtmlPurifier();
		$encuesta = Cquestionario::model()->findByPk($id);
		
		if(!empty($encuesta) && !empty($_POST)){
		
			$modelE = new Cquestionario();
			$modelE->descripcion = $encuesta->descripcion;
			$modelE->nombre = (!empty($_POST['campana_nombre']))?$p->purify($_POST['campana_nombre']):'Copia - '.$encuesta->nombre;
			$modelE->fechainicio = $encuesta->fechainicio;
			$modelE->fechafin = $encuesta->fechafin;
			$modelE->fecha = date('Y-m-d');
			$modelE->estado = 'ACTIVO';
			$modelE->ccampana_id =(int)$_POST['campana_item']; //$c;
			$modelE->guion = (!empty($encuesta->guion))?$encuesta->guion:'Ingrese el guión para el encuestador@';
			$modelE->cbasedatos_id = $encuesta->cbasedatos_id;
			
			if($modelE->save()){
				
				//obtener preguntas
				$preguntas = Cpregunta::model()->findAll(array('condition'=>'copcionpregunta_id is null and cquestionario_id = '.(int)$encuesta->id));
				if(!empty($preguntas)){

					foreach ($preguntas as $value) {
						$modelP = new Cpregunta();
						$modelP->descripcion = $value->descripcion;
						$modelP->fecha = date('Y-m-d');
						$modelP->estado = $value->estado;
						$modelP->ctipopregunta_id = $value->ctipopregunta_id;
						$modelP->cquestionario_id = $modelE->id;
						$modelP->tipocontenido = $value->tipocontenido;
						$modelP->orden = $value->orden;
						//$modelP->copcionpregunta_id = $value->copcionpregunta_id;
						if($modelP->save()){
							$op = Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$value->id));
							if(!empty($op)){
								foreach ($op as $op) {
									$modelOP = new Copcionpregunta();
									$modelOP->detalle = $op->detalle;
									$modelOP->valor = $op->valor;
									$modelOP->cpregunta_id = $modelP->id;
									$modelOP->save();
								}
							}
							//PREGUNTA MATRIZ VERIFICAR PARA REGISTRAR
							$pm = Cmatrizpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$value->id));
							if(!empty($pm)){
								foreach ($pm as $pm) {
									$modelM = new Cmatrizpregunta();
									$modelM->detalle = $pm->detalle;
									$modelM->valor =$pm->valor;
									$modelM->fecha = date('Y-m-d');
									$modelM->cpregunta_id = $modelP->id;
									$modelM->save();	
								}
							}
						}

					}
					
				}
				$this->redirect(array('admin','id'=>$modelE->ccampana_id));

		
			}
		}
		$this->render('duplicar',array('model'=>$encuesta));
	}
	public function actionReportesv(){
		$this->redirect(array('/cquestionario/reportes/c/'.$_GET['cbocampana'].'/r/'.$_GET['cboencuesta']));
		
	}
	public function actionReportes($c=null,$r=null){
		$model = null;
		$campana  = null;

		if(!empty($c))
			$campana = Ccampana::model()->findAll(array('condition'=>'id='.$c.' and estado = "ACTIVO"'));
		//else
			//$campana = Ccampana::model()->findAll(array('condition'=>'estado = "ACTIVO"'));
		
		if(!empty($r))
			$model = Cquestionario::model()->findAll(array("condition"=>'id='.$r.' and estado = "ACTIVO"'));
		/*else
			$model = Cquestionario::model()->findAll(array("condition"=>'estado = "ACTIVO"'));*/
		$this->render('reportes',array('model'=>$model,'campana'=>$campana));
	}  
	
	public function actionGetencuestas(){ 
		$p = new CHtmlPurifier();
		$valor = $p->purify($_POST['vl']);
		if(!empty($valor)){
			$model = Cquestionario::model()->findAll(array('condition'=>'ccampana_id = '.$valor));
			if(!empty($model)){
				$html = '<select id="cboencuesta" name="cboencuesta" class="form-control">';
				foreach($model as $m){
					$html .="<option value='$m->id'>$m->nombre</option>";
				}
				$html .= '</select>';
				echo $html;
			}
		}
	}
	public function actionDetallereporte($id){
		$model = Cquestionario::model()->findByPk((int)$id);
		$encuestados = Cencuestados::model()->findAll(array('condition'=>'cquestionario_id = '.(int)$model->id));
		$this->render('detalle',array('model'=>$model,'encuestados'=>$encuestados ));
	}
	public function actionDetallereporteusuario($u,$q){
		$model = Cquestionario::model()->find(array("condition"=>'id='.$q.' and estado = "ACTIVO"'));
		$qe = Cencuestadoscquestionario::model()->find(array('condition'=>'cquestionario_id='.$q.' and cencuestados_id='.$u));
		$this->render('detallereporteusuario',array('model'=>$model,'encuesta'=>$qe));
	}
	public function getUsuario($encuestado, $encuesta){
		$userD = Cencuestadoscquestionario::model()->find(array('condition'=>'cquestionario_id = '.$encuesta.' and cencuestados_id='.(int)$encuestado));
		if(!empty($userD)){
			$user = Usuarios::model()->findByPk($userD->usuarios_id);
			return $user;
		}
	}
	public function getEncuesta($encuestado, $encuesta){
		$userD = Cencuestadoscquestionario::model()->find(array('condition'=>'cquestionario_id = '.$encuesta.' and cencuestados_id='.(int)$encuestado));
		if(!empty($userD)){
			return $userD;
		}
	}
	public function actionGraficar(){
		$p = new CHtmlPurifier();
		$valor = $p->purify($_POST['vl']);
		if(!empty($valor)){
			$keyp = Cpregunta::model()->findByPk($valor);
			$opciones= Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$valor));
			$table = "";
			if(!empty($opciones)){
				
				foreach ($opciones as $keyo) {
					$numerorespuestas =0;

					$numerorespuestas = Cencuestadospreguntas::model()->count(array('condition'=>'pregunta_id ='.(int)$valor.' and copcionpregunta_id ='.$keyo->id));
					/*PARA MATRIZ*/
					if($keyp->ctipopregunta_id ==4){
						echo '<li style="padding:10px;width:100%;border:1px solid #ccc;margin:2px auto;">'.$cont.'.'.$conto.') &nbsp;<label style="width:250px;height:15px;font-weight:bold;font-size:13px;">'.$keyo->detalle.'</label><span style="margin:width:200px;auto auto auto 100px; padding:2px;">Respuestas: '.$numerorespuestas.'</span></li>';
					}else{
						$table .= $keyo->detalle.'-'.$numerorespuestas.'|';
					}

				}
			}
			echo $table;
		}
	}
	public function actionTraerRespuestas(){
		$p = new CHtmlPurifier();
		$valor = $p->purify($_POST['vl']);
		if(!empty($valor)){
			$keyp = Cpregunta::model()->findByPk($valor);
			$respuestas= Cencuestadospreguntas::model()->findAll(array('condition'=>'pregunta_id = '.(int)$valor));
			$table = "";
			if(!empty($respuestas)){

				foreach ($respuestas as $keyo) {
					$table .="<div style='width:100%;padding: 9px;margin:3px auto;border: 1px solid#ccc;font-weight: 600;'>$keyo->respuesta</div>";
				}
			echo $table;
			die();
			}
		}
		echo 0;
	}

	public function actionAbrirMatriz(){
		$p = new CHtmlPurifier();
		$valor = $p->purify($_POST['vl']);
		if(!empty($valor)){
			$keyp = Cpregunta::model()->findByPk($valor);
			$opciones= Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$keyp->id));
			$table = "";
			if(!empty($opciones)){
				foreach ($opciones as $keyo) {
					$table .='||'.$keyo->detalle.'-';
					$matriz= Cmatrizpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$keyp->id));
					if(!empty($matriz)){
						/*$table .='<table class="table"><tr>';
						foreach ($matriz as $key) {
							$table .='<th style="background:#CCC;text-align:center"><span >'.$key->detalle.' [ '.$key->valor.' ]</span></th>';
							//$table.='<li style="width:100%;padding:5px; margin:5px auto;"></li>';
						}
						$table .= '</tr>';*/
						//COMPROBAR SI HUBO RESPUESTA
						//$table .= '<tr>';
						foreach ($matriz as $key) {
							$RESP = 0;
							$RESP= Cencuestadospreguntas::model()->count(array('condition'=>'cmatrizpregunta_id = '.(int)$key->id.' and copcionpregunta_id='.$keyo->id));
							if($RESP >0){
								$table .= '<td style="font-size:13px;font-weight:100;text-align:center;">'.$RESP.'</td>';
							}
						}
						//$table .='</tr>';
						//$table .='</table>';
					}
				}
			}
		}
		echo $table;
	}

}
