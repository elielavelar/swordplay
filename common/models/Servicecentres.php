<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use kartik\helpers\Html;
use yii\web\JsExpression;
use backend\models\Appointmentservicesetting;
use backend\models\Settingsdetail;
use Exception;

/**
 * This is the model class for table "servicecentres".
 *
 * @property integer $Id
 * @property string $Name
 * @property integer $IdCountry
 * @property integer $IdZone
 * @property integer $IdState
 * @property integer $IdType
 * @property string $Address
 * @property string $Phone
 *
 * @property State $state
 * @property Zones $zone
 * @property Type $type
 * @property Countries $country
 */
class Servicecentres extends \yii\db\ActiveRecord
{
    public $nameCountry = "";
    
    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';
    
    const TYPE_CENTRAL = 'CNTRL';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicecentres';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'IdCountry', 'IdState', 'IdType','IdZone'], 'required'],
            [['IdCountry', 'IdState', 'IdType', 'IdZone'], 'integer'],
            [['Address'], 'string'],
            [['Name'], 'string', 'max' => 50],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'Id']],
            [['IdCountry'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['IdCountry' => 'Id']],
            [['IdZone'], 'exist', 'skipOnError' => true, 'targetClass' => Zones::className(), 'targetAttribute' => ['IdZone' => 'Id']],
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
            'IdCountry' => 'País',
            'IdZone' => 'Zona',
            'IdState' => 'Estado',
            'IdType' => 'Tipo',
            'Address' => 'Dirección',
            'nameCountry' => 'País',
        ];
    }
    
    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }


    public function afterFind() {
        $this->nameCountry = $this->IdCountry ? $this->country->Name: NULL;
        return parent::afterFind();
    }
    
    public function afterSave($insert, $changedAttributes) {
        return parent::afterSave($insert, $changedAttributes);
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
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['Id' => 'IdCountry']);
    }
    
    public function getCountries(){
        try {
            $droptions = Countries::findAll(['IdState'=>  State::findOne(['KeyWord'=>'Countries','Code'=>'ACT'])]);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(Zones::className(), ['Id' => 'IdZone']);
    }
    
    public function getZones(){
        try {
            $droptions = Zones::findAll(['IdState'=>  State::findOne(['KeyWord'=>  StringHelper::basename(Zones::className()),'Code'=>  Zones::STATUS_ACTIVE])]);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
