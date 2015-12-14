// BootStrap Modal 禁用ESC，以及遮罩层点击事件
if ($.fn.modal !== undefined) {
    $.fn.modal.Constructor.DEFAULTS.keyboard = false;
    $.fn.modal.Constructor.DEFAULTS.backdrop = 'static';
}

//Highcharts.theme = {
//    colors: ["#DDDF0D", "#7798BF", "#55BF3B", "#DF5353", "#aaeeee", "#ff0066", "#eeaaee",
//        "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
//    chart: {
//        backgroundColor: {
//            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//            stops: [
//                [0, 'rgb(96, 96, 96)'],
//                [1, 'rgb(16, 16, 16)']
//            ]
//        },
//        borderWidth: 0,
//        borderRadius: 0,
//        plotBackgroundColor: null,
//        plotShadow: false,
//        plotBorderWidth: 0
//    },
//    title: {
//        style: {
//            color: '#FFF',
//            font: '16px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
//        }
//    },
//    subtitle: {
//        style: {
//            color: '#DDD',
//            font: '12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
//        }
//    },
//    xAxis: {
//        gridLineWidth: 0,
//        lineColor: '#999',
//        tickColor: '#999',
//        labels: {
//            style: {
//                color: '#999',
//                fontWeight: 'bold'
//            }
//        },
//        title: {
//            style: {
//                color: '#AAA',
//                font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
//            }
//        }
//    },
//    yAxis: {
//        alternateGridColor: null,
//        minorTickInterval: null,
//        gridLineColor: 'rgba(255, 255, 255, .1)',
//        minorGridLineColor: 'rgba(255,255,255,0.07)',
//        lineWidth: 0,
//        tickWidth: 0,
//        labels: {
//            style: {
//                color: '#999',
//                fontWeight: 'bold'
//            }
//        },
//        title: {
//            style: {
//                color: '#AAA',
//                font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
//            }
//        }
//    },
//    legend: {
//        itemStyle: {
//            color: '#CCC'
//        },
//        itemHoverStyle: {
//            color: '#FFF'
//        },
//        itemHiddenStyle: {
//            color: '#333'
//        }
//    },
//    labels: {
//        style: {
//            color: '#CCC'
//        }
//    },
//    tooltip: {
//        backgroundColor: {
//            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//            stops: [
//                [0, 'rgba(96, 96, 96, .8)'],
//                [1, 'rgba(16, 16, 16, .8)']
//            ]
//        },
//        borderWidth: 0,
//        style: {
//            color: '#FFF'
//        }
//    },
//
//
//    plotOptions: {
//        series: {
//            nullColor: '#444444'
//        },
//        line: {
//            dataLabels: {
//                color: '#CCC'
//            },
//            marker: {
//                lineColor: '#333'
//            }
//        },
//        spline: {
//            marker: {
//                lineColor: '#333'
//            }
//        },
//        scatter: {
//            marker: {
//                lineColor: '#333'
//            }
//        },
//        candlestick: {
//            lineColor: 'white'
//        }
//    },
//
//    toolbar: {
//        itemStyle: {
//            color: '#CCC'
//        }
//    },
//
//    navigation: {
//        buttonOptions: {
//            symbolStroke: '#DDDDDD',
//            hoverSymbolStroke: '#FFFFFF',
//            theme: {
//                fill: {
//                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//                    stops: [
//                        [0.4, '#606060'],
//                        [0.6, '#333333']
//                    ]
//                },
//                stroke: '#000000'
//            }
//        }
//    },
//
//    // scroll charts
//    rangeSelector: {
//        buttonTheme: {
//            fill: {
//                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//                stops: [
//                    [0.4, '#888'],
//                    [0.6, '#555']
//                ]
//            },
//            stroke: '#000000',
//            style: {
//                color: '#CCC',
//                fontWeight: 'bold'
//            },
//            states: {
//                hover: {
//                    fill: {
//                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//                        stops: [
//                            [0.4, '#BBB'],
//                            [0.6, '#888']
//                        ]
//                    },
//                    stroke: '#000000',
//                    style: {
//                        color: 'white'
//                    }
//                },
//                select: {
//                    fill: {
//                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//                        stops: [
//                            [0.1, '#000'],
//                            [0.3, '#333']
//                        ]
//                    },
//                    stroke: '#000000',
//                    style: {
//                        color: 'yellow'
//                    }
//                }
//            }
//        },
//        inputStyle: {
//            backgroundColor: '#333',
//            color: 'silver'
//        },
//        labelStyle: {
//            color: 'silver'
//        }
//    },
//
//    navigator: {
//        handles: {
//            backgroundColor: '#666',
//            borderColor: '#AAA'
//        },
//        outlineColor: '#CCC',
//        maskFill: 'rgba(16, 16, 16, 0.5)',
//        series: {
//            color: '#7798BF',
//            lineColor: '#A6C7ED'
//        }
//    },
//
//    scrollbar: {
//        barBackgroundColor: {
//            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//            stops: [
//                [0.4, '#888'],
//                [0.6, '#555']
//            ]
//        },
//        barBorderColor: '#CCC',
//        buttonArrowColor: '#CCC',
//        buttonBackgroundColor: {
//            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//            stops: [
//                [0.4, '#888'],
//                [0.6, '#555']
//            ]
//        },
//        buttonBorderColor: '#CCC',
//        rifleColor: '#FFF',
//        trackBackgroundColor: {
//            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
//            stops: [
//                [0, '#000'],
//                [1, '#333']
//            ]
//        },
//        trackBorderColor: '#666'
//    },
//
//    // special colors for some of the demo examples
//    legendBackgroundColor: 'rgba(48, 48, 48, 0.8)',
//    background2: 'rgb(70, 70, 70)',
//    dataLabelsColor: '#444',
//    textColor: '#E0E0E0',
//    maskColor: 'rgba(255,255,255,0.3)'
//};
//
//// Apply the theme
//var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
//Highcharts.createElement('link', {
//	href: 'http://fonts.googleapis.com/css?family=Signika:400,700',
//	rel: 'stylesheet',
//	type: 'text/css'
//}, null, document.getElementsByTagName('head')[0]);
//
//// Add the background image to the container
//Highcharts.wrap(Highcharts.Chart.prototype, 'getContainer', function (proceed) {
//	proceed.call(this);
//	this.container.style.background = 'url(http://www.highcharts.com/samples/graphics/sand.png)';
//});
//
//
//Highcharts.theme = {
//	colors: ["#f45b5b", "#8085e9", "#8d4654", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
//		"#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
//	chart: {
//		backgroundColor: null,
//		style: {
//			fontFamily: "Signika, serif"
//		}
//	},
//	title: {
//		style: {
//			color: 'black',
//			fontSize: '16px',
//			fontWeight: 'bold'
//		}
//	},
//	subtitle: {
//		style: {
//			color: 'black'
//		}
//	},
//	tooltip: {
//		borderWidth: 0
//	},
//	legend: {
//		itemStyle: {
//			fontWeight: 'bold',
//			fontSize: '13px'
//		}
//	},
//	xAxis: {
//		labels: {
//			style: {
//				color: '#6e6e70'
//			}
//		}
//	},
//	yAxis: {
//		labels: {
//			style: {
//				color: '#6e6e70'
//			}
//		}
//	},
//	plotOptions: {
//		series: {
//			shadow: true
//		},
//		candlestick: {
//			lineColor: '#404048'
//		},
//		map: {
//			shadow: false
//		}
//	},
//
//	// Highstock specific
//	navigator: {
//		xAxis: {
//			gridLineColor: '#D0D0D8'
//		}
//	},
//	rangeSelector: {
//		buttonTheme: {
//			fill: 'white',
//			stroke: '#C0C0C8',
//			'stroke-width': 1,
//			states: {
//				select: {
//					fill: '#D0D0D8'
//				}
//			}
//		}
//	},
//	scrollbar: {
//		trackBorderColor: '#C0C0C8'
//	},
//
//	// General
//	background2: '#E0E0E8'
//	
//};
//
//// Apply the theme
//Highcharts.setOptions(Highcharts.theme);


