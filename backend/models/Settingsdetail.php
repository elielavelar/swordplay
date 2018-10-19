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
}
