<?php

/**
 * This is the model class for table "gestion_notificaciones".
 *
 * The followings are the available columns in table 'gestion_notificaciones':
 * @property string $id
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property integer $id_dealer
 * @property integer $id_asesor
 * @property integer $tipo
 * @property string $paso
 * @property string $descripcion
 * @property string $leido
 * @property string $categorizacion
 * @property string $fecha
 * @property integer $id_agendamiento
 */
class GestionNotificaciones extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionNotificaciones the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_notificaciones';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, id_vehiculo, id_dealer, id_asesor,tipo, id_agendamiento', 'numerical', 'integerOnly' => true),
            array('paso, leido', 'length', 'max' => 20),
            array('descripcion, fecha, categorizacion', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, id_vehiculo, id_dealer, id_asesor,tipo, paso, descripcion, leido, fecha', 'safe', 'on' => 'search'),
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
            'id_informacion' => 'Id Informacion',
            'id_vehiculo' => 'Id Vehiculo',
            'id_dealer' => 'Id Dealer',
            'id_asesor' => 'Id Asesor',
            'tipo' => 'Tipo',
            'paso' => 'Paso',
            'descripcion' => 'Descripcion',
            'leido' => 'Leido',
            'categorizacion' => 'Categorizacion',
            'fecha' => 'Fecha',
            'id_agendamiento' => 'Id Agendamiento',
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
        $criteria->compare('id_vehiculo', $this->id_vehiculo);
        $criteria->compare('id_dealer', $this->id_dealer);
        $criteria->compare('tipo', $this->tipo);
        $criteria->compare('paso', $this->paso, true);
        $criteria->compare('descripcion', $this->descripcion, true);
        $criteria->compare('leido', $this->leido, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
