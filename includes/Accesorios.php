<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Accesorios
 *
 * @author Edison Avila
 */
require_once 'Conexion.php';
require_once 'ConsultasBase.php';
class Accesorios {

    private $_conexion;

    private $_idAccesorio=Array();
    private $_codAccesorio;
    private $_nomAccesorio=Array();
    private $_precioAccesorio=Array();
    private $_ivaAccesorio;
    private $_precivaAccesorio=Array();
    private $_margenAccesorio;
    private $_markupAccesorio;
    private $_descripcionAccesorio=Array();
    private $_imgAccesorio=Array();
    private $_idAuto;

    public function  __construct() {
        $objConexion=new Conexion();
        $this->_conexion=$objConexion->conectar();
    }

    //Metodo que se conecta a la interfaz
    private function _pedirInfoInterfaz(){
        $objConsultaBase=new ConsultasBase();
        return $objConsultaBase;
    }

    public function dameInfoAccesorios($idModelo){
        $objInfo=$this->_pedirInfoInterfaz();

        $select=array(
                'id_accesorio',
                'nom_accesorio',
                'preciva_accesorio',
                'descripcion_accesorio',
                'img_accesorio'
                );
        $condiciones="publicar_accesorio = 's' and id_auto= $idModelo";
        $info=$objInfo->pedirInfo('accesorios', $select, $condiciones, 'nom_accesorio', '');

        $vecInfo1=explode('<#>', $info);
        $vecInfo2=explode('<|>', $vecInfo1[0]);

        $aux1=0;
        $aux2=1;
        $aux3=2;
        $aux4=3;
        $aux5=4;
        for($x=0; $x<$vecInfo1[1]; $x++){
            $this->_idAccesorio[$x]=$vecInfo2[$x+$aux1];
            $this->_nomAccesorio[$x]=$vecInfo2[$x+$aux2];
            $this->_precivaAccesorio[$x]=$vecInfo2[$x+$aux3];
            $this->_descripcionAccesorio[$x]=$vecInfo2[$x+$aux4];
            $this->_imgAccesorio[$x]=$vecInfo2[$x+$aux5];

            $aux1++;
            $aux2++;
            $aux3++;
            $aux4++;
            $aux5++;
        }
    }

    public function verificarContenidos($idModelo){
        $query="SELECT count(id_auto) as 'total_registros'
                FROM accesorios
                where id_auto= $idModelo
                and publicar_accesorio= 's'";

        $result=$this->_conexion->query($query);
        $row=$result->fetch_assoc();

        if($row['total_registros'] >0){
            return 1;
        }else{
            return 0;
        }
    }

    public function __getIdAccesorio(){
        return $this->_idAccesorio;
    }

    public function __getNombreAccesorio(){
        return $this->_nomAccesorio;
    }

    public function __getPrecioIvaAccesorio(){
        return $this->_precivaAccesorio;
    }

    public function __getDescripcionAccesorio(){
        return $this->_descripcionAccesorio;
    }
    
    public function __getImgAccesorios(){
        return $this->_imgAccesorio;
    }


}
?>
