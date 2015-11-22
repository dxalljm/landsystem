<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cooperative;
use app\models\Theyear;
/**
 * cooperativeSearch represents the model behind the search form about `app\models\Cooperative`.
 */
class cooperativeSearch extends Cooperative
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'peoples'], 'integer'],
            [['cooperativename', 'cooperativetype', 'directorname', 'finance', 'dividendmode'], 'safe'],
            [['registered_capital'], 'number'],
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
        $query = Cooperative::find();

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
            'peoples' => $this->peoples,
            'registered_capital' => $this->registered_capital,
        ]);

        $query->andFilterWhere(['like', 'cooperativename', $this->cooperativename])
            ->andFilterWhere(['like', 'cooperativetype', $this->cooperativetype])
            ->andFilterWhere(['like', 'directorname', $this->directorname])
            ->andFilterWhere(['like', 'finance', $this->finance])
            ->andFilterWhere(['like', 'dividendmode', $this->dividendmode])
            ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
