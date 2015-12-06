
function showPie(data,divID,title)
{
	$(function () {
        $('#'+divID ).highcharts({
        	chart: {
                 plotBorderWidth: null,
                 plotShadow: false,
//                 width: 400,
//                 height: 200
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

function showColumn(resultname,resultvalue,divID,title,subtitle)
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
	           resultname
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Rainfall (mm)'
	            }
	        },
	        tooltip: {
	            headerFormat: '<span style="font-size:10px">{point.key}</span>',
	            pointFormat: '' +
	                '',
	            footerFormat: '<table><tbody><tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f} mm</b></td></tr></tbody></table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0
	            }
	        },
	        series: resultvalue
	    });
	});
}

