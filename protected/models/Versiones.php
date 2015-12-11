<?php

/**
 * This is the model class for table "versiones".
 *
 * The followings are the available columns in table 'versiones':
 * @property integer $id_versiones
 * @property integer $id_modelos
 * @property integer $id_categoria
 * @property string $nombre_version
 * @property string $precio
 * @property integer $orden
 * @property string $pdf
 */
class Versiones extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Versiones the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'versiones';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_modelos, id_categoria, orden', 'numerical', 'integerOnly' => true),
            array('nombre_version', 'length', 'max' => 100),
            array('precio', 'length', 'max' => 11),
            array('pdf', 'length', 'max' => 200),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_versiones, id_modelos, id_categoria, nombre_version, precio, orden, pdf', 'safe', 'on' => 'search'),
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
            'id_versiones' => 'Id Versiones',
            'id_modelos' => 'Id Modelos',
            'id_categoria' => 'Id Categoria',
            'nombre_version' => 'Nombre Version',
            'precio' => 'Precio',
            'orden' => 'Orden',
            'pdf' => 'Pdf',
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

        $criteria->compare('id_versiones', $this->id_versiones);
        $criteria->compare('id_modelos', $this->id_modelos);
        $criteria->compare('id_categoria', $this->id_categoria);
        $criteria->compare('nombre_version', $this->nombre_version, true);
        $criteria->compare('precio', $this->precio, true);
        $criteria->compare('orden', $this->orden);
        $criteria->compare('pdf', $this->pdf, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}