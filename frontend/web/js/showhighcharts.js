
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
	            type: 'column',
	            
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
	                pointPadding: 0.10,
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
//	var pointFormatFunction = function () {
//		return '<span style="color:{series.color}">' + this.series.name + '</span>: <b>'+this.y+dw+'</b><br/>';
//	}

	// 所辖管理区农场数量统计数据, 百分比处理
	if (divID == 'statis-area') {
		var pointFormatFunction = function () {
			//$html = '<span style="color:{series.color}">' + this.series.name + '</span>: <b>'+this.y+dw+' 占比: (' + series.result[0]['percent'][this.x]  + '%)'+ '</b><br/>';
			$html = '<span style="color:{series.color}">' + this.series.name + '</span>: <b>'+this.y+dw+' 占比: (' + series.result[0]['percent'][this.x]  + '%)'+ '</b><br/><span style="color:{series.color}">' + '数量' + '</span>: <b>'+series.result[0]['rows'][this.x]+'户'+' 占比: (' + series.result[0]['rowpercent'][this.x]  + '%)'+ '</b><br/>';
			return $html;
		}
	}

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
				pointFormatter: pointFormatFunction,
	            shared: true
	        },
	        plotOptions: {
	        	column: {
                    allowPointSelect: true,
                    stacking: 'category',
                },
            },
	        labels: {                                                         
	            items: [{                                                     
	                html: pieTitle,                          
	                style: {                                                  
	                    left: '40px',                                         
	                    top: '80px',                                           
                                      
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
	            type: 'column',
//				borderColor: '#ccc',
//				borderWidth: 2,
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
	            },
				stackLabels: {
					enabled: true,
					style: {
						fontWeight: 'bold',
						color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
					}
				}
	        },

	        legend: {
				align: 'right',
				x: -70,
				verticalAlign: 'top',
				y: 20,
				floating: true,
				backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
				borderColor: '#909090',
				borderWidth: 2,
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
					borderColor: '#ccc',
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
