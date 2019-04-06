<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PlantPrice;

/**
 * plantpriceSearch represents the model behind the search form about `app\models\PlantPrice`.
 */
class plantpriceSearch extends PlantPrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'years'], 'integer'],
            [['plant'], 'safe'],
            [['price'], 'number'],
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
        $query = PlantPrice::find();

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
            'price' => $this->price,
            'years' => $this->years,
        ]);

        $query->andFilterWhere(['like', 'plant', $this->plant]);
//         ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
