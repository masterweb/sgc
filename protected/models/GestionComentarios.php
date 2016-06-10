<?php

/**
 * This is the model class for table "gestion_comentarios".
 *
 * The followings are the available columns in table 'gestion_comentarios':
 * @property string $id
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property integer $id_responsable_recibido
 * @property integer $id_responsable_enviado
 * @property string $titulo
 * @property string $comentario
 * @property string $fecha
 * @property string $img
 * @property string $leido
 */
class GestionComentarios extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionComentarios the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_comentarios';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, id_responsable_recibido, id_responsable_recibido, id_responsable_enviado, comentario, titulo, fecha', 'required'),
            array('id_informacion, id_responsable_recibido, id_responsable_enviado', 'numerical', 'integerOnly' => true),
            array('img, titulo', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, id_responsable_recibido, id_responsable_enviado, comentario, fecha, img, leido', 'safe', 'on' => 'search'),
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
            'id_responsable_recibido' => 'Id Responsable Recibido',
            'id_responsable_enviado' => 'Id Responsable Enviado',
            'titulo' => 'Título',
            'comentario' => 'Comentario',
            'fecha' => 'Fecha',
            'img' => 'Img',
            'leido' => 'Leido',
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
        $criteria->compare('id_responsable_recibido', $this->id_responsable_recibido);
        $criteria->compare('id_responsable_enviado', $this->id_responsable_enviado);
        $criteria->compare('comentario', $this->comentario, true);
        $criteria->compare('fecha', $this->fecha, true);
        $criteria->compare('img', $this->img, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
