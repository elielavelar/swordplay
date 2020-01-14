<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Ministryperiodvotingcandidates;

/**
 * MinistryperiodvotingcandidatesSearch represents the model behind the search form of `backend\models\Ministryperiodvotingcandidates`.
 */
class MinistryperiodvotingcandidatesSearch extends Ministryperiodvotingcandidates
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdVoting', 'IdMember', 'IdState', 'Sort'], 'integer'],
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
        $query = Ministryperiodvotingcandidates::find();

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
            'IdVoting' => $this->IdVoting,
            'IdMember' => $this->IdMember,
            'IdState' => $this->IdState,
            'Sort' => $this->Sort,
        ]);

        return $dataProvider;
    }
}
