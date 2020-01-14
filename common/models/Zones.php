<?php

namespace common\models;

use Yii;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use common\models\State;

/**
 * This is the model class for table "zones".
 *
 * @property integer $Id
 * @property string $Name
 * @property string $Code
 * @property integer $IdState
 * @property string $Description
 *
 * @property Servicecentres[] $servicecentres
 * @property State $state
 */
class Zones extends \yii\db\ActiveRecord
{
    
    const STATUS_ACTIVE = 'ACT';
    const STATUS_INACTIVE = 'INA';
    
    public $create;
    public $update;
    public $delete;
    public $view;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Code', 'IdState'], 'required','message'=>'Campo {attribute} no puede quedar vacío'],
            [['IdState'], 'integer'],
            [['Description'], 'string'],
            [['Name'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 8],
            [['Code'], 'unique','message'=>'Código {value} ya existe'],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Nombre',
            'Code' => 'Código',
            'IdState' => 'Estado',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicecentres()
    {
        return $this->hasMany(Servicecentres::className(), ['IdZone' => 'Id']);
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
    public function getStates(){
        try {
            $droptions = State::findAll(['KeyWord'=>StringHelper::basename(self::className())]);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function afterFind() {
        $this->create = \Yii::$app->user->can('zoneCreate');
        $this->update = \Yii::$app->user->can('zoneUpdate');
        $this->delete = \Yii::$app->user->can('zoneDelete');
        $this->view = \Yii::$app->user->can('zoneView');
        return parent::afterFind();
    }
}
