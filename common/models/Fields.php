<?php

namespace common\models;

use Yii;
use common\models\State;
use common\models\Type;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "fields".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $Code
 * @property int $IdType
 * @property int $IdState
 * @property int $HasCatalog
 * @property string $Value
 * @property int $MultipleValue
 * @property string $Description
 *
 * @property Extendedmodelfields[] $extendedmodelfields
 * @property State $state
 * @property Type $type
 * @property Fieldscatalogs[] $fieldscatalogs
 */
class Fields extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'Code', 'IdType', 'IdState', 'HasCatalog', 'Value'], 'required'],
            [['IdType', 'IdState', 'HasCatalog', 'MultipleValue'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'KeyWord', 'Code', 'Value'], 'string', 'max' => 50],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'id']],
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
            'IdType' => 'Tipo',
            'IdState' => 'Estado',
            'HasCatalog' => 'Tiene Catálogo',
            'Value' => 'Valor',
            'MultipleValue' => 'Valores Multiples',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelfields()
    {
        return $this->hasMany(Extendedmodelfields::className(), ['IdField' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'IdState']);
    }
    
    public function getStates(){
        $options = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($options, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'IdType']);
    }
    
    public function getTypes(){
        $options = Type::find()
                ->joinWith('state b')
                ->where([
                    'type.KeyWord' => StringHelper::basename(self::class),
                    'b.Code' => Type::STATUS_ACTIVE
                ])
                ->orderBy(['type.Id' => SORT_ASC])
                ->all();
        return ArrayHelper::map($options, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldscatalogs()
    {
        return $this->hasMany(Fieldscatalogs::className(), ['IdField' => 'id']);
    }
}
