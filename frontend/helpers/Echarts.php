<?php
namespace frontend\helpers;
use yii\web\View;
use yii\helpers\Html;
use app\models\Tables;
use app\models\Farms;
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2018/4/2
 * Time: 17:48
 */
class Echarts
{
    private $id;
    private $title;
    private $legend;
    private $xAxis;
    private $yAxis = null;
    private $series;
    public $type;
    private $width = '600px';
    private $height = '400px';
    private $dom;
    private $javaScript;
    private $name;
    private $options;
    private $tooltip;
    private $subtext;
    private $toolbox;
    private $dataset;
    private $unit='';
    private $color;
    private $class;
    private $where;
    private $percent;
    private $condition;
    private $symbol;
    private $andWhere = NULL;
    private $pjz;
    public function DOM($id,$state=true,$width=null,$height=null)
    {
        $this->id = $id;
        if(!$state) {
            return $this;
        }
        if(!empty($width)) {
            $this->width = $width;
        }
        if(!empty($height)) {
            $this->height = $height;
        }
        $html = '';
        if($id == 23) {
            $html .='<ul class="nav nav-pills nav-pills-warning">';
            $html .='<li class="active" id="pinfo"><a href="#huinong" data-toggle="tab" aria-expanded="true">生产者补贴</a></li>';
            $html .= '<li class="" id="pcinfo"><a href="#machine" data-toggle="tab" aria-expanded="false">农机补贴</a></li>';
            $html .= '</ul>';
        } else {
            $html .= '<div id="' . $this->id . '" style="width: ' . $this->width . ';height:' . $this->height . ';" style="margin-left:auto;margin-right:auto;"></div>';
        }
        $this->dom = $html;
        echo $this->dom;
        return $this;
    }


    private function begin()
    {
        $html = "<script type=\"text/javascript\">
            var myChart = echarts.init(document.getElementById('".$this->id."'));";
        return $html;
    }
    private function end()
    {
        $html = "myChart.setOption(option);</script>";
        return $html;
    }
    public function options(Array $array)
    {
//        var_dump($array);exit;
        foreach ($array as $key=>$value) {
//            if(empty($value)) {
//                $this->options .= $key.":".json_encode($value,JSON_FORCE_OBJECT).',';
//            } else {
//                $this->options .= $key.':'.json_encode($value).',';
//            }
            $this->$key = $value;
        }
//        var_dump($this->series['name'][0]);
//        exit;
        return $this;
    }
    public function obj($obj)
    {
        if(is_array($obj)) {

        } else {
            $arrclass = explode('\\', $obj->query->modelClass);
            $this->class = $arrclass[2];
            $this->where = $obj->query->where;
        }
        return $this;
    }
    public function andWhere(Array $array) {
        $this->andWhere = $array;
        return $this;
    }
    public function where(Array $array)
    {
        switch ($this->type) {
            case 'bar':
                $this->title = Tables::find()->where(['tablename'=>$this->class])->one()['Ctablename'];
                $this->xAxis = $this->getTypelist($array);
                $this->series = $this->getData($array);
                $this->unit = $array['unit'];
                break;
            case 'pie':
                $this->title = Tables::find()->where(['tablename'=>$this->class])->one()['Ctablename'];
                $this->legend = $this->getTypelist($array);
                $this->series = $this->getData($array);
                $this->unit = $array['unit'];
                $this->name = '占比';
                break;
            case 'wdj':
                $this->title = Tables::find()->where(['tablename'=>$this->class])->one()['Ctablename'];
                $this->xAxis = $this->getTypelist($array);
                $this->legend = ['实收金额','应收金额'];
                $this->series = $this->getData($array);
                $this->unit = $array['unit'];
                break;
            case 'barLabel':
//                var_dump($array);exit;
                $this->title = Tables::find()->where(['tablename'=>$this->class])->one()['Ctablename'];
                $this->xAxis = $this->getTypelist($array);
                $this->series = $this->getData($array);
                $this->unit = $array['unit'];
                break;
            case 'barLabel2':
                $this->title = Tables::find()->where(['tablename'=>$this->class])->one()['Ctablename'];
                $this->xAxis = $this->getTypelist($array);
                $this->series = $this->getData($array);
                $this->unit = $array['unit'];
                break;
        }
        return $this;
    }

