<?php

/**
 * This is the model class for table "gestion_financiamiento".
 *
 * The followings are the available columns in table 'gestion_financiamiento':
 * @property string $id
 * @property integer $id_informacion
 * @property integer $id_pdf
 * @property string $cuota_inicial
 * @property string $saldo_financiar
 * @property string $tarjeta_credito
 * @property string $otro
 * @property string $plazos
 * @property string $cuota_mensual
 * @property string $avaluo
 * @property string $observaciones
 * @property string $precio_vehiculo
 * @property string $precio_normal
 * @property string $tasa
 * @property string $seguro
 * @property string $valor_financiamiento
 * @property string $forma_pago
 * @property string $entidad_financiera
 * @property string $fecha
 * @property string $ts
 * @property integer $order
 * @property string $total_accesorios
 */
class GestionFinanciamiento extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionFinanciamiento the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_financiamiento';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, id_vehiculo', 'required'),
            array('id_informacion, id_vehiculo, id_pdf, order', 'numerical', 'integerOnly' => true),
            array('cuota_inicial, saldo_financiar, tarjeta_credito, otro, plazos, cuota_mensual, avaluo, categoria', 'length', 'max' => 45),
            array('fecha_cita, observaciones, fecha, total_accesorios', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, cuota_inicial, saldo_financiar, tarjeta_credito, otro, plazos, cuota_mensual, avaluo, observaciones, fecha', 'safe', 'on' => 'search'),
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
            'id_pdf' => 'Id Pdf',
            'cuota_inicial' => 'Cuota Inicial',
            'saldo_financiar' => 'Saldo Financiar',
            'tarjeta_credito' => 'Tarjeta Credito',
            'otro' => 'Otro',
            'plazos' => 'Plazos',
            'cuota_mensual' => 'Cuota Mensual',
            'avaluo' => 'Avaluo',
            'categoria' => 'Categoria',
            'fecha_cita' => 'Fecha Agendamiento',
            'observaciones' => 'Observaciones',
            'precio_vehiculo' => 'Precio Vehiculo',
            'precio_normal' => 'Precio Normal',
            'tasa' => 'Tasa',
            'seguro' => 'Seguro',
            'valor_financiamiento' => 'valor Financiamiento',
            'forma_pago' => 'Forma de pago',
            'entidad_financiera' => 'Entidad Financiera',
            'fecha' => 'Fecha',
            'ts' => 'Tiempo Seguro',
            'order' => 'Orden',
            'total_accesorios' => 'Total Accesorios'
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
        $criteria->compare('cuota_inicial', $this->cuota_inicial, true);
        $criteria->compare('saldo_financiar', $this->saldo_financiar, true);
        $criteria->compare('tarjeta_credito', $this->tarjeta_credito, true);
        $criteria->compare('otro', $this->otro, true);
        $criteria->compare('plazos', $this->plazos, true);
        $criteria->compare('cuota_mensual', $this->cuota_mensual, true);
        $criteria->compare('avaluo', $this->avaluo, true);
        $criteria->compare('categoria', $this->categoria, true);
        $criteria->compare('fecha_cita', $this->fecha_cita, true);
        $criteria->compare('observaciones', $this->observaciones, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}