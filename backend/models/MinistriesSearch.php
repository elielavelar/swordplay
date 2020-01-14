<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Ministries;

/**
 * MinistriesSearch represents the model behind the search form of `backend\models\Ministries`.
 */
class MinistriesSearch extends Ministries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdState', 'IdType', 'IdValidityType','IdEnvironmentType'], 'integer'],
            [['Name', 'Code', 'Description'], 'safe'],
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
        $query = Ministries::find();

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
            'IdState' => $this->IdState,
            'IdType' => $this->IdType,
            'IdValidityType' => $this->IdValidityType,
            'IdEnvironmentType' => $this->IdEnvironmentType,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Code', $this->Code])
            ->andFilterWhere(['like', 'Description', $this->Description]);

        return $dataProvider;
    }
}
