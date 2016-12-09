<?php
require_once 'Conexion.php';
require_once 'ConsultasBase.php';

class Auto

{

    private $_conexion;



    //Tipos

    private $_nombre_tipo=Array();



    //Modelo

    private $_idModelos= Array();

    private $_idTipo= Array();

	private $_code= array();

    private $_idCategoria= Array();

    private $_nombreModelo= Array();
	private $_modelo;

    private $_slogan= Array();

    private $_logoHome= Array();

    private $_fotocarHome= Array();

    private $_fotomainModelo;

    private $_logoModeloInterior= Array();

    private $_pdfFichaTecnica;




    //Especificaciones

    private $_idEspecificaciones;

    private $_dimensionesGrafico;

    private $_motor;

    private $_dimensionesTabla;



    //Versiones

    private $_idVersiones= Array();

    private $_nombreVersion= Array();

    private $_precioVersion= Array();







    public function  __construct() {

        $objConexion= new Conexion();

        $this->_conexion=$objConexion->conectar();

    }



   //Metodo que se conecta a la interfaz

    private function _pedirInfoInterfaz(){

        $objConsultaBase=new ConsultasBase();

        return $objConsultaBase;

    }



    public function dameEspecificaciones($idModelo){

        $tabla='especificaciones';

        $select=array(

            'id_modelos',

            'dimensiones_grafico',

            'motor',

            'dimensiones_tabla'

        );

        $condiciones='id_modelos= '.$idModelo;

        $orden='';

        $limit='';

        $objInfo=$this->_pedirInfoInterfaz();

        $info=$objInfo->pedirInfo($tabla, $select, $condiciones, $orden, $limit);

        $vecInfo1=explode('<#>', $info);

        $vecInfo2=explode('<|>', $info);



        $this->_idModelos[0]=$vecInfo2[0];

        $this->_dimensionesGrafico=$vecInfo2[1];

        $this->_motor=$vecInfo2[2];

        $this->_dimensionesTabla=$vecInfo2[3];

    }



    public function dameModelos(){

        $objInfo=$this->_pedirInfoInterfaz();



        $select=array(

            'id_modelos',

            'nombre_modelo'

        );

        $condiciones='estado= "s"';

        $orden='orden';

        $info=$objInfo->pedirInfo('modelos', $select, $condiciones, $orden, '');



        $vecInfo1=explode('<#>', $info);

        $vecInfo2=explode('<|>', $vecInfo1[0]);



        $aux1=0;

        $aux2=1;

        for($x=0; $x<$vecInfo1[1]; $x++){

            $this->_idModelos[$x]=$vecInfo2[$aux1];

            $this->_nombreModelo[$x]=$vecInfo2[$aux2];

            $aux1+=2;

            $aux2+=2;

        }

    }

	

	public function dameModelos2(){

        $objInfo=$this->_pedirInfoInterfaz();



        $select=array(

            'id_modelos',

            'nombre_modelo',

            'code',

            'id_tipo'

        );

        $condiciones='estado= "s" order by id_tipo asc, orden asc';

        $orden='';

        $info=$objInfo->pedirInfo('modelos', $select, $condiciones, $orden, '');



        $vecInfo1=explode('<#>', $info);

        $vecInfo2=explode('<|>', $vecInfo1[0]);



        $auxId=0;

        $auxNomModelo=1;

        $auxCode=2;

        $auxTipo=3;

        for($x=0; $x<$vecInfo1[1]; $x++){

            $this->_idModelos[]=$vecInfo2[$x+$auxId];

            $this->_nombreModelo[]=$vecInfo2[$x+$auxNomModelo];

            $this->_code[]=$vecInfo2[$x+$auxCode];

            $this->_idTipo[]=$vecInfo2[$x+$auxTipo];



            $auxId+=3;

            $auxNomModelo+=3;

            $auxCode+=3;

            $auxTipo+=3;

        }

    }



    public function dameModelosTipos($idTipo){

        $objInfo=$this->_pedirInfoInterfaz();



        $select=array(

            'id_modelos',

            'nombre_modelo',

            'code'

        );

        $condiciones='estado= "s" and id_tipo='.$idTipo;

        $orden='orden';

        $info=$objInfo->pedirInfo('modelos', $select, $condiciones, $orden, '');



        return $info;

    }



    public function dameVersiones($idModelo){

        $select=array(

            'id_versiones',

            'nombre_version',

            'precio'

        );

        $condiciones='id_modelos= '.$idModelo;



        $objInfo=$this->_pedirInfoInterfaz();

        $infoXml=$objInfo->pedirInfoXml('versiones', $select, $condiciones, 'orden', $limit);

        $versionXml=simplexml_load_string($infoXml);



        $longitud=count($versionXml->versiones->id_versiones);

        for($x=0; $x<$longitud; $x++){

            $this->_idVersiones[$x]=$versionXml->versiones->id_versiones[$x];

            $this->_nombreVersion[$x]=$versionXml->versiones->nombre_version[$x];

            $this->_precioVersion[$x]=$versionXml->versiones->precio[$x];

        }

    }



