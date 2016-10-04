<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    private $_id;

    public function authenticate() {

        $users = CHtml::listData(Usuarios::model()->findAll(array("condition" => "estado = :estado", 'params' => array(':estado' => "ACTIVO"))), "usuario", "password");
        //$this->password = sha1($this->password);
        
        if (!isset($users[$this->username]))
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        elseif ($users[$this->username] !== $this->password)
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $modelUsuario = Usuarios::model()->find(array("condition" => "usuario=:usuario", 'params' => array(':usuario' => $this->username)));
            $this->errorCode = self::ERROR_NONE;
            if (!empty($modelUsuario)) {
                $this->_id = $modelUsuario->id;
                $this->setState("first_name", $modelUsuario->apellido . ' ' . $modelUsuario->nombres);
                $this->setState("cargo_id", $modelUsuario->cargo_id);
                $this->setState("cargo_adicional", $modelUsuario->cargo_adicional);
                $this->setState("grupo_id", $modelUsuario->grupo_id);
                $this->setState("area_id", $modelUsuario->area_id);
                $this->setState("concesionario_id", $modelUsuario->concesionario_id);
                $this->setState("usuario", $modelUsuario->usuario);
                $this->setState("id", $modelUsuario->id);
               // session_start();
                $_SESSION['user'] = $modelUsuario->id;
                if (!empty($modelUsuario->foto) && $modelUsuario->foto != "") {
                    $_SESSION['foto'] = $modelUsuario->foto;
                }
            } else {
                $this->_id = 0;
                $this->setState("first_name", $this->username);
            }
        }
        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }

}
