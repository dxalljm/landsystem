<?php
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2018/3/3
 * Time: 11:28
 */

namespace frontend\controllers;
use app\models\Farms;
use app\models\ManagementArea;
use yii\helpers\Html;
use yii\web\Controller;
use app\models\Logs;

/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2018/3/3
 * Time: 10:55
 */

class ExceloutController extends Controller
{
    public $dbdata;
    public $classname;
    public $labels;

    public function actionExcelout($classname)
    {
//        $this->dbdata = $data;
//        $classname = $data->query->modelClass;
        $classname = 'app\\models\\'.$classname;
        $model = new $classname();
        $this->labels = $model->attributeLabels();
        return $this->renderAjax('selectlabels',[
            'labels' => $this->labels,
        ]);
    }

    public function actionSelectlabels()
    {
        $classname = 'app//models//'.$this->classname;
        $model = new $classname();
        $this->labels = $model->attributeLabels();
        return $this->renderAjax('selectlabels',[
            'labels' => $this->labels,
        ]);
    }


    public function actionTofile($classname,$where,$field)
    {
        set_time_limit ( 0 );
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        /** Include PHPExcel */
//        require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

        /** PHPExcel_IOFactory */
//        var_dump(unserialize($where));exit;
        $class = 'app\\models\\'.$classname;
        $model = new $class();
        $labels = $model->attributeLabels();
        $dbdata = $class::find()->where(json_decode($where,true))->all();
        $fieldArray = explode(',',$field);
        $objPHPExcel = new \PHPExcel();
        $words = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
            'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
            'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
        ];
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("PHPExcel Test Document")
            ->setSubject("PHPExcel Test Document")
            ->setDescription("Test document for PHPExcel, generated using PHP classes.")
            ->setKeywords("office PHPExcel php")
            ->setCategory("Test result file");
        $head = $objPHPExcel->setActiveSheetIndex(0);
        for ($i=0; $i<count($fieldArray);$i++) {
            $head->setCellValue($words[$i].'1', $labels[$fieldArray[$i]]);
        }
        $baseRow = 2;
        $row=0;
        foreach($dbdata as $r => $dataRow) {
            $row = $baseRow + $r;
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
            foreach ($fieldArray as $key => $value) {
                $objPHPExcel->getActiveSheet()->setCellValue($words[$key].$row, $dataRow[$value]);
            }

        }

        $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
//     	echo $this->render('insuranceprogress',['width'=>'100%']);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename = iconv("utf-8","gb2312//IGNORE",'xlsuploads/'.$classname.'.xls');
        $objWriter->save($filename);
        Logs::writeLogs('生成统计表');
        return $this->renderAjax('tofile',['filename'=>$filename]);
    }

    public function actionDatalist($classname,$where,$field)
    {
        $data = [];
        $class = 'app\\models\\'.$classname;
        $model = new $class();
        $labels = $model->attributeLabels();
        $dbdata = $class::find()->where(json_decode($where,true))->all();
        $fieldArray = explode(',',$field);
        $words = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
            'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
            'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
        ];
        foreach ($dbdata as $key => $value) {
            $tempData = [];
            foreach ($fieldArray as $k => $val) {
                switch ($val) {
                    case 'management_area':
                        $result = ManagementArea::find()->where(['id'=>$value[$val]])->one()['areaname'];
                        break;
                    case 'farms_id':
                        $result = Farms::find()->where(['id'=>$value[$val]])->one()['farmname'];
                        break;
                    default:
                        $result = $value[$val];
                }
                $tempData[$val] = $result;
            }
            $data[] = $tempData;
        }
//        var_dump($data);
//        var_dump($this->toDatafields($fieldArray));
//        var_dump($this->toColumns($fieldArray,$labels));
        return $this->renderAjax('datalist',[
            'data' => $data,
            'datafields' => $this->toDatafields($fieldArray),
            'columns' => $this->toColumns($fieldArray,$labels),
        ]);
    }

    public function toDatafields($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = ['name' => $value,'type' => 'string'];
        }
        return $result;
    }

    public function toColumns($array,$labels)
    {
//        var_dump($array);exit;
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = ['text' => $labels[$value],'datafield' => $value,'align' => 'center','cellsAlign' =>"center",'filtertype' => 'checkedlist'];
        }
        return $result;
    }
}