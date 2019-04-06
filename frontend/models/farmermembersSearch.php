<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Farmermembers;

/**
 * farmermembersSearch represents the model behind the search form about `app\models\Farmermembers`.
 */
class farmermembersSearch extends Farmermembers
{
    /**
     * @inheritdoc
     */
   public function rules()
    {
        return [
            [['id', 'farmer_id', 'isupdate'], 'integer'],
            [['relationship', 'membername', 'cardid', 'farmercardid','remarks'], 'safe'],
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
        $query = Farmermembers::find();

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
            'farmer_id' => $this->farmer_id,
            'isupdate' => $this->isupdate,
        ]);

        $query->andFilterWhere(['like', 'relationship', $this->relationship])
            ->andFilterWhere(['like', 'membername', $this->membername])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'farmercardid', $this->farmercardid])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
