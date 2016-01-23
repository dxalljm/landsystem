<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Projectapplication;
use app\models\Theyear;
use app\models\Farms;
/**
 * projectapplicationSearch represents the model behind the search form about `app\models\Projectapplication`.
 */
class projectapplicationSearch extends Projectapplication
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','farms_id', 'farmer_id','create_at', 'update_at', 'is_agree','management_area','reviewprocess_id'], 'integer'],
            [['projectdata'],'number'],
        	[['projecttype'], 'safe'],
        	[['content','unit'],'string']
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

        $query->andFilterWhere([
            'id' => $this->id,
        	'farms_id' => $this->farms_id,
        	'reviewprocess_id'=>$this->reviewprocess_id,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'is_agree' => $this->is_agree,
        	'management_area' => $this->management_area,
        	'projectdata' => $this->projectdata,
        ]);

        $query->andFilterWhere(['like', 'projecttype', $this->projecttype])
        ->andFilterWhere(['like', 'content', $this->content])
        ->andFilterWhere(['like', 'unit', $this->unit])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
    public function pinyinSearch($str = NULL)
    {
    	if (preg_match ("/^[A-Za-z]/", $str)) {
    		$tj = ['like','pinyin',$str];
    	} else {
    		$tj = ['like','farmname',$str];
    	}
    
    	return $tj;
    }
    
    public function farmerpinyinSearch($str = NULL)
    {
    	if (preg_match ("/^[A-Za-z]/", $str)) {
    		$tj = ['like','farmerpinyin',$str];
    	} else {
    		$tj = ['like','farmername',$str];
    	}
    	//     	var_dump($tj);exit;
    	return $tj;
    }
    public function searchIndex($params)
    {
//     	    	var_dump($params);exit;
    	$query = Projectapplication::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if($params['projectapplicationSearch']['management_area'] == 0)
    		$this->management_area = NULL;
    	else
    		$this->management_area = $params['projectapplicationSearch']['management_area'];
    	$farmid = [];
    	if((isset($params['projectapplicationSearch']['farms_id']) and $params['projectapplicationSearch']['farms_id'] !== '') or (isset($params['projectapplicationSearch']['farmer_id']) and $params['projectapplicationSearch']['farmer_id'] !== '')) {
	    	$farm = Farms::find();
	    	$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['projectapplicationSearch']['farms_id']) and $params['projectapplicationSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['projectapplicationSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		    		
    	}

    	if(isset($params['projectapplicationSearch']['farmer_id']) and $params['projectapplicationSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['projectapplicationSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
	    	foreach ($farm->all() as $value) {
	    		$farmid[] = $value['id'];
	    	}
    	}
    	if(isset($params['projectapplicationSearch']['projecttype']) and $params['projectapplicationSearch']['projecttype'] !== '')
    		$this->projecttype = $params['projectapplicationSearch']['projecttype'];
    	$query->andFilterWhere([
//     			'id' => $this->id,
    			'farms_id' => $farmid,
    			'projecttype' => $this->projecttype,
    			'management_area' => $this->management_area,
    	]);
    
    	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    
    	return $dataProvider;
    }
}
