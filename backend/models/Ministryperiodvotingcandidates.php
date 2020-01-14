<?php

namespace backend\models;

use Yii;
use backend\models\Ministryperiodvoting;
use common\models\Member;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use Exception;
/**
 * This is the model class for table "ministryperiodvotingcandidates".
 *
 * @property int $Id
 * @property int $IdVoting
 * @property int $IdMember
 * @property int $IdState
 * @property int $Sort
 *
 * @property Member $member
 * @property State $state
 * @property Ministryperiodvoting $voting
 * @property Ministryvotingballotvote[] $ministryvotingballotvotes
 */
class Ministryperiodvotingcandidates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ministryperiodvotingcandidates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdVoting', 'IdMember', 'IdState'], 'required'],
            [['IdVoting', 'IdMember', 'IdState', 'Sort'], 'integer'],
            [['IdMember'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['IdMember' => 'id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
            [['IdVoting'], 'exist', 'skipOnError' => true, 'targetClass' => Ministryperiodvoting::className(), 'targetAttribute' => ['IdVoting' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdVoting' => 'Proceso de Votación',
            'IdMember' => 'Miembro',
            'IdState' => 'Estado',
            'Sort' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'IdMember']);
    }
    
    public function getMembers(){
        $model = Member::find()
                    ->select(['member.Id',"CONCAT(member.FirstName,' ',member.FirstLastName) Name"])
                    ->innerJoin('state b','b.Id = member.IdState')
                    ->where(['b.Code' => Member::STATUS_ACTIVE])
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
        $model = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($model, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoting()
    {
        return $this->hasOne(Ministryperiodvoting::className(), ['id' => 'IdVoting']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryvotingballotvotes()
    {
        return $this->hasMany(Ministryvotingballotvote::className(), ['IdCandidate' => 'id']);
    }
}
