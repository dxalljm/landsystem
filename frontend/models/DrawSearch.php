<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Draw;

/**
 * DrawSearch represents the model behind the search form about `app\models\Draw`.
 */
class DrawSearch extends Draw
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'year', 'create_at', 'management_area','state'], 'integer'],
            [['cardid'],'string']
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
        $query = Draw::find();

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
            'year' => $this->year,
            'create_at' => $this->create_at,
            'management_area' => $this->management_area,
            'state' => $this->state,
        ]);
        $query->andFilterWhere(['like','cardid',$this->cardid]);

        return $dataProvider;
    }
}
