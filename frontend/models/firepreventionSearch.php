<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fireprevention;
use app\models\Theyear;
/**
 * firepreventionSearch represents the model behind the search form about `app\models\Fireprevention`.
 */
class firepreventionSearch extends Fireprevention
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id','management_area'], 'integer'],
            [['firecontract', 'safecontract', 'environmental_agreement', 'firetools', 'mechanical_fire_cover', 'chimney_fire_cover', 'isolation_belt', 'propagandist', 'fire_administrator', 'cooker', 'fieldpermit', 'propaganda_firecontract', 'leaflets', 'employee_firecontract', 'rectification_record', 'equipmentpic', 'peoplepic', 'facilitiespic'], 'safe'],
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
        $query = Fireprevention::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
        	'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'firecontract', $this->firecontract])
            ->andFilterWhere(['like', 'safecontract', $this->safecontract])
            ->andFilterWhere(['like', 'environmental_agreement', $this->environmental_agreement])
            ->andFilterWhere(['like', 'firetools', $this->firetools])
            ->andFilterWhere(['like', 'mechanical_fire_cover', $this->mechanical_fire_cover])
            ->andFilterWhere(['like', 'chimney_fire_cover', $this->chimney_fire_cover])
            ->andFilterWhere(['like', 'isolation_belt', $this->isolation_belt])
            ->andFilterWhere(['like', 'propagandist', $this->propagandist])
            ->andFilterWhere(['like', 'fire_administrator', $this->fire_administrator])
            ->andFilterWhere(['like', 'cooker', $this->cooker])
            ->andFilterWhere(['like', 'fieldpermit', $this->fieldpermit])
            ->andFilterWhere(['like', 'propaganda_firecontract', $this->propaganda_firecontract])
            ->andFilterWhere(['like', 'leaflets', $this->leaflets])
            ->andFilterWhere(['like', 'employee_firecontract', $this->employee_firecontract])
            ->andFilterWhere(['like', 'rectification_record', $this->rectification_record])
            ->andFilterWhere(['like', 'equipmentpic', $this->equipmentpic])
            ->andFilterWhere(['like', 'peoplepic', $this->peoplepic])
            ->andFilterWhere(['like', 'facilitiespic', $this->facilitiespic])
            ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
}
