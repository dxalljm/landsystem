<?php
namespace frontend\controllers;
use app\models\BankAccount;
use app\models\Fixed;
use app\models\Plantingstructurecheck;
use app\models\Sales;
use app\models\Subsidyratio;
use app\models\Subsidytypetofarm;
use frontend\helpers\match;
use frontend\models\farmsSearch;
use app\models\Breedtype;
use app\models\Insurance;
use app\models\Pesticides;
use app\models\Plant;
use app\models\Plantinputproduct;
use app\models\Plantpesticides;
use app\models\User;
use app\models\Goodseed;
use app\models\Inputproduct;
use app\models\Insurancecompany;
use Yii;
use app\models\Farms;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Farmer;
use app\models\ManagementArea;
use app\models\Farmermembers;
use app\models\Machineoffarm;
use app\models\Ttpo;
use app\models\Ttpozongdi;
use app\models\Lease;
use app\models\Nation;
use app\models\Machine;
use yii\helpers\Url;
use yii\helpers\Html;
use PhpOffice\PhpWord\Shared\ZipArchive;
use app\models\Projectapplication;
use app\models\Projecttype;
use app\models\Infrastructuretype;
use app\models\Loan;
use frontend\helpers\MoneyFormat;
use app\models\Plantingstructure;
use app\models\Employee;
use app\models\Firepreventionemployee;
use app\models\Theyear;
use app\models\Fireprevention;
use app\models\Breed;
use app\models\Breedinfo;
use app\models\Logs;
use app\models\Leader;

