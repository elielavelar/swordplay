<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "profileoptions".
 *
 * @property int $IdProfile
 * @property int $IdOption
 * @property int $Enabled
 *
 * @property Options $option
 * @property Profiles $profile
 */
class Profileoptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profileoptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdProfile', 'IdOption'], 'required'],
            [['IdProfile', 'IdOption', 'Enabled'], 'integer'],
            [['IdProfile', 'IdOption'], 'unique', 'targetAttribute' => ['IdProfile', 'IdOption']],
            [['IdOption'], 'exist', 'skipOnError' => true, 'targetClass' => Options::className(), 'targetAttribute' => ['IdOption' => 'Id']],
            [['IdProfile'], 'exist', 'skipOnError' => true, 'targetClass' => Profiles::className(), 'targetAttribute' => ['IdProfile' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdProfile' => 'Id Profile',
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
    public function getProfile()
    {
        return $this->hasOne(Profiles::className(), ['Id' => 'IdProfile']);
    }
    
}
