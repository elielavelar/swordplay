<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Servicecentres;

/**
 * ServicecentresSearch represents the model behind the search form about `common\models\Servicecentres`.
 */
class ServicecentresSearch extends Servicecentres
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'IdCountry', 'IdState', 'IdType','IdZone'], 'integer'],
            [['Name', 'Address' ], 'safe'],
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
        $query = Servicecentres::find();

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
            'IdCountry' => $this->IdCountry,
            'IdState' => $this->IdState,
            'IdType' => $this->IdType,
            'IdZone' => $this->IdZone,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Address', $this->Address]);

        return $dataProvider;
    }
}
