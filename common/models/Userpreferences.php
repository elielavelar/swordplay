<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use backend\models\Settingsdetail;

/**
 * This is the model class for table "userpreferences".
 *
 * @property int $IdUser
 * @property int $IdSettingDetail
 * @property string $Value
 *
 * @property Settingsdetail $settingDetail
 * @property User $user
 */
class Userpreferences extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userpreferences';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdUser', 'IdSettingDetail'], 'required'],
            [['IdUser', 'IdSettingDetail'], 'integer'],
            [['Value'], 'string', 'max' => 30],
            [['IdUser', 'IdSettingDetail'], 'unique', 'targetAttribute' => ['IdUser', 'IdSettingDetail']],
            [['IdSettingDetail'], 'exist', 'skipOnError' => true, 'targetClass' => Settingsdetail::className(), 'targetAttribute' => ['IdSettingDetail' => 'Id']],
            [['IdUser'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdUser' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdUser' => 'Id User',
            'IdSettingDetail' => 'Id Setting Detail',
            'Value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingDetail()
    {
        return $this->hasOne(Settingsdetail::className(), ['Id' => 'IdSettingDetail']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['Id' => 'IdUser']);
    }
    
    public function getHTMLControls(){
        try {
            $setting = Settingsdetail::find()
                    ->joinWith('state b')
                    ->where(['settingsdetail.Id' => $this->IdSettingDetail, 'b.Code' => Settingsdetail::STATUS_ACTIVE])
                    ->one();
            print_r($setting); die();
            if($setting){
                
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
