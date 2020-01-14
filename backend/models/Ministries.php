<?php

namespace backend\models;

use Yii;
use common\models\State;
use common\models\Servicecentres;
use common\models\Type;

use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use backend\models\Ministryperiods;
use backend\models\Ministryservicecentres;
use backend\models\Ministryprofiles;

use Exception;

/**
 * This is the model class for table "ministries".
 *
 * @property int $Id
 * @property string $Name
 * @property string $Code
 * @property int $IdState
 * @property int $IdType
 * @property int $IdValidityType
 * @property int $IdEnvironmentType
 * @property int $IdPeriodType
 * @property string $Description
 *
 * @property State $state
 * @property Type $type
 * @property Type $validityType
 * @property Type $environmentType
 * @property Type $periodType
 * @property Ministryperiods[] $ministryperiods
 * @property Ministryservicecentres[] $ministryservicecentres
 * @property Ministryprofiles[] $ministryprofiles
 * @property Servicecentres[] $serviceCentres
 */
class Ministries extends \yii\db\ActiveRecord
{
    
    const STATUS_ACTIVE = 'ACT'; 
    const STATUS_INACTIVE = 'INA';
    
    const TYPE_ENVIRONMENT_GLOBAL = 'GLBL';
    const TYPE_ENVIRONMENT_NATIONAL = 'NATL';
    const TYPE_ENVIRONMENT_LOCAL = 'LOCL';
    
    const TYPE_VALIDITY_PERMANENT = 'PERMT';
    const TYPE_VALIDITY_ROTATIVE = 'ROTV';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ministries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'Code', 'IdState', 'IdType', 'IdValidityType','IdEnvironmentType'], 'required'],
            [['IdState', 'IdType', 'IdValidityType','IdEnvironmentType','IdPeriodType'], 'integer'],
            [['Description'], 'string'],
            [['Name'], 'string', 'max' => 100],
            [['Code'], 'string', 'max' => 20],
            [['Code'], 'unique'],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'Id']],
            [['IdValidityType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdValidityType' => 'Id']],
            [['IdEnvironmentType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdEnvironmentType' => 'Id']],
            [['IdPeriodType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdPeriodType' => 'Id']],
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
            'Code' => 'Código',
            'IdState' => 'Estado',
            'IdType' => 'Tipo',
            'IdValidityType' => 'Tipo de Vigencia',
            'IdEnvironmentType' => 'Tipo de Ámbito',
            'IdPeriodType' => 'Tipo de Periodo',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        $model = State::find()->where(['KeyWord' => StringHelper::basename(self::class)])->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdType']);
    }

    public function getTypes(){
        $model = Type::find()
                ->joinWith('state b')
                ->where([
                    'type.KeyWord' => StringHelper::basename(self::class),
                    'b.Code' => Type::STATUS_ACTIVE,
                ])
                ->orderBy(['type.Id' => SORT_ASC])
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValidityType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdValidityType']);
    }
    
    public function getValidityTypes(){
        $model = Type::find()
                ->joinWith('state b')
                ->where([
                    'type.KeyWord' => StringHelper::basename(self::class).'Validity',
                    'b.Code' => Type::STATUS_ACTIVE,
                ])
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvironmentType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdEnvironmentType']);
    }
    
    public function getEnvironmentTypes(){
        $model = Type::find()
                ->joinWith('state b')
                ->where([
                    'type.KeyWord' => StringHelper::basename(self::class).'Environment',
                    'b.Code' => Type::STATUS_ACTIVE,
                ])
                ->orderBy(['type.Id' => SORT_ASC])
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdPeriodType']);
    }
    
    public function getPeriodTypes(){
        $model = Type::find()
                ->joinWith('state b')
                ->where([
                    'type.KeyWord' => StringHelper::basename(self::class).'Period',
                    'b.Code' => Type::STATUS_ACTIVE,
                ])
                ->orderBy(['type.Id' => SORT_ASC])
                ->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryperiods()
    {
        return $this->hasMany(Ministryperiods::className(), ['IdMinistry' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryprofiles()
    {
        return $this->hasMany(Ministryprofiles::className(), ['IdMinistry' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryservicecentres()
    {
        return $this->hasMany(Ministryservicecentres::className(), ['IdMinistry' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceCentres()
    {
        return $this->hasMany(Servicecentres::className(), ['Id' => 'IdServiceCentre'])->viaTable('ministryservicecentres', ['IdMinistry' => 'Id']);
    }
}
