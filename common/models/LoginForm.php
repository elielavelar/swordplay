<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Tbloperators;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $authkey;
    public $rememberMe = true;

    private $_user;

    const SCENARIO_WEBSERVICE = 'webservice';
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_WEBSERVICE] = ['authkey'];
        
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required','message'=>'Campo {attribute} no puede quedar vacío'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            [['authkey'], 'required','on'=>'webservice'],
            ['authkey', 'string'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'username'=>'Nombre de Usuario',
            'password'=>'Contraseña',
            'rememberMe'=>'Recuérdame',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user){ 
                #$this->getOperator();
                if(!$this->_user->validatePassword($this->password)) {
                    $this->addError($attribute, 'Contraseña incorrecta');
                }
            } else {
                $this->addError('username', 'Nombre de Usuario no encontrado');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    
    /**
     * Logs in a user using the provided authentication key
     *
     * @return bool whether the user is logged in successfully
     */
    public function loginByKey(){
        if($this->validate()){
            $login = Yii::$app->user->login($this->getUserByAuthKey(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            if($login){
                $user = $this->getUserByAuthKey();
                $user->setExpirationDate();
                if($user->expired){
                    $user->scenario = User::SCENARIO_WEBSERVICE;
                    $user->updateRamdomPass();
                }
            }
            return $login;
        } else {
            return FALSE;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
    
    /**
     * Finds user by [[authkey]]
     *
     * @return User|null
     */
    protected function getUserByAuthKey(){
        if($this->_user === NULL){
            $this->_user = User::findIdentityByAuthKey($this->authkey);
        }
        return $this->_user;
    }
    
    public function getOperator(){
        $operator = Tbloperators::find()->where(['CODOPER'=> $this->username])->one();
        if(!empty($operator)){
            $operator->password = $this->password;
            if($operator->comparePass()){
                if(!$this->_user->comparePasswords($this->password)){
                    $this->_user->setPassword($this->password);
                    $this->_user->save();
                    $this->_user->refresh();
                }
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }
}
