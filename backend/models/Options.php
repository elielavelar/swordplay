<?php

namespace backend\models;

use Yii;
use common\models\State;
use common\models\Type;
use backend\components\AuthorizationFunctions;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "options".
 *
 * @property integer $Id
 * @property string $Name
 * @property string $KeyWord
 * @property integer $IdState
 * @property integer $IdType
 * @property integer $IdParent
 * @property string $Icon
 * @property string $Url
 * @property integer $IdUrlType
 * @property integer $Sort
 * @property string $Description
 * @property integer $ItemMenu
 * @property integer $RequireAuth
 * @property integer $SaveLog
 * @property integer $SaveTransaction
 *
 * @property State $state
 * @property Type $type
 * @property Type $urlType
 * @property Options $parent
 * @property Optionenvironment[] $optionenvironments
 * @property Type[] $environmentTypes
 * @property Options[] $options
 * @property Profileoptions[] $profileoptions 
 * @property Profile[] $profiles
 * @property Useroptions[] $useroptions
 * @property User[] $users 
 */
class Options extends \yii\db\ActiveRecord
{
    private $codType;
    private $_key;
    private $_idparent;
    private $auth;
    public $Enabled;
    
    public $update = FALSE;
    public $delete = FALSE;
    
    const ACTIVE_STATUS = 'ACT';
    const INACTIVE_STATUS = 'INA';
    
    const TYPE_MODULE = 'MOD';
    const TYPE_CONTROLLER = 'CTRL';
    const TYPE_GROUP = 'GRP';
    const TYPE_ACTION = 'ACT';
    const TYPE_PERMISSION = 'PRM';
    
    const URL_INSIDE = 'INS';
    const URL_OUTSIDE = 'OUT';
    
    const REQUIRE_AUTH_FALSE = 0;
    const REQUIRE_AUTH_TRUE = 1;
    
    const DEFAULT_NAME = 'Default';
    const DEFAULT_ICON = 'fa fa-sticky-note';
    
    const DEFAULT_OPTION = 'DFLTACT';
     
    const SAVE_LOG_ENABLED = 1;
    const SAVE_LOG_DISABLED = 0;
    
    const SAVE_TRANSACTION_ENABLED = 1;
    const SAVE_TRANSACION_DISABLED = 0;
    
    public $Optionenvironment = [];
    public $_emptyEnvironments = FALSE;
    public $_envoptions = [];
    /**
     * @inheritdoc
     */
    
    function __construct($config = array()) {
        $this->auth = new AuthorizationFunctions();
        return parent::__construct($config);
    }
    
