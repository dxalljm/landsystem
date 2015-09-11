// BootStrap Modal 禁用ESC，以及遮罩层点击事件
if ($.fn.modal !== undefined) {
    $.fn.modal.Constructor.DEFAULTS.keyboard = false;
    $.fn.modal.Constructor.DEFAULTS.backdrop = 'static';
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
            farmsid: farmsid,
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

var statis = function () {
    return {
        // 农场管理
        farms : function () {
            $.getJSON('index.php?r=farms/getfarmrows', function (data) {
                console.log(data);
                if (data.status == 1) {
                    $('#statis-farms').html(data.count);
                }
            });
        },
        // 面积
        area : function () {
            $.getJSON('index.php?r=farms/getfarmarea', function (data) {
                if (data.status == 1) {
                    $('#statis-area').html(data.count);
                }
            });
        },
        // 实收金额
        payment : function () {
            $.getJSON('index.php?r=collection/getamounts', function (data) {
                if (data.status == 1) {
                    $('#statis-real').html(data.count.real);
                    $('#statis-mounts').html(data.count.amounts);
                }
            });
        }
    }
}

