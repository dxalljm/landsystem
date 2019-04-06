// BootStrap Modal 禁用ESC，以及遮罩层点击事件
if ($.fn.modal !== undefined) {
    $.fn.modal.Constructor.DEFAULTS.keyboard = false;
    $.fn.modal.Constructor.DEFAULTS.backdrop = 'static';
}

function createModel(url,id) {
	$.get(
	    'index.php',
	    {
	    	r: url,
	        id: id,

	    },
	    function (data) {
	        $('.modal-body').html(data);

	    }
	);
}


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



// 实例化租凭合同
lease = leaseEvent();
lease.close();


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
//    alert(old);
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
$(function () {
    $('[data-toggle="popover"]').each(function () {
        var element = $(this);
        var id = element.attr('help4');
        var txt = element.html();
        element.popover({
            trigger: 'manual',
            placement: 'bottom', //top, bottom, left or right
            title: txt,
            html: 'true',
            // content: ContentMethod(txt),

        }).on("mouseenter", function () {
            var _this = this;
            $(this).popover("show");
            $(this).siblings(".popover").on("mouseleave", function () {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function () {
            var _this = this;
            setTimeout(function () {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide")
                }
            }, 100);
        });
    });
});
$(function(){
    $('[data-toggle="popover"]').popover() //弹窗
        .on('show.bs.popover', function () { //展示时,关闭非当前所有弹窗
            $(this).parent().siblings().find('[data-toggle="popover"]').popover('hide');
        });
});
//给Body加一个Click监听事件
$(document).ready(function () {
$('body').on('click', function(event) {
    var target = $(event.target);
    if (!target.hasClass('popover') //弹窗内部点击不关闭
        && target.parent('.popover-content').length === 0
        && target.parent('.popover-title').length === 0
        && target.parent('.popover').length === 0
        && target.data("toggle") !== "popover") {
        //弹窗触发列不关闭，否则显示后隐藏
        $('[data-toggle="popover"]').popover('hide');
    }
});
});
function ContentMethod(txt) {
    return '<table class="table table-bordered"><tr><td>' + txt + '</td><td>BB</td><td>CC</td><td>DD</td></tr>' +
        '<tr><td>' + txt + '</td><td>BB</td><td>CC</td><td>DD</td></tr>' +
        '<tr><td>' + txt + '</td><td>BB</td><td>CC</td><td>DD</td></tr>'+
        '<tr><td>AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA</td><td>BB</td><td>CC</td><td>DD</td></tr></table>';
}
$(document).ready(function () {
    $("#helpdialog").dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        overlay: {
            backgroundColor: '#000',
            opacity: 0.5
        },
        buttons: {
            text: "取消",
            click: function() {
                $(this).dialog('close');
            }
        }
    });

});
function openHelpdialog() {
    $( "#helpdialog" ).dialog( "open" );
}