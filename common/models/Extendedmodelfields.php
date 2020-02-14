<?php

namespace common\models;

use Yii;
use common\models\Fields;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "extendedmodelfields".
 *
 * @property int $Id
 * @property int $IdExtendedModelKey
 * @property int $IdField
 * @property string $CustomLabel
 * @property int $Required
 * @property int $Sort
 * @property string $CssClass
 * @property int $ColSpan
 * @property int $RowSpan
 * @property string $Description
 *
 * @property Extendedmodelkeys $extendedModelKey
 * @property Fields $field
 * @property Extendedmodelfieldvalues[] $extendedmodelfieldvalues
 * @property Extendedmodelrecords[] $extendedModelRecords
 */
class Extendedmodelfields extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extendedmodelfields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdExtendedModelKey', 'IdField'], 'required'],
            [['IdExtendedModelKey', 'IdField', 'Required', 'Sort', 'ColSpan', 'RowSpan'], 'integer'],
            [['Description'], 'string'],
            [['CustomLabel', 'CssClass'], 'string', 'max' => 100],
            [['IdExtendedModelKey'], 'exist', 'skipOnError' => true, 'targetClass' => Extendedmodelkeys::className(), 'targetAttribute' => ['IdExtendedModelKey' => 'id']],
            [['IdField'], 'exist', 'skipOnError' => true, 'targetClass' => Fields::className(), 'targetAttribute' => ['IdField' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdExtendedModelKey' => 'Llave de Modelo',
            'IdField' => 'Campo',
            'CustomLabel' => 'Etiqueta Personalizada',
            'Required' => 'Requerido',
            'Sort' => 'Orden',
            'CssClass' => 'Clase CSS',
            'ColSpan' => 'Columnas',
            'RowSpan' => 'Filas',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedModelKey()
    {
        return $this->hasOne(Extendedmodelkeys::className(), ['id' => 'IdExtendedModelKey']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Fields::className(), ['id' => 'IdField']);
    }
    
    public function getFields(){
        $fields = Fields::find()
                ->joinWith('state b')
                ->where([
                    'fields.KeyWord' => $this->extendedModelKey->extendedModel->KeyWord,
                    'b.Code' => Fields::STATUS_ACTIVE,
                ])
                ->all();
        return ArrayHelper::map($fields, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelfieldvalues()
    {
        return $this->hasMany(Extendedmodelfieldvalues::className(), ['IdExtendedModelField' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedModelRecords()
    {
        return $this->hasMany(Extendedmodelrecords::className(), ['id' => 'IdExtendedModelRecord'])->viaTable('extendedmodelfieldvalues', ['IdExtendedModelField' => 'id']);
    }
}
