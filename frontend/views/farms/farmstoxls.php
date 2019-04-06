<?php
use app\models\Farms;
use app\models\Lease;
use app\models\Plantingstructure;
use app\models\Tables;
use app\models\Tablefields;
use app\models\ManagementArea;
use frontend\helpers\arraySearch;
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
$management_area = [0,1,2,3,4,5,6,7];
foreach ($management_area as $mid) {
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator('Maarten Balliauw')
							 ->setLastModifiedBy('Maarten Balliauw')
							 ->setTitle('PHPExcel Test Document')
							 ->setSubject('PHPExcel Test Document')
							 ->setDescription('Test document for PHPExcel, generated using PHP classes.')
							 ->setKeywords('office PHPExcel php')
							 ->setCategory('Test result file');

// Create the worksheet
$objPHPExcel->setActiveSheetIndex(0);
$excelSetValue = $objPHPExcel->getActiveSheet();

$excelSetValue->getColumnDimension('A')->setAutoSize(true);

$excelSetValue->setCellValue('A1','序号');
$word = 'A';
foreach ($ColumnNames as $column) {
	$str1=ord($word);
	$str1++;
	$word=chr($str1);
	$excelSetValue->getColumnDimension($word)->setAutoSize(true);
	$table_id = Tables::find()->where(['tablename'=>'farms'])->one()['id'];
	$value = Tablefields::find()->where(['tables_id'=>$table_id,'fields'=>$column])->one()['cfields'];
	$excelSetValue->setCellValue($word.'1',$value);

}
$i = 1;
if($mid !== 0)
	$farms = Farms::find()->where(['management_area'=>$mid])->all();
else 
	$farms = Farms::find()->all();
foreach ($farms as $farm) {
	$dataOne[] = $i++;
	foreach ($ColumnNames as $column) {
// 		var_dump($column);
		if($column == 'management_area') {
			
			$value = ManagementArea::find()->where(['id'=>$farm[$column]])->one()['areaname'];
		} elseif($column == 'zongdi') {
			$value = Lease::getListArea($farm[$column]);
		} else
			$value = $farm[$column];
// 		var_dump($value);
		$dataOne[] = $value; 
	}
	$dataArray[] = $dataOne;
	$dataOne = [];
// 	$excelSetValue->getStyle('K'.$i)->getAlignment()->setWrapText(true);
}
$arrayData = arraySearch::find($farms)->search();
$dataArray[] = ['合计','',$arrayData->count(),$arrayData->count('farmer_id'),'','','',$arrayData->sum('measure'),'',$arrayData->sum('notclear'),$arrayData->sum('notstate')];
$excelSetValue->fromArray($dataArray, NULL, 'A2');

// Set title row bold

$excelSetValue->getStyle('A1:'.$word.'1')->getFont()->setBold(true);

// Set autofilter

// Always include the complete filter range!
// Excel does support setting only the caption
// row, but that's not a best practise...
$excelSetValue->setAutoFilter($excelSetValue->calculateWorksheetDimension());

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
// $objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file

$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($mid.'.xlsx');
$objPHPExcel = '';
$objWriter = '';
}
