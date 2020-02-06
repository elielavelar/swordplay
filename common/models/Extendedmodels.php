<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "extendedmodels".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $AttributeKeyName
 * @property int $IdState
 * @property string $Description
 *
 * @property Extendedmodelfields[] $extendedmodelfields
 * @property State $state
 */
class Extendedmodels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extendedmodels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'AttributeKeyName', 'IdState'], 'required'],
            [['IdState'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'KeyWord', 'AttributeKeyName'], 'string', 'max' => 100],
            [['KeyWord'], 'unique'],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Name',
            'KeyWord' => 'Key Word',
            'AttributeKeyName' => 'Attribute Key Name',
            'IdState' => 'Id State',
            'Description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelfields()
    {
        return $this->hasMany(Extendedmodelfields::className(), ['IdExtendedModel' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'IdState']);
    }
}
