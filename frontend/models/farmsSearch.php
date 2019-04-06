<?php

namespace frontend\models;

use app\models\Breedtype;
use app\models\Collection;
use app\models\Fireprevention;
use app\models\Plantingstructurecheck;
use app\models\Plantingstructure;
use app\models\Projectapplication;
use app\models\Reviewprocess;
use Composer\Package\Loader\ValidatingArrayLoader;
use app\models\Insurance;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Farms;
use app\models\ManagementArea;
use app\models\User;
use app\models\Theyear;
use app\models\Farmer;
use yii\helpers\ArrayHelper;
use app\models\Breed;
use app\models\Loan;
use app\models\Lease;
/**
 * farmsSearch represents the model behind the search form about `app\models\farms`.
 */
class farmsSearch extends Farms
{
	public $farmername;
<<<<<<< HEAD
	public $careful;
	public $carefulwc;
	public $businesstype;
	public $plantIsFinished;
	public $condition;
	public $ids = array();

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'create_at', 'update_at', 'state', 'oldfarms_id', 'locked', 'management_area','careful','carefulwc','businesstype','condition','plantIsFinished','tempdata','isbreed'], 'integer'],
			[['farmname', 'farmername', 'address', 'measure', 'notstateinfo', 'telephone', 'spyear', 'zongdi', 'cooperative_id', 'notclear', 'surveydate', 'groundsign', 'farmersign', 'pinyin', 'farmerpinyin', 'contractnumber', 'begindate', 'enddate', 'latitude', 'longitude', 'accountnumber', 'contractarea', 'cardid'], 'safe'],
			[['notstate'], 'number'],
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
		if (!empty($this->contractarea)) {
			preg_match_all('/(.*)([0-9]+?)/iU', $this->contractarea, $where);
			//print_r($where);

			// 		string(2) ">="
			// 		string(3) "300"
			if ($where[1][0] == '>' or $where[1][0] == '>=')
				$tj = ['between', 'contractarea', (float)$where[2][0], (float)99999.0];
			if ($where[1][0] == '<' or $where[1][0] == '<=')
				$tj = ['between', 'contractarea', (float)0.0, (float)$where[2][0]];
			if ($where[1][0] == '')
				$tj = ['like', 'contractarea', $this->contractarea];
		} else
			$tj = ['like', 'contractarea', $this->contractarea];
		//var_dump($tj);
		return $tj;
	}

	public function measureSearch($str = NULL)
	{
		$this->measure = $str;
		if (!empty($this->measure)) {
=======
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_at', 'update_at','state','oldfarms_id','locked','management_area'], 'integer'],
            [['farmname', 'farmername', 'address','measure','notstateinfo','telephone', 'spyear', 'zongdi', 'cooperative_id','notclear','surveydate', 'groundsign', 'farmersign', 'pinyin','farmerpinyin','contractnumber', 'begindate', 'enddate','latitude','longitude','accountnumber','contractarea','cardid'], 'safe'],
            [['notstate'], 'number'],
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
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
			preg_match_all('/(.*)([0-9]+?)/iU', $this->measure, $where);
			//print_r($where);

			// 		string(2) ">="
			// 		string(3) "300"
			if ($where[1][0] == '>' or $where[1][0] == '>=')
				$tj = ['between', 'measure', (float)$where[2][0], (float)99999.0];
			if ($where[1][0] == '<' or $where[1][0] == '<=')
				$tj = ['between', 'measure', (float)0.0, (float)$where[2][0]];
			if ($where[1][0] == '')
				$tj = ['like', 'measure', $this->measure];
		} else
			$tj = ['like', 'measure', $this->measure];
		//var_dump($tj);
		return $tj;
	}

	public function contractareaSearch($str = NULL)
	{
		$this->contractarea = $str;
		if (!empty($this->contractarea)) {
			preg_match_all('/(.*)([0-9]+?)/iU', $this->contractarea, $where);
			//print_r($where);

			// 		string(2) ">="
			// 		string(3) "300"
			if ($where[1][0] == '>' or $where[1][0] == '>=')
				$tj = ['between', 'contractarea', (float)$where[2][0], (float)99999.0];
			if ($where[1][0] == '<' or $where[1][0] == '<=')
				$tj = ['between', 'contractarea', (float)0.0, (float)$where[2][0]];
			if ($where[1][0] == '')
				$tj = ['like', 'contractarea', $this->contractarea];
		} else
			$tj = ['like', 'contractarea', $this->contractarea];
		//var_dump($tj);
		return $tj;
	}

	public function pinyinSearch($str = NULL)
	{

		$this->farmname = $str;
		if (preg_match("/^[A-Za-z]/", $this->farmname)) {
			$tj = ['like', 'pinyin', $this->farmname];
		} else {
			$tj = ['like', 'farmname', $this->farmname];
		}
//     	var_dump($tj);exit;
		return $tj;
	}

	public function farmerpinyinSearch($str = NULL)
	{
//     	var_dump($str);exit;
		$this->farmername = $str;
		if (preg_match("/^[A-Za-z]/", $this->farmername)) {
			$tj = ['like', 'farmerpinyin', $this->farmername];
		} else {
			$tj = ['like', 'farmername', $this->farmername];
		}
//     	var_dump($tj);exit;
		return $tj;
	}

	public function getManagementWhere($managemetnarea)
	{
//     	if($managemetnarea)

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
// 		if(date('Y') == User::getYear()) {
//		$query = farms::find()->orderBy('farmerpinyin ASC');
		if(isset($_GET['iszx'])) {
//		if(isset($params['farmsSearch']['state']) and $params['farmsSearch']['state'] == 0) {
			$query = Farms::find()->where(['id'=>Reviewprocess::getOldFarms()])->orderBy('farmerpinyin ASC');
		} else {
			$query = $this->getLandCondition();
		}

// 		} else {
// 			$query = farms::find()->orderBy('farmerpinyin ASC');
// 		}

		//$query->joinWith(['farmer']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
//         		'pagination' => [
//         				'pageSize' => 0,
//         		],
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
//      	var_dump($this->state);exit;
//		foreach ($this->getSearchCondition($this->state) as $condition) {
//			$query->andFilterWhere($condition);
//		}
//		if($this->condition === '' or $this->condition === null) {
//			$this->condition = 1;
//		}




//      	var_dump($dataProvider->query->where);exit;
//      	$this->state = [1,2,3,4];
// 		if($this->state == '1' or $this->state == '0' or $this->state == '2' or $this->state == '3' or $this->state == '4') {
// 			$this->state = $this->notstateinfo;
// //			$query->andWhere('notstateinfo>0');
// 		} else {
// 			$query->andFilterWhere(['notstateinfo' => $this->notstateinfo,]);
// 		}
//      	$this->management_area = 6;
//         var_dump($dataProvider);
		$query->andFilterWhere($this->pinyinSearch($this->farmname))
			->andFilterWhere($this->farmerpinyinSearch($this->farmername));
//		var_dump($query->where);exit;
//		var_dump($this->getSearchCondition());exit;
		$query->andFilterWhere([
			'id' => $this->id,
			'locked' => $this->locked,
//			'state' => $this->state,
			'management_area' => $this->management_area,
			'tempdata' => $this->tempdata,
			'isbreed' => $this->isbreed,
		]);

		if($this->update_at == User::getYear()) {
			$query->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
//			var_dump($dataProvider->query->where);exit;
			return $dataProvider;
		}

		if($this->state == 99) {
//			var_dump(Farms::getOldfarms());exit;
			$query->andFilterWhere(['id'=>Farms::getOldfarms()]);
		} else {
			$query->andFilterWhere(['state'=>$this->state]);
		}

		if ($this->zongdi == 'icon') {
			$query->andWhere('zongdi <> ""');
		} else {
			$query->andFilterWhere(['like', 'zongdi', $this->zongdi]);
		}
		if ($this->notclear == 'icon') {
			$query->andWhere('notclear <> ""');
		} else {
			$query->andFilterWhere(['like', 'notclear', $this->notclear]);
		}
		if ($this->notstate == 'icon') {
			$query->andWhere('notstate <> ""');
		} else {
			$query->andFilterWhere(['like', 'notstate', $this->notstate]);
		}
		$query->andFilterWhere(['like', 'cardid', $this->cardid])
			->andFilterWhere(['like', 'telephone', $this->telephone])
			->andFilterWhere(['like', 'address', $this->address])
//            ->andFilterWhere(['like', 'state', $this->state])
			->andFilterWhere(['like', 'oldfarms_id', $this->oldfarms_id])
//             ->andWhere(['management_area' => $managementarea])
			->andFilterWhere(['like', 'spyear', $this->spyear])
//             ->andFilterWhere(['like', 'zongdi', $this->zongdi])
//             ->andFilterWhere(['like', 'notclear', $this->notclear])
			->andFilterWhere(['like', 'cooperative_id', $this->cooperative_id])
			->andFilterWhere(['like', 'surveydate', $this->surveydate])
			->andFilterWhere(['like', 'groundsign', $this->groundsign])
			->andFilterWhere(['like', 'farmersign', $this->farmersign])
			->andFilterWhere(['like', 'pinyin', $this->pinyin])
			->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
			->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
			->andFilterWhere(['like', 'latitude', $this->latitude])
			->andFilterWhere(['like', 'longitude', $this->longitude])
			->andFilterWhere(['like', 'accountnumber', $this->accountnumber])
			->andFilterWhere($this->contractareaSearch($this->contractarea))
			->andFilterWhere($this->betweenSearch());
		$farms = ArrayHelper::map(Farms::find()->where($query->where)->select('id')->all(),'id','id');
		sort($farms);
//		var_dump($this->businesstype);exit;
		switch ($this->businesstype) {
			case 20:
				if($this->condition === '1')
					$query->andWhere('measure > 0');
				if($this->condition === '0')
					$query->andWhere('measure = 0');
				if($this->condition === 9) {
					$query->andFilterWhere(['id'=>Farms::getOldfarms()]);
				}
				break;
			case 21:
				$data = ArrayHelper::map(Plantingstructure::find()->where(['year'=>User::getYear()])->all(),'id','farms_id');
				sort($data);
				if($this->condition === '1') {
					$ids = array_intersect($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				if($this->condition === '0') {
					$ids = array_diff($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				break;
			case 24:
//				var_dump($this->condition);exit;
				if($this->condition === '1') {
					$collections = ArrayHelper::map(Collection::find()->where(['payyear'=>User::getYear(),'management_area'=>$this->management_area])->andWhere('state>=1')->all(),'id','farms_id');
//					var_dump($collections);exit;
					if(empty($collections)) {
						$query->andFilterWhere(['id' => 0]);
					}
				}
				if($this->condition === '0') {
					$collections = ArrayHelper::map(Collection::find()->where(['payyear'=>User::getYear(),'state'=>0,'management_area'=>$this->management_area])->all(),'id','farms_id');
//					var_dump($collections);exit;
					if(empty($collections)) {
						$query->andFilterWhere(['id' => 0]);
					}
				}
//				var_dump($query->where);exit;
				if(isset($collections) and !empty($collections)) {
//					var_dump($collections);exit;
					sort($collections);
					$query->andFilterWhere(['id' => $collections]);
				}
				break;
			case 25:
				$data = ArrayHelper::map(Fireprevention::find()->where(['year'=>User::getYear(),'firecontract'=>1,'safecontract'=>1,'fieldpermit'=>1,'leaflets'=>1,'rectification_record'=>1,'environmental_agreement'=>1])->all(),'id','farms_id');
//				var_dump($fires);exit;
				sort($data);
				if($this->condition === '1') {
					$ids = array_intersect($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				if($this->condition === '0') {
					$ids = array_diff($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				break;
			case 26:
				$data = ArrayHelper::map(Breed::find()->all(),'id','farms_id');
				sort($data);
				if($this->condition === '1') {
					$ids = array_intersect($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				if($this->condition === '0') {
					$ids = array_diff($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				break;
			case 27:
				$data = ArrayHelper::map(Projectapplication::find()->where(['state'=>1,'year'=>User::getYear(),'management_area'=>$this->management_area])->all(),'id','farms_id');
				sort($data);
//				var_dump(array_diff($farms,$insurances));exit;
				if($this->condition === '1') {
					$ids = array_intersect($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				if($this->condition === '0') {
					$ids = array_diff($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				break;
			case 71:
				$data = ArrayHelper::map(Insurance::find()->where(['state'=>1,'year'=>User::getYear(),'management_area'=>$this->management_area])->all(),'id','farms_id');
				sort($data);
//				var_dump(array_diff($farms,$insurances));exit;
				if($this->condition === '1') {
					$ids = array_intersect($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				if($this->condition === '0') {
					$ids = array_diff($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				break;
			case 72:
				$data = ArrayHelper::map(Loan::find()->where(['state'=>1,'lock'=>1,'year'=>User::getYear(),'management_area'=>$this->management_area])->all(),'id','farms_id');
				sort($data);
//				var_dump(array_diff($farms,$insurances));exit;
				if($this->condition === '1') {
					$ids = array_intersect($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				if($this->condition === '0') {
					$ids = array_diff($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				break;
			case 'lease':
				$data = ArrayHelper::map(Lease::find()->where(['year'=>User::getYear(),'management_area'=>$this->management_area])->all(),'id','farms_id');
				sort($data);
//				var_dump(array_diff($farms,$insurances));exit;
				if($this->condition === '1') {
					$ids = array_intersect($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
				if($this->condition === '0') {
					$ids = array_diff($farms,$data);
					if($ids)
						$query->andFilterWhere(['id'=>$ids]);
					else
						$query->andFilterWhere(['id'=>0]);
				}
		}
//		var_dump($dataProvider->query->where);exit;
		return $dataProvider;
	}

	public function searchbreed($params)
	{
//     	var_dump($params);exit;
// 		if(date('Y') == User::getYear()) {
//		$query = farms::find()->orderBy('farmerpinyin ASC');
		if(isset($_GET['iszx'])) {
//		if(isset($params['farmsSearch']['state']) and $params['farmsSearch']['state'] == 0) {
			$query = Farms::find()->where(['id'=>Reviewprocess::getOldFarms()])->orderBy('farmerpinyin ASC');
		} else {
			$query = $this->getLandCondition();
		}

// 		} else {
// 			$query = farms::find()->orderBy('farmerpinyin ASC');
// 		}

		//$query->joinWith(['farmer']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
//         		'pagination' => [
//         				'pageSize' => 0,
//         		],
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
//      	var_dump($this->state);exit;
//		foreach ($this->getSearchCondition($this->state) as $condition) {
//			$query->andFilterWhere($condition);
//		}
//		if($this->condition === '' or $this->condition === null) {
//			$this->condition = 1;
//		}




//      	var_dump($dataProvider->query->where);exit;
//      	$this->state = [1,2,3,4];
// 		if($this->state == '1' or $this->state == '0' or $this->state == '2' or $this->state == '3' or $this->state == '4') {
// 			$this->state = $this->notstateinfo;
// //			$query->andWhere('notstateinfo>0');
// 		} else {
// 			$query->andFilterWhere(['notstateinfo' => $this->notstateinfo,]);
// 		}
//      	$this->management_area = 6;
//         var_dump($dataProvider);
		$query->andFilterWhere($this->pinyinSearch($this->farmname))
			->andFilterWhere($this->farmerpinyinSearch($this->farmername));
//		var_dump($query->where);exit;
//		var_dump($this->getSearchCondition());exit;
		$query->andFilterWhere([
			'id' => $this->id,
			'locked' => $this->locked,
//			'state' => $this->state,
			'management_area' => $this->management_area,
			'tempdata' => $this->tempdata,
			'isbreed' => $this->isbreed,
		]);


		$query->andFilterWhere(['like', 'cardid', $this->cardid])
			->andFilterWhere(['like', 'telephone', $this->telephone])
			->andFilterWhere(['like', 'address', $this->address])
//            ->andFilterWhere(['like', 'state', $this->state])
			->andFilterWhere(['like', 'oldfarms_id', $this->oldfarms_id])
//             ->andWhere(['management_area' => $managementarea])
			->andFilterWhere(['like', 'spyear', $this->spyear])
//             ->andFilterWhere(['like', 'zongdi', $this->zongdi])
//             ->andFilterWhere(['like', 'notclear', $this->notclear])
			->andFilterWhere(['like', 'cooperative_id', $this->cooperative_id])
			->andFilterWhere(['like', 'surveydate', $this->surveydate])
			->andFilterWhere(['like', 'groundsign', $this->groundsign])
			->andFilterWhere(['like', 'farmersign', $this->farmersign])
			->andFilterWhere(['like', 'pinyin', $this->pinyin])
			->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
			->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
			->andFilterWhere(['like', 'latitude', $this->latitude])
			->andFilterWhere(['like', 'longitude', $this->longitude])
			->andFilterWhere(['like', 'accountnumber', $this->accountnumber])
			->andFilterWhere($this->contractareaSearch($this->contractarea))
			->andFilterWhere($this->betweenSearch());

//		var_dump($this->businesstype);exit;

		return $dataProvider;
	}

	public function searchverify($params)
	{
//     	var_dump($params);        exit;
		$query = $this->getLandCondition();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		$query->andFilterWhere($this->pinyinSearch($this->farmname))
			->andFilterWhere($this->farmerpinyinSearch($this->farmername));
		$query->andFilterWhere([
			'id' => $this->id,
			'locked' => $this->locked,
			'state' => $this->contractnumber,
			'management_area' => $this->management_area,
			'tempdata' => $this->tempdata,
			'isbreed' => $this->isbreed,
		]);

		$query->andFilterWhere(['like', 'cardid', $this->cardid])
			->andFilterWhere(['like', 'telephone', $this->telephone])
			->andFilterWhere(['like', 'address', $this->address])
//            ->andFilterWhere(['like', 'state', $this->state])
			->andFilterWhere(['like', 'oldfarms_id', $this->oldfarms_id])
//             ->andWhere(['management_area' => $managementarea])
			->andFilterWhere(['like', 'spyear', $this->spyear])
//             ->andFilterWhere(['like', 'zongdi', $this->zongdi])
//             ->andFilterWhere(['like', 'notclear', $this->notclear])
			->andFilterWhere(['like', 'cooperative_id', $this->cooperative_id])
			->andFilterWhere(['like', 'surveydate', $this->surveydate])
			->andFilterWhere(['like', 'groundsign', $this->groundsign])
			->andFilterWhere(['like', 'farmersign', $this->farmersign])
			->andFilterWhere(['like', 'pinyin', $this->pinyin])
			->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
//			->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
			->andFilterWhere(['like', 'latitude', $this->latitude])
			->andFilterWhere(['like', 'longitude', $this->longitude])
			->andFilterWhere(['like', 'accountnumber', $this->accountnumber])
			->andFilterWhere($this->contractareaSearch($this->contractarea))
			->andFilterWhere($this->betweenSearch());
		$farms = ArrayHelper::map(Farms::find()->where($query->where)->all(),'id','contractarea');

		if($this->plantIsFinished === '1') {
//			var_dump($this->plantIsFinished);
			$allid = [];
			foreach ($farms as $farms_id => $contractarea) {
				$plantsum = sprintf('%.2f',Plantingstructurecheck::find()->where(['year' => User::getYear(), 'farms_id' => $farms_id])->sum('area'));
//				var_dump($plantsum.'==='.$contractarea);
				if(bccomp($plantsum,$contractarea) == 0) {
					$allid[] = $farms_id;
				}
			}
			$query->andFilterWhere(['id' => $allid]);
//			var_dump($query->where);
//			exit;
		}
		if($this->plantIsFinished === '0') {
//			var_dump($this->plantIsFinished);
			$allid = [];
			foreach ($farms as $farms_id => $contractarea) {
				$plantsum = sprintf('%.2f',Plantingstructurecheck::find()->where(['year' => User::getYear(), 'farms_id' => $farms_id])->sum('area'));
				if(bccomp($plantsum,$contractarea) == -1) {
					$allid[] = $farms_id;
				}
			}
			if($allid) {
				$query->andFilterWhere(['id' => $allid]);
			} else {
				$query->andFilterWhere(['id' => '0']);
			}
//			var_dump($query->where);
//			exit;
		}
//		exit;
		return $dataProvider;
	}

	public function searchCareful($params)
	{
		$query = $this->getLandCondition();
	
		$dataProvider = new ActiveDataProvider([
				'query' => $query,
		]);	
	
		$this->load($params);

		$query->andFilterWhere($this->pinyinSearch($this->farmname))
		->andFilterWhere($this->farmerpinyinSearch($this->farmername));

		$query->andFilterWhere([
				'id' => $this->id,
				'locked' => $this->locked,
				'state' => $this->state,
				'management_area' => $this->management_area,
			'tempdata' => $this->tempdata,
			'isbreed' => $this->isbreed,
		]);
// 		var_dump($this->careful);exit;
		if($this->careful == '=') {
			$query->andWhere(['notstate'=>0,'notclear'=>0]);
		}
		if($this->careful == '<') {
			$query->andWhere('notstate>0')->andWhere('measure > 0');
		}
		if($this->careful == '>') {
			$query->andWhere('notclear>0')->andWhere('measure > 0');
		}
		if($this->careful == '0') {
			$query->andWhere('contractarea = notclear');
		}
		
		if($this->carefulwc == '=') {
// 			$query->orWhere(['<=','notclear','contractarea*0.0025'])->orWhere('notstate <= contractarea*0.0025');
			$query->andWhere('notstate <= contractarea*0.0025')->andWhere('notclear <= contractarea*0.0025');
		}
		if($this->carefulwc == '<') {
			$query->andWhere('notstate>contractarea*0.0025')->andWhere('measure > 0');
		}
		if($this->carefulwc == '>') {
			$query->andWhere('notclear>contractarea*0.0025')->andWhere('measure > 0');
		}
		if($this->carefulwc == '0') {
			$query->andWhere('contractarea = notclear');
		}
// 	var_dump($query->where);exit;
		if ($this->zongdi == 'icon') {
			$query->andWhere('zongdi <> ""');
		} else {
			$query->andFilterWhere(['like', 'zongdi', $this->zongdi]);
		}
		if ($this->notclear == 'icon') {
			$query->andWhere('notclear <> ""');
		} else {
			$query->andFilterWhere(['like', 'notclear', $this->notclear]);
		}
		if ($this->notstate == 'icon') {
			$query->andWhere('notstate <> ""');
		} else {
			$query->andFilterWhere(['like', 'notstate', $this->notstate]);
		}
		$query->andFilterWhere(['like', 'cardid', $this->cardid])
		->andFilterWhere(['like', 'telephone', $this->telephone])
		->andFilterWhere(['like', 'address', $this->address])
		//            ->andFilterWhere(['like', 'state', $this->state])
		->andFilterWhere(['like', 'oldfarms_id', $this->oldfarms_id])
		//             ->andWhere(['management_area' => $managementarea])
		->andFilterWhere(['like', 'spyear', $this->spyear])
		//             ->andFilterWhere(['like', 'zongdi', $this->zongdi])
		//             ->andFilterWhere(['like', 'notclear', $this->notclear])
		->andFilterWhere(['like', 'cooperative_id', $this->cooperative_id])
		->andFilterWhere(['like', 'surveydate', $this->surveydate])
		->andFilterWhere(['like', 'groundsign', $this->groundsign])
		->andFilterWhere(['like', 'farmersign', $this->farmersign])
		->andFilterWhere(['like', 'pinyin', $this->pinyin])
		->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
		->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
		->andFilterWhere(['like', 'latitude', $this->latitude])
		->andFilterWhere(['like', 'longitude', $this->longitude])
		->andFilterWhere(['like', 'accountnumber', $this->accountnumber])
		->andFilterWhere($this->contractareaSearch($this->contractarea))
		->andFilterWhere($this->betweenSearch());
		//		if (date('Y') !== User::getYear()) {
		//			$query->andFilterWhere(['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]]);
		//		}
		//           	$query->andFilterWhere(['between', 'state', 1,3]);
		// 		var_dump($this->state);
		// 		var_dump($dataProvider->query->where);exit;
		return $dataProvider;
	}
	
	public function searchIndex($params)
	{
//     	    	var_dump($params);exit;
//     	       exit;
		$query = $this->getZhzxCondition();
		//$query->joinWith(['farmer']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		if (isset($params['farmsSearch']['state']) and $params['farmsSearch']['state'] !== null) {
			$this->state = $params['farmsSearch']['state'];
		} else {
			$this->state = null;
		}
//		var_dump($this->state);exit;
//		if (empty($this->state)) {
//			if(User::getYear() == date('Y')) {
//				$this->state = [1, 2, 3, 4, 5];
//			} else {
//				$this->state = [0,1, 2, 3, 4, 5];
//			}
//		}
//     	if(User::getItemname('法规科')) {
//     		$this->state = NULL;
//     	}
//		if (date('Y') == User::getYear())
//			$query->andFilterWhere(['state' => [1, 2, 3, 4]]);
//		$query->andFilterWhere($this->getSearchCondition());
		if (isset($params['farmsSearch']['management_area'])) {
			if ($params['farmsSearch']['management_area'] == 0 or $params['farmsSearch']['management_area'] == '')
				$this->management_area = NULL;
			else
				$this->management_area = $params['farmsSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
			if (count($management_area) > 1)
				$this->management_area = NULL;
			else
				$this->management_area = $management_area;
		}
		$query->andFilterWhere(['management_area' => $this->management_area]);
//     	if(isset($params['farmsSearch']['state']))
//     		$query->andFilterWhere(['state' => $params['farmsSearch']['state']]);

		if (isset($params['farmsSearch']['farmname']))
			$query->andFilterWhere($this->pinyinSearch($params['farmsSearch']['farmname']));
		if (isset($params['farmsSearch']['farmername']))
			$query->andFilterWhere($this->farmerpinyinSearch($params['farmsSearch']['farmername']));

		if (isset($params['farmsSearch']['telephone']))
			$this->telephone = $params['farmsSearch']['telephone'];

//		if (isset($params['farmsSearch']['tempdata']))
//			$this->telephone = $params['farmsSearch']['tempdata'];

		$query->andFilterWhere(['like', 'telephone', $this->telephone]);
		if (isset($params['farmsSearch']['address']))
			$this->address = $params['farmsSearch']['address'];
		$query->andFilterWhere(['like', 'address', $this->address]);
		if (isset($params['farmsSearch']['update_at']))
			$query->andFilterWhere(['between', 'update_at', $params['begindate'], $params['enddate']]);
		if (isset($params['farmsSearch']['measure']))
			$query->andFilterWhere($this->measureSearch($params['farmsSearch']['measure']));
		if (isset($params['farmsSearch']['contractarea']))
			$query->andFilterWhere($this->contractareaSearch($params['farmsSearch']['contractarea']));
		$query->andFilterWhere([
			'id' => $this->id,
			'locked' => $this->locked,
			'state' => $this->state,
			'management_area' => $this->management_area,
			'tempdata' => $this->tempdata,
			'isbreed' => $this->isbreed,
		]);
//		if (date('Y') !== User::getYear())
//			$query->andFilterWhere(['between', 'create_at', $params['begindate'], $params['enddate']]);

//     	var_dump($dataProvider->query->where);exit;
		return $dataProvider;
	}

	public function ttposearch($params)
	{
		//     	var_dump($params);
		//        exit;
		$query = farms::find();
		//$query->joinWith(['farmer']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);


		$this->load($params);
		//var_dump($dataProvider);
		$query->andFilterWhere([
			'id' => $this->id,
			//'state' => $this->state,
		]);
		//$this->management_area = [1, 4, 5];

		$query->orWhere($this->pinyinSearch())
			->orWhere($this->farmerpinyinSearch())
			->andWhere(['state' => $this->state]);
		//->andFilterWhere(['between', 'measure', $this->measure,$this->measure]);

		return $dataProvider;
	}

	public function getIDs($data)
	{
		$ids = [];
		foreach ($data as $value) {
			$ids[] = $value['id'];
		}
		return $ids;
	}

	public function Additional($ids)
	{
		return array_merge($this->ids,$ids);
	}

	public function getSearchCondition()
	{
		$query = Farms::find()->orderBy('farmerpinyin asc');
		if(date('Y') == User::getYear()) {
			if (User::getItemname('法规科') or User::getItemname('信息科', '管理员') and Yii::$app->controller->action->id == 'farmsindex') {
				$query->andFilterWhere(['between', 'state', 0, 5]);
			} else {
				$query->andFilterWhere(['between', 'state', 1, 5]);
			}

//			$this->ids = $this->getIDs($query->all());
		} else {
			//创建日期在所选年度里
//		var_dump(date('Y-m-d',Theyear::getYeartime()[0]));
			if (User::getItemname('法规科') or User::getItemname('信息科', '管理员') and Yii::$app->controller->action->id == 'farmsindex') {
				$query->andFilterWhere(['between', 'state', 0, 5]);
			} else {
				$query->where([
					'and',
					['between', 'state', 1, 5],
					['between','create_at',strtotime('2015-01-01 00:00:01'),Theyear::getYeartime()[1]],
				])->orWhere([
					'and',
//					['state' => 0],
					['otherstate'=>[6,7]],
					['nowyearstate' => -1],
//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
					['between', 'update_at', Theyear::getYeartime()[0], time()],
				]);
			}

		}
//		var_dump($query->where);exit;
		return $query;

	}
	
	public function getLandCondition()
	{
		$whereArray = self::getManagementArea ()['id'];
		$query = Farms::find()->orderBy('farmerpinyin asc');
		if(date('Y') == User::getYear()) {
			if(Yii::$app->user->identity->realname == '杜镇宇') {
				if(Yii::$app->controller->action->id == 'farmsbusiness' or Yii::$app->controller->action->id == 'farmsland') {
					$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 1, 5]);
				} else {
					$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 0, 5]);
				}
			} else {
				$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 1, 5]);
			}
		} else {
			//创建日期在所选年度里
			$query->andFilterWhere([
				'and',
				['management_area' => $whereArray],
				['between', 'state', 1, 5],
				['between', 'create_at', strtotime('2015-01-01 00:00:01'), Theyear::getYeartime()[1]],
			])->orFilterWhere([
				'and',
				['management_area' => $whereArray],
//				['state'=>0],
				['otherstate' => [6,7]],
				['nowyearstate' => -1],
				//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
				['between', 'update_at', Theyear::getYeartime()[0], time()],
			]);
		}
//		var_dump($query->where);exit;
		return $query;
	}

	public function getZhzxCondition()
	{
		$whereArray = self::getManagementArea ()['id'];
		$query = Farms::find()->orderBy('farmerpinyin asc');
		if(date('Y') == User::getYear()) {
			if(Yii::$app->user->identity->realname == '杜镇宇') {
				$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 1, 3]);
			} else {
				$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 1, 5]);
			}
		} else {
			//创建日期在所选年度里
			$query->where([
				'and',
				['management_area' => $whereArray],
				['between', 'state', 1, 5],
				['between', 'create_at', strtotime('2015-01-01 00:00:01'), Theyear::getYeartime()[1]],
			])->orWhere([
				'and',
				['management_area' => $whereArray],
				['state'=>0],
//				['otherstate' => [6,7]],
//				['nowyearstate' => 1],
				//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
			]);
		}

		return $query;
	}
}
