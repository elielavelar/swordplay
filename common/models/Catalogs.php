<?php

namespace common\models;
use Yii;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "catalogs".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $Code
 * @property int $IdState
 * @property string $Description
 *
 * @property State $state
 * @property Catalogversions[] $catalogversions
 */
class Catalogs extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'ACT';
    const STATUS_INACTIVE = 'INA';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catalogs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'Code', 'IdState'], 'required'],
            [['IdState'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 30],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
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
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        $droptions = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($droptions, 'Id', 'Name');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogversions()
    {
        return $this->hasMany(Catalogversions::className(), ['IdCatalog' => 'Id']);
    }
}
