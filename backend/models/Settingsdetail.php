<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "settingsdetail".
 *
 * @property int $Id
 * @property int $IdSetting
 * @property string $Name
 * @property string $Code
 * @property int $IdType
 * @property int $IdState
 * @property string $Value
 * @property int $Sort
 * @property string $Description
 *
 * @property Settings $setting
 * @property States $state
 * @property Types $type
 */
class Settingsdetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settingsdetail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdSetting', 'Name', 'Code', 'IdType', 'IdState', 'Value'], 'required'],
            [['IdSetting', 'IdType', 'IdState', 'Sort'], 'integer'],
            [['Value', 'Description'], 'string'],
            [['Name'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 30],
            [['IdSetting'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['IdSetting' => 'Id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Types::className(), 'targetAttribute' => ['IdType' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdSetting' => 'Id Setting',
            'Name' => 'Name',
            'Code' => 'Code',
            'IdType' => 'Id Type',
            'IdState' => 'Id State',
            'Value' => 'Value',
            'Sort' => 'Sort',
            'Description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(Settings::className(), ['Id' => 'IdSetting']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(States::className(), ['Id' => 'IdState']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Types::className(), ['Id' => 'IdType']);
    }
}
