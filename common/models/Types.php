<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "types".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $Code
 * @property int $IdState
 * @property string $Description
 *
 * @property States $state
 */
class Types extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'Code', 'IdState'], 'required'],
            [['IdState'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 30],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['IdState' => 'Id']],
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
            'Code' => 'Code',
            'IdState' => 'Id State',
            'Description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(States::className(), ['Id' => 'IdState']);
    }
}
