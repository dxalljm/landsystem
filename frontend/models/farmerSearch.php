<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Farmer;

/**
 * farmerSearch represents the model behind the search form about `app\models\farmer`.
 */
class farmerSearch extends farmer
{
	//public $farmname;
    /**
     * @inheritdoc
     */
    public function rules()
    {
       return [
            [['id', 'farms_id', 'isupdate', 'years', 'state'], 'integer'],
            [['farmerbeforename', 'nickname', 'gender', 'nation', 'political_outlook', 'cultural_degree', 'domicile', 'nowlive', 'living_room', 'photo', 'cardpic','cardpicback', 'create_at', 'update_at'], 'safe'],
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
    	
        $query = Farmer::find();
        $query->joinWith(['farms']);
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
            'isupdate' => $this->isupdate,
            'years' => $this->years,
        ]);

        $query->andFilterWhere(['like', 'farmerbeforename', $this->farmerbeforename])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'nation', $this->nation])
            ->andFilterWhere(['like', 'political_outlook', $this->political_outlook])
            ->andFilterWhere(['like', 'cultural_degree', $this->cultural_degree])
            ->andFilterWhere(['like', 'domicile', $this->domicile])
            ->andFilterWhere(['like', 'nowlive', $this->nowlive])
            ->andFilterWhere(['like', 'living_room', $this->living_room])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'cardpic', $this->cardpic])
            ->andFilterWhere(['like', 'create_at', $this->create_at])
            ->andFilterWhere(['like', 'update_at', $this->update_at]);
        return $dataProvider;
    }
}
