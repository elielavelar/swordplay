<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use frontend\models\Citizen;
use common\models\State;
use common\models\Type;
use common\models\Servicecentres;
use backend\models\Settings;
use backend\models\Settingsdetail;
use backend\models\Appointmentservicesetting;
use yii\db\Query;
use common\models\Holidays;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use nepstor\validators\DateTimeCompareValidator;

/**
 * This is the model class for table "appointments".
 *
 * @property integer $Id
 * @property integer $IdCitizen
 * @property string $AppointmentDate
 * @property string $AppointmentHour
 * @property string $Code
 * @property string $ShortCode
 * @property integer $IdState
 * @property integer $IdType
 * @property integer $IdServiceCentre
 * @property string $CreationDate
 * @property string $RegistrationMethod
 *
 * @property State $idState
 * @property State $idType
 * @property Citizen $idCitizen
 * @property Servicecentres $idServiceCentre
 */
class Appointments extends ActiveRecord
{
    public $view;
    public $create;
    public $update;
    public $delete;
    public $cancel;
    public $reschedule;
    public $citizenName;
    public $hourDate;
    public $sendmail;
    public $sendremindermail;
    
    public $finishDate;
    public $_finishYear;
    public $_finishMonth;
    public $_finishDay;
    public $_finishHour;
    public $_finishMinute = 0;
    
    private $_date;
    public $_day;
    private $_hour;
    
    private $_count = 0;
    private $_correlative = 0;
    private $_max_request = 2;
    private $_weekday;
    
    public $RegistrationMethodName = 'No Definido';
    
    public $response_format = 'ARRAY';
    
    private $date_format = 'd-m-Y';
    private $dbDateFormat = '%d-%m-%Y';
    private $time_format = 'H:i';


    const ACTIVE_STATUS = 'ACT';
    const INACTIVE_STATUS = 'INA';
    const UNATTENDED_STATUS = 'NAT';
    const CANCELED_STATUS = 'CAN';
    const ATTENDED_STATUS = 'ATD';
    
    const RESPONSE_FORMAT_GRID = 'GRID';
    const RESPONSE_FORMAT_ARRAY = 'ARRAY';
    
    private $unvalidatedScenarios = ['cancel'];
    
    const SCENARIO_CANCEL = 'cancel';
    const SCENARIO_CONSOLE = 'console';
    
    public $message = "";

