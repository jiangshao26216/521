<?php
/*
 * @Author : 元圣(ZR)
 * @Url : www.ysdhw.cc
 * @QQ : 3267972560
 * @Autograph : 元于梦想，圣于努力！
"Zero"in dreams，"Resplendence" on strive！
 */
include("./YS/yuansheng.php");  //引入核心文件

//加载前端
$mod = isset($_GET['mod'])?$_GET['mod']:'index';
$loadfile = Template::load($mod);
include $loadfile;