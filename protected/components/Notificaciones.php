<?php

class Notificaciones extends CWidget {
    public $tipo;
    
    public function init() {
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $this->render('notificaciones');
    }
    
}

