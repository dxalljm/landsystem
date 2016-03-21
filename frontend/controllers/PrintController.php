<?php
namespace frontend\controllers;

use Yii;
use app\models\Farms;
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

class PrintController extends Controller
{
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

		$templateProcessor->setValue('farmsname', htmlspecialchars($this->formatName($farm['farmname'],32)));
		$templateProcessor->setValue('farmsname2', htmlspecialchars($this->formatName($farm['farmname'],40)));
		$templateProcessor->setValue('farmername', htmlspecialchars($this->formatName($farm['farmername'],31)));
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
		
		$templateProcessor->setValue('farmsname', htmlspecialchars($this->formatName($farm['farmname'],28)));
		$templateProcessor->setValue('farmername', htmlspecialchars($this->formatName($farm['farmername'],28)));
		$templateProcessor->setValue('farmsname2', htmlspecialchars($farm['farmname']));
		$templateProcessor->setValue('farmername2', htmlspecialchars($farm['farmername']));
		$templateProcessor->setValue('areaname', htmlspecialchars($this->formatName(ManagementArea::find()->where(['id'=>$farm['management_area']])->one()['areaname'],30)));
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
		
// 		$templateProcessor2->saveAs('farmsfile/'.$filename);
		$urlFielname = 'http://front.lngwh.gov:8001'.\Yii::$app->getUrlManager()->baseUrl.'/farmsfile/'.$filename;
		return $this->render('printfarmsfile', [
				'filename' => $filename,
				'cardpic' => $farmer['cardpic'],
		]);
	}
	public function actionPrintleasecontract($lease_id)
	{
		include_once '../../vendor/phpoffice/phpword/samples/Sample_Header.php';
	
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
		$farm = Farms::find()->where(['id'=>$lease['farms_id']])->one();
		
		$templateProcessor->setValue('farmername', htmlspecialchars($this->formatName($farm['farmername'],28)));
		$templateProcessor->setValue('lessee', htmlspecialchars($this->formatName($lease['lessee'],28)));
		$templateProcessor->setValue('nowdate', htmlspecialchars($this->formatName(date('Y年m月d日'),26)));
		$templateProcessor->setValue('farmername2', htmlspecialchars($farm['farmername']));
		$templateProcessor->setValue('farmname', htmlspecialchars($this->formatName($farm['farmname'],54)));
		$templateProcessor->setValue('cardid', htmlspecialchars($farm['cardid']));
		$templateProcessor->setValue('address', htmlspecialchars($farm['address']));
		$templateProcessor->setValue('telephone', htmlspecialchars($farm['telephone']));
		$templateProcessor->setValue('lease', htmlspecialchars($lease['lessee']));
		$templateProcessor->setValue('leasecardid', htmlspecialchars($lease['lessee_cardid']));
		$templateProcessor->setValue('leaseaddress', htmlspecialchars($lease['address']));
		$templateProcessor->setValue('leasetelephone', htmlspecialchars($lease['lessee_telephone']));
		$templateProcessor->setValue('farmername3', htmlspecialchars($this->formatName($farm['farmname'],32)));
		$templateProcessor->setValue('zongdimeasure', htmlspecialchars($this->formatName(Lease::getListArea($lease['lease_area']),44)));
		$templateProcessor->setValue('begindate', htmlspecialchars($lease['begindate']));
		$templateProcessor->setValue('enddate', htmlspecialchars($lease['enddate']));
		$templateProcessor->setValue('zongdi', htmlspecialchars($this->formatName($farm['zongdi'],50)));
		// 		$templateProcessor->setValue('addresspic', htmlspecialchars(date('d',$farm['update_at'])));
	
		$filename = $farm['id'].'-'.date('Ymd',$lease['update_at']).'.docx';
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
		$alllen = $l;
		$len = intval(($alllen - $strlen)/2);
		$str1 = $this->str_prefix($name,$len,'_');
		$str = $this->str_suffix($str1,$len,'_');
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
}