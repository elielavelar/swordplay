<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "extendedmodelkeys".
 *
 * @property int $Id
 * @property int $IdExtendedModel
 * @property string $AttributeKeyName
 * @property string $AttributeKeyValue
 * @property int $IdState
 * @property string $Description
 *
 * @property Extendedmodelfields[] $extendedmodelfields
 * @property Extendedmodels $extendedModel
 * @property State $state
 * @property Extendedmodelrecords[] $extendedmodelrecords
 */
class Extendedmodelkeys extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extendedmodelkeys';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdExtendedModel', 'AttributeKeyName', 'IdState'], 'required'],
            [['IdExtendedModel', 'IdState'], 'integer'],
            [['Description'], 'string'],
            [['AttributeKeyName', 'AttributeKeyValue'], 'string', 'max' => 100],
            [['AttributeKeyValue'], 'unique'
                , 'targetAttribute' => ['IdExtendedModel', 'AttributeKeyName', 'AttributeKeyValue']
                , 'when' => function($model){
                    return !empty($this->AttributeKeyValue);
                },
            ],
            [['AttributeKeyName'], 'unique'
                , 'targetAttribute' => ['IdExtendedModel', 'AttributeKeyName']
                , 'when' => function($model){
                    return empty($this->AttributeKeyValue);
                },
            ],
            [['IdExtendedModel'], 'exist', 'skipOnError' => true, 'targetClass' => Extendedmodels::className(), 'targetAttribute' => ['IdExtendedModel' => 'id']],
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
            'IdExtendedModel' => 'Modelo',
            'AttributeKeyName' => 'Atributo Clave',
            'AttributeKeyValue' => 'Valor Clave',
            'IdState' => 'Estado',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelfields()
    {
        return $this->hasMany(Extendedmodelfields::className(), ['IdExtendedModelKey' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedModel()
    {
        return $this->hasOne(Extendedmodels::className(), ['id' => 'IdExtendedModel']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'IdState']);
    }
    
    public function getStates(){
        $states = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($states, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelrecords()
    {
        return $this->hasMany(Extendedmodelrecords::className(), ['IdExtendedModelKey' => 'id']);
    }
    
    public function beforeSave($insert) {
        $this->AttributeKeyValue = empty($this->AttributeKeyValue) ? null: $this->AttributeKeyValue;
        return parent::beforeSave($insert);
    }
}
