<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Environment;

/**
 * EnvironmentSearch represents the model behind the search form about `app\models\Environment`.
 */
class EnvironmentSearch extends Environment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'management_area', 'state', 'isgovernment'], 'integer'],
            [['contractarea'], 'number'],
            [['contractnumber'], 'safe'],
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
        $query = Environment::find();

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
            'management_area' => $this->management_area,
            'contractarea' => $this->contractarea,
            'state' => $this->state,
            'isgovernment' => $this->isgovernment,
        ]);

        $query->andFilterWhere(['like', 'contractnumber', $this->contractnumber]);

        return $dataProvider;
    }
}
