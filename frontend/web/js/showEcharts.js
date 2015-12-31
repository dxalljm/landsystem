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