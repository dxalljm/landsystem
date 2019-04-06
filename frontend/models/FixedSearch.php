<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fixed;

/**
 * FixedSearch represents the model behind the search form about `app\models\Fixed`.
 */
class FixedSearch extends Fixed
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'number','management_area'], 'integer'],
            [['name', 'unit', 'state', 'remarks','cardid'], 'safe'],
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
//        var_dump($params);exit;
        $query = Fixed::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
//        var_dump($this->management_area);exit;
        if(is_array($this->management_area) and count($this->management_area) > 1) {
            $this->management_area = null;
        }

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }


        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'number' => $this->number,
            'cardid' => $this->cardid,
            'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
