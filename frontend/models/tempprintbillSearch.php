<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tempprintbill;

/**
 * tempprintbillSearch represents the model behind the search form about `app\models\Tempprintbill`.
 */
class tempprintbillSearch extends Tempprintbill
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','state','farms_id'], 'integer'],
            [['farmername','remarks', 'nonumber','bigamountofmoney','standard', 'number', 'amountofmoney','create_at','update_at'], 'safe'],
            
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
    	//var_dump($params);
        $query = Tempprintbill::find();

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
        	'farms' => $this->farms_id,
            'standard' => $this->standard,
        	'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'farmername', $this->farmername])
        ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'bigamountofmoney', $this->bigamountofmoney])
        	->andFilterWhere(['like', 'number', $this->number])
        	->andFilterWhere(['like', 'amountofmoney', $this->amountofmoney])
        	->andFilterWhere(['like', 'nonumber', $this->nonumber])
        	->andFilterWhere(['like', 'create_at', $this->create_at])
        	->andFilterWhere(['like', 'update_at', $this->update_at]);

        return $dataProvider;
    }
}
