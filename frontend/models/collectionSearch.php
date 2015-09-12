<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Collection;

/**
 * collectionSearch represents the model behind the search form about `app\models\Collection`.
 */
class collectionSearch extends Collection
{
	
    /**
     * @inheritdoc
     */
	public $farmname;
	public function rules()
    {
        return [
            [['id', 'farms_id', 'amounts_receivable', 'real_income_amount', 'ypayyear', 'isupdate'], 'integer'],
            [['payyear', 'billingtime','cardid'], 'safe'],
            [['ypayarea', 'ypaymoney', 'owe'], 'number'],
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
        $query = Collection::find()->where($params);
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
            'amounts_receivable' => $this->amounts_receivable,
            'real_income_amount' => $this->real_income_amount,
            'ypayyear' => $this->ypayyear,
            'ypayarea' => $this->ypayarea,
            'ypaymoney' => $this->ypaymoney,
            'owe' => $this->owe,
            'isupdate' => $this->isupdate,
        ]);

        $query->andFilterWhere(['like', 'payyear', $this->payyear])
            ->andFilterWhere(['like', 'billingtime', $this->billingtime])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'land_farms.farmname', $this->farmname]);

        return $dataProvider;
    }
}
