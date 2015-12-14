<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Plantingstructure;
use app\models\Theyear;
/**
 * plantingstructureSearch represents the model behind the search form about `app\models\Plantingstructure`.
 */
class plantingstructureSearch extends Plantingstructure
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'plant_id', 'goodseed_id', 'lease_id'], 'integer'],
            [['area'], 'number'],
            [['zongdi','farms_id'], 'safe'],
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
        $query = Plantingstructure::find();

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
            'plant_id' => $this->plant_id,
            'area' => $this->area,
            'goodseed_id' => $this->goodseed_id,
            'farms_id' => $this->farms_id,
        	'lease_id' => $this->lease_id,
        ]);

        $query->andFilterWhere(['like', 'zongdi', $this->zongdi])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
