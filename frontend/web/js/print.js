var print = {

    lodop : null,

    element : null,

    setElement : function (ele) {
        this.element = ele;
    },

    getElement : function () {
        return this.element;
    },

    preview : function () {
        CreateOnePage();
        this.lodop.SET_PRINT_MODE("PRINT_PAGE_PERCENT","Auto-Width");
        LODOP.PREVIEW();
    },

    createOnPage : function () {
        this.lodop=getLodop();
        this.lodop.PRINT_INIT("打印控件功能演示_Lodop功能_整页缩放打印输出");
        strCenterStyle="<style/>form {text-align: center}</style>";

        this.lodop.ADD_PRINT_HTM("25mm","20mm","RightMargin:0mm","BottomMargin:9mm",strCenterStyle+document.getElementById(this.getElement()).innerHTML); //上下边距9mm，左右边距0mm
        this.lodop.SET_PRINT_STYLEA(0,"Horient",2);
        this.lodop.SET_PREVIEW_WINDOW(0,0,0,0,0,"");
    }
};
