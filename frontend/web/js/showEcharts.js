function showShadow(divID,legendata,xdata,seriesdata,dw)
{
	require.config({
		  paths: {
		    echarts: 'vendor/bower/echarts/build/dist/'
		  }
		});

	//使用
	require(
	  [
	    'echarts',
	    'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
	  ],
	  function (ec) {
	    // 基于准备好的dom，初始化echarts图表
	    var myChart = ec.init(document.getElementById(divID)); 
	    //设置数据
	    var option = {
//	    	    title : {
//	    	        text: '温度计式图表',
//	    	        subtext: 'From ExcelHome',
//	    	        sublink: 'http://e.weibo.com/1341556070/AizJXrAEa'
//	    	    },
	    		 grid : {
		    	    	x:70,
		    	    	y:30,
		    	    	x2:15,
		    	    	y2:30,
		    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	    	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
	    	        },
	    	        formatter: function (params,ticket,callback) {
	    	            console.log(params)
	    	            var res = params[0].name;
	    	            var s = new Array();
	    	            for (var i = 0, l = params.length; i < l; i++) {
	    	                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + dw;
	    	                s [i] = params[i].value;
	    	            }
	    	            var v = s[1]/s[0];
	    	            res += '<br/>' + '完成：' + v.toFixed(2)*100 + '%';
	    	            return res;
	    	        }
//	    	        formatter: '{b0}<br/>{a0}:{c0}'+dw+'<br/>{a1}:{c1}' + dw + '<br/>完成：' + '{c1}/{c0}*100' + '%'
	    	    },
	    	    legend: {
	    	        selectedMode:false,
	    	        data:legendata,
	    	    },
	    	    toolbox: {
	    	        show : true,
	    	        feature : {
	    	            mark : {show: false},
	    	            dataView : {show: false, readOnly: false},
	    	            restore : {show: false},
	    	            saveAsImage : {show: false}
	    	        }
	    	    },
	    	    calculable : true,
	    	    xAxis : [
	    	        {
	    	            type : 'category',
	    	            data : xdata,
	    	        }
	    	    ],
	    	    yAxis : [
	    	        {
	    	            type : 'value',
	    	            boundaryGap: [0, 0.1]
	    	        }
	    	    ],
	    	    series : seriesdata
	    	};

	    // 为echarts对象加载数据 
	    myChart.setOption(option); 
	  }
	);
}
function showShadowThermometer(divID,legendata,xdata,data1,data2,dw)
{
//	alert(obj2string(seriesdata));
	require.config({
		  paths: {
		    echarts: 'vendor/bower/echarts/build/dist/'
		  }
		});

	//使用
	require(
	  [
	    'echarts',
	    'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
	  ],
	  function (ec) {
	    // 基于准备好的dom，初始化echarts图表
	    var myChart = ec.init(document.getElementById(divID)); 
	    //设置数据
	    var option = {
//	    	    title : {
//	    	        text: '温度计式图表',
//	    	        subtext: 'From ExcelHome',
//	    	        sublink: 'http://e.weibo.com/1341556070/AizJXrAEa'
//	    	    },
	    		 grid : {
		    	    	x:50,
		    	    	y:30,
		    	    	x2:15,
		    	    	y2:30,
		    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	    	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
	    	        },
	    	        formatter: function (params,ticket,callback) {
	    	            console.log(params)
	    	            var res = params[0].name;
	    	            var s = new Array();
	    	            for (var i = 0, l = params.length; i < l; i++) {
//	    	                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + dw;
	    	                s [i] = params[i].value;
	    	            }
	    	            res += '<br/>'+params[0].seriesName+'：' + s[1];
	    	            alls = s[0] + s[1];
	    	            res += '<br/>'+params[1].seriesName+'：' + alls;
	    	            var v = s[1]/(s[0]+s[1]);
	    	            res += '<br/>' + '完成：' + v.toFixed(2)*100 + '%';
	    	            return res;
	    	        }
//	    	        formatter: '{b0}<br/>{a0}:{c0}'+dw+'<br/>{a1}:{c1}' + dw + '<br/>完成：' + '{c1}/{c0}*100' + '%'
	    	    },
	    	    legend: {
	    	        selectedMode:false,
	    	        data:legendata,
	    	    },
	    	    toolbox: {
	    	        show : true,
	    	        feature : {
	    	            mark : {show: false},
	    	            dataView : {show: false, readOnly: false},
	    	            restore : {show: false},
	    	            saveAsImage : {show: false}
	    	        }
	    	    },
	    	    calculable : true,
	    	    xAxis : [
	    	        {
	    	            type : 'category',
	    	            data : xdata,
	    	        }
	    	    ],
	    	    yAxis : [
	    	        {
	    	            type : 'value',
	    	            boundaryGap: [0, 0.1]
	    	        }
	    	    ],
	    	    series : seriesdata
	    	};

	    // 为echarts对象加载数据 
	    myChart.setOption(option); 
	  }
	);
}
function wdjShowEchart(divID,legendata,xdata,alldata,realdata,dw)
{
//	alert(obj2string(seriesdata));
	require.config({
		  paths: {
		    echarts: 'vendor/bower/echarts/build/dist/'
		  }
		});

	//使用
	require(
	  [
	    'echarts',
	    'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
	  ],
	  function (ec) {
	    // 基于准备好的dom，初始化echarts图表
	    var myChart = ec.init(document.getElementById(divID)); 
	    //设置数据
	    var option = {
//	    	    title : {
//	    	        text: '温度计式图表',
//	    	        subtext: 'From ExcelHome',
//	    	        sublink: 'http://e.weibo.com/1341556070/AizJXrAEa'
//	    	    },
	    		 grid : {
		    	    	x:50,
		    	    	y:30,
		    	    	x2:15,
		    	    	y2:30,
		    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	    	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
	    	        },
	    	        formatter: function (params,ticket,callback) {
	    	        	var row = eval(ticket); 
	    	            var res = params[0].name;
	    	            var s = new Array();
	    	            var n = new Array();
	    	            for (var i = 0, l = params.length; i < l; i++) {
//	    	                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + dw;
	    	                s [i] = params[i].value;
	    	            }
	    	            res += '<br/>'+params[1].seriesName+'：' + s[1] + dw;
	    	            alls = s[0]+s[1];
	    	            res += '<br/>'+params[0].seriesName+'：' + alls.toFixed(2) + dw;
	    	           
	    	            var v = s[1]/alls; 
//	    	            alert(v);
	    	            res += '<br/>' + '完成：' + v.toFixed(2)*100 + '%';
	    	            return res;
	    	        },
	    	    },
	    	    legend: {
	    	        selectedMode:false,
	    	        data:legendata,
	    	    },
	    	    toolbox: {
	    	        show : true,
	    	        feature : {
	    	            mark : {show: false},
	    	            dataView : {show: false, readOnly: false},
	    	            restore : {show: false},
	    	            saveAsImage : {show: false}
	    	        }
	    	    },
	    	    calculable : true,
	    	    xAxis : [
	    	        {
	    	            type : 'category',
	    	            data : xdata,
	    	        }
	    	    ],
	    	    yAxis : [
	    	        {
	    	            type : 'value',
	    	            boundaryGap: [0, 0.1]
	    	        }
	    	    ],
	    	    series : [
	    	              {
	    	                  name:legendata[0],
	    	                  type:'bar',
	    	                  stack: 'sum',
	    	                  barCategoryGap: '50%',
	    	                  itemStyle: {
	    	                      normal: {
	    	                          color: 'tomato',
	    	                          barBorderColor: 'tomato',
	    	                          barBorderWidth: 6,
	    	                          barBorderRadius:0,
	    	                          label : {
	    	                              show: true, position: 'insideTop'
	    	                          }
	    	                      }
	    	                  },
	    	                  data:realdata['count'],
	    	              },
	    	              {
	    	                  name:legendata[1],
	    	                  type:'bar',
	    	                  stack: 'sum',
	    	                  itemStyle: {
	    	                      normal: {
	    	                          color: '#fff',
	    	                          barBorderColor: 'tomato',
	    	                          barBorderWidth: 3,
	    	                          barBorderRadius:0,
	    	                          label : {
	    	                              show: false, 
	    	                              position: 'top',
	    	                              formatter: function (params) {
	    	                                  for (var i = 0, l = option.xAxis[0].data.length; i < l; i++) {
	    	                                      if (option.xAxis[0].data[i] == params.name) {
	    	                                    	  var d = option.series[0].data[i] + params.value; 
	    	                                          return d.toFixed(2);
	    	                                      }
	    	                                  }
	    	                              },
	    	                              textStyle: {
	    	                                  color: 'tomato'
	    	                              }
	    	                          }
	    	                      }
	    	                  },
	    	                  data:alldata['count']
	    	              }
	    	          ]
	    	};

	    // 为echarts对象加载数据 
	    myChart.setOption(option); 
	  }
	);
}
function wdjHuinong(divID,legendata,xdata,alldata,realdata,dw)
{
//	alert(obj2string(seriesdata));
	require.config({
		  paths: {
		    echarts: 'vendor/bower/echarts/build/dist/'
		  }
		});

	//使用
	require(
	  [
	    'echarts',
	    'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
	  ],
	  function (ec) {
	    // 基于准备好的dom，初始化echarts图表
	    var myChart = ec.init(document.getElementById(divID)); 
	    //设置数据
	    var option = {
//	    	    title : {
//	    	        text: '温度计式图表',
//	    	        subtext: 'From ExcelHome',
//	    	        sublink: 'http://e.weibo.com/1341556070/AizJXrAEa'
//	    	    },
	    		 grid : {
		    	    	x:50,
		    	    	y:30,
		    	    	x2:15,
		    	    	y2:30,
		    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	    	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
	    	        },
	    	        formatter: function (params,ticket,callback) {
	    	        	var row = eval(ticket); 
	    	            var res = params[0].name;
	    	            var s = new Array();
	    	            var n = new Array();
	    	            for (var i = 0, l = params.length; i < l; i++) {
//	    	                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + dw;
	    	                s [i] = params[i].value;
	    	            }
	    	            res += '<br/>'+params[1].seriesName+'：' + s[1] + dw;
	    	            alls = s[0]+s[1];
	    	            res += '<br/>'+params[0].seriesName+'：' + alls.toFixed(2) + dw;
	    	           
	    	            var v = realdata['count'][row]*1/alldata['count'][row]*1; 
//	    	            alert(v);
	    	            res += '<br/>' + '完成：' + v.toFixed(4)*100 + '%';
	    	            return res;
	    	        },
	    	    },
	    	    legend: {
	    	        selectedMode:false,
	    	        data:legendata,
	    	    },
	    	    toolbox: {
	    	        show : true,
	    	        feature : {
	    	            mark : {show: false},
	    	            dataView : {show: false, readOnly: false},
	    	            restore : {show: false},
	    	            saveAsImage : {show: false}
	    	        }
	    	    },
	    	    calculable : true,
	    	    xAxis : [
	    	        {
	    	            type : 'category',
	    	            data : xdata,
	    	        }
	    	    ],
	    	    yAxis : [
	    	        {
	    	            type : 'value',
	    	            boundaryGap: [0, 0.1]
	    	        }
	    	    ],
	    	    series : [
	    	              {
	    	                  name:legendata[0],
	    	                  type:'bar',
	    	                  stack: 'sum',
	    	                  barCategoryGap: '50%',
	    	                  itemStyle: {
	    	                      normal: {
	    	                          color: 'tomato',
	    	                          barBorderColor: 'tomato',
	    	                          barBorderWidth: 6,
	    	                          barBorderRadius:0,
	    	                          label : {
	    	                              show: true, position: 'insideTop'
	    	                          }
	    	                      }
	    	                  },
	    	                  data:realdata['sum'],
	    	              },
	    	              {
	    	                  name:legendata[1],
	    	                  type:'bar',
	    	                  stack: 'sum',
	    	                  itemStyle: {
	    	                      normal: {
	    	                          color: '#fff',
	    	                          barBorderColor: 'tomato',
	    	                          barBorderWidth: 3,
	    	                          barBorderRadius:0,
	    	                          label : {
	    	                              show: false, 
	    	                              position: 'top',
	    	                              formatter: function (params) {
	    	                                  for (var i = 0, l = option.xAxis[0].data.length; i < l; i++) {
	    	                                      if (option.xAxis[0].data[i] == params.name) {
	    	                                    	  var d = option.series[0].data[i] + params.value; 
	    	                                          return d.toFixed(2);
	    	                                      }
	    	                                  }
	    	                              },
	    	                              textStyle: {
	    	                                  color: 'tomato'
	    	                              }
	    	                          }
	    	                      }
	    	                  },
	    	                  data:alldata['sum'],
	    	              }
	    	          ]
	    	};

	    // 为echarts对象加载数据 
	    myChart.setOption(option); 
	  }
	);
}
function showBar(divID,legenddata,xdata,series,dw)
{
//	var dwarr = dw.split(',');
	require.config({
		  paths: {
		    echarts: 'vendor/bower/echarts/build/dist/'
		  }
		});

	//使用
	require(
	  [
	    'echarts',
	    'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
	  ],
	  function (ec) {
	    // 基于准备好的dom，初始化echarts图表
	    var myChart = ec.init(document.getElementById(divID)); 
	    //设置数据
	    var option = {
	    	    title : {
//	    	        text: '某地区蒸发量和降水量',
//	    	        subtext: '纯属虚构'
	    	    },
	    	    grid : {
	    	    	x:50,
	    	    	y:30,
	    	    	x2:15,
	    	    	y2:30,
	    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        formatter: function (params,ticket,callback) {
	    	        	var row = eval(ticket); 
	    	            console.log(params)
	    	            var res = params[0].name;
	    	            var percent = new Array();
	    	            for (var i = 0, l = params.length; i < l; i++) {
//	    	            	alert(i);
	    	            	percent[i] = params[i].series.percent;
//	    	            	alert(obj2string(params[i]));
	    	                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + dw[params[i].seriesName];
	    	                res += '<br/>' + '占比：' + percent[i][row] +'%';
	    	            }
	    	            
	    	            return res;
	    	        }
//	    	        formatter: '{b0}<br/>{a0}:{c0}'+dwarr[0]+'<br/>{a1}:{c1}' + dwarr[1]
	    	    },
	    	    legend: {
	    	        data:legenddata
	    	    },
	    	    toolbox: {
	    	        show : true,
	    	        feature : {
	    	        	dataZoom:{x:0,y:0},
//	    	            mark : {show: false},
//	    	            dataView : {show: false, readOnly: false},
//	    	            magicType : {show: false, type: ['line', 'bar']},
//	    	            restore : {show: false},
//	    	            saveAsImage : {show: false}
	    	        }
	    	    },
	    	    calculable : true,
	    	    xAxis : [
	    	        {
	    	            type : 'category',
	    	            data : xdata
	    	        }
	    	    ],
	    	    yAxis : [
	    	        {
	    	            type : 'value'
	    	        }
	    	    ],
	    	    series : series
	    	};
	    	                    

	    // 为echarts对象加载数据 
	    myChart.setOption(option); 
	  }
	);    
}

