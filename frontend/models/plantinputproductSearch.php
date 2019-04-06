<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Plantinputproduct;
use app\models\Farms;

/**
 * plantinputproductSearch represents the model behind the search form about `app\models\Plantinputproduct`.
 */
class plantinputproductSearch extends Plantinputproduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'lessee_id', 'father_id', 'son_id', 'inputproduct_id', 'plant_id','planting_id','management_area'], 'integer'],
            [['pconsumption'], 'number'],
            [['zongdi'], 'safe'],
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
        $query = Plantinputproduct::find();

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
            'lessee_id' => $this->lessee_id,
        	'planting_id' => $this->planting_id,
            'father_id' => $this->father_id,
            'son_id' => $this->son_id,
            'inputproduct_id' => $this->inputproduct_id,
            'pconsumption' => $this->pconsumption,
            'plant_id' => $this->plant_id,
        	'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'zongdi', $this->zongdi])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
