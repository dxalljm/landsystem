<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Log;

/**
 * logSearch represents the model behind the search form about `app\models\Log`.
 */
class logSearch extends Log
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['user_ip', 'action', 'action_type', 'object_name', 'object_id', 'operate_desc', 'operate_time', 'object_old_attr', 'object_new_attr','macadress'], 'safe'],
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
        $query = Log::find()->orderBy('id DESC');

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
        ]);

        $query->andFilterWhere(['like', 'user_ip', $this->user_ip])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'action_type', $this->action_type])
            ->andFilterWhere(['like', 'object_name', $this->object_name])
            ->andFilterWhere(['like', 'object_id', $this->object_id])
            ->andFilterWhere(['like', 'operate_desc', $this->operate_desc])
            ->andFilterWhere(['like', 'operate_time', $this->operate_time])
            ->andFilterWhere(['like', 'object_old_attr', $this->object_old_attr])
            ->andFilterWhere(['like', 'macadress', $this->macadress])
            ->andFilterWhere(['like', 'object_new_attr', $this->object_new_attr]);

        return $dataProvider;
    }
}
