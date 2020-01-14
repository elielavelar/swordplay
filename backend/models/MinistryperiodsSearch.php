<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Ministryperiods;

/**
 * MinistryperiodsSearch represents the model behind the search form of `backend\models\Ministryperiods`.
 */
class MinistryperiodsSearch extends Ministryperiods
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdMinistryServiceCentre', 'IdState'], 'integer'],
            [['Name','StartDate', 'EndDate', 'Description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Ministryperiods::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'Id' => $this->Id,
            'IdMinistryServiceCentre' => $this->IdMinistryServiceCentre,
            'IdState' => $this->IdState,
            'StartDate' => $this->StartDate,
            'EndDate' => $this->EndDate,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
                ->andFilterWhere(['like', 'Description', $this->Description])
                ;

        return $dataProvider;
    }
}
