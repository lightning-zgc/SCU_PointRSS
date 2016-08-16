<?php
/*
绝对王权II-API
Made by lightning_zgc
Time 2016年1月2日01:48:34
Belongs to
Rjm_Hxb——软基萌核心部
Prismx——棱镜科技弱点实验室
Lightning——雷霆云实验室
****************************************
**研究项目，仅供学习参考，严禁非法使用**
****************************************
*/
set_time_limit(60);
header("content-Type: text/html; charset=UTF-8");
date_default_timezone_set('Etc/GMT-8');
require_once('rss.class.php');
@$user=@$_GET['user'];
@$pass=@$_GET['passwd'];
@$class=@$_GET['class'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://202.115.47.141/loginAction.do?zjh=$user&mm=$pass");
// curl_setopt($ch, CURLOPT_COOKIE, $cookies);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$ret = curl_exec($ch);
curl_close($ch);
preg_match_all('|Set-Cookie: (.*);|U', $ret, $arr);
$cookies = implode(';', $arr[1]);
if (strstr($ret,iconv('UTF-8', 'GBK','重新')) != FALSE)
{
    echo '密码或账号错误';
    exit;
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://202.115.47.141/gradeLnAllAction.do?type=ln&oper=fa");
curl_setopt($ch, CURLOPT_COOKIE, $cookies);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$ret = curl_exec($ch);
curl_close($ch);
preg_match_all("/fajhh\=(\d+?)\"/",$ret,$ma);
$num=$ma[1][0];


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://202.115.47.141/gradeLnAllAction.do?type=ln&oper=fainfo&fajhh=$num");
curl_setopt($ch, CURLOPT_COOKIE, $cookies);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT,100);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,0);
$ret = curl_exec($ch);
curl_close($ch);
$allg = $ret;
preg_match_all("/>(.[\S\s]+?)</",$allg,$am);
$am[1] = preg_replace("/\s/",'',$am[1]);
$am[1] = preg_replace("/&nbsp;/",'',$am[1]);
$am[1] = preg_replace("/<.+?>/",'',$am[1]);
$count = 0;

foreach ($am[1] as $key => $value) {
    if (($value or $value === '0') and $key > 54)$con[] = $value;
}
if($class) {
    $re = 10000000;
    foreach ($con as $key => $value) {
        if ($value == $class) $re = $key;
        if ($key <= $re + 6 and $key >= $re) {
        echo iconv('GBK','UTF-8',$value.' ');
        }
    }
    exit();
}


$RSS= new RSS("成绩",htmlentities('http://prismx.cc/jwc/mypoint.php'),"","");
foreach ($con as $key => $value) {
    $temp = mb_strstr($value,iconv('UTF-8','GBK','要求修读最低学分'));
        if ($count == 7) {
            if (!is_numeric($value) and !$temp) {
                $str .= $value;
                $count--;
                $value = '';
            }
        $RSS->AddItem((($con[$key-1] < 60 and is_numeric($con[$key-1]))?'(挂科)':'').iconv('GBK','UTF-8',$con[$key-5].':'.$con[$key-1]),'http://'.$_SERVER['SERVER_NAME'].$_SERVER['DOCUMENT_URI'].htmlentities('?'.$_SERVER['QUERY_STRING'].'&class='.iconv('GBK','UTF-8',$con[$key-7])),iconv('GBK','UTF-8',$str),gmdate(DATE_RFC822));
        $str = '';
        $count -= 7;
        // if (is_numeric($con[$key+1])) continue;
        }
        $count++;
        $str .= $value.' ';
    if ($temp)break;

}

// preg_match_all("/align\=\"center\"\>(.[\s\S]+?)\<\/td\>/",$ret,$ma1);

// preg_replace("/\s/","",$ma1);
// var_dump($ma1[1]);


// echo "<script>location.reload(1);</script>";
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "http://202.115.47.141/bxqcjcxAction.do");
// curl_setopt($ch, CURLOPT_COOKIE, $cookies);
// curl_setopt($ch, CURLOPT_HEADER, 0);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_TIMEOUT,100);
// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,0);
// $ret = curl_exec($ch);
// curl_close($ch);
// $thisg = $ret;


$RSS->Display();//输出RSS内容
?>
