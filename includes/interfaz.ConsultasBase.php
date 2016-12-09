<?php
interface InterfazConsultasBase {
    public function pedirInfoXml($tabla, $select, $condiciones, $orden, $limit);
    public function pedirInfo($tabla, $select, $condiciones, $orden, $limit);
    public function totalRegistros($countNombre, $tablas, $condicion);
    public function dameFichaTecnica($idModelo);
}
?>
