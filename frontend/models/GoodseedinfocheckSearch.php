<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Goodseedinfocheck;

/**
 * GoodseedinfocheckSearch represents the model behind the search form about `app\models\Goodseedinfocheck`.
 */
class GoodseedinfocheckSearch extends Goodseedinfocheck
{
    public $planter;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'management_area', 'lease_id', 'planting_id', 'plant_id', 'goodseed_id', 'create_at', 'update_at', 'year','planter'], 'integer'],
            [['zongdi'], 'safe'],
            [['area', 'total_area'], 'number'],
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
//        var_dump($params);exit;
        $query = Goodseedinfocheck::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if(is_array($this->management_area) and count($this->management_area) == 1) {
            $this->management_area = $this->management_area[0];
        }

        if($this->planter == 1) {
            $query->andWhere('lease_id>0');
        } else {
            $query->andFilterWhere(['lease_id'=>$this->planter]);
        }

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
//            'lease_id' => $this->lease_id,
            'planting_id' => $this->planting_id,
            'plant_id' => $this->plant_id,
            'goodseed_id' => $this->goodseed_id,
            'area' => $this->area,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'year' => $this->year,
            'total_area' => $this->total_area,
        ]);

        $query->andFilterWhere(['like', 'zongdi', $this->zongdi]);

        return $dataProvider;
    }
}
