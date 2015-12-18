<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Machine;

/**
 * MachineSearch represents the model behind the search form about `app\models\Machine`.
 */
class MachineSearch extends Machine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','machinetype_id'], 'integer'],
            [['productname', 'implementmodel', 'filename', 'province', 'enterprisename', 'parameter','content'], 'safe'],
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
        $query = Machine::find();

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
        	'machinetype_id' => $this->machinetype_id,
        ]);

        $query->andFilterWhere(['like', 'productname', $this->productname])
            ->andFilterWhere(['like', 'implementmodel', $this->implementmodel])
            ->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'enterprisename', $this->enterprisename])
            ->andFilterWhere(['like', 'parameter', $this->parameter])
            ->andFilterWhere(['like', 'content', $this->content]);
        return $dataProvider;
    }
}
