<?php
require_once 'Conexion.php';
require_once 'ConsultasBase.php';

class Formularios {

    private $_conexion;

    public function __construct() {
        $objConexion = new Conexion();
        $this->_conexion = $objConexion->conectar();
    }

    private function _pedirInfoInterfaz() {
        $objConsultaBase = new ConsultasBase();
        return $objConsultaBase;
    }

    public function ingresosGeneral($tabla, $camposValores) {
        $longitud = count($camposValores);
        $x = 0;
        $campos = '';
        $valores = '';
        foreach ($camposValores as $camposDb => $camposIngreso) {
            $campos.=$camposDb;
            $valores.=$camposIngreso;
            if ($x != $longitud - 1) {
                $campos.=', ';
                $valores.=', ';
            }
            $x++;
        }

        $query = "INSERT INTO " . $tabla . ' ( ' . $campos . ' ) ' .
                ' VALUES ( ' . $valores . ' )';
        //die ('sql:  '.$query);
        $result = $this->_conexion->query($query);
        return $result;
    }

    public function ingresosGeneralAdoDb($tabla, $camposValores) {
        require_once 'adodb/adodb.inc.php';
        $host = '10.178.13.216';
        $user = 'adminkia_b4s3k1';
        $pass = 'CTVLBsUZoGx';
        $dbname = 'adminkia_b4s3k1';

        $conn1 = &ADONewConnection('mysql');
        $conn1->PConnect($host, $user, $pass, $dbname);
        $longitud = count($camposValores);
        $x = 0;

        foreach ($camposValores as $camposDb => $camposIngreso) {
            $campos.=$camposDb;
            $valores.=$camposIngreso;
            if ($x != $longitud - 1) {
                $campos.=', ';
                $valores.=', ';
            }
            $x++;
        }

        $query = "INSERT INTO " . $tabla . ' ( ' . $campos . ' ) ' .
                ' VALUES ( ' . $valores . ' )';
        //die ('sql:  '.$query);
        $result =  $conn1->Execute($sql);
        if ($result == false) {
            return false;
        } else {
            return true;
        }
    }

    public function getLastQuery() {
        return mysqli_insert_id($this->_conexion);
    }

    public function dameCiudades() {

        $objInfo = $this->_pedirInfoInterfaz();
        $select = array(
            'cityid',
            'name'
        );

        $condiciones = 'cityid !=-1 and cityid > 3 and cityid < 23';
        $orden = 'name';
        $info = $objInfo->pedirInfo('dealercities', $select, $condiciones, $orden, '');
        $vecInfo1 = explode('<#>', $info);
        $vecInfo2 = explode('<|>', $vecInfo1[0]);
        $aux1 = 0;
        $aux2 = 1;
        for ($x = 0; $x < $vecInfo1[1]; $x++) {
            echo '<option value="' . $vecInfo2[$aux1] . '">' . $vecInfo2[$aux2] . '</option>';
            $aux1+=2;
            $aux2+=2;
        }

        //echo '</select>';
    }

    public function dameModelos() {

        $objInfo = $this->_pedirInfoInterfaz();

        $select = array(
            'id_modelos',
            'nombre_modelo'
        );
        $condiciones = 'id_modelos !=-1';
        $orden = 'nombre_modelo';
        $info = $objInfo->pedirInfo('modelos', $select, $condiciones, $orden, '');

        $vecInfo1 = explode('<#>', $info);
        $vecInfo2 = explode('<|>', $vecInfo1[0]);

        $aux1 = 0;
        $aux2 = 1;
        for ($x = 0; $x < $vecInfo1[1]; $x++) {
            echo '<option value="' . $vecInfo2[$aux1] . '">' . $vecInfo2[$aux2] . '</option>';
            $aux1+=2;
            $aux2+=2;
        }

        //echo '</select>';
    }

    public function dameModelosSC() {
        $objInfo = $this->_pedirInfoInterfaz();

        $select = array(
            'id_modelos',
            'nombre_modelo'
        );

        $condiciones = 'id_modelos !=-1';
        $orden = 'orden';
        $info = $objInfo->pedirInfo('modelos', $select, $condiciones, $orden, '');

        $vecInfo1 = explode('<#>', $info);
        $vecInfo2 = explode('<|>', $vecInfo1[0]);

        return $vecInfo2;
    }