class PrintController extends Controller
{
	public function actionPrintindex()
	{		
		return $this->render('printindex');
	}
	public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		} else {
			return true;
		}
	}
	public function actionPrintsixindex()
	{
		set_time_limit ( 0 );
		$searchModel = new farmsSearch ();
		$whereArray = Farms::getManagementArea()['id'];
		//		var_dump($whereArray);exit;
		$params = Yii::$app->request->queryParams;
		$params ['farmsSearch'] ['state'] = [1,2,3,4,5];
		// 管理区域是否是数组
		if (empty($params['farmsSearch']['mamagement_area'])) {
			$params ['farmsSearch'] ['management_area'] = $whereArray;
		}
		//		 var_dump($params);exit;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog ( '六项基础调查表打印' );
		return $this->render('printsixindex',[
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionPrinthsindex()
	{
		set_time_limit ( 0 );
		$searchModel = new farmsSearch ();
		$whereArray = Farms::getManagementArea()['id'];
		//		var_dump($whereArray);exit;
		$params = Yii::$app->request->queryParams;
		$params ['farmsSearch'] ['state'] = [1,2,3,4,5];
		// 管理区域是否是数组
		if (empty($params['farmsSearch']['mamagement_area'])) {
			$params ['farmsSearch'] ['management_area'] = $whereArray;
		}
		//		 var_dump($params);exit;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog ( '农业基础数据核实表打印' );
		return $this->render('printhsindex',[
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionPrintsixone($farms_id)
	{
		return $this->render('printsixone',[
			'farms_id' => $farms_id,
		]);
	}

	public function actionPrintsone($farms_id)
	{
		return $this->render('printhsone',[
			'farms_id' => $farms_id,
		]);
	}

	public function actionSixtable($farms_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		$isLease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->count();
		return $this->renderAjax('sixtable',[
			'farm' => $farm,
			'isLease' => $isLease,
		]);
	}
	public function actionFarmertable($farms_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		$isFarmerPlanting = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>0])->all();
		$fire = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
//		$breedinfos = Breedinfo::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		$sales = Sales::find()->where(['farms_id'=>$farms_id,'year'=>User::getLastYear()])->all();
		return $this->renderAjax('farmertable',[
			'farms_id' => $farms_id,
			'farm' => $farm,
			'isFarmerPlanting' => $isFarmerPlanting,
			'fire' => $fire,
//			'breedinfos' => $breedinfos,
			'sales' => $sales
		]);
	}
	public function actionFire($farms_id)
	{
		$fire = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
		$breedinfos = Breedinfo::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		return $this->renderAjax('fire',[
			'fire' => $fire,
			'breedinfos' => $breedinfos,
		]);
	}

	public function actionBeizhu()
	{
		return $this->renderAjax('beizhu');
	}

	public function actionFarminfotable($farms_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		return $this->renderAjax('farminfotable',[
			'farm' => $farm,
		]);
	}
	public function actionLeasetable($farms_id,$lease_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		$lease = Lease::find()->where(['id'=>$lease_id,'year'=>User::getYear()])->one();
		$isFarmerPlanting = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>0])->all();
		$sales = Sales::find()->where(['farms_id'=>$farms_id,'year'=>User::getLastYear()])->all();
		$fire = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
		$breedinfos = Breedinfo::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		return $this->renderAjax('leasetable',[
				'lease' => $lease,
				'farms_id' => $farms_id,
				'farm' => $farm,
				'sales' => $sales,
				'isFarmerPlanting' => $isFarmerPlanting,
				'fire' => $fire,
				'breedinfos' => $breedinfos,
		]);
	}
	public function actionHstable($farms_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		//$lease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
		$farmerPlantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>0])->all();
		//$leasePlantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->andWhere('lease_id>0')->all();
		$zongdis = Farms::getZongdi($farms_id);
		//var_dump($zongdis);
		return $this->renderAjax('hstable',[
			'farms_id' => $farms_id,
			'farm' => $farm,
			'farmerPlantings' => $farmerPlantings,
			'zongdiArray' => $zongdis,
		]);
	}
	public function actionHstablelease($farms_id,$lease_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		//$lease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
//		$farmerPlantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>0])->count();
//		$leasePlantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>$lease_id,'year'=>User::getYear()])->all();
		$zongdis = Farms::getZongdi($farms_id);
//		var_dump($zongdis);exit;
		$dd = 0.0;
		$xm = 0.0;
		$mls = 0.0;
		$zd = 0.0;
		$otherarea = 0.0;
//		foreach ($Plantingstructure as $value) {
		$ddID = Plant::find()->where(['typename'=>'大豆'])->one()['id'];
		$xmID = Plant::find()->where(['typename'=>'小麦'])->one()['id'];
		$ymID = Plant::find()->where(['typename'=>'玉米'])->one()['id'];
		$mlsID = Plant::find()->where(['typename'=>'马铃薯'])->one()['id'];
		$zdID = Plant::find()->where(['typename'=>'杂豆'])->one()['id'];
		$allid = ArrayHelper::map(Plant::find()->where('father_id>1')->all(),'id','id');
		sort($allid);
		$array = [$ddID,$xmID,$ymID,$mlsID,$zdID];
		$otherID = array_diff($allid,$array);
		$dd = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>User::getYear(),'plant_id'=>$ddID])->sum('area');
		$xm = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>User::getYear(),'plant_id'=>$xmID])->sum('area');
		$mls = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>User::getYear(),'plant_id'=>$mlsID])->sum('area');
		$ym = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>User::getYear(),'plant_id'=>$ymID])->sum('area');
		$zd = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>User::getYear(),'plant_id'=>$zdID])->sum('area');
		$other = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>User::getYear(),'plant_id'=>$otherID])->sum('area');

		$huinongascription = '';
		foreach (Subsidytypetofarm::find()->all() as $value) {
			$radio = Subsidyratio::find()->where(['lease_id'=>$lease_id,'typeid'=>$value['id']])->one();
			switch ($value['mark']) {
				case 'zhzb':
					//$farmer = new match();
//					$huinongascription .= $value['typename'].':法人占比'.$radio['farmer'].';承租人占比'.$radio['lessee'].';';
					break;
				case 'ddcj':
					$typeid = Plant::find()->where(['typename'=>'大豆'])->one()['id'];
//					var_dump($typeid);exit;
//					$plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'plant_id'=>$typeid])->one();
//					var_dump($dd);exit;
					$huinongascription .= $value['typename'].':法人占比'.$radio['farmer'].'('.sprintf('%.2f',(float)$radio['farmer']/100*$dd).'亩);'.'承租人占比'.$radio['lessee'].'('.sprintf('%.2f',(float)$radio['lessee']/100*$dd).'亩);';
					break;
				case 'ymcj':
					$typeid = Plant::find()->where(['typename'=>'玉米'])->one()['id'];
//					$plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'plant_id'=>$typeid])->one();
					$huinongascription .= $value['typename'].':法人占比'.$radio['farmer'].'('.sprintf('%.2f',(float)$radio['farmer']/100*$ym).'亩);'.'承租人占比'.$radio['lessee'].'('.sprintf('%.2f',(float)$radio['lessee']/100*$ym).'亩);';
					break;
				case 'new':
//					$huinongascription .= $value['typename'].':法人占比'.$radio['farmer'].';承租人占比'.$radio['lessee'].';';
					break;
			}
		}
		return $this->renderAjax('hstablelease',[
			'farms_id' => $farms_id,
			'farm' => $farm,
			'lease_id' => $lease_id,
			'zongdiArray' => $zongdis,
			'info' => $huinongascription,
//			'farmerPlantings' => $farmerPlantings,
		]);
	}
	public function actionGetlessee($farms_id)
	{
		$pingints = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		$result = [];
		foreach($pingints as $value) {
			$result[] = $value['lease_id'];
		}

		$result = array_unique($result);

		echo json_encode(['str'=>implode(',',$result)]);
	}
	public function actionHstable2($farms_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		//$lease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
		$farmerPlantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>0])->all();
		$leasePlantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->andWhere('lease_id>0')->all();
		return $this->renderAjax('hstable2',[
			'farms_id' => $farms_id,
			'farm' => $farm,
			'farmerPlantings' => $farmerPlantings,
			'leasePlantings' => $leasePlantings,
		]);
	}
	public function actionDowntable()
	{
		// 		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		return $this->renderAjax('downtable');
	}
	public function actionSixtableempty()
	{
		// 		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		return $this->renderAjax('sixtableempty');
	}
	public function actionHsdowntable()
	{
		// 		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		return $this->renderAjax('hsdowntable');
	}
	public function actionPrintcontract($farms_id)
	{
		include_once '../../vendor/phpoffice/phpword/samples/Sample_Header.php';
		
		// Template processor instance creation
// 		echo date('H:i:s'), ' 生成新合同...', EOL;
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/Contract.docx');
		
		// Variables on different parts of document
		$templateProcessor->setValue('weekday', htmlspecialchars(date('l'))); // On section/content
		$templateProcessor->setValue('time', htmlspecialchars(date('H:i'))); // On footer
		$templateProcessor->setValue('serverName', htmlspecialchars(realpath(__DIR__))); // On header
		
		// Simple table
// 		$templateProcessor->cloneRow('rowValue', 10);
		
		$farm = Farms::find()->where(['id'=>$farms_id])->one();

		$templateProcessor->setValue('farmsname', html_entity_decode($this->formatName($farm['farmname'],50)));
		$templateProcessor->setValue('farmsname2', html_entity_decode($this->formatName($farm['farmname'],40)));
		$templateProcessor->setValue('farmername', html_entity_decode($this->formatName($farm['farmername'],52)));
		$templateProcessor->setValue('address', htmlspecialchars($farm['address']));
		$templateProcessor->setValue('contract', htmlspecialchars($farm['contractnumber']));
		$templateProcessor->setValue('measure', htmlspecialchars($farm['measure']));
		$templateProcessor->setValue('beginyear', htmlspecialchars(date('Y',strtotime($farm['begindate']))));
		$templateProcessor->setValue('beginmonth', htmlspecialchars(date('m',strtotime($farm['begindate']))));
		$templateProcessor->setValue('beginday', htmlspecialchars(date('d',strtotime($farm['begindate']))));
		$templateProcessor->setValue('endyear', htmlspecialchars(date('Y',strtotime($farm['enddate']))));
		$templateProcessor->setValue('endmonth', htmlspecialchars(date('m',strtotime($farm['enddate']))));
		$templateProcessor->setValue('endday', htmlspecialchars(date('d',strtotime($farm['enddate']))));
		$templateProcessor->setValue('nowyear', htmlspecialchars(date('Y',$farm['update_at'])));
		$templateProcessor->setValue('nowmonth', htmlspecialchars(date('m',$farm['update_at'])));
		$templateProcessor->setValue('nowday', htmlspecialchars(date('d',$farm['update_at'])));
		$templateProcessor->setValue('zongdi', htmlspecialchars($farm['zongdi']));
// 		$templateProcessor->setValue('addresspic', htmlspecialchars(date('d',$farm['update_at'])));
		
		$filename = $farm['contractnumber'].'.docx';
// 		echo date('H:i:s'), ' 保存合同文件...', EOL;
// 		if(file_exists('contract_file/'.$filename))
			$templateProcessor->saveAs('contract_file/'.$filename);
		Logs::writeLogs('生成'.$farm['farmname'].'合同文件');
		return $this->render('printcontract', [
                'filename' => $filename,
            ]);
		
	}
	
	public function actionPrintloan($farms_id,$loan_id)
	{
		include_once '../../vendor/phpoffice/phpword/samples/Sample_Header.php';
	
		// Template processor instance creation
		// 		echo date('H:i:s'), ' 生成新合同...', EOL;
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/loan.docx');
	
		// Variables on different parts of document
		$templateProcessor->setValue('weekday', htmlspecialchars(date('l'))); // On section/content
		$templateProcessor->setValue('time', htmlspecialchars(date('H:i'))); // On footer
		$templateProcessor->setValue('serverName', htmlspecialchars(realpath(__DIR__))); // On header
	
		// Simple table
		// 		$templateProcessor->cloneRow('rowValue', 10);
// 		var_dump($farms_id);exit;
	
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		$loan = Loan::find()->where(['id'=>$loan_id])->one();
// 		var_dump($farm['farmname']);exit;
		
		$templateProcessor->setValue('farmname', htmlspecialchars($farm['farmname']));
		$templateProcessor->setValue('farmername', htmlspecialchars($farm['farmername']));
		$templateProcessor->setValue('measure', htmlspecialchars($farm['measure']));
		$templateProcessor->setValue('contractnumber', htmlspecialchars($farm['contractnumber']));
		$templateProcessor->setValue('measure', htmlspecialchars($farm['measure']));
		$templateProcessor->setValue('bankname', htmlspecialchars($loan['mortgagebank']));
		$templateProcessor->setValue('money', htmlspecialchars(MoneyFormat::big($loan['mortgagemoney'])));
		$templateProcessor->setValue('begindate', htmlspecialchars(date('Y年m月d日',strtotime($loan['begindate']))));
		$templateProcessor->setValue('enddate', htmlspecialchars(date('Y年m月d日',strtotime($loan['enddate']))));
		$templateProcessor->setValue('nowdate', htmlspecialchars(date('Y年m月d日')));
		
		// 		$templateProcessor->setValue('addresspic', htmlspecialchars(date('d',$farm['update_at'])));
	
		$filename = $farm['contractnumber'].'.docx';
		// 		echo date('H:i:s'), ' 保存合同文件...', EOL;
		// 		if(file_exists('contract_file/'.$filename))
		$templateProcessor->saveAs('loan_file/'.$filename);
		Logs::writeLogs('生成'.$farm['farmname'].'贷款文件');
		return $this->render('printloan', [
				'filename' => $filename,
		]);
	
	}
	
	public function actionPrintfarmsfile($farms_id)
	{
		include_once '../../vendor/phpoffice/phpword/samples/Sample_Header.php';
		
		// Template processor instance creation
		// 		echo date('H:i:s'), ' 生成新合同...', EOL;
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/farmsfile.docx');
		
		// Variables on different parts of document
		$templateProcessor->setValue('weekday', htmlspecialchars(date('l'))); // On section/content
		$templateProcessor->setValue('time', htmlspecialchars(date('H:i'))); // On footer
		$templateProcessor->setValue('serverName', htmlspecialchars(realpath(__DIR__))); // On header
		
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		$farmer = Farmer::find()->where(['farms_id'=>$farms_id])->one();
		
		$templateProcessor->setValue('farmsname', html_entity_decode($this->formatName($farm['farmname'],21)));
		$templateProcessor->setValue('farmername', html_entity_decode($this->formatName($farm['farmername'],19)));
		$templateProcessor->setValue('farmsname2', htmlspecialchars($farm['farmname']));
		$templateProcessor->setValue('farmername2', htmlspecialchars($farm['farmername']));
		$templateProcessor->setValue('areaname', html_entity_decode($this->formatName(ManagementArea::find()->where(['id'=>$farm['management_area']])->one()['areaname'],23)));
		$templateProcessor->setValue('cyear', htmlspecialchars(date('Y',$farm['create_at'])));
		$templateProcessor->setValue('cmonth', htmlspecialchars(date('m',$farm['create_at'])));
		$templateProcessor->setValue('cday', htmlspecialchars(date('d',$farm['create_at'])));
		$templateProcessor->setValue('beforename', htmlspecialchars($farmer['farmerbeforename']));
		$templateProcessor->setValue('nation', htmlspecialchars(Nation::find()->where(['id'=>$farmer['nation']])->one()['nationname']));
		$templateProcessor->setValue('p_o', htmlspecialchars($farmer['political_outlook']));
		$templateProcessor->setValue('c_d', htmlspecialchars($farmer['cultural_degree']));
		$templateProcessor->setValue('measure', htmlspecialchars($farm['measure']));
		$templateProcessor->setValue('cardid', htmlspecialchars($farm['cardid']));
		$templateProcessor->setValue('address', htmlspecialchars($farm['address']));
		$templateProcessor->setValue('telephone', htmlspecialchars($farm['telephone']));
		$templateProcessor->setValue('longitude', htmlspecialchars($farm['longitude']));
		$templateProcessor->setValue('latitude', htmlspecialchars($farm['latitude']));
		$templateProcessor->setValue('zongdi', htmlspecialchars($farm['zongdi']));
		$templateProcessor->setValue('domicile', htmlspecialchars($farmer['domicile']));
		$templateProcessor->setValue('nowlive', htmlspecialchars($farmer['nowlive']));
// 		$templateProcessor->setValue('farmpic', htmlspecialchars(''));
// 		$image = new \PhpOffice\PhpWord\Element\Image('images/plant.jpg');
// 		$templateProcessor->saveAs($fileName)
// 		$data=file_get_contents("images/plant.jpg");
// 		$im = base64_encode($data);
		
// 		var_dump($im);exit;
// 		$templateProcessor->setValue('cardpic', ['jpeg','frontend\helpers\image.php']);
		
		$member = Farmermembers::find()->where(['farmer_id'=>$farmer['id']])->all();
		
		$templateProcessor->cloneRow('relationship', count($member));
// 		$templateProcessor->cloneRow('name', count($member));
// 		$templateProcessor->cloneRow('mcardid', count($member));
// 		$templateProcessor->cloneRow('remarks', count($member));
		
		for($i=1;$i<=count($member);$i++) {
			$templateProcessor->setValue('relationship#'.$i, htmlspecialchars(Farmermembers::getRelationship($member[$i-1]['relationship'])));
			$templateProcessor->setValue('name#'.$i, htmlspecialchars($member[$i-1]['membername']));
			$templateProcessor->setValue('mcardid#'.$i, htmlspecialchars($member[$i-1]['cardid']));
			$templateProcessor->setValue('remarks#'.$i, htmlspecialchars($member[$i-1]['remarks']));
		}
		
		$machine = Machineoffarm::find()->where(['farms_id'=>$farms_id])->all();
		
		$templateProcessor->cloneRow('maname', count($machine));
// 		$templateProcessor->cloneRow('name', count($machine));
// 		$templateProcessor->cloneRow('mcardid', count($machine));
// 		$templateProcessor->cloneRow('remarks', count($machine));
		
		for($i=1;$i<=count($machine);$i++) {
			$m = Machine::find()->where(['id'=>$machine[$i-1]['machine_id']])->one();
			$templateProcessor->setValue('maname#'.$i, htmlspecialchars($machine[$i-1]['machinename'].'('.$m['implementmodel'].')'));
			$templateProcessor->setValue('filename#'.$i, htmlspecialchars($m['filename']));
			$templateProcessor->setValue('atime#'.$i, htmlspecialchars($machine[$i-1]['acquisitiontime']));
		}
		
		$ttpo = Ttpo::find()->orWhere(['newfarms_id'=>$farms_id])->orWhere(['oldfarms_id'=>$farms_id])->all();
// 		if($ttpo) {
			$templateProcessor->cloneRow('cdate', count($ttpo));
	// 		$templateProcessor->cloneRow('ttopm', count($ttpo));
	// 		$templateProcessor->cloneRow('ttopzongdi', count($ttpo));
	// 		$templateProcessor->cloneRow('nname', count($ttpo));
			$j = 1;
			for($i=1;$i<=count($ttpo);$i++) {
				$j++;
				$templateProcessor->setValue('cdate#'.$i, htmlspecialchars(date('Y-m-d',$ttpo[$i-1]['create_at'])));
				$templateProcessor->setValue('ttopm#'.$i, htmlspecialchars($farm['measure']));
				$templateProcessor->setValue('ttopzongdi#'.$i, htmlspecialchars('整体过户'));
				$templateProcessor->setValue('nname#'.$i, htmlspecialchars($farm['farmername']));
			}
// 		}
		
		$ttpozongdi = Ttpozongdi::find()->orWhere(['newfarms_id'=>$farms_id])->orWhere(['oldfarms_id'=>$farms_id])->all();
		if($ttpozongdi) {
// 		$templateProcessor->cloneRow('ttopm', count($ttpozongdi));
// 		$templateProcessor->cloneRow('ttopm', count($ttpozongdi));
// 		$templateProcessor->cloneRow('ttopzongdi', count($ttpozongdi));
// 		$templateProcessor->cloneRow('nname', count($ttpozongdi));
			$n = $j+count($ttpozongdi);
// 			var_dump($ttpozongdi);exit;
			for($i=1;$i<=$n;$i++) {
				$templateProcessor->setValue('cdate#'.$i, htmlspecialchars(date('Y-m-d',$ttpozongdi[$i-1]->getAttribute('create_at'))));
				$templateProcessor->setValue('ttopm#'.$i, htmlspecialchars(Lease::getListArea($Leasearea)));
				$templateProcessor->setValue('ttopzongdi#'.$i, htmlspecialchars($ttpozongdi[$i-1]['ttpozongdi']));
				$templateProcessor->setValue('nname#'.$i, htmlspecialchars($farm['farmername']));
			}
		}
		
		$filename = $farm['contractnumber'].'.docx';
		// 		echo date('H:i:s'), ' 保存合同文件...', EOL;
		// 		if(file_exists('contract_file/'.$filename))
		$templateProcessor->saveAs('farmsfile/'.$filename);
// 		$templateProcessor2 = new \PhpOffice\PhpWord\TemplateProcessor('farmsfile/'.$filename);
// 		$zip = new ZipArchive();
// 		if ($zip->open('farmsfile/'.$filename, ZipArchive::OVERWRITE) !== true) {
// 			if ($zip->open('farmsfile/'.$filename, ZipArchive::CREATE) !== true) {
// 				throw new \Exception("Could not open '{$filename}' for writing.");
// 			}
// 		}
// 		$image = call_user_func('imagecreatefromjpeg', 'images/plant.jpg');
// 		$target = 'Pictures/'.time().'.jpg';
// 		ob_start();
// 		call_user_func('imagegif', $image);
// 		$imageContents = ob_get_contents();
// 		ob_end_clean();
// 		$zip->addFromString($target, $imageContents);
// // 		imagedestroy($image);
// 		// 		var_dump(get_class_methods($image));
// 		// 		var_dump($image->getIsWatermark());
// 		var_dump($imageContents);
// 		header('Content-Type: image/jpeg'); //对应jpeg的类型
// 		imagejpeg($imageContents);////也要对应jpeg的类型
// 		var_dump(imagedestroy($imageContents));

		return $this->render('printfarmsfile', [
				'filename' => $filename,
				'cardpic' => $farmer['cardpic'],
		]);
	}

