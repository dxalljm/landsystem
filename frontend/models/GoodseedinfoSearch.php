<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Goodseedinfo;

/**
 * GoodseedinfoSearch represents the model behind the search form about `app\models\Goodseedinfo`.
 */
class GoodseedinfoSearch extends Goodseedinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'management_area', 'lease_id', 'planting_id', 'plant_id', 'goodseed_id', 'create_at', 'update_at', 'year'], 'integer'],
            [['zongdi'], 'safe'],
            [['area'], 'number'],
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
        $query = Goodseedinfo::find();

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
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'management_area' => $this->management_area,
            'lease_id' => $this->lease_id,
            'planting_id' => $this->planting_id,
            'plant_id' => $this->plant_id,
            'goodseed_id' => $this->goodseed_id,
            'area' => $this->area,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'zongdi', $this->zongdi]);

        return $dataProvider;
    }
}
