<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reviewprocess;

/**
 * ReviewprocessSearch represents the model behind the search form about `app\models\Reviewprocess`.
 */
class ReviewprocessSearch extends Reviewprocess
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'oldfarms_id', 'newfarms_id', 'management_area', 'create_at', 'update_at', 'estate', 'finance', 'filereview', 'publicsecurity', 'leader', 'mortgage', 'steeringgroup', 'estatetime', 'financetime', 'filereviewtime', 'publicsecuritytime', 'leadertime', 'mortgagetime', 'steeringgrouptime', 'regulations', 'regulationstime'], 'integer'],
            [['estatecontent', 'financecontent', 'filereviewcontent', 'publicsecuritycontent', 'leadercontent', 'mortgagecontent', 'steeringgroupcontent', 'regulationscontent'], 'safe'],
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
        $query = Reviewprocess::find();

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
            'oldfarms_id' => $this->oldfarms_id,
        	'newfarms_id' => $this->newfarms_id,
        	'management_area' => $this->management_area,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'estate' => $this->estate,
            'finance' => $this->finance,
            'filereview' => $this->filereview,
            'publicsecurity' => $this->publicsecurity,
            'leader' => $this->leader,
            'mortgage' => $this->mortgage,
            'steeringgroup' => $this->steeringgroup,
            'estatetime' => $this->estatetime,
            'financetime' => $this->financetime,
            'filereviewtime' => $this->filereviewtime,
            'publicsecuritytime' => $this->publicsecuritytime,
            'leadertime' => $this->leadertime,
            'mortgagetime' => $this->mortgagetime,
            'steeringgrouptime' => $this->steeringgrouptime,
            'regulations' => $this->regulations,
            'regulationstime' => $this->regulationstime,
        ]);

        $query->andFilterWhere(['like', 'estatecontent', $this->estatecontent])
            ->andFilterWhere(['like', 'financecontent', $this->financecontent])
            ->andFilterWhere(['like', 'filereviewcontent', $this->filereviewcontent])
            ->andFilterWhere(['like', 'publicsecuritycontent', $this->publicsecuritycontent])
            ->andFilterWhere(['like', 'leadercontent', $this->leadercontent])
            ->andFilterWhere(['like', 'mortgagecontent', $this->mortgagecontent])
            ->andFilterWhere(['like', 'steeringgroupcontent', $this->steeringgroupcontent])
            ->andFilterWhere(['like', 'regulationscontent', $this->regulationscontent]);

        return $dataProvider;
    }
}
