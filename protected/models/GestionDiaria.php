<?php

/**
 * This is the model class for table "gestion_diaria".
 *
 * The followings are the available columns in table 'gestion_diaria':
 * @property string $id
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property string $status
 * @property string $prospeccion
 * @property string $primera_visita
 * @property string $seguimiento
 * @property string $cierre
 * @property string $entrega
 * @property string $seguimiento_entrega
 * @property string $desiste
 * @property string $observaciones
 * @property string $medio_contacto
 * @property string $fuente_contacto
 * @property string $codigo_vehiculo
 * @property string $proximo_seguimiento
 * @property string $categoria
 * @property string $test_drive
 * @property string $img
 * @property string $exonerado
 * @property string $venta_concretada
 * @property string $forma_pago
 * @property string $chasis
 * @property string $paso
 * @property string $fecha_venta
 * @property string $fecha
 * @property integer $responsable
 */
class GestionDiaria extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionDiaria the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_diaria';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, id_vehiculo, observaciones, medio_contacto, fuente_contacto, codigo_vehiculo', 'required'),
            array('id_informacion, id_vehiculo, prospeccion, primera_visita, seguimiento, cierre, entrega, seguimiento_entrega, desiste,encuestado', 'numerical', 'integerOnly' => true),
            array('realizado,status, medio_contacto, fuente_contacto, codigo_vehiculo, proximo_seguimiento, categoria, test_drive, exonerado, venta_concretada, forma_pago, chasis', 'length', 'max' => 45),
            array('fecha_venta, fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, status, obervaciones, medio_contacto, fuente_contacto, codigo_vehiculo, proximo_seguimiento, categoria, test_drive, exonerado, venta_concretada, forma_pago, chasis, fecha_venta, fecha', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
			'gestioninformacion' => array(self::BELONGS_TO, 'GestionInformacion', 'id_informacion'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_informacion' => 'Id Informacion',
            'id_vehiculo' => 'Id Vehículo',
            'status' => 'Status',
            'prospeccion' => 'Prospección',
            'primera_visita' => 'Primera Visita',
            'seguimiento' => 'Seguimiento',
            'cierre' => 'Cierre',
            'entrega' => 'Entrega',
            'seguimiento_entrega' => 'Seguimiento de Entrega',
            'desiste' => 'Desiste',
            'obervaciones' => 'Observaciones',
            'medio_contacto' => 'Medio Contacto',
            'fuente_contacto' => 'Fuente Contacto',
            'codigo_vehiculo' => 'Codigo Vehiculo',
            'proximo_seguimiento' => 'Proximo Seguimiento',
            'categoria' => 'Categoria',
            'test_drive' => 'Test Drive',
            'img' => 'Imágen',
            'exonerado' => 'Exonerado',
            'venta_concretada' => 'Venta Concretada',
            'forma_pago' => 'Forma Pago',
            'chasis' => 'Chasis',
            'paso' => 'Paso',
            'fecha_venta' => 'Fecha Venta',
            'fecha' => 'Fecha',
            'responsable' => 'Responsable',
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
        $criteria->compare('id_informacion', $this->id_informacion);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('observaciones', $this->obervaciones, true);
        $criteria->compare('medio_contacto', $this->medio_contacto, true);
        $criteria->compare('fuente_contacto', $this->fuente_contacto, true);
        $criteria->compare('codigo_vehiculo', $this->codigo_vehiculo, true);
        $criteria->compare('proximo_seguimiento', $this->proximo_seguimiento, true);
        $criteria->compare('categoria', $this->categoria, true);
        $criteria->compare('test_drive', $this->test_drive, true);
        $criteria->compare('img', $this->img, true);
        $criteria->compare('exonerado', $this->exonerado, true);
        $criteria->compare('venta_concretada', $this->venta_concretada, true);
        $criteria->compare('forma_pago', $this->forma_pago, true);
        $criteria->compare('chasis', $this->chasis, true);
        $criteria->compare('fecha_venta', $this->fecha_venta, true);
        $criteria->compare('fecha', $this->fecha, true);
        $criteria->compare('realizado', $this->realizado, true);
        $criteria->compare('encuestado', $this->encuestado, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    

}
