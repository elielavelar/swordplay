<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Catalogdetails;

/**
 * CatalogdetailSearch represents the model behind the search form of `common\models\Catalogdetails`.
 */
class CatalogdetailSearch extends Catalogdetails
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdCatalogVersion', 'IdState', 'IdType'], 'integer'],
            [['Name', 'KeyWord', 'Code', 'Description'], 'safe'],
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
        $query = Catalogdetails::find();

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
            'IdCatalogVersion' => $this->IdCatalogVersion,
            'IdState' => $this->IdState,
            'IdType' => $this->IdType,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'KeyWord', $this->KeyWord])
            ->andFilterWhere(['like', 'Code', $this->Code])
            ->andFilterWhere(['like', 'Description', $this->Description]);

        return $dataProvider;
    }
}
