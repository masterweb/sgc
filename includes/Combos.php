<?php
require_once 'Conexion.php';
require_once 'ConsultasBase.php';
class Combos
{
    private $_conexion;

    //Tipos

    //Modelo

    //Especificaciones

    //Versiones
    private $_idConcesionario= Array();
    private $_nombreConcesionario= Array();
    private $_idCiudad= Array();
     private $_nombreCiudad= Array();


    public function  __construct() {
        $objConexion= new Conexion();
        $this->_conexion=$objConexion->conectar();
    }

   //Metodo que se conecta a la interfaz
    private function _pedirInfoInterfaz(){
        $objConsultaBase=new ConsultasBase();
        return $objConsultaBase;
    }

    public function dameConcesionarios($idCiudad){
        $select=array(
            'dealerid',
            'name'
        );
        $condiciones='cityid= '.$idCiudad.' AND statusid = 1';

        $objInfo=$this->_pedirInfoInterfaz();
        $infoXml=$objInfo->pedirInfoXml('dealers', $select, $condiciones, 'name', $limit);
        $versionXml=simplexml_load_string($infoXml);

        $longitud=count($versionXml->dealers->dealerid);
        for($x=0; $x<$longitud; $x++){
            $this->_idConcesionario[$x]=$versionXml->dealers->dealerid[$x];
            $this->_nombreConcesionario[$x]=$versionXml->dealers->name[$x];
        }

    }
    
    public function dameCiudades($idProvincia){
        $select=array(
            'cityid',
            'name'
        );
        $condiciones='id_provincia= '.$idProvincia;

        $objInfo=$this->_pedirInfoInterfaz();
        $infoXml=$objInfo->pedirInfoXml('dealercities', $select, $condiciones, 'name', $limit);
        $versionXml=simplexml_load_string($infoXml);

        $longitud=count($versionXml->dealercities->cityid);
        for($x=0; $x<$longitud; $x++){
            $this->_idCiudad[$x]=$versionXml->dealercities->cityid[$x];
            $this->_nombreCiudad[$x]=$versionXml->dealercities->name[$x];
        }

    }


    public function __getIdConcesionario(){
        return $this->_idConcesionario;
    }

    public function __getNombreConcesionario(){
        return $this->_nombreConcesionario;
    }
    public function __getIdCiudad(){
        return $this->_idCiudad;
    }

    public function __getNombreCiudad(){
        return $this->_nombreCiudad;
    }
}

?>
