<?php

/**
 * This is the model class for table "gestion_nueva_cotizacion".
 *
 * The followings are the available columns in table 'gestion_nueva_cotizacion':
 * @property string $id
 * @property string $fuente
 * @property string $motivo_exonerados
 * @property string $identificacion
 * @property string $cedula
 * @property string $ruc
 * @property string $pasaporte
 * @property string $fecha
 * @property string $tipo
 * @property string $lugar_exhibicion
 * @property string $empresa_flotas
 * @property string $datos_cliente
 */
class GestionNuevaCotizacion extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionNuevaCotizacion the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_nueva_cotizacion';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() { 
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cedula,fuente', 'required', 'on' => 'consulta'),
            array('fuente', 'required', 'on' => 'prospeccion'),
            array('fuente, motivo_exonerados', 'length', 'max' => 45),
            array('cedula', 'length', 'max' => 40, 'on' => 'consulta'),
            array('cedula', 'validateDocument', 'on' => 'consulta'),
            array('ruc', 'validateRuc', 'on' => 'ruc'),
            array('fecha, tipo, lugar_exhibicion, empresa_flotas, datos_cliente', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, fuente, cedula, fecha', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'fuente' => 'Fuente',
            'motivo_exonerados' => 'Motivo Exonerados',
            'identificacion' => 'Identificación',
            'cedula' => 'Cedula',
            'ruc' => 'Número',
            'pasaporte' => 'Número',
            'fecha' => 'Número',
            'tipo' => 'Tipo',
            'lugar_exhibicion' => 'Lugar Exhibicion',
            'empresa_flotas' => 'Empresa Flotas',
            'datos_cliente' => 'Datos Cliente',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('fuente', $this->fuente, true);
        $criteria->compare('motivo_exonerados', $this->motivo_exonerados, true);
        $criteria->compare('cedula', $this->cedula, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /* Funcion para validar el numero de la cedula */

    public function validateDocument() {
        $numero = $this->cedula;
        if (strlen($numero) == '10') {
            $suma = 0;
            $residuo = 0;
            $pri = 0;
            $pub = 0;
            $nat = 0;
            $numeroProvincias = 22;
            $modulo = 11;
            /* Aqui almacenamos los digitos de la cedula en variables. */
            $d1 = substr($numero, 0, 1);
            $d2 = substr($numero, 1, 1);
            $d3 = substr($numero, 2, 1);
            $d4 = substr($numero, 3, 1);
            $d5 = substr($numero, 4, 1);
            $d6 = substr($numero, 5, 1);
            $d7 = substr($numero, 6, 1);
            $d8 = substr($numero, 7, 1);
            $d9 = substr($numero, 8, 1);
            $d10 = substr($numero, 9, 1);
            /* El tercer digito es: */
            /* 9 para sociedades privadas y extranjeros */
            /* 6 para sociedades publicas */
            /* menor que 6 (0,1,2,3,4,5) para personas naturales */
            if ($d3 == 7 || $d3 == 8) {
                //echo"El tercer d&iacute;gito ingresado es inv&aacute;lido";
                $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                return 0;
            }
            /* Solo para personas naturales (modulo 10) */
            if ($d3 < 6) {
                $nat = 1;
                $p1 = $d1 * 2;
                if ($p1 >= 10)
                    $p1 -= 9;
                $p2 = $d2 * 1;
                if ($p2 >= 10)
                    $p2 -= 9;
                $p3 = $d3 * 2;
                if ($p3 >= 10)
                    $p3 -= 9;
                $p4 = $d4 * 1;
                if ($p4 >= 10)
                    $p4 -= 9;
                $p5 = $d5 * 2;
                if ($p5 >= 10)
                    $p5 -= 9;
                $p6 = $d6 * 1;
                if ($p6 >= 10)
                    $p6 -= 9;
                $p7 = $d7 * 2;
                if ($p7 >= 10)
                    $p7 -= 9;
                $p8 = $d8 * 1;
                if ($p8 >= 10)
                    $p8 -= 9;
                $p9 = $d9 * 2;
                if ($p9 >= 10)
                    $p9 -= 9;
                $modulo = 10;
            }
            /* Solo para sociedades publicas (modulo 11) */
            /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
            else if ($d3 == 6) {
                $pub = 1;
                $p1 = $d1 * 3;
                $p2 = $d2 * 2;
                $p3 = $d3 * 7;
                $p4 = $d4 * 6;
                $p5 = $d5 * 5;
                $p6 = $d6 * 4;
                $p7 = $d7 * 3;
                $p8 = $d8 * 2;
                $p9 = 0;
            }
            /* Solo para entidades privadas (modulo 11) */ else if ($d3 == 9) {
                $pri = 1;
                $p1 = $d1 * 4;
                $p2 = $d2 * 3;
                $p3 = $d3 * 2;
                $p4 = $d4 * 7;
                $p5 = $d5 * 6;
                $p6 = $d6 * 5;
                $p7 = $d7 * 4;
                $p8 = $d8 * 3;
                $p9 = $d9 * 2;
            }
            $suma = $p1 + $p2 + $p3 + $p4 + $p5 + $p6 + $p7 + $p8 + $p9;
            $residuo = $suma % $modulo;
            /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo */
            $digitoVerificador = $residuo == 0 ? 0 : $modulo - $residuo;
            /* ahora comparamos el elemento de la posicion 10 con el dig. ver. */
            if ($pub == 1) {
                if ($digitoVerificador != $d9) {
                    //echo"El ruc de la empresa del sector p&uacute;blico es incorrecto.";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
                /* El ruc de las empresas del sector publico terminan con 0001 */
                if (substr($numero, 9, 4) != '0001') {
                    //echo "El ruc de la empresa del sector p&uacute;blico debe terminar con 0001";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
            } elseif ($pri == 1) {
                if ($digitoVerificador != $d10) {
                    //echo"El ruc de la empresa del sector privado es incorrecto.";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
                if (substr($numero, 10, 3) != '001') {
                    //echo"El ruc de la empresa del sector privado debe terminar con 001";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
            } elseif ($nat == 1) {
                if ($digitoVerificador != $d10) {
                    //echo"El n&uacute;mero de c&eacute;dula de la persona natural es incorrecto.";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
                if (strlen($numero) > 10 && substr($numero, 10, 3) != '001') {
                    //echo"El ruc de la persona natural debe terminar con 001";
                    $this->addError('cedula', 'Ingresa correctamente el Número de Cédula');
                    return 0;
                }
            }
        } else {
            $this->addError('cedula', 'El número ingresado no tiene 10 dígitos');
            return 0;
        }
        return 1;
    }
    
    public function validateRuc() {
        $strRUC = $this->ruc;
        if (strlen($strRUC) != 13) {
            $this->addError('ruc', 'El número ingresado no tiene 13 dígitos');
            return FALSE;
        }

        $suma = 0;
        $strOriginal = $strRUC;
        $intProvincia = substr($strRUC, 0, 2);
        $intTercero = $strRUC[2];
        if (!settype($strRUC, "float")) {
            $this->addError('ruc', 'Ingrese correctamente el RUC');
            return FALSE;
        }

        if ((int) $intProvincia < 1 || (int) $intProvincia > 23) {
            $this->addError('ruc', 'Ingrese correctamente el RUC');
            return FALSE;
        }

        if ((int) $intTercero != 6 && (int) $intTercero != 9) {
            if (substr($strRUC, 10, 3) == '001')
                return $this->validarCI(substr($strRUC, 0, 10));
            $this->addError('ruc', 'Ingrese correctamente el RUC');
            return FALSE;
        }
        if ((int) $intTercero == 6) {
            $intUltimo = $strOriginal[8];
            for ($indice = 0; $indice < 9; $indice++) {
                //echo $strOriginal[$indice],'</br>';
                switch ($indice) {
                    case 0:
                        $arrProducto[$indice] = $strOriginal[$indice] * 3;
                        break;
                    case 1:
                        $arrProducto[$indice] = $strOriginal[$indice] * 2;
                        break;
                    case 2:
                        $arrProducto[$indice] = $strOriginal[$indice] * 7;
                        break;
                    case 3:
                        $arrProducto[$indice] = $strOriginal[$indice] * 6;
                        break;
                    case 4:
                        $arrProducto[$indice] = $strOriginal[$indice] * 5;
                        break;
                    case 5:
                        $arrProducto[$indice] = $strOriginal[$indice] * 4;
                        break;
                    case 6:
                        $arrProducto[$indice] = $strOriginal[$indice] * 3;
                        break;
                    case 7:
                        $arrProducto[$indice] = $strOriginal[$indice] * 2;
                        break;
                    case 8:
                        $arrProducto[$indice] = 0;
                        break;
                }
            }
        } else {
            $intUltimo = $strOriginal[9];
            for ($indice = 0; $indice < 9; $indice++) {
                //echo $strOriginal[$indice],'</br>';
                switch ($indice) {
                    case 0:
                        $arrProducto[$indice] = $strOriginal[$indice] * 4;
                        break;
                    case 1:
                        $arrProducto[$indice] = $strOriginal[$indice] * 3;
                        break;
                    case 2:
                        $arrProducto[$indice] = $strOriginal[$indice] * 2;
                        break;
                    case 3:
                        $arrProducto[$indice] = $strOriginal[$indice] * 7;
                        break;
                    case 4:
                        $arrProducto[$indice] = $strOriginal[$indice] * 6;
                        break;
                    case 5:
                        $arrProducto[$indice] = $strOriginal[$indice] * 5;
                        break;
                    case 6:
                        $arrProducto[$indice] = $strOriginal[$indice] * 4;
                        break;
                    case 7:
                        $arrProducto[$indice] = $strOriginal[$indice] * 3;
                        break;
                    case 8:
                        $arrProducto[$indice] = $strOriginal[$indice] * 2;
                        break;
                }
            }
        }
        foreach ($arrProducto as $indice => $producto)
            $suma += $producto;
        $residuo = $suma % 11;
        $intVerificador = $residuo == 0 ? 0 : 11 - $residuo;
        //echo "$intVerificador == $intUltimo";
        //return ($intVerificador == $intUltimo ? TRUE : FALSE);
        if ($intVerificador == $intUltimo) {
            return TRUE;
        } else {
            $this->addError('ruc', 'Ingrese correctamente el RUC');
            return FALSE;
        }
    }

    public function validarCI($strCedula) {
        $suma = 0;
        $strOriginal = $strCedula;
        $intProvincia = substr($strCedula, 0, 2);
        $intTercero = $strCedula[2];
        $intUltimo = $strCedula[9];
        if (!settype($strCedula, "float"))
            return FALSE;
        if ((int) $intProvincia < 1 || (int) $intProvincia > 23)
            return FALSE;
        if ((int) $intTercero == 7)
            return FALSE;
        for ($indice = 0; $indice < 9; $indice++) {
            //echo $strOriginal[$indice],'</br>';
            switch ($indice) {
                case 0:
                case 2:
                case 4:
                case 6:
                case 8:
                    $arrProducto[$indice] = $strOriginal[$indice] * 2;
                    if ($arrProducto[$indice] >= 10)
                        $arrProducto[$indice] -= 9;
                    //echo $arrProducto[$indice],'</br>';
                    break;
                case 1:
                case 3:
                case 5:
                case 7:
                    $arrProducto[$indice] = $strOriginal[$indice] * 1;
                    if ($arrProducto[$indice] >= 10)
                        $arrProducto[$indice] -= 9;
                    //echo $arrProducto[$indice],'</br>';
                    break;
            }
        }
        foreach ($arrProducto as $indice => $producto)
            $suma += $producto;
        $residuo = $suma % 10;
        $intVerificador = $residuo == 0 ? 0 : 10 - $residuo;
        return ($intVerificador == $intUltimo ? TRUE : FALSE);
    }

}