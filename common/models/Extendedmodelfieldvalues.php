<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "extendedmodelfieldvalues".
 *
 * @property int $Id
 * @property int $IdExtendedModelRecord
 * @property int $IdExtendedModelField
 * @property string $Value
 * @property int $IdFieldCatalog
 * @property int $CustomValue
 * @property string $Description
 *
 * @property Extendedmodelfields $extendedModelField
 * @property Fieldscatalogs $fieldCatalog
 * @property Extendedmodelrecords $extendedModelRecord
 */
class Extendedmodelfieldvalues extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extendedmodelfieldvalues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdExtendedModelRecord', 'IdExtendedModelField', 'IdFieldCatalog'], 'required'],
            [['IdExtendedModelRecord', 'IdExtendedModelField', 'IdFieldCatalog', 'CustomValue'], 'integer'],
            [['Value', 'Description'], 'string'],
            [['IdExtendedModelRecord', 'IdExtendedModelField'], 'unique', 'targetAttribute' => ['IdExtendedModelRecord', 'IdExtendedModelField']],
            [['IdExtendedModelField'], 'exist', 'skipOnError' => true, 'targetClass' => Extendedmodelfields::className(), 'targetAttribute' => ['IdExtendedModelField' => 'id']],
            [['IdFieldCatalog'], 'exist', 'skipOnError' => true, 'targetClass' => Fieldscatalogs::className(), 'targetAttribute' => ['IdFieldCatalog' => 'id']],
            [['IdExtendedModelRecord'], 'exist', 'skipOnError' => true, 'targetClass' => Extendedmodelrecords::className(), 'targetAttribute' => ['IdExtendedModelRecord' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdExtendedModelRecord' => 'Id Extended Model Record',
            'IdExtendedModelField' => 'Id Extended Model Field',
            'Value' => 'Value',
            'IdFieldCatalog' => 'Id Field Catalog',
            'CustomValue' => 'Custom Value',
            'Description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedModelField()
    {
        return $this->hasOne(Extendedmodelfields::className(), ['id' => 'IdExtendedModelField']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldCatalog()
    {
        return $this->hasOne(Fieldscatalogs::className(), ['id' => 'IdFieldCatalog']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedModelRecord()
    {
        return $this->hasOne(Extendedmodelrecords::className(), ['id' => 'IdExtendedModelRecord']);
    }
}
