<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Estate;

/**
 * estateSearch represents the model behind the search form about `app\models\Estate`.
 */
class estateSearch extends Estate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tjsqjbzs', 'tjsffyj', 'sfyzy', 'sfmqzongdi', 'sfydcbg', 'reviewprocess_id'], 'integer'],
            [['tjsqjbzscontent', 'tjsffyjcontent', 'sfyzycontent', 'sfmqzongdicontent', 'sfydcbgcontent'], 'safe'],
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
        $query = Estate::find();

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
            'tjsqjbzs' => $this->tjsqjbzs,
            'tjsffyj' => $this->tjsffyj,
            'sfyzy' => $this->sfyzy,
            'sfmqzongdi' => $this->sfmqzongdi,
            'sfydcbg' => $this->sfydcbg,
            'reviewprocess_id' => $this->reviewprocess_id,
        ]);

        $query->andFilterWhere(['like', 'tjsqjbzscontent', $this->tjsqjbzscontent])
            ->andFilterWhere(['like', 'tjsffyjcontent', $this->tjsffyjcontent])
            ->andFilterWhere(['like', 'sfyzycontent', $this->sfyzycontent])
            ->andFilterWhere(['like', 'sfmqzongdicontent', $this->sfmqzongdicontent])
            ->andFilterWhere(['like', 'sfydcbgcontent', $this->sfydcbgcontent]);

        return $dataProvider;
    }
}
