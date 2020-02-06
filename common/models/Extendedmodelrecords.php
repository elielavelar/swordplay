<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "extendedmodelrecords".
 *
 * @property int $Id
 * @property int $IdExtendedModel
 * @property string $AttributeKeyValue
 *
 * @property Extendedmodelfieldvalues[] $extendedmodelfieldvalues
 * @property Extendedmodelfields[] $extendedModelFields
 */
class Extendedmodelrecords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extendedmodelrecords';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdExtendedModel', 'AttributeKeyValue'], 'required'],
            [['IdExtendedModel'], 'integer'],
            [['AttributeKeyValue'], 'string', 'max' => 100],
            [['IdExtendedModel', 'AttributeKeyValue'], 'unique', 'targetAttribute' => ['IdExtendedModel', 'AttributeKeyValue']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdExtendedModel' => 'Id Extended Model',
            'AttributeKeyValue' => 'Attribute Key Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelfieldvalues()
    {
        return $this->hasMany(Extendedmodelfieldvalues::className(), ['IdExtendedModelRecord' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedModelFields()
    {
        return $this->hasMany(Extendedmodelfields::className(), ['id' => 'IdExtendedModelField'])->viaTable('extendedmodelfieldvalues', ['IdExtendedModelRecord' => 'id']);
    }
}
