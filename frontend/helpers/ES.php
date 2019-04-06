<?php
namespace frontend\helpers;
use app\models\Machineapply;
use app\models\Plantingstructurecheck;
use app\models\Tables;
use app\models\Farms;
use app\models\Cache;
use app\models\Collection;
use app\models\Yields;
use app\models\Insurance;
use app\models\Loan;
use app\models\Plantinputproduct;
use app\models\Projectapplication;
use app\models\Huinonggrant;
use app\models\User;
use app\models\Fireprevention;
use app\models\Breedinfo;
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2018/4/2
 * Time: 19:08
 */
class ES extends Echarts
{
    //柱形图,options=['title'=>'测试','tooltip'=>[],'legend'=>['销量'],'xAxis'=>["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"],'yAxis'=>[],'series'=>[5, 20, 36, 10, 10, 20]]
    public static function bar(Array $array = NULL)
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'bar';
        return $obj;
    }
    public static function bar2(Array $array = NULL)
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'bar2';
        return $obj;
    }
    public static function barUnit(Array $array = NULL)
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'barUnit';
        return $obj;
    }
    //温度计图,options=['title'=>'测试','legend'=>['总数','已销售'],'xAxis'=>["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"],'unit'=>'元','yAxis'=>[],'series'=>[[60,60,60,60,60,60],[5, 20, 36, 10, 10, 20]]]
    public static function wdj()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'wdj';
        return $obj;
    }
    public static function wdjStack()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'wdjStack';
        return $obj;
    }
    //饼形图,options=['title'=>'某站点用户访问来源','legend'=>['直接访问','邮件营销','联盟广告','视频广告','搜索引擎'],'series'=>[['value'=>335, 'name'=>'直接访问'], ['value'=>310, 'name'=>'邮件营销'], ['value'=>234, 'name'=>'联盟广告'], ['value'=>135, 'name'=>'视频广告'], ['value'=>1548, 'name'=>'搜索引擎']]]
    public static function pie()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'pie';
        return $obj;
    }
    public static function pietest()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'pietest';
        return $obj;
    }
    //饼形图(左右两个),options=['title'=>'南丁格尔玫瑰图','subtext'=>'虚构','legend'=>['rose1','rose2','rose3','rose4','rose5','rose6','rose7','rose8'],'series'=>['name'=>['半径模式','面积模式'],'data'=>[$sdata1,$sdata2]]]
    public static function pie2()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'pie2';
        return $obj;
    }
    //柱形图表(组),options=['dataset'=>[['product(项目)','2015','2016','2017'],['aaa(名称)',123,231,234(数据)],['bbb',24,254,234],['ccc',423,54,321]]]
    public static function barGroup()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'barGroup';
        return $obj;
    }
    //柱形图表(组)--柱形条显示名称options=['color'=>['#003366', '#006699', '#4cabce', '#e5323e'],'legend'=>['投入品1','投入品2','投入品3','农药1'],'xAxis'=>['小麦','玉米','大豆','杂豆','马铃薯'],'series'=>[[12,53,24,54,43],[65,34,26,34,23],[64,34,24,34,43],[36,54,26,76,14],[54,24,54,23,54]]]
    public static function barLabel()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'barLabel';
        return $obj;
    }
    //种植作物,计划与复核,options=[]
    public static function barLabel2()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'barLabel2';
        return $obj;
    }
    //种植作物,计划与复核,options=[]
    public static function barLabel3()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'barLabel3';
        return $obj;
    }
    //种植作物,计划与复核,options=[]
    public static function barLabel4()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'barLabel4';
        return $obj;
    }
    //种植作物,计划与复核,options=[]
    public static function barDouble()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'barDouble';
        return $obj;
    }

    //堆叠图表
    public static function barDiudie()
    {
        self::updateUserEcharts();
        $obj = new Echarts();
        $obj->type = 'barDiudie';
        return $obj;
    }

    public static function updateUserEcharts()
    {
        $cache = Cache::find()->where(['user_id'=>\Yii::$app->user->id,'year'=>User::getYear()])->one();
        if(empty($cache)) {
            self::newEcharts($cache['id']);
        }
        if(empty($cache['state'])) {
            self::updateEcharts($cache['id']);
        }
    }