//	public function actionPrintleasecontract($lease_id)
//	{
//		$lease = Lease::findOne($lease_id);
//		$farm = Farms::findOne($lease->farms_id);
//		return $this->render('printleasecontract',[
//			'lease' => $lease,
//
//		]);
//	}

	public function actionPrintleasecontract($lease_id)
	{
//		echo '33333';
//		$this->layout='@app/views/layouts/nomain.php';
// 		include_once '../../vendor/phpoffice/phpword/samples/Sample_Header.php';

		// Template processor instance creation
		// 		echo date('H:i:s'), ' 生成新合同...', EOL;
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/leasecontract.docx');

		// Variables on different parts of document
		$templateProcessor->setValue('weekday', htmlspecialchars(date('l'))); // On section/content
		$templateProcessor->setValue('time', htmlspecialchars(date('H:i'))); // On footer
		$templateProcessor->setValue('serverName', htmlspecialchars(realpath(__DIR__))); // On header

		// Simple table
		// 		$templateProcessor->cloneRow('rowValue', 10);

		$lease = Lease::find()->where(['id'=>$lease_id])->one();
		$lbank = BankAccount::find()->where(['farms_id'=>$lease['farms_id'],'lease_id'=>$lease_id])->one();
		$fbank = BankAccount::find()->where(['farms_id'=>$lease['farms_id'],'lease_id'=>0])->one();
//		var_dump($fbank);exit;
		if($fbank) {
			$templateProcessor->setValue('faccountnumber', $fbank['accountnumber']);
		} else {
			$templateProcessor->setValue('faccountnumber', '');
		}
		if($lbank) {
			$templateProcessor->setValue('laccountnumber', $lbank['accountnumber']);
		} else {
			$templateProcessor->setValue('laccountnumber', '');
		}
// 		var_dump($lease);exit;
		$farm = Farms::find()->where(['id'=>$lease['farms_id']])->one();

		$insurance = Insurance::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>$lease['year']])->one();
		if(empty($insurance)) {
			$insurance = Insurance::find()->where(['farms_id'=>$farm['id'],'lease_id'=>0,'year'=>$lease['year']])->one();
		}