function showAllShadowProject(divID,legendData,xData,series,dw)
{
	require.config({
		  paths: {
		    echarts: 'vendor/bower/echarts/build/dist/'
		  }
		});

	//使用
	require(
	  [
	    'echarts',
	    'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
	  ],
	  function (ec) {
	    // 基于准备好的dom，初始化echarts图表
	    var myChart = ec.init(document.getElementById(divID)); 
	    //设置数据
	   var option = {
			   grid : {
	    	    	x:50,
	    	    	y:30,
	    	    	x2:15,
	    	    	y2:30,
	    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	    	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
	    	        },
	    	    	formatter: function (params,ticket,callback) {
	    	        	var row = eval(ticket); 
	    	            console.log(params)
	    	            var res = params[0].name;
	    	            for (var i = 0, l = params.length; i < l; i++) {
	    	                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + dw[params[i].name];
	    	            }
	    	            
	    	            return res;
	    	    	}
	    	    },
	    	    legend: {
	    	        data:legendData
	    	    },
//	    	    toolbox: {
//	    	        show : true,
//	    	        orient: 'vertical',
//	    	        x: 'right',
//	    	        y: 'center',
//	    	        feature : {
//	    	            mark : {show: true},
//	    	            dataView : {show: true, readOnly: false},
//	    	            magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
//	    	            restore : {show: true},
//	    	            saveAsImage : {show: true}
//	    	        }
//	    	    },
	    	    calculable : true,
	    	    xAxis : [
	    	        {
	    	            type : 'category',
	    	            data : xData
	    	        }
	    	    ],
	    	    yAxis : [
	    	        {
	    	            type : 'value'
	    	        }
	    	    ],
	    	    series : series
	    	};
	    	                    
	    // 为echarts对象加载数据 
	    myChart.setOption(option); 
	  }
	);    
}
function showAllShadow(divID,legendData,xData,series,dw)
{
	require.config({
		  paths: {
		    echarts: 'vendor/bower/echarts/build/dist/'
		  }
		});

	//使用
	require(
	  [
	    'echarts',
	    'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
	  ],
	  function (ec) {
	    // 基于准备好的dom，初始化echarts图表
	    var myChart = ec.init(document.getElementById(divID)); 
	    //设置数据
	   var option = {
			   grid : {
	    	    	x:50,
	    	    	y:30,
	    	    	x2:15,
	    	    	y2:30,
//	    	    	width:200,
	    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	    	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
	    	        },
	    	    	formatter: function (params,ticket,callback) {
	    	        	var row = eval(ticket); 
	    	            console.log(params)
	    	            var res = params[0].name;
	    	            for (var i = 0, l = params.length; i < l; i++) {
	    	            	if(dw instanceof Array) {
	    	            		res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + dw[row];
	    	            	} else
	    	            		res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + dw;
	    	            }
	    	            
	    	            return res;
	    	    	}
	    	    },
	    	    legend: {
	    	        data:legendData
	    	    },
//	    	    toolbox: {
//	    	        show : true,
//	    	        orient: 'vertical',
//	    	        x: 'right',
//	    	        y: 'center',
//	    	        feature : {
//	    	            mark : {show: true},
//	    	            dataView : {show: true, readOnly: false},
//	    	            magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
//	    	            restore : {show: true},
//	    	            saveAsImage : {show: true}
//	    	        }
//	    	    },
	    	    calculable : true,
	    	    xAxis : [
	    	        {
	    	            type : 'category',
	    	            data : xData
	    	        }
	    	    ],
	    	    yAxis : [
	    	        {
	    	            type : 'value'
	    	        }
	    	    ],
	    	    series : series
	    	};
	    	                    
	    // 为echarts对象加载数据 
	    myChart.setOption(option); 
	  }
	);    
}
//对象转字符串
function obj2string(o){ 
	 var r=[]; 
	 if(typeof o=="string"){ 
	  return "\""+o.replace(/([\'\"\\])/g,"\\$1").replace(/(\n)/g,"\\n").replace(/(\r)/g,"\\r").replace(/(\t)/g,"\\t")+"\""; 
	 } 
	 if(typeof o=="object"){ 
	  if(!o.sort){ 
	   for(var i in o){ 
	    r.push(i+":"+obj2string(o[i])); 
	   } 
	   if(!!document.all&&!/^\n?function\s*toString\(\)\s*\{\n?\s*\[native code\]\n?\s*\}\n?\s*$/.test(o.toString)){ 
	    r.push("toString:"+o.toString.toString()); 
	   } 
	   r="{"+r.join()+"}"; 
	  }else{ 
	   for(var i=0;i<o.length;i++){ 
	    r.push(obj2string(o[i])) 
	   } 
	   r="["+r.join()+"]"; 
	  } 
	  return r; 
	 } 
	 return o.toString(); 
	}
