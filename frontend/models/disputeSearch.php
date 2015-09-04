<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dispute;

/**
 * disputeSearch represents the model behind the search form about `app\models\Dispute`.
 */
class disputeSearch extends Dispute
{
    /**
     * @inheritdoc
     */
	public $farmname;
    public function rules()
    {
        return [
            [['id', 'farms_id'], 'integer'],
            [['content', 'create_at', 'update_at'], 'safe'],
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
        $query = Dispute::find()->where($params);
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
        
        $dataProvider->setSort([
        		'attributes' => [
        				/* 其它字段不要动 */
        				/*  下面这段是加入的 */
        				/*=============*/
        				'farmsname' => [
        						'asc' => ['farms.farmname' => SORT_ASC],
        						'desc' => ['farms.farmname' => SORT_DESC],
        						'label' => '农场名称'
        				],
        				/*=============*/
        		]
        ]);
        
        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'create_at', $this->create_at])
            ->andFilterWhere(['like', 'update_at', $this->update_at])
            ->andFilterWhere(['like', 'farms.farmname', $this->farmname]);

        return $dataProvider;
    }
}
