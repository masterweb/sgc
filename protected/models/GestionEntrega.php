<?php

/**
 * This is the model class for table "gestion_entrega".
 *
 * The followings are the available columns in table 'gestion_entrega':
 * @property string $id
 * @property string $agendamiento1
 * @property string $agendamiento2
 * @property integer $id_vehiculo
 * @property integer $id_informacion
 * @property string $accesorios
 * @property string $observaciones
 * @property string $firma
 * @property string $foto_cliente
 * @property string $fecha
 * @property string $observaciones
 */
class GestionEntrega extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionEntrega the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_entrega';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_vehiculo, id_informacion, accesorios', 'required'),
            array('id_vehiculo, id_informacion', 'numerical', 'integerOnly' => true),
            array('agendamiento1, agendamiento2', 'length', 'max' => 100),
            array('fecha, accesorios, observaciones, firma, foto_cliente, observaciones', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, agendamiento1, agendamiento2, id_vehiculo, id_informacion, fecha', 'safe', 'on' => 'search'),
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
            'agendamiento1' => 'Agendamiento1',
            'agendamiento2' => 'Agendamiento2',
            'id_vehiculo' => 'Id Vehiculo',
            'id_informacion' => 'Id Informacion',
            'accesorios' => 'Accesorios',
            'observaciones' => 'Observaciones',
            'firma' => 'Firma',
            'foto_cliente' => 'Foto del Cliente',
            'fecha' => 'Fecha',
            'observaciones' => 'Observaciones',
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
        $criteria->compare('agendamiento1', $this->agendamiento1, true);
        $criteria->compare('agendamiento2', $this->agendamiento2, true);
        $criteria->compare('id_vehiculo', $this->id_vehiculo);
        $criteria->compare('id_informacion', $this->id_informacion);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
