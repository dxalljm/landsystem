<?php
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2018/11/13
 * Time: 19:06
 */

namespace frontend\helpers;


class Tab
{
    private function begin()
    {
        $html = "<script type=\"text/javascript\">";
//            $(document).ready(function () ".addslashes('{');
        return $html;
    }
    private function end()
    {
//        $html = addslashes('}')."</script>";
        $html = "</script>";
        return $html;
    }

    public function createTab($action,$options)
    {
        $html = $this->begin();
        foreach ($options as $check) {
            $html .= "$('#".$check."').click(function () {
                $.session.set('".$action."', '".$check."');";
            foreach ($options as $name) {
                if($check == $name) {
                    $html .= "$('#" . $name . "').attr('class','active');";
                    $html .= "$('#".$name."').attr('aria-expanded',true);";
                    $html .= "$('#".$name."list').attr('class','tab-pane active');";
                } else {
                    $html .= "$('#" . $name . "').attr('class','');";
                    $html .= "$('#".$name."').attr('aria-expanded',false);";
                    $html .= "$('#".$name."list').attr('class','tab-pane');";
                }
            }
            $html .= "});";
        }
        foreach ($options as $if) {
            $html .= "if($.session.get('".$action."') == '".$if."') {";
            foreach ($options as $name) {
                if($if == $name) {
                    $html .= "$('#" . $name . "').attr('class','active');";
                    $html .= "$('#".$name."').attr('aria-expanded',true);";
                    $html .= "$('#".$name."list').attr('class','tab-pane active');";
                } else {
                    $html .= "$('#" . $name . "').attr('class','');";
                    $html .= "$('#".$name."').attr('aria-expanded',false);";
                    $html .= "$('#".$name."list').attr('class','tab-pane');";
                }
            }
            $html .= "}";
        }
        $html .= $this->end();
        return $html;
    }

    public function test($options)
    {
        $html = "<script type=\"text/javascript\">";
        foreach ($options as $check) {
            $html .= "$('#".$check."').click(function () {";
            $html .= "alert('".$check."');";
            $html .= "});";
        }
        $html .= "</script>";
        return $html;
    }
}