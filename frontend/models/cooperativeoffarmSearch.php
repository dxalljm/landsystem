<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CooperativeOfFarm;
use app\models\Theyear;

/**
 * cooperativeoffarmSearch represents the model behind the search form about `app\models\CooperativeOfFarm`.
 */
class cooperativeoffarmSearch extends CooperativeOfFarm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'cia', 'cooperative_id'], 'integer'],
            [['proportion'], 'safe'],
            [['bonus'], 'number'],
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
        $query = CooperativeOfFarm::find();

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
            'cia' => $this->cia,
            'bonus' => $this->bonus,
            'cooperative_id' => $this->cooperative_id,
        ]);

        $query->andFilterWhere(['like', 'proportion', $this->proportion])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