    public function typename($array)
    {
        $result = [];
        $types = [];
        $classname = 'app\\models\\'.$this->class;
        $data = $classname::find()->where($this->where)->all();
        if(is_array($array['field'])) {
            $key = key($array['field']);
            return $array['field'][$key];
        } else {
            switch ($array['field']) {
                case 'management_area':
                    $classname2 = 'app\\models\\ManagementArea';
                    break;
                case 'company_id':
                    $classname2 = 'app\\models\\Insurancecompnay';
                default:
                    $class2 = explode('_',$array['field']);
                    $classname2 = 'app\\models\\'.ucfirst($class2[0]);
            }

        }

        foreach ($data as $key => $value) {
            $types[] = ['id'=>$value[$array['field']]];
        }
//        var_dump($types);exit;
        if($types) {
            $newdata = Farms::unique_arr($types);
            foreach ($newdata as $value) {
                switch ($array['field']) {
                    case 'management_area':
                        $result[$value['id']] = $classname2::find()->where(['id' => $value['id']])->one()['areaname'];
                        break;
                    case 'compnay_id':
                        $result[$value['id']] = $classname2::find()->where(['id' => $value['id']])->one()['companyname'];
                    default:
                        $result[$value['id']] = $classname2::find()->where(['id' => $value['id']])->one()['typename'];
                }

            }
        }
        return $result;
    }

    public function getTypelist($array)
    {
        $data = [];
        $types = $this->typename($array);
        foreach ($types as $value) {
            $data[] = $value;
        }
        return $data;
    }