function farmercontract(key) {
    // $('#createassign').click(function() {
    // alert(key);
    $.get(
        'index.php',         
        {
            r: 'farmer/farmercontract',
            id: key,

        },
        function (data) {
            $('.modal-body').html(data);

        }  
    );
    // });
}
function farmercreate(key) {
    // $('#createassign').click(function() {
    // alert(key);
    $.get(
        'index.php',         
        {
            r: 'farmer/farmercreate',
            id: key,
        },
        function (data) {
            $('.modal-body').html(data);

        }  
    );
    // });
}

function leaseindex(farmsid) {
    $.get(
        'index.php',         
        {
            r: 'lease/leaseindex',
            id: farmsid,
        },
        function (data) {
            $('.modal-body').html(data);

        }  
    );
    // });
}
function collectioncreate(farmsid,cardid) {
    $('#leasecreate-modal').modal("hide");
    $.get(
        'index.php',         
        {
            r: 'collection/collectioncreate',
            farms_id: farmsid,
            cardid:cardid,

        },
        function (data) {
            $('.modal-body').html(data);

        }  
    );
    // });
}


/**
 * 租凭事件
 *
 * @returns {{isCreateButton: boolean, create: Function, close: Function}}
 */
var leaseEvent = function () {

    return {

        /**
         * @var boolean 是否点击创建按钮
         */
        isCreateButton: false,

        /**
         * @var int 农场ID
         */
        farmsId: 0,

        /**
         * 点击添加租凭
         * @param int farmsId 农场ID
         * @param int theYear 当前选择的年份
         */
        create: function (farmsId, theYear) {

            _this = this;

            // 设置当前ID
            this.farmsId = farmsId;

            // 隐藏当前modal
            $.get('index.php', {r: 'lease/leasecreate', id: farmsId}, function (data) {

                // 点击创建按钮
                _this.isCreateButton = true;

                // 填充数据
                $('.modal-body').html(data);

            });

        },

        /**
         * 关闭租凭
         */
        close: function () {

            _this = this;

            // 关闭modal点击
            $('#close-leaseindex-modal').on("click", function () {

                // 重新请求数据，渲染视图
                if (_this.isCreateButton == true) {
                    leaseindex(_this.farmsId);
                } else {
                    $('#leaseindex-modal').modal('hide');
                }

                // 取消创建按钮事件
                _this.isCreateButton = false;

            });
        }
    }
}


