<?php

namespace common\models;

use Yii;
use common\models\State;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "extendedmodels".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $AttributeKeyName
 * @property int $IdState
 * @property string $Description
 *
 * @property Extendedmodelfields[] $extendedmodelfields
 * @property State $state
 */
class Extendedmodels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extendedmodels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'AttributeKeyName', 'IdState'], 'required'],
            [['IdState'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'KeyWord', 'AttributeKeyName'], 'string', 'max' => 100],
            [['AttributeKeyName'], 'unique', 'targetAttribute' => ['KeyWord', 'AttributeKeyName'], 'message' => 'Ya existe el Campo {value} para la llave ingresada'],
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
            'AttributeKeyName' => 'Atributo Clave',
            'IdState' => 'Estado',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelfields()
    {
        return $this->hasMany(Extendedmodelfields::className(), ['IdExtendedModel' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }

    public function getStates(){
        $options = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($options, 'Id', 'Name');
    }
}
