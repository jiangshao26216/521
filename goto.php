<?php
require ('./YS/yuansheng.php');
	$t_url=$_GET['url'];
	if(!empty($t_url)) {
		preg_match('/(http|https):\/\//',$t_url,$matches);
		 if($matches){
			$url=$t_url;
			$title='页面跳转中，请稍候……';
		 }else{
			preg_match('/\./i',$t_url,$matche);
			if($matche){
				$url='http://'.$t_url;
				$title='页面跳转中，请稍候……';
			}else{
				$url='../';
				$title='链接错误，正在返回……';
			}
		}
	}else{
		$title='链接错误，正在返回……';
		$url='../';
	}
include_once './muban/go/'.$conf['muban_go'].'.php';