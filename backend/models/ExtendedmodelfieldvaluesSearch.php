<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Extendedmodelfieldvalues;

/**
 * ExtendedmodelfieldvaluesSearch represents the model behind the search form of `common\models\Extendedmodelfieldvalues`.
 */
class ExtendedmodelfieldvaluesSearch extends Extendedmodelfieldvalues
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdExtendedModelRecord', 'IdExtendedModelField', 'IdFieldCatalog', 'CustomValue'], 'integer'],
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
        $query = Extendedmodelfieldvalues::find();

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
            'IdExtendedModelRecord' => $this->IdExtendedModelRecord,
            'IdExtendedModelField' => $this->IdExtendedModelField,
            'IdFieldCatalog' => $this->IdFieldCatalog,
            'CustomValue' => $this->CustomValue,
        ]);

        $query->andFilterWhere(['like', 'Value', $this->Value])
            ->andFilterWhere(['like', 'Description', $this->Description]);

        return $dataProvider;
    }
}