    public function dameProvincias() {
        $objInfo = $this->_pedirInfoInterfaz();

        $select = array(
            'id_provincia',
            'nombre_provincia'
        );

        $condiciones = 'id_provincia !=-1';
        $orden = 'nombre_provincia';
        $info = $objInfo->pedirInfo('provincia', $select, $condiciones, $orden, '');


        $vecInfo1 = explode('<#>', $info);
        $vecInfo2 = explode('<|>', $vecInfo1[0]);

        $aux1 = 0;
        $aux2 = 1;
        for ($x = 0; $x < $vecInfo1[1]; $x++) {
            echo '<option value="' . $vecInfo2[$aux1] . '">' . $vecInfo2[$aux2] . '</option>';
            $aux1+=2;
            $aux2+=2;
        }

        //echo '</select>';
    }

    public function dameEmailsConcesionariosTipoAtencion($idConcesionario, $idTipoAtencion) {
        $objInfo = $this->_pedirInfoInterfaz();

        $select = array(
            'para_email',
            'cc_email',
            'cco_email'
        );

        $condiciones = "dealersid = $idConcesionario AND id_tipo_atencion = $idTipoAtencion";
        //die('select: '.$select + $condiciones);
        $info = $objInfo->pedirInfo('dealers_email', $select, $condiciones, '', '');

        $vecInfo1 = explode('<#>', $info);
        $vecInfo2 = explode('<|>', $vecInfo1[0]);

        return $vecInfo2;
    }

    public function dameEmailsConcesionariosNombre($nombreConcesionario) {
        $objInfo = $this->_pedirInfoInterfaz();

        $select = array(
            'dealerid'
        );

        $condiciones = "name LIKE '" . $nombreConcesionario . "%'";
        $info = $objInfo->pedirInfo('dealers', $select, $condiciones, '', '');

        $vecInfo1 = explode('<#>', $info);
        $vecInfo2 = explode('<|>', $vecInfo1[0]);

        return $vecInfo2;
    }

    public function dameCargos() {
        $objInfo = $this->_pedirInfoInterfaz();

        $select = array(
            'id_cargo',
            'nombre_cargo'
        );

        $condiciones = 'id_cargo !=-1';
        $orden = 'nombre_cargo';
        $info = $objInfo->pedirInfo('cargos', $select, $condiciones, $orden, '');

        $vecInfo1 = explode('<#>', $info);
        $vecInfo2 = explode('<|>', $vecInfo1[0]);



        $aux1 = 0;
        $aux2 = 1;
        for ($x = 0; $x < $vecInfo1[1]; $x++) {
            echo '<option value="' . $vecInfo2[$aux1] . '">' . $vecInfo2[$aux2] . '</option>';
            $aux1+=2;
            $aux2+=2;
        }
    }

    public function actualizarFormulario($tabla, $clavePrimaria, $camposValores) {
        $objInfo = $this->_pedirInfoInterfaz();
        $info = $objInfo->actualizarCampos2($tabla, $clavePrimaria, $camposValores);
    }

    public function dameNombresCC($parametro, $parametro1) {
        $objInfo = $this->_pedirInfoInterfaz();
        $info = $objInfo->selecTablas($parametro, $parametro1);
        return $info;
    }
    
    public function dameNombresCC1($parametro, $parametro1) {
        $objInfo = $this->_pedirInfoInterfaz();
        $info = $objInfo->selecTablas1($parametro, $parametro1);
        return $info;
    }

    public function dameNombresCC2($parametro) {

        $objInfo = $this->_pedirInfoInterfaz();

        $info = $objInfo->selecTablas2($parametro);

        return $info;
    }

    public function dameNombresCC4($parametro, $parametro1) {

        $objInfo = $this->_pedirInfoInterfaz();

        $info = $objInfo->selecTablas4($parametro, $parametro1);

        return $info;
    }

    public function dameNombresema($parametro) {

        $objInfo = $this->_pedirInfoInterfaz();

        $info = $objInfo->selecTablasema($parametro);

        return $info;
    }

    public function comillas_inteligentes($valor) {
        $objInfo = $this->_pedirInfoInterfaz();
        //Retirar las barras
        if (!get_magic_quotes_gpc()) {
            $valor = addslashes(htmlspecialchars($valor));
        } else {
            $valor = $valor;
        }
        return $valor;
    }

    public function validarTelfs($numTelefono) {
        for ($x = 0; $x < strlen($numTelefono); $x++) {
            $num_rep = substr($numTelefono, $x, 1);
            if (substr_count($numTelefono, $num_rep) > 4) {
                return 0;
            } else {
                return 1;
            }
        }
    }

}

?>