<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "useroptions".
 *
 * @property int $IdUser
 * @property int $IdOption
 * @property int $Enabled
 *
 * @property Options $option
 * @property Users $user
 */
class Useroptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'useroptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdUser', 'IdOption', 'Enabled'], 'required'],
            [['IdUser', 'IdOption', 'Enabled'], 'integer'],
            [['IdUser', 'IdOption'], 'unique', 'targetAttribute' => ['IdUser', 'IdOption']],
            [['IdOption'], 'exist', 'skipOnError' => true, 'targetClass' => Options::className(), 'targetAttribute' => ['IdOption' => 'Id']],
            [['IdUser'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['IdUser' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdUser' => 'Id User',
            'IdOption' => 'Id Option',
            'Enabled' => 'Enabled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(Options::className(), ['Id' => 'IdOption']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['Id' => 'IdUser']);
    }
}
