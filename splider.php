<?php
/*a
Author:LiuZX
Date:2016-11-24
version:1.0
*/
function splider($lotteryid,$starttime,$endtime){
	$url ="http://www.syx201.net/game_bonuscode.shtml?lotteryid=".$lotteryid."&starttime=".$starttime."&endtime=".$endtime;
	$pattern='/^http:\/\/.*\/(.*)/';
	preg_match_all($pattern,$url,$get);
	$ua="Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.22 Safari/537.36 SE 2.X MetaSr 1.0";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"GET /".$get[1][0]." HTTP/1.1",
		"Host: www.syx201.net",
		"Connection: keep-alive",
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
		"Upgrade-Insecure-Requests: 1",
		"User-Agent: ".$ua,
		"Accept-Encoding: gzip, deflate, sdch",
		"Accept-Language: zh-CN,zh;q=0.8",
		'Cookie: __nxquid=RqnLXwAAAAC2uK/1e5fDAQ==-2120014; __nxqsid=14798736000014; CGISESSID=aeeg1abf04734bvlper5cteb12; _sessionHandler=2c7d6aef7057ed297549882741a431c583cc8aa1; __lc.visitor_id.8319881=S1479874139.744ebee15d; lc_window_state=minimized; __zlcmid=dkg1Ot2G6fIqRg'
	));
	curl_setopt($ch, CURLOPT_USERAGENT,$ua);
	curl_setopt($ch, CURLOPT_REFERER,"https://www.baidu.com/s?word=%E7%9F%A5%E4%B9%8E&tn=sitehao123&ie=utf-8&ssl_sample=normal&f=3&rsp=0");
	curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT,120);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result === false) {
		return null;
	} else {
		return $result;
	}
}
function writefile($path,$data){
	if ($path == 'tmpfile'){
		$f='w';
	}else{
		$f='a';
	}
	$myfile = fopen($path, $f) or die("Unable to write file!");
	fwrite($myfile, $data);
	fclose($myfile);
	return $path;
}
function get_data($data){
	$file = fopen($data, "r") or exit("Unable to open file!");
	$output="";
	while(!feof($file))
	{
	 $line=fgets($file);
	 if (preg_match_all("/.*?<tr.*?class=\"lostcolor\">.*?/",$line,$attr)){
		$goal=array();
		$title="";
			while(! preg_match_all('/.*?<td.*?class="wdh".*?align="center">.*?<div.*?class="aball03">.*?<span class="lost">.*?\d+.*?<\/span>.*?<\/div>.*?<\/td>/',$line,$attr)){
			 if (preg_match_all('/<td.*?id="title".*?>(.*)<\/td>/',$line,$match)){
				$title=trim($match[1][0]);
			 }
			 if (preg_match_all('/.*?(\d+).*<\/div>.*<\/td>/',$line,$match)){
				array_push($goal,trim($match[1][0]));
			 }
			 $line=fgets($file);
			}
		 $output.="$title ".join(" ",$goal)."\n";
		 unset($goal);
	 }
	}
	return $output;
}
/////main
if ($argc <4){
	die ("Usage:php $argv[0] lotteryid starttime endtime");
}
$lotteryid=$argv[1];
$starttime=$argv[2];
$endtime=$argv[3];
if (!preg_match_all('/\d{4}-\d{2}-\d{2}/',$starttime,$match) or !preg_match_all('/\d{4}-\d{2}-\d{2}/',$endtime,$match)){
	die("pls input correct format:\n Example:2016-05-09");
}
$timestamp=time();
while(1){
		$string_html=splider($lotteryid,$starttime,$starttime);
		if ($string_html != null){
			writefile($timestamp,get_data(writefile("tmpfile",$string_html)));
			echo "$starttime capture done\n";
		}else{
			echo "Capture Web Fail,Pls contact QQ:178216111 \n";
		}
		$starttime=date('Y-m-d',strtotime('+1 day',strtotime($starttime)));
		if ($starttime > $endtime){
			unlink('tmpfile');
			echo "Orz ........Finished\n";
			exit;
		}
}
?>
