<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Extendedmodelkeys;

/**
 * ExtendedmodelkeySearch represents the model behind the search form of `common\models\Extendedmodelkeys`.
 */
class ExtendedmodelkeySearch extends Extendedmodelkeys
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'IdExtendedModel', 'IdState'], 'integer'],
            [['AttributeKeyName', 'AttributeKeyValue', 'Description'], 'safe'],
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
        $query = Extendedmodelkeys::find();

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
            'IdExtendedModel' => $this->IdExtendedModel,
            'IdState' => $this->IdState,
        ]);

        $query->andFilterWhere(['like', 'AttributeKeyName', $this->AttributeKeyName])
            ->andFilterWhere(['like', 'AttributeKeyValue', $this->AttributeKeyValue])
            ->andFilterWhere(['like', 'Description', $this->Description]);

        return $dataProvider;
    }
}
