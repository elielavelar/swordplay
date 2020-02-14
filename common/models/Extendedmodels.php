<?php

namespace common\models;

use Yii;
use backend\models\Settingsdetail;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;

/**
 * This is the model class for table "extendedmodels".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property int $IdNameSpace
 * @property int $IdState
 * @property string $Description
 *
 * @property Extendedmodelkeys[] $extendedmodelkeys
 * @property Settingsdetail $nameSpace
 * @property State $state
 */
class Extendedmodels extends \yii\db\ActiveRecord
{
    const _NAMESPACE_ = 'NameSpace';
    const _NAMESPACE_CODE_ = 'NESP';
    public $term = '';
    private $namespacePath = null;
    private $path = null;
    private $model = null;
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
            [['Name', 'KeyWord', 'IdNameSpace', 'IdState'], 'required'],
            [['IdNameSpace', 'IdState'], 'integer'],
            [['Description'], 'string'],
            [['Name', 'KeyWord'], 'string', 'max' => 100],
            [['KeyWord'], 'unique'],
            [['IdNameSpace'], 'exist', 'skipOnError' => true, 'targetClass' => Settingsdetail::className(), 'targetAttribute' => ['IdNameSpace' => 'id']],
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
            'IdNameSpace' => 'Espacio de Nombre',
            'IdState' => 'Estado',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtendedmodelkeys()
    {
        return $this->hasMany(Extendedmodelkeys::className(), ['IdExtendedModel' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNameSpace()
    {
        return $this->hasOne(Settingsdetail::className(), ['Id' => 'IdNameSpace']);
    }
    
    public function getNameSpaces(){
        $settings = Settingsdetail::find()
                ->joinWith('setting b')
                ->where([
                    'b.KeyWord' => self::_NAMESPACE_,
                    'b.Code' => self::_NAMESPACE_CODE_
                ])
                ->orderBy(['settingsdetail.Sort' => SORT_ASC])
                ->all();
        return ArrayHelper::map($settings, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    /**
     * @return array
     */
    public function getStates(){
        $options = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($options, 'Id', 'Name');
    }
    
    public function getModels(){
        try {
            $nameSpace = Settingsdetail::findOne(['Id' => $this->IdNameSpace]);
            $path = Yii::getAlias('@'.$nameSpace->Value);
            $this->term = empty($this->term) ? '': $this->term.'*';
            $files = glob($path.'/models/*'.$this->term.'.php');
            $result = [];
            foreach ($files as $i => $file){
                $filename = str_replace(".php", "", $file);
                $basename = StringHelper::basename($filename);
                $result[] = ['id' => $basename, 'text' =>$basename];
            }
            return ['results' => $result];
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function afterFind() {
        $this->namespacePath = $this->IdNameSpace ? $this->nameSpace->Value : null;
        if($this->namespacePath){
            $this->path = $this->namespacePath."\models\\".$this->KeyWord;
            $this->model = new $this->path ;
        }
        return parent::afterFind();
    }

    public function getModelAttributes(){
        try {
            $attributes = [];
            foreach ($this->model->attributes as $key => $attr){
                $attributes[$key] = $this->model->getAttributeLabel($key);
            }
            return $attributes;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getModelAttributeLabel($key = null){
        try {
            return $this->model->getAttributeLabel($key);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getModelAttribute($key = null){
        try {
            return $this->model->getAttribute($key);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
