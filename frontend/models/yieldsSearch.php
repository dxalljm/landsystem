<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Yields;

/**
 * yieldsSearch represents the model behind the search form about `app\models\Yields`.
 */
class yieldsSearch extends Yields
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'planting_id', 'farms_id'], 'integer'],
            [['single'], 'number'],
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
        $query = Yields::find();

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
            'single' => $this->single,
        ]);

        return $dataProvider;
    }
    public function searchIndex($params)
    {
//     	var_dump($params);exit;
    	$query = Yields::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if(isset($params['farms_id']))
    		$farms_id = $params['farms_id'];
    	else 
    		$farms_id = $this->farms_id;
//     	$this->load($params);
    
    	if (!$this->validate()) {
    		// uncomment the following line if you do not want to any records when validation fails
    		// $query->where('0=1');
    		return $dataProvider;
    	}
    
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'planting_id' => $this->planting_id,
    			'farms_id' => $farms_id,
    			'single' => $this->single,
    	]);
    	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	return $dataProvider;
    }
}
