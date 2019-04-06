<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lockstate;

/**
 * LockstateSearch represents the model behind the search form about `app\models\Lockstate`.
 */
class LockstateSearch extends Lockstate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'systemstate', 'loanconfig', 'transferconfig'], 'integer'],
            [['systemstatedate', 'platestate', 'loanconfigdate', 'transferconfigdate'], 'safe'],
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
        $query = Lockstate::find();

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
            'systemstate' => $this->systemstate,
            'loanconfig' => $this->loanconfig,
            'transferconfig' => $this->transferconfig,
        ]);

        $query->andFilterWhere(['like', 'systemstatedate', $this->systemstatedate])
            ->andFilterWhere(['like', 'platestate', $this->platestate])
            ->andFilterWhere(['like', 'loanconfigdate', $this->loanconfigdate])
            ->andFilterWhere(['like', 'transferconfigdate', $this->transferconfigdate]);

        return $dataProvider;
    }
}