// Apply the theme
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);

// 实例化租凭合同
lease = leaseEvent();
lease.close();

//var statis = function () {
//    return {
//        // 农场管理
//        farms : function () {
//            $.getJSON('index.php?r=farms/getfarmstatistics', function (data) {
//
//                
//                if (data.status == 1) {
//                    //$('#statis-farms').html(data.count);
//                    $(function () {
//                        $('#statis-farms').highcharts({
//                        	chart: {
//                                 plotBorderWidth: null,
//                                 plotShadow: false
//                            },
////                            subtitle: {
////                                text: "总数:10000",
////                                x: -100,
////                                y: 150
////                            },
//                            title: {
//                                text: '农场,共' + data.total + '个农场'
//                            },
//                            tooltip: {
//                        	    pointFormat: '{point.y}个农场 <br>{series.name}: <b>{point.percentage:.1f}%</b>'
//                            },
//                            plotOptions: {
//
//                                pie: {
//                                    allowPointSelect: true,
//                                    cursor: 'pointer',
//                                    dataLabels: {
//                                        enabled: true,
//                                        color: '#ffffff',
//                                        connectorColor: '#ffffff',
//                                        format: '<b>{point.name}</b><br>{point.y}个农场 <br> <b>占比</b>:{point.percentage:.1f}%'
//                                    }
//                                }
//                            },
//                            series: [{
//                                type: 'pie',
//                                name: '占比',
//                                data: data.result,
//                            }]
//                        });
//                    });
//                }
//            });
//        },
//        // 面积
//        area : function () {
//            $.getJSON('index.php?r=farms/getfarmarea', function (data) {
//                if (data.status == 1) {
//                	$(function () {
//                        $('#statis-area').highcharts({
//                        	chart: {
//                                 plotBorderWidth: null,
//                                 plotShadow: false
//                            },
////                            subtitle: {
////                                text: "总数:10000",
////                                x: -100,
////                                y: 50
////                            },
//                            title: {
//                                text: '面积,共' + data.total + '亩'
//                            },
//                            tooltip: {
//                        	    pointFormat: '{point.y}个农场 <br>{series.name}: <b>{point.percentage:.1f}%</b>'
//                            },
//                            plotOptions: {
//                                pie: {
//                                    allowPointSelect: true,
//                                    cursor: 'pointer',
//                                    dataLabels: {
//                                        enabled: true,
//                                        color: '#ffffff',
//                                        connectorColor: '#ffffff',
//                                        format: '<b>{point.name}</b><br>{point.y}亩 <br> <b>占比</b>:{point.percentage:.1f}%'
//                                    }
//                                }
//                            },
//                            series: [{
//                                type: 'pie',
//                                name: '占比',
//                                data: data.result,
//                            }]
//                        });
//                    });
//                }
//            });
//        },
//        // 实收金额
//        payment : function () {
//            $.getJSON('index.php?r=collection/getamounts', function (data) {
//                if (data.status == 1) {
//                    $('#statis-real').html(data.count.real);
//                    $('#statis-mounts').html(data.count.amounts);
//                }
//            });
//        }
//    }
//}

function jumpurl(action)
{
	
	$.get(
	        'index.php',         
	        {
	            r: action,
	            farms_id: $('#setFarmsid').val(),
	        },
	        function (data) {
	            $('body').html(data);

	        }  
	    );
}
//去掉数值中末尾无效的0
function cutZero(old){  
    //拷贝一份 返回去掉零的新串  
    newstr=old;  
    //循环变量 小数部分长度  
    var leng = old.length-old.indexOf(".")-1  
    //判断是否有效数  
    if(old.indexOf(".")>-1){  
        //循环小数部分  
        for(i=leng;i>0;i--){  
                //如果newstr末尾有0  
                if(newstr.lastIndexOf("0")>-1 && newstr.substr(newstr.length-1,1)==0){  
                    var k = newstr.lastIndexOf("0");  
                    //如果小数点后只有一个0 去掉小数点  
                    if(newstr.charAt(k-1)=="."){  
                        return  newstr.substring(0,k-1);  
                    }else{  
                    //否则 去掉一个0  
                        newstr=newstr.substring(0,k);  
                    }  
                }else{  
                //如果末尾没有0  
                    return newstr;  
                }  
            }  
        }  
        return old;  
  }  