// 		var_dump(html_entity_decode($this->formatName(date('Y年m月d日'),19)));

		$Plantingstructure = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'year'=>$lease['year'],'lease_id'=>$lease['id']])->all();

		$templateProcessor->setValue('pfarmname', $farm['farmname']);
		$templateProcessor->setValue('pfarmername', htmlspecialchars($farm['farmername']));
		$templateProcessor->setValue('pcontractarea', htmlspecialchars($farm['contractarea']));
		$templateProcessor->setValue('plessee', htmlspecialchars($lease['lessee']));
		$templateProcessor->setValue('pleasearea', htmlspecialchars($lease['lease_area']));
		$dd = 0.0;
		$xm = 0.0;
		$mls = 0.0;
		$zd = 0.0;
		$otherarea = 0.0;
//		foreach ($Plantingstructure as $value) {
		$ddID = Plant::find()->where(['typename'=>'大豆'])->one()['id'];
		$xmID = Plant::find()->where(['typename'=>'小麦'])->one()['id'];
		$ymID = Plant::find()->where(['typename'=>'玉米'])->one()['id'];
		$mlsID = Plant::find()->where(['typename'=>'马铃薯'])->one()['id'];
		$zdID = Plant::find()->where(['typename'=>'杂豆'])->one()['id'];
		$allid = ArrayHelper::map(Plant::find()->where('father_id>1')->all(),'id','id');
		sort($allid);
		$array = [$ddID,$xmID,$ymID,$mlsID,$zdID];
		$otherID = array_diff($allid,$array);
		$dd = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>$lease['year'],'plant_id'=>$ddID])->sum('area');
		$xm = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>$lease['year'],'plant_id'=>$xmID])->sum('area');
		$mls = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>$lease['year'],'plant_id'=>$mlsID])->sum('area');
		$ym = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>$lease['year'],'plant_id'=>$ymID])->sum('area');
		$zd = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>$lease['year'],'plant_id'=>$zdID])->sum('area');
		$other = Plantingstructure::find()->where(['farms_id'=>$farm['id'],'lease_id'=>$lease_id,'year'=>$lease['year'],'plant_id'=>$otherID])->sum('area');

