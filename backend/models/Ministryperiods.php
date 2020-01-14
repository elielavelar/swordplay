<?php

namespace backend\models;

use Yii;
use backend\models\Ministryservicecentres;
use common\models\Type;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ministryperiods".
 *
 * @property int $Id
 * @property int $IdMinistryServiceCentre
 * @property string $Name
 * @property int $IdState
 * @property string $StartDate
 * @property string $EndDate
 * @property string $Description
 *
 * @property Ministryservicecentres $ministryServiceCentre
 * @property State $state
 * @property Ministryperiodvoting[] $ministryperiodvotings 
 */
class Ministryperiods extends \yii\db\ActiveRecord {

    const STATUS_CURRENT = 'VGT';
    const STATUS_INACTIVE = 'INA';
    const STATUS_CLOSED = 'CLS';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'ministryperiods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['IdMinistryServiceCentre','Name', 'IdState', 'StartDate', 'EndDate'], 'required'],
            [['IdMinistryServiceCentre', 'IdState'], 'integer'],
            [['StartDate', 'EndDate'], 'safe'],
            [['Name'], 'string', 'max' => 100],
            [['Description'], 'string'],
            [['IdMinistryServiceCentre'], 'exist', 'skipOnError' => true, 'targetClass' => Ministryservicecentres::className(), 'targetAttribute' => ['IdMinistryServiceCentre' => 'Id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'Id' => 'ID',
            'IdMinistryServiceCentre' => 'Id Ministry Service Centre',
            'Name' => 'Nombre',
            'IdState' => 'Estado',
            'StartDate' => 'Fecha Inicio',
            'EndDate' => 'Fecha Fin',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryServiceCentre() {
        return $this->hasOne(Ministryservicecentres::className(), ['Id' => 'IdMinistryServiceCentre']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState() {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }

    public function getStates() {
        $model = State::find()
                        ->where([
                            'KeyWord' => StringHelper::basename(self::class),
                        ])->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistryperiodvotings() {
        return $this->hasMany(Ministryperiodvoting::className(), ['IdMinistryPeriod' => 'Id']);
    }

    public function beforeSave($insert) {
        $this->StartDate = $this->StartDate ? \Yii::$app->getFormatter()->asDate($this->StartDate, 'php:Y-m-d') : $this->StartDate;
        $this->EndDate = $this->StartDate ? \Yii::$app->getFormatter()->asDate($this->EndDate, 'php:Y-m-d') : $this->EndDate;
        if ($this->isNewRecord) {
            ($this->IdState ? $this->state->Code == self::STATUS_CURRENT ? ( $this->closeActivePeriod()) : null : null);
        } else {
            $this->_evaluatePeriod();
        }
        return parent::beforeSave($insert);
    }
    
    public function afterFind() {
        $this->StartDate = $this->StartDate ? \Yii::$app->getFormatter()->asDate($this->StartDate, 'php:d-m-Y') : $this->StartDate;
        $this->EndDate = $this->StartDate ? \Yii::$app->getFormatter()->asDate($this->EndDate, 'php:d-m-Y') : $this->EndDate;
        return parent::afterFind();
    }

    private function _evaluatePeriod() {
        try {
            $model = $this->getActivePeriod();
            (!empty($model) ? $model->Id != $this->Id ? $model->closePeriod : null : null);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getActivePeriod() {
        $model = self::find()
                        ->joinWith('state b')
                        ->where([
                            'b.Code' => Ministryperiods::STATUS_CURRENT,
                            'ministryperiods.IdMinistryServiceCentre' => $this->IdMinistryServiceCentre,
                        ])->one();
        return $model;
    }

    public function closeActivePeriod() {
        try {
            $model = $this->getActivePeriod();
            $model->IdState = State::findOne([
                        'KeyWord' => StringHelper::basename(self::class),
                        'Code' => self::STATUS_CLOSED
                    ])->Id;
            if (!$model->save()) {
                $message = \Yii::$app->customFunctions->getErrors($model->errors);
                throw new Exception($message, 92099);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function closePeriod() {
        try {
            $this->IdState = State::findOne([
                        'KeyWord' => StringHelper::basename(self::class),
                        'Code' => self::STATUS_CLOSED
                    ])->Id;
            if (!$this->save()) {
                $message = \Yii::$app->customFunctions->getErrors($this->errors);
                throw new Exception($message, 92099);
            } else {
                return ['message' => 'Periodo Cerrado Exitosamente'];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
