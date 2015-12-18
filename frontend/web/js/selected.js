/**
 * 全选控件
 *
 * @author wubaiqing <wubaiqing@vip.qq.com>
 */
var selected = {
    /**
     * 全选
     */
    all: function(control, nodes) {
        $(control).click(function () {
            if ($(this).is(':checked') == true) {
                $(nodes).prop('checked', true);
            } else {
                $(nodes).prop('checked', false);
            }
        });
    },
    /**
     * 反选
     */
    invert: function(control, nodes) {
        $(control).click(function () {
            $(nodes).each(function () {
                if ($(this).is(':checked') == true) {
                    $(this).prop('checked', false);
                } else {
                    $(this).prop('checked', true);
                }
            });
        });
    },
    /**
     * 取消选择
     */
    revoke: function(control, nodes) {
        $(control).click(function () {
            $(nodes).prop('checked', false);
        });
    }
}