    public static function tableName()
    {
        return 'options';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'KeyWord', 'IdState', 'IdType'], 'required','message'=>'Campo {attribute} no puede quedar vacío'],
            [['IdState', 'IdType', 'IdUrlType', 'IdParent', 'Sort', 'ItemMenu','RequireAuth','SaveLog','SaveTransaction'], 'integer'],
            ['RequireAuth', 'in', 'range' => [self::REQUIRE_AUTH_FALSE, self::REQUIRE_AUTH_TRUE]],
            [['RequireAuth'], 'default','value'=> self::REQUIRE_AUTH_TRUE],
            [['Description'], 'string'],
            [['Name', 'KeyWord'], 'string', 'max' => 50],
            [['Icon'], 'string', 'max' => 30],
            [['Url'], 'string', 'max' => 100],
            [['KeyWord'], 'unique'],
            [['Sort'], 'unique','targetAttribute'=>['IdParent','Sort'],'message'=>'El Orden {value} ya ha sido utilizado para está opción padre'],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'Id']],
            [['IdUrlType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdUrlType' => 'Id']],
            [['IdParent'], 'exist', 'skipOnError' => true, 'targetClass' => Options::className(), 'targetAttribute' => ['IdParent' => 'Id']],
            [['ItemMenu'],'in','range'=>[0,1]],
            [['SaveLog'],'in','range'=>[self::SAVE_LOG_DISABLED, self::SAVE_LOG_ENABLED]],
            [['SaveTransaction'],'in','range'=>[self::SAVE_TRANSACION_DISABLED, self::SAVE_TRANSACTION_ENABLED]],
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
            'IdState' => 'Estado',
            'IdType' => 'Tipo',
            'IdUrlType' => 'Tipo de URL',
            'IdParent' => 'Padre',
            'Icon' => 'Icono',
            'Url' => 'Ruta',
            'Sort' => 'Orden',
            'Description' => 'Descripcion',
            'ItemMenu' => 'Menú',
            'Enabled' => 'Habilitado',
            'RequireAuth' => 'Requiere Autenticación',
            'envlist' => 'Entornos',
            'SaveLog' => 'Guardar Bitacora',
            'SaveTransaction' => 'Guardar Transacción',
        ];
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
            $droptions = State::findAll(['KeyWord'=>StringHelper::basename(self::className())]);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdType']);
    }
    
    public function getTypes(){
        try {
            $droptions = Type::findAll(['KeyWord'=>StringHelper::basename(self::className())]);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrlType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdUrlType']);
    }
    
    public function getUrlTypes(){
        try {
            $droptions = Type::findAll(['KeyWord'=>'Url']);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Options::className(), ['Id' => 'IdParent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Options::className(), ['IdParent' => 'Id']);
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProfileoptions()
    {
        return $this->hasMany(Profileoptions::className(), ['IdOption' => 'Id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUseroptions()
    {
        return $this->hasMany(Useroptions::className(), ['IdOption' => 'Id']);
    }
    
    public function getOptionenvironments()
    {
        return $this->hasMany(Optionenvironment::className(), ['IdOption' => 'Id']);
    }
    
    public function getEnvironmentTypes()
    {
        return $this->hasMany(Type::className(), ['Id' => 'IdEnvironmentType'])->viaTable('optionenvironment', ['IdOption' => 'Id']);
    }
    
    public function getEnvTypes(){
        try {
            $droptions = Type::find()->where(['KeyWord'=> StringHelper::basename(Optionenvironment::className())])
                    ->orderBy(['Id' => SORT_ASC])
                    ->all();
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /*ACTION METHODS*/
    public function afterFind() {
        return parent::afterFind();
    }
    
    
    public function beforeSave($insert) {
        try {
            if(empty($this->Sort)){
                $this->_getNextSort();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes) {
        $this->refresh();
        $this->_key = $this->attributes['KeyWord'];
        $this->_createByType();
        if(!empty($this->Optionenvironment)){
            $settings = $this->Optionenvironment;
            $environments = new Optionenvironment();
            $environments->IdOption = $this->Id;
            $environments->setting = $settings;
            $environments->_setEnvironments();
        } elseif($this->_emptyEnvironments){
            $environments = new Optionenvironment();
            $environments->IdOption = $this->Id;
            $environments->_resetAllEnvironments();
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete() {
        $this->_deleteByType();
        return parent::afterDelete();
    }
    
    private function _createByType(){
        try {
            $type = Type::findOne(['Id'=>  $this->IdType]);
            if($type != NULL){
                $this->_setByType($type);
            }
        } catch (Exception $ex) {
            throw new Exception(StringHelper::basename(self::className()).': '.$ex->getMessage(), $code, $previous);
        }
    }
    
    private function _setByType($type){
        try {
            switch ($type->Code) {
                case self::TYPE_MODULE:
                    $this->_createModule();
                    break;
                case self::TYPE_GROUP:
                    $this->_createGroup();
                    break;
                case self::TYPE_CONTROLLER:
                    $this->_createController();
                    break;
                case self::TYPE_ACTION:
                    $this->_createAction();
                    break;
                case self::TYPE_PERMISSION:
                    $this->_createPermission();
                    break;
                default:
                    break;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _deleteByType(){
        try {
            $code = $this->IdType ? $this->type->Code:self::TYPE_PERMISSION;
            switch ($code) {
                case self::TYPE_MODULE:
                    $this->_deleteModule();
                    break;
                case self::TYPE_GROUP:
                    $this->_deleteGroup();
                    break;
                case self::TYPE_CONTROLLER:
                    $this->_deleteController();
                    break;
                case self::TYPE_ACTION:
                    $this->_deleteAction();
                    break;
                case self::TYPE_PERMISSION:
                    $this->_deletePermission();
                    break;
                default:
                    break;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    private function _createModule(){
        try {
            if($this->_validateExists()){
                return $this->_updatePermission();
            } else {
                $create = $this->auth->createModule($this->KeyWord);
                $this->_createDefaultGroup();
                return $create;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _createDefaultGroup(){
        try {
            $model = new Options();
            $model->IdParent = $this->Id;
            $model->Enabled = $this->Enabled;
            $model->Name = self::DEFAULT_NAME." Group";
            $model->KeyWord = $this->KeyWord."Group";
            $model->Icon = self::DEFAULT_ICON;
            $model->ItemMenu = 0;
            $model->RequireAuth = $this->RequireAuth;
            $model->Url = NULL;
            $model->IdType = Type::findOne(['KeyWord'=> StringHelper::basename(self::className()),'Code'=> self::TYPE_GROUP])->Id;
            $model->IdUrlType = $this->IdUrlType;
            $model->IdState = $this->IdState;
            $model->Description = 'Grupo por Defecto';
            #print_r($model->attributes); die();
            if(!$model->save()){
                $message = Yii::$app->customFunctions->getErrors($model->errors);
                throw new Exception("Group Error: ".$message, 92000);
            } else {
                return TRUE;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _createGroup(){
        try {
            if($this->_validateExists()){
                return $this->_updatePermission();
            } else {
                return $this->auth->createGroup($this->KeyWord, $this->parent->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function _createController(){
        try {
            if($this->_validateExists()){
                return $this->_updatePermission();
            } else {
                return $this->auth->createController($this->KeyWord, $this->parent->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _createAction(){
        try {
            if($this->_validateExists()){
                return $this->_updatePermission();;
            } else {
                return$this->auth->createAction($this->KeyWord, $this->parent->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _createPermission(){
        try {
            if($this->_validateExists()){
                return $this->_updatePermission();
            } else {
                return$this->auth->createPermission($this->KeyWord, $this->parent->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private  function _validateExists(){
        try {
            #$option = [];
            $option = $this->auth->getPermission($this->KeyWord);
            return $option == FALSE ? FALSE:TRUE;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _deleteModule(){
        try {
            if($this->_validateExists()){
                return $this->auth->removeModule($this->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _deleteGroup(){
        try {
            if($this->_validateExists()){
                return $this->auth->removeGroup($this->KeyWord, $this->parent->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _deleteController(){
        try {
            if($this->_validateExists()){
                return $this->auth->removeController($this->KeyWord, $this->parent->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _deleteAction(){
        try {
            if($this->_validateExists()){
                return $this->auth->removeAction($this->KeyWord, $this->parent->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _deletePermission(){
        try {
            if($this->_validateExists()){
                return $this->auth->removePermission($this->KeyWord, $this->parent->KeyWord);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function _updatePermission(){
        try {
            $name = isset($this->oldAttributes['KeyWord']) ?$this->oldAttributes['KeyWord']:NULL;
            if($name){
                $permission = $this->auth->getPermission($name);
                $permission->description = $this->Description;
                $permission->name = $this->KeyWord;
                return $this->auth->updatePermission($name, $permission);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
    
    public static function getHtmlList($criteria = NULL){
        try {
            $options = self::filterChildren(NULL, $criteria);
            $table = "";
            if($options == NULL){
                $table .= "<tr>"
                        . "<td colspan='12'>No se encontraron Registros</td>"
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
            $options = self::find()
                    ->joinWith('type b')
                    ->joinWith('state c')
                    ->where($criteria)
                    ->andWhere(['options.IdParent'=>$idparent])
                    ->orderBy(['options.Sort'=>SORT_ASC])->all();
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
                if($opt->options){
                    $table.= self::iterateChildren($opt->options);
                }
            }
            return $table;
        } catch (Exception $ex) {
            
        }
        
    }
    
    private static function getHtmlChildren($opt = NULL){
        try {
            if($opt == NULL){
                return "";
            }
            $code = $opt->IdType ? $opt->type->Code:self::TYPE_PERMISSION;
            $table = "";
            $actions = "";
            switch ($code) {
                case self::TYPE_MODULE:
                    $table = "<tr class='bg-success'>";
                    $table .= "<td colspan='7'>"
                        . "<i class='$opt->Icon'></i> "
                        . $opt->Name;
                    $table .= "</td>";
                    $actions = Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-pencil'])), "javascript:editModule($opt->Id)", []);
                    $actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-trash'])), "javascript:deleteModule($opt->Id)", []);
                    $actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-plus'])), "javascript:addGroup($opt->Id)", ['title'=>'Agregar Grupo']);
                    break;
                case self::TYPE_GROUP:
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
                    $actions = Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-pencil'])), "javascript:editGroup($opt->Id)", []);
                    $actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-trash'])), "javascript:deleteGroup($opt->Id)", []);
                    $actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-plus'])), "javascript:addController($opt->Id)", ['title'=>'Agregar Controlador']);
                    break;
                case self::TYPE_CONTROLLER:
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
                    $actions = Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-pencil'])), "javascript:editController($opt->Id)", []);
                    $actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-trash'])), "javascript:deleteController($opt->Id)", []);
                    $actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-plus'])), "javascript:addAction($opt->Id)", ['title'=>'Agregar Acción']);
                    break;
                case self::TYPE_ACTION:
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
                    $actions = Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-pencil'])), "javascript:editAction($opt->Id)", []);
                    $actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-trash'])), "javascript:deleteAction($opt->Id)", []);
                    #$actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-plus'])), "javascript:addPermission($opt->Id)", ['title'=>'Agregar Acción']);
                    break;
                case self::TYPE_PERMISSION:
                default:
                    $parentType = ($opt->IdParent ? ($opt->parent->IdType ? $opt->parent->type->Code:self::TYPE_ACTION):self::TYPE_ACTION);
                    $colspan = ($parentType == self::TYPE_ACTION ? 4:3);
                    $colspanName = ($parentType == self::TYPE_ACTION ? 1:2);
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
                    $actions = Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-pencil'])), "javascript:editPermission($opt->Id)", []);
                    $actions .= Html::a((Html::tag('span', '', ['class'=>'glyphicon glyphicon-trash'])), "javascript:deletePermission($opt->Id)", []);
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
            $table .= "<td>"
                    . ($opt->RequireAuth == 1 ? "SI":"NO")
                    . "</td>";
            $table .= "<td>"
                    . ($opt->SaveLog == 1 ? "SI":"NO")
                    . "</td>";
            $table .= "<td>"
                    . ($opt->SaveTransaction == 1 ? "SI":"NO")
                    . "</td>";
            $table .= "<td class='action-column'>". $actions. "</td>";
            $table .= "</tr>";
            return $table;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    private function _getNextSort(){
        try {
            $values = self::find();
            if($this->IdParent){
                $values->where('IdParent = :parent',[':parent'=> $this->IdParent]);
            } else {
                $values->where('IdParent IS NULL');
            }
            $_values = $values->max('Sort');
            $this->Sort = (int)$_values + 1;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public static function getChildren($idparent = NULL, $criteria = []){
        try {
            $options = self::filterChildren($idparent, $criteria);
            $options_list = [];
            foreach ($options as $opt){
                $option = $opt->attributes;
                $option['type']= $opt->type->attributes;
                $option['state']= $opt->state->attributes;
                $children = self::getChildren($option["Id"]);
                $option["children"] = $children;
                #$option["environments"] = $opt->optionenvironments;
                $options_list[$option['KeyWord']] = $option;
            }
            return $options_list;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getDefaultMenu(){
        try {
            return $this->_getChildrenMenu();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function _getChildrenMenu($opt = NULL){
       try {
            if(empty($opt)){
                $opt = new Options();
                $opt->Id = NULL;
            } 
            $items = [];
            $children = self::find()
                    ->where(['IdParent'=>$opt->Id,'ItemMenu'=>TRUE, 'RequireAuth'=> FALSE])
                    ->orderBy(['Sort'=>SORT_ASC])
                    ->all();
            foreach ($children as $child){
                #if($child->idType->Code == Options::TYPE_GROUP) {
                    $item =[];
                    if($child->Url){
                        $url = '@web/'.$child->Url;
                        $item['url']= ($child->IdUrlType ? ($child->urlType->Code == Options::URL_OUTSIDE ? $child->Url:$url):$url);
                    } else {
                        $_items = $this->loadDefaultMenu($child);
                        $item['items'] = $_items;
                    }
                    $items[$child->Sort] = $item;
                #}
            }
            return $items;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
 
    public function loadDefaultMenu($opt = NULL){
        try {
            if(empty($opt)){
                $opt = new Options();
                $opt->Id = NULL;
            } 
            
            $items = [];
            $children = self::find()
                    ->innerJoin('optionenvironment b', 'b.IdOption = options.Id')
                    ->innerJoin('type c', 'c.Id = b.IdEnvironmentType')
                    ->innerJoin('state d', 'd.Id = c.IdState')
                    ->where([
                        'options.IdParent'=>$opt->Id,'options.ItemMenu'=>TRUE, 'options.RequireAuth'=> FALSE,
                        'c.KeyWord' => StringHelper::basename(Optionenvironment::class),
                        'c.Code' => Yii::$app->id, 
                        'b.Enabled' => Optionenvironment::ENABLED_VALUE,
                        'd.KeyWord' => StringHelper::basename(Type::class),
                        'd.Code'=> Type::STATUS_ACTIVE
                    ])
                    ->orderBy(['Sort'=>SORT_ASC])
                    ->all();
            foreach ($children as $child){
                #if($child->idType->Code == Options::TYPE_GROUP) {
                    $item =[
                        'label'=>$child->Name,
                        'icon'=> $child->Icon,
                        #'active'=> Yii::$app->controller->id == 'site',
                    ];
                    if($child->Url){
                        $url = '@web/'.$child->Url;
                        $item['url']= ($child->IdUrlType ? ($child->urlType->Code == Options::URL_OUTSIDE ? $child->Url:$url):$url);
                    } else {
                        $_items = $this->loadDefaultMenu($child);
                        $item['items'] = $_items;
                    }
                    $items[$child->Sort] = $item;
                #}
            }
            return $items;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getExtendedValues(){
        try {
            foreach ($this->optionenvironments as $opt){
                array_push($this->_envoptions, [$opt->IdEnvironmentType => $opt->Enabled]);
            }
            return array_merge($this->attributes, ['Optionenvironment' => $this->_envoptions]);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
