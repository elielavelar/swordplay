<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "fieldscatalogs".
 *
 * @property int $Id
 * @property int $IdField
 * @property string $Name
 * @property string $Value
 * @property int $Sort
 * @property int $IdState
 * @property string $Description
 *
 * @property Extendedmodelfieldvalues[] $extendedmodelfieldvalues
 * @property Fields $field
 * @property State $state
 */
class Fieldscatalogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fieldscatalogs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdField', 'Name', 'IdState'], 'required'],
            [['IdField', 'Sort', 'IdState'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'Value'], 'string', 'max' => 100],
            [['IdField'], 'exist', 'skipOnError' => true, 'targetClass' => Fields::className(), 'targetAttribute' => ['IdField' => 'id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdField' => 'Id Field',
            'Name' => 'Name',
            'Value' => 'Value',
            'Sort' => 'Sort',
            'IdState' => 'Id State',
            'Description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelfieldvalues()
    {
        return $this->hasMany(Extendedmodelfieldvalues::className(), ['IdFieldCatalog' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Fields::className(), ['id' => 'IdField']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'IdState']);
    }
}
