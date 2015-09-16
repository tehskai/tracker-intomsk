<?php
// Init
if (empty($bb_cfg['seed_points_enabled']))
	message_die(GENERAL_ERROR, $lang['Disabled']);

$user_id = $userdata['user_id'];
$btu = get_bt_userdata ($user_id);

if (empty($btu)) 
{
	require(INC_DIR .'functions_torrent.php');
	generate_passkey($user_id, true);
	$btu = get_bt_userdata($user_id);
}

$seed_points = (float) $btu['seed_points'];

if (!empty($_POST['bonus_id']))
{
	$bonus = explode('_', $_POST['bonus_id']);
	$bonus_type  = (string) $bonus[0]; // 'upload' or 'invite'
	$bonus_count = (float)  $bonus[1]; // count of GiB's or invites
	$do = isset($_GET['mode']) && isset($_GET['do']);
	
	
	$return = '<br /><br /><a href="profile.php?mode=bonus">'. $lang['RETURN_TO_BONUS'] .'</a>';
$template->assign_vars(array());
	switch ($bonus_type)
	{

//
// UPLOAD
//
		case 'upload':
			if (($cost = $bb_cfg['seed_points_ex']['upload'][$bonus_count]) && @$seed_points >= $cost)
			{
				$up_add = $bonus_count*1024*1024*1024; // GiB's to bytes
				DB()->query("UPDATE ". BB_BT_USERS ." SET 
							u_up_total  = u_up_total + $up_add,
							seed_points = seed_points - $cost
							WHERE user_id = $user_id");
				$msg = sprintf($lang['SUCCESSFULLY_EXCHANGED_UP'], $cost, $bonus_count) . $return;
			}
			else
				message_die(GENERAL_ERROR, $lang['POINTS_NOT_ENOUGH'] . $return);
		break;


//
// Invites
//
		case 'invite':
			if (($cost = $bb_cfg['seed_points_ex']['invite'][$bonus_count]) && $seed_points >= $cost)
				{
			if($bonus_count == 1){
				$inv_msg = "Инвайт";
				$inv_msg2= "инвайт";
			}elseif($bonus_count > 1 && $bonus_count < 5){
				$inv_msg = "Инвайта";
				$inv_msg2= "инвайты";
			}elseif($bonus_count >= 5){
				$inv_msg = "Инвайтов";
				$inv_msg2= "инвайты";
			}
					for($i=0; $i < $bonus_count; $i++){
						$invite_code = substr(md5(time()), rand(1, 14), 16);
						$sql = "INSERT INTO `invites` (`invite_id`,`user_id`,`new_user_id`,`invite_code`,`active`,`generation_date`,`activation_date`) VALUES(null,".(int)$userdata['user_id'].",0,'".$invite_code."','1',".time().",0)";
						DB()->query($sql);
						DB()->query("UPDATE ". BB_BT_USERS ." SET 
							seed_points = seed_points - $cost
							WHERE user_id = $user_id");
					}
					$msg = sprintf($lang['SUCCESSFULLY_EXCHANGED_INVITE'], $inv_msg2, $cost, $bonus_count, $inv_msg) . $return;
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['POINTS_NOT_ENOUGH'] . $return);
				}
		break;

/*
//
// Invites
//
		case 'invite':
			if (($cost = $bb_cfg['seed_points_ex']['invite'][$bonus_count]) && $seed_points >= $cost)
				{
					$invite_code = substr(md5(time()), rand(1, 14), 16);
					$sql = "INSERT INTO `invites` (`invite_id`,`user_id`,`new_user_id`,`invite_code`,`active`,`generation_date`,`activation_date`) VALUES(null,".(int)$userdata['user_id'].",0,'".$invite_code."','1',".time().",0)";
					DB()->query($sql);
					DB()->query("UPDATE ". BB_BT_USERS ." SET 
						seed_points = seed_points - $cost
						WHERE user_id = $user_id");
					$msg = sprintf($lang['SUCCESSFULLY_EXCHANGED_INVITE'], $cost, $invite_code) . $return;
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['POINTS_NOT_ENOUGH'] . $return);
				}
		break;*/

//
// VIP
//
		case 'vip':
			if (($cost = $bb_cfg['seed_points_ex']['vip']["$bonus_count"]) && $seed_points >= $cost)
			{
				if($bonus_count == 1){
					$vip_color1 = "#DD5500";
				}elseif($bonus_count > 1 && $bonus_count == 2){
					$vip_color1 = "#DDAA00";
				}elseif($bonus_count > 2 && $bonus_count == 3){
					$vip_color1 = "#AADD00";
				}elseif($bonus_count > 3 && $bonus_count == 4){
					$vip_color1 = "#00AA00";
				}
				$tar_id = $bonus_count;
				$cur_time = time();
				$vrow = DB()->fetch_row("SELECT * FROM bb_vip_tarif WHERE vip_tar_id = '".$tar_id."'");
				$konec = $cur_time + $vrow['vip_tar_time'];
				$sql = "UPDATE ". BB_USERS ."
				SET vip_tarif = ".$tar_id.", vip_lock = 0, vip_ballance = 0, vip_start_date = ".$cur_time.", vip_end_date = ".$konec."
				WHERE user_id = ".$user_id;
				DB()->sql_query($sql);
				$minus_cost = "UPDATE ". BB_BT_USERS ." SET seed_points = seed_points - $cost ";
				DB()->sql_query($minus_cost);
				$msg = sprintf($lang['SUCCESSFULLY_EXCHANGED_VIP'], '<b style="color:'.$vip_color1.';">'. $vrow['vip_tar_name'] .'</b>', bb_date($konec), $cost) . $return;
			}
			else
			{
				message_die(GENERAL_ERROR, $lang['POINTS_NOT_ENOUGH'] . $return);
			}
		break;
	}
	message_die(GENERAL_MESSAGE, $msg);
}
else
{
	$template->assign_vars(array(
		'BONUS_PRESENT' => true,
		'INVITE_PRESENT' => false,
		'PAGE_TITLE' => $lang['MY_BONUS'],

		'U_USER_PROFILE' => 'profile.php?mode=viewprofile&u='. $user_id,
		'S_MODE_ACTION'	 =>'profile.php?mode=bonus',
	));
	foreach ($bb_cfg['seed_points_ex']['upload'] as $traffic => $cost)
	{
		if($traffic <= 2){
			$color_mod = "#AA0000";
		}elseif($traffic > 2 && $traffic < 20){
			$color_mod = "#DD5500";
		}
		elseif($traffic >= 20 && $traffic < 50){
			$color_mod = "#DDAA00";
		}
		elseif($traffic >= 100 && $traffic < 200){
			$color_mod = "#AADD00";
		}
		elseif($traffic >= 500 && $traffic < 1024){
			$color_mod = "#00AA00";
		}
		$class = ($seed_points >= $cost) ? 'seed' : 'leech';
		$template->assign_block_vars('bonusrow', array(
			'BONUS_ID'   => "upload_$traffic",
			'BONUS_DESC' => '<b style="color:#666666;">Обменять на </b><b style="color:'.$color_mod.';">'.humn_size($traffic*1024*1024*1024).'</b> <b style="color:#666666;">отдачи</b>', // From bytes to GiB's
			'BONUS_TIP'  => $lang['UPLOAD_TIP'],
			'POINTS'     => "$cost / <b class=\"$class\">$seed_points</b>",
		));
	}
//
// VIP END
//


//
// Invites
//
	if (!empty($bb_cfg['new_user_reg_only_by_invite']))
	{
		foreach ($bb_cfg['seed_points_ex']['invite'] as $count => $cost)
		{
			if($count == 1){
				$inv_plus = "Инвайт";
			}elseif($count > 1 && $count < 5){
				$inv_plus = "Инвайта";
			}elseif($count >= 5){
				$inv_plus = "Инвайтов";
			}
			
			$class = ($seed_points >= $cost) ? 'seed' : 'leech';
			$template->assign_block_vars('bonusrow', array(
				'BONUS_ID'   => "invite_$count",
				'BONUS_DESC' => '<b style="color:#666666;">Обменять на </b><b style="color:#0000AA;">'.$count.' '.$inv_plus.'</b>',
				'BONUS_TIP'  => $lang['INVITE_TIP'],
				'POINTS'     => "$cost / <b class=\"$class\">$seed_points</b>",
			));
		}
	}
//
// END Invites
//

//
// VIP 
//
foreach ($bb_cfg['seed_points_ex']['vip'] as $count => $cost)
{
	$sql = "SELECT * FROM bb_vip_tarif WHERE vip_tar_id =".$count;
	if (!$result = DB()->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Ошибка при изменении', '', __LINE__, __FILE__, $sql);
	}
	$row = DB()->sql_fetchrow($result);
	$date2 = $row['vip_tar_time'] / 86400;
	if($date2 >= 365 && $date2 < 3000){
		$datec = $row['vip_tar_time'] / 86400 / 365;
	}elseif($date2 >= 10000){
		$datec = "Unlim";
	}elseif($date2 < 365){
		$datec = $row['vip_tar_time'] / 86400;
	}
	if($count == 1){
		$vip_color = "#DD5500";
	}elseif($count > 1 && $count == 2){
		$vip_color = "#DDAA00";
	}elseif($count > 2 && $count == 3){
		$vip_color = "#AADD00";
	}elseif($count > 3 && $count == 4){
		$vip_color = "#00AA00";
	}
	$class = ($seed_points >= $cost) ? 'seed' : 'leech';
	$template->assign_block_vars('bonusrow', array(
		'BONUS_ID'   => "vip_$count",
		'BONUS_DESC' =>  '<b style="color:#666666;">Обменять на </b><b class="colorVIP">VIP</b> <b style="color:#666666;">Тариф:</b> <b style="color:'.$vip_color.';">'. $row['vip_tar_name'] .'</b><b style="color:#666666;">, Длительность: '. $datec . ' дней.</b>',
		'BONUS_TIP'  => $lang['VIP_TIP'],
		'POINTS'     => "$cost / <b class=\"$class\">$seed_points</b>",
	));
}
//
// END VIP
//

$do = isset($_GET['mode']) && isset($_GET['do']);
switch($do)
{
	case 'invites':
		$sql = 'SELECT * FROM `invites` WHERE `user_id`='.$userdata['user_id'].' ORDER BY `generation_date` DESC';
		if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, 'Ошибка при получении списка инвайтов', '', __LINE__, __FILE__, $sql);
		$invite_row = DB()->sql_fetchrowset($result);
		$num_invite_row = DB()->num_rows($result);
		DB()->sql_freeresult($result);
		if ($num_invite_row > 0) {
			$template->assign_vars(array('INVITE_PRESENT' => true,
				'BONUS_PRESENT' => false,
				'INVITES_PRESENT' => true
			));
			for ($i = 0; $i < $num_invite_row; $i++) {
				$new_user_data = get_userdata($invite_row[$i]['new_user_id']);
				$template->assign_block_vars('invite_row', array(
					'GENERATION_DATE' => date('d.m.Y H:i', $invite_row[$i]['generation_date']),
					'INVITE_CODE' => $invite_row[$i]['invite_code'],
					'ACTIVE' => ($invite_row[$i]['active'] == '1') ? 'Да' : 'Нет',
					'NEW_USER' => ($invite_row[$i]['active'] == '1') ? '-' : '<a href="profile.php?mode=viewprofile&u='.$invite_row[$i]['new_user_id'].'">'.$new_user_data['username'].'</a>',
					'ACTIVATION_DATE' => ($invite_row[$i]['active'] == '1') ? '-' : date('d.m.Y H:i', $invite_row[$i]['activation_date']))
				);
			}
		} else $template->assign_vars(array('INVITES_PRESENT' => false));
	break;
}
	print_page('usercp_bonus.tpl');
}
?>