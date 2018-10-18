<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profiles".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property int $IdState
 * @property string $Description
 *
 * @property States $state
 * @property Users[] $users
 */
class Profiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'IdState'], 'required'],
            [['IdState'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
            [['KeyWord'], 'unique'],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['IdProfile' => 'Id']);
    }
}
