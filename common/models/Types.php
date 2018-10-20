<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "types".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $Code
 * @property int $IdState
 * @property string $Description
 *
 * @property States $state
 */
class Types extends \yii\db\ActiveRecord
{
    
    const STATUS_ACTIVE = 'ACT';
    const STATUS_INACTIVE = 'INA';
    
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_SEARCH = 'search';
    const SCENARIO_UPDATE = 'update';
    
    public $create ;
    public $update ;
    public $delete ;
    public $view ;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'types';
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = ['Id','Name','KeyWord','Code'
            ,'IdState','Description'
        ];
        $scenarios[self::SCENARIO_UPDATE] = $scenarios[self::SCENARIO_SEARCH];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'Code', 'IdState'], 'required'],
            [['IdState'], 'integer'],
            [['Description'], 'string','max'=>1000],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 30],
            [['KeyWord', 'Code'], 'unique', 'targetAttribute' => ['KeyWord', 'Code'], 'message' => 'The combination of Key Word and Code has already been taken.'],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['IdState' => 'Id']],
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
            'KeyWord' => 'Llave',
            'Code' => 'Código',
            'IdState' => 'Estado',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(States::className(), ['Id' => 'IdState']);
    }
    
    public function afterFind() {
        if($this->scenario == self::SCENARIO_DEFAULT){
            #$this->create = \Yii::$app->customFunctions->userCan(self::tableName().'Create');
            #$this->update = \Yii::$app->customFunctions->userCan(self::tableName().'Update');
            #$this->delete = \Yii::$app->customFunctions->userCan(self::tableName().'Delete');
            #$this->view = \Yii::$app->customFunctions->userCan(self::tableName().'View');
        }
        return parent::afterFind();
    }
}
