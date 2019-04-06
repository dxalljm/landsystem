<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Inputproduct;

/**
 * inputproductSearch represents the model behind the search form about `app\models\Inputproduct`.
 */
class inputproductSearch extends Inputproduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'father_id'], 'integer'],
            [['fertilizer'], 'safe'],
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
        $query = Inputproduct::find();

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
            'father_id' => $this->father_id,
        ]);

        $query->andFilterWhere(['like', 'fertilizer', $this->fertilizer]);
//        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
