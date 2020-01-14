<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Appointments;

/**
 * AppointmentsSearch represents the model behind the search form about `common\models\Appointments`.
 */
class AppointmentsSearch extends Appointments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'IdCitizen', 'IdState', 'IdServiceCentre','IdType'], 'integer'],
            [['citizenName','Code','ShortCode','RegistrationMethod'], 'string'],
            [['AppointmentDate','citizenName','Code','ShortCode'
                #'AppointmentHour',
                #,'hourDate'
                ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Appointments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        /*
         [
                'Id',
                'citizenName' => [
                    'asc' => ['Name' => SORT_ASC, 'LastName' => SORT_ASC],
                    'desc' => ['Name' => SORT_DESC, 'LastName' => SORT_DESC],
                    'label' => 'Ciudadano',
                    'default' => SORT_ASC
                ],
                'IdState',
                'AppointmentDate',
                'IdServiceCentre',
                'IdType',
            ]
         */
        
        $dataProvider->setSort([
            'attributes' => array_merge($this->attributes(),[
                'citizenName' => [
                    'asc' => ['Name' => SORT_ASC, 'LastName' => SORT_ASC],
                    'desc' => ['Name' => SORT_DESC, 'LastName' => SORT_DESC],
                    'label' => 'Ciudadano',
                    'default' => SORT_ASC
                ],
                /*'AppointmentHour'=>[
                    'asc'=>["date_format(AppointmentDate,'%H:%i')" => SORT_ASC],
                    'desc'=>["date_format(AppointmentDate,'%H:%i')" => SORT_DESC],
                    'label'=>'Hora Cita',
                    'default'=>SORT_ASC,
                ],*/
            ]),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'appointments.Id' => $this->Id,
            'appointments.IdCitizen' => $this->IdCitizen,
            #'appointments.AppointmentDate' => $this->AppointmentDate,
            'appointments.IdState' => $this->IdState,
            'appointments.IdType' => $this->IdType,
            'appointments.ShortCode' => $this->ShortCode,
            'appointments.IdServiceCentre' => $this->IdServiceCentre,
            'appointments.RegistrationMethod' => $this->RegistrationMethod,
        ]);
        if(!empty($this->AppointmentDate)){
            $query->andWhere("date_format(AppointmentDate,'%Y-%m-%d') = :fecha",[':fecha'=> date_format(new \DateTime($this->AppointmentDate),'Y-m-d')]);
        }
        if(!empty($this->AppointmentHour)){
            $query->andWhere("date_format(AppointmentDate,'%H') = :hora",[':hora'=> \Yii::$app->formatter->asTime($this->AppointmentHour,'php:H')]);
        }
//        if(!empty($this->AppointmentDate)){
//            #$query->andWhere("date_format(AppointmentDate,'%H:%i') = :hora",[':hora'=> Yii::$app->formatter->asTime($this->hourDate,'php:H:i')]);
//            $fecha = $this->AppointmentDate != NULL ?  Yii::$app->formatter->asDate($this->AppointmentDate):date('Y-m-d');
//            $query->andWhere("date_format(AppointmentDate,'%Y-%m-%d') = :fecha",[':fecha'=>$fecha]);
//        }
        
        
        $query->innerJoin('citizen', 'citizen.Id = appointments.IdCitizen');
        
         // filter by person full name
        $query->andWhere('citizen.Name LIKE "%' . $this->citizenName . '%" ' . //This will filter when only first name is searched.
            'OR citizen.LastName LIKE "%' . $this->citizenName . '%" '. //This will filter when only last name is searched.
            'OR CONCAT(citizen.Name, " ", citizen.LastName) LIKE "%' . $this->citizenName . '%"' //This will filter when full name is searched.
        );
        $query->orderBy(['AppointmentDate'=>'ASC','AppointmentHour'=>'ASC']);
        
        return $dataProvider;
    }
}
