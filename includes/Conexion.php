<?php
class Conexion
{
//   CONEXION PARA KIA PREDESARROLLO
//    private $_servidor="190.85.49.213";
//    private $_usuario="freelo.kiaecu";
//    private $_password="AriadnaEc5050*";
//    private $_dataBase="kiaweb2013";
    
//   CONEXION PARA KIA ESTATICO ACTUAL    
//    private $_servidor="10.178.13.216";
//    private $_usuario="adminkia_b4s3k1";
//    private $_password="CTVLBsUZoGx";
//    private $_dataBase="adminkia_b4s3k1";
  
    private $_servidor="localhost";
    private $_usuario="root";
    private $_password="k143c89?4Fg&2";
    private $_dataBase="adminkia_b4s3k1";
    
    

    public function conectar(){
        $db=new mysqli($this->_servidor, $this->_usuario, $this->_password, $this->_dataBase);

        if(mysqli_connect_errno()){
            echo 'Existe un error en la conexion.';
            exit;
        }

        return $db;
    }

    public function desconectar($db){
        $db->close();
    }
}

?>
