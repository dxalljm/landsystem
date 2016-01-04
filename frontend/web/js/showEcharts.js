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
	    	    tooltip : {
	    	        trigger: 'axis',
	    	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	    	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
	    	        },
	    	        formatter: function (params){
	    	            return params[0].name + '<br/>'
	    	                   + params[0].seriesName + ' : ' + params[0].value + '<br/>'
	    	                   + params[1].seriesName + ' : ' + (params[1].value + params[0].value);
	    	        }
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

function showBar(divID,legenddata,xdata)
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
	    	    title : {
//	    	        text: '某地区蒸发量和降水量',
//	    	        subtext: '纯属虚构'
	    	    },
	    	    tooltip : {
	    	        trigger: 'axis'
	    	    },
	    	    legend: {
	    	        data:legenddata
	    	    },
	    	    toolbox: {
	    	        show : true,
//	    	        feature : {
//	    	            mark : {show: false},
//	    	            dataView : {show: false, readOnly: false},
//	    	            magicType : {show: false, type: ['line', 'bar']},
//	    	            restore : {show: false},
//	    	            saveAsImage : {show: false}
//	    	        }
	    	    },
	    	    calculable : true,
	    	    xAxis : [
	    	        {
	    	            type : 'category',
	    	            data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
	    	        }
	    	    ],
	    	    yAxis : [
	    	        {
	    	            type : 'value'
	    	        }
	    	    ],
	    	    series : [
	    	        {
	    	            name:'蒸发量',
	    	            type:'bar',
	    	            data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3],
	    	            markPoint : {
	    	                data : [
	    	                    {type : 'max', name: '最大值'},
	    	                    {type : 'min', name: '最小值'}
	    	                ]
	    	            },
	    	            markLine : {
	    	                data : [
	    	                    {type : 'average', name: '平均值'}
	    	                ]
	    	            }
	    	        },
	    	        {
	    	            name:'降水量',
	    	            type:'bar',
	    	            data:[2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3],
	    	            markPoint : {
	    	                data : [
	    	                    {name : '年最高', value : 182.2, xAxis: 7, yAxis: 183, symbolSize:18},
	    	                    {name : '年最低', value : 2.3, xAxis: 11, yAxis: 3}
	    	                ]
	    	            },
	    	            markLine : {
	    	                data : [
	    	                    {type : 'average', name : '平均值'}
	    	                ]
	    	            }
	    	        }
	    	    ]
	    	};
	    	                    

	    // 为echarts对象加载数据 
	    myChart.setOption(option); 
	  }
	);    
}