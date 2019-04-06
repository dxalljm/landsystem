<?php 
/** 
  * PLChart - PHP图表生成工具 - Create charts in PHP 
  * 
  * ====================================================================   
  *    Filename :    plchart.php   
  *    Summary:      PLChart图表类文件 - PLChart class file 
  *    Author:       zeroleonhart ( msn:zeroleonhart@hotmail.com ) 
  *    Version:      0.060811 
  *    Copyright (c)2006 zeroleonhart@hotmail.com 
  * ==================================================================== 
  * 
  * 
 */

class plchart 
{ 
 // ============================ 参数定义 - define references ============================ 

// 图表变量 - chart variable 
var $chart; 

// 图表类型 - chart type 
var $type; 

// 3D效果 - 3D effect 
var $d3d; 

// 图表宽度 - chart width 
var $width; 

// 图表高度 - chart height 
var $height; 

// 图表质量 - chart quality 
var $quality; 

 // 图表背景色 - image backgroundcolor 
var $bgcolor = array(); 

 // 图表参数 - chart parameter 
var $param = array(); 

// 保存为文件的路径 - saving file directory 
var $savefile; 

// 图形参数 - graphics references 
var $graphics = array(); 

// 标题参数 - title references 
var $title = array(); 

// 注释参数 - description references 
var $desc = array(); 

// 图表颜色数组 - colors array in chart 
var $colors = array(); 

// 颜色定义 - define color 
var $colordefine = array( 
 "lightgray" => array(192, 192, 192),
 "darkgray" => array(144, 144, 144),

 "lightred" => array(255, 0, 0),
 "darkred" => array(128, 0, 0),

 "lightgreen" => array(0, 255, 0),
 "darkgreen" => array(0, 80, 0),

 "lightblue" => array(0, 0, 255),
 "darkblue" => array(0, 0, 80),

 "lightyellow" => array(255, 255, 0),
 "darkyellow" => array(200, 200, 50),

 "lightcyan" => array(0, 255, 255),
 "darkcyan" => array(0, 90, 90),
                ); 


// ============================ 设置函数 - setting functions ============================ 

/*
  * @todo  设置图像背景色 - set the backgroundcolor of the chart image 
  * 
  * @param  int $red       
  * @param  int $green     
  * @param  int $blue     
  * 
 */
function setbgcolor($red = 0, $green = 0, $blue = 0) 
{ 

 $this->bgcolor[0] = $red; 
 $this->bgcolor[1] = $green; 
 $this->bgcolor[2] = $blue; 

} 

/*
  * @todo  设置图像参数 - set the parameters of the chart image 
  * 
  * @param  string $type 
  * @param  int $d3d 
  * @param  int $width 
  * @param  int $height 
  * @param  int $quality 
  * @param  array $data 
  * @param  string $savefile 
  * 
 */
function setchartdata($type = "pie", $d3d = 1, $width = 400, $height = 400, $quality = 70, $data = array(), $savefile = '') 
{ 

 $this->type = $type;     // 图表类型 - type of the chart : pie, column, line 
 $this->d3d = $d3d;     // 3D效果 - 
 $this->width = $width;     // 图表宽度 
 $this->height = $height;     // 图表高度 
 $this->quality = $quality;     // 图表显示质量 
 $this->param = $data;     // 源数据数组 
 $this->savefile = $savefile;     // 保存文件路径 

} 



/*
  * @todo  设置图像标题 - set the title of the chart 
  * 
  * @param  string $tstring 
  * @param  float $tfontsize 
  * @param  float $tangle 
  * @param  int $tposx 
  * @param  int $tposy 
  * @param  string $tfontfile 
  * @param  int $tfred       
  * @param  int $tfgreen     
  * @param  int $tfblue     
  * 
 */
function settitle($tstring = '', $tfontsize = 20, $tangle = 0, $tposx = 10, $tposy = 20, $tfontfile = 'c:/windows/fonts/simhei.ttf', $tfred = 0, $tfgreen = 0, $tfblue = 0) 
{ 
 $this->title = array($tfontsize, $tangle, $tposx, $tposy, $tfontfile, $tstring, $tfred, $tfgreen, $tfblue); 

} 



/*
  * @todo  设置图像注释 - set the decsription of the chart 
  * 
  * @param  int $dposx 
  * @param  int $dposy 
  * @param  int $dwidth 
  * @param  int $dheight 
  * @param  int $dmargin 
  * @param  int $dsize 
  * @param  int $dangle 
  * @param  string $dfontfile 
  * @param  int $dfred   
  * @param  int $dfgreen     
  * @param  int $dfblue     
  * 
 */
function setdesc($dposx = 0, $dposy = 0, $dwidth = 100, $dheight = 300, $dmargin = 10, $dsize = 10, $dangle = 0, $dfontfile = 'c:/windows/fonts/simhei.ttf', $dfred = 0, $dfgreen = 0, $dfblue = 0) 
{ 
 $this->desc = array($dposx, $dposy, $dwidth, $dheight, $dmargin, $dsize, $dangle, $dfontfile, $dfred, $dfgreen, $dfblue); 

} 



/*
  * @todo  设置图形 - set the graphics of the chart 
  * 
  * @param  int $gstartx 
  * @param  int $gstarty 
  * @param  int $gwidth 
  * @param  int $gheight 
  * @param  int $gmargin       
  * @param  float $shadow     
  * 
 */
function setgraphics($gstartx = 0, $gstarty = 0, $gwidth = 100, $gheight = 100, $gmargin = 10, $shadow = 0.1) 
{ 
 $this->graphics = array($gstartx, $gstarty, $gwidth, $gheight, $gmargin, $shadow); 

} 



// ============================ 生成函数 - build functions ============================ 

/*
     * @todo  生成图表实例 - build chart object 
     * 
 */
 function buildchart() 
    { 
 // 图像头信息 - header of the image file 
 header("Content-type: image/jpeg"); 

 // 建立图像 - create image 
 $this->chart = imagecreatetruecolor($this->width, $this->height); 

 // 填充背景色 - set backgroundcolor 
 $bgc = imagecolorallocate($this->chart, $this->bgcolor[0], $this->bgcolor[1], $this->bgcolor[2]); 
        imagefill($this->chart, 0, 0, $bgc); 

 // 定义颜色 - allocate colors in the graphics                     
 foreach($this->colordefine as $key => $value) 
     { 
           $$key = imagecolorallocate($this->chart, $value[0], $value[1], $value[2]); 
 array_push($this->colors, $$key);    // 颜色变量入栈 - add color variables into array 

     } 

} 



/*
     * @todo  生成图表标题 - build title of the chart 
     * 
 */
 function buildtitle() 
    { 
 // 设置标题颜色 - set title color 
 $titlecolor = imagecolorallocate($this->chart, $this->title[6], $this->title[7], $this->title[8]); 
 // 写标题 - write title 
   imagettftext($this->chart, $this->title[0], $this->title[1], $this->title[2], $this->title[3], $titlecolor, $this->title[4], $this->title[5]); 

} 



/*
     * @todo  生成图表说明 - build description of the chart 
     * 
 */
 function builddesc() 
    { 
 // 注释位置变量 - image position variables 
 $dposx = $this->desc[0]; 
 $dposy = $this->desc[1];     // 注释起始坐标 - the start position of the description 
 $w = $this->desc[2];     // 注释部分宽度 - width of all description 
 $h = $this->desc[3];     // 注释部分高度 - height of all description 
 $num = count($this->param);     // 注释数量 - number of description lines 
 $perh = round($h / $num);      // 每行注释的平均高度 - height of each description line 
 $margin = $this->desc[4];     // 注释的间距 - margin between square and font 
 $dsize = $this->desc[5];     // 注释的字体大小 - font size of description 
 $dangle = $this->desc[6];     // 注释的字体角度 - font display angle of description 
 $dfontfile = $this->desc[7];     // 注释的字体文件 - font file of description 
 $dfcolor = imagecolorallocate($this->chart, $this->desc[8], $this->desc[9], $this->desc[10]);     // 注释的字体颜色 - font color of description 

   // 写注释 - write description 
 $dstring = array_keys($this->param); 
 for($i = 0; $i < $num; $i++) 
   { 
 // 矩形色块 - colorful square 
        imagefilledrectangle($this->chart, $dposx, $dposy, $dposx + $dsize, $dposy + $dsize, $this->colors[$i * 2]); 

 // 写文字 - write string 
     imagettftext($this->chart, $dsize, $dangle, $dposx + $dsize + $margin, $dposy + $dsize, $dfcolor, $dfontfile, $dstring[$i] . " - " . $this->param[$dstring[$i]]); 

 // 下移 - move down to write next description 
 $dposy += $perh; 

   } 
    } 


/*
     * @todo  生成图形 - build graphics of the chart 
     * 
     * @param  source $chart 
     * @param  int $d3d 
     * @param  array $param 
  * @param  array $colordefine 
     * 
 */
 function buildgraphics() 
    { 
 // 定义生成图形函数 - define make graphics function 
  // ====================   饼状图 - pie   ==================== 
 if($this->type == "pie") 
  {   
 // 取得图形位置 - get the positoin of the graphics 
 $posx = $this->graphics[0]; 
 $posy = $this->graphics[1]; 

 // 取得图形宽度和高度 - get width and height of the graphics 
 $w = $this->graphics[2]; 
 $h = $this->graphics[3]; 

 // 图形边距 - graphics margin 
 $margin = $this->graphics[4]; 

 // 3D阴影高度对于椭圆高度的比例 - percent of 3D effect shadow height as the height of the ellipse 
 $shadow = $this->graphics[5]; 

 // 图形位置变量 - image position variables 
 $centerx = round($posx + $w / 2 + $margin); 
 $centery = round($posy + $h / 2 + $margin);     // 椭圆中心坐标 - the center of the ellipse         

          // 数据处理 - data process 
 $total = array_sum($this->param);     // 取得总数 - get total 
 $percent = array(0);     // 保存比例 - save each percent 
 $temp = 0; 
 foreach($this->param as $v) 
          { 

 $temp += 360 * ($v / $total); 
 array_push($percent, $temp);     // 保存角度 - save angle 

          } 

 // 生成饼状图 - make pie chart   
    // 生成3D饼状图 - make 3D pie chart 
 if($this->d3d == 1)     
          { 
 // 3D阴影 - make 3D shadow 
 for ($j = ($centery * (1 + $shadow)); $j > $centery; $j--)   
                 { 
 for ($k = 0; $k < count($percent)-1; $k++) 
                     { 

                       imagefilledarc($this->chart, $centerx, $j, $w, $h, $percent[$k], $percent[$k + 1], $this->colors[$k * 2 + 1], IMG_ARC_NOFILL); 

               } 
                    } 

             } 
 // 生成平面饼状图 - make 2D pie chart     
 for ($i = 0; $i < count($percent)-1; $i++) 
       { 

     imagefilledarc($this->chart, $centerx, $centery, $w, $h, $percent[$i], $percent[$i + 1], $this->colors[$i * 2], IMG_ARC_PIE); 

       } 

  } 

 // ====================   柱状图 - column   ====================     
 elseif($this->type == "column") 
  { 
 // 取得图形位置 - get the positoin of the graphics 
 $posx = $this->graphics[0]; 
 $posy = $this->graphics[1]; 

 // 取得图形宽度和高度 - get width and height of the graphics 
 $w = $this->graphics[2]; 
 $h = $this->graphics[3]; 

 // 图形边距 - graphics margin 
 $margin = $this->graphics[4]; 

 // 3D阴影高度对于柱体宽度的比例 - percent of 3D effect shadow height as the width of the column 
 $shadow = $this->graphics[5]; 

 // 图形位置变量 - image position variables 
 $startx = round($posx + $margin); 
 $starty = round($posy + $h - $margin);     // 图形左下角坐标 - the left-bottom position of the graphics         

          // 数据处理 - data process 
 $maxvalue = max($this->param);    // 取得最大值 - get max value 
 $num = count($this->param);     // 取得条柱个数 - get number of columns 
 $multiple = floor(log10($maxvalue));     // 取得数值区间 - get data field 
 $field = floor($maxvalue / pow(10, $multiple)) + 1;     // 区间数 - number of unit fields 
 $fieldnumber = $field > 5 ? $field : 5;     // 数据区域数量 - number of data fields 
 $topvalue = $field * pow(10, $multiple);     // 图表最高点数值 - value of the top 
 $unitx = ($w - $margin * 2) / $num;     // 取得x单位长度 - get x unit length 
 $unity = ($h - $margin * 2) / $fieldnumber;     // 取得y单位长度 - get y unit length 
 $shadowheight = $unitx / 2 * $shadow;     // 阴影宽度 - shadow height 

    // 初始化坐标系 - initialize reference frame 
 if($this->d3d == 1)     // 3D效果 - 3D effect 
    { 
           imagefilledpolygon($this->chart, array($startx, $starty, $startx + $shadowheight, $starty - $shadowheight, $startx + $shadowheight, $posy - $shadowheight, $startx, $posy), 4, $this->colors[0]); 
           imageline($this->chart, $startx + $shadowheight, $starty - $shadowheight, $startx + $shadowheight, $posy - $shadowheight, $this->colors[1]);       
    } 
    imageline($this->chart, $startx, $starty, $posx + $w, $starty, $this->colors[1]);     // x 
    imageline($this->chart, $startx, $starty, $startx, $posy, $this->colors[1]);     // y 

    // 区间标识 - declare fields 
 for($i = 0; $i <= $fieldnumber; $i++)     
    { 
 // 区间标识线 - lines declaring fields 
      // 3D效果 - 3D effect 
 if($this->d3d == 1) 
            { 
             imageline($this->chart, $startx, $starty - $unity * $i, $startx + $shadowheight, $starty - $unity * $i - $shadowheight, $this->colors[1]); 
       imageline($this->chart, $startx + $shadowheight, $starty - $unity * $i - $shadowheight, $posx + $w + $shadowheight, $starty - $unity * $i - $shadowheight, $this->colors[1]); 
      } 
 // 2D - 2D 
 else
      { 
                   imageline($this->chart, $startx, $starty - $unity * $i, $posx + $w, $starty - $unity * $i, $this->colors[0]);       
      } 
 // 区间说明 - field description 
            imagettftext($this->chart, $this->desc[5], $this->desc[6], $posx, $starty - $unity * $i, $this->colors[0], $this->desc[7], $topvalue / $fieldnumber * $i);     
    } 

 // 生成条柱 - make columns 
 $paramkeys = array_keys($this->param); 
 for($j = 0; $j < $num; $j++) 
          { 

 $columnheight = ($h - $margin * 2) * ($this->param[$paramkeys[$j]] / $topvalue);     // 条柱高度 - column height 
 $columnx = $startx + $unitx / 4 + $unitx * $j;     // 条柱起点x坐标 - x coordinate of column 
              imagefilledrectangle($this->chart, $columnx, $starty - $columnheight, $columnx + $unitx / 2, $starty - 1, $this->colors[$j * 2]);     // 画条柱 - draw columns   

     // 3D效果 - 3D effect 
 if($this->d3d == 1) 
           { 
 // 轮廓线 - contour line 
                  imagerectangle($this->chart, $columnx, $starty - $columnheight, $columnx + $unitx / 2, $starty - 1, $this->colors[$j * 2 + 1]); 
 // 3D表面 - 3D top 
         imagefilledpolygon($this->chart, array($columnx, $starty - $columnheight, $columnx + $unitx / 2, $starty - $columnheight, $columnx + $unitx / 2 + $shadowheight, $starty - $columnheight - $shadowheight, $columnx + $shadowheight, $starty - $columnheight - $shadowheight), 4, $this->colors[$j * 2]); 
         imagepolygon($this->chart, array($columnx, $starty - $columnheight, $columnx + $unitx / 2, $starty - $columnheight, $columnx + $unitx / 2 + $shadowheight, $starty - $columnheight - $shadowheight, $columnx + $shadowheight, $starty - $columnheight - $shadowheight), 4, $this->colors[$j * 2 + 1]); 
 // 3D阴影 - 3D shadow 
         imagefilledpolygon($this->chart, array($columnx + $unitx / 2, $starty, $columnx + $unitx / 2 + $shadowheight, $starty - $shadowheight, $columnx + $unitx / 2 + $shadowheight, $starty - $columnheight - $shadowheight, $columnx + $unitx / 2, $starty - $columnheight), 4, $this->colors[$j * 2 + 1]); 
         imagepolygon($this->chart, array($columnx + $unitx / 2, $starty, $columnx + $unitx / 2 + $shadowheight, $starty - $shadowheight, $columnx + $unitx / 2 + $shadowheight, $starty - $columnheight - $shadowheight, $columnx + $unitx / 2, $starty - $columnheight), 4, $this->colors[$j * 2 + 1]); 
           } 
          } 

  } 

 // ====================   曲线图 - line   ==================== 
 else
  { 

 // 取得图形位置 - get the positoin of the graphics 
 $posx = $this->graphics[0]; 
 $posy = $this->graphics[1]; 

 // 取得图形宽度和高度 - get width and height of the graphics 
 $w = $this->graphics[2]; 
 $h = $this->graphics[3]; 

 // 图形边距 - graphics margin 
 $margin = $this->graphics[4]; 

 // 每个点的直径 - diameter of each point 
 $pointsize = $this->graphics[5] * 20; 

 // 图形位置变量 - image position variables 
 $startx = round($posx + $margin); 
 $starty = round($posy + $h - $margin);     // 图形左下角坐标 - the left-bottom position of the graphics         

          // 数据处理 - data process 
 $maxvalue = max($this->param);    // 取得最大值 - get max value 
 $minvalue = min($this->param);    // 取得最小值 - get min value 
 $num = count($this->param);     // 取得点个数 - get number of points 
 $fieldnumber = $num;     // 数据区域数量 - number of data fields 
 $fielddata = $maxvalue - $minvalue;     // 取得数据区间 - get data field 
 $unitdata = $fielddata / $fieldnumber;     // 取得单位区间数值 - get unit field data value 
 $unitx = ($w - $margin * 2) / $num;     // 取得x单位长度 - get x unit length 
 $unity = ($h - $margin * 2) / $fieldnumber;     // 取得y单位长度 - get y unit length 

    // 初始化坐标系 - initialize reference frame 
    imageline($this->chart, $startx, $starty, $posx + $w, $starty, $this->colors[1]);     // x 
    imageline($this->chart, $startx, $starty, $startx, $posy, $this->colors[1]);     // y 

    // 区间标识 - declare fields 
 for($i = 0; $i <= $fieldnumber; $i++)     
    { 
 // 标识线 - declaring fields line 
      imageline($this->chart, $startx, $starty - $unity * $i, $posx + $w, $starty - $unity * $i, $this->colors[0]);       
 // 区间说明 - field description 
            imagettftext($this->chart, $this->desc[5], $this->desc[6], $posx, $starty - $unity * $i, $this->colors[0], $this->desc[7], $minvalue + $unitdata * $i);     
    } 

 // 生成线条 - make line 
 $paramkeys = array_keys($this->param); 
 $loca = array();     // 保存轨迹的数组 - array to save locas 
 for($i = 0; $i < $num; $i++)     // 得到轨迹数组 - get loca array 
          { 
 // 点x坐标 - x coordinate of the point 
 $pointx = $startx + $unitx * $i; 
 // 点y坐标 - y coordinate of the point     
 $pointy = $starty - $unity * $fieldnumber * (($this->param[$paramkeys[$i]] - $minvalue) / $fielddata); 
 // 坐标数据入栈 - push coordinates into array     
 $loca[$i * 2] = $pointx; 
 $loca[$i * 2 + 1] = $pointy; 
 // 画点 - draw point 
     imagefilledellipse($this->chart, $pointx, $pointy, $pointsize, $pointsize, $this->colors[$i * 2]); 
          } 
 $linecolor = imagecolorallocate($this->chart, $this->title[6], $this->title[7], $this->title[8]);     // 定义线条颜色 - define line color 
 for($i = 0; $i < $num + 3; ) 
    { 
 // 画线条 - draw line 
     imageline($this->chart, $loca[$i], $loca[$i + 1], $loca[$i + 2], $loca[$i + 3], $linecolor); 
 $i += 2; 
    } 

  } 
    } 



/*
  * @todo  输出图像至浏览器或文件 - output the chart image to browser or file 
 */
 function outputchart() 
{ 

 // 建立图标 - build chart 
 $this->buildchart(); 
 // 写入图形 - build graphics 
 $this->buildgraphics(); 

 // 写入注释 - build description 
 $this->builddesc(); 

 // 写入标题 - build title 
 $this->buildtitle(); 

 // 输出图像 - flush image 
        imagejpeg($this->chart, $this->savefile, $this->quality); 
        imagedestroy($this->chart); 

    } 

} 

?>