    public function getData($array)
    {
        $result = [];
        $types = $this->typename($array);
        $classname = 'app\\models\\' . $this->class;
        switch ($this->type) {
            case 'wdj':
                foreach ($types as $key => $value) {
                    if(isset($array['sum'])) {
                        $query = $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key]);
                        $all[] = sprintf('%.2f', $query->sum($array['sum'][0]));
                        if(!empty($this->andWhere)) {
                            $query->andFilterWhere($this->andWhere);
                        }
                        $real[] = sprintf('%.2f', $query->sum($array['sum'][1]));
                    }
                    if(isset($array['count'])) {
                        $query = $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key]);
                        if(!empty($this->andWhere)) {
                            $query->andFilterWhere($this->andWhere);
                        }
                        $all[] = sprintf('%.2f', $query->count($array['count']));
                        $real[] = sprintf('%.2f', $query->count($array['count']));
                    }
                }
                $result = [$real,$all];
                break;
            case 'pie':
                if(is_array($array['field'])) {
                    $field = key($array['field']);
                    foreach ($array['field'][$field] as $key => $value) {
                        $query = $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key]);
                        if(!empty($this->andWhere)) {
                            $query->andFilterWhere($this->andWhere);
                        }
                        if(isset($array['sum'])) {
                            $result[$key] = sprintf('%.2f', $query->sum($array['sum']));
                        }
                        if(isset($array['count'])) {
                            $result[$key] = sprintf('%.2f', $query->count($array['count']));
                        }
                    }
                    return $result;
                } else {
                    $classname = 'app\\models\\' . $this->class;
                    foreach ($types as $key => $value) {
                        $query = $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key]);
                        if(!empty($this->andWhere)) {
                            $query->andFilterWhere($this->andWhere);
                        }
                        if (isset($array['sum'])) {
                            $result[] = ['value' => sprintf('%.2f', $query->sum($array['sum'])), 'name' => $value];
                        }
                        if (isset($array['count'])) {
                            $result[] = ['value' => sprintf('%.2f', $query->groupBy($array['count'])->count()), 'name' => $value];
                        }
                    }
                }
                break;
            default:
                if(is_array($array['field'])) {
                    $field = key($array['field']);
                    foreach ($array['field'][$field] as $key => $value) {
                        $query = $classname::find()->where($this->where)->andFilterWhere([$field => $value]);
                        if(!empty($this->andWhere)) {
                            $query->andFilterWhere($this->andWhere);
                        }
                        if(isset($array['sum'])) {
                            $result[$key] = $query->sum($array['sum']);
                        }
                        if(isset($array['count'])) {
                            $result[$key] = $query->count($array['count']);
                        }
                    }
                } else {
                    $classname = 'app\\models\\' . $this->class;
                    foreach ($types as $key => $value) {
                        $query = $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key]);
                        if(!empty($this->andWhere)) {
                            $query->andFilterWhere($this->andWhere);
                        }
                        if(isset($array['sum'])) {
                            $result[] = sprintf('%.2f', $query->sum($array['sum']));
                        }
                        if(isset($array['count'])) {
                            $result[$key] = $query->count($array['count']);
                        }
                    }
                }
                break;
        }
        return $result;
    }

    public function getWdjData($array)
    {
        $all = [];
        $real = [];
        $types = $this->typename($array);
        $classname = 'app\\models\\' . $this->class;
        foreach ($types as $key => $value) {
            if(isset($array['sum'])) {
                $all[] = sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key])->sum($array['sum'][0]));
                $real[] = sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key])->sum($array['sum'][1]));
            }
            if(isset($array['count'])) {
                $all[] = sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key])->count($array['count']));
                $real[] = sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key])->count($array['count']));
            }
        }
        return [$real,$all];
    }

    public function getBarDoubleData($array)
    {
        $data = [];
        $types = $this->typename($array);
        $classname = 'app\\models\\' . $this->class;
        if(isset($array['condition'])) {
            $classname2 = 'app\\models\\' . $array['condition']['class'];
            $field2 = $array['condition']['field'];
            $where = $this->getCondition($this->where,'year');
            $result = $classname2::find()->where($where)->one()[$field2];
        }
        foreach ($types as $key => $value) {
            $sum1 = sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key])->sum($array['sum']));
            switch ($this->symbol) {
                case 'mul':
                    $data[] = sprintf('%.2f',$sum1 * $result);
                    break;
            }
        }
        return $data;
    }

    public function getCondition($where,$field)
    {
        $result = [];
        $array = whereHandle::whereToArray($where);
        if(is_array($field)) {
            foreach ($array as $key => $value) {
                foreach ($field as $k => $v) {
                    if ($v == $key) {
                        $result[$key] = $value;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                if ($field == $key) {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }

    public function getPieData($array)
    {
        $result = [];
        $types = $this->typename($array);
        if(is_array($array['field'])) {
            $classname = 'app\\models\\' . $this->class;
            $field = key($array['field']);
//            var_dump($array['field'][$field]);exit;
            foreach ($array['field'][$field] as $key => $value) {
                if(isset($array['sum'])) {
                    $result[$key] = sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$field => $value])->sum($array['sum']));
                }
                if(isset($array['count'])) {
                    $result[$key] = sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$field => $value])->count($array['count']));
                }
            }
            return $result;
        } else {
//            if(is_array($array['field'])) {
//                switch (key($array['field'])) {
//                    case 'proportion':
//                        foreach ($array['field'] as $key => $value) {
//                            $classname = 'app\\models\\' . $key;
//                            $result[] = sprintf('%.2f', $classname::find()->where($this->where)->sum($value));
//                        }
//                        break;
//                    default:
                        $classname = 'app\\models\\' . $this->class;
                        foreach ($types as $key => $value) {
                            if (isset($array['sum'])) {
                                $result[] = ['value' => sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key])->sum($array['sum'])), 'name' => $value];
                            }
                            if (isset($array['count'])) {
                                $result[] = ['value' => sprintf('%.2f', $classname::find()->where($this->where)->andFilterWhere([$array['field'] => $key])->groupBy($array['count'])->count()), 'name' => $value];
                            }
                        }
