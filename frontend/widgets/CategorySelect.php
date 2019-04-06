<?php

namespace frontend\widgets;

use yii\helpers\Html;
use app\models\Infrastructuretype;

/**
 * 分类的下拉框展示.
 *
 * @author wubaiqing <wubaiqing@vip.qq.com>
 *
 * @since 1.0
 */
class CategorySelect extends \yii\bootstrap\Widget
{
    /**
     * 表单对象属性.
     *
     * @var string
     */
    public $attribute;

    /**
     * 当前表中的对象
     *
     * @var Object
     */
    public $model;

    /**
     * wiki下拉框的数据,默认将获取全部的分类.
     *
     * @var type
     */
    public $categories;

    /**
     * 是否禁用父级分类.
     *
     * @var bool
     */
    public $isDisableParent;

    /**
     * html options.
     *
     * @var array
     */
    public $htmlOptions = array();

    /**
     * 子分类前面需要填充的长度.
     *
     * @var int
     */
    public $strPadLength;

    /**
     * 子分类需要填充的字符.
     *
     * @var string
     */
    public $strPadStr;

    /**
     * 是否显示终极分类.
     *
     * @var bool
     */
    public $isShowFinal;

    /**
     * 默认被选中值.
     */
    public $selectedValue;

    /**
     * 初始化下拉框的数据.
     *
     * @author jiayahong
     */
    public function init()
    {
        parent::init();

        if ($this->categories === null) {
            $this->categories = Infrastructuretype::getAllList();
        }

        if ($this->strPadLength === null) {
            $this->strPadLength = 4;
        }

        if (empty($this->strPadStr)) {
            $this->strPadStr = '-';
        }

        if ($this->isShowFinal === null) {
            $this->isShowFinal = true;
        }

        if ($this->isDisableParent === null) {
            $this->isDisableParent = false;
        }
    }

    /**
     * 生成下拉框的hmlt代码，并输出.
     */
    public function run()
    {
        if ($this->model == null || $this->attribute == null) {
            $options = '<option value="1">请选择分类</option>';
        } else {
            $selection = $this->selectedValue;// 默认选中

            $options = '<option value="1">请选择分类</option>'.$this->listOptions(0, 0, $selection);
        }
        echo Html::tag('select', $options, $this->htmlOptions);
    }

    /**
     * 根据category_id获取选择选项.
     *
     * @param int $category_id
     * @param int $str_pad_len 缩进的长度
     * @param int $selection   默认选中的值
     *
     * @return string
     */
    public function listOptions($category_id, $str_pad_len = 0, $selection = null)
    {
        $options = '';
        foreach ($this->categories as $category) {
            if ((int) $category['father_id'] !== (int) $category_id) {
                continue;
            }

            $attrs = [];

            $optionHtml = '<option value="%s"%s>%s</option>';

            //获取子分类的html.递归实现
            $childOptions = $this->listOptions($category['id'], $str_pad_len + $this->strPadLength, $selection);

            //如果禁用了父级分类的选择,并且子集分类的选项不存在，则不添加父级分类
            if ($this->isDisableParent && empty($childOptions)) {
                continue;
            }

            //设置默认选中的值
            if ((!$this->isDisableParent) && $selection == $category['id']) {
                $attrs['selected'] = 'selected';
            }

            $attrStr = '';
            //将属性数组，转换成字符串
            foreach ($attrs as $k => $v) {
                $attrStr .= " $k='$v'";
            }

            $options .= sprintf($optionHtml,
                $category['id'],
                $attrStr,
                str_pad($category['typename'], strlen($category['typename']) + $str_pad_len, $this->strPadStr, STR_PAD_LEFT));
            $options .= $childOptions;
        }

        return $options;
    }
}
