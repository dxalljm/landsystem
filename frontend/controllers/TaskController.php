<?php

namespace frontend\controllers;

use app\models\CollectionElasticSearch;
use app\models\Collectionsum;
use app\models\Fireprevention;
use app\models\Insurance;
use app\models\Plantingstructurecheck;
use app\models\Plantingstructureyearfarmsid;
use app\models\Yields;
use console\models\Loan;
use frontend\models\employeeSearch;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Logs;
use app\models\Farms;
use app\models\Farmer;
use frontend\helpers;
use frontend\helpers\Pinyin;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Plant;
use app\models\Plantingstructure;
use app\models\Collection;
use app\models\User;
use app\models\Department;
use app\models\Theyear;
use app\models\Breedinfo;
use app\models\Breedtype;
use app\models\Goodseed;
use app\models\Search;
use app\models\MenuToUser;
use frontend\helpers\arraySearch;
use app\models\Reviewprocess;
use app\models\Mainmenu;
use app\models\Huinong;
use app\models\Breed;
use app\models\Projectapplication;
/**
 * BankAccountController implements the CRUD actions for BankAccount model.
 */
class TaskController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		} else {
			return true;
		}
	}

	public function actionTaskindex($begindate=null,$enddate=null)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		if(empty($begindate))
			$begindate = Theyear::getYeartime()[0];
		else {
			$begindate = strtotime($begindate.' 00:00:01');
//     		$params['collectionSearch']['state'] = 1;
		}
		if(empty($enddate))
			$enddate = Theyear::getYeartime()[1];
		else
			$enddate = strtotime($enddate.' 23:59:59');
//     	var_dump($begindate);exit;
//		$params['begindate'] = $begindate;
//		$params['enddate'] = $enddate;
		$html = '';