//    public static function typename($array)
//    {
//        $result = [];
//        $types = [];
//        $classname = 'app\\models\\'.$array['class'];
//        $data = $classname::find()->where($array['where'])->all();
//        $class2 = explode('_',$array['field']);
//        $classname2 = 'app\\models\\'.ucfirst($class2[0]);
//        foreach ($data as $key => $value) {
//            $types[] = ['id'=>$value[$array['field']]];
//        }
////        var_dump($types);exit;
//        if($types) {
//            $newdata = Farms::unique_arr($types);
//            foreach ($newdata as $value) {
//                $result[$value['id']] = $classname2::find()->where(['id' => $value['id']])->one()['typename'];
//            }
//        }
//        return $result;
//    }
//
//    public static function getTypelist($array)
//    {
//        $types = self::typename($array);
//        sort($types);
//        return $types;
//    }
//
//    public static function getData($array)
//    {
//        $result = [];
//        $types = self::typename($array);
//        $classname = 'app\\models\\'.$array['class'];
//        foreach ($types as $key => $value) {
//            $result[] = sprintf('%.2f',$data = $classname::find()->where($array['where'])->andFilterWhere([$array['field']=>$key])->sum($array['sum']));
//        }
//        return $result;
//    }

    public static function updateEcharts($cache_id)
    {
        $model = Cache::findOne($cache_id);
//        var_dump($model);exit;
        $user_id = $model->user_id;
        self::Farmscacheone($user_id);
        self::Collectioncacheone($user_id);
        self::Huinongcacheone($user_id);
        self::Insurancecacheone($user_id);
        self::Loancacheone($user_id);
        self::Firecache($user_id);
        self::Plantingstructurecacheone($user_id);
//        self::Plantinputproductcacheone($user_id);
        self::Projectapplicationcacheone($user_id);
        self::Yieldscacheone($user_id);
        self::Breedinfocacheone($user_id);
        $model->state = 1;
        $model->save();
    }
    public static function newEcharts($cache_id)
    {
        $model = new Cache();
        $user_id = \Yii::$app->user->id;
        self::Farmscacheone($user_id);
        self::Collectioncacheone($user_id);
        self::Huinongcacheone($user_id);
        self::Insurancecacheone($user_id);
        self::Loancacheone($user_id);
        self::Firecache($user_id);
        self::Plantingstructurecacheone($user_id);
        self::Plantinputproductcacheone($user_id);
        self::Projectapplicationcacheone($user_id);
        self::Yieldscacheone($user_id);
        self::Breedinfocacheone($user_id);
        $model->state = 1;
        $model->save();
    }

    public function updateall($user_id)
    {
        $this->actionFarmscacheone($user_id);
        $this->actionCollectioncacheone($user_id);
        $this->actionHuinongcacheone($user_id);
        $this->actionInsurancecacheone($user_id);
        $this->actionLoancacheone($user_id);
        $this->actionFirecache($user_id);
        $this->actionPlantingstructurecacheone($user_id);
        $this->actionPlantinputproductcacheone($user_id);
        $this->actionProjectapplicationcacheone($user_id);
        $this->actionYieldscacheone($user_id);
        $this->actionBreedinfocacheone($user_id);
    }

    //以下为单个用户
    public function actionFarmscacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->farmscache = Echartsdata::getFarmarea($user_id);
        $landcache->farmstitle = '面积：'.Echartsdata::totalArea($user_id).' 农场户数：'.Echartsdata::totalNum($user_id);
        $landcache->farmscategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->farmstime = time();
        $result = $landcache->save();
        echo '宜林农地首页图表更新完成！';
        return $result;
    }

    public function actionCollectioncacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->collectioncache = Echartsdata::getCollection($user_id);
        $landcache->collectiontitle = '应收：'. Echartsdata::totalAmounts($user_id).' 实收：'.Echartsdata::totalReal($user_id);
        $landcache->collectioncategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->collectiontime = time();
        $result = $landcache->save();
        echo 'collectioncache finished|';
        return $result;
    }
    public function actionPlantingstructurecacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $plantid = Echartsdata::getAllPlantname($user_id);
        $landcache->plantingstructurecache = Echartsdata::getPlantingstructure($user_id,$plantid['id']);
        $landcache->plantingstructuretitle = '作物面积：'.Echartsdata::getPlantGoodseedSum($user_id)['fact']['plantSum'].'亩&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;良种面积：'.Echartsdata::getPlantGoodseedSum($user_id)['fact']['goodseedSum'].'亩';
        $landcache->plantingstructurecategories = json_encode($plantid['name']);
        $landcache->plantingstructuretime = time();
