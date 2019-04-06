<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Insurancedck;

/**
 * insurancedckSearch represents the model behind the search form about `app\models\Insurancedck`.
 */
class insurancedckSearch extends Insurancedck
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'insurance_id', 'management_area', 'isoneself', 'iscompany', 'isbank', 'iswt', 'iscontract'], 'integer'],
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
        $query = Insurancedck::find();

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
            'farms_id' => $this->farms_id,
            'insurance_id' => $this->insurance_id,
            'management_area' => $this->management_area,
            'isoneself' => $this->isoneself,
            'iscompany' => $this->iscompany,
            'isbank' => $this->isbank,
            'iswt' => $this->iswt,
            'iscontract' => $this->iscontract,
        ]);

        return $dataProvider;
    }
}
