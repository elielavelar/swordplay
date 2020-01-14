<?php

namespace backend\models;

use Yii;
use backend\models\Ministryperiods;
use backend\models\Ministries;
use backend\models\Ministryvotingballot;
use common\models\Catalogdetails;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use backend\models\Settingsdetail;
use Exception;

/**
 * This is the model class for table "ministryperiodvoting".
 *
 * @property int $Id
 * @property int $IdMinistryPeriod
 * @property int $IdState
 * @property string $ProcessDate
 * @property int $TotalVotingBallot
 * @property string $Description
 *
 * @property Ministryperiods $ministryPeriod
 * @property State $state
 * @property Ministryperiodvotingcandidates[] $ministryperiodvotingcandidates
 * @property Ministryvotingballot[] $ministryvotingballots
 */
class Ministryperiodvoting extends \yii\db\ActiveRecord
{
    
    const STATUS_INACTIVE = 'INA';
    const STATUS_OPENED = 'OPN';
    const STATUS_CLOSE = 'CLS';
    
    const TIME_SETTING = 'TIME';
    
    private $createBallots = false;
    public $periodName = null;
    public $refreshTime = 20000;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ministryperiodvoting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdMinistryPeriod', 'IdState', 'ProcessDate'], 'required'],
            [['IdMinistryPeriod', 'IdState', 'TotalVotingBallot'], 'integer'],
            [['ProcessDate'], 'safe'],
            [['Description'], 'string'],
            [['IdMinistryPeriod'], 'exist', 'skipOnError' => true, 'targetClass' => Ministryperiods::className(), 'targetAttribute' => ['IdMinistryPeriod' => 'id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdMinistryPeriod' => 'Periodo Ministerio',
            'IdState' => 'Estado',
            'ProcessDate' => 'Fecha Proceso',
            'TotalVotingBallot' => 'Total Boletas',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryPeriod()
    {
        return $this->hasOne(Ministryperiods::className(), ['id' => 'IdMinistryPeriod']);
    }
    
    public function getAllMinistryPeriods(){
        $model = Ministryperiods::find()
                ->select(['ministryperiods.Id', "CONCAT(c.Name,' ', ministryperiods.Name) Name"])
                ->innerJoin('ministryservicecentres b','b.Id = ministryperiods.IdMinistryServiceCentre')
                ->innerJoin('ministries c', 'c.Id = b.IdMinistry')
                ->innerJoin('state d','d.Id = c.IdState')
                ->where([
                    'd.Code' => Ministries::STATUS_ACTIVE,
                ])->asArray()
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }

    public function getMinistryPeriods(){
        $model = Ministryperiods::find()
                ->select(['ministryperiods.Id', "CONCAT(c.Name,' ', ministryperiods.Name) Name"])
                ->innerJoin('ministryservicecentres b','b.Id = ministryperiods.IdMinistryServiceCentre')
                ->innerJoin('ministries c', 'c.Id = b.IdMinistry')
                ->innerJoin('state d','d.Id = c.IdState')
                ->where([
                    'd.Code' => Ministries::STATUS_ACTIVE,
                ])
                ->asArray()
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'IdState']);
    }
    
    public function getStates(){
        $model = State::findAll([
            'KeyWord' => StringHelper::basename(self::class),
        ]);
        return ArrayHelper::map($model, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryperiodvotingcandidates()
    {
        return $this->hasMany(Ministryperiodvotingcandidates::className(), ['IdVoting' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryvotingballots()
    {
        return $this->hasMany(Ministryvotingballot::className(), ['IdVoting' => 'id']);
    }
    
    public function afterFind() {
        $this->periodName = $this->IdMinistryPeriod ? ($this->ministryPeriod->IdMinistryServiceCentre ? ( $this->ministryPeriod->ministryServiceCentre->IdMinistry ? $this->ministryPeriod->ministryServiceCentre->ministry->Name.' ' : '') : '').$this->ministryPeriod->Name : "";
        $this->ProcessDate = $this->ProcessDate ? \Yii::$app->formatter->asDate($this->ProcessDate, 'php:d-m-Y') : $this->ProcessDate;
        return parent::afterFind();
    }
    
    public function beforeSave($insert) {
        $this->createBallots = $this->isNewRecord;
        $this->ProcessDate = $this->ProcessDate ? \Yii::$app->getFormatter()->asDate($this->ProcessDate,'php:Y-m-d') : $this->ProcessDate;
        return parent::beforeSave($insert);
    }


    public function afterSave($insert, $changedAttributes) {
        try {
            ($this->createBallots ? $this->_createBallots() : null);
        } catch (Exception $ex) {
            throw $ex;
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    private function _createBallots(){
        $transaction = $this->getDb()->beginTransaction();
        try {
            for($i = 1; $i <= $this->TotalVotingBallot; $i++){
                $model = new Ministryvotingballot();
                $model->IdState = $model->getDefaultState();
                $model->IdVoting = $this->Id;
                $model->Number = $i;
                $model->save();
            }
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }
    
    public function getBallotsProcessed(){
        try {
            return Ministryvotingballot::find()
                    ->joinWith('state b')
                    ->where('b.Code != :code', [':code' => Ministryvotingballot::STATUS_INACTIVE])
                    ->count('ministryvotingballot.Id')
                    ;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getBallotsAnnulled(){
        try {
            return Ministryvotingballot::find()
                    ->joinWith('state b')
                    ->where('b.Code = :code', [':code' => Ministryvotingballot::STATUS_ANNULED])
                    ->count('ministryvotingballot.Id')
                    ;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getRefreshTime(){
        try {
            $settings = Settingsdetail::find()
                    ->joinWith('setting b')
                    ->where([
                        'b.KeyWord' => StringHelper::basename(self::class),
                        'b.Code' => self::TIME_SETTING,
                    ])->one();
            $this->refreshTime = !empty($settings) ? $settings->Value : $this->refreshTime;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getElectedCandidates(){
        try {
            $model = self::find()
                    ->select(['e.Id','f.Name'])
                    ->innerJoin(Ministryperiods::tableName().' b', 'b.Id = '.self::tableName().'.IdMinistryPeriod')
                    ->innerJoin(Ministryservicecentres::tableName().' c','c.Id = b.IdMinistryServiceCentre')
                    ->innerJoin(Ministries::tableName().' d','d.Id = c.IdMinistry')
                    ->innerJoin(Ministryprofiles::tableName().' e','e.IdMinistry = d.Id')
                    ->innerJoin(Catalogdetails::tableName().' f','f.Id = e.IdProfile')
                    ->orderBy(['e.Sort' => SORT_ASC])
                    ->asArray()
                    ->all();
            print_r($model); die();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
}
