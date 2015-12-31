<<<<<<< HEAD
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
=======
function showShadow()
{
	option = {
		    title : {
		        text: '温度计式图表',
		        
		    },
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
		        data:['Acutal', 'Forecast']
		    },
		    toolbox: {
		        show : true,
		        feature : {
		            mark : {show: true},
		            dataView : {show: true, readOnly: false},
		            restore : {show: true},
		            saveAsImage : {show: true}
		        }
		    },
		    calculable : true,
		    xAxis : [
		        {
		            type : 'category',
		            data : ['Cosco','CMA','APL','OOCL','Wanhai','Zim']
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
		            name:'Acutal',
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
		            data:[260, 200, 220, 120, 100, 80]
		        },
		        {
		            name:'Forecast',
		            type:'bar',
		            stack: 'sum',
		            itemStyle: {
		                normal: {
		                    color: '#fff',
		                    barBorderColor: 'tomato',
		                    barBorderWidth: 6,
		                    barBorderRadius:0,
		                    label : {
		                        show: true, 
		                        position: 'top',
		                        formatter: function (params) {
		                            for (var i = 0, l = option.xAxis[0].data.length; i < l; i++) {
		                                if (option.xAxis[0].data[i] == params.name) {
		                                    return option.series[0].data[i] + params.value;
		                                }
		                            }
		                        },
		                        textStyle: {
		                            color: 'tomato'
		                        }
		                    }
		                }
		            },
		            data:[40, 80, 50, 80,80, 70]
		        }
		    ]
		};
>>>>>>> eeb6816296c9c9c844c87b0d98c64c9ef06f5015
}