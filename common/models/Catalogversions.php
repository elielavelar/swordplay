<?php

namespace common\models;

use Yii;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "catalogversions".
 *
 * @property int $Id
 * @property string $Version
 * @property int $IdCatalog
 * @property int $IdState
 * @property string $Description
 *
 * @property Catalogdetails[] $catalogdetails
 * @property Catalogs $catalog
 * @property State $state
 */
class Catalogversions extends \yii\db\ActiveRecord
{
    
    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catalogversions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdCatalog', 'IdState'], 'required'],
            [['IdCatalog', 'IdState'], 'integer'],
            [['Description'], 'string'],
            [['Version'], 'string', 'max' => 10],
            [['IdCatalog', 'Version'], 'unique', 'targetAttribute' => ['IdCatalog', 'Version']],
            [['IdCatalog'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['IdCatalog' => 'Id']],
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
            'Version' => 'Versión',
            'IdCatalog' => 'Catálogo',
            'IdState' => 'Estado',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogdetails()
    {
        return $this->hasMany(Catalogdetails::className(), ['IdCatalogVersion' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(Catalogs::className(), ['Id' => 'IdCatalog']);
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
}
