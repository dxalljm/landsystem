<?php

namespace frontend\controllers;

use app\models\Farmer;
use app\models\Logs;
use app\models\Machine;
use app\models\Machineoffarm;
use app\models\Machinescanning;
use app\models\Machinesubsidy;
use app\models\Machinetrial;
use app\models\Machineapplymachine;
use Yii;
use app\models\Machineapply;
use frontend\models\MachineapplySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Farmerinfo;
use app\models\ManagementArea;
use app\models\User;

/**
 * MachineapplyController implements the CRUD actions for Machineapply model.
 */
class MachineapplyController extends Controller
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
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }
    /**
     * Lists all Machineapply models.
     * @return mixed
     */
    public function actionMachineapplyindex()
    {
        $searchModel = new MachineapplySearch();
        $params = Yii::$app->request->queryParams;
        $params['MachineapplySearch']['year'] = User::getYear();
        $params['MachineapplySearch']['dckstate'] = 1;
        $params['MachineapplySearch']['state'] = 1;
        $dataProvider = $searchModel->search($params);
        Logs::writeLogs('农机补贴确认列表');
        return $this->render('machineapplyindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMachineapplyfinished($id)
    {
        $model = Machineapply::findOne($id);
        $model->scanfinished = 1;
        if($model->save()) {
            return $this->redirect(['machineapply/machineapplyindex']);
        }

    }

    /**
     * Displays a single Machineapply model.
     * @param integer $id
     * @return mixed
     */
    public function actionMachineapplyview($machineoffarm_id)
    {
        $machineoffarm = Machineapplymachine::findOne($machineoffarm_id);
        $model = Machineapply::find()->where(['cardid'=>$machineoffarm->cardid,'year'=>User::getYear()])->one();
        $machine = Machine::findOne($machineoffarm->machine_id);
        Logs::writeLogs('查看农机补贴信息',$machine);
        return $this->render('machineapplyview', [
            'model' => $model,
            'farm' => Farms::findOne($model->farms_id),
            'farmer' => Farmerinfo::find()->where(['cardid' => $model->cardid])->one(),
            'machine' => Machine::findOne($machineoffarm->machine_id),
            'subsidymoney' => $model->subsidymoney
        ]);
    }

    public function deleteDirFiles($path)
    {
        if(file_exists('temp/temp.zip')) {
            unlink('temp/temp.zip');
        }
        $dir_arr = scandir($path);
        foreach($dir_arr as $key => $val) {
            if($val == '.' or $val == '..') {}
            else {
                unlink($path.'/'.$val);
            }
        }
        $newdir = scandir($path);
        if(count($newdir) <=2) {
            return true;
        } else {
            return false;
        }
    }

    public function actionMachineapplyexplode($id)
    {
        set_time_limit(0);//设置PHP超时时间
        $state = false;
        $model = $this->findModel($id);
        Logs::writeLogs('下载农机相关电子材料',$model);
        $dirState = $this->deleteDirFiles('imgTemp');
        if($dirState) {

            $imagesURLArray = [];
//        $head = '';
            $head = 'http://192.168.1.10/';
            $farm = Farms::find()->where(['id' => $model->farms_id])->one();
            $farmerinfo = Farmerinfo::find()->where(['cardid' => $model->cardid])->one();
            $imagesURLArray['photo'] = $head . $farmerinfo['photo'];
            $content = file_get_contents($head . $farmerinfo['photo']);
            file_put_contents('imgTemp/近期照片.jpg', $content);

            $imagesURLArray['cardpic'] = $head . $farmerinfo['cardpic'];
            $content = file_get_contents($head . $farmerinfo['cardpic']);
            file_put_contents('imgTemp/身份证正面.jpg', $content);

            $imagesURLArray['cardpicback'] = $head . $farmerinfo['cardpicback'];
            $content = file_get_contents($head . $farmerinfo['cardpicback']);
            file_put_contents('imgTemp/身份证反面.jpg', $content);

            $mtr = Machinescanning::find()->where(['cardid' => $model->cardid,'machineapplymachine_id'=>$model->machineoffarm_id])->all();
            foreach ($mtr as $key => $m) {
//                $imagesURLArray[$key] = $head . $m['scanimage'];
                $save_to = 'imgTemp/相关材料' . $key . '.jpg';
                $content = file_get_contents($head . $m['scanimage']);
                file_put_contents($save_to, $content);
            }
//        var_dump($farm['farmername']);
            $filename = iconv('utf-8', 'gb2312//IGNORE', $farm['farmername']);
//        var_dump($filename);
//        var_dump($imagesURLArray);
//            foreach ($imagesURLArray as $key => $val) {
//                switch ($key) {
//                    case 'photo':
//                        $save_to = 'imgTemp/近期照片.jpg';
//                        $content = file_get_contents($val);
//                        file_put_contents($save_to, $content);
////                        var_dump($val);exit;
//                        break;
//                    case 'cardpic':
//                        $save_to = 'imgTemp/身份证正面.jpg';
//                        $content = file_get_contents($val);
//                        file_put_contents($save_to, $content);
//                        break;
//                    case 'cardpicback':
//                        $save_to = 'imgTemp/身份证反面.jpg';
//                        $content = file_get_contents($val);
//                        file_put_contents($save_to, $content);
//                        break;
//                    default:
////                        var_dump($key);
//                        $save_to = 'imgTemp/相关材料' . $key+1 . '.jpg';
//                        $content = file_get_contents($val);
//                        file_put_contents($save_to, $content);
////                        var_dump($val);exit;
//                }
////                $content = file_get_contents($val);
//                file_put_contents($save_to, $content);
//            }
            system('zip -r temp/temp.zip imgTemp');
            ob_clean();
//        flush();
            $res = Yii::$app->response;
            $state = $res->sendFile('temp/temp.zip', $filename . '.zip');
        }
        echo json_encode(['state'=>$state]);
//        header("Cache-Control: public");
//        header("Content-Description: File Transfer");
//        header('Content-disposition: attachment; filename='.$filename.'.zip'); //文件名
//        header("Content-Type: application/zip"); //zip格式的
//        header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
//        header('Content-Length: '. filesize('temp/temp.zip')); //告诉浏览器，文件大小
//        ob_clean();
//        flush();
//        readfile($filename.'.zip');
//        return $this->redirect(['machineapply/machineapplyindex']);
//        system('rm -rf imgTemp/');
    }

    public function actionMachineapplysearch($tab,$begindate,$enddate)
    {
        if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
            return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
                'tab' => $_GET['tab'],
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
//    				$_GET['tab'].'Search' => ['management_area'=>$_GET['management_area']],
            ]);
        }
        $searchModel = new MachineapplySearch();
        $_GET['MachineapplySearch']['state'] = 1;
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchSearch ( $_GET );
        Logs::writeLogs('综合查询-农机补贴');
        return $this->render('machineapplysearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }
    
    public function actionMachineapply($farms_id)
    {
        $farm = Farms::findOne($farms_id);
        $macheapply = Machineapply::find()->where(['cardid'=>$farm['cardid']])->one();
        $farmerinfo = Farmerinfo::find()->where(['cardid'=>$farm['cardid']])->one();
        if($macheapply) {
            $model = new Machineapply();
        } else {
            $model = Machineapply::findOne($macheapply['id']);
        }
//        var_dump($model);exit;
        if ($model->load(Yii::$app->request->post())) {
            $model->year = User::getYear();
            $model->save();
            return $this->redirect(['machineapplysend']);
        } else {
            return $this->render ( 'machineapply', [
                'farm' => $farm,
                'farmer' => $farmerinfo,
                'model' => $model,
            ] );
        }
    }

    public function actionMachineprint($farms_id)
    {
        $farm = Farms::findOne($farms_id);
        $farmerinfo = Farmerinfo::find()->where(['cardid'=>$farm['cardid']])->one();
        Logs::writeLogs('打印农机补贴申请表');
        return $this->render ( 'machineprint', [
            'farm' => $farm,
            'farmer' => $farmerinfo
        ] );
    }
    /**
     * Creates a new Machineapply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMachineapplycreate($machineoffarm_id)
    {
        $machineoffarm = Machineoffarm::findOne($machineoffarm_id);
        $model = new Machineapply();
        $farm = Farms::findOne($machineoffarm->farms_id);
//        $macheapply = Machineapply::find()->where(['cardid'=>$farm['cardid'],'year'=>User::getYear()])->orderBy('id DESC')->one();
//        if($macheapply) {
//            if($macheapply->dckstate == 1 or $macheapply->state == 1) {
//                return $this->redirect(['error/error', 'msg' => '对不起,此法人已经在其他管理区申请了补贴。']);
//            } else {
//                $macheapply->state = -1;
//                $macheapply->save();
//            }
//        }
        $farmerinfo = Farmerinfo::find()->where(['cardid'=>$farm['cardid']])->one();
//        if($macheapply) {
//            $model = Machineapply::findOne($macheapply['id']);
//        }
        if ($model->load(Yii::$app->request->post())) {
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->farms_id = $farm->id;
            $model->age = Farms::getAge($farm['cardid']);
            $model->sex = $farmerinfo['gender'];
//            $model->domicile = $farmerinfo['domicile'];
            $model->management_area = $farm['management_area'];
            $model->cardid = $farm['cardid'];
//            $model->telephone = $farm['telephone'];
            $model->farmerpinyin = $farm['farmerpinyin'];
            $model->farmername = $farm['farmername'];
            $model->state = 0;
            $model->dckstate = 1;
            $model->year = User::getYear();
            $model->machineoffarm_id = $machineoffarm_id;
            $model->machinetype_id = $machineoffarm['machinetype_id'];
            $model->machineoffarmold_id = $machineoffarm_id;
            $model->scanfinished = 0;
            $model->save();
            Logs::writeLogs('提交农机补贴申请表',$model);
            $machinetrial = new Machinetrial();
            $machinetrial->farms_id = $farm->id;
            $machinetrial->management_area = $farm->management_area;
            $machinetrial->apply_id = $model->id;
            $machinetrial->isoneself = $_POST['isoneself'];
//            $machinetrial->iscooperative = $_POST['iscooperative'];
            $machinetrial->ismaterial = $_POST['ismaterial'];
            $machinetrial->create_at = time();
            $machinetrial->update_at = $machinetrial->create_at;
//            $machinetrial->year = User::getYear();
            $machinetrial->save();

            if($farmerinfo['domicile'] !== $model->domicile) {
                $farmerinfo->domicile = $model->domicile;
                $farmerinfo->save();
            }
            if($farm['telephone'] !== $model->telephone) {
                $farm->telephone = $model->telephone;
                $farm->save();
            }
            return $this->redirect(['machineoffarm/machineoffarmindex','farms_id'=>$farm['id']]);
        } else {
            return $this->render('machineapplycreate', [
                'model' => $model,
                'farm' => $farm,
                'machine' => $machineoffarm,
                'farmer' => $farmerinfo,
            ]);
        }
    }

    public function dirArray($array)
    {
        $result = [];
        foreach($array as $value) {
//			print_r($value->filename);
            $result[] = $value->filename;
        }
        return $result;
    }

    public function newDir($array)
    {
        $result = [];
        $newarr = explode('/',$array);
//        $result[] = $newarr[0];
        $result[] = 'machine_image';
        for($i=1;$i<count($newarr);$i++) {
            $result[] = $newarr[$i];
        }
        return $result;
    }

    public function actionMachineapplycomparison($subsidy_id,$apply_id,$farms_id)
    {
        $model = Machineapply::findOne($apply_id);
        $subsidy = Machinesubsidy::findOne($subsidy_id);
        $machineoffarm = Machineoffarm::findOne($model->machineoffarm_id);
        if(isset($_POST['Machineapply']['subsidymoney'])) {
            $model->update_at = time();
            $model->subsidymoney = $subsidy->subsidymoney;
            $model->state = 1;
            $model->machinetype_id = $machineoffarm->machinetype_id;
            $model->machineoffarm_id = Machineapplymachine::newMachine($model->machineoffarm_id);
            $model->save();
//            $ftp = Yii::$app->ftp;
//            $ftp2 = Yii::$app->ftp;
//            $farmerinfo = Farmerinfo::find()->where(['cardid'=>$model->cardid])->one();
//            $files = ['photo'=>$farmerinfo['photo'],'cardpic'=>$farmerinfo['cardpic'],'cardpicback'=>$farmerinfo['cardpicback']];
//            var_dump($files);exit;
//            $farm = Farms::findOne($model->farms_id);
//            $strc = iconv("UTF-8","gbk//TRANSLIT", $farm['farmname'].'-'.$farm['farmername']);
//            $stra = iconv("UTF-8","gbk//TRANSLIT", '身份证扫描件');
//            $management_area = iconv("UTF-8","gbk//TRANSLIT", ManagementArea::getAreanameOne($farm['management_area']));
//            $filename = time().'.jpg';
//            $path = 'photo_gallery/machine_image/'.$management_area.'/'.$strc.'/'.$stra;
//            $filepath = $path.'/'.$filename;
//            $uploadpath = 'machine_image/'.$management_area.'/'.$strc.'/'.$stra;
//            $uploadfile = $uploadpath.'/'.$filename;

//            foreach($files as $key => $filepath) {
//                $ftpfile = explode('/',$filepath);
//                $fcount = count($ftpfile) - 1;
//                $ftpfilename = $ftpfile[$fcount];
//                unset($ftpfile[0]);
//                unset($ftpfile[$fcount]);
//                foreach($ftpfile as $fp) {
//                    $fp = iconv("UTF-8","gbk//TRANSLIT", $fp);
//                    $ftp->chdir($fp);
//                    var_dump($ftp->ls());
//                }
//                var_dump($ftpfilename);
//                exit;
//                var_dump(iconv("UTF-8","gbk//TRANSLIT", implode('/',$ftpfile)));exit;
//                if($key == 'cardpic') {
//                    var_dump($ftp->ls());exit;
//                    var_dump(iconv("UTF-8","gbk//TRANSLIT", implode('/',$ftpfile)));exit;
//                    var_dump($ftp->get(iconv("UTF-8","gbk//TRANSLIT", implode('/',$ftpfile))));exit;
//                }
//                $getfile = $ftp->get(iconv("UTF-8","gbk//TRANSLIT", implode('/',$ftpfile)));
////                var_dump($getfile);exit;
////                var_dump($filepath);
//                $uploadpath = $this->newDir($filepath);
////                var_dump($uploadpath);exit;
//                $count = count($uploadpath) - 1;
//                $file = $uploadpath[$count];
////                unset($uploadpath[0]);
//                unset($uploadpath[$count]);
//                $gen = $this->dirArray($ftp2->ls());
//                foreach ($uploadpath as $value) {
//                    $value = iconv("UTF-8","gbk//TRANSLIT", $value);
//                    if (in_array($value, $gen)) {
//                        $gen = $this->dirArray($ftp2->ls($value));
//                        $ftp2->chdir($value);
//                    } else {
////                        var_dump($value);exit;
//                        $ftp2->mkdir($value);
//                        $ftp2->chdir($value);
//                    }
//                }
//                $newfile =
//                    $ftp2->put($getfile, $ftpfilename);
//                $ftp->chdir('/');
//                $ftp2->chdir('/');
//            }
            Logs::writeLogs('确认农机补贴金额',$model);
            return $this->redirect(['machineapplyprint','id'=>$apply_id,'farms_id'=>$farms_id]);
        } else {
            return $this->render('machineapplycomparison', [
                'model' => $model,
                'farm' => Farms::findOne($model->farms_id),
                'farmer' => Farmerinfo::find()->where(['cardid' => $model->cardid])->one(),
                'machine' => Machine::findOne($machineoffarm->machine_id),
                'subsidymoney' => $subsidy->subsidymoney,
            ]);
        }
    }

    public function actionMachineapplycacle($id)
    {
        $model = $this->findModel($id);
        $model->state = -1;
//        $model->scanfinished = 0
        $model->save();
        Logs::writeLogs('撤消农机补贴',$model);
//        $machienoffarm = Machineoffarm::find()->where(['id'=>$model->machineoffarm_id])->one();
//        $machienoffarm->delete();
        $machinetr = Machinetrial::find()->where(['farms_id'=>$model->farms_id])->all();
        foreach($machinetr as $m) {
            $m = Machinetrial::findOne($m['id']);
            $m->delete();
        }
        $applymachine = Machineapplymachine::findOne($model->machineoffarm_id);
        if($applymachine) {
            $applymachine->delete();
        }
        return $this->redirect(['machineapplyindex']);
    }

    public function actionMachineapplydckcacle($id)
    {
        $model = $this->findModel($id);
        $model->state = -1;
        $model->save();
        Logs::writeLogs('撤消农机补贴',$model);
//        $machienoffarm = Machineoffarm::find()->where(['id'=>$model->machineoffarm_id])->one();
//        $machienoffarm->delete();
        $machinetr = Machinetrial::find()->where(['farms_id'=>$model->farms_id])->all();
        foreach($machinetr as $m) {
            $m = Machinetrial::findOne($m['id']);
            $m->delete();
        }
        $applymachine = Machineapplymachine::findOne($model->machineoffarm_id);
        if($applymachine) {
            $applymachine->delete();
        }
        return $this->redirect(['machineoffarm/machineoffarmindex','farms_id'=>$model->farms_id]);
    }

    public function actionMachineapplyprint($id,$farms_id)
    {
        $model = $this->findModel($id);
        $machineoffarm = Machineapplymachine::findOne($model->machineoffarm_id);
        $machine = Machine::findOne($machineoffarm['machine_id']);
        Logs::writeLogs('农机补贴申请表打印页面');
        return $this->render('machineapplyprint',[
           'model' => $model,
            'machine' => $machine,
        ]);
    }

    public function actionMachineapplysend($farms_id)
    {
        return $this->render('machineapplysend',[
            'farms_id'=>$farms_id
        ]);
    }
    /**
     * Updates an existing Machineapply model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachineapplyupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machineapplyview', 'id' => $model->id]);
        } else {
            return $this->render('machineapplyupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Machineapply model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachineapplydelete($id)
    {
        $model = $this->findModel($id);
        $machienoffarm = Machineoffarm::find()->where(['cardid'=>$model->cardid])->all();
        foreach($machienoffarm as $mf) {
            $mm = Machineoffarm::findOne($mf['id']);
            $mm->delete();
        }
        $model->delete();

        Logs::writeLogs('删除农机补贴',$model);
        return $this->redirect(['machineapplyindex']);
    }

    /**
     * Finds the Machineapply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Machineapply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Machineapply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
