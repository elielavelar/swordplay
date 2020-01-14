<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Useroptions;

/**
 * UseroptionSearch represents the model behind the search form about `backend\models\Useroptions`.
 */
class UseroptionSearch extends Useroptions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdUser', 'IdOption', 'Enabled'], 'integer'],
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
        $query = Useroptions::find();

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
            'IdUser' => $this->IdUser,
            'IdOption' => $this->IdOption,
            'Enabled' => $this->Enabled,
        ]);

        return $dataProvider;
    }
}
