<?php

namespace common\models;

use Yii;
use common\models\State;
use backend\models\Profileoptions;
use yii\helpers\ArrayHelper;
use Exception;
use backend\components\AuthorizationFunctions;

/**
 * This is the model class for table "profile".
 *
 * @property integer $Id
 * @property string $Name
 * @property string $KeyWord
 * @property string $Code
 * @property string $Description
 * @property integer $IdState
 *
 * @property State $state
 * @property Profileoptions[] $profileoptions 
 * @property User[] $users 
 */
class Profile extends \yii\db\ActiveRecord
{
    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';
    public $disabled = FALSE;
    private $auth;
    
    const CONTROLLER_NAME = 'profile';

    private $_profile;
    public $profilesetting = NULL;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }
    
    public function __construct($config = array()) {
        #$this->auth = \Yii::$app->authManager;
        $this->auth = new AuthorizationFunctions();
        return parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name','Code', 'IdState','KeyWord'], 'required','message'=>'Campo {attribute} no puede quedar vacío'],
            [['KeyWord'], 'unique','message'=>'{attribute} {value} ya existe'],
            [['Code'], 'unique','message'=>'{attribute} {value} ya existe'],
            [['IdState'], 'integer'],
            [['Code'], 'string', 'max' => 20], 
            [['IdState'], 'default','value'=>  State::findOne(['KeyWord'=>'Profile','Code'=>  self::STATE_ACTIVE])->Id],
            [['Name','KeyWord'], 'string', 'max' => 50],
            [['Description'], 'string', 'max' => 255],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
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
            'Description' => 'Descripción',
            'IdState' => 'Estado',
            'Code' => 'Código',
            'KeyWord' => 'Llave',
        ];
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
   public function getProfileoptions()
   {
       return $this->hasMany(Profileoptions::className(), ['IdProfile' => 'Id']);
   }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        try {
            $droptions = State::findAll(['KeyWord'=>'Profile']);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
   public function getUsers()
   {
       return $this->hasMany(User::className(), ['IdProfile' => 'Id']);
   }
   
    public function beforeSave($insert) {
        try {
            if(!$this->isNewRecord){
                $this->_validatePermission();
                $this->_applyStateChanges();
                if(!empty(array_diff($this->attributes, $this->oldAttributes))){
                    $this->_updateProfile();
                }
            } 
        } catch (Exception $ex) {
            throw $ex;
        }
        return parent::beforeSave($insert);
    }
   
    public function afterSave($insert, $changedAttributes) {
        $this->_validatePermission();
        $this->_create();
        return parent::afterSave($insert, $changedAttributes);
    }
   
   private function _create(){
       if($this->_profile == NULL){
           return $this->auth->createRole($this->KeyWord, $this->Description);
       }
   }
   
   public function afterFind() {
       $this->disabled = !$this->isNewRecord ? $this->state->Code == self::STATE_INACTIVE:FALSE;
       return parent::afterFind();
   }


   public function afterDelete() {
       $this->auth->removeAllAssignments($this->KeyWord); 
       return parent::afterDelete();
   }
   
   private function _applyStateChanges(){
       try {
           switch ($this->state->Code) {
               case self::STATE_ACTIVE:
                   $this->_create();
                   $this->_applyPermissions();
                   break;
               case self::STATE_INACTIVE:
                   $this->auth->removeAllAssignments($this->KeyWord);
                   break;
               default:
                   break;
           }
       } catch (Exception $exc) {
           throw $exc;
       }
   }
   
   private function _validatePermission(){
       try {
           $this->_profile = $this->auth->getRole($this->KeyWord);
       } catch (Exception $ex) {
           throw $ex;
       }
       
   }
   
   private function _applyPermissions(){
       try {
           $profileoption = new Profileoptions();
           $profileoption->IdProfile = $this->Id;
           $profileoption->permissions = $this->profilesetting;
           $profileoption->_setPermissions();
           
       } catch (Exception $ex) {
           throw $ex;
       }
   }
   
   private function _updateProfile(){
       try {
           $this->_validatePermission();
           if($this->_profile){
               $this->_profile->name = $this->KeyWord;
               $this->_profile->description = $this->Description;
               return $this->auth->updatePermission($this->KeyWord, $this->_profile);
           } else {
               $this->_create();
           }
       } catch (Exception $ex) {
           throw $ex;
       }
   }
   
}
