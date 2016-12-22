<?php

class FabricadeCreditoController extends Controller {

    public function actionGridViewCredito($id = NULL) {
        
        //$data=array("id"=>"1","nombre"=>"prueba","cedula"=>"1757417546","celular"=>"0984430114","modelo"=>"prueba","consecionario"=>"kia","fecha"=>"2016-12-08");
        //$model=array("1","prueba","1757417546","0984430114","prueba","kia","2016-12-08");

        $criteria = new CDbCriteria;
        $criteria->group = "t1.id";
        $criteria->select = "DISTINCT t1.id,
							t1.nombres,
							t1.apellidos,
						    t1.cedula,
						    t1.ruc,
						    t1.email,
						    t1.celular,
						    t1.dealer_id,
						    t2.fecha,
						    t2.modelo,
						    t3.name
						    ";
        $criteria->alias = 't1';
        $criteria->join = " INNER JOIN gestion_solicitud_credito t2 ON t1.id=t2.id_informacion";
        $criteria->join .=" INNER JOIN dealers t3 ON t1.dealer_id=t3.id";
        $criteria->join .=" INNER JOIN gestion_status_solicitud t4 ON t1.id=t4.id_informacion";
        $criteria->condition = "t4.status=1";
        $criteria->order = "t2.id_informacion desc";
        //$data=GestionInformacion::model()->findAll($criteria);
        //print_r("<pre>"); print_r($criteria);die();
        // Count total records
//        echo '<pre>';
//        print_r($criteria);
//        echo '</pre>';
        $pages = new CPagination(GestionInformacion::model()->count($criteria));
        // Set Page Limit
        $pages->pageSize = 10;
        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);
        $data = GestionInformacion::model()->findAll($criteria);

        $gconcesionarios = Dealers::model()->findAll(array('order' => 'name ASC'));
        $this->render('gridViewFabrica', array(
            'data' => $data, 'pages' => $pages, 'gconcesionarios' => $gconcesionarios
        ));
    }

    public function actionDetalle($id = NULL) {

        $sql = "SELECT DISTINCT
				t1.id as id_informacion,
				t1.nombres,
				t1.apellidos,
			    t1.cedula,
			    t1.ruc,
			    t1.email,
			    t1.celular,
			    t1.dealer_id,
			    t2.*,
			    t3.name as NombreConcesionario
			FROM
				gestion_informacion t1
			INNER JOIN 
				gestion_solicitud_credito t2
			ON
				t1.id=t2.id_informacion 
			INNER JOIN
				dealers t3
			ON
				t1.dealer_id=t3.id
			INNER JOIN
				gestion_status_solicitud t4
			ON
				t1.id=t4.id_informacion
			WHERE 
				t2.id_informacion=" . $id;

        $datos = Yii::app()->db->createCommand($sql)->queryAll();
        /* print_r("<pre>");
          die(print_r($datos)); */
        $this->render('detalleFabricaCredito', array(
            'datos' => $datos,
        ));
    }

    public function actionGridViewCreditoFiltro() {
        $fecha = $_GET["FabricadeCredito_fecha"];
        $status = $_GET["FabricadeCredito_status"];
        $grupo = $_GET["FabricadeCredito_gconcesionario"];
        $concesionario = $_GET["FabricadeCredito_concesionario"];
        $asesor = $_GET["FabricadeCredito_asesor"];
        $fecha = explode('-', $fecha);
        $fechaIni = $fecha[0];
        $fechaIni = str_replace("/", '-', $fechaIni);
        $fechaFin = $fecha[1];
        $fechaFin = str_replace("/", '-', $fechaFin);

        //FILTROS
        //solo fecha
        if ($fecha != "" and $status == 0 and $concesionario == 0) {
            $where = "t4.status=1 AND t2.fecha>='" . $fechaIni . "' AND t2.fecha<='" . $fechaFin . "'";
        }
        //fecha y estatus
        if ($fecha != "" and $status != 0 and $concesionario == 0) {
            $where = "t4.status=" . $status . " AND t2.fecha>='" . $fechaIni . "' AND t2.fecha<='" . $fechaFin . "'";
        }
        //fecha y grupo
        /* if($fecha!="" and $status==0 and $grupo!=0 and $concesionario==0){
          $where="t4.status=1 AND t2.fecha>='".$fechaIni."' AND t2.fecha<='".$fechaFin."' AND ";
          }
          //fecha, status y grupo
          if($fecha!="" and $status!=0 and $grupo!=0 and $concesionario==0){
          //$where="t4.status=".$status." AND t2.fecha>='".$fechaIni."' AND t2.fecha<='".$fechaFin."'";
          }
          // fecha, grupo y concesionario
          if($fecha!="" and $status==0 and $grupo!=0 and $concesionario!=0){
          //$where="t4.status=1 AND t2.fecha>='".$fechaIni."' AND t2.fecha<='".$fechaFin."'";
          } */
        //fecha,status, grupo y concesionario
        if ($fecha != "" and $status != 0 and $concesionario != 0) {
            $where = "t4.status=" . $status . " AND t2.fecha>='" . $fechaIni . "' AND t2.fecha<='" . $fechaFin . "' AND dealer_id=" . $concesionario;
        }
        if ($fecha != "" and $status == 0 and $concesionario != 0) {
            $where = "t4.status=1 AND t2.fecha>='" . $fechaIni . "' AND t2.fecha<='" . $fechaFin . "' AND dealer_id=" . $concesionario;
        }

        //FIN FILTROS

        $criteria = new CDbCriteria;
        $criteria->group = "t1.id";
        $criteria->select = "DISTINCT t1.id,
							t1.nombres,
							t1.apellidos,
						    t1.cedula,
						    t1.ruc,
						    t1.email,
						    t1.celular,
						    t1.dealer_id,
						    t2.fecha,
						    t2.modelo
						    ";
        $criteria->alias = 't1';
        $criteria->join = " INNER JOIN gestion_solicitud_credito t2 ON t1.id=t2.id_informacion";
        //$criteria->join .=" INNER JOIN dealers t3 ON t1.dealer_id=t3.id";
        $criteria->join .=" INNER JOIN gestion_status_solicitud t4 ON t1.id=t4.id_informacion";
        $criteria->condition = $where;
        $criteria->order = "t2.id_informacion desc";
        //$data=GestionInformacion::model()->findAll($criteria);
        // Count total records
        $pages = new CPagination(GestionInformacion::model()->count($criteria));
        // Set Page Limit
        $pages->pageSize = 10;
        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);
        $data = GestionInformacion::model()->findAll($criteria);

        $this->render('gridViewFabrica', array(
            'data' => $data, 'pages' => $pages,
        ));
    }

}
