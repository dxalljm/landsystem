<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lease;
use app\models\Theyear;

/**
 * leaseSearch represents the model behind the search form about `app\models\Lease`.
 */
class leaseSearch extends Lease
{
    /**
     * @inheritdoc
     */
	public function rules()
    {
        return [
            [['id', 'farms_id', 'years'], 'integer'],
            [['lease_area', 'lessee', 'lessee_cardid', 'lessee_telephone', 'begindate', 'enddate', 'photo', 'create_at', 'update_at', 'rentpaymode', 'policyholder', 'insured', 'huinongascription', 'address','year','zhzb_farmer','zhzb_lessee','ddcj_farmer','ddcj_lessee','goodseed_farmer','goodseed_lessee','new_farmer','new_lessee'], 'safe'],
            [['rent'], 'number'],
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
//     	var_dump($params);exit;
        $query = Lease::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

         $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'years' => $this->years,
            'rent' => $this->rent,
         	'year' => $this->year,
        ]);
        

        $query->andFilterWhere(['like', 'lease_area', $this->lease_area])
            ->andFilterWhere(['like', 'lessee', $this->lessee])
            ->andFilterWhere(['like', 'lessee_cardid', $this->lessee_cardid])
            ->andFilterWhere(['like', 'lessee_telephone', $this->lessee_telephone])
            ->andFilterWhere(['like', 'begindate', $this->begindate])
            ->andFilterWhere(['like', 'enddate', $this->enddate])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'rentpaymode', $this->rentpaymode])
            ->andFilterWhere(['like', 'policyholder', $this->policyholder])
            ->andFilterWhere(['like', 'insured', $this->insured])
            ->andFilterWhere(['like', 'huinongascription', $this->huinongascription])
            ->andFilterWhere(['like', 'address', $this->address]);
//            ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
        
// 		var_dump($dataProvider->query);exit;
        return $dataProvider;
    }
}
