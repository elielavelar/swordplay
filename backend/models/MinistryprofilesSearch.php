<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Ministryprofiles;

/**
 * MinistryprofilesSearch represents the model behind the search form of `backend\models\Ministryprofiles`.
 */
class MinistryprofilesSearch extends Ministryprofiles
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdMinistry', 'IdProfile', 'IdState','Sort'], 'integer'],
            [['Description','CustomName'], 'safe'],
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
        $query = Ministryprofiles::find();

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
            'IdMinistry' => $this->IdMinistry,
            'IdProfile' => $this->IdProfile,
            'IdState' => $this->IdState,
            'Sort' => $this->Sort,
        ]);

        $query->andFilterWhere(['like', 'CustomName', $this->CustomName])
                ->andFilterWhere(['like', 'Description', $this->Description]);

        return $dataProvider;
    }
}
