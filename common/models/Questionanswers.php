<?php

namespace common\models;

use Yii;
use Exception;

/**
 * This is the model class for table "questionanswers".
 *
 * @property int $Id
 * @property string $Value
 * @property int $IdQuestion
 * @property int $TrueValue
 * @property string $Description
 * @property int $Sort
 *
 * @property Roundquestions $question
 */
class Questionanswers extends \yii\db\ActiveRecord
{
    const SCENARIO_UPLOAD = 'upload';
    const DEFAULT_TRUE_VALUE = 1;
    const TRUE_VALUE_ENABLE = 1;
    const TRUE_VALUE_DISABLE = 0;
    const DEFAULT_SORT_VALUE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questionanswers';
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPLOAD] = ['Value','IdQuestion','TrueValue','Sort','Description'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Value', 'IdQuestion'], 'required'],
            [['Value', 'Description'], 'string'],
            [['IdQuestion', 'TrueValue', 'Sort'], 'integer'],
            [['IdQuestion'], 'exist', 'skipOnError' => true, 'targetClass' => Roundquestions::className(), 'targetAttribute' => ['IdQuestion' => 'Id']],
            ['TrueValue','default','value' => self::DEFAULT_TRUE_VALUE],
            ['Sort','default','value' => self::DEFAULT_SORT_VALUE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Value' => 'Valor',
            'IdQuestion' => 'Pregunta',
            'TrueValue' => 'Verdadero',
            'Description' => 'Descripción',
            'Sort' => 'Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Roundquestions::className(), ['Id' => 'IdQuestion']);
    }
}
