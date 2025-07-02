<?php 
header("Content-Type:text/html;charset=utf-8");
$web=trim(@$_GET['wz']);
if($web==""){
	echo '<script>alert("非法操作！");location.href="/"</script>';
	exit;
}
require ('./YS/yuansheng.php');
?> 
<!DOCTYPE html>
<html>
	<head>		
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0" />

</head>   
<body> <br><br><br> 
<p><strong>以下是您提交百度推送的反馈信息：</strong></p><br><br>
<p>
<?php
$urls[]=$web;
$bdapi=$conf['baidu_token'];
$api = $bdapi;
$ch = curl_init();
$options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;
?>
</p><br><br><br><br><br>
<br>
本次推送链接为：
<?php 
$zd = $web;
echo $zd;
?>
</body>
</html>