<?
//определяем IP
if(empty($user_ip)){if (getenv('HTTP_X_FORWARDED_FOR')) {$user_ip=getenv('HTTP_X_FORWARDED_FOR'); } else{$user_ip=getenv('REMOTE_ADDR'); }}
else{$user_ip=getenv('REMOTE_ADDR'); }
//раскладываем IP на составные части
if(15 < strlen($user_ip)){list($user_ip,$user_ip1,$user_ip2,$user_ip3)=explode(", ", $user_ip);}
//выводим для программы толко первый IP
$ip = $user_ip;
//--------------------
foreach($ips as $res){
if($ip !== $res){
$result = "false";
}else{
$result = "true";
}
break;
}

echo $result.'<br /><br />';

//$ip = "213.172.16.201";
//функция, для выделение определенного количесво знаков из IP
function obrez ($ip_pass){
if ( ereg("([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)", $ip_pass, $array))
$array[3] = substr ( $array[3], 0, 3);
$ip_pass = "$array[1].$array[2].$array[3].$array[4]";
return $ip_pass;
}
//обрезаем IP до 213.172.1
$obrez_ip = obrez ($ip);

echo $obrez_ip.'<br /><br />';

?>
