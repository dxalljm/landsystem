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
class farmsSearch extends Farms
{
	public $farmername;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_at', 'update_at','state'], 'integer'],
            [['farmname', 'farmername', 'address','measure', 'management_area', 'spyear', 'zongdi', 'cooperative_id','notclear','surveydate', 'groundsign', 'investigator', 'farmersign', 'pinyin','farmerpinyin'], 'safe'],
            //[['measure'], 'number'],
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

    
    public function betweenSearch()
    {
    	if(!empty($this->measure)) {
			preg_match_all('/(.*)([0-9]+?)/iU', $this->measure, $where);
			//print_r($where);
	
	// 		string(2) ">="
	// 		string(3) "300"
	    	if($where[1][0] == '>' or $where[1][0] == '>=')
	    		$tj = ['between', 'measure', (float)$where[2][0],(float)99999.0];
	    	if($where[1][0] == '<' or $where[1][0] == '<=')
	    		$tj = ['between', 'measure', (float)0.0,(float)$where[2][0]];
	    	if($where[1][0] == '')
	    		$tj = ['like', 'measure', $this->measure];
    	} else
    		$tj = ['like', 'measure', $this->measure];
    	//var_dump($tj);
    	return $tj;
    }
    
    public function pinyinSearch()
    {
    	if (preg_match ("/^[A-Za-z]/", $this->farmname)) {
    		$tj = ['like','pinyin',$this->farmname];
    	} else {
    		$tj = ['like','farmname',$this->farmname];
    	}
		return $tj;
    }
    
    public function farmerpinyinSearch()
    {
    	if (preg_match ("/^[A-Za-z]/", $this->farmername)) {
    		$tj = ['like','farmerpinyin',$this->farmername];
    	} else {
    		$tj = ['like','farmername',$this->farmername];
    	}
    	return $tj;
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
    	var_dump($params);
//        exit;
        $query = farms::find();
        //$query->joinWith(['farmer']);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		//var_dump($params['farmsSearch']['measure']);
//         print_r($params['farmsSearch']);
//         $this->betweenSearch($params['farmsSearch']['measure']);
//        $dataProvider->setSort([
//         		'attributes' => [
        				 
//         				'id' => [
//         						'asc' => ['id' => SORT_ASC],
//         						'desc' => ['id' => SORT_DESC],
//         						//'label' => '管理区',
//         				],
//         				'farmname' => [
//         						'asc' => ['farmname' => SORT_ASC],
//         						'desc' => ['farmname' => SORT_DESC],
//         						//'label' => '管理区',
//         				],
//         				'farmername' => [
//         						'asc' => ['land_farmer.farmername' => SORT_ASC],
//         						'desc' => ['land_farmer.farmername' => SORT_DESC],
//         						'label' => '法人姓名',
//         				],
//         				'measure' => [
//         						'asc' => ['measure' => SORT_ASC],
//         						'desc' => ['measure' => SORT_DESC],
//         						//'label' => '管理区',
//         				],
//         		] 
//         ]);

        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //var_dump($dataProvider);
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $this->management_area = [1, 4, 5];

          $query->andFilterWhere($this->pinyinSearch())
            ->andFilterWhere($this->farmerpinyinSearch())
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'address', $this->address])

            ->andWhere(['management_area' => $this->management_area])


            ->andFilterWhere(['like', 'spyear', $this->spyear])
            ->andFilterWhere(['like', 'zongdi', $this->zongdi])
            ->andFilterWhere(['like', 'notclear', $this->notclear])
            ->andFilterWhere(['like', 'cooperative_id', $this->cooperative_id])
            ->andFilterWhere(['like', 'surveydate', $this->surveydate])
            ->andFilterWhere(['like', 'groundsign', $this->groundsign])
            ->andFilterWhere(['like', 'investigator', $this->investigator])
            ->andFilterWhere(['like', 'farmersign', $this->farmersign])
            ->andFilterWhere(['like', 'pinyin', $this->pinyin])
            ->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
            ->andFilterWhere($this->betweenSearch());
          	//->andFilterWhere(['between', 'measure', $this->measure,$this->measure]);

        return $dataProvider;
    }
}