//		}
		$templateProcessor->setValue('dd', htmlspecialchars(sprintf('%.2f',$dd)));
		$templateProcessor->setValue('xm', htmlspecialchars(sprintf('%.2f',$xm)));
		$templateProcessor->setValue('mls', htmlspecialchars(sprintf('%.2f',$mls)));
		$templateProcessor->setValue('ym', htmlspecialchars(sprintf('%.2f',$ym)));
		$templateProcessor->setValue('zd', htmlspecialchars(sprintf('%.2f',$zd)));
		$templateProcessor->setValue('other', htmlspecialchars(sprintf('%.2f',$otherarea)));
		$templateProcessor->setValue('year', htmlspecialchars($lease['year']));
		$templateProcessor->setValue('management_area',  htmlspecialchars(ManagementArea::getAreaname($farm['management_area'])));
		$templateProcessor->setValue('farmname3',  htmlspecialchars($farm['farmname']));
		$templateProcessor->setValue('farmname2',  htmlspecialchars($farm['farmname']));
		$templateProcessor->setValue('contractnumber',  htmlspecialchars($farm['contractnumber']));
		$templateProcessor->setValue('contractarea',  htmlspecialchars($farm['contractarea']));

		$templateProcessor->setValue('farmername',  html_entity_decode($farm['farmername']));
		$templateProcessor->setValue('lessee', html_entity_decode($lease['lessee']));
		$templateProcessor->setValue('nowdate', html_entity_decode(date('Y年m月d日')));
		$templateProcessor->setValue('farmername2', htmlspecialchars($farm['farmername']));
		$templateProcessor->setValue('farmname', html_entity_decode($farm['farmname']));
		$templateProcessor->setValue('cardid', htmlspecialchars($farm['cardid']));
		$templateProcessor->setValue('address', htmlspecialchars($farm['address']));
		$templateProcessor->setValue('telephone', htmlspecialchars($farm['telephone']));
		$templateProcessor->setValue('lease', htmlspecialchars($lease['lessee']));
		$templateProcessor->setValue('leasecardid', htmlspecialchars($lease['lessee_cardid']));
		$templateProcessor->setValue('leaseaddress', htmlspecialchars($lease['address']));
		$templateProcessor->setValue('leasetelephone', htmlspecialchars($lease['lessee_telephone']));
		$templateProcessor->setValue('leasemoney', htmlspecialchars($lease['rent']));
		$templateProcessor->setValue('leasemode', htmlspecialchars($lease['rentpaymode']));
		$templateProcessor->setValue('renttime', htmlspecialchars(Theyear::dateToStr($lease['begindate'])));
		$templateProcessor->setValue('lbegindate', htmlspecialchars(Theyear::dateToStr($lease['begindate'])));
		$templateProcessor->setValue('lenddate', htmlspecialchars(Theyear::dateToStr($lease['enddate'])));
		$templateProcessor->setValue('renttype', htmlspecialchars($lease['renttype']));
		$templateProcessor->setValue('farmername3', html_entity_decode($this->formatName($farm['farmname'],33)));
// 		var_dump(Lease::getListArea($lease['lease_area']));exit;
		$templateProcessor->setValue('zongdimeasure', html_entity_decode(Lease::getListArea($lease['lease_area'])));
		$templateProcessor->setValue('leasedate', html_entity_decode($lease['begindate'].'至'.$lease['enddate']));
//		$templateProcessor->setValue('zongdi', html_entity_decode($this->formatName($farm['zongdi'],37)));
// var_dump($lease['otherassumpsit']);
		$templateProcessor->setValue('otherassumpsit', htmlspecialchars($lease['otherassumpsit']));

		$templateProcessor->setValue('policyholder', htmlspecialchars($insurance['policyholder']));
		$templateProcessor->setValue('insured', htmlspecialchars($insurance['policyholder']));
		$huinongascription = '';
		foreach (Subsidytypetofarm::find()->all() as $value) {
			$radio = Subsidyratio::find()->where(['lease_id'=>$lease_id,'typeid'=>$value['id']])->one();
			switch ($value['mark']) {
				case 'zhzb':
					//$farmer = new match();
					$huinongascription .= $value['typename'].':法人占比'.$radio['farmer'].';承租人占比'.$radio['lessee'].';';
					break;
				case 'ddcj':
					$typeid = Plant::find()->where(['typename'=>'大豆'])->one()['id'];
//					var_dump($typeid);exit;
//					$plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'plant_id'=>$typeid])->one();
//					var_dump($dd);exit;
					$huinongascription .= $value['typename'].':法人占比'.$radio['farmer'].'('.sprintf('%.2f',(float)$radio['farmer']/100*$dd).'亩);'.'承租人占比'.$radio['lessee'].'('.sprintf('%.2f',(float)$radio['lessee']/100*$dd).'亩)';
					break;
				case 'ymcj':
					$typeid = Plant::find()->where(['typename'=>'玉米'])->one()['id'];
//					$plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'plant_id'=>$typeid])->one();
					$huinongascription .= $value['typename'].':法人占比'.$radio['farmer'].'('.sprintf('%.2f',(float)$radio['farmer']/100*$ym).'亩);'.'承租人占比'.$radio['lessee'].'('.sprintf('%.2f',(float)$radio['lessee']/100*$ym).'亩)';
					break;
				case 'new':
					$huinongascription .= $value['typename'].':法人占比'.$radio['farmer'].';承租人占比'.$radio['lessee'].';';
					break;
			}
		}
		$templateProcessor->setValue('huinongascription',htmlspecialchars($huinongascription));

		$employee = Employee::find()->where(['farms_id'=>$farm['id'],'year'=>$lease['year']])->all();
// 		var_dump($employee[0]);
		$templateProcessor->cloneRow('ename', count($employee));
		for($i=1;$i<=count($employee);$i++) {

			$templateProcessor->setValue('i#'.$i, htmlspecialchars($i));
			$templateProcessor->setValue('ename#'.$i, htmlspecialchars($employee[$i-1]['employeename']));
			$templateProcessor->setValue('esex#'.$i, htmlspecialchars(substr($employee[$i-1]['cardid'], (strlen($employee[$i-1]['cardid'])==15 ? -2 : -1), 1) % 2 ? '男' : '女'));
			$templateProcessor->setValue('ecardid#'.$i, htmlspecialchars($employee[$i-1]['cardid']));
			$templateProcessor->setValue('ephone#'.$i, htmlspecialchars($employee[$i-1]['telephone']));
			$templateProcessor->setValue('esmoking#'.$i, htmlspecialchars(Firepreventionemployee::find()->where(['employee_id'=>$employee[$i-1]['id']])->one()['is_smoking']? '是' : '否'));
		}

		$fixeds = Fixed::find()->where(['cardid'=>$farm->cardid])->all();
//		$templateProcessor->setValue('fname', htmlspecialchars('1111111111'));
//		var_dump($fixeds);exit;
//		if($fixeds) {
			$templateProcessor->cloneRow('fname', count($fixeds));
			for ($i = 1; $i <= count($fixeds); $i++) {

				$templateProcessor->setValue('f#' . $i, htmlspecialchars($i));
				$templateProcessor->setValue('fname#' . $i, htmlspecialchars($fixeds[$i - 1]['name']));
				$templateProcessor->setValue('funit#' . $i, htmlspecialchars($fixeds[$i - 1]['unit']));
				$templateProcessor->setValue('fnumber#' . $i, htmlspecialchars($fixeds[$i - 1]['number']));
				$templateProcessor->setValue('fstate#' . $i, htmlspecialchars($fixeds[$i - 1]['state']));
				$templateProcessor->setValue('fremarks#' . $i, htmlspecialchars($fixeds[$i - 1]['remarks']));
			}
