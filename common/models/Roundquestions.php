<?php

namespace common\models;

use Yii;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use common\models\State;
use common\models\Type;
use common\models\Competitionrounds;
use Exception;

/**
 * This is the model class for table "roundquestions".
 *
 * @property int $Id
 * @property string $Name
 * @property int $IdRound
 * @property int $IdType
 * @property int $IdState
 * @property int $Sort
 * @property string $QuoteReference
 * @property string $Description
 *
 * @property Questionanswers[] $questionanswers
 * @property Competitionrounds $round
 * @property State $state
 * @property Type $type
 */
class Roundquestions extends \yii\db\ActiveRecord
{
    const SCENARIO_UPLOAD = 'upload';
    const DEFAULT_SORT_VALUE = 1;
    const DEFAULT_STATUS = 'PND';
    const STATUS_PENDENT = 'PND';
    const STATUS_SUCCESS = 'RST';
    const STATUS_ANNULLED = 'ANU';
    
    const TYPE_SIMPLE_QUESTION = 'SMP';
    
    public $IdCompetition = null;
    public $IdRoundOrder = null;
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPLOAD] = ['Name','IdRound','IdState','Order','Description','QuoteReference','IdType'];
        
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roundquestions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'IdRound', 'IdType', 'IdState', 'QuoteReference'], 'required'],
            [['IdRound', 'IdType', 'IdState', 'Sort'], 'integer'],
            [['Description'], 'string'],
            [['Name'], 'string', 'max' => 250],
            [['QuoteReference'], 'string', 'max' => 100],
            [['IdRound'], 'exist', 'skipOnError' => true, 'targetClass' => Competitionrounds::className(), 'targetAttribute' => ['IdRound' => 'id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'id']],
            ['Sort','default','value'=> self::DEFAULT_SORT_VALUE],
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
            'IdRound' => 'Id Round',
            'IdType' => 'Id Type',
            'IdState' => 'Id State',
            'QuoteReference' => 'Quote Reference',
            'Description' => 'Description',
            'Sort' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionanswers()
    {
        return $this->hasMany(Questionanswers::className(), ['IdQuestion' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRound()
    {
        return $this->hasOne(Competitionrounds::className(), ['Id' => 'IdRound']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdType']);
    }
    
    public function beforeValidate() {
        try {
            if($this->scenario == self::SCENARIO_UPLOAD && $this->isNewRecord){
                $round = Competitionrounds::findOne(['IdCompetition' => $this->IdCompetition, 'Sort' => $this->IdRoundOrder]);
                $this->IdState = State::findOne(['KeyWord' => StringHelper::basename(self::class),'Code' => self::DEFAULT_STATUS])->Id;
                $this->IdType = Type::findOne(['KeyWord' => StringHelper::basename(self::class),'Code' => self::TYPE_SIMPLE_QUESTION])->Id;
                if(!empty($round)){
                    $this->IdRound = $round->Id;
                } else {
                    throw new Exception(StringHelper::basename(self::class).'- ERROR: No se encontró Ronda'.$this->IdRoundOrder, 93000);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return parent::beforeValidate();
    }


    public function beforeSave($insert) {
        
        return parent::beforeSave($insert);
    }
}
