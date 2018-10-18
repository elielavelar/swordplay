<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "states".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $Code
 * @property string $Description
 *
 * @property Profiles[] $profiles
 * @property Types[] $types
 * @property Users[] $users
 */
class States extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'Code'], 'required'],
            [['Description'], 'string'],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
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
            'Name' => 'Name',
            'KeyWord' => 'Key Word',
            'Code' => 'Code',
            'Description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profiles::className(), ['IdState' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasMany(Types::className(), ['IdState' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['IdState' => 'Id']);
    }
}
