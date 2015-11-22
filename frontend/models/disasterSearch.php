<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Disaster;
use app\models\Theyear;
/**
 * disasterSearch represents the model behind the search form about `app\models\Disaster`.
 */
class disasterSearch extends Disaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'disastertype_id','isinsurance'], 'integer'],
            [['disasterarea', 'insurancearea', 'yieldreduction', 'socmoney'], 'number'],
            [['disasterplant'], 'safe'],
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
        $query = Disaster::find();

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
            'disastertype_id' => $this->disastertype_id,
            'disasterarea' => $this->disasterarea,
            'insurancearea' => $this->insurancearea,
            'yieldreduction' => $this->yieldreduction,
            'socmoney' => $this->socmoney,
        	'isinsurance' => $this->isinsurance,
        ]);

        $query->andFilterWhere(['like', 'disasterplant', $this->disasterplant])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
