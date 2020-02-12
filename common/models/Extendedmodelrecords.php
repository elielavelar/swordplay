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
 * @property int $IdExtendedModelKey
 * @property string $AttributeKeyValue
 *
 * @property Extendedmodelfieldvalues[] $extendedmodelfieldvalues
 * @property Extendedmodelfields[] $extendedModelFields
 * @property Extendedmodelkeys $extendedModelKey
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
            [['IdExtendedModelKey'], 'required'],
            [['IdExtendedModelKey'], 'integer'],
            [['AttributeKeyValue'], 'string', 'max' => 100],
            [['IdExtendedModelKey', 'AttributeKeyValue'], 'unique', 'targetAttribute' => ['IdExtendedModelKey', 'AttributeKeyValue']],
            [['IdExtendedModelKey'], 'exist', 'skipOnError' => true, 'targetClass' => Extendedmodelkeys::className(), 'targetAttribute' => ['IdExtendedModelKey' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdExtendedModelKey' => 'Id Extended Model Key',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedModelKey()
    {
        return $this->hasOne(Extendedmodelkeys::className(), ['id' => 'IdExtendedModelKey']);
    }
}
