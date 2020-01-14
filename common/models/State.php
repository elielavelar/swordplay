<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "state".
 *
 * @property integer $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $Code
 * @property string $Value
 * @property string $Description
 *
 * @property Type[] $types
 */
class State extends \yii\db\ActiveRecord
{
    public $create;
    public $update;
    public $delete;
    public $view;
    
    private $controller = NULL;
    const CONTROLLER_NAME = 'state';
    
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->controller = !empty(\Yii::$app->controller) ? \Yii::$app->controller->id: NULL;
    }
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'Code'], 'required'],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 10],
            [['Value'], 'string', 'max' => 20],
            [['Description'], 'string', 'max' => 1000],
            [['Code'], 'unique', 'targetAttribute' => ['KeyWord', 'Code'], 'message' => 'Ya existe el Código {value} para la llave ingresada'],
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
            'KeyWord' => 'Llave',
            'Code' => 'Código',
            'Value' => 'Valor',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasMany(Type::className(), ['IdState' => 'Id']);
    }
    
    public function afterFind() {
        if($this->controller == self::CONTROLLER_NAME ){
            $this->create = \Yii::$app->user->can(self::tableName().'Create');
            $this->update = \Yii::$app->user->can(self::tableName().'Update');
            $this->delete = \Yii::$app->user->can(self::tableName().'Delete');
            $this->view = \Yii::$app->user->can(self::tableName().'View');
        }
        return parent::afterFind();
    }
    
}
