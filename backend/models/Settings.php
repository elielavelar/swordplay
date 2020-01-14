<?php

namespace backend\models;

use Yii;
use common\models\State;
use common\models\Type;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use backend\models\CustomActiveRecord;

/**
 * This is the model class for table "settings".
 *
 * @property integer $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $Code
 * @property integer $IdState
 * @property integer $IdType
 * @property string $Description
 *
 * @property State $state
 * @property Type $type
 * @property Settingsdetail[] $settingsdetails
 */
class Settings extends CustomActiveRecord
{
    
    const STATUS_ACTIVE = 'ACT';
    const STATUS_INACTIVE = 'INA';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'Code', 'IdState', 'IdType'], 'required'],
            [['IdState', 'IdType'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 20],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'Id']],
            [['Code'], 'unique', 'targetAttribute' => ['Code'], 'message' => 'Ya existe el código {value} en los parámetros'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Nombre',
            'KeyWord' => 'Llave',
            'Code' => 'Código',
            'IdState' => 'Estado',
            'IdType' => 'Tipo',
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
            $droptions = State::findAll(['KeyWord'=>StringHelper::basename(self::className())]);
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
            $droptions = Type::findAll(['KeyWord'=>StringHelper::basename(self::className())]);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingsdetails()
    {
        return $this->hasMany(Settingsdetail::className(), ['IdSetting' => 'Id']);
    }
}
