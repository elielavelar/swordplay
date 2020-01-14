<?php

namespace backend\models;

use Yii;
use backend\models\Ministries;
use common\models\Catalogs;
use common\models\Catalogversions;
use common\models\Catalogdetails;
use common\models\State;

use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ministryprofiles".
 *
 * @property int $Id
 * @property int $IdMinistry
 * @property int $IdProfile related with catalogdetails table
 * @property int $Sort
 * @property int $IdState
 * @property string $CustomName
 * @property string $Description
 *
 * @property Catalogdetails $profile
 * @property Ministries $ministry
 * @property State $state
 */
class Ministryprofiles extends \yii\db\ActiveRecord
{
    
    const CODE_CATALOG = 'PROFILE';
    const DEFAULT_SORT_VALUE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ministryprofiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdMinistry', 'IdProfile', 'IdState'], 'required'],
            [['IdMinistry', 'IdProfile', 'IdState','Sort'], 'integer'],
            [['Description'], 'string'],
            [['CustomName'], 'string','max' => 100],
            [['IdProfile'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogdetails::className(), 'targetAttribute' => ['IdProfile' => 'Id']],
            [['IdMinistry'], 'exist', 'skipOnError' => true, 'targetClass' => Ministries::className(), 'targetAttribute' => ['IdMinistry' => 'Id']],
            [['IdState'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['IdState' => 'Id']],
            ['Sort', 'default', 'value' => self::DEFAULT_SORT_VALUE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdMinistry' => 'Ministerio',
            'IdProfile' => 'Cargo',
            'IdState' => 'Estado',
            'CustomName' => 'Nombre Personalizado',
            'Sort' => 'Orden',
            'Description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Catalogdetails::className(), ['Id' => 'IdProfile']);
    }
    
    public function getProfiles(){
        $model = Catalogdetails::find()
                #->select(['catalogdetails.Id','catalogdetails.Name','b.Id'])
                ->joinWith('catalogVersion b')
                ->innerJoin('catalogs c', 'b.IdCatalog = c.Id')
                ->innerJoin('state d', 'd.Id = c.IdState')
                ->innerJoin('state e', 'e.Id = b.IdState')
                ->where([
                    'c.KeyWord' => StringHelper::basename(Ministries::class),
                    'c.Code' => self::CODE_CATALOG,
                    'd.Code' => Catalogs::STATUS_ACTIVE,
                    'e.Code' => Catalogversions::STATE_ACTIVE,
                    'catalogdetails.KeyWord' => $this->ministry->Code,
                ])->all();
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistry()
    {
        return $this->hasOne(Ministries::className(), ['Id' => 'IdMinistry']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['Id' => 'IdState']);
    }
    
    public function getStates(){
        $model = State::findAll(['KeyWord' => StringHelper::basename(self::class)]);
        return ArrayHelper::map($model, 'Id', 'Name');
    }
    
    public function beforeSave($insert) {
        $this->CustomName = empty($this->CustomName) ? null : $this->CustomName;
        return parent::beforeSave($insert);
    }
}
