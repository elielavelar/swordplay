<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "profileoptions".
 *
 * @property int $IdProfile
 * @property int $IdOption
 * @property int $Enabled
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
}