//        $landcache->year = User::getYear();
        $result = $landcache->save();
        echo '种植结构首页图表更新完成！';
        return $result;
    }

    public function actionHuinongcacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->huinongcache = Echartsdata::getHuinonggrantinfo($user_id);
        $landcache->huinongtitle = '惠农政策补贴发放情况';
        $landcache->huinongcategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->huihongtime = time();
        $result = $landcache->save();
        echo '惠农政策补贴发放首页图表更新完成！';
        return $result;
    }

    public function actionPlantinputproductcacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->plantinputproductcache = Echartsdata::getInputproduct($user_id);
        $landcache->plantinputproducttitle = '投入品使用情况';
        $landcache->plantinputproductcategories = json_encode(Echartsdata::getTypenamelist($user_id)['typename']);
        $landcache->plantinputproducttime = time();
        $result = $landcache->save();
        echo '投入品使用情况首页图表更新完成！';
        return $result;
    }

    public function actionMachinecacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->machinecache = json_encode(Machineapply::getCache($user_id));
        $landcache->machinetitle = '农机补贴情况';
//        var_dump(Plantingstructurecheck::getAllPlantname($user_id)['name']);
        $landcache->machinecategories = json_encode(Farms::getUserManagementArea($user_id)['small']);
        $landcache->machinetime = time();
//        var_dump($landcache);exit;
        $result = $landcache->save();
        echo '农机首页图表更新完成！';
        return $result;
    }

    public function actionYieldscacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->plantinputproductcache = json_encode(Yields::getCache($user_id));
        $landcache->plantinputproducttitle = '农产品产量情况';
//        var_dump(Plantingstructurecheck::getAllPlantname($user_id)['name']);
        $landcache->plantinputproductcategories = json_encode(Plantingstructurecheck::getAllPlantname($user_id,User::getLastYear())['name']);
        $landcache->plantinputproducttime = time();
//        var_dump($landcache);exit;
        $result = $landcache->save();
        echo '农产品产量情况首页图表更新完成！';
        return $result;
    }

    public function actionFirecache($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
// 		var_dump(Fireprevention::getBfblist($id));
        $landcache->firecache = Fireprevention::getBfblist($user_id);
// 		var_dump($landcache->firecache);
        $landcache->firetitle = '防火完成度：'.Fireprevention::getAllbfb($user_id);
        $landcache->firecategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->firetime = time();
        $landcache->save();
        echo 'finished fire';
    }

    public function actionBreedinfocacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->breedinfocache = Breedinfo::getCache($user_id);
        $landcache->breedinfotitle = '畜牧养殖';
        $landcache->breedinfocategories = json_encode(Breedinfo::getTypes($user_id));
        $landcache->breedinfotime = time();
        $result = $landcache->save();
        echo 'breedinfo finished|';
        return $result;
    }

    public function actionProjectapplicationcacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
//        var_dump($user_id);exit;
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->projectapplicationcache = Projectapplication::getProjectapplicationcache($user_id);
        $landcache->projectapplicationtitle = '项目情况';
        $landcache->projectapplicationcategories = json_encode(Projectapplication::getTypenamelist($user_id)['projecttype']);
        $landcache->projectapplicationtime = time();
//        var_dump($landcache);exit;
        $result = $landcache->save();
