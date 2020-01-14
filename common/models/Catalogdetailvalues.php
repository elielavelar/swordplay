<?php

namespace common\models;

use Yii;
use common\models\Type;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "catalogdetailvalues".
 *
 * @property int $Id
 * @property int $IdCatalogDetail
 * @property int $IdDataType
 * @property int $IdValueType
 * @property int $Sort
 * @property string $Value
 * @property string $Description
 *
 * @property Catalogdetails $catalogDetail
 * @property Type $dataType
 * @property Type $valueType
 */
class Catalogdetailvalues extends \yii\db\ActiveRecord
{
    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catalogdetailvalues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdCatalogDetail', 'IdDataType', 'IdValueType', 'Value'], 'required'],
            [['IdCatalogDetail', 'IdDataType', 'IdValueType','Sort'], 'integer'],
            [['Value'], 'string', 'max' => 500],
            [['Description'], 'string', 'max' => 1000],
            [['IdCatalogDetail', 'Sort'], 'unique', 'targetAttribute' => ['IdCatalogDetail', 'Sort']],
            [['IdCatalogDetail'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogdetails::className(), 'targetAttribute' => ['IdCatalogDetail' => 'Id']],
            [['IdDataType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdDataType' => 'Id']],
            [['IdValueType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdValueType' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdCatalogDetail' => 'Detalle Catálogo',
            'IdDataType' => 'Tipo de Dato',
            'IdValueType' => 'Tipo de Valor',
            'Value' => 'Valor',
            'Sort' => 'Orden',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogDetail()
    {
        return $this->hasOne(Catalogdetails::className(), ['Id' => 'IdCatalogDetail']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdDataType']);
    }
    
    public function getDataTypes(){
        $droptions = Type::find()
                ->joinWith('state b')
                ->where([
                    'b.KeyWord' => StringHelper::basename(Type::class),
                    'b.Code' => Type::STATUS_ACTIVE,
                    'type.KeyWord' => 'Data',
                ])
                ->orderBy(['type.Value' => SORT_ASC])
                ->all();
        return ArrayHelper::map($droptions, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdValueType']);
    }
    
    public function getValueTypes(){
        $droptions = Type::find()
                ->joinWith('state b')
                ->where([
                    'b.KeyWord' => StringHelper::basename(Type::class),
                    'b.Code' => Type::STATUS_ACTIVE,
                    'type.KeyWord' => StringHelper::basename(self::class),
                ])
                ->orderBy(['type.Value' => SORT_ASC])
                ->all();
        return ArrayHelper::map($droptions, 'Id', 'Name');
    }
    
    public function beforeSave($insert) {
        try {
            if(empty($this->Sort)){
                $this->_getNextSort();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return parent::beforeSave($insert);
    }
    
    private function _getNextSort(){
        try {
            $values = self::find();
            $values->where(['IdCatalogDetail' => $this->IdCatalogDetail]);
            $_values = $values->max('Sort');
            $this->Sort = (int)$_values + 1;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
