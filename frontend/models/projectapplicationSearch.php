<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Projectapplication;
use app\models\Theyear;
/**
 * projectapplicationSearch represents the model behind the search form about `app\models\Projectapplication`.
 */
class projectapplicationSearch extends Projectapplication
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','farms_id', 'create_at', 'update_at', 'is_agree','management_area','reviewprocess_id'], 'integer'],
            [['projecttype'], 'safe'],
        	[['content'],'string']
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
        $query = Projectapplication::find();

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
        	'reviewprocess_id'=>$this->reviewprocess_id,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'is_agree' => $this->is_agree,
        	'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'projecttype', $this->projecttype])
        ->andFilterWhere(['like', 'content', $this->content])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
