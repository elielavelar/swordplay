<?php

namespace backend\models;

use Yii;
use backend\models\Ministries;
use common\models\State;
use common\models\Servicecentres;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

use common\models\Catalogs;
use common\models\Catalogversions;
use common\models\Catalogdetails;
use common\models\Catalogdetailvalues;
use Exception;

/**
 * This is the model class for table "ministryservicecentres".
 *
 * @property int $Id
 * @property int $IdServiceCentre
 * @property int $IdMinistry
 * @property int $IdPeriodValue
 * @property int $IdState
 * @property int $ApplyVotation
 *
 * @property Ministryperiods[] $ministryperiods
 * @property Ministries $ministry
 * @property Servicecentres $servicecentre
 * @property State $state
 * @property Catalogdetailvalues $periodValue
 */
class Ministryservicecentres extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'ACT';
    const STATUS_INACTIVE = 'INA';
    
    const APPLY_VOTATION_ENABLED = 1;
    const APPLY_VOTATION_DISABLED = 0;
    
    public $serviceCentreName = null;
    public $periodValueName = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ministryservicecentres';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdServiceCentre', 'IdMinistry', 'IdState'], 'required'],
            [['IdServiceCentre', 'IdMinistry', 'IdState','IdPeriodValue'], 'integer'],
            [['IdServiceCentre', 'IdMinistry'], 'unique', 'targetAttribute' => ['IdServiceCentre', 'IdMinistry']],
            [['IdServiceCentre'], 'exist', 'skipOnError' => true, 'targetClass' => Servicecentres::className(), 'targetAttribute' => ['IdServiceCentre' => 'Id']],
            [['IdMinistry'], 'exist', 'skipOnError' => true, 'targetClass' => Ministries::className(), 'targetAttribute' => ['IdMinistry' => 'Id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdPeriodValue'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogdetailvalues::class, 'targetAttribute' => ['IdPeriodValue' => 'Id']],
            [['ApplyVotation'],'in','range'=>[self::APPLY_VOTATION_DISABLED, self::APPLY_VOTATION_ENABLED]],
            ['ApplyVotation','default','value' => self::APPLY_VOTATION_DISABLED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdServiceCentre' => 'Filial',
            'IdMinistry' => 'Ministerio',
            'IdPeriodValue' => 'Vigencia de Periodo',
            'IdState' => 'Estado',
            'serviceCentreName' => 'Filial',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryperiods()
    {
        return $this->hasMany(Ministryperiods::className(), ['IdMinistryServiceCentre' => 'Id']);
    }
    
    public function getState(){
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        $model = State::find()->where(['KeyWord' => StringHelper::basename(self::class)])->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    public function getMinistry(){
        return $this->hasOne(Ministries::className(), ['Id' => 'IdMinistry']);
    }
    
    public function getMinistries(){
        $model = Ministries::find()
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    public function getServicecentre(){
        return $this->hasOne(Servicecentres::className(), ['Id' => 'IdServiceCentre']);
    }
    
    public function getServicecentres(){
        $model = Servicecentres::find()
                ->joinWith('state b')
                ->where(['b.Code' => Servicecentres::STATE_ACTIVE])
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodValue()
    {
        return $this->hasOne(Catalogdetailvalues::class, ['Id' => 'IdPeriodValue']);
    }
    
    public function getPeriodValues(){
        $typeCode = $this->IdMinistry ? ($this->ministry->IdPeriodType ? $this->ministry->periodType->Code : null ) : null;
        $model = Catalogdetailvalues::find()
                ->select(['catalogdetailvalues.Id', 'b.Name'])
                ->innerJoin('catalogdetails b','b.Id = catalogdetailvalues.IdCatalogDetail')
                ->innerJoin('catalogversions c','c.Id = b.IdCatalogVersion')
                ->innerJoin('catalogs d','d.Id = c.IdCatalog')
                ->innerJoin('state e','e.Id = c.IdState')
                ->innerJoin('state f','f.Id = d.IdState')
                ->where([
                    'b.KeyWord' => $typeCode,
                    'e.Code' => Catalogversions::STATE_ACTIVE,
                    'f.Code' => Catalogs::STATUS_ACTIVE,
                ])
                ->orderBy(['b.Id' => SORT_ASC])
                ->asArray()
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    public function getPeriodValuesArrayList(){
        $values = $this->getPeriodValues();
        $result = [];
        foreach ($values as $key => $value){
            $result[] = ['id' => $key, 'text' => $value];
        }
        return $result;
    }
    
    public function afterFind() {
        $this->serviceCentreName = $this->IdServiceCentre ? $this->servicecentre->Name : null;
        $this->periodValueName = $this->IdPeriodValue ? $this->periodValue->IdCatalogDetail ? $this->periodValue->catalogDetail->Name : null: null;
        return parent::afterFind();
    }
    
    public function beforeSave($insert) {
        try {
            if(empty($this->IdPeriodValue)){
                if($this->IdMinistry ? $this->ministry->IdValidityType ? $this->ministry->validityType->Code == Ministries::TYPE_VALIDITY_ROTATIVE : false : false){
                    $message = 'Vigencia de Periodo es Requerido';
                    $this->addError('IdPeriodValue', $message);
                    return false;
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return parent::beforeSave($insert);
    }
    
    public function validatePeriod(){
        return ($this->IdMinistry ? $this->ministry->IdValidityType ? $this->ministry->validityType->Code == Ministries::TYPE_VALIDITY_ROTATIVE : false : false);
    }
}
