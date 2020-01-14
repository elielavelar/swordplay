<?php

namespace backend\models;

use Yii;
use backend\models\Ministryperiodvoting;
use backend\models\Ministryprofiles;
use backend\models\Ministryperiodvotingcandidates;
use backend\models\Ministryvotingballotvote;
use common\models\State;
use common\models\User;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "ministryvotingballot".
 *
 * @property int $Id
 * @property int $IdVoting
 * @property int $IdState
 * @property int $Number
 * @property int $IdUserCreate
 * @property int $IdUserUpdate
 *
 * @property State $state
 * @property User $userCreate
 * @property User $userUpdate
 * @property Ministryperiodvoting $voting
 * @property Ministryvotingballotvote[] $ministryvotingballotvotes
 */
class Ministryvotingballot extends \yii\db\ActiveRecord
{
    private $transaction = null;
    const STATUS_INACTIVE = 'INA';
    const STATUS_PROCESSED = 'PRC';
    const STATUS_ANNULED = 'ANU';
    
    public $candidates = [];
    public $profiles = [];
    public $votes = [];
    public $userCreateName = null;
    public $userUpdateName = null;
    
    public $rangeStart = null;
    public $rangeEnd = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ministryvotingballot';
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdVoting', 'Number'], 'required'],
            [['IdVoting', 'IdState', 'Number','IdUserCreate','IdUserUpdate'], 'integer'],
            [['IdVoting', 'Number'], 'unique', 'targetAttribute' => ['IdVoting', 'Number']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdState'], 'default', 'value' => State::findOne(['KeyWord' => StringHelper::basename(self::class),'Code' => self::STATUS_INACTIVE])->Id],
            [['IdVoting'], 'exist', 'skipOnError' => true, 'targetClass' => Ministryperiodvoting::className(), 'targetAttribute' => ['IdVoting' => 'Id']],
            [['IdUserCreate'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdUserCreate' => 'Id']],
            [['IdUserUpdate'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdUserUpdate' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdVoting' => 'Id Voting',
            'IdState' => 'Estado',
            'Number' => 'Número',
            'IdUserCreate' => 'Usuario Creación',
            'IdUserUpdate' => 'Usuario Actualización',
            'rangeStart' => 'Boleta Inicial',
            'rangeEnd' => 'Boleta Final',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCreate()
    {
        return $this->hasOne(User::className(), ['Id' => 'IdUserCreate']);
    }
    
    public function getUsers(){
        $model = User::find()
                    ->joinWith('state b')
                    ->where(['b.Code' => User::STATE_ACTIVE])
                ->all();
        return ArrayHelper::map($model, 'Id', 'DisplayName');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserUpdate()
    {
        return $this->hasOne(User::className(), ['Id' => 'IdUserUpdate']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        $model = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    public function getDefaultState(){
        return State::findOne(['KeyWord' => StringHelper::basename(self::class),'Code' => self::STATUS_INACTIVE])->Id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoting()
    {
        return $this->hasOne(Ministryperiodvoting::className(), ['Id' => 'IdVoting']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryvotingballotvotes()
    {
        return $this->hasMany(Ministryvotingballotvote::className(), ['IdVotingBallot' => 'Id']);
    }
    
    public function getCandidates(){
        try {
            $this->candidates = Ministryperiodvotingcandidates::find()
                    ->where([
                        'IdVoting' => $this->IdVoting,
                    ])
                    ->orderBy(['Sort' => SORT_ASC])
                    ->all();
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getProfiles(){
        try {
            $this->profiles = Ministryprofiles::find()
                    ->where(['ministryprofiles.IdMinistry' => $this->voting->ministryPeriod->ministryServiceCentre->IdMinistry])
                    ->orderBy(['ministryprofiles.Sort' => SORT_ASC])
                    ->all();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function beforeSave($insert) {
        if($this->isNewRecord){
            $this->IdUserCreate = Yii::$app->user->getIdentity()->getId();
        } else {
            $this->IdUserUpdate = Yii::$app->user->getId();
        }
        if(!empty($this->votes)){
            $this->IdState = State::findOne(['KeyWord' => StringHelper::basename(self::class),'Code' => self::STATUS_PROCESSED])->Id;
            $this->transaction = $this->getDb()->beginTransaction();
        }
        return parent::beforeSave($insert);
    }
    
    public function afterFind() {
        $this->userCreateName = $this->IdUserCreate ? $this->userCreate->DisplayName : null;
        $this->userUpdateName = $this->IdUserUpdate ? $this->userUpdate->DisplayName : null;
        return parent::afterFind();
    }
    
    public function afterSave($insert, $changedAttributes) {
        if(!empty($this->votes)){
            $this->_saveVotes();
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    private function _saveVotes(){
        try {
            foreach ($this->votes as $value){
                $vote = new Ministryvotingballotvote();
                $vote->IdVotingBallot = $this->Id;
                $vote->IdCandidate = $value['IdCandidate'];
                $vote->IdMinistryProfile = $value['IdProfile'];
                if(!$vote->save()){
                    $message = Yii::$app->customFunctions->getErrors($vote->errors);
                    throw new Exception($message, 93099);
                }
            }
            $this->transaction->commit();
        } catch (Exception $ex) {
            ($this->transaction ? $this->transaction->rollBack() : null);
            throw $ex;
        } 
    }
    
    public function voidBallot(){
        try {
            $this->IdState = State::findOne(['KeyWord' => StringHelper::basename(self::class),'Code' => self::STATUS_ANNULED])->Id;
            if(!$this->save()){
                $message = Yii::$app->customFunctions->getErrors($this->errors);
                throw new Exception($message, 920001);
            } else {
                $this->refresh();
                return [
                    'success' => true,
                    'message' => 'Boleta '.$this->state->Name.' Exitosamente',
                ];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getVotes(){
        try {
            $table = "<table class='table table-hover table-bordered' style='width:100%'>"
                    . "<tbody>";
            $this->getProfiles();
            $votes = [];
            foreach($this->profiles as $prof){
                $votes[$prof->Id] = [
                    'NAME' => ($prof->CustomName ? $prof->CustomName : $prof->profile->Name),
                    'CANDIDATE' => null
                ];
            }
            foreach ($this->ministryvotingballotvotes as $vote){
                $votes[$vote->IdMinistryProfile]['CANDIDATE'] = $vote;
            }
            foreach ($votes as $key => $vote){
                $table .= "<tr>";
                if($vote['CANDIDATE']){
                    $candidate = $vote['CANDIDATE'];
                    $table .= "<td>".Html::img($candidate->candidate->member->IdAttachmentPicture ? $candidate->candidate->member->path : '@web/img/avatar.png', ['alt' => 'Miembro', 'class' => 'img-fluid', 'style' => 'width: 65px','id' => 'img-candidate-'.$candidate->candidate->Id])
                        . "</td>"
                        . "<td><b>".($candidate->candidate->member->displayName). "</b></td>";
                } else {
                    $table .= "<td>".Html::img('@web/img/avatar.png', ['alt' => 'Miembro', 'class' => 'img-fluid', 'style' => 'width: 65px','id' => 'img-candidate-'.$key])
                        . "</td>"
                        . "<td><b>-</b></td>";
                }
                $table .= "<td><b>".($vote['NAME'])."</b></td>";"</tr>";
            }
            $table .= "</tbody>"
                    . "</table>";
            return $table;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
