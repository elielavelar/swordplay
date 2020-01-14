<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Ministryvotingballotvote;

/**
 * MinistryvotingballotvoteSearch represents the model behind the search form of `backend\models\Ministryvotingballotvote`.
 */
class MinistryvotingballotvoteSearch extends Ministryvotingballotvote
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdVotingBallot', 'IdCandidate', 'IdMinistryProfile'], 'integer'],
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
        $query = Ministryvotingballotvote::find();

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
            'IdVotingBallot' => $this->IdVotingBallot,
            'IdCandidate' => $this->IdCandidate,
            'IdMinistryProfile' => $this->IdMinistryProfile,
        ]);

        return $dataProvider;
    }
}
