<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Loan;
use app\models\Theyear;
/**
 * loanSearch represents the model behind the search form about `app\models\Loan`.
 */
class loanSearch extends Loan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id'], 'integer'],
            [['mortgagearea', 'mortgagemoney'], 'number'],
            [['mortgagebank'], 'safe'],
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
        $query = Loan::find();

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
            'mortgagearea' => $this->mortgagearea,
            'mortgagemoney' => $this->mortgagemoney,
        ]);

        $query->andFilterWhere(['like', 'mortgagebank', $this->mortgagebank])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
            

        return $dataProvider;
    }
}
