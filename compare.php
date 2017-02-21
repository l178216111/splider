<?php
/*a
Author:LiuZX
Date:2016-11-24
version:1.0
*/
function popdata($type,$filepath){
	$output=array();
	$file = fopen($filepath, "r") or exit("Unable to open file:$filepath!");
	while(!feof($file)){
		$line=trim(fgets($file));
		if ($line ==""){
			continue;
		}
		$line=str_replace("\n","",$line);
		$data=preg_split('/\s+/',$line);
		if ($type =='data'){
			array_shift($data);
		}
		$data=array_map("init",$data);
		array_push($output,$data);
	}
	return $output;
}
function init($v){
	return (int)$v;
}
function compare($array1,$array2){
	sort($array1);
	sort($array2);
	if($array1==$array2){
#		print_r($array1);
#		print_r($array2);
		return true;
	}else{
		return false;
	};
}
function integrate($data){
	array_push($data,9);
	$array=array();
	$last_string=9;
	$index=count($data)-1;
	for($i=0;$i<=$index;$i++){
			if (isset($array[$data[$i]])){
				
			}else{
				$array[$data[$i]]=array();
			}
			if(isset($count[$data[$i]])){
				
			}else{
				$count[$data[$i]]=1;
			}
			if ($data[$i]==$last_string){
				$count[$last_string]+=1;
			}else{
				if(isset($count[$last_string])){
					array_push($array[$last_string],$count[$last_string]);
					unset($count[$last_string]);
				}
			}
			$last_string=$data[$i];
	}
	return $array;
}
/////main
if ($argc <3){
	die ("Usage:php $argv[0] data plan");
}
$datapath=$argv[1];
$plahpath=$argv[2];
$data=popdata('data',$datapath);
$plan=popdata('plan',$plahpath);
$result=array();
foreach($data as $goal){
	$bingo=false;
	foreach($plan as $origin){
		if (compare($goal,$origin)==true){
	#		print_r($origin);
			$bingo=true;
			break;
		}
	}
	if ($bingo == true){
		array_push($result,1);
	}else{
		array_push($result,0);
	}
}
#print_r($result);
$result=integrate($result);
#print_r($result);
$no_win=array();
echo "############################################\n";
echo "Data:$datapath		Plan:$plahpath\n";
echo "############################################\n";
if (isset($result[0])){
	foreach($result[0] as $string){
		if (isset($no_win[$string])){
			$no_win[$string]+=1;
		}else{
			$no_win[$string]=1;
		}
	}
	ksort($no_win);
	print_r($no_win);
}else{
	echo "All Win\n";
}



