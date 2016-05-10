<?php

/**
 * This is the model class for table "gestion_consulta".
 *
 * The followings are the available columns in table 'gestion_consulta':
 * @property string $id
 * @property string $id_informacion
 * @property string $preg1_sec1
 * @property string $preg1_sec2
 * @property string $preg1_sec3
 * @property string $preg1_sec4
 * @property string $preg1_sec5
 * @property string $preg2
 * @property string $preg2_sec1
 * @property string $preg2_sec2
 * @property string $preg3
 * @property string $preg3_sec1
 * @property string $preg3_sec2
 * @property string $preg3_sec3
 * @property string $preg3_sec4
 * @property string $preg4
 * @property string $preg5
 * @property string $preg6
 * @property string $preg7
 * @property string $preg8
 * @property string $link
 * @property string $fecha
 * @property string $status
 * @property string $colores
 */
class GestionConsulta extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionConsulta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_consulta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('preg1_sec1, preg1_sec2, preg1_sec3, preg1_sec4', 'length', 'max' => 80),
            array('preg1_sec5, preg2, preg3, preg4, preg5, preg6', 'length', 'max' => 10),
            array('preg3_sec1, preg3_sec2, preg3_sec3, preg3_sec4', 'length', 'max' => 20),
            array('preg2_sec1, preg2_sec2, fecha, colores', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, preg1_sec1, preg1_sec2, preg1_sec3, preg1_sec4, preg1_sec5, preg2, preg2_sec1, preg3, preg3_sec1, preg3_sec2, preg3_sec3, preg3_sec4, preg4, preg5, preg6, fecha', 'safe', 'on' => 'search'),
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
            'id_informacion' => 'ID Info',
            'preg1_sec1' => 'Marca',
            'preg1_sec2' => 'Modelo',
            'preg1_sec3' => 'Año',
            'preg1_sec4' => 'Kilometraje',
            'preg1_sec5' => 'Primer Vehículo',
            'preg2' => 'Preg2',
            'preg2_sec1' => 'Fotos del vehiculo',
            'preg2_sec2' => 'Porque cliente no sube fotos',
            'preg3' => 'Para que utilizara nuevo vehiculo',
            'preg3_sec1' => 'Primer vehiculo familiar',
            'preg3_sec2' => 'Primer vehiculo trabajo',
            'preg3_sec3' => 'Segundo vehiculo familiar',
            'preg3_sec4' => 'Primer vehiculo trabajo',
            'preg4' => 'Quien participa decision de compra',
            'preg5' => 'Preg5',
            'preg6' => 'Preg6',
            'preg7' => 'Categorización',
            'preg8' => 'Preg8',
            'link' => 'Link',
            'fecha' => 'Fecha',
            'status' => 'Status',
            'colores' => 'Colores'
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
        $criteria->compare('preg1_sec1', $this->preg1_sec1, true);
        $criteria->compare('preg1_sec2', $this->preg1_sec2, true);
        $criteria->compare('preg1_sec3', $this->preg1_sec3, true);
        $criteria->compare('preg1_sec4', $this->preg1_sec4, true);
        $criteria->compare('preg1_sec5', $this->preg1_sec5, true);
        $criteria->compare('preg2', $this->preg2, true);
        $criteria->compare('preg2_sec1', $this->preg2_sec1, true);
        $criteria->compare('preg3', $this->preg3, true);
        $criteria->compare('preg3_sec1', $this->preg3_sec1, true);
        $criteria->compare('preg3_sec2', $this->preg3_sec2, true);
        $criteria->compare('preg3_sec3', $this->preg3_sec3, true);
        $criteria->compare('preg3_sec4', $this->preg3_sec4, true);
        $criteria->compare('preg4', $this->preg4, true);
        $criteria->compare('preg5', $this->preg5, true);
        $criteria->compare('preg6', $this->preg6, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}