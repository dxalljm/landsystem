<?php
namespace frontend\controllers;

use Yii;
use app\models\Farms;
use yii\web\Controller;

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
		if(!file_exists('contract_file/'.$filename))
			$templateProcessor->saveAs('contract_file/'.$filename);

		
		return $this->render('printcontract', [
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