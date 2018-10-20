<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;
use common\models\States;
use common\models\Profiles;
use backend\models\Profileoptions;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use app\components\AuthorizationFunctions;
use kartik\password\StrengthValidator;
use backend\models\Settingsdetail;
use backend\models\Settings;
use backend\models\Useroptions;

/**
 * User model
 *
 * @property integer $Id
 * @property string $Username
 * @property string $FirstName
 * @property string $SecondName
 * @property string $LastName
 * @property string $SecondLastName
 * @property string $PasswordHash
 * @property string $PasswordResetToken
 * @property string $Email
 * @property string $AuthKey
 * @property integer $IdState
 * @property integer $IdProfile
 * @property string $CreatedDate
 * @property string $UpdatedDate
 * @property string $PasswordExpirationDate
 * @property string $password write-only password
 * 
 * @property States $state
 * @property Profiles $profile
 * @property Useroptions[] $useroptions;
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';

    public $_password = NULL;
    public $_passwordconfirm = NULL;
    public $completeName = NULL;
    public $stateName = NULL;
    public $profileName = NULL;
    private $_customPassword = FALSE;
    private $_new = FALSE;
    
    private $auth;
    
    public $disabled = FALSE;
    public $expired = FALSE;
    public $remainingDays = 0;
    public $warningPass = FALSE;
    
    private $_role = NULL;
    
    public $menuItems = [];
    public $usersetting = NULL;
    public $_emptyUserOptions = FALSE;
    
    const DEFAULT_PROFILE = 'USER';
    const SCENARIO_CREATE = 'create';
    const SCENARIO_CONSOLE = 'console';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_DETAIL = 'detail';
    
    const CLASS_USER_BACKEND = 'User';
    const CLASS_USER_FRONTEND = 'User';
    
    const PASSWORD_EXPIRATION_PARAMETER = 'PASSEXP';
    const PASSWORD_EXPIRATION_WARNING_PARAMETER = 'WRNPASS';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['Id','Username','FirstName','SecondName','LastName','SecondLastName'
            ,'Email','_password','_passwordconfirm','IdProfile','profileName'
            #,'IdServiceCentre'
            ,'IdState'
        ];
        $scenarios[self::SCENARIO_LOGIN] = ['Username','_password','IdState','PasswordExpirationDate'];
        return $scenarios;
    }
    
    function __construct($config = array()) {
        
        if( \Yii::$app->id != 'app-console'){
            $this->auth = new AuthorizationFunctions();
        } else {
            $this->scenario = self::SCENARIO_CONSOLE;
        }
        return parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['CreatedDate'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['UpdatedDate'],
                ],
                'value'=>new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Username','Email','IdState','IdProfile','FirstName','LastName'],'required','message'=>'Campo {attribute} no puede quedar vacío'],
            [['IdState','IdProfile'],'integer'],
            [['Email'],'email'],
            ['AuthKey', 'string'],
            [['FirstName','SecondName'],'string','max'=>30],
            [['LastName','SecondLastName'],'string','max'=>50],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdState'], 'default','value'=>  State::findOne(['KeyWord'=>'User','Code'=>  self::STATE_ACTIVE])->Id],
            [['IdProfile'], 'exist', 'skipOnError' => true, 'targetClass' => Profiles::className(), 'targetAttribute' => ['IdProfile' => 'Id']],
            [['IdProfile'], 'default','value'=> Profiles::findOne(['Code'=>  self::DEFAULT_PROFILE])->Id],
            ['_passwordconfirm', 'string', 'min' => 8],
            ['_password', StrengthValidator::className(),'preset'=>'normal','userAttribute'=>'Username'],
            ['_passwordconfirm', 'compare', 'compareAttribute'=>'_password', 'message'=>"Contraseñas no coinciden" ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Username' => 'Nombre de Usuario',
            'FirstName' => 'Primer Nombre',
            'SecondName' => 'Segundo Nombre',
            'LastName' => 'Primer Apellido',
            'SecondLastName' => 'Segundo Apellido',
            'completeName' => 'Nombre',
            'IdState' => 'Estado',
            'stateName' => 'Estado',
            'IdProfile' => 'Perfil',
            'profileName' => 'Perfil',
            #'IdServiceCentre' => 'Departamento',
            #'serviceCentreName' => 'Departamento',
            'AuthKey' => 'Llave',
            'Email' => 'Email',
            'PasswordHash' => 'Contraseña',
            'CreatedDate' => 'Fecha Creación',
            'UpdatedDate' => 'Fecha Actualización',
            '_password' => 'Contraseña',
            '_passwordconfirm' => 'Confirmar Contraseña',
            'PasswordExpirationDate'=>'Fecha Expiración Contraseña',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'IdState' => States::findOne(['KeyWord'=>'User','Code'=>self::STATE_ACTIVE])->Id]);
        #return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['Username' => $username, 'IdState' => States::findOne(['KeyWord'=>'User','Code'=>self::STATE_ACTIVE])->Id]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'PasswordResetToken' => $token,
            'IdState' => States::findOne(['KeyWord'=>'User','Code'=>self::STATE_ACTIVE])->Id
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->AuthKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->PasswordHash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->PasswordHash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->AuthKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->PasswordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->PasswordResetToken = null;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(States::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        try {
            $droptions = States::findAll(['KeyWord'=>'User']);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profiles::className(), ['Id' => 'IdProfile']);
    }
    
    public function getProfiles(){
        try {
            $droptions = Profiles::find()
                ->select(["profiles.Id","profiles.Name","profiles.IdState"])
                ->innerJoinWith('state b')
                ->where(['b.Code'=> Profiles::STATE_ACTIVE])
                ->orderBy(['profiles.Id'=>'ASC'])
                ->all();
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function beforeSave($insert) {
        $this->FirstName = strtoupper($this->FirstName);
        $this->SecondName = strtoupper($this->SecondName);
        $this->LastName = strtoupper($this->LastName);
        $this->SecondLastName = strtoupper($this->SecondLastName);
        $this->Username = strtoupper($this->Username);

        if($this->_password){
            $this->setPassword($this->_password);
            $this->_customPassword = TRUE;
            $this->_new = $this->isNewRecord;

            $user = \Yii::$app->user->getIdentity();
            if($this->isNewRecord || $this->Username != $user->Username){
                $date = date_sub(date_create(date('Y-m-d')), date_interval_create_from_date_string("1 day"));
            } else {
                $daysSetting = Settingsdetail::find()
                        ->select(['settingsdetail.Id','settingsdetail.Value','settingsdetail.IdSetting','settingsdetail.IdState'])
                        ->joinWith('setting b')
                        ->joinWith('state c')
                        ->where(['settingsdetail.Code'=> self::PASSWORD_EXPIRATION_PARAMETER
                                , 'b.Code'=> self::PASSWORD_EXPIRATION_PARAMETER
                                , 'c.Code'=> Settings::STATUS_ACTIVE,
                            ])
                        ->asArray()
                        ->one();
                $days = !empty($daysSetting) ? (int)$daysSetting['Value']:120;
                $date = date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string("$days day"));
            }
            $this->PasswordExpirationDate = $date->format('Y-m-d');
        }
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes) {

        $this->refresh();
        $this->_getAssignedRole();
        if(!$this->_verifyRole()){
            $this->_revokeProfile();
        }
        if(!$this->_role){
            $this->_assignProfile();
        }
        if($this->_customPassword && !$this->_new){
            Yii::$app->getSession()->setFlash('success','Contraseña de Usuario Actualizada');
        } elseif($this->_new) {
            Yii::$app->getSession()->setFlash('success','Usuario Creado Correctamente');
        } else {
            Yii::$app->getSession()->setFlash('success','Usuario Actualizado Correctamente');
        }

        if($this->usersetting){
            $settings = $this->usersetting;
            $useroptions = new Useroptions();
            $useroptions->IdUser = $this->Id;
            $useroptions->permissions = $settings['Custom'];
            unset($settings["Custom"]);
            $useroptions->enabledOptions = $settings;
            $useroptions->_setPermissions();
        } elseif($this->_emptyUserOptions){
            $useroptions = new Useroptions();
            $useroptions->IdUser = $this->Id;
            $useroptions->_resetAllPermissions();
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterFind() {

        if($this->scenario != self::SCENARIO_CONSOLE){
            $this->profileName = $this->IdProfile ? $this->profile->Name:"";
            $this->completeName = $this->FirstName." ".$this->LastName;

            $this->stateName = $this->IdState ? $this->state->Name:"";
            #$this->serviceCentreName = $this->IdServiceCentre ? $this->idServiceCentre->Name:"";

            $this->disabled = ( !$this->isNewRecord ? ( $this->IdState ? ($this->state->Code == self::STATE_INACTIVE):FALSE ): FALSE);
        }

        $this->CreatedDate = \Yii::$app->formatter->asDate($this->CreatedDate,'php:d-m-Y');
        $this->UpdatedDate = \Yii::$app->formatter->asDate($this->UpdatedDate,'php:d-m-Y');

        return parent::afterFind();
    }
    
    private function _getAssignedRole(){
        try {
            $this->_role = $this->auth->getUserAssignments($this->Id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function setExpirationDate(){
        try{
            $date = date_create(date('Y-m-d'));
            $dexp = $this->PasswordExpirationDate ? \DateTime::createFromFormat('Y-m-d', $this->PasswordExpirationDate): date_sub($date, date_interval_create_from_date_string("1 day"));
            $diff = $date->diff($dexp);

            if($diff->days == 0 || ($diff->invert == 1 && $diff->days > 0)){
                $this->expired = TRUE;
                $this->remainingDays = $diff->days;
            } else {
                $this->expired = FALSE;
                $this->remainingDays = $diff->days;
            }

            if($this->expired == FALSE){
                $warningDays = Settingsdetail::find()
                        ->select(['settingsdetail.Id','settingsdetail.Value','settingsdetail.IdSetting'])
                        ->joinWith('setting b')
                        ->where(['b.KeyWord'=>'User','b.Code'=> self::PASSWORD_EXPIRATION_WARNING_PARAMETER,'settingsdetail.Code'=> self::PASSWORD_EXPIRATION_WARNING_PARAMETER])
                        ->one();
                if($warningDays != NULL){
                    $this->warningPass = (int) $warningDays->Value >= $diff->days;
                }
            }
            $this->PasswordExpirationDate = $this->PasswordExpirationDate ? \Yii::$app->formatter->asDate($this->PasswordExpirationDate, 'php:d-m-Y'):$this->PasswordExpirationDate;
        } catch (Exception $exc){
            throw $exc;
        }
    }
    
    private function _verifyRole(){
        try {
            if($this->IdProfile){
                $rolename = $this->profile->KeyWord;
                $role = $this->auth->getRole($rolename);
                return $role ? in_array($role->name,$this->_role):TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _assignProfile(){
        try {
            $this->auth->assignRole($this->Id, $this->profile->KeyWord);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _revokeProfile(){
        try {
            $this->auth->revokeAllRoles($this->Id);
            $this->_role = NULL;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function comparePasswords($password){
        try {
            return \Yii::$app->security->validatePassword($password, $this->PasswordHash);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
