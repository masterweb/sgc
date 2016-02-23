<?php

/**
 * This is the model class for table "gestion_paso_entrega".
 *
 * The followings are the available columns in table 'gestion_paso_entrega':
 * @property string $id
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property integer $envio_factura
 * @property integer $emision_contrato
 * @property integer $agendar_firma
 * @property integer $alistamiento_unidad
 * @property integer $pago_matricula
 * @property integer $recepcion_contratos
 * @property integer $recepcion_matricula
 * @property integer $vehiculo_revisado
 * @property integer $entrega_vehiculo
 * @property integer $foto_entrega
 * @property integer $foto_hoja_entrega
 * @property integer $paso
 * @property string $fecha
 */
class GestionPasoEntrega extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionPasoEntrega the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_paso_entrega';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, id_vehiculo', 'required'),
            array('id_informacion, id_vehiculo, envio_factura, emision_contrato, agendar_firma, alistamiento_unidad, pago_matricula, recepcion_contratos, recepcion_matricula, vehiculo_revisado, entrega_vehiculo, foto_entrega, foto_hoja_entrega', 'numerical', 'integerOnly' => true),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, id_vehiculo, envio_factura, emision_contrato, agendar_firma, alistamiento_unidad, pago_matricula, recepcion_contratos, recepcion_matricula, vehiculo_revisado, entrega_vehiculo, foto_entrega, foto_hoja_entrega, fecha', 'safe', 'on' => 'search'),
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
            'envio_factura' => 'Envio Factura',
            'emision_contrato' => 'Emision Contrato',
            'agendar_firma' => 'Agendar Firma',
            'alistamiento_unidad' => 'Alistamiento Unidad',
            'pago_matricula' => 'Pago Matricula',
            'recepcion_contratos' => 'Recepcion Contratos',
            'recepcion_matricula' => 'Recepcion Matricula',
            'vehiculo_revisado' => 'Vehiculo Revisado',
            'entrega_vehiculo' => 'Entrega Vehiculo',
            'foto_entrega' => 'Foto Entrega',
            'foto_hoja_entrega' => 'Foto Hoja Entrega',
            'fecha' => 'Fecha',
            'paso' => 'Paso',
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
        $criteria->compare('envio_factura', $this->envio_factura);
        $criteria->compare('emision_contrato', $this->emision_contrato);
        $criteria->compare('agendar_firma', $this->agendar_firma);
        $criteria->compare('alistamiento_unidad', $this->alistamiento_unidad);
        $criteria->compare('pago_matricula', $this->pago_matricula);
        $criteria->compare('recepcion_contratos', $this->recepcion_contratos);
        $criteria->compare('recepcion_matricula', $this->recepcion_matricula);
        $criteria->compare('vehiculo_revisado', $this->vehiculo_revisado);
        $criteria->compare('entrega_vehiculo', $this->entrega_vehiculo);
        $criteria->compare('foto_entrega', $this->foto_entrega);
        $criteria->compare('foto_hoja_entrega', $this->foto_hoja_entrega);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