//		} else {
//			$templateProcessor->setValue('f' . $i, htmlspecialchars(''));
//			$templateProcessor->setValue('fname' . $i, htmlspecialchars(''));
//			$templateProcessor->setValue('funit' . $i, htmlspecialchars(''));
//			$templateProcessor->setValue('fnumber' . $i, htmlspecialchars(''));
//			$templateProcessor->setValue('fstate' . $i, htmlspecialchars(''));
//			$templateProcessor->setValue('fremarks' . $i, htmlspecialchars(''));
//		}
		$filename = $farm['contractnumber'].'.docx';
		// 		echo date('H:i:s'), ' 保存合同文件...', EOL;
		// 		if(file_exists('contract_file/'.$filename))
		$templateProcessor->saveAs('leasefile/'.$filename);

		$urlFielname = \Yii::$app->getUrlManager()->baseUrl.$filename;
// 		var_dump($urlFielname);exit;
		return $this->render('printleasecontract', [
				'filename' => $filename,
		]);

	}
	
	public function actionPrintproject($id)
	{
		include_once '../../vendor/phpoffice/phpword/samples/Sample_Header.php';
	
		// Template processor instance creation
		// 		echo date('H:i:s'), ' 生成新合同...', EOL;
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/application.docx');
// 		var_dump($templateProcessor);
		// Variables on different parts of document
		$templateProcessor->setValue('weekday', htmlspecialchars(date('l'))); // On section/content
		$templateProcessor->setValue('time', htmlspecialchars(date('H:i'))); // On footer
		$templateProcessor->setValue('serverName', htmlspecialchars(realpath(__DIR__))); // On header
	
		// Simple table
		// 		$templateProcessor->cloneRow('rowValue', 10);
	
		$project = Projectapplication::find()->where(['id'=>$id])->one();
		$farm = Farms::find()->where(['id'=>$project['farms_id']])->one();
		$typename = Infrastructuretype::find()->where(['id'=>$project['projecttype']])->one()['typename'];
		
		$templateProcessor->setValue('projecttype', htmlspecialchars($typename));
		$templateProcessor->setValue('farmername', htmlspecialchars($farm['farmername']));
		$templateProcessor->setValue('farmname', htmlspecialchars($farm['farmname']));
		$templateProcessor->setValue('farmaddress', htmlspecialchars($farm['address']));
		$templateProcessor->setValue('projectcontent', htmlspecialchars($project['content']));
		$templateProcessor->setValue('projectdata', htmlspecialchars($project['projectdata']));
		$templateProcessor->setValue('unit', htmlspecialchars($project['unit']));
		
		$templateProcessor->setValue('nowyear', htmlspecialchars(date('Y',$project['update_at'])));
		$templateProcessor->setValue('nowmonth', htmlspecialchars(date('m',$project['update_at'])));
		$templateProcessor->setValue('nowday', htmlspecialchars(date('d',$project['update_at'])));
	
// 		$str = date('Y',$project['update_at']).$farm['farmername'].$typename.'申请.docx';
// 		$filename = iconv("UTF-8","gbk//TRANSLIT", $str);
		$filename = date('Y',$project['update_at']).'-'.$farm['id'].'-'.$project['id'].'.docx';
		// 		echo date('H:i:s'), ' 保存合同文件...', EOL;
		// 		if(file_exists('contract_file/'.$filename))
// 		var_dump($templateProcessor);
		$templateProcessor->saveAs('projectapplication/'.$filename);
	
		
		return $this->render('printproject', [
				'filename' => $filename,
		]);
	
	}
	
	private function formatName($name,$l)
	{
		$strlen = strlen($name);
// 		var_dump($name);
//		if(!eregi("[^\x80-\xff]","$name")){
			if($strlen == 6) {
				$one = mb_substr($name,0,1,'UTF-8');
				$two = mb_substr($name,1,1,'UTF-8');
				$name = $one.'  '.$two;
			}
//		}
// 		var_dump($name);
		$alllen = $l;
		$newlen = strlen($name);
//		var_dump($newlen);
		$len = intval(($alllen - $newlen)/2);
		$sub = $alllen - $newlen;
		$mod = $sub%2;
		$str1 = $this->str_prefix($name,$len,'&nbsp;');
		$str = $this->str_suffix($str1,$len,'&nbsp;');
//		var_dump($mod);
		if($mod and strlen($str) < 68) {
			$str .= '&nbsp;';
		}
// 		var_dump($str);
		return $str;
	}
	
	private function str_prefix($str, $n=1, $char=" "){
		for ($x=0;$x<$n;$x++){$str = $char.$str;}
		return $str;
	}
	private function str_suffix($str, $n=1, $char=" "){
		for ($x=0;$x<$n;$x++){$str = $str.$char;}
		return $str;
	}

	public function actionPrintsixcheck($farms_id)
	{

//		include_once '../../vendor/phpoffice/phpword/samples/Sample_Header.php';

		// Template processor instance creation
		// 		echo date('H:i:s'), ' 生成新合同...', EOL;
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/sixcheck.docx');

		// Variables on different parts of document
//		$templateProcessor->setValue('weekday', htmlspecialchars(date('l'))); // On section/content
//		$templateProcessor->setValue('time', htmlspecialchars(date('H:i'))); // On footer
//		$templateProcessor->setValue('serverName', htmlspecialchars(realpath(__DIR__))); // On header

		// Simple table
		// 		$templateProcessor->cloneRow('rowValue', 10);
		$farm = Farms::findOne($farms_id);
		$plantingstructures = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		$islease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->count();
//		$insuranceData = Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		$fireData = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
		$breedData = Breed::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
		$breedinfoData = Breedinfo::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();

// 		var_dump(html_entity_decode($this->formatName(date('Y年m月d日'),19)));

		$templateProcessor->setValue('management_area', ManagementArea::getAreaname($farm->management_area));
		$templateProcessor->setValue('toYear', User::getYear());
		$templateProcessor->setValue('farmname', htmlspecialchars($farm->farmname));
		$templateProcessor->setValue('contractnumber', htmlspecialchars($farm->contractnumber));
		$templateProcessor->setValue('contractarea', htmlspecialchars($farm->contractarea));
		$templateProcessor->setValue('address', htmlspecialchars($farm->address));
		$templateProcessor->setValue('farmername', htmlspecialchars($farm->farmername));
		$templateProcessor->setValue('cardid', htmlspecialchars($farm->cardid));
		$templateProcessor->setValue('telephone', htmlspecialchars($farm->telephone));
        $templateProcessor->setValue('today', htmlspecialchars(date('Y年m月d日')));
		$templateProcessor->setValue('coordinate', htmlspecialchars($farm->longitude.'  '.$farm->latitude));
		if($islease) {
			$templateProcessor->setValue('isLease', htmlspecialchars('是'));
		} else {
			$templateProcessor->setValue('isLease', htmlspecialchars('否'));
		}
        $templateProcessor->setValue('firecontract', htmlspecialchars($fireData['firecontract']?'是':'否'));
        $templateProcessor->setValue('safecontract', htmlspecialchars($fireData['safecontract']?'是':'否'));
        $templateProcessor->setValue('environmental_agreement', htmlspecialchars($fireData['environmental_agreement']?'是':'否'));
        $templateProcessor->setValue('fieldpermit', htmlspecialchars($fireData['fieldpermit']?'是':'否'));
        $templateProcessor->setValue('leaflets', htmlspecialchars($fireData['leaflets']?'是':'否'));
        $templateProcessor->setValue('rectification_record', htmlspecialchars($fireData['rectification_record']?'是':'否'));

//        var_dump($breedinfoData);
        if($breedinfoData) {
            $templateProcessor->cloneRow('j', count($breedinfoData));
            for ($k = 0; $k < count($breedinfoData); $k++) {
//                var_dump($breedinfoData[$k]);
                $breedtype = Breedtype::find()->where(['id' => $breedinfoData[$k]['breedtype_id']])->one()['typename'];
                $l = $k+1;
                $templateProcessor->setValue('j#' . $l, htmlspecialchars($l));
                $templateProcessor->setValue('breedtype_id#' . $l, htmlspecialchars($breedtype));
                $templateProcessor->setValue('number#' . $l, htmlspecialchars($breedinfoData[$k]['number']));
                $templateProcessor->setValue('basicinvestment#' . $l, htmlspecialchars($breedinfoData[$k]['basicinvestment']));
                $templateProcessor->setValue('housingarea#' . $l, htmlspecialchars($breedinfoData[$k]['housingarea']));
            }
        } else {
            $templateProcessor->setValue('j', htmlspecialchars('——'));
            $templateProcessor->setValue('breedtype_id', htmlspecialchars('——'));
            $templateProcessor->setValue('number', htmlspecialchars('——'));
            $templateProcessor->setValue('basicinvestment', htmlspecialchars('——'));
            $templateProcessor->setValue('housingarea', htmlspecialchars('——'));
        }
        $leaser = [];
        foreach ($plantingstructures as $value) {
            $leaser[] = $value['lease_id'];
        }
        $leaser2 = array_unique($leaser);
        foreach ($leaser2 as $value) {
            $lastLeaser[] = $value;
        }
		$templateProcessor->cloneRow('i', count($lastLeaser));
		for($i=1;$i<=count($lastLeaser);$i++) {
            $plantingstructureData = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>$lastLeaser[$i-1],'year'=>User::getYear()])->all();
            $areaSum = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>$lastLeaser[$i-1],'year'=>User::getYear()])->sum('area');
            foreach ($plantingstructureData as $key=>$value) {
                if ($value['lease_id'] == 0) {
                    $lessee = $farm->farmername;
                    $cardid = $farm->cardid;
                    $telephone = $farm->telephone;
                    $templateProcessor->setValue('i#' . $i, htmlspecialchars($i));
                    $templateProcessor->setValue('lessee#' . $i, htmlspecialchars($lessee));
                    $templateProcessor->setValue('lessee_cardid#' . $i, htmlspecialchars($cardid));
                    $templateProcessor->setValue('lessee_telephone#' . $i, htmlspecialchars($telephone));
                    $templateProcessor->setValue('lease_area#' . $i, htmlspecialchars($areaSum));
                    $templateProcessor->setValue('zhzb_farmer#' . $i, htmlspecialchars('100%'));
                    $templateProcessor->setValue('zhzb_lessee#' . $i, htmlspecialchars('0%'));
                    $templateProcessor->setValue('ddcj_farmer#' . $i, htmlspecialchars('100%'));
                    $templateProcessor->setValue('ddcj_lessee#' . $i, htmlspecialchars('0%'));
                    $templateProcessor->setValue('goodseed_farmer#' . $i, htmlspecialchars('100%'));
                    $templateProcessor->setValue('goodseed_lessee#' . $i, htmlspecialchars('0%'));
                    $templateProcessor->setValue('new_farmer#' . $i, htmlspecialchars('100%'));
                    $templateProcessor->setValue('new_lessee#' . $i, htmlspecialchars('0%'));
                } else {
                    $leaseData = Lease::find()->where(['id' => $value['lease_id']])->one();
                    $lessee = $leaseData['lessee'];
                    $cardid = $leaseData['lessee_cardid'];
                    $telephone = $leaseData['lessee_telephone'];
                    $templateProcessor->setValue('i#' . $i, htmlspecialchars($i));
                    $templateProcessor->setValue('lessee#' . $i, htmlspecialchars($lessee));
                    $templateProcessor->setValue('lessee_cardid#' . $i, htmlspecialchars($cardid));
                    $templateProcessor->setValue('lessee_telephone#' . $i, htmlspecialchars($telephone));
                    $templateProcessor->setValue('lease_area#' . $i, htmlspecialchars($areaSum));
                    $templateProcessor->setValue('zhzb_farmer#' . $i, htmlspecialchars($leaseData['zhzb_farmer']));
                    $templateProcessor->setValue('zhzb_lessee#' . $i, htmlspecialchars($leaseData['zhzb_lessee']));
                    $templateProcessor->setValue('ddcj_farmer#' . $i, htmlspecialchars($leaseData['ddcj_farmer']));
                    $templateProcessor->setValue('ddcj_lessee#' . $i, htmlspecialchars($leaseData['ddcj_lessee']));
                    $templateProcessor->setValue('goodseed_farmer#' . $i, htmlspecialchars($leaseData['goodseed_farmer']));
                    $templateProcessor->setValue('goodseed_lessee#' . $i, htmlspecialchars($leaseData['goodseed_lessee']));
                    $templateProcessor->setValue('new_farmer#' . $i, htmlspecialchars($leaseData['new_farmer']));
                    $templateProcessor->setValue('new_lessee#' . $i, htmlspecialchars($leaseData['new_lessee']));
                }
            }
                $plant = Plant::find()->where(['id' => $value['plant_id']])->one();
                $other = 0.0;
                $dd = 0.0;
                $xm = 0.0;
                $mls = 0.0;
                $zd = 0.0;
