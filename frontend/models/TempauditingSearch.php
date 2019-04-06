<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tempauditing;

/**
 * TempauditingSearch represents the model behind the search form about `app\models\Tempauditing`.
 */
class TempauditingSearch extends Tempauditing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'update_at', 'create_at','state'], 'integer'],
            [['begindate', 'enddate'],'string'],
        	[['tempauditing'], 'safe'],
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
//     	var_dump($params);exit;
        $query = Tempauditing::find();

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
            'user_id' => $this->user_id,
            'update_at' => $this->update_at,
            'begindate' => $this->begindate,
            'enddate' => $this->enddate,
            'create_at' => $this->create_at,
        	'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'tempauditing', $this->tempauditing]);

        return $dataProvider;
    }
}
