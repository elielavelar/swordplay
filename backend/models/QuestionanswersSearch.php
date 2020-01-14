<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Questionanswers;

/**
 * QuestionanswersSearch represents the model behind the search form of `common\models\Questionanswers`.
 */
class QuestionanswersSearch extends Questionanswers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdQuestion', 'TrueValue', 'Sort'], 'integer'],
            [['Value', 'Description'], 'safe'],
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
        $query = Questionanswers::find();

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
            'IdQuestion' => $this->IdQuestion,
            'TrueValue' => $this->TrueValue,
            'Sort' => $this->Sort,
        ]);

        $query->andFilterWhere(['like', 'Value', $this->Value])
            ->andFilterWhere(['like', 'Description', $this->Description]);

        return $dataProvider;
    }
}
