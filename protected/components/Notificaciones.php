<?php

class Notificaciones extends CWidget {
    public $tipo;
    
    public function init() {
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        if($cargo_id == 71 || $cargo_id == 70 || $cargo_id == 85){
        	$this->render('notificaciones');
        }
    }
    
}

