<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\farms;
use app\models\ManagementArea;

/**
 * farmsSearch represents the model behind the search form about `app\models\farms`.
 */
class farmsSearch extends farms
{
	public $areaname;
	//public $farmname;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'measure'], 'integer'],
            [['farmname', 'address', 'management_area', 'spyear', 'zongdi', 'cooperative_id', 'surveydate', 'groundsign', 'investigator', 'farmersign'], 'safe'],
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
        $query = farms::find();
        $query->joinWith(['managementarea']);
        //$query->joinWith(['farms']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
       $dataProvider->setSort([
        		'attributes' => [
        				 'areaname' => [
        						'asc' => ['land_management_area.areaname' => SORT_ASC],
        						'desc' => ['land_management_area.areaname' => SORT_DESC],
        						'label' => '管理区',
        				],
        				'id' => [
        						'asc' => ['id' => SORT_ASC],
        						'desc' => ['id' => SORT_DESC],
        						//'label' => '管理区',
        				],
        				'farmname' => [
        						'asc' => ['land_farms.farmname' => SORT_ASC],
        						'desc' => ['land_farms.farmname' => SORT_DESC],
        						//'label' => '管理区',
        				],
        				'spyear' => [
        						'asc' => ['spyear' => SORT_ASC],
        						'desc' => ['spyear' => SORT_DESC],
        						//'label' => '管理区',
        				],
        		] 
        ]);

        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

         $query->andFilterWhere(['like', 'farmname', $this->farmname])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'management_area', $this->management_area])
            ->andFilterWhere(['like', 'spyear', $this->spyear])
            ->andFilterWhere(['like', 'zongdi', $this->zongdi])
            ->andFilterWhere(['like', 'cooperative_id', $this->cooperative_id])
            ->andFilterWhere(['like', 'surveydate', $this->surveydate])
            ->andFilterWhere(['like', 'groundsign', $this->groundsign])
            ->andFilterWhere(['like', 'investigator', $this->investigator])
            ->andFilterWhere(['like', 'farmersign', $this->farmersign])
        	->andFilterWhere(['like', 'land_management_area.areaname', $this->areaname]);
         //->andFilterWhere(['like', 'land_farms.farmname', $this->farmname]);

        return $dataProvider;
    }
}
