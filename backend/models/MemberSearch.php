<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Member;

/**
 * MemberSearch represents the model behind the search form of `common\models\Member`.
 */
class MemberSearch extends Member
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdServiceCentre', 'IdState'], 'integer'],
            [['FirstName', 'SecondName', 'ThirdName', 'FirstLastName', 'SecondLastName', 'Gender', 'Code', 'BirthDate', 'ConversionDate', 'BaptismDate', 'DeceaseDate'], 'safe'],
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
        $query = Member::find();

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
            'IdServiceCentre' => $this->IdServiceCentre,
            'IdState' => $this->IdState,
            'BirthDate' => $this->BirthDate,
            'ConversionDate' => $this->ConversionDate,
            'BaptismDate' => $this->BaptismDate,
            'DeceaseDate' => $this->DeceaseDate,
        ]);

        $query->andFilterWhere(['like', 'FirstName', $this->FirstName])
            ->andFilterWhere(['like', 'SecondName', $this->SecondName])
            ->andFilterWhere(['like', 'ThirdName', $this->ThirdName])
            ->andFilterWhere(['like', 'FirstLastName', $this->FirstLastName])
            ->andFilterWhere(['like', 'SecondLastName', $this->SecondLastName])
            ->andFilterWhere(['like', 'Gender', $this->Gender])
            ->andFilterWhere(['like', 'Code', $this->Code]);

        return $dataProvider;
    }
}
