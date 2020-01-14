<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\State;
use common\models\Profile;
use backend\models\Profileoptions;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use backend\components\AuthorizationFunctions;
use kartik\password\StrengthValidator;
use backend\models\Settingsdetail;
use backend\models\Settings;
use backend\models\Useroptions;
use common\models\Userpreferences;

/**
 * User model
 *
 * @property integer $Id
 * @property string $Username
 * @property string $FirstName
 * @property string $SecondName
 * @property string $LastName
 * @property string $SecondLastName
 * @property string $DisplayName
 * @property string $PasswordHash
 * @property string $PasswordResetToken
 * @property string $Email
 * @property string $AuthKey
 * @property string $CreateDate
 * @property string $UpdateDate
 * @property string $PasswordExpirationDate
 * @property integer $IdState
 * @property integer $IdProfile
 * @property string $CodEmployee
 * @property integer $IdServiceCentre
 * @property string $password write-only password
 * 
 * @property State $state
 * @property Profile $profile
 * @property Servicecentres $serviceCentre
 * @property Options[] $options
 * @property Useroptions[] $useroptions;
 * @property Userpreferences[] $userpreferences
 * @property Settingsdetail[] $settingDetails
 * 
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $profileName;
    public $completeName;
    public $serviceCentreName;
    public $stateName;
    public $settings = [];

    private $_customPassword = FALSE;
    private $_new = FALSE;
    
    private $auth;
    
    public $disabled = FALSE;
    public $expired = FALSE;
    public $remainingDays = 0;
    public $warningPass = FALSE;
    
    private $_role = NULL;
    
    public $_password = NULL;
    public $_passwordconfirm = NULL;
    
    public $menuItems = [];
    public $usersetting = NULL;
    public $_emptyUserOptions = FALSE;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const STATE_ACTIVE = 'ACT';
    const STATE_INACTIVE = 'INA';
    
    const DEFAULT_PROFILE = 'USER';
    const SCENARIO_CREATE = 'create';
    const SCENARIO_CONSOLE = 'console';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_DETAIL = 'detail';
    const SCENARIO_WEBSERVICE = 'webservice';
    
    const CLASS_USER_BACKEND = 'User';
    const CLASS_USER_FRONTEND = 'Citizen';
    
    const PASSWORD_EXPIRATION_PARAMETER = 'PASSEXP';
    const PASSWORD_EXPIRATION_WARNING_PARAMETER = 'WRNPASS';
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['Id','Username','FirstName','SecondName','LastName','SecondLastName','DisplayName'
            ,'Email','_password','_passwordconfirm','IdProfile','profileName'
            ,'IdServiceCentre','IdState','CodEmployee'
        ];
        $scenarios[self::SCENARIO_LOGIN] = ['Username','_password','IdState','PasswordExpirationDate'];
        $scenarios[self::SCENARIO_WEBSERVICE] = ['Username','_password','AuthKey','IdState','PasswordExpirationDate'];
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['CreateDate'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['UpdateDate'],
                ],
                'value'=>new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FirstName','LastName','IdState','IdProfile','Username','Email','IdServiceCentre','DisplayName'],'required','message'=>'Campo {attribute} no puede quedar vacío'],
            [['IdState','IdProfile'],'integer'],
            [['Email'],'email'],
            [['_password','_passwordconfirm'],'required','on'=>['create']],
            [['Username'],'unique','message'=>'{attribute} {value} ya existe'],
            [['CreateDate', 'UpdateDate','PasswordExpirationDate'], 'safe'],
            [['IdState'], 'default','value'=>  State::findOne(['KeyWord'=>'User','Code'=>  self::STATE_ACTIVE])->Id],
            [['IdProfile'], 'default','value'=> Profile::findOne(['Code'=>  self::DEFAULT_PROFILE])->Id],
            [['Username','FirstName','LastName', 'IdState'], 'required','message'=>'{attribute} no puede quedar vacío'],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdProfile'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['IdProfile' => 'Id']],
            [['IdServiceCentre'], 'exist', 'skipOnError' => true, 'targetClass' => Servicecentres::className(), 'targetAttribute' => ['IdServiceCentre' => 'Id']],
            ['_password', 'string', 'min' => 8],
            ['Username', 'string', 'min' => 4],
            [['Username','DisplayName'], 'string', 'max' => 50],
            ['AuthKey', 'string'],
            [['CodEmployee'], 'string', 'max' => 10],
            [['Username'], 'unique'],
            [['CodEmployee'], 'unique'],
            [['FirstName','SecondName','LastName','SecondLastName'], 'string','max'=>50],
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
            'DisplayName' => 'Nombre para Mostrar',
            'IdState' => 'Estado',
            'stateName' => 'Estado',
            'IdProfile' => 'Perfil',
            'profileName' => 'Perfil',
            'CodEmployee' => 'Código Empleado',
            'IdServiceCentre' => 'Departamento',
            'serviceCentreName' => 'Departamento',
            'AuthKey' => 'Llave',
            'Email' => 'Email',
            'PasswordHash' => 'Contraseña',
            'CreateDate' => 'Fecha Creación',
            'UpdateDate' => 'Fecha Actualización',
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
        return static::findOne(['Id' => $id, 'IdState' => State::findOne(['KeyWord'=>'User','Code'=>self::STATE_ACTIVE])->Id]);
        #return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAuthKey($auth_key){
        return static::findOne(['AuthKey'=>$auth_key]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['Username' => $username, 'IdState' => State::findOne(['KeyWord'=>'User','Code'=>self::STATE_ACTIVE])->Id]);
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
            $droptions = State::findAll(['KeyWord'=>'User']);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceCentre()
    {
        return $this->hasOne(Servicecentres::className(), ['Id' => 'IdServiceCentre']);
    }
    
    public function getServiceCentres(){
        try {
            $droptions = Servicecentres::find()
                ->select(["servicecentres.Id","servicecentres.Name","servicecentres.IdState","servicecentres.Id"])
                ->innerJoinWith('state b')
                ->where(['b.Code'=> Servicecentres::STATE_ACTIVE])
                ->orderBy(['servicecentres.Id'=>'ASC'])
                ->all();
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
   public function getUseroptions()
   {
       return $this->hasMany(Useroptions::className(), ['IdUser' => 'Id']);
   }
    
    /**
    * @return \yii\db\ActiveQuery
    */
   public function getUserpreferences()
   {
       return $this->hasMany(Userpreferences::className(), ['IdUser' => 'Id']);
   }
   
   /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Options::className(), ['Id' => 'IdOption'])->viaTable('useroptions', ['IdUser' => 'Id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingDetails()
    {
        return $this->hasMany(Settingsdetail::className(), ['Id' => 'IdSettingDetail'])->viaTable('userpreferences', ['IdUser' => 'Id']);
    }
    
    public function getSettings(){
        try {
            $this->settings = Settingsdetail::find()
                                ->joinWith('setting b')
                                ->joinWith('state c')
                                ->where(['b.KeyWord' => StringHelper::basename(Userpreferences::class), 'c.Code' => Settingsdetail::STATUS_ACTIVE])
                                ->all();
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['Id' => 'IdProfile']);
    }

    
    public function getProfiles(){
        try {
            $droptions = Profile::find()
                ->select(["profile.Id","profile.Name","profile.IdState"])
                ->innerJoinWith('state b')
                ->where(['b.Code'=> Profile::STATE_ACTIVE])
                ->orderBy(['profile.Id'=>'ASC'])
                ->all();
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
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
            'Idstate' => State::findOne(['KeyWord'=>'User','Code'=>self::STATE_ACTIVE])->Id
            #'status' => self::STATUS_ACTIVE,
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
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->AuthKey;
    }

    /**
     * @inheritdoc
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
    
    public function beforeSave($insert) {
        $this->FirstName = strtoupper($this->FirstName);
        $this->SecondName = strtoupper($this->SecondName);
        $this->LastName = strtoupper($this->LastName);
        $this->SecondLastName = strtoupper($this->SecondLastName);
        $this->Username = strtoupper($this->Username);
        $this->DisplayName =empty($this->DisplayName) ? $this->LastName.(!empty($this->FirstName) ? $this->FirstName[0]:'') : $this->DisplayName;
        $this->CreateDate = !empty($this->CreateDate) ? \Yii::$app->getFormatter()->asDate($this->CreateDate, 'php:Y-m-d') : $this->CreateDate;
        
        $this->_defineExpirationDatePass();
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
            $this->serviceCentreName = $this->IdServiceCentre ? $this->serviceCentre->Name:"";

            $this->disabled = ( !$this->isNewRecord ? ( $this->IdState ? ($this->state->Code == self::STATE_INACTIVE):FALSE ): FALSE);
        }
        
        $this->CreateDate = \Yii::$app->formatter->asDate($this->CreateDate,'php:d-m-Y');
        $this->UpdateDate = \Yii::$app->formatter->asDate($this->UpdateDate,'php:d-m-Y');
        
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
    
    public function getUserMenu(){
        try {
            $useritems = new Useroptions();
            $useritems->IdUser = $this->Id;

            $profileoptions = new Profileoptions();
            $profileoptions->IdProfile = $this->IdProfile;
            $profileoptions->IdOption = NULL;
            $itemsprofile = $profileoptions->getChildrenOptions($profileoptions);
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function updateRamdomPass(){
        try {
            $this->_password = Yii::$app->security->generateRandomString(12);
            $this->_defineExpirationDatePass();
            $this->save();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function _defineExpirationDatePass(){
        try {
            if($this->_password){
                $this->setPassword($this->_password);
                $this->_customPassword = TRUE;
                $this->_new = $this->isNewRecord;

                $user = \Yii::$app->user->getIdentity();
                if(($this->isNewRecord || $this->Username != $user->Username) && $this->scenario != self::SCENARIO_WEBSERVICE){
                    $date = date_sub(date_create(date('Y-m-d')), date_interval_create_from_date_string("1 day"));
                } else {
                    $days = $this->_getExpirationPassSetting();
                    $date = date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string("$days day"));
                    
                }
                $this->PasswordExpirationDate = $date->format('Y-m-d');
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _getExpirationPassSetting(){
        try {
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
                return $days;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
           
}