//                }
//            }
            return $result;
        }
    }
    
    public function JS()
    {
        $html = $this->begin();
//        var_dump($this->series['data'][0]);exit;
        switch ($this->type)
        {
            case 'bar':
                $html.= "
                    var option = {

                    title: {
                        text: ".json_encode($this->title).",
                        x:'center'
                    },
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        formatter: '{b} <br/> {c}".$this->unit."'
                    },
                    
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis : [
                        {
                            type : 'category',
                            data : ".json_encode($this->xAxis).",
                            axisTick: {
                                alignWithLabel: true
                            }
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    legend: {
                      data:".json_encode($this->legend).",
                    },
                    series : [
                        {
                            type:'bar',
                            data:".json_encode($this->series)."
                        }
                    ]};";
                break;
            case 'bar2':
                $html.= "
                    var option = {
                    
                    color: ['#3398DB'],
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        formatter: '{a} <br/>{b} : {c}".$this->unit."'
                    },
                    
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis : [
                        {
                            type : 'category',
                            data : ".json_encode($this->xAxis).",
                            axisTick: {
                                alignWithLabel: true
                            }
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            type:'bar',
                            data:".json_encode($this->series)."
                        }
                    ]};";
                break;
            case 'barUnit':
//                echo "var option={".$this->options."};";
                $html.= "
                    var option = {
                    
                    color: ['#3398DB','#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'],
                    tooltip : {
                        trigger: 'axis',
                        formatter: function (params,ticket,callback) {
                            var row = eval(ticket); 
                            var res = params[0].seriesName;
                            var unit = ".json_encode($this->unit).";
                            res += '<br/>'+params[0].name+'：' + params[0].value + unit[params[0].dataIndex];
                            return res;
                        },
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis : [
                        {
                            type : 'category',
                            data : ".json_encode($this->xAxis).",
                            axisTick: {
                                alignWithLabel: true
                            }
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:".json_encode($this->name).",
                            type:'bar',
                            barWidth: '60%',
                            data:".json_encode($this->series)."
                        }
                    ]};";
                break;
            case 'wdj':
//                echo "var option={".$this->options."};";
                $html .= "
                    var option = {
                    title: {
                        text: ".json_encode($this->title)."
                    },
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        formatter: function (params,ticket,callback) {
                            var row = eval(ticket); 
                            var res = params[0].name;
                           res += '<br/>'+params[1].seriesName+'：' + params[1].value + '".$this->unit."'
                           res += '<br/>'+params[0].seriesName+'：' + params[0].value + '".$this->unit."'
                            if(params[0].value == 0) {
                                var v = 0;
                            } else {
                                var v = params[1].value/params[0].value; 
                            }
                            var bfb = v.toFixed(2)*100;
                           res += '<br/>' + '完成：' + bfb.toFixed(2) + '%';
                            return res;
                         },
                    },
                   
                    xAxis: {
                        data: ".json_encode($this->xAxis)."
                    },
                    yAxis: {
                        
                    },
                    legend: {
                        data: ".json_encode($this->legend)."
                    },
                    series: [{
                        name: ".json_encode($this->legend[1]).",
                        type: 'bar',
                        itemStyle: {
                            normal: {
                                color: '#fff',
                                borderColor:'#b12f27',
                                borderWidth:3
                            }
                        },
                        silent: true,
                        barGap: '-100%', // Make series be overlap
                        data: ".json_encode($this->series[1])."
                    }, {
                        name: ".json_encode($this->legend[0]).",
                        type: 'bar',
                        z: 10,
                        data: ".json_encode($this->series[0])."
                    }]
                };";
                break;
            //stack=堆叠
            case 'wdjStack':
//                echo "var option={".$this->options."};";
                $html .= "
                    var option = {
                    title: {
                        text: ".json_encode($this->title)."
                    },
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        formatter: function (params,ticket,callback) {
                            var row = eval(ticket); 
                            var res = params[0].name;
                           res += '<br/>'+params[2].seriesName+'：' + params[2].value + '".$this->unit."'
                           res += '<br/>'+params[1].seriesName+'：' + params[1].value + '".$this->unit."'
                           res += '<br/>'+params[0].seriesName+'：' + params[0].value + '".$this->unit."'
                            if(params[0].value == 0) {
                                var v = 0;
                            } else {
                                var v = params[1].value/params[0].value; 
                            }
                            var bfb = v.toFixed(2)*100;
                           res += '<br/>' + '完成：' + bfb.toFixed(2) + '%';
                            return res;
                         },
                    },
                   
                    xAxis: {
                        data: ".json_encode($this->xAxis)."
                    },
                    yAxis: {
                        
                    },
                    legend: {
                        itemStyle: {
                            normal: {
                                borderColor:'#b12f27',
                                borderWidth:3
                            }
                        },
                        data: ".json_encode($this->legend)."
                    },
                    series: [
                    {
                        name: ".json_encode($this->legend[0]).",
                        type: 'bar',
                        itemStyle: {
                            normal: {
                                color: '#fff',
                                borderColor:'#b12f27',
                                borderWidth:3
                            }
                        },
                        silent: true,
                        barGap: '-100%', // Make series be overlap
                        data: ".json_encode($this->series[0])."
                    },
                    {
                        name: ".json_encode($this->legend[1]).",
                        type: 'bar',
                        itemStyle: {
                            normal: {
                                color: '#03ddd0',
                                borderColor:'#b12f27',
                                borderWidth:3
                            }
                        },
                        silent: true,
                        barGap: '-100%', // Make series be overlap
                        data: ".json_encode($this->series[1])."
                    }, 
                    {
                        name: ".json_encode($this->legend[2]).",
                        type: 'bar',
                        z: 10,
                        data: ".json_encode($this->series[2])."
                    },
                    ]
                };";
                break;
            case 'pie':
                $html.= "
                var option = {
                    title : {
                        text: ".json_encode($this->title).",
                        x:'center'
                    },
                    tooltip : {
                        trigger: 'item',
                        left: 'left',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left',
                        data: ".json_encode($this->legend)."
                    },
                    series : [
                        {
                            name: ".json_encode($this->name).",
                            type: 'pie',
                            radius : '80%',
                            center: ['50%', '60%'],
                            data:".json_encode($this->series).",
                            itemStyle: {
                                emphasis: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };
                ";
                break;
            case 'pietest':
                $html.= "
                    var option = {
                    
                   
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        
                    },
                    
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis : [
                        {
                            type : 'category',
                            data : ".json_encode($this->xAxis).",
                            axisTick: {
                                alignWithLabel: true
                            }
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            type:'bar',
                            data:".json_encode($this->series)."
                        },
                        {
                            type: 'pie',
                            radius: [0, '30%'],
                            center: ['75%', '25%'],
                            data:".json_encode($this->series)."
                        },
                    ]};";
                break;
            case 'pie2':
                $html .= "
                var option = {
                    title : {
                        text: ".json_encode($this->title).",
                        subtext: '".json_encode($this->subtext)."',
                        x:'center'
                    },
                    tooltip : {
                        trigger: 'item',
                        formatter: \"{a} <br/>{b} : {c} ({d}%)\"
                    },
                    legend: {
                        x : 'center',
                        y : 'bottom',
                        data:".json_encode($this->legend)."
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            mark : {show: true},
                            dataView : {show: true, readOnly: false},
                            magicType : {
                                show: true,
                                type: ['pie', 'funnel']
                            },
                            restore : {show: true},
                            saveAsImage : {show: true}
                        }
                    },
                    calculable : true,
                    series : [
                        {
                            name:".json_encode($this->series['name'][0]).",
                            type:'pie',

                            center : ['25%', '50%'],
                    
                            
                            data:".json_encode($this->series['data'][0])."
                        },
                        {
                            name:".json_encode($this->series['name'][1]).",
                            type:'pie',
                            
                            center : ['75%', '50%'],
                            data:".json_encode($this->series['data'][1])."
                        }
                    ]
                };";
                break;
            case 'barGroup':
                $html .= "
                    option = {
                        legend: {},
                        tooltip: {},
                        dataset: {
                            source: ".json_encode($this->dataset)."
                        },
                        xAxis: {type: 'category'},
                        yAxis: {},
                        series: [
                            {type: 'bar'},
                            {type: 'bar'},
                            {type: 'bar'}
                        ]
                    };
                ";
                break;
            case 'barLabel':
                $html.= "
                    var option = {
                        color: ".json_encode($this->color).",
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            },
                            
                        },
                        legend: {
                            data: ".json_encode($this->legend)."
                        },
                        toolbox: {
                            show: true,
                            orient: 'vertical',
                            left: 'right',
                            top: 'center',
                            feature: {
                                mark: {show: true},
                                dataView: {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                                restore: {show: true},
                                saveAsImage: {show: true}
                            }
                        },
                        calculable: true,
                        xAxis: [
                            {
                                type: 'category',
                                axisTick: {show: false},
                                data: ".json_encode($this->xAxis)."
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value'
                            }
                        ],
                        series: [
                        ";
                        foreach ($this->legend as $key=>$value) {
                            $html.= "{
                                name: ".json_encode($this->legend[$key]).",
                                type: 'bar',
                               
                                barGap: 0,
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'insideBottom',
                                        distance: 15,
                                        align: 'left',
                                        verticalAlign: 'middle',
                                        rotate: 90,
                                        formatter: '{c} '+'".$this->unit[$key]."',
                                        fontSize: 16,
                                        rich: {
                                            name: {
                                                textBorderColor: '#fff'
                                            }
                                        }
                                    }
                                },
                                data: ".json_encode($this->series[$key])."
                            },";
                        }
                    $html.= "]};
                ";
                break;
            case 'barLabel2':
                $html.= "
                    var option = {
                    color: ".json_encode($this->color).",
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            },
                            
                        },
                        legend: {
                            data: ".json_encode($this->legend)."
                        },
                        toolbox: {
                            show: false,
                            orient: 'vertical',
                            left: 'right',
                            top: 'center',
                            feature: {
                                mark: {show: true},
                                dataView: {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                                restore: {show: true},
                                saveAsImage: {show: true}
                            }
                        },
                        calculable: true,
                        xAxis: [
                            {
                                type: 'category',
                                axisTick: {show: false},
                                data: ".json_encode($this->xAxis).",
                                nameRotate:45,
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value'
                            }
                        ],
                        series: [
                        ";
                foreach ($this->legend as $key=>$value) {
                    $html.= "{
                                name: ".json_encode($this->legend[$key]).",
                                type: 'bar',
                               
                                barGap: 0,
                                
                                label: {
                                    normal: {
                                        show: false,
                                        position: 'insideBottom',
                                        distance: 15,
                                        align: 'left',
                                        verticalAlign: 'top',
                                        rotate: 90,
                                        formatter: '{c} '+".json_encode($this->unit).",
                                        fontSize: 16,
                                        rich: {
                                            name: {
                                                textBorderColor: '#fff'
                                            }
                                        }
                                    }
                                },
                                data: ".json_encode($this->series[$key])."
                            },";
                }
                $html.= "]};
                ";
                break;
            case 'barLabel3':
                $pjzlegend = $this->legend;
                $pjzlegend[] = '平均值';
                $html.= "
                    var option = {
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            },
                        },
                        legend: {
                            data: ".json_encode($pjzlegend)."
                        },
                        toolbox: {
                            show: false,
                            orient: 'vertical',
                            left: 'right',
                            top: 'center',
                            feature: {
                                mark: {show: true},
                                dataView: {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                                restore: {show: true},
                                saveAsImage: {show: true}
                            }
                        },
                        calculable: true,
                        xAxis: [
                            {
                                type: 'category',
                                axisTick: {show: false},
                                data: ".json_encode($this->xAxis).",
                                nameRotate:45,
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value',
                                name: '',
                                axisLabel: {
                                    formatter: '{value} 万元'
                                }
                            },
                            {
                                type: 'value',
                                name: '平均值',
                                axisLabel: {
                                    formatter: '{value} 万元'
                                }
                            }
                        ],
                        series: [
                        ";
                foreach ($this->legend as $key=>$value) {
                    $html.= "{
                                name: ".json_encode($this->legend[$key]).",
                                type: 'bar',
                               
                                barGap: 0,
                                
                                label: {
                                    normal: {
                                        show: false,
                                        position: 'insideBottom',
                                        distance: 15,
                                        align: 'left',
                                        verticalAlign: 'top',
                                        rotate: 90,
                                        formatter: '{c} '+".json_encode($this->unit).",
                                        fontSize: 16,
                                        rich: {
                                            name: {
                                                textBorderColor: '#fff'
                                            }
                                        }
                                    }
                                },
                                data: ".json_encode($this->series[$key])."
                            },";
                }
                $html.= "{
                                    name:'平均值',
                                    type:'line',
                                    yAxisIndex: 1,
                                    data:".json_encode($this->pjz).",
                                }";
                $html.= "]};
                ";
                break;
            case 'barLabel4':
                $html .= "
                    var option = {
                        
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            }
                        },
                        legend: {
                            data: ".json_encode($this->legend)."
                        },
                        grid: {
                            left: 100
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                saveAsImage: {}
                            }
                        },
                        yAxis: {
                            type: 'value',
                            axisLabel: {
                                formatter: '{value}'
                            }
                        },
                        xAxis: {
                            type: 'category',
                            inverse: true,
                            data: ['']
                        },
                        series: [";
                        if(count($this->series) == count($this->legend)) {
                       foreach ($this->series as $key => $value) {
                           $html .= "{
                                name: " . json_encode($this->legend[$key]) . ",
                                type: 'bar',
                                data: [" . json_encode($value) . "],
                            },";
                       }}
                        $html .= "]
                    };
                ";
                break;
            case 'barStack':
                $html.= "
                    var option = {
                        tooltip : {
                            trigger: 'axis',
                            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                            }
                        },
                        legend: {
                            data: ".json_encode($this->legend)."
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis:  {
                            type: 'value'
                        },
                        yAxis: {
                            type: 'category',
                            data: ".json_encode($this->yAxis)."
                        },
                        series: [";
                        foreach ($this->legend as $key => $value) {
                            $html.= "{
                                name: ".json_encode($value).",
                                type: 'bar',
                                stack: '总量',
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'insideRight'
                                    }
                                },
                                data: ".json_encode($this->series[$key])."
                            },";
                        }
                    $html.= "]};
                ";
                break;
            case 'barDouble':
                $html .= "var option = {
                    title: {
                        text: ".json_encode($this->title).",
                        subtext: ".json_encode($this->subtext)."
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    legend: {
                        data: ".json_encode($this->legend)."
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    yAxis: {
                        type: 'value',
                        boundaryGap: [0, 0.01]
                    },
                    xAxis: {
                        type: 'category',
                        data: ".json_encode($this->xAxis).",
                        axisLabel: {
                            interval: 0,
                            rotate: 30
                        },
                    },
                    series: [
                        {
                            name: ".json_encode($this->legend[0]).",
                            type: 'bar',
                            data: ".json_encode($this->series[0])."
                        },
                        {
                            name: ".json_encode($this->legend[1]).",
                            type: 'bar',
                            data: ".json_encode($this->series[1])."
                        }
                    ]
                };";
                break;
            case 'barDuidie':
                $html .= "var option = {
                        tooltip : {
                            trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                                        type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                        legend: {
                            data: ".json_encode($this->legend)."
                    },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis:  {
                            type: 'value',
                            data: ".json_encode($this->xAxis)."
                        },
                        yAxis: {
                            type: 'category',
                        },
                        series: [";
                        foreach ($this->series as $key => $value) {

                            $html .= "{
                                name: " . json_encode($this->legend[$key]) . ",
                                type: 'bar',
                                stack: '总量',
                                label: {
                                normal: {
                                    show: true,
                                        position: 'insideRight'
                                    }
                            },
                                data: " . json_encode($value) . "
                            },
                        ]";
                        }
                   $html .= " };";
                break;
            case 'barLabel3':
                $html .= "option = {
                title : {
                    text: '某地区蒸发量和降水量',
                    subtext: '纯属虚构'
                },
                            tooltip : {
                                trigger: 'axis'
                },
                            legend: {
                                data:".json_encode($this->legend)."
                },
                            toolbox: {
                                show : true,
                    feature : {
                                    dataView : {show: true, readOnly: false},
                                    magicType : {show: true, type: ['line', 'bar']},
                                    restore : {show: true},
                                    saveAsImage : {show: true}
                                }
                },
                            calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : ".json_encode($this->xAxis)."
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'".json_encode($this->legend[0])."',
                        type:'bar',
                        data:".json_encode($this->series[0]).",
                        markLine : {
                        data : [
                                {type : 'average', name: '平均值'}
                            ]
                        }
                    },
                    {
                        name:'".json_encode($this->legend[1])."',
                        type:'bar',
                        data:".json_encode($this->series[1]).",
                        markLine : {
                        data : [
                                {type : 'average', name : '平均值'}
                            ]
                        }
                    }
                ]
            };";

                break;
        }
        $html.= $this->end();
//        var_dump($html);
        return $html;
    }

    public function showInfo()
    {
//        var_dump($this->legend);
//        var_dump($this->xAxis);
//        var_dump($this->yAxis);
        var_dump($this->series);
//        var_dump($this->percent);
    }
}