//                $plantArray[$key] = $plant['typename'];
//                $goodseed[$key] = $value['goodseed_id'];
//                $Input = Plantinputproduct::find()->where(['planting_id' => $value['id']])->one();
//                $inputproductID[$key] = $Input['inputproduct_id'];
//                $inputproductpconsumption[$key] = $Input['pconsumption'];
//                $Pesticides = Plantpesticides::find()->where(['planting_id' => $value['id']])->one();
//                $pesticidesID[$key] = $Pesticides['pesticides_id'];
//                //var_dump($Pesticides['pconsumption']);exit;
//                $pesticidespconsumption[$key] = $Pesticides['pconsumption'];
//                switch ($plant['typename']) {
//                    case '大豆':
//                        if ($value['area'] > 0.0 and $value['area'] !== '') {
//                            $dd = $value['area'];
//                        }
//                        break;
//                    case '小麦':
//                        if ($value['area'] > 0.0 and $value['area'] !== '') {
//                            $xm = $value['area'];
//                        }
//                        break;
//                    case '马铃薯':
//                        if ($value['area'] > 0.0 and $value['area'] !== '') {
//                            $mls = $value['area'];
//                        }
//                        break;
//                    case '杂豆':
//                        if ($value['area'] > 0.0 and $value['area'] !== '') {
//                            $zd = $value['area'];
//                        }
//                        break;
//                    default:
//                        $other += $value['area'];
//                }
//
//                $templateProcessor->setValue('dd#' . $i, htmlspecialchars($dd));
//                $templateProcessor->setValue('xm#' . $i, htmlspecialchars($xm));
//                $templateProcessor->setValue('mls#' . $i, htmlspecialchars($mls));
//                $templateProcessor->setValue('zd#' . $i, htmlspecialchars($zd));
//                if ($other > 0.0) {
//                    $templateProcessor->setValue('other#' . $i, htmlspecialchars($other));
//                } else {
//                    $templateProcessor->setValue('other#' . $i, htmlspecialchars(0));
//                }
//            var_dump($plantArray);exit;
                $templateProcessor->cloneRow('plants#' . $i, count($plantingstructureData));
                for ($j = 1; $j <= count($plantingstructureData); $j++) {
                    $goodseedname = Goodseed::find()->where(['id' => $plantingstructureData[$j-1]['goodseed_id']])->one()['typename'];

                    $plant = Plant::find()->where(['id' => $plantingstructureData[$j-1]['plant_id']])->one();
                    $Input = Plantinputproduct::find()->where(['planting_id' => $plantingstructureData[$j-1]['id']])->one();
                    $inputproductName = Inputproduct::find()->where(['id' => $Input['inputproduct_id']])->one()['fertilizer'];
                    $Pesticides = Plantpesticides::find()->where(['planting_id' => $plantingstructureData[$j-1]['id']])->one();
                    $pesticidesName = Pesticides::find()->where(['id' => $Pesticides['pesticides_id']])->one()['pesticidename'];

                    $templateProcessor->setValue('plants#' . $i . '#' . $j, htmlspecialchars($plant['typename']));
                    $templateProcessor->setValue('area#' . $i . '#' . $j, htmlspecialchars($plantingstructureData[$j-1]['area']));
                    $templateProcessor->setValue('goodseed#' . $i . '#' . $j, htmlspecialchars($goodseedname));
                    $templateProcessor->setValue('inputproductname#' . $i . '#' . $j, htmlspecialchars($inputproductName));
                    $templateProcessor->setValue('inputproductnumber#' . $i . '#' . $j, htmlspecialchars($Input['pconsumption']));
                    $templateProcessor->setValue('pesticidesname#' . $i . '#' . $j, htmlspecialchars($pesticidesName));
                    $templateProcessor->setValue('pesticidesnumber#' . $i . '#' . $j, htmlspecialchars($Pesticides['pconsumption']));
                }

                $insuranceData = Insurance::find()->where(['farms_id' => $farm->id, 'year' => User::getYear(), 'cardid' => $cardid])->one();
