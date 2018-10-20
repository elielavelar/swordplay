<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "profileoptions".
 *
 * @property int $IdProfile
 * @property int $IdOption
 * @property int $Enabled
 *
 * @property Options $option
 * @property Profiles $profile
 */
class Profileoptions extends \yii\db\ActiveRecord
{
    
    private static $_idProfile;
    private $profileoptions;
    private $children = NULL;
    public $permissions = [];
    public $list;
    private $auth;
    
    public $_idParent;
    
    
    function __construct($config = array()) {
        $this->auth = new AuthorizationFunctions();
        return parent::__construct($config);
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profileoptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdProfile', 'IdOption'], 'required'],
            [['IdProfile', 'IdOption', 'Enabled'], 'integer'],
            [['IdProfile', 'IdOption'], 'unique', 'targetAttribute' => ['IdProfile', 'IdOption']],
            [['IdOption'], 'exist', 'skipOnError' => true, 'targetClass' => Options::className(), 'targetAttribute' => ['IdOption' => 'Id']],
            [['IdProfile'], 'exist', 'skipOnError' => true, 'targetClass' => Profiles::className(), 'targetAttribute' => ['IdProfile' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdProfile' => 'Perfil',
            'IdOption' => 'Opción',
            'Enabled' => 'Habilitado',
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
    public function getProfile()
    {
        return $this->hasOne(Profiles::className(), ['Id' => 'IdProfile']);
    }
    
    public static function getHtmlList($criteria = NULL){
        try {
            self::$_idProfile = isset($criteria['IdProfile']) ? $criteria['IdProfile']:NULL;
            unset($criteria['IdProfile']);
            $options = self::filterChildren(NULL, $criteria);
            $table = "";
            if($options == NULL){
                $table .= "<tr>"
                        . "<td colspan='10'>No se encontraron Registros</td>"
                        . "</tr>";
            } else {
                $table .= self::iterateChildren($options);
            }
            return $table;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private static function filterChildren($idparent = NULL, $criteria = []){
        try {
            $options = Options::find()
                ->joinWith(['profileoptions b'])
                ->joinWith(['type c'],FALSE)
                ->where($criteria)
                ->andWhere(['IdParent'=>$idparent])
                ->select('options.*, b.Enabled, c.Code')
                ->orderBy(['options.Sort'=>SORT_ASC])
                ->asArray()
                ->all();
            return $options;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private static function iterateChildren($options){
        try {
            $table = "";
            foreach ($options as $opt){
                $table .= self::getHtmlChildren($opt);
                $children = self::filterChildren($opt['Id']);
                if(!empty($children)){
                    $table.= self::iterateChildren($children);
                }
            }
            return $table;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private static function getHtmlChildren($option = []){
        try {
            if(empty($option)){
                return "";
            }
            $opt = Options::findOne(['Id'=>$option['Id']]);
            $profileoption = self::findOne(['IdProfile'=> self::$_idProfile,'IdOption'=> $opt->Id]);
            $code = $opt->IdType ? $opt->type->Code:  Options::TYPE_PERMISSION;
            $table = "";
            $actions = "";
            switch ($code) {
                case Options::TYPE_MODULE:
                    $table = "<tr class='bg-success'>";
                    $table .= "<td colspan='7'>"
                        . "<i class='$opt->Icon'></i> "
                        . $opt->Name;
                    $table .= "</td>";
                    break;
                case Options::TYPE_GROUP:
                    $table = "<tr class='bg-danger'>";
                    $table .= "<td></td>"
                        . "<td colspan='4'>"
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
                case Options::TYPE_CONTROLLER:
                    $table = "<tr class='bg-warning'>";
                    $table .= "<td colspan='2'></td>"
                        . "<td colspan='3'>"
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
                case Options::TYPE_ACTION:
                    $table = "<tr class='bg-info'>";
                    $table .= "<td colspan='3'></td>"
                        . "<td colspan='2'>"
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
                case Options::TYPE_PERMISSION:
                default:
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
                    . $opt->KeyWord
                    . "</td>";
            $table .= "<td>"
                    . ($opt->IdState ? $opt->state->Name:"")
                    . "</td>";
            $table .= "<td>"
                    . ($opt->ItemMenu == 1 ? "SI":"NO")
                    . "</td>";
            $table .= "<td class='action-column'>"; 
            $tableName = StringHelper::basename(Profile::className()) ."[".StringHelper::basename(self::className())."][".$opt->Id."]";
            $table .= Html::checkbox($tableName, ($profileoption ? ($profileoption->Enabled ? TRUE:FALSE):FALSE), []);
            $table .= "</td>";
            $table .= "</tr>";
            return $table;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public static function getChildren($idparent = NULL, $criteria = []){
        try {
            $options = self::filterChildren($idparent, $criteria);
            $profileoptions = [];
            foreach ($options as $opt){
                $children = self::getChildren($opt["Id"]);
                $opt["profileoptions"] = $children;
                $profileoptions[$opt['KeyWord']] = $opt;
                $profileoptions[$opt['KeyWord']]['level']= StringHelper::basename(self::class);
            }
            return $profileoptions;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getMenuItems($opt = NULL){
        try {
            $children = self::find()->joinWith('option b',true)
                    ->where(['IdProfile'=>$opt->IdProfile,'b.ItemMenu'=>TRUE,'b.IdParent'=>$opt->IdOption])
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
    
    public function getChildrenOptions($opt = NULL){
        try {
            $children = self::find()->joinWith('option b',true)
                    ->where(['IdProfile'=>$opt->IdProfile,'b.ItemMenu'=>TRUE,'b.IdParent'=>$opt->IdOption])
                    ->orderBy(['b.Sort'=>SORT_ASC])
                    ->all();
            $items = [];
            foreach ($children as $child){
                $item =[
                    'label'=>"<i class='".$child->option->Icon."'></i>&nbsp;".$child->option->Name,
                ];
                if($child->option->Url != NULL){
                    $url = '@web/'.$child->option->Url;
                    $item['url']= ($child->IdOption ? ($child->option->IdUrlType ? ($child->option->urlType->Code == Options::URL_OUTSIDE ? $child->option->Url:$url):$url):$url);
                } else {
                    $item['items']= $this->getChildrenOptions($child);
                }
                $items[$child->option->Sort] = $item;
            }
            return $items;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Authorization Permissions adminitration
     */
    
    public function afterSave($insert, $changedAttributes) {
        $this->_createByType();
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete() {
        $this->_revoke();
        return parent::afterDelete();
    }
    
    public function _setPermissions(){
        try {
            if(!empty($this->permissions)){
                $this->_getProfileChildren();
                $this->_iterateActualPermissions();
                $this->_addNewPermissions();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _getProfileChildren(){
        try {
            $this->profileoptions = self::find()->where(['IdProfile'=>  $this->IdProfile])->all();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _getChildren(){
        try {
            $this->children = $this->IdOption ? $this->option->options:NULL; 
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _iterateActualPermissions(){
        try {
            if(!empty($this->permissions)){
                foreach ($this->profileoptions as $opt){
                    if(!in_array($opt->Id, $this->permissions)){
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
                $profileopt = self::findOne(['IdProfile'=> $this->IdProfile, 'IdOption'=> $key]);
                if(!$profileopt){
                    $this->_saveOption($key);
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
                case Options::TYPE_GROUP:
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
            $this->auth->assignRolePermission($this->profile->KeyWord, $this->option->KeyWord);
        } catch (Exception $ex) {
            
        }
    }
    
    private function _revoke(){
        try {
            $code = $this->IdOption ? ($this->option->IdType ? $this->option->type->Code:Options::TYPE_PERMISSION):Options::TYPE_PERMISSION;
            switch ($code) {
                case Options::TYPE_MODULE:
                case Options::TYPE_GROUP:
                case Options::TYPE_CONTROLLER:
                case Options::TYPE_ACTION:
                case Options::TYPE_PERMISSION:
                default:
                    $this->_revokePermission();
                    break;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _revokePermission(){
        try {
            $this->auth->removeRolePermission($this->profile->KeyWord, $this->option->KeyWord);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function _saveOption($IdOption){
        try {
            $model = new Profileoptions();
            $model->IdOption = $IdOption;
            $model->IdProfile = $this->IdProfile;
            if(!$model->save()){
                $message = $this->_gerErrors($model->errors);
                throw new \Exception($message, 92001);  
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function _getErrors($errors = NULL){
        try {
            return StringHelper::basename(self::className()).': '.\Yii::$app->components->customFunctions->getErrors($errors);
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
}
