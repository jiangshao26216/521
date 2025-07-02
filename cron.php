<?php
include("./YS/yuansheng.php");  //引入核心文件
$zr=isset($_GET['zr'])?daddslashes($_GET['zr']):null;
switch($zr){
//采集系统
case 'Collect':
    $cronkey=daddslashes($_GET['cronkey']);
    if ($conf['collect_open']==0) {
    exit('{"code":-1,"msg":"采集系统未启用！"}');
    }
    if (!$cronkey) {
    exit('{"code":-1,"msg":"监控秘钥不能为空！"}');
    } else if ($cronkey!=$conf['cronkey']) {
    exit('{"code":-1,"msg":"监控秘钥错误！"}');
    }
    $task=$DB->get_row("SELECT * FROM zrdao_collect_task WHERE type='0' limit 1");
    if ($task) {
    $rule=$DB->get_row("SELECT * FROM zrdao_collect_rule WHERE type='1' and id='".$task['RuleId']."' limit 1");
    $url=$task['url'];
    $url=file_get_contents($url);
    //标题
    preg_match('/'.$rule['title'].'/si', $url, $title);
    //文章内容
	preg_match_all("/".$rule['content']."/sS",$url,$content);
	//站点关键词
	preg_match("/<meta name=\"keywords\" content=\"(.*?)\"/sS",$url,$keywords);
	//站点描述
	preg_match("/<meta name=\"description\" content=\"(.*?)\"/sS",$url,$description);
	$content = str_replace(array("\r\n", "\r", "\n","\t"), "", $content[1][0]);
	if(!$title[1]){
    $DB->query("update zrdao_collect_task set type='2' where id='".$task['id']."'");
	exit('{"code":-1,"msg":"获取异常，请检查规则"}');
	}
	//采集基础数据
	if ($conf['collect_post_open']==1) {
	$img=$conf['collect_post_url'];
	} else {
	$img='';
	}
	if ($task['TaskType']==3) { //全部采集
    $post = "insert into zrdao_post (user,title,keywords,description,content,sortid,view,love,tui,img,type,time) values('采集','".daddslashes($title[1])."','".daddslashes($keywords[1])."','".daddslashes($description[1])."','".daddslashes($content)."','".$task['SiteSortId']."','0','0','0','$img','1','$ZR_time')";
    $site = "insert into zrdao_site (title,keywords,description,qq,sortid,url,user,time,view,moon,day,love,kuai,tui,type)  values('".daddslashes($title[1])."','".daddslashes($keywords[1])."','".daddslashes($description[1])."','".$conf['kfqq']."','".$task['SiteSortId']."','".$task['url']."','采集','$ZR_time','0','0','0','0','0','0','1')";
	} else if ($task['TaskType']==2) { //文章
    $post = "insert into zrdao_post (user,title,keywords,description,content,sortid,view,love,tui,img,type,time) values('采集','".daddslashes($title[1])."','".daddslashes($keywords[1])."','".daddslashes($description[1])."','".daddslashes($content)."','".$task['SiteSortId']."','0','0','0','$img','1','$ZR_time')";
    $site='';
	} else if ($task['TaskType']==1) { //站点
    $post='';
    $site = "insert into zrdao_site (title,keywords,description,qq,sortid,url,user,time,view,moon,day,love,kuai,tui,type)  values('".daddslashes($title[1])."','".daddslashes($keywords[1])."','".daddslashes($description[1])."','".$conf['kfqq']."','".$task['SiteSortId']."','".$task['url']."','采集','$ZR_time','0','0','0','0','0','0','1')";
	}
	if ($post or $site) {
    $DB->query($post);
    $DB->query($site);
    $DB->query("update zrdao_collect_task set type='1' where id='".$task['id']."'");
    exit('{"code":0,"msg":"采集成功！"}');
	} else {
    $DB->query("update zrdao_collect_task set type='0' where id='".$task['id']."'");
    exit('{"code":-1,"msg":"采集错误！"}');
	}
    } else {
    exit('{"code":-1,"msg":"任务池中没任务了..."}');
    }
break;
default:
    exit('{"code":-4,"msg":"元圣导航系统监控端."}');
break;
}