<?php
/*************************************************************************** 
*                              Friends.php 
*                            ------------------- 
*   begin                : Monday, Jan 20, 2003 
*   copyright            : (C) 2008-2009 lEx0 ak Kadyl Amangeldy
*   email                : lexo_kz@mail.ru 
*   $Id: friends.php,v 1.0.0 2009/02/28 
* 
* 
***************************************************************************/ 

/*************************************************************************** 
* 
*   This program is free software; you can redistribute it and/or modify 
*   it under the terms of the GNU General Public License as published by 
*   the Free Software Foundation; either version 2 of the License, or 
*   (at your option) any later version. 
* 
***************************************************************************/ 

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

$page_title = 'VIP - Добавление пользователя';

$template->assign_vars(array('PAGE_TITLE' => $page_title));
require(PAGE_HEADER);

if (IS_ADMIN)
{
	$mode = isset($_POST['mode'])?$_POST['mode']:(isset($_GET['mode'])?$_GET['mode']:(""));
	$vip_id = intval(isset($_POST['v'])?$_POST['v']:(isset($_GET['v'])?$_GET['v']:("")));
	$vip_user = get_userdata($vip_id);
	if ($mode=='add')
	{
		// Входящие переменные
		$ballance_get	= $_GET['b'];
		$tarif			= $_GET['tar'];
		$locked_get		= intval(isset($_POST['l'])?$_POST['l']:(isset($_GET['l'])?$_GET['l']:("")));
				
		if ($tarif==9999)
		{
			$sql = "UPDATE bb_users
					SET vip_tarif = '0', vip_end_date ='0', vip_start_date='0', vip_lock = ".$locked_get.", vip_ballance = ".$ballance_get."
				WHERE user_id = ".$vip_id;
			if (!$result = DB()->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Ошибка при изменении', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$cur_time = time();
			$vrow = DB()->fetch_row("SELECT * FROM bb_vip_tarif WHERE vip_tar_id = '".$tarif."'");
			if($vrow['vip_tar_time'] == -1){
			$konec = -1;
			}else{
			$konec = $cur_time + $vrow['vip_tar_time'];
			}
			$sql = "UPDATE bb_users
					SET vip_tarif = ".$tarif.", vip_lock = ".$locked_get.", vip_ballance = ".$ballance_get.", vip_start_date = ".$cur_time.", vip_end_date = ".$konec." 
					WHERE user_id = ".$vip_id;
			if (!$result = DB()->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Ошибка при изменении', '', __LINE__, __FILE__, $sql);
			}	
		}
		message_die(GENERAL_MESSAGE, 'Статус пользователя было успешно изменено на VIP<br><a href="index.php">Нажмите</a> для переход на главную страницу');
	}
	if ($vip_id <=0)
	{
		message_die(GENERAL_ERROR, "Вы не выбрали пользователя или такого пользователя не существует!");
	}
	$sql = "SELECT * FROM bb_users WHERE user_id =".$vip_id;
	if ( !($result = DB()->sql_query($sql)) ) 
	{
		message_die(GENERAL_ERROR, "Ошибка при выводе пользователей вип", '', __LINE__, __FILE__, $sql); 
	}
	if (DB()->num_rows($result)<=0)
	{
		message_die(GENERAL_ERROR, "Такого пользователя не существует!");
	}
	$sql = "SELECT * FROM bb_vip_tarif";
	if ( !($result = DB()->sql_query($sql)) ) 
	{
		message_die(GENERAL_ERROR, "Ошибка при выводе пользователей вип", '', __LINE__, __FILE__, $sql); 
	}
	$tar_list = '<select name="tar">
				<option value="9999">Откючить</option>';
	while ($row = DB()->sql_fetchrow($result))
	{
		if ($row['vip_tar_id'] == $vip_user['vip_tarif'])
		{
		$tar_list .='<option value="'.$row['vip_tar_id'].'" selected="selected">'.$row['vip_tar_name'].'</option>';
		}
		else
		{
		$tar_list .='<option value="'.$row['vip_tar_id'].'">'.$row['vip_tar_name'].'</option>';
		}
	}
	$tar_list .= '</select>';

	$template->assign_vars(array(
		'BALLANCE'	=> $vip_user['vip_ballance'],
		'USERNAME'	=> $vip_user['username'],
		'START_DATE'=> date('Y',$vip_user['vip_start_date']),
		'END_DATE'	=> bb_date($vip_user['vip_end_date']),
		'TARIF'		=> $tar_list,
		'LOCKED'	=> $vip_user['vip_lock'],
		'VIP_IDE'	=> $vip_id
	));
	print_page('add_vip.tpl');
}
else
{
	redirect('index.php');
}
?>