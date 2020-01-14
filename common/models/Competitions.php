<?php

namespace common\models;

use Yii;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use Exception;
use common\models\Competitionrounds;
use common\models\Roundquestions;
use common\models\Questionanswers;
use moonland\phpexcel\Excel;

/**
 * This is the model class for table "competitions".
 *
 * @property int $Id
 * @property string $Name
 * @property int $IdState
 * @property int $NumberRounds
 * @property string $BookName
 * @property string $Description
 *
 * @property Competitionrounds[] $competitionrounds
 * @property State $state
 */
class Competitions extends \yii\db\ActiveRecord
{
    const STATE_DEFAULT = 'NEW';
    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';
    
    const SCENARIO_UPLOAD = 'upload';
    public $uploadFile = null;
    
    const DEFAULT_ROUNDS_NUMBER = 1;

    private $questions = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'competitions';
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPLOAD] = ['Name','BookName','NumberRounds','IdState','Description'];
        return $scenarios;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'IdState', 'BookName'], 'required'],
            [['IdState','NumberRounds'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'BookName'], 'string', 'max' => 100],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
            ['NumberRounds','default','value'=> self::DEFAULT_ROUNDS_NUMBER],
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
            'IdState' => 'Estado',
            'BookName' => 'Libro',
            'Description' => 'Descripción',
            'NumberRounds' => 'Rondas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitionrounds()
    {
        return $this->hasMany(Competitionrounds::className(), ['IdCompetition' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
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
    
    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }

    public function upload(){
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $data = Excel::import($this->uploadFile->tempName, ['setFirstRecordAsKeys'=>TRUE,'setIndexSheetByName'=>TRUE,]);
            $values = [];
            foreach ($data as $sheet => $lines){
                $values[$sheet] = $lines;
            }
            if(isset($values['Competitions'])){
                $this->attributes = $values['Competitions'][0];
                $this->IdState = State::findOne(['KeyWord' => StringHelper::basename(self::class), 'Code' => self::STATE_DEFAULT])->Id;
                $this->questions = $values['Roundquestions'];
                if($this->save()){
                    $this->refresh();
                    $this->_createRounds();
                    $this->_createQuestions();
                    $transaction->commit();
                    return true;
                } else {
                    $message = \Yii::$app->customFunctions->getErrors($this->errors);
                    throw new Exception('Error al guardar la Competencia: '.$message, 92001);
                }
            } else {
                $message = 'Formato Incorrecto de Archivo cargado';
                $this->addError('uploadFile', $message);
                throw new Exception($message, 92000);
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }
    
    private function _createRounds(){
        try {
            if(count($this->competitionrounds) == 0){
                $rounds = [];
                $type = Type::findOne(['KeyWord' => StringHelper::basename(Competitionrounds::class),'Code' => Competitionrounds::DEFAULT_ROUND_TYPE]);
                $state = State::findOne(['KeyWord' => StringHelper::basename(Competitionrounds::class),'Code' => Competitionrounds::STATE_ACTIVE]);
                for($i = 1; $i <= $this->NumberRounds; $i++){
                    $rounds[] = [
                        'Sort'=>$i,
                        'IdCompetition'=>  $this->Id,
                        'Name'=>  'Ronda '.$i,
                        'Description'=>  'Ronda '.$i,
                        'IdState' => $state->Id,
                        'IdType' => $type->Id,
                    ];
                }
                foreach ($rounds as $round){
                    $roundModel = new Competitionrounds();
                    $roundModel->scenario = Competitionrounds::SCENARIO_UPLOAD;
                    $roundModel->attributes = $round;
                    if(!$roundModel->save()){
                        $errors = $roundModel->viewErrors();
                        throw new Exception('ERROR: No se pudo crear Ronda '.$errors, 92000);
                    }
                }
                $this->refresh();
            }
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    private function _createQuestions(){
        try {
            foreach ($this->questions as $qt){
                $question = new Roundquestions();
                $question->scenario = Roundquestions::SCENARIO_UPLOAD;
                $question->IdCompetition = $this->Id;
                $question->IdRoundOrder = $qt['IdCompetitionRound'];
                $question->Sort = $qt['Sort'];
                $value = $qt['Value'];
                unset($qt['IdCompetitionRound'], $qt['Value']);
                $question->attributes = $qt;
                if($question->save()){
                    $answer = new Questionanswers();
                    $answer->scenario = Questionanswers::SCENARIO_UPLOAD;
                    $answer->IdQuestion = $question->Id;
                    $answer->Sort = Questionanswers::DEFAULT_SORT_VALUE;
                    $answer->TrueValue = Questionanswers::DEFAULT_TRUE_VALUE;
                    $answer->Value = $value;
                    if(!$answer->save()){
                        $message = Yii::$app->customFunctions->getErrors($answer->errors);
                        $this->addError('uploadFile', $message);
                        throw new Exception('Error al crear Respuestas: '.$message, 94000);
                    }
                } else {
                    $message = Yii::$app->customFunctions->getErrors($question->errors);
                    $this->addError('uploadFile', $message);
                    throw new Exception('Error al crear Preguntas: '.$message, 93000);
                } 
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }
}
