<?php

require_once 'Conexion.php';
require_once 'interfaz.ConsultasBase.php';

class ConsultasBase implements InterfazConsultasBase {

    private $_conexion;

    public function __construct() {
        $objConexion = new Conexion();
        $this->_conexion = $objConexion->conectar();
    }

    public function pedirInfoXml($tabla, $select, $condiciones, $orden, $limit) {
        $longitud = count($select);
        for ($x = 0; $x < $longitud; $x++) {
            @$contSelect.=$select[$x];
            if ($x < $longitud - 1) {
                $contSelect.=', ';
            }
        }

        if ($orden == '' && $limit == '') {
            $query = "SELECT " . $contSelect . " FROM " . $tabla . ' WHERE ' . $condiciones;
        }
        if ($orden != '' && $limit == '') {
            $query = "SELECT " . $contSelect . " FROM " . $tabla . ' WHERE ' . $condiciones . ' order by ' .
                    $orden . ' asc ';
        }
        if ($orden == '' && $limit != '') {
            $query = "SELECT " . $contSelect . " FROM " . $tabla . ' WHERE ' . $condiciones . ' LIMIT ' . $limit;
        }
        if ($orden != '' && $limit != '') {
            $query = "SELECT " . $contSelect . " FROM " . $tabla . ' WHERE ' . $condiciones . ' order by ' .
                    $orden . ' asc LIMIT ' . $limit;
        }

        $result = $this->_conexion->query($query);
        $num_result = $result->num_rows;

        if ($num_result == 1) {
            $result->data_seek(0);
            $row = $result->fetch_assoc();

            $xml = '<?xml version="1.0" encoding="utf-8"?>';
            $xml.='<doc>';
            $xml.='<' . $tabla . '>';
            for ($x = 0; $x < $longitud; $x++) {
                $xml.="<$select[$x]>";
                $xml.=$row[$select[$x]];
                $xml.="</$select[$x]>";
            }
            $xml.='</' . $tabla . '>';
            $xml.='</doc>';
        }

        if ($num_result > 1) {
            $result->data_seek(0);
            $xml = '<?xml version="1.0" encoding="utf-8"?>';
            $xml.='<doc>';
            $xml.='<' . $tabla . '>';
            for ($x = 0; $x < $num_result; $x++) {
                $row = $result->fetch_assoc();
                $z = 0;
                for ($y = 0; $y < $longitud; $y++) {
                    $xml.="<$select[$z]>";
                    $xml.=$row[$select[$y]];
                    $xml.="</$select[$z]>";

                    $z++;
                }
            }
            $xml.='</' . $tabla . '>';
            $xml.='</doc>';
        }

        //$xml=$query;
        return $xml;
    }

    public function pedirInfo($tabla, $select, $condiciones, $orden, $limit) {
        $longitud = count($select);
        $contSelect = '';
        $info = '';
        for ($x = 0; $x < $longitud; $x++) {
            $contSelect.=$select[$x];
            if ($x < $longitud - 1) {
                $contSelect.=', ';
            }
        }

        if ($orden == '' && $limit == '') {
            $query = "SELECT " . $contSelect . " FROM " . $tabla . ' WHERE ' . $condiciones;
        }
        if ($orden != '' && $limit == '') {
            $query = "SELECT " . $contSelect . " FROM " . $tabla . ' WHERE ' . $condiciones . ' order by ' .
                    $orden . ' asc ';
        }
        if ($orden == '' && $limit != '') {
            $query = "SELECT " . $contSelect . " FROM " . $tabla . ' WHERE ' . $condiciones . ' LIMIT ' . $limit;
        }
        if ($orden != '' && $limit != '') {
            $query = "SELECT " . $contSelect . " FROM " . $tabla . ' WHERE ' . $condiciones . ' order by ' .
                    $orden . ' asc LIMIT ' . $limit;
        }
        //die('sql:  '.$query);
        $result = $this->_conexion->query($query);
        //$result=query($query);

        $num_result = $result->num_rows;
        //die('num rows:  '.$num_result);

        if ($num_result == 1) {
            $result->data_seek(0);
            $row = $result->fetch_assoc();
            for ($x = 0; $x < $longitud; $x++) {
                $info.= $row[$select[$x]] . '<|>';
            }
        }

        if ($num_result > 1) {
            $result->data_seek(0);
            for ($x = 0; $x < $num_result; $x++) {
                $row = $result->fetch_assoc();
                for ($y = 0; $y < $longitud; $y++) {
                    $info.=$row[$select[$y]] . '<|>';
                }
            }
        }
        //echo $info;

        $info.='<#>' . $num_result;
        return $info;
        //return $query;
    }