    private $dayname = [
        '1'=>'Lunes',
        '2'=>'Martes',
        '3'=>'Miércoes',
        '4'=>'Jueves',
        '5'=>'Viernes',
        '6'=>'Sábado',
        '7'=>'Domingo',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'appointments';
    }
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::className(),
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['CreationDate'],
            ],
            'value'=>new Expression('NOW()'),
        ];
        return $behaviors;
    }


    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CANCEL] = ['Id','IdState'];
        
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $date = date_create(date($this->date_format));
        $hour = date_create(date($this->time_format));
        $da = $this->getDayBefore();
        date_add($date, date_interval_create_from_date_string("$da day"));
        $this->finishDate = date_format($date, $this->date_format);
        $this->_finishYear = date_format($date, 'Y');
        $this->_finishMonth = date_format($date, 'm');
        $this->_finishDay = date_format($date, 'd');
        $this->_finishHour = $this->getStartHour();
        return [
            [['IdCitizen', 'IdState', 'IdServiceCentre','IdType'], 'required','message'=>'<i a class="fa fa-ban"></i> {attribute} no puede quedar vacío'],
            [['IdCitizen', 'IdState', 'IdServiceCentre','IdType'], 'integer'],
            [['AppointmentHour','CreationDate'], 'safe'],
            [['AppointmentDate'], 'date'],
            [['Code'], 'string', 'max' => 50],
            [['ShortCode'], 'string', 'max' => 8],
            [['RegistrationMethod'], 'string', 'max' => 50],
            [['Code'], 'unique','message'=>'Código {value} ya existe'],
            ['AppointmentDate', 'dateValidation'],
            [['AppointmentDate','AppointmentHour'], 'required','on'=>'default','message'=>'{attribute} no puede quedar vacío'],
            [['AppointmentDate'],  DateTimeCompareValidator::className(),'compareValue'=> Yii::$app->formatter->asDate($date, 'php:'.$this->date_format)
                ,'operator'=>'>=','format'=> $this->date_format,'jsFormat'=>'DD-MM-YYYY'
                ,'message'=>'{attribute} debe ser mayor o igual que '.$this->finishDate,'on' => 'create'],
            [['AppointmentDate'],  DateTimeCompareValidator::className(),'compareValue'=> Yii::$app->formatter->asDate($date, 'php:'.$this->date_format)
                ,'operator'=>'>=','format'=> $this->date_format,'jsFormat'=>'DD-MM-YYYY'
                ,'message'=>'{attribute} debe ser mayor o igual que '.$this->finishDate,'on' => 'default'],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            [['IdType'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['IdType' => 'Id']],
            [['IdCitizen'], 'exist', 'skipOnError' => true, 'targetClass' => Citizen::className(), 'targetAttribute' => ['IdCitizen' => 'Id']],
            [['IdServiceCentre'], 'exist', 'skipOnError' => true, 'targetClass' => Servicecentres::className(), 'targetAttribute' => ['IdServiceCentre' => 'Id']],
            [['IdServiceCentre'],function ($attribute, $params, $validator) {
                $centre = Servicecentres::findOne(['Id'=> $params]);
                if($centre){
                    if($centre->idState == Servicecentres::STATE_INACTIVE){
                        $this->addError('IdServiceCentre', 'El Duicentro se encuentra inhabilitado para registrar citas');
                    }
                }
            }],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdCitizen' => 'Ciudadano',
            'citizenName' => 'Nombre del Ciudadano',
            'AppointmentDate' => 'Fecha',
            'AppointmentHour' => 'Hora',
            'hourDate' => 'Hora',
            'IdState' => 'Estado',
            'IdType' => 'Tipo de Trámite',
            'IdServiceCentre' => 'Duicentro',
            'Code'=>'Código',
            'ShortCode'=>'Código',
            'RegistrationMethod'=>'Registro',
            'RegistrationMethodName'=>'Registro',
        ];
    }
    
    /*Maintainance Methods*/
    public function beforeValidate() {
        $this->_validateScenario();
//        $this->dateValidation();
        return parent::beforeValidate();
    }
    
    public function afterValidate() {
        return parent::afterValidate();
    }
    
    private function _validateScenario(){
        try {
            $status = isset($this->attributes["IdState"]) ? $this->attributes["IdState"]:NULL;
//            if ($status == State::findOne(['KeyWord'=>StringHelper::basename(self::className()),'Code'=>  self::CANCELED_STATUS])->Id){
//                $this->scenario = self::SCENARIO_CANCEL;
//            }
        } catch (Exception $exc) {
            
        }
    }

    public function afterFind() {
        
        if(\Yii::$app->id == "app-frontend"){
            $this->cancel = TRUE;
            $this->reschedule = TRUE;
            $values = Settingsdetail::find()->joinWith('idSetting b', TRUE)
                        ->where(['b.KeyWord'=>'Apppointment','b.Code'=>'RESCH'])
                        ->one();
            $this->reschedule = $values != NULL ? ((int)$values->Value == 1):FALSE;
        } elseif(\Yii::$app->id == "app-backend"){
            $this->view = \Yii::$app->user->can('appointmentView');
            $this->create = \Yii::$app->user->can('appointmentCreate');
            $this->update = \Yii::$app->user->can('appointmentUpdate');
            $this->delete = \Yii::$app->user->can('appointmentDelete');
            $this->cancel = \Yii::$app->user->can('appointmentCancel');
            $this->reschedule = \Yii::$app->user->can('appointmentReschedule');
            $this->sendremindermail = \Yii::$app->user->can('appointmentSendremindermail');
        } else {}
        
        $this->cancel = $this->cancel && ($this->IdState ? ($this->idState->Code == "ACT" ? TRUE:FALSE):FALSE);
        $this->sendmail = ($this->IdState ? ($this->idState->Code == "ACT" ? TRUE:FALSE):FALSE);
        $this->sendremindermail = $this->sendremindermail && ($this->IdState ? ($this->idState->Code == "ACT" ? TRUE:FALSE):FALSE);
        $this->reschedule = $this->reschedule && ($this->IdState ? ($this->idState->Code == "ACT" ? TRUE:FALSE):FALSE);
        $this->AppointmentDate = Yii::$app->formatter->asDate($this->AppointmentDate, 'php:d-m-Y');
        $this->AppointmentHour = Yii::$app->formatter->asTime($this->AppointmentHour, 'php:h:i a');
        $this->citizenName = $this->IdCitizen ? $this->idCitizen->CompleteName:"";
        $this->hourDate = Yii::$app->formatter->asTime($this->AppointmentDate,'php:H:i a');
        $this->_day = (int) Yii::$app->formatter->asDate($this->AppointmentDate,'php:d');
        $this->CreationDate = Yii::$app->formatter->asDatetime($this->CreationDate,'php:d-m-Y h:i:a');
        if($this->RegistrationMethod){
            $type = Type::findOne(['KeyWord'=>'RegistrationMethod','Code'=> $this->RegistrationMethod]);
            if($type){
                $this->RegistrationMethodName = $type->Name;
            }
        }
        return parent::afterFind();
    }
    
    public function beforeSave($insert) {
        try {
            $this->_validateScenario();
            if(parent::beforeSave($insert)){
                $this->_loadDefaultData();
                $this->AppointmentDate = Yii::$app->formatter->asDate($this->AppointmentDate, 'php:Y-m-d');
                $this->AppointmentHour = Yii::$app->formatter->asTime($this->AppointmentHour, 'php:H:i');
                return ((in_array($this->getScenario(), $this->unvalidatedScenarios)) ? TRUE:($this->validateData() && $this->validateCenter()));
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    public function afterSave($insert, $changedAttributes) {
//        if($this->IdState){
//            if(in_array($this->idState->Code, ['ACT','CAN'])){
//                $this->sendMailConfirmationBatch(['Id'=>''])
//            }
//        }
        return parent::afterSave($insert, $changedAttributes);
    }


    private function _loadDefaultData(){
        try {
            if($this->isNewRecord){
                $state = State::findOne(['KeyWord'=>StringHelper::basename(self::className()),'Code'=>  self::ACTIVE_STATUS]);
                $this->IdState = $state->Id;
                $this->getCode();
             } elseif($this->ShortCode == NULL){
                 $this->_generateShortCode();
             }
             $this->RegistrationMethod = \Yii::$app->id;
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
    
    public function getDateFormat(){
        return $this->date_format;
    }
    
    public function getDbDateFormat(){
        return $this->dbDateFormat;
    }
    
    public function setDateParam($date = NULL){
        $this->_date = $date;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        try {
            $droptions = State::findAll(['KeyWord'=>'Appointments']);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdType()
    {
        return $this->hasOne(Type::className(), ['Id' => 'IdType']);
    }
    
    public function getTypes(){
        try {
            $droptions = Type::findAll(['KeyWord'=>'Process','IdState'=>  State::findOne(['KeyWord'=>'Type','Code'=>'ACT'])]);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCitizen()
    {
        return $this->hasOne(Citizen::className(), ['Id' => 'IdCitizen']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdServiceCentre()
    {
        return $this->hasOne(Servicecentres::className(), ['Id' => 'IdServiceCentre']);
    }
    
    public function getServiceCentres(){
        try {
            $idType = Type::findOne(['KeyWord'=>  'Servicecentres','Code'=>'DUISITE'])->Id;
            $droptions = Servicecentres::findAll(['IdState'=>  State::findOne(['KeyWord'=>'Servicecentres','Code'=>'ACT']),'IdType'=>$idType]);
            return ArrayHelper::map($droptions, 'Id', 'Name');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getAppointmentDate(){
        try {
            $dia = $this->dayname[\Yii::$app->formatter->asDate($this->AppointmentDate, 'e')];
            return $dia." ".\Yii::$app->formatter->asDate($this->AppointmentDate, 'php:d/m/Y');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function getAppointmentHour(){
        try {
            return \Yii::$app->formatter->asTime($this->AppointmentHour, 'php:h:i a');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function validateData() {
        try {
            $criteria = ['IdState'=>  State::findOne(['KeyWord'=>StringHelper::basename(self::className()),'Code'=>  self::ACTIVE_STATUS])->Id,'IdCitizen'=>  $this->IdCitizen];
            if($this->Id != NULL){
                $model = Appointments::findByCondition($criteria)->andWhere('Id != :id',[':id'=>  $this->Id])->count('*');

            } else {
                $model = Appointments::findByCondition($criteria)->count('*');
            }
            if($model > 0){
                $this->addError('AppointmentDate','Ciudadano ya posee una cita agendada');
                return FALSE;
            } else {return TRUE;}
        } catch (Exception $exc) {
            throw $exc;
        }
            
    }
    
    private function validateCenter() {
        try{
            $criteria = ['IdState'=>  State::findOne(['KeyWord'=>StringHelper::basename(self::className()),'Code'=>  self::ACTIVE_STATUS])->Id,
                'IdServiceCentre'=>  $this->IdServiceCentre,
            ];
            $this->_date = date_format(new \DateTime($this->AppointmentDate),'Y-m-d');#Yii::$app->formatter->asDate($this->AppointmentDate, 'php:Y-m-d H:i:s');
            $this->_hour = date_format(new \DateTime($this->AppointmentHour),'H');
            $this->_weekday = ((int) date_format(new \DateTime($this->AppointmentDate),'w'))+1;
            
            $this->getCountAppointment($criteria);
            $this->setMaxRequest();
            
            if($this->_count >= $this->_max_request){
                $this->addError('AppointmentDate','La Cantidad de citas de Duicentro para esta hora se ha completado. Seleccione otro horario');
                return FALSE;
            } else {return TRUE;}
        } catch (Exception $exc) {
            throw $exc;
        }
    }


    public function cancel(){
        try {
            $state = State::findOne(['KeyWord'=>  StringHelper::basename(self::className()),'Code'=>  self::CANCELED_STATUS]);
            $this->IdState = $state->Id;
            return $this->save();
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    private function setMaxRequest(){
        try {
            $validate = $this->_getServicecentresetting();
            if($validate){
                $values = Settingsdetail::find()->joinWith('idSetting b', TRUE)
                        ->where(['b.KeyWord'=>'Apppointment','b.Code'=>'QTY'])
                        ->one();
                $this->_max_request = $values != NULL ? $values->Value:$this->_max_request;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
    
    private function _getServicecentresetting(){
        try {
            $validate = TRUE;
            $setting = Appointmentservicesetting::findOne(['IdServiceCentre'=>  $this->IdServiceCentre,'IdDay'=>  $this->_weekday,'IdHour'=>  $this->_hour]);
            $this->_max_request = 2;
            if($setting != NULL){
                if($setting->idState->Code == Appointmentservicesetting::DEFAULT_STATE){
                    $this->_max_request = $setting->Quantity;
                    $validate = FALSE;
                } 
            }
            return $validate;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function getCountAppointment($criteria = NULL){
        try {
            if(!$this->isNewRecord){
                $this->_count = Appointments::findByCondition($criteria)
                        ->andWhere('Id != :id',[':id'=>  $this->Id])
                        ->andWhere("date_format(AppointmentDate,'%Y-%m-%d') = :fecha",[':fecha'=> $this->_date])
                        ->andWhere("date_format(AppointmentHour,'%H') = :hora",[':hora'=> $this->_hour])
                        ->count();
                
            } else {
                $this->_count = Appointments::findByCondition($criteria)
                        ->andWhere("date_format(AppointmentDate,'%Y-%m-%d') = :fecha",[':fecha'=> $this->_date])
                        ->andWhere("date_format(AppointmentHour,'%H') = :hora",[':hora'=> $this->_hour])
                        ->count('*');
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    private function getCode(){
        try {
            
            $this->Code = NULL;
            $type = Type::findOne(['Id'=>  $this->IdType]);
            $idService = (int) $this->IdServiceCentre ? $this->idServiceCentre->MBCode ? $this->idServiceCentre->MBCode:$this->IdServiceCentre:0;
            $servicecentre = str_pad($idService, 3, '0', STR_PAD_LEFT);
            $fdate = date_format(new \DateTime($this->AppointmentDate),'Ymd');
            $fhour = \Yii::$app->formatter->asTime($this->AppointmentHour, 'hh');
            $fsecond = date('is');
            $corr = str_pad(((int) $this->_count + 1), 3,'0',STR_PAD_LEFT);
            $this->Code = $type->Code.'-'.$servicecentre.'-'.$fdate.$fhour.$fsecond.$corr;
            $this->_generateShortCode();

        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    private function _generateShortCode(){
        try {
            $length = $this->_getLengthCode();
            $this->ShortCode = \Yii::$app->customFunctions->getRandomString($length, FALSE, 2);
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    private function _getLengthCode(){
        try {
            $lenght = 5;
            $value = Settingsdetail::find()->where(['settingsdetail.Code'=>'SHCODE'])
                    ->joinWith('idSetting b',true)
                    ->andWhere(['b.KeyWord'=>'Appointment','b.Code'=>'SHCODE'])
                    ->one();
            if($value != NULL){
                $lenght = $value->Value;
            }
            return $lenght;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function getDayBefore(){
        try {
            $day = 1;
            $value = Settingsdetail::find()->where(['settingsdetail.Code'=>'DFLT'])
                    ->joinWith('idSetting b',true)
                    ->joinWith('idState c',true)
                    ->andWhere(['b.KeyWord'=>'Appointment','b.Code'=>'QTYDA'])
                    ->andWhere(['c.Code'=> Settings::STATUS_ACTIVE])
                    ->one();
            if($value != NULL){
                $day = $value->Value;
            }
            return $day;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getMaxDays(){
        try {
            $day = 0;
            $value = Settingsdetail::find()->where(['settingsdetail.Code'=>'MAXDT'])
                    ->joinWith('idSetting b',true)
                    ->joinWith('idState c',true)
                    ->andWhere(['b.KeyWord'=>'Appointment','b.Code'=>'QTYDA'])
                    ->andWhere(['c.Code'=> Settings::STATUS_ACTIVE])
                    ->one();
            if($value != NULL){
                $day = $value->Value;
            }
            return $day;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function getStartHour(){
        try {
            $val = 8;
            $value = \backend\models\Settingsdetail::find()->where(['settingsdetail.Code'=>'LVMI'])
                    ->joinWith('idSetting b',true)
                    ->andWhere(['b.KeyWord'=>'Appointment','b.Code'=>'HOR'])
                    ->one();
            if($value != NULL){
                $val = $value->Value;
            }
            return $val;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getAvailableHours(){
        try {
            $day = ((int) date_format(new \DateTime($this->AppointmentDate),'w'))+1;
            $_hour = (int) \Yii::$app->formatter->asTime($this->AppointmentHour, 'php:H');
            $code_start = NULL;
            $code_end = NULL;
            if($day == 7){
                $code_start = Appointmentservicesetting::WEEKEND_START_SCHEDULE;
                $code_end = Appointmentservicesetting::WEEKEND_END_SCHEDULE;
            } else {
                $code_start = Appointmentservicesetting::NORMAL_START_MORNING_SCHEDULE;
                $code_end = Appointmentservicesetting::NORMAL_END_AFTERNOON_SCHEDULE;
            }
            
            $start = Settingsdetail::find()
                    //->andFilterWhere(['in','settingsdetail.Code',$code])
                    ->joinWith('idSetting b', TRUE)
                    ->joinWith('idState c', TRUE)
                    ->where([
                            'c.Code'=> Settings::STATUS_ACTIVE,
                            'b.KeyWord'=>'Servicecentres',
                            'b.Code'=>'HOR',
                            'settingsdetail.Code'=>$code_start,
                        ])
                    ->one();
            
            $end = Settingsdetail::find()
                    //->andFilterWhere(['in','settingsdetail.Code',$code])
                    ->joinWith('idSetting b', TRUE)
                    ->joinWith('idState c', TRUE)
                    ->where([
                            'c.Code'=> Settings::STATUS_ACTIVE,
                            'b.KeyWord'=>'Servicecentres',
                            'b.Code'=>'HOR',
                            'settingsdetail.Code'=>$code_end,
                        ])
                    ->one()
                    ;
            $i = ($start != NULL ? $start->Value:"08:00:00");
            $f = ($end != NULL ? $end->Value:($day != 7 ? "16:00:00":"12:00:00"));
            
            $_i = explode(":", $i);
            $_f = explode(":", $f);
            
            $ini = $_i[0];
            $final = (int) $_f[0];
            unset($_f[0]);
            $complement = implode(":", $_f);
            
            $query = new Query();
            $query->select(['a.AppointmentHour','count(1) Quantity'])
                    ->from('appointments a')
                    ->innerJoin('state b', 'a.IdState = b.Id')
                    ->where(['a.IdServiceCentre'=> $this->IdServiceCentre, 'b.Code'=> self::ACTIVE_STATUS])
                    ->andWhere("date_format(a.AppointmentDate,'%Y-%m-%d') = :fecha"
                            ,[':fecha'=> Yii::$app->formatter->asDate($this->AppointmentDate, 'php:Y-m-d')])
                    ->groupBy(['a.AppointmentHour'])
                    ->orderBy(['a.AppointmentHour'=> SORT_ASC]);
            $appointments = $query->all();
            
            # Appointment Quatity by hour
            $hours = [];
            foreach($appointments as $app){
                $hours[$app["AppointmentHour"]] = $app["Quantity"];
            }
            $disabledHours = [];
            
            $settingHour = Appointmentservicesetting::find()
                        ->select(['appointmentservicesetting.*','b.Code'])
                        ->joinWith('idState b', FALSE)
                        ->where(['IdServiceCentre'=> $this->IdServiceCentre,'IdDay'=> $day])
                        ->orderBy('IdHour');
            $settingHours = $settingHour->asArray()->all();
            $setHours = [];
            foreach ($settingHours as $set){
                $setHours[$set["IdHour"]] = ['Quantity'=>$set["Quantity"],'Code'=>$set["Code"]];
            }
            
            foreach ($hours as $key => $value){
                $_time = explode(":", $key);
                $_ha = (int) $_time[0];
                $p = (int) $setHours[$_ha]["Quantity"];
                $value = (int) $value;
                if(($value >= $p && $setHours[$_ha]["Code"] == self::ACTIVE_STATUS) || ($setHours[$_ha]['Code'] == self::INACTIVE_STATUS) ){
                    array_push($disabledHours, $_ha);
                }
            }
            
            $c = 0;
            $list = $this->response_format == 'GRID' ? "":[];
            for($j = $ini; $j <= ($final -1); $j++){
                if(!in_array($j, $disabledHours)){
                    $_h = str_pad($j, 2,'0',STR_PAD_LEFT).":".$complement;
                    $_hPlus = (str_pad(($j+1), 2,'0',STR_PAD_LEFT)).":".$complement;
                    $hour = \Yii::$app->formatter->asTime($_h, 'php:h:i a');
                    $hourPlus = \Yii::$app->formatter->asTime($_hPlus, 'php:h:i a');
                    if($this->response_format == 'GRID'){
                        $h = $hour." - ".$hourPlus;
                        $a = Html::a($h,'javascript:selectHour("'.$hour.'")',['class'=>'btn btn-'.($j == $_hour ? 'success':'primary')]);
                        $list .= Html::tag('div', $a, ['class'=>'col-xs-push-4','style'=>'margin-bottom: 10px; ']);
                    } else {
                        array_push($list, $hour);
                    }
                    $c++;
                }
            }
            if($c == 0 && $this->response_format == 'GRID'){
                $list .= Html::tag('div', 'No hay horarios disponibles para el día seleccionado' , ['class'=>'alert alert-danger','role'=>'alert']);
            }
            return $list;
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function sendMailConfirmationBatch($condition = [], $action = 'create'){
        try {
            $url = Url::to(\Yii::$app->params["mainSiteUrl"]["url"]);
            $mails = [
                'SentMail'=>[],
                'FailedMail'=>[],
            ];
            switch ($action){
                case 'create':
                    $subject = 'Creación ';
                    $state = 'Registrada';
                    break;
                case 'update':
                    $subject = 'Reprogramación';
                    $state = 'Reprogramada';
                    break;
                case 'cancel':
                    $subject = 'Cancelación';
                    $state = 'Cancelada';
                    break;
                case 'reminder':
                    $subject = 'Recordatorio ';
                    $state = 'Registrada';
                    break;
                default :
                    $subject = 'Creación ';
                    $state = 'Registrada';
            }
            $status = State::findOne(['KeyWord'=> StringHelper::basename(self::className()),'Code'=> self::ACTIVE_STATUS])->Id;
            $appointments = self::find();
            if(isset($condition["citizenName"])){
                $appointments->andWhere('citizen.Name LIKE "%' . $condition["citizenName"] . '%" ' . //This will filter when only first name is searched.
                    'OR citizen.LastName LIKE "%' . $condition["citizenName"] . '%" '. //This will filter when only last name is searched.
                    'OR CONCAT(citizen.Name, " ", citizen.LastName) LIKE "%' . $condition["citizenName"] . '%"' //This will filter when full name is searched.
                );
                unset($condition["citizenName"]);
            }
            $appointments->where($condition)
                    ->andWhere("IdState = :idState",[':idState'=>$status]);
            if($this->_date != NULL){
                    $appointments->andWhere("date_format(AppointmentDate,'$this->dbDateFormat') = :fecha",[':fecha'=> $this->_date]);
            } 
            $models = $appointments->all();
            
            if($models != NULL){
                foreach ($models as $m){
                    $model = Appointments::findOne($m["Id"]);
                    if(!empty($model->idCitizen->Email)){
                        $body = '<ul> '
                                . '<li>Fecha: <b>'.$model->getAppointmentDate().'</b></li>'
                                . '<li>Hora: <b>'.$model->getAppointmentHour().'</b></li>'
                                . '<li><b>'.$state.'</b></li>'
                                . '<li>Duicentro: <b>'.$model->idServiceCentre->Name.'</b></li>'
                                .(!empty($model->idServiceCentre->Address) ? '<li>Dirección: '.$model->idServiceCentre->Address.'</li>':'')
                                . '<li>Tipo Trámite: <b>'.$model->idType->Name.'</b></li>'
                                . '<li>Código de Confirmación:<br/>'
                                . '<h2>'.$model->ShortCode.'</h2>'
                                . '</li>'
                                . '<li>Código: <strong>'.$model->Code.'</strong>'
                                . '</li>'
                                . '</ul>';
                        $footer = "<br/>"
                                . "<b>*Debe presentarse al Duicentro 10 minutos antes de la cita registrada</b><br/>"
                                . "<b>**De no presentarse a la cita a la hora registrada, la cita será cancelada</b><br/>"
                                . "<span style='color:red; font-weight: bolder'><h2>*** Debe presentarse al Duicentro seleccionado</h2></span><br/>"
                                . "<br/>"
                                . "<b>Visite ".$url." para más información<br/>"
                                ;
                        
                        $content = [
                            'title'=>$subject.' de Cita Programada',
                            'body'=>$body,
                            'footer'=>$footer,
                        ];
                        $email = Yii::$app
                            ->mailer
                            ->compose(
                                ['html' => '@backend/mail/default-html'],
                                ['data' => $content]
                            )
                            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                            ->setTo($model->idCitizen->Email)
                            ->setSubject($content['title'])
                            ->send();
                        if($email){
                            array_push($mails["SentMail"],$model->idCitizen->Email);
                        } else {
                            array_push($mails["FailedMail"],$model->idCitizen->Email);
                        }
                    }
                    
                }
            }
            if($action == 'reminder'){
                $this->sendAdminMail($mails);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    private function sendAdminMail($mails = []){
        try {
            $html_response = "<b>Envíos Exitosos: </b>"
                    . "<br/><ul>";
            foreach ($mails["SentMail"] as $mail) {
                $html_response .= "<li>$mail</li>";
            }
            $html_response .= "</ul>";
            $html_response .= "<b>Envíos Fallidos: </b>"
                    . "<br/><ul>";
            foreach ($mails["FailedMail"] as $mail) {
                $html_response .= "<li>$mail</li>";
            }
            $html_response .= "</ul>";
            
            $content_admin = [
                'title' => 'Registro de envío de Correo Automático',
                'body'=> $html_response,
                'footer' => '',
            ];
            
            $email = Yii::$app
                        ->mailer
                        ->compose(
                            ['html' => '@backend/mail/default-html'],
                            ['data' => $content_admin]
                        )
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                        ->setTo(\Yii::$app->params['supportEmail'])
                        ->setSubject($content_admin['title'])
                        ->send();
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
    
    
    public function dateValidation(){
        try {
            if(empty($this->IdServiceCentre)){
                $this->addError('IdServiceCentre', 'Debe seleccionar Duicentro');
                return FALSE;
            } elseif(empty ($this->AppointmentDate)){
                $this->addError('AppointmenDate', 'Campo Fecha no puede quedar vacío');
                return FALSE;
            }
            $query = Holidays::find()
                    ->joinWith('idType b')
                    ->joinWith('idFrequencyType c')
                    ->joinWith('idState d')
                    ->leftJoin('holidaysdetails e', 'e.IdHoliday = holidays.Id')
                    ->where(['d.Code'=> Holidays::STATUS_ACTIVE])
                    ->andWhere("(
                            ( /*General*/
                                b.Code = :generalCode
                                AND
                                ( /*GnrlConditions*/
                                    ( /*GnrDt01*/
                                        ( /*YearEval*/
                                            ( /*dayEval*/
                                                :day BETWEEN date_format(holidays.DateStart,'%d') AND date_format(holidays.DateEnd,'%d')
                                            ) /*dayEval*/
                                            AND ( /*monthEval*/
                                                :month BETWEEN date_format(holidays.DateStart,'%m') AND date_format(holidays.DateEnd,'%m')
                                            ) /*monthEval*/
                                        ) /*YearEval*/
                                        AND c.Code = :yearCode
                                    ) /*GnrDt01*/
                                    OR (  /*GnrDt02*/
                                        :date BETWEEN date_format(holidays.DateStart,'%d-%m-%Y') AND date_format(holidays.DateEnd,'%d-%m-%Y') 
                                        AND c.Code = :specialCode
                                    ) /*GnrDt02*/
                                ) /*GnrlConditions*/
                            ) /*General*/
                            OR ( /*Specific*/
                                b.Code = :specificCode AND e.IdServiceCentre = :idCentre
                                AND 
                                ( /*SpecificConditions*/
                                    ( /*SpcfDt01*/
                                        ( /*YearEval*/
                                            ( /*dayEval*/
                                                :day BETWEEN date_format(holidays.DateStart,'%d') AND date_format(holidays.DateEnd,'%d')
                                            ) /*dayEval*/
                                            AND ( /*monthEval*/
                                                :month BETWEEN date_format(holidays.DateStart,'%m') AND date_format(holidays.DateEnd,'%m')
                                            ) /*monthEval*/
                                        ) /*YearEval*/
                                        AND c.Code = :yearCode
                                    ) /*SpcfDt01*/
                                     OR ( /*SpcfDt02*/
                                        :date BETWEEN date_format(holidays.DateStart,'%d-%m-%Y') AND date_format(holidays.DateEnd,'%d-%m-%Y') 
                                        AND c.Code = :specialCode 
                                    ) /*SpcfDt02*/
                                ) /*SpecificConditions*/
                            ) /*Specific*/
                        )"
                    ,[
                        ':generalCode' => Holidays::TYPE_GENERAL,
                        ':yearCode'=> Holidays::TYPE_FREQ_ANNUAL,
                        ':specificCode'=> Holidays::TYPE_SPECIFIC,
                        ':specialCode'=> Holidays::TYPE_FREQ_SPECIAL,
                        ':idCentre'=> $this->IdServiceCentre,
                        ':date' => \Yii::$app->formatter->asDate($this->AppointmentDate, 'php:d-m-Y'),
                        ':day' => \Yii::$app->formatter->asDate($this->AppointmentDate, 'php:d'),
                        ':month' => \Yii::$app->formatter->asDate($this->AppointmentDate, 'php:m'),
                    ]);
            $count = $query->count('*');
            if($count > 0){
                $this->addError('AppointmentDate', 'Día Seleccionado no se encuentra disponible. Favor Seleccione otra fecha ');
            }
             
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
