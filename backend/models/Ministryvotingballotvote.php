<?php

namespace backend\models;

use Yii;
use backend\models\Ministryperiodvotingcandidates;
use backend\models\Ministryprofiles;
use backend\models\Ministryvotingballot;
use common\models\Member;
use yii\helpers\Html;
use yii\db\Query;
use Exception;

/**
 * This is the model class for table "ministryvotingballotvote".
 *
 * @property int $Id
 * @property int $IdVotingBallot
 * @property int $IdCandidate
 * @property int $IdMinistryProfile
 *
 * @property Ministryperiodvotingcandidates $candidate
 * @property Ministryprofiles $ministryProfile
 * @property Ministryvotingballot $votingBallot
 */
class Ministryvotingballotvote extends \yii\db\ActiveRecord
{
    public $IdVoting = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ministryvotingballotvote';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdVotingBallot', 'IdCandidate', 'IdMinistryProfile'], 'required'],
            [['IdVotingBallot', 'IdCandidate', 'IdMinistryProfile'], 'integer'],
            [['IdCandidate'], 'exist', 'skipOnError' => true, 'targetClass' => Ministryperiodvotingcandidates::className(), 'targetAttribute' => ['IdCandidate' => 'Id']],
            [['IdMinistryProfile'], 'exist', 'skipOnError' => true, 'targetClass' => Ministryprofiles::className(), 'targetAttribute' => ['IdMinistryProfile' => 'Id']],
            [['IdVotingBallot'], 'exist', 'skipOnError' => true, 'targetClass' => Ministryvotingballot::className(), 'targetAttribute' => ['IdVotingBallot' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdVotingBallot' => 'Id Voting Ballot',
            'IdCandidate' => 'Id Candidate',
            'IdMinistryProfile' => 'Id Ministry Profile',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandidate()
    {
        return $this->hasOne(Ministryperiodvotingcandidates::className(), ['Id' => 'IdCandidate']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryProfile()
    {
        return $this->hasOne(Ministryprofiles::className(), ['Id' => 'IdMinistryProfile']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingBallot()
    {
        return $this->hasOne(Ministryvotingballot::className(), ['Id' => 'IdVotingBallot']);
    }
    
    public function getVotes(){
        try {
            $query = new Query();
            $query->select([
                'a.IdCandidate', 'a.IdMinistryProfile', 'i.Name ProfileName', "CONCAT(g.FirstName, ' ', g.FirstLastName) MemberName",
                'g.Id','count(a.Id) VotesNumber',
            ]);
            $query->from(self::tableName()." a")
                    ->innerJoin('ministryvotingballot b', 'b.Id = a.IdVotingBallot')
                    ->innerJoin('ministryperiodvoting c', 'c.Id = b.IdVoting')
                    ->innerJoin('ministryperiodvotingcandidates d', 'd.Id = a.IdCandidate AND d.IdVoting = c.Id')
                    ->innerJoin('ministryperiods e', 'e.Id = c.IdMinistryPeriod')
                    ->innerJoin('ministryservicecentres f', 'f.Id = e.IdMinistryServiceCentre')
                    ->innerJoin('member g', 'g.Id = d.IdMember')
                    ->innerJoin('ministryprofiles h', 'h.Id = a.IdMinistryProfile AND h.IdMinistry = f.IdMinistry')
                    ->innerJoin('catalogdetails i', 'i.Id = h.IdProfile')
                    ->innerJoin('state j', 'j.Id = b.IdState')
                    ->where([
                        'b.IdVoting' => $this->IdVoting,
                        'j.Code' => Ministryvotingballot::STATUS_PROCESSED,
                    ])
                    ->groupBy(['a.IdCandidate', 'a.IdMinistryProfile', 'i.Name', "CONCAT(g.FirstName, ' ', g.FirstLastName)",'g.Id'])
                    ->orderBy(['a.IdMinistryProfile' => SORT_ASC, 'COUNT(a.Id)' => SORT_DESC,'g.Id' => SORT_ASC]);
            $results = $query->all();
            return $results;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getTableVotes(){
        try {
            $result = $this->getVotes();
            $table = [];
            $voting = Ministryperiodvoting::findOne(['Id' => $this->IdVoting]);
            $ballot = new Ministryvotingballot();
            $ballot->IdVoting = $this->IdVoting;
            #$candidates = $ballot->getCandidates();
            $ballot->getProfiles();
            $profiles = $ballot->profiles;
            
            
            foreach($profiles as $profile){
                $table[$profile->Id] = [];
            }
            $classTop = 'bg-green';
            $c = 0;
            $cVotes = 0;
            $top = '';
            $lastProfile = 0;
            foreach($result as $vote){
                if($lastProfile != (int)$vote['IdMinistryProfile']){
                    $lastProfile = (int)$vote['IdMinistryProfile'];
                    $c = 0;
                }
                $member = Member::findOne(['Id' => $vote['Id']]);
                if($c == 0){
                    $top = $classTop;
                    $cVotes = (int) $vote['VotesNumber'];
                    $lastProfile = (int)$vote['IdMinistryProfile'];
                } else {
                    $top = ($cVotes == (int) $vote['VotesNumber']) ? $classTop : '';
                }
                $table[$vote['IdMinistryProfile']][] = "<div class='box box-default $top'>"
                        . "<div class='box-body'>"
                        . "<span class=''>"
                        . Html::img($member->IdAttachmentPicture ? $member->path : '@web/img/avatar.png', ['title' => $member->displayName, 'alt' => 'Miembro', 'class' => '', 'style' => 'width: 100%'])
                        . "</span>"
                        . "<h5 class='box-title'>$member->displayName</h5>"
                        . "<span class='info-box-number'>Votos: <label>".((int)$vote["VotesNumber"])."</label></span>"
                        . "</div>"
                        . "</div>";
                $c++;
            }
            $processed = $voting->getBallotsProcessed();
            $annulled = $voting->getBallotsAnnulled();
            $response = [
                'table' => $table,
                'ballots' => [
                    'processed' => $processed,
                    'pendent' => ($voting->TotalVotingBallot - $processed),
                    'annulled' => $annulled,
                    'total' => $voting->TotalVotingBallot,
                ],
            ];
            return $response;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
