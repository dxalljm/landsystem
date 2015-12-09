
function showPie(divID,title,data)
{
	$(function () {
        $('#'+divID ).highcharts({
        	chart: {
                 plotBorderWidth: null,
                 plotShadow: false,
                 width: 550
            },
//            subtitle: {
//                text: "总数:10000",
//                x: -100,
//                y: 150
//            },
            title: {
                text: title,
            },
            tooltip: {
        	    pointFormat: '{point.y} <br>{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {

                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#ffffff',
                        connectorColor: '#ffffff',
                        format: '<b>{point.name}</b><br>{point.y} <br> <b>占比</b>:{point.percentage:.1f}%'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '占比',
                data: data,
            }]
        });
    });
}

function showColumn(divID,title,categories,subtitle,series,ytitle,dw)
{
	$(function () {
	    $('#'+divID).highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: title
	        },
	        subtitle: {
	            text: subtitle
	        },
	        xAxis: {
	            categories: categories
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: ytitle
	            }
	        },
	        tooltip: {
	            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	                '<td style="padding:0"><b>{point.y:.1f} '+dw+'</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0
	            }
	        },
	        series: series.result
	    });
	});
}
//categories:各项目名称
function showCombination(divID,title,categories,pieTitle,series,dw)
{
	// 所辖管理区农场数量统计数据, 百分比处理
	if (divID == 'statis-farms') {
		series.result[0]['dataLabels']['formatter'] = function () {
			console.log(this.point.x);
			console.dir(series.result[0]['percent']);

			return this.point.y + '(' + '百分比' + ')';
		}
	}

	console.log(series.result[0]['dataLabels']['formatter']);

	$(function () {                                                               
	    $('#'+divID).highcharts({                                          
	        chart: {                                                          
	        },                                                                
	        title: {                                                          
	            text: title                                     
	        },                                                                
	        xAxis: {                                                          
	            categories: categories
	        },      

	        tooltip: {                                                        
	        	pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}'+dw+'</b><br/>',
	            shared: true                                                        
	        },    
	        plotOptions: {
	        	column: {
                    allowPointSelect: true,
                    stacking: 'category',
                }
            },
	        labels: {                                                         
	            items: [{                                                     
	                html: pieTitle,                          
	                style: {                                                  
	                    left: '40px',                                         
	                    top: '8px',                                           
                                      
	                }                                                         
	            }]                                                            
	        },                                                                
	        series: series.result 
	    });
	});                        
}
function showStacked(divID,title,categories,ytitle,series,dw)
{
	$(function () {
	    $('#'+divID).highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: title
	        },
	        xAxis: {
	            categories: categories
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: ytitle
	            }
	        },
	        legend: {
	            align: 'right',
	            x: -70,
	            verticalAlign: 'top',
	            y: 20,
	            floating: true,
	            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
	            borderColor: '#CCC',
	            borderWidth: 1,
	            shadow: false
	        },
	        tooltip: {
	            formatter: function() {
	                return '<b>'+ this.x +'</b><br/>'+
	                    this.series.name +': '+ this.y +'<br/>'+
	                    '占比: '+Highcharts.numberFormat(this.point.percentage, 2)+'%';
	            }
	        },
	        plotOptions: {
	            column: {
	                stacking: 'normal',
	                dataLabels: {
	                    enabled: true,
	                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
	                }
	            }
	        },
	            series: series.result
	    });
	});
}