    public function dameInfoMain($idModelo){//obtiene el fondo del main y el nombre del modelo para cargarlo en el swf        

        $objInfo=$this->_pedirInfoInterfaz();



        $select=array('nombre_modelo', 'fotomain_modelo');

        $condiciones='id_modelos= '.$idModelo;

        $info=$objInfo->pedirInfo('modelos', $select, $condiciones, $orden, $limit);



        $vecInfo1=explode('<#>', $info);

        $vecInfo2=explode('<|>', $vecInfo1[0]);



        unset($this->_nombreModelo);

        $nombreModelo=$vecInfo2[0];

        $imgMainModelo=$vecInfo2[1];

        

        $infoFlash="&nombreModelo=$nombreModelo&imgMainModelo=$imgMainModelo";

        return $infoFlash;

    }



    public function listarTiposAutos(){

        $objInfo=$this->_pedirInfoInterfaz();



        $select=array('id_tipos', 'nombre_tipo');

        $condiciones='id_tipos != -1';

        $info=$objInfo->pedirInfo('tipos', $select, $condiciones, '', '');



        return $info;

    }



    public function demeTiposAuto(){///esto ejecuta el administrador---genera el xml con los contenidos del menu principal        





        $query="SELECT modelos.id_modelos,

                tipos.nombre_tipo,

                modelos.nombre_modelo,

                modelos.logo_home,

                modelos.fotocar_home



                FROM tipos, modelos

                where tipos.id_tipos= modelos.id_tipo

                and modelos.estado='s'";



        $result=$this->_conexion->query($query);

        $num_result=$result->num_rows;



        $contenido='<?xml version="1.0" encoding="UTF-8"?>

                    <contenido1_Auto>';



        for($x=0; $x<$num_result; $x++){

            $row=$result->fetch_assoc();

            $contenido.= '<auto>';

            $contenido.= '<id_modelos>'.$row['id_modelos'].'</id_modelos>';

            $contenido.= '<nombre_tipo>'.$row['nombre_tipo'].'</nombre_tipo>';

            $contenido.= '<nombre_modelo>'.$row['nombre_modelo'].'</nombre_modelo>';

            $contenido.= '<logo_home>'.$row['logo_home'].'</logo_home>';

            $contenido.= '<fotocar_home>'.$row['fotocar_home'].'</fotocar_home>';

            $contenido.= '</auto>';

        }



        $contenido.='</contenido1_Auto>';



        $file=fopen("xml/contenido1_Auto.xml","w+");

        fwrite($file,$contenido);

        fclose($file);

    }



    public function verificarContenidos($idModelo){

        $query="SELECT count(id_modelos) as 'total_registros'

                FROM modelos

                where id_modelos= $idModelo

                and pdf_ficha_tecnica != ''";



        $result=$this->_conexion->query($query);

        $row=$result->fetch_assoc();



        if($row['total_registros'] >0){

            return 1;

        }else{

            return 0;

        }

    }



    private function _cargaPdfFichaTecnica($idModelo){

        $objInfo=$this->_pedirInfoInterfaz();



        $select=array('pdf_ficha_tecnica');

        $condiciones="id_modelos= $idModelo";

        $info=$objInfo->pedirInfo('modelos', $select, $condiciones, '', '');



        $vecInfo1=explode('<#>', $info);

        $vecInfo2=explode('<|>', $vecInfo1[0]);



        $this->_pdfFichaTecnica=$vecInfo2[0];

    }



    private function _logoModeloInterior($idModelo){

        $objInfo=$this->_pedirInfoInterfaz();



        $select=array('logo_modelo_interior');

        $condiciones="id_modelos= $idModelo";

        $info=$objInfo->pedirInfo('modelos', $select, $condiciones, '', '');



        $vecInfo1=explode('<#>', $info);

        $vecInfo2=explode('<|>', $vecInfo1[0]);



        $this->_logoModeloInterior=$vecInfo2[0];

    }
	
	public function modelo($idModelo){
		$query="SELECT nombre_modelo FROM modelos
				where id_modelos=$idModelo
				and estado= 's'";
		$result=$this->_conexion->query($query);
		$row=$result->fetch_assoc();
		
		$this->_modelo=$row['nombre_modelo'];
	}
	
	
	
	public function __getDameModelo(){
		return $this->_modelo;
	}



    public function __getLogoModeloInterior($idModelo){

        $this->_logoModeloInterior($idModelo);

        return $this->_logoModeloInterior;

    }



    public function __getCargaPdfFichaTecnica($idModelo){

        $this->_cargaPdfFichaTecnica($idModelo);

        return $this->_pdfFichaTecnica;

    }



    public function __getIdModelos(){

        return $this->_idModelos;

    }



    public function __getNombreModelos(){

        return $this->_nombreModelo;

    }

	

	public function __getCode(){

        return $this->_code;

    }

	

	public function __getIdTipo(){

        return $this->_idTipo;

    }

	

	public function __getArrayIdModelos(){

        return $this->_idModelos;

    }



    public function __getDimensionesGrafico(){

        return $this->_dimensionesGrafico;

    }



    public function __getMotor(){

        return $this->_motor;

    }



    public function __getDimensionesTabla(){

        return $this->_dimensionesTabla;

    }



    public function __getIdVersion(){

        return $this->_idVersiones;

    }



    public function __getNombreVersion(){

        return $this->_nombreVersion;

    }



    public function __getPrecioVersion(){

        return $this->_precioVersion;

    }

}
?>