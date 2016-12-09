<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of Clicks
 *
 * @author Edison Avila
 */
require_once 'ConsultasBase.php';
class Clicks {
    private $_idClicks;
    private $_idSeccion;
    private $_idModelo;
    private $_idAuto;
    private $_idClickOpciones;
    private $_idNoticia;
    private $_titularNoticia;
    private $_nombreClickOpcion;
    private $_nombreSeccion;
    private $_fecha;


    //Metodo que se conecta a la interfaz
    private function _pedirInfoInterfaz() {
        $objConsultaBase=new ConsultasBase();
        return $objConsultaBase;
    }

    public function guardaClicks($idSeccion, $idModelo, $idOpciones, $idNoticias, $idProvincias, $idCiudad) {
        $objInfo=$this->_pedirInfoInterfaz();

        $fecha=date('Y-m-d');
        $camposValores=array(
                'id_seccion,'=>$idSeccion,
                'id_modelo,'=>$idModelo,
                'id_click_opciones,'=>$idOpciones,                
                'id_provincia,'=>$idProvincias,
                'id_ciudad,'=>$idCiudad,
                'id_noticia,'=>$idNoticias,
                'fecha'=>"'$fecha'"
        );
        $objInfo->ingresarCampos('clicks', $camposValores);
    }


}
?>
