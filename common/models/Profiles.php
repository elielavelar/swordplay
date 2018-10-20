<?php

namespace common\models;

use Yii;
use backend\models\Profileoptions;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use common\models\States;
use app\components\AuthorizationFunctions;

/**
 * This is the model class for table "profiles".
 *
 * @property int $Id
 * @property string $Name
 * @property string $KeyWord
 * @property int $IdState
 * @property string $Description
 *
 * @property States $state
 * @property Users[] $users
 */
class Profiles extends \yii\db\ActiveRecord
{
    
    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';
    public $disabled = FALSE;
    private $auth;
    
    const CONTROLLER_NAME = 'profile';

    private $_profile;
    public $profilesetting = NULL;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles';
    }
    
    public function __construct($config = array()) {
        #$this->auth = \Yii::$app->authManager;
        $this->auth = new AuthorizationFunctions();
        return parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'IdState'], 'required'],
            [['IdState'], 'integer'],
            [['Description'], 'string','max'=>255],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
            [['Code'], 'string', 'max' => 30], 
            [['KeyWord'], 'unique','message'=>'{attribute} {value} ya existe'],
            [['Code'], 'unique','message'=>'{attribute} {value} ya existe'],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['IdState' => 'Id']],
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
            'Description' => 'Descripción',
            'IdState' => 'Estado',
            'Code' => 'Código',
            'KeyWord' => 'Llave',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(States::className(), ['Id' => 'IdState']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['IdProfile' => 'Id']);
    }
    
      /**
    * @return \yii\db\ActiveQuery
    */
   public function getProfileoptions()
   {
       return $this->hasMany(Profileoptions::className(), ['IdProfile' => 'Id']);
   }
   
   public function beforeSave($insert) {
       if(!$this->isNewRecord){
            $this->_validatePermission();
//            $code = ($this->IdState ? ($this->idState->Code):NULL);
//            $oldstate = $this->oldAttributes['IdState'];
            #if($code != NULL && $this->IdState != $oldstate){
            $this->_applyStateChanges();
            #}
            #print_r(array_diff($this->attributes, $this->oldAttributes)); die();
            
            if(!empty(array_diff($this->attributes, $this->oldAttributes))){
                $this->_updateProfile();
            }
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
       \Yii::$app->components->authorization->removeAllAssignments($this->KeyWord); 
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
                   \Yii::$app->components->authorization->removeAllAssignments($this->KeyWord);
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
