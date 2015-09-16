<?php
define('IN_PHPBB', true);
define('BB_ROOT', './');
require(BB_ROOT ."common.php");
require(INC_DIR .'bbcode.php');
require(INC_DIR .'functions_post.php');
require_once(INC_DIR .'functions_admin.php');

// 
// Start session management 
// 
$user->session_start(); 
// 
// End session management 

if( !$userdata['session_logged_in'] ) { 
	header("Location: " . append_sid(BB_ROOT . "login.php?redirect=friends.php")); 
	exit; 
}

$page_title = 'VIP тарифы';

$template->assign_vars(array('PAGE_TITLE' => $page_title));
require(PAGE_HEADER);

$sql = "SELECT * FROM bb_vip_tarif";
if (!$result = DB()->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Ошибка при изменении', '', __LINE__, __FILE__, $sql);
}


while ( $row = DB()->sql_fetchrow($result) )
{
$date2 = $row['vip_tar_time'] / 86400;
if($date2 > 0 && $date2 <= 365){
$datec = $row['vip_tar_time'] / 86400;
}elseif($date2 < 0){
$datec = "Тариф Unlim не закончится";
}

				if($datec == 7){
					$vip_color = "#DD5500";
				}elseif($datec > 7 && $datec <= 30){
					$vip_color = "#DDAA00";
				}elseif($datec > 30 && $datec <= 60){
					$vip_color = "#AADD00";
				}elseif($datec > 60 && $datec <= 180){
					$vip_color = "#00AA00";
				}elseif($datec > 180 && $datec <= 365){
					$vip_color = "#99AA00";
				}elseif($date2 < 0){
					$vip_color = "#009999";
				}

	$template->assign_block_vars('tarrow', array(
		'NAME'			=> '<b style="color:'.$vip_color.';">'.$row['vip_tar_name'].'</b>',
		'PRICE'			=> $row['vip_tar_price'],
		'TIME'			=> $datec
	));
}
print_page('vip_list.tpl');
?>