<?php

namespace backend\models;

use Yii;
use common\models\User;
use backend\models\Profileoptions;
use common\models\Profile;

use backend\components\AuthorizationFunctions;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\Type;
use common\models\State;

/**
 * This is the model class for table "useroptions".
 *
 * @property int $IdUser
 * @property int $IdOption
 * @property int $Enabled
 *
 * @property Options $option
 * @property User $user
 */
class Useroptions extends \yii\db\ActiveRecord
{
    
    private static $_idUser;
    private $useroptions;
    private $children = NULL;
    public $permissions = [];
    public $enabledOptions = [];
    public $list;
    private $auth;
    public $Custom = [];
    private $_model = NULL;
    
    public $_idParent;
    
    function __construct($config = array()) {
        $this->auth = new AuthorizationFunctions();
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'useroptions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdUser', 'IdOption', 'Enabled'], 'required'],
            [['IdUser', 'IdOption', 'Enabled'], 'integer'],
            [['IdOption'], 'exist', 'skipOnError' => true, 'targetClass' => Options::className(), 'targetAttribute' => ['IdOption' => 'Id']],
            [['IdUser'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdUser' => 'Id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdUser' => 'Id User',
            'IdOption' => 'Id Option',
            'Enabled' => 'Enabled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(Options::className(), ['Id' => 'IdOption']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['Id' => 'IdUser']);
    }
    
    
     public static function getHtmlList($criteria = NULL){
        try {
            self::$_idUser = isset($criteria['IdUser']) ? $criteria['IdUser']:NULL;
            unset($criteria['IdUser']);
            
            $user = User::findOne(['Id'=> self::$_idUser]);
            $options = Options::getChildren(NULL, []);
            $useroptions = self::getChildren(NULL, $criteria);
            $profileoptions = Profileoptions::getChildren(NULL, ['IdProfile'=> $user->IdProfile]);
            
            $_profile = [];
            $_user = [];
            self::_iterateChildren($profileoptions, $_profile, 'profileoptions');
            self::_iterateChildren($useroptions, $_user,'useroptions');
            
            $general_options = [
                'GENERAL'=> $options,
                'PROFILE'=> $_profile,
                'USER'=> $_user,
            ];
            
            $_options = self::_validateChildren($general_options);
            
            $table = "";
            if(empty($options)){
                $table .= "<tr>"
                        . "<td colspan='10'>No se encontraron Registros</td>"
                        . "</tr>";
            } else {
                $table = self::_iterateHtmlChildren($_options, $_user, $_profile);
            }
            return $table;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private static function _iterateChildren($option = [], &$parent_opt =[], $_key = 'children'){
        try {
            foreach ($option as $opt){
                $parent_opt = array_merge($parent_opt, [$opt['KeyWord']=>$opt['Enabled']]);
                if(!empty($opt[$_key])){
                    self::_iterateChildren($opt[$_key], $parent_opt, $_key);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public static function getChildren($idparent = NULL, $criteria = []){
        try {
            $options = self::filterChildren($idparent, $criteria);
            
            $useroptions = [];
            foreach ($options as $opt){
                $children = self::getChildren($opt["Id"]);
                $opt["useroptions"] = $children;
                $useroptions[$opt['KeyWord']] = $opt;
            }
            return $useroptions;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private static function filterChildren($idparent = NULL, $criteria = []){
        try {
            $options = self::find()
                    ->select('b.*, useroptions.Enabled, useroptions.IdOption, useroptions.IdUser')
                    ->joinWith('option b')
                    ->where($criteria)
                    ->andWhere(['b.IdParent'=> $idparent])
                    ->orderBy(['b.Sort'=> SORT_ASC])
                    ->asArray()
                    ->all()
                    ;
            return $options;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private static function _validateChildren($options = []){
        try {
            $_general = $options['GENERAL'];
            $_profile = $options['PROFILE'];
            $_user = $options['USER'];
            
            $_options = self::_iterateOptionChildren($_general, $_user, $_profile);
            return $_options;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    private static function _iterateOptionChildren($options = [], $_user = [], $_profile = []){
        try {
            $_options = [];
            foreach ($options as $key => $option){
                if(isset($_user[$option['KeyWord']])){
                    $option['level'] = StringHelper::basename(User::className());
                } else {
                    $option['level'] = StringHelper::basename(Profile::className());
                }
                if(!empty($option['children'])){
                    $children = self::_iterateOptionChildren($option['children'], $_user, $_profile);
                    $option['children'] = $children;
                }
                $_options[$key] = $option;
            }
            return $_options;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private static function _iterateHtmlChildren($options = [], $_user = [], $_profile = []){
        try {
            $table = "";
            foreach ($options as $option){
                $table.= self::getHtmlChildren($option, $_user, $_profile);
                if(!empty($option['children'])){
                    $table .= self::_iterateHtmlChildren($option['children'], $_user, $_profile);
                }
            }
            return $table;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private static function getHtmlChildren($option = [], $_user = [], $_profile = []){
        try {
            if(empty($option)){
                return "";
            }
            $code = isset($option['type']) ? $option['type']['Code']:  Options::TYPE_PERMISSION;
            $keyword = $option['KeyWord'];
            $enabled = isset($_user[$keyword]) ? $_user[$keyword]:(isset($_profile[$keyword]) ? $_profile[$keyword]:0);
            $profilevalue = isset($_profile[$keyword]) ? $_profile[$keyword]:0;
            $disabled = $option['level'] == StringHelper::basename(Profile::className());
            $table = "";
            $actions = "";
            switch ($code) {
                case Options::TYPE_MODULE:
                    $table = "<tr class='bg-success'>";
                    $table .= "<td colspan='7'>"
                        . "<i class='$option[Icon]'></i> "
                        . $option['Name'];
                    $table .= "</td>";
                    break;
                case Options::TYPE_GROUP:
                    $table = "<tr class='bg-danger'>";
                    $table .= "<td></td>"
                        . "<td colspan='4'>"
                        . "<i class='$option[Icon]'></i> "
                        . $option['Name'];
                    $table .= "</td>";
                    $table .= "<td>"
                            . (isset($option['IdType']) ? $option['type']['Name']:'')
                            . "</td>";
                    $table .= "<td>"
                            . $option['Url']
                            . "</td>";
                    break;
                case Options::TYPE_CONTROLLER:
                    $table = "<tr class='bg-warning'>";
                    $table .= "<td colspan='2'></td>"
                        . "<td colspan='3'>"
                        . "<i class='$option[Icon]'></i> "
                        . $option["Name"];
                    $table .= "</td>";
                    $table .= "<td>"
                            . (isset($option['IdType']) ? $option['type']['Name']:'')
                            . "</td>";
                    $table .= "<td>"
                            . $option['Url']
                            . "</td>";
                    break;
                case Options::TYPE_ACTION:
                    $table = "<tr class='bg-info'>";
                    $table .= "<td colspan='3'></td>"
                        . "<td colspan='2'>"
                        . "<i class='$option[Icon]'></i> "
                        . $option["Name"];
                    $table .= "</td>";
                    $table .= "<td>"
                            . (isset($option['IdType']) ? $option['type']['Name']:'')
                            . "</td>";
                    $table .= "<td>"
                            . $option['Url']
                            . "</td>";
                    break;
                case Options::TYPE_PERMISSION:
                default:
                    $opt = Options::find()->where(['Id'=> $option['Id']])->one();
                    $parentType = ($opt->IdParent ? ($opt->parent->IdType ? $opt->parent->type->Code:Options::TYPE_ACTION):  Options::TYPE_ACTION);
                    $colspan = ($parentType == Options::TYPE_ACTION ? 4:3);
                    $colspanName = ($parentType == Options::TYPE_ACTION ? 1:2);
                    $table = "<tr>";
                    $table .= "<td colspan='$colspan'></td>"
                        . "<td colspan='$colspanName'>"
                        . "<i class='$opt->Icon'></i> "
                        . $opt->Name;
                    $table .= "</td>";
                    $table .= "<td>"
                            . ($opt->IdType ? $opt->type->Name:'')
                            . "</td>";
                    $table .= "<td>"
                            . $opt->Url
                            . "</td>";
                    break;
            }
            $table .= "<td>"
                    . $option["KeyWord"]
                    . "</td>";
            $table .= "<td>"
                    . (isset($option["IdState"]) ? $option["state"]["Name"]:"")
                    . "</td>";
            $table .= "<td>"
                    . ($option["ItemMenu"] == 1 ? "SI":"NO")
                    . "</td>";
            $table .= "<td class='level'>".$option["level"]. "</td>";
            $table .= "<td class='action-column'>"; 
            $tableName = 'User' ."[".StringHelper::basename(self::className())."][Custom][".$option["Id"]."]";
                $table .= Html::checkbox($tableName, ($option['level'] == StringHelper::basename(User::className()))
                    , [
                        'class'=>'custom-level',
                        'data'=> [
                            'bind'=>'Custom_'.$option['Id'],
                        ]
                    ]);
            $table .= "</td>";
            $table .= "<td class='action-column'>"; 
            $tableName = 'User' ."[".StringHelper::basename(self::className())."][".$option["Id"]."]";
            $table .= Html::checkbox($tableName, ($enabled==1), [
                'id'=>"Custom_".$option["Id"], 
                'disabled'=> $disabled,
                'data'=>[
                    'default'=> StringHelper::basename(Profile::className()),
                    'level'=> $option['level'],
                    'custom'=> StringHelper::basename(User::className()),
                    'enabled'=> $enabled,
                    'profile'=> $profilevalue,
                ]
            ]);
            $table .= "</td>";
            $table .= "</tr>";
            return $table;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    public function afterSave($insert, $changedAttributes) {
        #$this->_createByType();
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete() {
        #$this->_model = self::findOne(['Id'=> $this->Id]);
        return parent::beforeDelete();
    }

    public function afterDelete() {
        #$this->_revoke();
        return parent::afterDelete();
    }
    
    public function _setPermissions(){
        try {
            if(!empty($this->permissions)){
                $this->_getUserChildren();
                $this->_iterateActualPermissions();
                $this->_addNewPermissions();
            } 
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function _resetAllPermissions(){
        try {
            return self::deleteAll(['IdUser' => $this->IdUser]);
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    private function _getUserChildren(){
        try {
            $this->useroptions = self::find()->where(['IdUser'=>  $this->IdUser])->all();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _iterateToParent($idOption = NULL){
        try {
            if($idOption){
                if(!isset($this->permissions[$idOption])){
                    $option = Options::find()->where(['Id'=> $idOption])->one();
                    if($option){
                        $opt = new Useroptions();
                        $opt->IdOption = $option->Id;
                        $opt->IdUser = $this->IdUser;
                        $opt->Enabled = 1;
                        $opt->save();
                        $opt->refresh();
                        if($opt->option->IdParent){
                            $this->_iterateToParent($opt->option->IdParent);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _iterateActualPermissions(){
        try {
            if(!empty($this->permissions)){
                foreach ($this->useroptions as $opt){
                    if(!isset($this->permissions[$opt->IdOption])){
                        $opt->delete();
                    } 
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    private function _addNewPermissions(){
        try {
            foreach ($this->permissions as $key => $value){
                $useropt = self::findOne(['IdUser'=> $this->IdUser, 'IdOption'=> $key]);
                if(!$useropt){
                    $useropt = new Useroptions();
                    $useropt->IdOption = $key;
                    $useropt->IdUser = $this->IdUser;
                }
                $_enabled = isset($this->enabledOptions[$key]) ? $this->enabledOptions[$key]:0;
                $useropt->Enabled = $_enabled;
                if(!$useropt->save()){
                    $message = $this->_getErrors($useropt->errors);
                    throw new \Exception($message, 92001);  
                } else {
                    $useropt->refresh();
                    if($useropt->option->IdParent){
                        $this->_iterateToParent($useropt->option->IdParent);
                    }
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _createByType(){
        try {
            
            $code = $this->IdOption ? ($this->option->IdType ? $this->option->type->Code:Options::TYPE_PERMISSION):Options::TYPE_PERMISSION;
            switch ($code) {
                case Options::TYPE_MODULE:
                case Options::TYPE_CONTROLLER:
                case Options::TYPE_ACTION:
                case Options::TYPE_PERMISSION:
                default:
                    $this->_assignPermission();
                    break;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _assignPermission(){
        try {
            $this->auth->assignUserPermission($this->IdUser, $this->option->KeyWord);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _revoke(){
        try {
            $code = $this->_model->IdOption ? ($this->_model->option->IdType ? $this->_model->option->type->Code:Options::TYPE_PERMISSION):Options::TYPE_PERMISSION;
            switch ($code) {
                case Options::TYPE_MODULE:
                case Options::TYPE_CONTROLLER:
                case Options::TYPE_ACTION:
                case Options::TYPE_PERMISSION:
                default:
                    $this->_model->_revokePermission();
                    break;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _revokePermission(){
        try {
            $this->auth->revokeUserPermission($this->IdUser, $this->option->KeyWord);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function _getErrors($errors = NULL){
        try {
            return StringHelper::basename(self::className()).': '.\Yii::$app->customFunctions->getErrors($errors);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function _getIndex(){
        try {
            $child = Options::findOne(['IdOption'=>  $this->IdOption,'KeyWord'=> $this->option->KeyWord.'Index']);
            return $child != NULL ? $child->Id:NULL;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    public function _getParent(){
        try {
            return $this->IdOption ? ($this->option->IdParent):NULL;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function loadMenu(){
        try {
            $options= $this->_constructMenu(NULL);
            $items = $this->_iterateOptions($options);
            return $items;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _iterateOptions(&$options = []){
        try {
            $items = [];
            foreach($options as $key => $child){
                $item =[
                    'label'=> " ".$child->option->Name,
                    'icon'=> $child->option->Icon,
                    #'active'=> TRUE,
                ];
                if($child->option->Url != NULL){
                    $url = '@web/'.$child->option->Url;
                    $item['url']= ($child->IdOption ? ($child->option->IdUrlType ? ($child->option->urlType->Code == Options::URL_OUTSIDE ? $child->option->Url:$url):$url):$url);
                } else {
                    $childoptions = $this->_constructMenu($child->IdOption);
                    $item['items']= $this->_iterateOptions($childoptions);
                }
                $items[$child->option->Sort] = $item;
            }
            return $items;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _constructMenu($IdOption = NULL){
        try {
            $useroptions = new Useroptions();
            $useroptions->IdUser = $this->IdUser;
            $useroptions->IdOption = $IdOption;
            $useritems = $this->getMenuItems($useroptions);
            
            $profileoptions = new Profileoptions();
            $profileoptions->IdProfile = $this->user->IdProfile;
            $profileoptions->IdOption = $IdOption;
            $profileitems = $profileoptions->getMenuItems($profileoptions);
            
            $items = [];
            foreach ($profileitems as $key => $value){
                $option = NULL;
                if(isset($useritems[$key])){
                    $option = $useritems[$key];
                    unset($useritems[$key]);
                } else {
                    $option = $value;
                }
                $items[$option->option->Sort] = $option;
            }
            if(!empty($useritems)){
                foreach ($useritems as $key => $value) {
                    $items[$value->option->Sort] = $value;
                }
            }
            return $items;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function getMenuItems($opt){
        try {
            $children = self::find()
                    ->joinWith('option b',true)
                    ->innerJoin('optionenvironment c', 'c.IdOption = b.Id')
                    ->innerJoin('type d', 'd.Id = c.IdEnvironmentType')
                    ->innerJoin('state e', 'e.Id = d.IdState')
                    ->where([
                        'useroptions.IdUser'=>$opt->IdUser,'b.ItemMenu'=>TRUE,'b.IdParent'=>$opt->IdOption,
                        'd.KeyWord' => StringHelper::basename(Optionenvironment::class),
                        'd.Code' => Yii::$app->id,
                        'c.Enabled' => Optionenvironment::ENABLED_VALUE,
                        'e.KeyWord' => StringHelper::basename(Type::class),
                        'e.Code' => Type::STATUS_ACTIVE,
                    ])
                    ->orderBy(['b.Sort'=>SORT_ASC])
                    ->all();
            $items = [];
            foreach ($children as $child){
                $items[$child->option->Sort] = $child;
            }
            return $items;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
}
