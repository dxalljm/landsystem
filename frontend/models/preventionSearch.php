<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Prevention;

/**
 * preventionSearch represents the model behind the search form about `app\models\Prevention`.
 */
class preventionSearch extends Prevention
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'breedinfo_id', 'preventionnumber', 'breedinfonumber', 'isepidemic'], 'integer'],
            
            [['vaccine','preventionrate', 'isepidemic'], 'safe'],
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
        $query = Prevention::find();

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
            'breedinfo_id' => $this->breedinfo_id,
            'preventionnumber' => $this->preventionnumber,
            'breedinfonumber' => $this->breedinfonumber,
            //'preventionrate' => $this->preventionrate,
            //'isepidemic' => $this->isepidemic,
        ]);

        $query->andFilterWhere(['like', 'vaccine', $this->vaccine])
        	->andFilterWhere(['like', 'preventionrate', $this->preventionrate])
       		->andFilterWhere(['like', 'isepidemic', $this->isepidemic])
       		->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
        return $dataProvider;
    }
}
