<?php

/**
 * This is the model class for table "gestion_paso_entrega_detail".
 *
 * The followings are the available columns in table 'gestion_paso_entrega_detail':
 * @property string $id
 * @property integer $id_paso
 * @property integer $id_gestion
 * @property string $fecha_paso
 * @property string $observaciones
 * @property string $placa
 * @property string $responsable
 * @property string $foto_entrega
 * @property string $foto_hoja_entrega
 * @property string $fecha
 */
class GestionPasoEntregaDetail extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionPasoEntregaDetail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_paso_entrega_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_paso,id_gestion', 'numerical', 'integerOnly' => true),
            array('placa', 'length', 'max' => 30),
            array('responsable', 'length', 'max' => 50),
            array('foto_entrega', 'length', 'max' => 250),
            array('fecha_paso, observaciones, foto_hoja_entrega, fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_paso, fecha_paso, observaciones, placa, responsable, foto_entrega, foto_hoja_entrega, fecha', 'safe', 'on' => 'search'),
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
            'id_paso' => 'Id Paso',
            'id_gestion' => 'Id Gestion',
            'fecha_paso' => 'Fecha Paso',
            'observaciones' => 'Observaciones',
            'placa' => 'Placa',
            'responsable' => 'Responsable',
            'foto_entrega' => 'Foto Entrega',
            'foto_hoja_entrega' => 'Foto Hoja Entrega',
            'fecha' => 'Fecha',
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
        $criteria->compare('id_paso', $this->id_paso);
        $criteria->compare('fecha_paso', $this->fecha_paso, true);
        $criteria->compare('observaciones', $this->observaciones, true);
        $criteria->compare('placa', $this->placa, true);
        $criteria->compare('responsable', $this->responsable, true);
        $criteria->compare('foto_entrega', $this->foto_entrega, true);
        $criteria->compare('foto_hoja_entrega', $this->foto_hoja_entrega, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
