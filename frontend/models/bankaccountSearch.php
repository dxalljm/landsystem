<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BankAccount;

/**
 * bankaccountSearch represents the model behind the search form about `app\models\BankAccount`.
 */
class bankaccountSearch extends BankAccount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farmer_id'], 'integer'],
            [['accountnumber', 'bank'], 'safe'],
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
        $query = BankAccount::find();

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
        ]);

        $query->andFilterWhere(['like', 'accountnumber', $this->accountnumber])
            ->andFilterWhere(['like', 'bank', $this->bank]);

        return $dataProvider;
    }
}
