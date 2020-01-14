<?php

namespace common\models;

use Yii;
use common\models\Countries;
use common\models\Cities;
/**
 * This is the model class for table "departments".
 *
 * @property int $Id
 * @property string $Name
 * @property int $IdCountry
 * @property string $Description
 *
 * @property Cities[] $cities
 * @property Countries $country
 */
class Departments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'IdCountry'], 'required'],
            [['IdCountry'], 'integer'],
            [['Description'], 'string'],
            [['Name'], 'string', 'max' => 100],
            [['IdCountry'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['IdCountry' => 'id']],
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
            'IdCountry' => 'País',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(Cities::className(), ['IdDepartment' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'IdCountry']);
    }
}
