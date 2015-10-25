<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sales;

/**
 * salesSearch represents the model behind the search form about `app\models\Sales`.
 */
class salesSearch extends Sales
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'planting_id','farms_id'], 'integer'],
            [['whereabouts'], 'safe'],
            [['volume', 'price'], 'number'],
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
        $query = Sales::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'planting_id' => $this->planting_id,
        	'farms_id' => $this->farms_id,
            'volume' => $this->volume,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'whereabouts', $this->whereabouts]);

        return $dataProvider;
    }
}
