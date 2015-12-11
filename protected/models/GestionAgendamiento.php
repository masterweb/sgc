<?php

/**
 * This is the model class for table "gestion_agendamiento".
 *
 * The followings are the available columns in table 'gestion_agendamiento':
 * @property string $id
 * @property integer $id_informacion
 * @property string $paso
 * @property string $agendamiento
 * @property string $observaciones
 * @property string $fecha
 * @property string $categorizacion
 * @property string $observacion_categorizacion
 * @property string $observacion_seguimiento
 * @property string $otro_observacion
 */
class GestionAgendamiento extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionAgendamiento the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_agendamiento';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion', 'required'),
            array('id_informacion', 'numerical', 'integerOnly' => true),
            array('paso', 'length', 'max' => 20),
            array('agendamiento', 'length', 'max' => 100),
            array('observaciones,observacion_categorizacion,observacion_seguimiento, fecha, categorizacion, otro_observacion', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, paso, agendamiento, observaciones, fecha', 'safe', 'on' => 'search'),
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
            'paso' => 'Paso',
            'agendamiento' => 'Agendamiento',
            'observaciones' => 'Observaciones de Seguimiento',
            'fecha' => 'Fecha',
            'categorizacion' => 'Categorización',
            'observacion_categorizacion' => 'Observación Categorización',
            'observacion_seguimiento' => 'Otro',
            'otro_observacion' => 'Comentario'
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
        $criteria->compare('paso', $this->paso, true);
        $criteria->compare('agendamiento', $this->agendamiento, true);
        $criteria->compare('observaciones', $this->observaciones, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
