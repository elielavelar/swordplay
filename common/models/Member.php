<?php

namespace common\models;

use Yii;
use common\models\Servicecentres;
use common\models\State;
use backend\models\Attachments;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use Exception;

/**
 * This is the model class for table "member".
 *
 * @property int $Id
 * @property string $FirstName
 * @property string $SecondName
 * @property string $ThirdName
 * @property string $FirstLastName
 * @property string $SecondLastName
 * @property string $Gender
 * @property int $IdServiceCentre
 * @property string $Code
 * @property int $IdState
 * @property int $IdAttachmentPicture
 * @property string $BirthDate
 * @property string $ConversionDate
 * @property string $BaptismDate
 * @property string $DeceaseDate
 *
 * @property Servicecentres $serviceCentre
 * @property State $state
 * @property Attachments $attachmentPicture
 */
class Member extends \yii\db\ActiveRecord {

    const GENDER_FEMALE = 'F';
    const GENDER_MALE = 'M';
    const STATUS_ACTIVE = 'ACT';
    const STATUS_INACTIVE = 'INA';
    const STATUS_DECESASE = 'DSC';

    public $displayName = null;
    public $path = null;
    public $photo = null;
    const DEFAULT_IMG = 'img/avatar.png';
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['FirstName', 'FirstLastName', 'IdServiceCentre', 'IdState', 'Gender'], 'required'],
            [['IdServiceCentre', 'IdState', 'IdAttachmentPicture'], 'integer'],
            [['BirthDate', 'ConversionDate', 'BaptismDate', 'DeceaseDate'], 'safe'],
            [['FirstName', 'SecondName', 'ThirdName', 'FirstLastName', 'SecondLastName'], 'string', 'max' => 30],
            [['Code'], 'string', 'max' => 20],
            [['Gender'], 'string', 'max' => 1],
            [['IdServiceCentre'], 'exist', 'skipOnError' => true, 'targetClass' => Servicecentres::className(), 'targetAttribute' => ['IdServiceCentre' => 'id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'id']],
            [['IdAttachmentPicture'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::className(), 'targetAttribute' => ['IdAttachmentPicture' => 'id']],
            [['Gender'], 'in', 'range' => [self::GENDER_FEMALE, self::GENDER_MALE]],
            [['photo'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'Id' => 'ID',
            'FirstName' => 'Primer Nombre',
            'SecondName' => 'Segundo Nombre',
            'ThirdName' => 'Tercer Nombre',
            'FirstLastName' => 'Primer Apellido',
            'SecondLastName' => 'Segundo Apellido',
            'IdServiceCentre' => 'Filial',
            'Code' => 'Código',
            'IdState' => 'Estado',
            'BirthDate' => 'Fecha Nacimiento',
            'ConversionDate' => 'Fecha de Conversión',
            'BaptismDate' => 'Fecha Bautizo',
            'DeceaseDate' => 'Fecha Fallecimiento',
            'IdAttachmentPicture' => 'Fotografía',
            'Gender' => 'Género',
            'photo' => 'Fotografía',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceCentre() {
        return $this->hasOne(Servicecentres::className(), ['id' => 'IdServiceCentre']);
    }

    public function getServiceCentres() {
        $model = Servicecentres::find()
                        ->joinWith('state b')
                        ->where([
                            'b.Code' => Servicecentres::STATE_ACTIVE
                        ])->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState() {
        return $this->hasOne(State::className(), ['id' => 'IdState']);
    }

    public function getStates() {
        $model = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($model, 'Id', 'Name');
    }

    public function getGenders() {
        $options = [
            ['Id' => self::GENDER_FEMALE, 'Name' => 'Femenino'],
            ['Id' => self::GENDER_MALE, 'Name' => 'Masculino'],
        ];
        return ArrayHelper::map($options, 'Id', 'Name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachmentPicture() {
        return $this->hasOne(Attachments::className(), ['id' => 'IdAttachmentPicture']);
    }
    
    public function afterFind() {
        $this->displayName = $this->FirstName.' '.$this->FirstLastName;
        $this->path = $this->IdAttachmentPicture ? $this->attachmentPicture->path : Yii::$app->getUrlManager()->createAbsoluteUrl(self::DEFAULT_IMG);
        return parent::afterFind();
    }

    public function beforeSave($insert) {
        try {
            if ($this->isNewRecord) {
                $this->_getCode();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return parent::beforeSave($insert);
    }

    private function _getCode() {
        try {
            $countryCode = $this->IdServiceCentre ? ($this->serviceCentre->IdCountry ? $this->serviceCentre->country->Code : 'GRL') : 'GRL';
            $id =((int) $this->_getLastId()) + 1;
            $this->Code = $countryCode . str_pad($this->IdServiceCentre, 3, '0', STR_PAD_LEFT) . str_pad($id, 4, '0', STR_PAD_LEFT);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function _getLastId() {
        try {

            $criteria = [];
            if ($this->IdServiceCentre) {
                $criteria['b.IdCountry'] = $this->serviceCentre->IdCountry;
            }
            return self::find()
                            ->innerJoin('servicecentres b', 'b.Id = member.IdServiceCentre')
                            ->where($criteria)
                            ->max('member.Id');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