//        var_dump($landcache->getErrors());exit;
        echo '项目情况首页图表更新完成！';
        return $result;
    }
    public function actionInsurancecacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->year = User::getUserYear();
            $landcache->user_id = $user_id;
        }
        $landcache->insurancecache = Insurance::getInsurancecache($user_id);
        $landcache->insurancetitle = '保险业务';
        $landcache->insurancecategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->insurancetime = time();
        $result = $landcache->save();
        echo 'insurahcecache finished|';
        return $result;
    }

    public function actionLoancacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->year = User::getYear();
        }
        $landcache->user_id = $user_id;
        $landcache->loancache = Loan::getLoancache($user_id);
        $landcache->loantitle = '贷款金额：'.Loan::getLoanMoney($user_id).'亩';
        $landcache->loancategories = json_encode(Loan::getBankName('small'));
        $landcache->loantime = time();
        $result = $landcache->save();
//        var_dump($landcache);
//        var_dump($landcache->getErrors());
        echo 'loancache finished|';
        return $result;
    }

    //以下为单个用户     静态方法
    public static function Farmscacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }

        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->farmscache = Echartsdata::getFarmarea($user_id);
        $landcache->farmstitle = '面积：'.Echartsdata::totalArea($user_id).' 农场户数：'.Echartsdata::totalNum($user_id);
        $landcache->farmscategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->farmstime = time();
        $result = $landcache->save();
//        echo '宜林农地首页图表更新完成！';
//        return $result;
    }

    public static function Collectioncacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->collectioncache = Echartsdata::getCollection($user_id);
        $landcache->collectiontitle = '应收：'. Echartsdata::totalAmounts($user_id).' 实收：'.Echartsdata::totalReal($user_id);
        $landcache->collectioncategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->collectiontime = time();
        $result = $landcache->save();
//        echo 'collectioncache finished|';
//        return $result;
    }
    public static function Plantingstructurecacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $plantid = Echartsdata::getPlan_checkname($user_id);
        if($plantid) {
            $landcache->plantingstructurecache = json_encode(Echartsdata::getPlantingstructure($user_id));
            $landcache->plantingstructurecategories = json_encode($plantid['typename']);
        } else {
            $landcache->plantingstructurecache = json_encode(['plan'=>[],'fact'=>[]]);
            $landcache->plantingstructurecategories = json_encode([]);
        }
//        var_dump($landcache->plantingstructurecache);exit;
        $landcache->plantingstructuretitle = '作物面积：'.Echartsdata::getPlantGoodseedSum($user_id)['fact']['plantSum'].'亩&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;良种面积：'.Echartsdata::getPlantGoodseedSum($user_id)['fact']['goodseedSum'].'亩';

        $landcache->plantingstructuretime = time();
//        $landcache->year = User::getYear();
        $result = $landcache->save();
//        echo '种植结构首页图表更新完成！';
//        return $result;
    }

    public static function Huinongcacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->huinongcache = Echartsdata::getHuinongCache($user_id);
        $landcache->huinongtitle = '惠农政策补贴发放情况';
        $landcache->huinongcategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->huinongtime = time();
        $result = $landcache->save();
//        echo '惠农政策补贴发放首页图表更新完成！';
//        return $result;
    }

//    public static function Plantinputproductcacheone($user_id=null)
//    {
//        if(empty($user_id)) {
//            $user_id = \Yii::$app->user->id;
//        }
//        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
//            $landcache = Cache::findOne($cache->id);
//        else {
//            $landcache = new Cache();
//            $landcache->user_id = $user_id;
//            $landcache->year = User::getYear();
//        }
//        $landcache->plantinputproductcache = Plantinputproduct::getInputproduct($user_id);
//        $landcache->plantinputproducttitle = '投入品使用情况';
//        $landcache->plantinputproductcategories = json_encode(Plantinputproduct::getTypenamelist($user_id)['typename']);
//        $landcache->plantinputproducttime = time();
//        $result = $landcache->save();
////        echo '投入品使用情况首页图表更新完成！';
////        return $result;
//    }
//
//    public static function Machinecacheone($user_id=null)
//    {
//        if(empty($user_id)) {
//            $user_id = \Yii::$app->user->id;
//        }
//        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
//            $landcache = Cache::findOne($cache->id);
//        else {
//            $landcache = new Cache();
//            $landcache->user_id = $user_id;
//            $landcache->year = User::getYear();
//        }
//        $landcache->machinecache = json_encode(Machineapply::getCache($user_id));
//        $landcache->machinetitle = '农机补贴情况';
////        var_dump(Plantingstructurecheck::getAllPlantname($user_id)['name']);
//        $landcache->machinecategories = json_encode(Farms::getUserManagementArea($user_id)['small']);
//        $landcache->machinetime = time();
////        var_dump($landcache);exit;
//        $result = $landcache->save();
////        echo '农机首页图表更新完成！';
////        return $result;
//    }

    public static function Yieldscacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->plantinputproductcache = json_encode(Echartsdata::getYieldsCache());
        $landcache->plantinputproducttitle = '农产品产量情况';