    //Devuelve el total de registros ingresados en la condicion
    public function totalRegistros($countNombre, $tablas, $condicion) {
        foreach ($countNombre as $countNombre => $nombre) {
            
        }

        $query = "SELECT
                count(galeria.id_galeria) as total_externas
                FROM galeria, galeria_tipos
                where galeria.id_modelos=10
                and id_galeria_tipos=1";

        $result = $this->_conexion->query($query);
        $row = $result->fetch_assoc();

        return $row['total_externas'];
    }

    public function dameFichaTecnica($idModelo) {

        $select = array('pdf_ficha_tecnica');
        $condiciones = 'id_modelos= ' . $idModelo;
        $info = $this->pedirInfo('modelos', $select, $condiciones, '', '');

        $vecinfo1 = explode('<#>', $info);
        $vecinfo2 = explode('<|>', $vecinfo1[0]);
        return $vecinfo2[0];
    }

#Actualizacion de Tablas  Ultimo Cambio Renan

    public function actualizarCampos2($tabla, $clavePrimaria, $camposValores) {

        foreach ($clavePrimaria as $campoCPrimaria => $campoValorCP) {
            
        }

        $longitud = count($camposValores);
        $x = 0;
        foreach ($camposValores as $camposDb => $valoresUs) {
            $camposActualizar.=$camposDb . '= ' . $valoresUs;
            if ($x < $longitud - 1) {
                $camposActualizar.=', ';
            }
            $x++;
        }

        $query = " UPDATE " . $tabla . ' SET ' . $camposActualizar . ' WHERE ' . $campoCPrimaria . "= " . $campoValorCP;
        //echo $query.'<br />';
        $result = $this->_conexion->query($query);
    }

    //Ingreso de Registros
    //NOTA:Campos valores deben incluir la coma ejemplo:('nomb_campo,'=>valor)
    public function ingresarCampos($tabla, $camposValores) {
        $sw = count($camposValores);
        $x = 1;
        foreach ($camposValores as $c => $v) {
            $campos.=$c;
            $valores.=$v;
            if ($x < $sw) {
                $valores.=',';
            }
            $x++;
        }

        $query = "INSERT INTO $tabla
                ($campos)
                VALUES ($valores);";

        $result = $this->_conexion->query($query);
        return $query;
    }

    public function selecTablas($parametro, $parametro1) {
        $Sql = "SELECT dc.`name` as NombreCiudad, d.`name` as NombreConce, d.direccion  FROM dealercities dc, dealers d WHERE ";
        $Sql .= "dc.cityid=d.cityid 
			        AND dc.cityid = " . $parametro . " 
					AND d.dealerid = " . $parametro1 . " 
					";
        //die('sql: '.$Sql);
        $result = $this->_conexion->query($Sql);
        //$num_result=$result->num_rows;
        //$row=$result->fetch_assoc();
        return $result;
    }

    public function selecTablas1($parametro, $parametro1) {

        $Sql = "SELECT m.nombre_modelo, v.nombre_version FROM modelos m, versiones v WHERE ";
        $Sql .= "m.id_modelos=v.id_modelos 
			         AND m.id_modelos = " . $parametro . " 
				     AND v.id_versiones = " . $parametro1 . "
				   ";
        //die('sql:  '.$Sql);
        $result = $this->_conexion->query($Sql);
        //$num_result=$result->num_rows;
        //$row=$result->fetch_assoc();
        return $result;
    }

    public function selecTablas2($parametro) {
        $Sql = "SELECT * FROM dealers	WHERE dealerid = " . $parametro . "";
        $result = $this->_conexion->query($Sql);
        //$num_result=$result->num_rows;
        //$row=$result->fetch_assoc();
        return $result;
    }

    public function selecTablas4($parametro, $parametro1) {
        if (!empty($parametro)) {
            //echo '1';
            $Sql = "SELECT * FROM dealercities c, provincia p 
						WHERE c.id_provincia=p.id_provincia 
						AND c.cityid=" . $parametro . " 
						AND p.id_provincia=" . $parametro1 . "";
        } else {
            $Sql = "SELECT nombre_provincia 
                                        FROM provincia
                                        WHERE id_provincia = " . $parametro1 . "";
        }
        $result = $this->_conexion->query($Sql);
        //$num_result=$result->num_rows;
        //$row=$result->fetch_assoc();
        return $result;
    }

    public function selecTablasema($parametro) {
        $Sql = "SELECT email FROM emaillist
					WHERE email='" . $parametro . "'
					LIMIT 1
					";
        //echo $Sql;
        $result = $this->_conexion->query($Sql);
        //$num_result=$result->num_rows;
        //$row=$result->fetch_assoc();
        return $result;
    }

}

?>
