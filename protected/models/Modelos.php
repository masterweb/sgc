<?php

/**
 * This is the model class for table "modelos".
 *
 * The followings are the available columns in table 'modelos':
 * @property integer $id_modelos
 * @property integer $id_tipo
 * @property integer $id_categoria
 * @property string $nombre_modelo
 * @property string $code
 * @property string $slogan
 * @property string $logo_home
 * @property string $fotocar_home
 * @property string $fotomain_modelo
 * @property string $logo_modelo_interior
 * @property string $pdf_ficha_tecnica
 * @property string $estado
 * @property integer $orden
 */
class Modelos extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Modelos the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'modelos';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('code', 'required'),
            array('id_tipo, id_categoria, orden', 'numerical', 'integerOnly' => true),
            array('nombre_modelo', 'length', 'max' => 100),
            array('code, logo_home, fotocar_home, fotomain_modelo, logo_modelo_interior, pdf_ficha_tecnica', 'length', 'max' => 60),
            array('slogan', 'length', 'max' => 255),
            array('estado', 'length', 'max' => 1),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_modelos, id_tipo, id_categoria, nombre_modelo, code, slogan, logo_home, fotocar_home, fotomain_modelo, logo_modelo_interior, pdf_ficha_tecnica, estado, orden', 'safe', 'on' => 'search'),
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
            'id_modelos' => 'Id Modelos',
            'id_tipo' => 'Id Tipo',
            'id_categoria' => 'Id Categoria',
            'nombre_modelo' => 'Nombre Modelo',
            'code' => 'Code',
            'slogan' => 'Slogan',
            'logo_home' => 'Logo Home',
            'fotocar_home' => 'Fotocar Home',
            'fotomain_modelo' => 'Fotomain Modelo',
            'logo_modelo_interior' => 'Logo Modelo Interior',
            'pdf_ficha_tecnica' => 'Pdf Ficha Tecnica',
            'estado' => 'Estado',
            'orden' => 'Orden',
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

        $criteria->compare('id_modelos', $this->id_modelos);
        $criteria->compare('id_tipo', $this->id_tipo);
        $criteria->compare('id_categoria', $this->id_categoria);
        $criteria->compare('nombre_modelo', $this->nombre_modelo, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('slogan', $this->slogan, true);
        $criteria->compare('logo_home', $this->logo_home, true);
        $criteria->compare('fotocar_home', $this->fotocar_home, true);
        $criteria->compare('fotomain_modelo', $this->fotomain_modelo, true);
        $criteria->compare('logo_modelo_interior', $this->logo_modelo_interior, true);
        $criteria->compare('pdf_ficha_tecnica', $this->pdf_ficha_tecnica, true);
        $criteria->compare('estado', $this->estado, true);
        $criteria->compare('orden', $this->orden);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}