//		$html .= $this->getCollection($begindate,$enddate);
//		$html .= $this->getReviewprocess($begindate,$enddate);
//		$html .= $this->getFire($begindate,$enddate);
		return $this->render('taskindex',[
			'html'=>$this->showTasks($begindate,$enddate),
			'begindate' => $begindate,
			'enddate' => $enddate,
		]);
	}

	public function actionGetinfo($id,$begindate=null,$enddate=null) 
	{
		$result = [];
		switch ($id) {
			case 'plantingstructure':
				$result = $this->getPlantingstructurecheck($begindate,$enddate);
				break;
		}
		return $this->renderAjax('getinfo',[
			'result' => $result
		]);
	}
	
	public function getCollection($begindate=null,$enddate=null)
	{
		$html = '';
		$html .= '<div class="card">';
		$html .= '<div class="card-header"><h3>承包费收缴</h3></div>';
		$html .= '<table class="table">';
		$html .= '<tr>';
		$html .= '<td colspan="4"><h4>承包费收缴:</h4></td>';
		$html .= '<tr>';
		$html .= '<td><h4>应收金额:'.helpers\MoneyFormat::num_format(Collection::totalAmounts()).'元</h4></td>';
		$html .= '<td><h4>已收金额:'.helpers\MoneyFormat::num_format(Collection::totalReal($begindate,$enddate)).'元</h4></td>';
		$html .= '<td><h4>完成比率:'.Collection::totalBfb($begindate,$enddate).'</h4></td>';
		$html .= '</tr>';
		$html .= '</table></div></br>';
		return $html;
	}

	public function getLoan($begindate=null,$enddate=null)
	{
		$management_area = Farms::getManagementArea();
		$loans = Loan::find()->where(['management_area'=>$management_area['id'],'year'=>User::getYear()]);
		if(!empty($begindate)) {
			$loans->andFilterWhere(['between','update_at',$begindate,$enddate]);
		}
//		$html = '<table class="table">';
//		$html .= '<tr>';
//		$html .= '<td>所办理贷款人次:</td>';
//		$html .= '<td>'.$loans->count().'</td>';
//		$html .= '<td>所办理贷款金额:</td>';
//		$html .= '<td>'.$loans->sum('mortgagemoney').'</td>';
//		$html .= '</tr>';
//		$html .= '</table>';
		return ['count'=>$loans->count(),'sum'=>$loans->sum('mortgagemoney')];
	}

	public function getInsurance($begindate=null,$enddate=null)
	{
		$management_area = Farms::getManagementArea();
		$insurance = Insurance::find()->where(['management_area'=>$management_area['id'],'year'=>User::getYear()]);
		if(!empty($begindate)) {
			$insurance->andFilterWhere(['between','update_at',$begindate,$enddate]);
		}
//		$html = '<table class="table">';
//		$html .= '<tr>';
//		$html .= '<td>所办理保险人次:</td>';
//		$html .= '<td>'.$insurance->count().'</td>';
//		$html .= '<td>所办理保险亩数:</td>';
//		$html .= '<td>'.$insurance->sum('insuredarea').'</td>';
//		$html .= '</tr>';
//		$html .= '</table>';
		return ['count'=>$insurance->count(),'area'=>$insurance->sum('insuredarea')];
	}

	public function getPlantingstructurecheck($begindate=null,$enddate=null)
	{
		$management_area = Farms::getManagementArea();
		$plant = Plantingstructurecheck::getPlantname();

		foreach ($plant['id'] as $val) {
			$checks = Plantingstructurecheck::find()->where(['management_area'=>$management_area['id'],'year'=>User::getYear()]);
			if(!empty($begindate)) {
				$checks->andFilterWhere(['between','update_at',$begindate,$enddate]);
			}
			$area = $checks->andFilterWhere(['plant_id'=>$val])->sum('area');
			if($area)
				$plantArea[] = ['name'=>Plant::findOne($val)->typename,'value' => (float)sprintf("%.2f", $area)];
		}
		$checks = Plantingstructurecheck::find()->where(['management_area'=>$management_area['id'],'year'=>User::getYear()]);
		if(!empty($begindate)) {
			$checks->andFilterWhere(['between','update_at',$begindate,$enddate]);
		}
		$html = '<table class="table">';
		foreach ($plantArea as $value) {
			$html .= '<tr>';
			$html .= '<td>'.$value['name'].'</td>';
			$html .= '<td>' .$value['value'].'亩</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		$result['sum'] = (float)sprintf("%.2f", $checks->sum('area'));
		$result['html'] = $html;
		return $result;
	}

	public function getProject($begindate=null,$enddate=null)
	{
		$management_area = Farms::getManagementArea();
		$reviewprocess = Reviewprocess::find()->where(['management_area'=>$management_area['id']]);
		if(!empty($begindate)) {
			$reviewprocess->andFilterWhere(['between','create_at',strtotime($begindate),strtotime($enddate)]);
		} else {
			$begindate = strtotime(User::getYear().'-01-01 00:00:00');
			$enddate = strtotime(User::getYear().'-12-31 23:59:59');
			$reviewprocess->andFilterWhere(['between','create_at',$begindate,$enddate]);
		}
		$count = $reviewprocess->andFilterWhere(['actionname'=>'projectapplication'])->count();
		return ['count' => $count];
	}

	public function getReviewprocess($actionname, $begindate=null,$enddate=null)
	{
//		var_dump($begindate);exit;
		$management_area = Farms::getManagementArea();
		$reviewprocess = Reviewprocess::find()->where(['management_area'=>$management_area['id']]);
		if(!empty($begindate)) {
			$reviewprocess->andFilterWhere(['between','create_at',$begindate,$enddate]);
		} else {
			$begindate = strtotime(User::getYear().'-01-01 00:00:00');
			$enddate = strtotime(User::getYear().'-12-31 23:59:59');
			$reviewprocess->andFilterWhere(['between','create_at',$begindate,$enddate]);
		}

		$result = $reviewprocess->andFilterWhere(['actionname'=>$actionname])->andWhere('state>=6')->count();
		return $result;
//		$loanrows = $this->getLoan($begindate,$enddate)['count'];
//		$insurancerows = $this->getInsurance($begindate,$enddate)['count'];
//		$projectrows = $this->getProject($begindate,$enddate)['count'];
//		$rows = $reviewprocess->count() + $fhrows + $loanrows + $insurancerows + $projectrows;
////		var_dump($reviewprocess->andFilterWhere(['actionname'=>'loancreate'])->andWhere('state>=6')->count());exit;
//		$html = '<div class="card">';
//		$html .= '<table class="table table2-bordered">';
//		$html .= '<tr>';
//		$html .= '<td colspan="5"><h4>共完成任务:'.$rows.'人次</h4></td>';
//		$html .= '</tr>';
//		$html .= '<td><h4>其中</h4></td>';
//		$html .= '<td><h4>过户任务:'.$fhrows.'人次</h4></td>';
//		$html .= '<td><h4>贷款任务:'.$loanrows.'人次&nbsp;&nbsp;&nbsp;&nbsp;贷款金额:'.$this->getLoan($begindate,$enddate)['sum'].'万元</h4></td>';
//		$html .= '<td><h4>保险任务:'.$insurancerows.'人次&nbsp;&nbsp;&nbsp;&nbsp;保险面积:'.$this->getInsurance($begindate,$enddate)['area'].'亩</h4></td>';
//		$html .= '<td><h4>项目任务:'.$projectrows.'个</h4></td>';
//		$html .= '</tr>';
//		$html .= '</table></div></br>';
//		return $html;
	}



	public function getFire($begindate=null,$enddate=null)
	{
		$management_area = Farms::getManagementArea();
		$fire = Fireprevention::find()->where(['management_area'=>$management_area['id']]);
		if(!empty($begindate)) {
			$fire->andFilterWhere(['between','update_at',$begindate,$enddate]);
		} else {
			$fire->andFilterWhere(['between','update_at',strtotime(User::getYear().'-01-01 00:00:00'),strtotime(User::getYear().'-12-31 23:59:59')]);
		}
		$finished = 0;
		$all = $fire->count();
		foreach ($fire->all() as $value) {
			if(Fireprevention::getPercent($value) >= 60) {
				$finished++;
			}
		}
		if($all) {
			$bfb = sprintf("%.2f", $finished / $all) * 100;
		} else {
			$bfb = 0;
		}
		$unfinished = $all-$finished;
		$html = '<div class="card">';
		$html .= '<table class="table table2-bordered">';
		$html .= '<tr>';
		$html .= '<td colspan="4"><h4>防火任务:'.$all.'人次</h4></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td><h4>其中</h4></td>';
		$html .= '<td><h4>完成任务:'.$finished.'</h4></td>';
		$html .= '<td><h4>未完成任务:'.$unfinished.'</h4></td>';
		$html .= '<td><h4>完成比率:'.$bfb.'%</h4></td>';
		$html .= '</tr>';
		$html .= '</table></div>';
		return $html;
	}

	public function getHtml($divInfo,$template='default')
	{
		$html = '';
		$html .= '<div class="col-md-3">';
		$html .= '<div class="card card-pricing card-raised">';
		$html .= '<div class="content">';
		$html .= '<h3>'.$divInfo['title'].'</h3>';
		$html .= '<div class="icon icon-'.$divInfo['color'].'">';
		$html .= '<i class="'.$divInfo['icon'].'"></i>';
		$html .= '</div>';
		$html .= $divInfo['info'];
		$html .= '<p class="card-description">';
//		$html .= $divInfo['description'];
		$html .= '</p><br>';
		$html .= '<a class="btn btn-round" href="#" data-background-color="'.$divInfo['color'].'" onclick="showDialog("plantingstructure")">详细信息</a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	public function showTasks($begindate,$enddate) {

		$arrayBusinessMenu = User::getPlate()['id'];
		$sort = [];
		foreach ($arrayBusinessMenu as $menu) {
			$menuUrl = Mainmenu::find ()->where ( [
				'id' => $menu
			] )->one ();
			$sort[$menuUrl['sort']] = $menuUrl;
		}
		sort($sort);
		$html = '<div class="row" >';

		for($i = 0; $i < count ( $sort ); $i ++) {
			$html .= $this->showTask ( $sort[$i] ,$begindate,$enddate);
		}
		$html .= '</div>';
		return $html;
	}

	private function getPlate($controller, $menuUrl,$begindate,$enddate) {
// 		$cacheKey = 'cache-key-plate1'.\Yii::$app->getUser()->id;
// 		$value = Yii::$app->cache->get($cacheKey);
// 		if (!empty($value)) {
// 			return $value;
// 		}
		$query = Farms::getCondition();
		$where = Farms::getManagementArea ()['id'];
		switch ($controller) {
			case 'farms' :
				$value['color'] = 'blue';
				$value ['icon'] = 'fa fa-delicious';
				$value ['title'] = $menuUrl ['menuname'];
				if(Yii::$app->user->identity->realname == '杜镇宇') {
					$value ['url'] = Url::to ( 'index.php?r=' . 'farms/farmscareful' );
				} else {
					$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				}
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = '农场基础信息';
				break;
			case 'plantingstructure' :
				$value['color'] = 'green';
				$value ['icon'] = 'fa fa-pagelines';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$data = Plantingstructurecheck::find()->where( [
						'management_area' => Farms::getManagementArea ()['id'],
					] )->andFilterWhere(['between','create_at',$begindate,$enddate])->sum('area');
				$value ['info'] = '<h2 class="card-title">'.sprintf("%.2f",$data) .'<span class="text-sm">亩</span></h2>';
				$value ['description'] = '种植作物信息';
				break;
			case 'yields' :
				$value['color'] = 'orange';
				$value ['icon'] = 'fa fa-line-chart';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '产品信息';
				$data = Yields::getYieldsSum();
				$value ['info'] = '<h2 class="card-title">'.$data.'<span class="text-sm">斤</span></h2>';
				$value ['description'] = '农产品产量信息';
				break;
			case 'huinonggrant' :
				$value['color'] = 'purple';
				$value ['icon'] = 'fa fa-dollar';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '现有' . Huinong::find ()->andWhere ( 'enddate>=' . Theyear::getYeartime ()[0] )->andWhere ( 'enddate<=' . Theyear::getYeartime ()[1] )->count () . '条惠农补贴信息';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = '补贴发放情况';
				break;
			case 'collection' :
				$value['color'] = 'rose';
				$value ['icon'] = 'fa fa-cny';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '完成' . Collection::getPercentage () . '%';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = '承包费收缴情况';
				break;
			case 'fireprevention' :
				$value['color'] = 'red';
				$value ['icon'] = 'fa fa-fire-extinguisher';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Fireprevention::find ()->where ( [
						'management_area' => $where
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '户签订防火合同';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = '防火完成情况';
				break;
			case 'breedinfo' :
				$value['color'] = 'rs';
				$value ['icon'] = 'fa fa-github-alt';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$employeerows = Breed::find ()->where ( [
					'management_area' => $where,
					'year' => User::getYear(),
				] )->count ();
				$value ['info'] = '共有' . $employeerows . '户养殖户';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = '养殖户基本信息';
				break;
			case 'disaster' :
				$value['color'] = 'blue';
				$value ['icon'] = 'fa fa-soundcloud';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Disaster::find ()->where ( [
						'management_area' => $where
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '户受灾';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = '农户受灾情况';
				break;
			case 'projectapplication' :
				$value['color'] = 'xm';
				$value ['icon'] = 'fa fa-road';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Projectapplication::find ()->where ( [
						'management_area' => $where
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条基础设施建设';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = '项目情况';
				break;
			case 'insurance' :
				$value['color'] = 'bx';
				$value ['icon'] = 'fa fa-file-text';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '已经办理' . Insurance::find ()->where ( [
						'management_area' => $where,
						'state' => 1
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '保险业务';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = '保险业务';
				break;
			case 'loan' :
				$value['color'] = 'dk';
				$value ['icon'] = 'fa fa-bank';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '现有' . Loan::find ()->where ( [
						'management_area' => $where,
						'lock' => 1
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '贷款业务';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = ' 笔贷款业务';
				break;
			case 'photograph' :
				$value['color'] = 'blue';
				$value ['icon'] = 'fa fa-camera';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$farmerinfo = Farmerinfo::find()->where(['cardid'=>Farms::getFarmsCardID($_GET['farms_id'])])->one();
				if($farmerinfo['cardpic'] == '' or $farmerinfo['cardpicback'] == '' or $farmerinfo['photo'] == '')
					$value ['info'] = '法人电子信息不完整';
				else
					$value['info'] = '法人电子信息已经全部采集完成';
				$value ['info'] = '<h2 class="card-title">'.$query->count ().'<span class="text-sm">户农场</span></h2>';
				$value ['description'] = ' 电子信息采集';
				break;
			default :
				$value = false;
		}
// 		Yii::$app->cache->set($cacheKey, $value, 0);
		return $value;
	}

	private function showTask($menuUrl,$begindate,$enddate) {
		$str = explode ( '/', $menuUrl ['menuurl'] );
		$divInfo = $this->getPlate ( $str [0], $menuUrl ,$begindate,$enddate);
		$html = $this->getHtml($divInfo,$begindate,$enddate);
		return $html;
	}
	
}
