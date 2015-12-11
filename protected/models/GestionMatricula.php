<?php

/**
 * This is the model class for table "gestion_matricula".
 *
 * The followings are the available columns in table 'gestion_matricula':
 * @property string $id
 * @property integer $factura_ingreso
 * @property integer $envio_factura
 * @property integer $pago_consejo
 * @property integer $venta_credito
 * @property integer $entrega_documentos_gestor
 * @property integer $ente_regulador_placa
 * @property integer $vehiculo_matricula_placas
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property string $fecha
 * @property string $agendamiento
 */
class GestionMatricula extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionMatricula the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_matricula';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, id_vehiculo', 'required'),
            array('factura_ingreso, envio_factura, pago_consejo, venta_credito, entrega_documentos_gestor, ente_regulador_placa, vehiculo_matricula_placas, id_informacion, id_vehiculo', 'numerical', 'integerOnly' => true),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, factura_ingreso, envio_factura, pago_consejo, venta_credito, entrega_documentos_gestor, ente_regulador_placa, vehiculo_matricula_placas, id_informacion, id_vehiculo, fecha', 'safe', 'on' => 'search'),
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
            'factura_ingreso' => 'Factura Ingreso',
            'envio_factura' => 'Envio Factura',
            'pago_consejo' => 'Pago Consejo',
            'venta_credito' => 'Venta Credito',
            'entrega_documentos_gestor' => 'Entrega Documentos Gestor',
            'ente_regulador_placa' => 'Ente Regulador Placa',
            'vehiculo_matricula_placas' => 'Vehiculo Matricula Placas',
            'id_informacion' => 'Id Informacion',
            'id_vehiculo' => 'Id Vehiculo',
            'fecha' => 'Fecha',
            'agendamiento' => 'Agendamiento',
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
        $criteria->compare('factura_ingreso', $this->factura_ingreso);
        $criteria->compare('envio_factura', $this->envio_factura);
        $criteria->compare('pago_consejo', $this->pago_consejo);
        $criteria->compare('venta_credito', $this->venta_credito);
        $criteria->compare('entrega_documentos_gestor', $this->entrega_documentos_gestor);
        $criteria->compare('ente_regulador_placa', $this->ente_regulador_placa);
        $criteria->compare('vehiculo_matricula_placas', $this->vehiculo_matricula_placas);
        $criteria->compare('id_informacion', $this->id_informacion);
        $criteria->compare('id_vehiculo', $this->id_vehiculo);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