//        var_dump(Plantingstructurecheck::getAllPlantname($user_id)['name']);
        $landcache->plantinputproductcategories = json_encode(Echartsdata::getPlan_checkname());
        $landcache->plantinputproducttime = time();
//        var_dump($landcache);exit;
        $result = $landcache->save();
//        echo '农产品产量情况首页图表更新完成！';
//        return $result;
    }

    public static function Firecache($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
// 		var_dump(Fireprevention::getBfblist($id));
        $landcache->firecache = Fireprevention::getBfblist($user_id);
// 		var_dump($landcache->firecache);
        $landcache->firetitle = '防火完成度：'.Fireprevention::getAllbfb($user_id);
        $landcache->firecategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->firetime = time();
        $landcache->save();
//        echo 'finished fire';
    }

    public static function Breedinfocacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->breedinfocache = Echartsdata::getBreedinfoCache($user_id,'data');
        $landcache->breedinfotitle = '畜牧养殖';
        $landcache->breedinfocategories = json_encode(Echartsdata::getBreedtypeList($user_id,'typename'));
        $landcache->breedinfotime = time();
        $result = $landcache->save();
//        echo 'breedinfo finished|';
//        return $result;
    }

    public static function Projectapplicationcacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
//        var_dump($user_id);exit;
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->user_id = $user_id;
            $landcache->year = User::getYear();
        }
        $landcache->projectapplicationcache = Echartsdata::getProjectapplicationcache();
        $landcache->projectapplicationtitle = '项目情况';
        $landcache->projectapplicationcategories = json_encode(Echartsdata::getProjectTypename()['projecttype']);
        $landcache->projectapplicationtime = time();
//        var_dump($landcache);exit;
        $result = $landcache->save();
//        var_dump($landcache->getErrors());exit;
//        echo '项目情况首页图表更新完成！';
//        return $result;
    }
    public static function Insurancecacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->year = User::getUserYear();
            $landcache->user_id = $user_id;
        }
        $landcache->insurancecache = Echartsdata::getInsurancecache();
        $landcache->insurancetitle = '保险业务';
        $landcache->insurancecategories = json_encode(Farms::getUserManagementAreaname($user_id));
        $landcache->insurancetime = time();
        $result = $landcache->save();
//        echo 'insurahcecache finished|';
//        return $result;
    }

    public static function Loancacheone($user_id=null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>User::getYear()])->one())
            $landcache = Cache::findOne($cache->id);
        else {
            $landcache = new Cache();
            $landcache->year = User::getYear();
        }
        $landcache->user_id = $user_id;
        $landcache->loancache = Echartsdata::getLoancache($user_id);
        $landcache->loantitle = '贷款金额：'.Echartsdata::getLoanMoney($user_id).'万元';
        $landcache->loancategories = json_encode(Echartsdata::getBankList('small'));
        $landcache->loantime = time();
        $result = $landcache->save();
//        var_dump($landcache);
//        var_dump($landcache->getErrors());
//        echo 'loancache finished|';
//        return $result;
    }
    public function actionNewyear()
    {
        $oldyear = Theyear::getYear();
        if(Theyear::getYear() !== User::getYear()) {
            Theyear::setYear(User::getYear());
            User::setAllUserYear(User::getYear());
            $collection = Collection::find()->all();
            foreach ($collection as $coll) {
                $model = Collection::findOne($coll['id']);
                if ($model->state == 0 and $model->payyear == $oldyear) {
                    $model->owe = $model->ypaymoney;
// 					$model->update_at = time();
                    $model->ypayyear = $oldyear;
                }

                $model->save();
            }
        }
    }
}