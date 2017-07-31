<?php
 
class AjaxCalls{

    public function AjaxGetModelos() {
        //FECHAS RENDER
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";

        $tipo_busqueda_por = isset($_POST["tipo_busqueda_por"]) ? $_POST["tipo_busqueda_por"] : "";
        $concesion_active = isset($_POST["concesion_active"]) ? $_POST["concesion_active"] : "";
        $resp_active = isset($_POST["resp_active"]) ? $_POST["resp_active"] : "";
        $GestionInformacionProvincias = isset($_POST["GestionInformacionProvincias"]) ? $_POST["GestionInformacionProvincias"] : "";
        $GestionInformacionGrupo = isset($_POST["GestionInformacionGrupo"]) ? $_POST["GestionInformacionGrupo"] : "";

        $modelosClass = new Modelos;
        $modelos_ma = $modelosClass->getModleosActivos($fecha1[0], $fecha1[1], $fecha2[0], $fecha2[1], null, $tipo_b, $tipo_busqueda_por, $concesion_active, $resp_active, $GestionInformacionProvincias, $GestionInformacionGrupo);
        return ''.$modelos_ma;
    }

     public function AjaxGetTipoBDC() {
        
        $dealer_id = isset($_POST["concesion_active"]) ? $_POST["concesion_active"] : "";
        $resposable = isset($_POST["resp_active"]) ? $_POST["resp_active"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";

        $tipo_busqueda_por = isset($_POST["tipo_busqueda_por"]) ? $_POST["tipo_busqueda_por"] : "";
        $GestionInformacionProvincias = isset($_POST["GestionInformacionProvincias"]) ? $_POST["GestionInformacionProvincias"] : "";
        $GestionInformacionGrupo = isset($_POST["GestionInformacionGrupo"]) ? $_POST["GestionInformacionGrupo"] : "";

        $extraWhere = '';
        if($tipo_busqueda_por == 'provincias'){
            if($GestionInformacionProvincias != ''){
                $extraWhere .= '';
            }
        }else{
            if($GestionInformacionGrupo != ''){
                $extraWhere .= '';
            }
        }

        if($dealer_id != ''){
            $extraWhere .= ' dealer_id = '.$dealer_id.' AND ';
        }
        if($resposable != ''){
            $extraWhere .= ' responsable = '.$resposable.' AND ';
        }

        //FECHAS RENDER
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        $con = Yii::app()->db;

        $sql_select = "SELECT distinct id FROM gestion_informacion WHERE bdc = 1 AND ".$extraWhere." (DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."')";           
        $request = $con->createCommand($sql_select);
        $request = $request->queryAll();

        foreach ($request as $value) {
            $id_info_BDC_GI .= $value['id'].', ';
        }
        $id_info_BDC_GI = rtrim($id_info_BDC_GI, ", ");

        $id_info_BDC_GI = " id_informacion IN (".$id_info_BDC_GI.") ";

        $sql_BDC_activos = "select distinct desiste from  gestion_diaria where".$id_info_BDC_GI;          
        $request_BDC_activos = $con->createCommand($sql_BDC_activos);
        $request_BDC_activos = $request_BDC_activos->queryAll();

        $BDC_activos = '<option value="">--Seleccione un estado BDC--</option>';
        $control_BDC_activos = 0;
        foreach ( $request_BDC_activos as $key_BDC_activos => $value_BDC_activos) {
            if($value_BDC_activos['desiste'] == 0){
                $BDC_activos .= '<option value="caducados">Caducados</option>';
            }else{
                if($control_BDC_activos == 0){
                    $BDC_activos .= '<option value="desiste">Desiste</option>';
                    $control_BDC_activos = 1;
                }
            }
        }

        return $BDC_activos;

    }

    public function AjaxGetExonerados() {
        $dealer_id = isset($_POST["concesion_active"]) ? $_POST["concesion_active"] : "";
        $resposable = isset($_POST["resp_active"]) ? $_POST["resp_active"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";

        $tipo_busqueda_por = isset($_POST["tipo_busqueda_por"]) ? $_POST["tipo_busqueda_por"] : "";
        $GestionInformacionProvincias = isset($_POST["GestionInformacionProvincias"]) ? $_POST["GestionInformacionProvincias"] : "";
        $GestionInformacionGrupo = isset($_POST["GestionInformacionGrupo"]) ? $_POST["GestionInformacionGrupo"] : "";

        $extraWhere = '';
        if($tipo_busqueda_por == 'provincias'){
            if($GestionInformacionProvincias != ''){
                $extraWhere .= '';
            }
        }else{
            if($GestionInformacionGrupo != ''){
                $extraWhere .= '';
            }
        }

        if($dealer_id != ''){
            $extraWhere .= ' dealer_id = '.$dealer_id.' AND ';
        }
        if($resposable != ''){
            $extraWhere .= ' responsable = '.$resposable.' AND ';
        }

        //FECHAS RENDER
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        $con = Yii::app()->db;

        $sql_select = "SELECT distinct tipo_ex FROM gestion_informacion WHERE tipo_ex IS NOT NULL AND ".$extraWhere." (DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."')";           
        $request = $con->createCommand($sql_select);
        $request = $request->queryAll();

        $data = '<option value="">--Seleccione un tipo--</option>';
        foreach ($request as $value) {
            if($value['nombre'] != 'TODOS'){
                $data .= '<option value="' . $value['tipo_ex'].'">'.$value['tipo_ex'];
                $data .= '</option>';
            }
        }

        return $data;
    }

    public function AjaxGetAsesores() {
        //die('enter get asesores:================');
        $dealer_id = isset($_POST["dealer_id"]) ? $_POST["dealer_id"] : "";
        $resposable = isset($_POST["responsable"]) ? $_POST["responsable"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";
        $tipo_grupo = isset($_POST["tipo_grupo"]) ? $_POST["tipo_grupo"] : "";
        $tipo = isset($_POST["tipo_search"]) ? $_POST["tipo_search"] : "";
        //die('tipo: '.$tipo);

        //FECHAS RENDER
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        //controlador de tipo de busqueda
        $extra_where = '';
        if($tipo_b == 'web'){
            $extra_where = " bdc = 1 AND ";
            //echo $extra_where;
        }else if($tipo_b == 'exonerados'){
            $extra_where = " tipo_ex IS NOT NULL AND ";
        }
        else if($tipo_b == 'usados'){
        }


        //GET asesores activos en rango de fechas
        $con_aa = Yii::app()->db;
        if($tipo_b == 'bdc' OR $tipo_b == 'exonerados'){
            $responsable = 'responsable_origen, responsable';
        }else{
            $responsable = 'responsable';
        }
        $sql_asesores_act = "SELECT distinct ".$responsable." FROM gestion_informacion WHERE ".$extra_where." (DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."')";           
        //sdie ('sql asesores actuales: '.$sql_asesores_act);
        //die();
        $request_aa = $con_aa->createCommand($sql_asesores_act);
        $request_aa = $request_aa->queryAll();


        if(!empty($request_aa)){
            $asesores_aa = '';
            $asesores_aa2 = array();
            foreach ($request_aa as $id_asesor) {
                if($id_asesor['responsable_origen']){
                    if($id_asesor['responsable_origen'] != ''){
                        if($id_asesor['responsable'] == ''){
                            $id_asesor['responsable'] = $id_asesor['responsable_origen'];
                        }
                        $asesores_aa .= $id_asesor['responsable_origen'].', ';
                        array_push($asesores_aa2, array($id_asesor['responsable'], $id_asesor['responsable_origen']));
                    }
                }else{
                    if($id_asesor['responsable'] != ''){
                        $asesores_aa .= $id_asesor['responsable'].', ';
                    }
                }        
            }
            $asesores_aa = rtrim($asesores_aa, ", ");
            //die('asesoresaa: '.$asesores_aa);
            //print_r($asesores_aa2);
            //FIN

            $cargo_id = (int) Yii::app()->user->getState('cargo_id');
            $con = Yii::app()->db;

            if($tipo_b == 'bdc'){
                $extra_where = " bdc = 1 AND ";
                $active_cargo = '70, 71, 72, 73, 75, 76, 77';
                //echo $extra_where;
            }else if($tipo_b == 'exonerados'){
                $extra_where = " tipo_ex IS NOT NULL AND ";
                $active_cargo = '70, 71, 72, 73, 75, 76, 77';
            }
            else if($tipo_b == 'usados'){
                $active_cargo = '76, 77';
            }elseif($tipo_b == 'general'){
                $active_cargo = '70, 71';
            }elseif($tipo_b == 'web'){
                $active_cargo = '85,86';
            }
            else{
                $active_cargo = '70, 71, 72, 73, 75, 76, 77';
            }
            if($tipo == 'tw'){
                $active_cargo = '89';
            }

            if( $cargo_id == 69 || 
                $cargo_id == 70 ||
                $cargo_id == 85 || 
                $cargo_id == 4 || 
                $cargo_id == 45 ||
                $cargo_id == 46 ||
                $cargo_id == 48 ||
                $cargo_id == 57 ||
                $cargo_id == 58 ||
                $cargo_id == 60 ||
                $cargo_id == 61 ||
                $cargo_id == 62 ||
                $cargo_id == 76||
                $cargo_id == 72){
                
                
                if($tipo_grupo == 1){
                    $sql = "SELECT u.* FROM usuarios u INNER JOIN grupoconcesionariousuario gr ON gr.usuario_id = u.id "
                            . "WHERE gr.concesionario_id = {$dealer_id} AND u.cargo_id IN (".$active_cargo.") AND u.id IN (".$asesores_aa.") ORDER BY u.nombres ASC";
                }
                if($tipo_grupo == 0){
                    $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (".$active_cargo.") AND id IN (".$asesores_aa.") ORDER BY nombres ASC";
                }
                //echo $sql; die();
                $request = $con->createCommand($sql);
                $request = $request->queryAll();

                if(empty($request)){
                    $data = '<option value="">--Seleccione Asesor--</option><option value="1000">Todos</option>';
                }else{
                    $data = '<option value="">--Seleccione Asesor--</option><option value="1000"';
                    if($resposable == 1000){
                        $data .= ' selected';
                    }
                    $data .= '>Todos</option>';
                }
                
                foreach ($request as $value) {
                    $id_for_name = $value['id'];
                    foreach ($asesores_aa2 as $value2) {
                        if(in_array($value['id'], $value2)){
                            if($value2[0] != ''){
                                $value['id'] = $value2[0];
                            }else{
                                echo 'Null';
                            }                            
                        }
                    }
                    $data .= '<option value="' . $value['id'].'" ';
                    if($resposable == $value['id']){
                        $data .= 'selected';
                    }
                    $data .= '>'.$this->getResponsableNombres($id_for_name);
                    $data .= '</option>';
                }

                return $data;
            }
        }else{
            $data = '<option value=""> No hay asesores activos en este rango de fechas</option>';
            return $data;
        }
    }
    public function getResponsableNombres($id) {
        //die($id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id}"
        ));
        $dealer = Usuarios::model()->find($criteria);
        return ucfirst($dealer->nombres) . ' ' . ucfirst($dealer->apellido);
    }

    public function AjaxGetDealers() {
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $grupo_id = isset($_POST["grupo_id"]) ? $_POST["grupo_id"] : "";
        //die('grupo id: '.$grupo_id);
        $active  = isset($_POST["dealer"]) ? $_POST["dealer"] : "";
        $tipo  = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";
        $con = Yii::app()->db;
        //FECHAS RENDER
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        //controlador de tipo de busqueda
        $extra_where = '';
        if($tipo_b == 'bdc'){
            $extra_where = " bdc = 1 AND ";
        }else if($tipo_b == 'exonerados'){
            $extra_where = " tipo_ex IS NOT NULL AND ";
        }else if($tipo_b == 'usados'){
            $extra_where = " marca_usado IS NOT NULL AND ";
        }elseif($tipo_b == 'general'){
            $extra_where = " tipo_ex IS NULL AND marca_usado IS NULL AND bdc = 0 AND ";   
        }

        $sql = "SELECT distinct dealer_id FROM gestion_informacion WHERE ".$extra_where." (DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."') ORDER BY dealer_id ASC";
        //die('sql 1: '.$sql);
        $request = $con->createCommand($sql);
        $request = $request->queryAll();
        foreach ($request as $id_concesionario) {
            $concesionario .= $id_concesionario['dealer_id'].', ';
        }
        $concesionario = rtrim($concesionario, ", ");
        
        if(!empty($concesionario)){
            $concesionario = " dealer_id IN (".$concesionario.") ";
        }
        //die('concesionario'.$concesionario);

        $join = '';
        if($tipo == 'p'){
            $where = "provincia = {$grupo_id} AND ";
            if($grupo_id == 1000){
                $where = "";
            }
        }else{
            if($cargo_id == 70){
                $join = ' LEFT JOIN grupoconcesionariousuario as gc ON gc.concesionario_id = dealer_id ';
                $where = " ";
                $concesionario = $this->GetUserDealers();
            }
            if($grupo_id == 1000){
                $where = "";
            }else{
                $where = "id_grupo = {$grupo_id} AND ";
            }
        }
        $sql = "SELECT distinct nombre, dealer_id FROM gr_concesionarios ".$join." WHERE ".$where.$concesionario." ORDER BY nombre ASC";
        //die ('sql 2:'.$sql);
        $request = $con->createCommand($sql);
        $request = $request->queryAll();
        
        $data = '<option value="">--Seleccione Concesionario--</option>';
        if(count($request) > 1){ # OPCION TODOS SELECCIONADO COLOCAR SELECTED AL OPTION
            $data .= '<option value="1000" ';
            if($active == 1000){
                 $data .= 'selected';
            }
            $data .= '>Todos</option>';
            
        }
        
        foreach ($request as $value) {
            if($value['nombre'] != 'TODOS'){
                $data .= '<option value="' . $value['dealer_id'].'" ';
                    if($active == $value['dealer_id']){
                        $data .= 'selected';
                    }
                $data .= '>'.$value['nombre'];
                $data .= '</option>';
            }
        }
        

        return $data;
    }
    public function GetUserDealers() {
        $user_id = (int) Yii::app()->user->getState('id');
        $con = Yii::app()->db;

        $sql = "SELECT * FROM grupoconcesionariousuario WHERE usuario_id = ".$user_id." ORDER BY concesionario_id ASC";
        $request = $con->createCommand($sql);
        $request = $request->queryAll();

        foreach ($request as $id_concesionario) {
            $concesionario .= $id_concesionario['concesionario_id'].', ';
        }
        $concesionario = rtrim($concesionario, ", ");
        
        if(!empty($concesionario)){
            $concesionario = " dealer_id IN (".$concesionario.") ";
        }

        return $concesionario;
    }

    public function AjaxGetProvincias() {
        //FECHAS RENDER
        $active  = isset($_POST["active"]) ? $_POST["active"] : "";
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";
        $tipo = isset($_POST["tipo_search"]) ? $_POST["tipo_search"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        //GET asesores activos en rango de fechas

        //controlador de tipo de busqueda
        $extra_where = '';
        //echo $tipo_b;
        if($tipo_b == 'bdc'){
            $extra_where = " bdc = 1 AND ";
        }else if($tipo_b == 'exonerados'){
            $extra_where = " tipo_ex IS NOT NULL AND ";
        }else if($tipo_b == 'usados'){
            $extra_where = " marca_usado IS NOT NULL AND ";
        }elseif($tipo_b == 'general'){
            $extra_where = " tipo_form_web IS NULL AND bdc = 0 AND ";
        }
        // SACAR PROVINCIAS SOLO DEL GRUPO ASIAUTO
        if($tipo == 'tw'){
            $pr = GrConcesionarios::model()->findAll(array('condition' => "id_grupo = 2"));
            $prov = '';
            foreach ($pr as $value) {
                $prov.= $value['provincia'].', ';
            }
            $prov = rtrim($prov, ", ");
            $extra_where = " provincia_conc IN ({$prov}) AND ";
        }

        $con = Yii::app()->db;
        $sql = "SELECT distinct provincia_conc FROM gestion_informacion WHERE ".$extra_where." (DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."')";           
        //echo $sql;
        $request = $con->createCommand($sql);
        $request = $request->queryAll();

        if(!empty($request)){
            $provincias = '';
            foreach ($request as $id_asesor) {
                $provincias.= $id_asesor['provincia_conc'].', ';
            }
            $provincias = rtrim($provincias, ", ");
            //FIN

            $cargo_id = (int) Yii::app()->user->getState('cargo_id');
            $con = Yii::app()->db;

            if( $cargo_id == 69 || 
                $cargo_id == 70 || 
                $cargo_id == 4 || 
                $cargo_id == 45 ||
                $cargo_id == 46 ||
                $cargo_id == 48 ||
                $cargo_id == 57 ||
                $cargo_id == 58 ||
                $cargo_id == 60 ||
                $cargo_id == 61 ||
                $cargo_id == 62){
                $sql = "SELECT * FROM provincias WHERE id_provincia IN (".$provincias.") ORDER BY nombre ASC";
                //echo $sql;
                $request = $con->createCommand($sql);
                $request = $request->queryAll();

                if(empty($request)){
                    $data = '<option value=""> No hay Provincias activas en este rango de fechas</option>';
                }else{
                    $data = '<option value="">--Seleccione Provincia--</option><option value="1000">Todas</option>'; 
                }
                
                foreach ($request as $value) {
                    $data .= '<option value="' . $value['id_provincia'].'" ';
                    if($active == $value['id_provincia']){
                        $data .= 'selected';
                    }
                    $data .= '>'.$value['nombre'];
                    $data .= '</option>';
                }

                echo $data;
            }
        }else{
            $data = '<option value=""> No hay Provincias activas en este rango de fechas</option>';
            echo $data;
        }
    }

    public function AjaxGetGrupo() {
        //FECHAS RENDER
        $active  = isset($_POST["active"]) ? $_POST["active"] : "";
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $tipo = isset($_POST["tipo_search"]) ? $_POST["tipo_search"] : "";
        $fecha2 = explode(" - ", $fecha2);
        //die('tipo: '.$tipo);


        //GET concesionarios activos en rango de fechas

        //controlador de tipo de busqueda
        $extra_where = '';
        //echo $tipo_b;
        if($tipo_b == 'bdc'){
            $extra_where = " bdc = 1 AND dealer_id > 0 AND ";
        }else if($tipo_b == 'exonerados'){
            $extra_where = " tipo_ex IS NOT NULL AND dealer_id > 0 AND ";
        }else if($tipo_b == 'usados'){
            $extra_where = " marca_usado IS NOT NULL AND dealer_id > 0 AND ";
        }elseif($tipo_b == 'general'){
            $extra_where = " tipo_form_web IS NULL AND bdc = 0 AND dealer_id > 0 AND ";
        }

        $con = Yii::app()->db;
        $sql = "SELECT distinct dealer_id FROM gestion_informacion WHERE".$extra_where." (DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."')";           
        //echo $sql;
        $request = $con->createCommand($sql);
        $request = $request->queryAll();

        if(!empty($request)){
            $grupos = '';
            foreach ($request as $grupo) {
                $grupos .= $grupo['dealer_id'].', ';
            }
            $grupos = rtrim($grupos, ", ");

            //GET grupos activos en rango de fechas
            $con = Yii::app()->db;
            $sql = "SELECT distinct id_grupo FROM gr_concesionarios WHERE dealer_id IN (".$grupos.") ORDER BY nombre ASC";
            echo $sql;           
            $request2 = $con->createCommand($sql);
            $request2 = $request2->queryAll();

            $grupos = '';
            foreach ($request2 as $grupo) {
                $grupos .= $grupo['id_grupo'].', ';
            }
            $grupos = rtrim($grupos, ", ");
        }
        
        if(!empty($request2)){
            $cargo_id = (int) Yii::app()->user->getState('cargo_id');
            $con = Yii::app()->db;

            if( $cargo_id == 69 || 
                $cargo_id == 70 || 
                $cargo_id == 4 || 
                $cargo_id == 45 ||
                $cargo_id == 46 ||
                $cargo_id == 48 ||
                $cargo_id == 57 ||
                $cargo_id == 58 ||
                $cargo_id == 60 ||
                $cargo_id == 61 ||
                $cargo_id == 62){
                if($tipo == 'tw'){
                    $sql = "SELECT * FROM gr_grupo WHERE id  = 2 ORDER BY nombre_grupo ASC";
                }else{
                    $sql = "SELECT * FROM gr_grupo WHERE id IN (".$grupos.") ORDER BY nombre_grupo ASC";
                }
                
            
                $request = $con->createCommand($sql);
                $request = $request->queryAll();

                if(empty($request2)){
                    $data = '<option value=""> No hay grupos activos en este rango de fechas</option>';
                }else{
                    $data = '<option value="">--Seleccione Grupo--</option><option value="1000">Todos</option>'; 
                }
                
                foreach ($request as $value) {
                    $data .= '<option value="' . $value['id'].'" ';
                    if($active == $value['id']){
                        $data .= 'selected';
                    }
                    $data .= '>'. $value['nombre_grupo'];
                    $data .= '</option>';
                }

                echo $data;
            }
        }else{
            $data = '<option value=""> No hay grupo activos en este rango de fechas</option>';
            echo $data;
        }
    }

    public function AjaxGetExcel() {
        $data = isset($_POST["excel"]) ? $_POST["excel"] : "";

        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();

        $objPHPExcel->getProperties()->setCreator("SGC Kia Ecuador")
                ->setLastModifiedBy("SGC")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Embudo")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Embudo");

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B14:C14");
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("D14:E14");
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("F14:G14");

        foreach ($data as $key_col => $columna) {
            if($key_col == 'columna_0'){
                $columna_print = 'A';
            }else if($key_col == 'columna_1'){
                $columna_print = 'B';
            }else if($key_col == 'columna_2'){
                $columna_print = 'C';
            }else if($key_col == 'columna_3'){
                $columna_print = 'D';
            }else if($key_col == 'columna_4'){
                $columna_print = 'E';
            }else if($key_col == 'columna_5'){
                $columna_print = 'F';
            }else if($key_col == 'columna_6'){
                $columna_print = 'G';
            }

            foreach ($columna as $key_fil => $fila) {
                if($fila == 'undefined' || $fila == '&nbsp;'){
                    $val_print = '';
                }else{
                    $val_print = $fila;
                }
                $val_print = strip_tags($val_print);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columna_print.($key_fil + 1), $val_print);
            }
        }

        //ESTILOS EXCEL
        $headers_verde = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 12,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '5cb85c')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            )
        );

        $headers_amarillo = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 12,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'f0ad4e')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            )
        );

        $headers_gris = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 12,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '848485')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            )
        );

        $subtitulo = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 9,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '7E8083')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle('B1:B1')->applyFromArray($headers_verde);
        $objPHPExcel->getActiveSheet()->getStyle('B14:C14')->applyFromArray($headers_verde);
        $objPHPExcel->getActiveSheet()->getStyle('C1:C1')->applyFromArray($headers_amarillo);
        $objPHPExcel->getActiveSheet()->getStyle('D14:E14')->applyFromArray($headers_amarillo);
        $objPHPExcel->getActiveSheet()->getStyle('D1:E1')->applyFromArray($headers_gris);
        $objPHPExcel->getActiveSheet()->getStyle('F14:G14')->applyFromArray($headers_gris); 
        $objPHPExcel->getActiveSheet()->getStyle('B15:G15')->applyFromArray($subtitulo);
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 2);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Embudo.xls');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
        header('Cache-Control: cache, must-revalidate'); 
        header('Pragma: public'); 

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}