//			var_dump($insuranceData);
                if ($insuranceData) {
                    $templateProcessor->setValue('policyholder#' . $i, htmlspecialchars($insuranceData['policyholder']));
                    $templateProcessor->setValue('policyholder2#' . $i, htmlspecialchars($insuranceData['policyholder']));
                    $templateProcessor->setValue('companyname#' . $i, htmlspecialchars(Insurancecompany::find()->where(['id' => $insuranceData['company_id']])->one()['companynname']));
                    $templateProcessor->setValue('insuredarea#' . $i, htmlspecialchars($insuranceData['insuredarea']));
                    if($insuranceData['insuredsoybean']) {
                    	$templateProcessor->setValue('insuredsoybean#' . $i, htmlspecialchars($insuranceData['insuredsoybean']));
                    } else {
                    	$templateProcessor->setValue('insuredsoybean#' . $i, htmlspecialchars(0));
                    }
                    
                    if($insuranceData['insuredsoybean']) {
                    	$templateProcessor->setValue('insuredwheat#' . $i, htmlspecialchars($insuranceData['insuredwheat']));
                    } else {
                    	$templateProcessor->setValue('insuredwheat#' . $i, htmlspecialchars(0));
                    }
                    if($insuranceData['insuredother']) {
                    	$templateProcessor->setValue('insuredother#' . $i, htmlspecialchars($insuranceData['insuredother']));
                    } else {
                    	$templateProcessor->setValue('insuredother#' . $i, htmlspecialchars(0));
                    }
                    
                } else {
                    $templateProcessor->setValue('policyholder#' . $i, htmlspecialchars('——'));
                    $templateProcessor->setValue('policyholder2#' . $i, htmlspecialchars('——'));
                    $templateProcessor->setValue('companyname#' . $i, htmlspecialchars('——'));
                    $templateProcessor->setValue('insuredarea#' . $i, htmlspecialchars('——'));
                    $templateProcessor->setValue('insuredsoybean#' . $i, htmlspecialchars('——'));
                    $templateProcessor->setValue('insuredwheat#' . $i, htmlspecialchars('——'));
                    $templateProcessor->setValue('insuredother#' . $i, htmlspecialchars('——'));
                }
            }

//        $machine = Machineoffarm::find()->where(['farms_id'=>$farms_id])->all();

//        $templateProcessor->cloneRow('maname', count($machine));
// 		$templateProcessor->cloneRow('name', count($machine));
// 		$templateProcessor->cloneRow('mcardid', count($machine));
// 		$templateProcessor->cloneRow('remarks', count($machine));

//        for($m=1;$m<=count($machine);$m++) {
//            $machetype = Machine::find()->where(['id'=>$machine[$m-1]['machine_id']])->one();
//            $templateProcessor->setValue('m#'.$m, htmlspecialchars($m));
//            $templateProcessor->setValue('maname#'.$m, htmlspecialchars($machine[$m-1]['machinename'].'('.$machetype['implementmodel'].')'));
//            $templateProcessor->setValue('filename#'.$m, htmlspecialchars($machetype['filename']));
//            $templateProcessor->setValue('atime#'.$m, htmlspecialchars($machine[$m-1]['acquisitiontime']));
//        }


		$filename =  $farm['contractnumber'].'.docx';
		// 		echo date('H:i:s'), ' 保存合同文件...', EOL;
		// 		if(file_exists('contract_file/'.$filename))
		$templateProcessor->saveAs('sixcheckfile/'.$filename);

		$urlFielname = \Yii::$app->getUrlManager()->baseUrl.$filename;
// 		var_dump($urlFielname);exit;
		return $this->render('printsixcheck', [
			'filename' => $filename,
		]);

	}

	public function actionPrinttest()
    {
        include_once '../../vendor/phpoffice/phpword/samples/Sample_Header.php';
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/test.docx');
        $templateProcessor->cloneRow('a', 5);
        for($i=1;$i<=5;$i++) {
            $templateProcessor->setValue('a#'.$i,htmlspecialchars('A'.$i));
            $templateProcessor->setValue('b#'.$i,htmlspecialchars('B'.$i));
            $templateProcessor->setValue('c#'.$i,htmlspecialchars('C'.$i));
            $templateProcessor->setValue('d#'.$i,htmlspecialchars('D'.$i));
            $templateProcessor->setValue('e#'.$i,htmlspecialchars('E'.$i));
        }

        $templateProcessor->cloneRow('i', 5);
        for($i=1;$i<=5;$i++) {
            $templateProcessor->setValue('i#'.$i,htmlspecialchars($i));
            $templateProcessor->setValue('ib#'.$i,htmlspecialchars('B'.$i));
            $templateProcessor->setValue('ic#'.$i,htmlspecialchars('C'.$i));
            $templateProcessor->setValue('id#'.$i,htmlspecialchars('D'.$i));
//            $templateProcessor->setValue('ie#'.$i,htmlspecialchars('E'.$i));
        }
        $filename = 'test.docx';
        // 		echo date('H:i:s'), ' 保存合同文件...', EOL;
        // 		if(file_exists('contract_file/'.$filename))
        $templateProcessor->saveAs('sixcheckfile/'.$filename);

        $urlFielname = \Yii::$app->getUrlManager()->baseUrl.$filename;
// 		var_dump($urlFielname);exit;
        return $this->render('printtest', [
            'filename' => $filename,
        ]);
    }

}