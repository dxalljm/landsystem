<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Huinong;

/**
 * HuinongSearch represents the model behind the search form about `app\models\Huinong`.
 */
class HuinongSearch extends Huinong
{
    /**
     * @inheritdoc
     */
 	public function rules()
    {
        return [
            [['id', 'typeid', 'create_at', 'update_at', 'begindate', 'enddate'], 'integer'],
            [['subsidiestype_id'], 'safe'],
            [['subsidiesarea', 'subsidiesmoney', 'totalamount', 'realtotalamount','totalsubsidiesarea'], 'number'],
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
        $query = Huinong::find();

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
            'subsidiesarea' => $this->subsidiesarea,
            'subsidiesmoney' => $this->subsidiesmoney,
            'typeid' => $this->typeid,
        	'totalsubsidiesarea' => $this->totalsubsidiesarea,
            'totalamount' => $this->totalamount,
            'realtotalamount' => $this->realtotalamount,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'subsidiestype_id', $this->subsidiestype_id])
            ->andFilterWhere(['like', 'begindate', $this->begindate])
            ->andFilterWhere(['like', 'enddate', $this->enddate]);

        return $dataProvider;
    }
}
