<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Huinong;

/**
 * HuinongSearch represents the model behind the search form about `app\models\Huinong`.
 */
class HuinongSearch extends Huinong
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','typeid'], 'integer'],
            [['subsidiestype_id'], 'safe'],
            [['subsidiesarea', 'subsidiesmoney'], 'number'],
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
        $query = Huinong::find();

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
        	'typeid' => $this->typeid,
            'subsidiesarea' => $this->subsidiesarea,
            'subsidiesmoney' => $this->subsidiesmoney,
        ]);

        $query->andFilterWhere(['like', 'subsidiestype_id', $this->subsidiestype_id]);

        return $dataProvider;
    }
}
