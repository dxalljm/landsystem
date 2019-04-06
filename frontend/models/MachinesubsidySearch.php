<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Machinesubsidy;

/**
 * MachinesubsidySearch represents the model behind the search form about `app\models\Machinesubsidy`.
 */
class MachinesubsidySearch extends Machinesubsidy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'machinetype_id','year'], 'integer'],
            [['filename', 'parameter', 'subsidymoney'], 'safe'],
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
        $query = Machinesubsidy::find();

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
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'parameter', $this->parameter])
            ->andFilterWhere(['like', 'subsidymoney', $this->subsidymoney]);

        return $dataProvider;
    }
}
