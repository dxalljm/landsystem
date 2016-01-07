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
		    	    	x:40,
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
	    	                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value;
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

function showBar(divID,legenddata,xdata,series,dw)
{
	var dwarr = dw.split(',');
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
	    	    	x:30,
	    	    	y:30,
	    	    	x2:15,
	    	    	y2:30,
	    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
//	    	        formatter: function (params,ticket,callback) {
//	    	            console.log(params)
//	    	            var res = params[0].name;
//	    	            var s = new Array();
//	    	            for (var i = 0, l = params.length; i < l; i++) {
//	    	                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value;
//	    	                
//	    	            }
//	    	            
//	    	            return res;
//	    	        }
	    	        formatter: '{b0}<br/>{a0}:{c0}'+dwarr[0]+'<br/>{a1}:{c1}' + dwarr[1]
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
	    	    	x:30,
	    	    	y:30,
	    	    	x2:15,
	    	    	y2:30,
	    	    },
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	    	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
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