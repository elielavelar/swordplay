<?php

namespace backend\models;

use Yii;
use common\models\State;
use common\models\Type;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
/**
 * This is the model class for table "settingsdetail".
 *
 * @property integer $Id
 * @property integer $IdSetting
 * @property string $Name
 * @property string $Code
 * @property integer $IdType
 * @property integer $IdState
 * @property string $Value
 * @property integer $Sort
 * @property string $Description
 *
 * @property State $state
 * @property Type $type
 * @property Settings $setting
 */
class Settingsdetail extends \yii\db\ActiveRecord
{
    
    const STATUS_ACTIVE = 'ACT';
    const STATUS_INACTIVE = 'INA';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settingsdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdSetting', 'Name', 'Code', 'IdType', 'IdState', 'Value'], 'required','message'=>'{attribute} no puede quedar vacío'],
            [['IdSetting', 'IdType', 'IdState', 'Sort'], 'integer'],
            [['Value', 'Description'], 'string'],
            [['Name'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 50],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'Id']],
            [['IdSetting'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['IdSetting' => 'Id']],
            [['Code'], 'unique', 'targetAttribute' => ['IdSetting', 'Code'], 'message' => 'Ya existe el codigo {value} para este parámetro'],
            [['Sort'], 'unique', 'targetAttribute' => ['IdSetting', 'Sort'], 'message' => 'Ya existe el orden {value} para este parámetro'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdSetting' => 'Id Setting',
            'Name' => 'Nombre',
            'Code' => 'Código',
            'IdType' => 'Tipo',
            'IdState' => 'Estado',
            'Value' => 'Valor',
            'Sort' => 'Orden',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        try {
            $droptions = State::findAll(['KeyWord'=>'Settings']);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdType']);
    }

    public function getTypes(){
        try {
            $droptions = Type::findAll(['KeyWord'=>'Data']);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(Settings::className(), ['Id' => 'IdSetting']);
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
            $values = self::find()
                    ->where(['IdSetting'=> $this->IdSetting])
                    ->max('Sort');
            $this->Sort = (int)$values + 1;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
