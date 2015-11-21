<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Inputproductbrandmodel;

/**
 * inputproductbrandmodelSearch represents the model behind the search form about `app\models\Inputproductbrandmodel`.
 */
class inputproductbrandmodelSearch extends Inputproductbrandmodel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inputproduct_id'], 'integer'],
            [['brand', 'model', 'brandpinyin'], 'safe'],
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
        $query = Inputproductbrandmodel::find();

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
            'inputproduct_id' => $this->inputproduct_id,
        ]);

        $query->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'model', $this->model])
        	->andFilterWhere(['like', 'brandpinyin', $this->brandpinyin])
        	->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
        return $dataProvider;
    }
}
