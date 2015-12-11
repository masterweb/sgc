<?php

/**
 * This is the model class for table "qir_comentario_file".
 *
 * The followings are the available columns in table 'qir_comentario_file':
 * @property integer $id
 * @property integer $qirComentarioId
 * @property string $nombre_file
 *
 * The followings are the available model relations:
 * @property Qircomentario $qirComentario
 */
class QirComentarioFile extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return QirComentarioFile the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'qir_comentario_file';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nombre_file', 'required'),
            array('qirComentarioId', 'numerical', 'integerOnly' => true),
            array('nombre_file', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, qirComentarioId, nombre_file', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'qirComentario' => array(self::BELONGS_TO, 'Qircomentario', 'qirComentarioId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'qirComentarioId' => 'Qir Comentario',
            'nombre_file' => 'Nombre File',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('qirComentarioId', $this->qirComentarioId);
        $criteria->compare('nombre_file', $this->nombre_file, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
