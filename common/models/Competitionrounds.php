<?php

namespace common\models;

use Yii;
use common\models\State;
use common\models\Type;
use common\models\Competitions;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use Exception;
/**
 * This is the model class for table "competitionrounds".
 *
 * @property int $Id
 * @property string $Name
 * @property int $IdCompetition
 * @property int $IdType
 * @property int $IdState
 * @property int $Sort
 * @property string $Icon
 * @property string $Description
 * @property string $QuestionTime
 *
 * @property Competitions $competition
 * @property State $state
 * @property Type $type
 * @property Roundquestions[] $roundquestions
 */
class Competitionrounds extends \yii\db\ActiveRecord
{
    
    const SCENARIO_UPLOAD = 'upload';
    const DEFAULT_ICON = 'fas fa-square';
    const DEFAULT_SORT = 1;
    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';
    const DEFAULT_ROUND_TYPE = 'QST';
    const DEFAULT_QUESTION_TIME = '00:00:00';
    
    public $update = FALSE;
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPLOAD] = ['Name','IdCompetition','IdState','IdType','Description','Sort'];
        
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'competitionrounds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'IdCompetition', 'IdType', 'IdState'], 'required'],
            [['IdCompetition', 'IdType', 'IdState', 'Sort'], 'integer'],
            [['Description','QuestionTime'], 'string'],
            [['Name'], 'string', 'max' => 100],
            [['Icon'], 'string', 'max' => 50],
            [['IdCompetition'], 'exist', 'skipOnError' => true, 'targetClass' => Competitions::className(), 'targetAttribute' => ['IdCompetition' => 'id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Nombre',
            'IdCompetition' => 'Competencia',
            'IdType' => 'Tipo',
            'IdState' => 'Estado',
            'Icon' => 'Ícono',
            'Description' => 'Descripción',
            'QuestionTime' => 'Tiempo para Respuesta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetition()
    {
        return $this->hasOne(Competitions::className(), ['id' => 'IdCompetition']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'IdState']);
    }
    
    public function getStates(){
        try {
            $states = State::find()
                    ->where(['KeyWord' => StringHelper::basename(self::class)])
                    ->all();
            return ArrayHelper::map($states, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'IdType']);
    }
    
    public function getTypes(){
        try {
            $types = Type::find()
                    ->joinWith('state b')
                    ->where([
                        'type.KeyWord' => StringHelper::basename(self::class),
                        'b.KeyWord' => StringHelper::basename(Type::class),
                        'b.Code' => Type::STATUS_ACTIVE,
                    ])
                    ->all();
            return ArrayHelper::map($types, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoundquestions()
    {
        return $this->hasMany(Roundquestions::className(), ['IdRound' => 'id']);
    }
    
    public function beforeSave($insert) {
        $this->QuestionTime = !empty($this->QuestionTime) ? $this->QuestionTime : self::DEFAULT_QUESTION_TIME;
        $this->Icon = !empty($this->Icon) ? $this->Icon : self::DEFAULT_ICON;
        return parent::beforeSave($insert);
    }
    
    public function afterFind() {
        $this->update = $this->IdState ? $this->state->Code == self::STATE_ACTIVE : FALSE;
        return parent::afterFind();
    }
}
