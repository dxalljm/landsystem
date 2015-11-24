
function showPie(data,divID,title)
{
	$(function () {
        $('#'+divID ).highcharts({
        	chart: {
                 plotBorderWidth: null,
                 plotShadow: false
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


