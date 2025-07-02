<?php
/*
 * 访问说明：
 * 域名/baidu.php/?cronkey=监控密钥
 * */
include("./YS/yuansheng.php");  //引入核心文件
try {
    # 判断
    $cronkey = daddslashes($_GET['cronkey']);
    if (!$cronkey) {
        exit('{"code":-1,"msg":"监控秘钥不能为空！"}');
    } else if ($cronkey !== $conf['cronkey']) {
        exit('{"code":-1,"msg":"监控秘钥错误！"}');
    }
    $site = [];
    foreach ($DB->query("SELECT * FROM zrdao_site WHERE type='1'") as $row) {
        $site[] = 'http://'.$domain.'/site_'.$row['id'].'.html';
    }
    $bdapi=$conf['baidu_token'];
    $api = $bdapi;
    $ch = curl_init();
    $options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $site),
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    exit($result);
    //exit('{"code":0,"msg":"推送成功'.$ZR_time.'！"}');
} catch (Exception $e) {
    exit('{"code":-1,"msg":"推送失败['.$e->getMessage().']-'.$ZR_time.'！"}');
}