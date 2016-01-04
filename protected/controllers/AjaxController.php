<?php

class AjaxController extends Controller {

    public function actionModelos($id) {
        $con = Yii::app()->db;

        $sqlModelos_nv = "SELECT id_versiones, id_modelos, nombre_version from versiones WHERE id_modelos = '{$id}'";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $versiones_car = $requestModelos_nv->queryAll();

        echo '<ul class="versiones">';
        foreach ($versiones_car as $key => $value) {
            echo '<li style="font-size:10px;"><input class="subcheckbox" type="checkbox" name="version[]" value="' . $value['id_versiones'] . '" >' . $value['nombre_version'] . '</li>';
        }
        echo '</ul>';
    }

}
