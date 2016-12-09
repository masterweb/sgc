<?php
require_once 'Conexion.php';
//require_once 'Conexion_1.php';
require_once 'ConsultasBase.php';
class Rss
{

    private $_conexion;

    private $_titulo=array();
    private $_enlace=array();
    private $_descripcion=array();

    public function  __construct() {
        $objConexion= new Conexion();
        $this->_conexion=$objConexion->conectar();
    }

    //Metodo que se conecta a la interfaz
    private function _pedirInfoInterfaz(){
        $objConsultaBase=new ConsultasBase();
        return $objConsultaBase;
    }

    public function dameInfoRss(){


        $objInfo=$this->_pedirInfoInterfaz();

        $select=array('id_noticias',
                      'titulo_noticias',
                      'resumen'
                     );
        $condiciones= 'estado= "s" order by fecha desc';
        $orden= '';
        $info=$objInfo->pedirInfo('noticias', $select, $condiciones, $orden, '');
        
//        $select=array('id',
//                      'title',
//                      'catid',
//                      'created'
//                     );
//        $condiciones= 'catid=50 ORDER BY created DESC';
//        $orden= '';
//        $info=$objInfo->pedirInfo('devel_content', $select, $condiciones, $orden, '');

        $vecInfo1=explode('<#>', $info);
        $vecInfo2=explode('<|>', $vecInfo1[0]);

        $auxId=0;
        $auxTitulo=1;
        $auxDesc=2;
        
        for($x=0; $x<$vecInfo1[1]; $x++){

            $titulo=$vecInfo2[$x+$auxTitulo];
            $this->_titulo[]=utf8_decode($titulo);
            //$this->_enlace[]='noticias.php?noticia='.$vecInfo2[$x+$auxId];
            $this->_enlace[]='Noticias-Kia/lista-noticias-kia.html';
            $sinHtml=strip_tags($vecInfo2[$x+$auxDesc]);


            $this->_descripcion[]=str_replace('&nbsp;', '', $sinHtml);

            $auxId+=2;
            $auxTitulo+=2;
            $auxDesc+=2;            
        }
    }


    public function  __get($name) {
        switch ($name){
            case 'titulo':
                return $this->_titulo;
                break;
            case 'enlace':
                return $this->_enlace;
                break;
            case 'descripcion':
                return $this->_descripcion;
                break;
        }
    }


}

?>
