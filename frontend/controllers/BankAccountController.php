<?php

namespace frontend\controllers;

use app\models\Farmerinfo;
use app\models\Lease;
use app\models\Plantingstructurecheck;
use app\models\Subsidyratio;
use frontend\helpers\Pinyin;
use frontend\models\employeeSearch;
use Yii;
use app\models\BankAccount;
use frontend\models\bankaccountSearch;
use yii\debug\models\search\Log;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Farms;
use app\models\User;
use app\models\Huinong;
use app\models\ManagementArea;
/**
 * BankAccountController implements the CRUD actions for BankAccount model.
 */
class BankaccountController extends Controller
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
//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }
    
    /**
     * Lists all BankAccount models.
     * @return mixed
     */
    public function actionBankaccountindex()
    {
        $searchModel = new bankaccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('进入银账号管理页面');
        return $this->render('bankaccountindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBankaccountcacle($id)
    {
        $model = BankAccount::findOne($id);
        $model->state = 2;
        $save = $model->save();
        echo json_encode(['state'=>$save]);
    }

    public function actionBankto()
    {
        $data = BankAccount::find()->all();
        foreach ($data as $value) {
            $model = BankAccount::findOne($value['id']);
            $lease = Lease::find()->where(['lessee_cardid'=>$model->cardid,'farms_id'=>$model->farms_id,'year'=>User::getYear()])->one();
            if($lease) {
                $model->lease_id = $lease['id'];
            } else {
                $model->lease_id = 0;
            }
            $model->save();
        }
        echo 'fff';
    }

    public function actionScancardid($cardid)
    {
        $bank = BankAccount::find()->where(['cardid'=>$cardid])->one();
        $state = false;
        $number = '';
        if($bank) {
            $state = true;
            $number = $bank['accountnumber'];
        }
        echo json_encode(['state'=>$state,'number'=>$number]);
    }

    public function actionSetaccountnumber($farms_id,$accountnumber)
    {
        $farm = Farms::findOne($farms_id);
        $model = BankAccount::find()->where(['farms_id'=>$farms_id,'lease_id'=>0])->one();
        if($model) {
            $model->accountnumber = $accountnumber;
            $save = $model->save();
            Logs::writeLogs('更新' . $farm['farmername'] . '银行帐号', $model);
        } else {
            $model = new BankAccount();
            $model->farms_id = $farms_id;
            $model->bank = '大兴按岭农村商业银行';
            $model->accountnumber = $accountnumber;
            $model->cardid = $farm['cardid'];
            $model->lessee = '';
            $model->create_at = time();
            $model->update_at = $model->create_at;
//                if (BankAccount::scanCard($cardid)) {
//                    $model->state = $bankstate['state'];
//                    $model->modfiyname = $bankstate['modfiyname'];
//                    $model->modfiytime = $bankstate['modfiytime'];
//                } else {
            $model->state = 1;
//                }
            $model->management_area = $farm['management_area'];
            $model->farmername = $farm['farmername'];
            $model->farmerpinyin = $farm['farmerpinyin'];
            $model->farmname = $farm['farmname'];
            $model->farmpinyin = $farm['pinyin'];
            $model->lesseepinyin = '';
            $model->contractnumber = $farm['contractnumber'];
            $model->contractarea = $farm['contractarea'];
            $model->farmstate = $farm['state'];
            $model->lease_id = 0;
            Logs::writeLogs('新增' . $farm['farmername'] . '银行帐号', $model);
            $save = $model->save();
        }
        echo json_encode(['state'=>$save,'number'=>$accountnumber]);
    }


    public function actionBankaccountsave($lessee,$cardid,$bank,$accountnumber,$farms_id,$id)
    {
        $farm = Farms::findOne($farms_id);
        $banks = BankAccount::find()->where(['cardid'=>$cardid])->all();
//        $bankone = BankAccount::find()->where(['cardid'=>$cardid,'farms_id'=>$farms_id])->one();
        $bankstate = BankAccount::scanCard($cardid);
        if($id) {
            $model = BankAccount::findOne($id);
            $oldnumber = $model->accountnumber;
            $model->cardid = $cardid;
            $model->lessee = $lessee;
            $model->bank = $bank;
            $model->accountnumber = $accountnumber;
            $model->update_at = time();
            if ($bankstate) {
                $model->state = $bankstate['state'];
                $model->modfiyname = $bankstate['modfiyname'];
                $model->modfiytime = $bankstate['modfiytime'];
            }
            $save = $model->save();
            if($save) {
                foreach ($banks as $b) {
                    if ($b['accountnumber'] == $oldnumber) {
                        $model = BankAccount::findOne($b['id']);
    //                    $model->cardid = $cardid;
    //                    $model->lessee = $lessee;
    //                    $model->bank = $bank;
                        $model->accountnumber = $accountnumber;
                        $model->update_at = time();
                        if ($bankstate) {
                            $model->state = $bankstate['state'];
                            $model->modfiyname = $bankstate['modfiyname'];
                            $model->modfiytime = $bankstate['modfiytime'];
                        }
                        $save = $model->save();
                        Logs::writeLogs('更新' . $farm['farmername'] . '银行账号', $model);
                    }
                }
            }
            Logs::writeLogs('更新'.$farm['farmername'].'银行账号',$model);
        } else {
                $model = new BankAccount();
                $model->farms_id = $farms_id;
                $model->bank = $bank;
                $model->accountnumber = $accountnumber;
                $model->cardid = $cardid;
                $model->lessee = $lessee;
                $model->create_at = time();
                $model->update_at = $model->create_at;
                if (BankAccount::scanCard($cardid)) {
                    $model->state = $bankstate['state'];
                    $model->modfiyname = $bankstate['modfiyname'];
                    $model->modfiytime = $bankstate['modfiytime'];
                } else {
                    $model->state = 2;
                }
                $model->management_area = $farm['management_area'];
                $model->farmername = $farm['farmername'];
                $model->farmerpinyin = $farm['farmerpinyin'];
                $model->farmname = $farm['farmname'];
                $model->farmpinyin = $farm['pinyin'];
                $model->lesseepinyin = Pinyin::encode($lessee);
                $model->contractnumber = $farm['contractnumber'];
                $model->contractarea = $farm['contractarea'];
                $model->farmstate = $farm['state'];
                $lease = Lease::find()->where(['lessee_cardid' => $cardid, 'farms_id' => $farms_id,'year'=>User::getYear()])->one();
                if ($lease) {
                    $model->lease_id = $lease['id'];
                } else {
                    $model->lease_id = 0;
                }
                $save = $model->save();
                Logs::writeLogs('创建' . $farm['farmername'] . '银行账号', $model);

        }
        if(BankAccount::scanCard($cardid)) {
            $lessees = Lease::getLesees($farms_id);
            foreach ($lessees as $lessee) {
                $check = Plantingstructurecheck::find()->where(['farms_id' => $farms_id,'lease_id'=>$lessee['id'],'year'=>User::getYear()])->all();
                if($check) {
                    foreach ($check as $val) {
                        $cm = Plantingstructurecheck::findOne($val['id']);
                        $cm->bankstate = 1;
                        $cm->isbank = 1;
                        $cm->save();
                    }
                }
            }
        } else {
            $lessees = Lease::getLesees($farms_id);
            foreach ($lessees as $lessee) {
                $check = Plantingstructurecheck::find()->where(['farms_id' => $farms_id,'lease_id'=>$lessee['id'],'year'=>User::getYear()])->all();
                if($check) {
                    foreach ($check as $val) {
                        $cm = Plantingstructurecheck::findOne($val['id']);
                        $cm->isbank = 1;
                        $cm->save();
                    }
                }
            }
        }
        echo json_encode(['state'=>$save]);
    }

    public function actionBankaccountmodfiy($id,$cardid,$accountnumber)
    {
        $bankone = BankAccount::findOne($id);
        $oldcardid = $bankone->cardid;
        if($bankone->cardid !== $cardid) {
            $bankone->cardid = $cardid;
        }
        $bankone->accountnumber = $accountnumber;
        $save = $bankone->save();
        if($save) {
            $banks  = BankAccount::find()->where(['cardid'=>$oldcardid])->all();
            foreach ($banks as $bank) {
                $model = BankAccount::findOne($bank['id']);
                if($bank['cardid'] !== $cardid) {
                    $model->cardid = $cardid;
                }
                $model->accountnumber = $accountnumber;
                $model->save();
            }
        }
        echo json_encode(['state'=>$save]);
    }

    public function actionSetstate($id,$cardid,$accountnumber)
    {
        $bank = BankAccount::findOne($id);
        $oldcardid = $bank->cardid;
        if($bank->cardid !== $cardid) {
            $bank->cardid = $cardid;
            $lease = Lease::findOne($bank->lease_id);
            $lease->lessee_cardid = $cardid;
            $lease->save();
        }
        $oldnumber = $bank->accountnumber;
        $bank->state = 1;
        $bank->accountnumber = $accountnumber;
        $bank->modfiyname = Yii::$app->user->identity->realname;
        $bank->modfiytime = time();
        $save = $bank->save();
        $banks = BankAccount::find()->where(['cardid'=>$oldcardid])->all();
        foreach ($banks as $bank) {
            $model = BankAccount::findOne($bank['id']);
            if($bank['accountnumber'] == $oldnumber) {
                $model->accountnumber = $accountnumber;
                $model->state = 1;
                $model->save();
            }
            if($bank['cardid'] !==  $cardid) {
                $model->cardid = $cardid;
                $model->save();
            }
        }
        if($save) {
            $ps = Plantingstructurecheck::find()->where(['farms_id'=>$bank->farms_id,'year'=>User::getYear()])->all();
            foreach ($ps as $p) {
                $model = Plantingstructurecheck::findOne($p['id']);
                $model->bankstate = 1;
                $model->save();
            }
        }
        echo json_encode($save);
    }

    public function actionGetbankinfo($id)
    {
        $bank = BankAccount::findOne($id);
        $result = [
            'id' => $bank['id'],
            'farms_id' => $bank['farms_id'],
            'lessee' => $bank['lessee'],
            'cardid' => $bank['cardid'],
            'bank' => $bank['bank'],
            'lease_id' => $bank['lease_id'],
            'accountnumber' => $bank['accountnumber'],
        ];
        echo json_encode($result);
    }

    public function actionGetone($cardid)
    {
        $model = BankAccount::find()->where(['cardid'=>$cardid])->one();
        if($model) {
            $state = true;
        } else {
            $state = false;
        }
        echo json_encode(['state'=>$state,'accountnumber'=>$model['accountnumber']]);
    }
    
    public function actionBankaccountgetbank($id)
    {
        $model = BankAccount::findOne($id);
        echo json_encode(['bank'=>$model->bank,'accountnumber'=>$model->accountnumber,'cardid'=>$model->cardid,'lessee'=>$model->lessee]);
    }
    public function actionBankaccountdelbank($id,$farms_id)
    {
        $model = BankAccount::findOne($id);
        $farm = Farms::findOne($farms_id);
        Logs::writeLogs('删除'.$farm['farmername'].'银行账号',$model);
        $del = $model->delete();
        if($del) {
            $ps = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
            foreach ($ps as $p) {
                $p = Plantingstructurecheck::findOne($p['id']);
                $p->isbank = '';
                $p->bankstate = '';
                $p->save();
            }
        }
        echo json_encode(['state'=>$del]);
    }
    /**
     * Displays a single Bankaccount model.
     * @param integer $id
     * @return mixed
     */
    public function actionBankaccountview($id)
    {
    	Logs::writeLog('银行账号查看操作',$id);
        return $this->render('bankaccountview', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionToxls($where)
    {
        Logs::writeLogs('种植结构汇总表');
        $f = 0;
        $l = 0;
        $whereArray = json_decode($where,true);
        $result = [];

        $data = BankAccount::find()->where($whereArray)->all();
        $i = 0;
        $farmsAllid = [];
        foreach ($data as $key => $val) {

            $ddarea = 0;
            $ymarea = 0;
            $content = '';
            $plantid = Huinong::getPlant('id');
            //获取法人种植信息
            $farm = Farms::findOne($val['farms_id']);
            if($val['lease_id'] == 0) {
                $accountnumber = $val['accountnumber'];
                $cardid = $val['cardid'];
                $lessee = $val['farmername'];
                $telephone = $farm['telephone'];
            } else {
                $lease = Lease::findOne($val['lease_id']);
                $accountnumber = $val['accountnumber'];
                $cardid = $lease['lessee_cardid'];
                $lessee = $lease['lessee'];
                $telephone = $lease['lessee_telephone'];
            }

            $f++;
            $i++;

            foreach ($plantid as $id) {
                $sub = Subsidyratio::getSubsidyratio($id,$val['farms_id']);
                switch ($id) {
                    case 6:
                        if($sub) {
                            if($val['lease_id'] == 0) {
                                $area = sprintf('%.2f',Plantingstructurecheck::find()->where(['farms_id' => $val['farms_id'], 'lease_id' => $sub['lease_id'],'plant_id'=>$id,'year'=>User::getYear()])->sum('area'));
                                $farmerp = (float)$sub['farmer']/100;
                                $lesseep = (float)$sub['lessee']/100;
                                if(bccomp($farmerp,1) == 0) {
                                    $ddarea = bcmul($area,$farmerp,2);
                                }
                                if(bccomp($lesseep,1) == 0) {
                                    $ddarea = bcmul($area,$lesseep,2);
                                }
                                if(bccomp($farmerp,1) == 1 or bccomp($farmerp,1) == -1) {
                                    $ddarea = bcmul($area,$farmerp,2);
                                }
                            } else {
//                                var_dump('farms_id ==='.$val['farms_id']);
//                                var_dump('lease_id === '.$val['lease_id']);
                                $area = sprintf('%.2f',Plantingstructurecheck::find()->where(['farms_id' => $val['farms_id'], 'lease_id' => $val['lease_id'],'plant_id'=>$id,'year'=>User::getYear()])->sum('area'));
//                                var_dump($area);
                                $farmerp = (float)$sub['farmer']/100;
                                $lesseep = (float)$sub['lessee']/100;
                                if(bccomp($farmerp,1) == 0) {
                                    $ddarea = bcmul($area,$farmerp,2);
                                }
                                if(bccomp($lesseep,1) == 0) {
                                    $ddarea = bcmul($area,$lesseep,2);
                                }
                                if(bccomp($farmerp,1) == 1 or bccomp($farmerp,1) == -1) {
                                    $ddarea = bcmul($area,$lesseep,2);
                                }
//                                var_dump('ddarea=='.$ddarea);
                            }
                        } else {
                            $ddarea = sprintf('%.2f',Plantingstructurecheck::find()->where(['farms_id' => $val['farms_id'], 'lease_id' => $val['lease_id'],'plant_id'=>$id,'year'=>User::getYear()])->sum('area'));
                        }
                    break;
                    case 3:
                        if($sub) {
                            if($val['lease_id'] == 0) {
                                $area = sprintf('%.2f',Plantingstructurecheck::find()->where(['farms_id' => $val['farms_id'], 'lease_id' => $sub['lease_id'],'plant_id'=>$id,'year'=>User::getYear()])->sum('area'));
//                                var_dump('farms_id==='.$val['farms_id']);
//                                var_dump('lease_id==='.$val['lease_id']);
//                                var_dump($area);
                                $farmerp = (float)$sub['farmer']/100;
                                $lesseep = (float)$sub['lessee']/100;
                                if(bccomp($farmerp,1) == 0) {
                                    $ymarea = bcmul($area,$farmerp,2);
                                }
                                if(bccomp($lesseep,1) == 0) {
                                    $ymarea = bcmul($area,$lesseep,2);
                                }
                                if(bccomp($farmerp,1) == 1 or bccomp($farmerp,1) == -1) {
                                    $ymarea = bcmul($area,$farmerp,2);
                                }
                            } else {
                                $area = sprintf('%.2f',Plantingstructurecheck::find()->where(['farms_id' => $val['farms_id'], 'lease_id' => $val['lease_id'],'plant_id'=>$id,'year'=>User::getYear()])->sum('area'));
                                $farmerp = (float)$sub['farmer']/100;
                                $lesseep = (float)$sub['lessee']/100;
                                if(bccomp($farmerp,1) == 0) {
                                    $ymarea = bcmul($area,$farmerp,2);
                                }
                                if(bccomp($lesseep,1) == 0) {
                                    $ymarea = bcmul($area,$lesseep,2);
                                }
                                if(bccomp($farmerp,1) == 1 or bccomp($farmerp,1) == -1) {
                                    $ymarea = bcmul($area,$lesseep,2);
                                }
                            }
                        } else {
                            $ymarea = sprintf('%.2f',Plantingstructurecheck::find()->where(['farms_id' => $val['farms_id'], 'lease_id' => $val['lease_id'],'plant_id'=>$id,'year'=>User::getYear()])->sum('area'));
                        }
                        break;
                }
            }

//                $contractareaSum += $farm['contractarea'];
//                $plantAllSum += $plantsum;
            $result[] = [
                'row' => $i,
                'management_area' => ManagementArea::getAreaname($val['management_area']),
                'lease' => $lessee,
                'cardid' => $cardid,
                'accountnumber' => $accountnumber,
                'telephone' => $telephone,
                'ddarea' => $ddarea,
                'ymarea' => $ymarea,
                'content' => '',
            ];

        }
//        var_dump($farmsAllid);
//        var_dump(array_unique($farmsAllid));exit;
//        $contractareaSum = Farms::find()->where(['id'=>array_unique($farmsAllid)])->sum("contractarea");
//        $plantAllSum = Plantingstructurecheck::find()->where(['farms_id'=>array_unique($farmsAllid)])->sum('area');
//        var_dump($result);exit;
        return $this->render('toxls', [
            'result' => $result,
            'areaname' => Farms::getManagementArea()['areaname'],
//			'contractareaSum' => sprintf('%2.f',$sum),
//			'testdata' => [$f,$l],
        ]);
    }

    /**
     * Creates a new BankAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBankaccountcreate()
    {
        $model = new BankAccount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$newAttr = $model->attributes;
        	Logs::writeLog('创建银行账号',$model->id,'',$newAttr);
            return $this->redirect(['bankaccountview', 'id' => $model->id]);
        } else {
            return $this->render('bankaccountcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bankaccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBankaccountupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('更新银行账号',$id,$old,$new);
            return $this->redirect(['bankaccountview', 'id' => $model->id]);
        } else {
            return $this->render('bankaccountupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bankaccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBankaccountdelete($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
		$model->delete();
		Logs::writeLog('删除银行账号',$id,$old);
        return $this->redirect(['bankaccountindex']);
    }

    /**
     * Finds the BankAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BankAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
