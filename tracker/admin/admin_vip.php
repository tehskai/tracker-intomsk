<?php
if (!empty($setmodules)) {
	$file = basename(__FILE__);
	$module['Mods']['VIP_Управление'] = $file.'?mode=cp';
	$module['Mods']['VIP_Тарифы'] = $file.'?mode=tarif';
	return;
}

if (isset($_POST['mode']) || isset($_GET['mode'])) $mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];

require('./pagestart.php');

$mode = isset($_POST['mode'])?$_POST['mode']:(isset($_GET['mode'])?$_GET['mode']:(""));
$vip_id = intval(isset($_POST['v'])?$_POST['v']:(isset($_GET['v'])?$_GET['v']:("")));
$tarif_get = intval(isset($_POST['tar'])?$_POST['tar']:(isset($_GET['tar'])?$_GET['tar']:("")));

$vip_user = get_userdata($vip_id);

if ($mode == 'cp')
{
	$template->assign_vars(array(
		'cp'	=> true,
		'NAZV'	=> 'Control Panel',
		'DESC'	=> 'Панель для управления пользователями VIP<br />Здесь вы можете изменять балланс, изменить тариф, снять статус VIP и т.д.'
	));	
	$sql = "SELECT 
				u.*,
				t.* 
			FROM 
				bb_users u,
				bb_vip_tarif t
			WHERE
				t.vip_tar_id = u.vip_tarif ";
	if ($vrow = DB()->fetch_rowset($sql))
	{
		$total_vip = count($vrow);
		$template->assign_vars(array(
			'VIP_TOTAL'	=> $total_vip,
		));
	}
	if ( !($result = DB()->sql_query($sql)) ) 
	{
		message_die(GENERAL_ERROR, "Ошибка при выводе пользователей вип", '', __LINE__, __FILE__, $sql); 
	}
	while ( $row = DB()->sql_fetchrow($result) ) 
	{
		$col = count($row['user_id']);
		$lock = '<strong style="color:#009900">Открыт</strong>';
		if ($row['vip_lock'])
		{
			$lock = '<strong style="color:#FF0000">Заблокирован</strong>';
		}
		if($row['vip_end_date'] > 0){
					$end_date = date("d.m.Y",$row['vip_end_date']);
					} else {
					$end_date = 'Тариф Unlim не закончится.';
					} 
		$template->assign_block_vars('viprow', array(
			'VIP_NAME'			=> $row['username'],
			'VIP_ID'			=> $row['user_id'],
			'VIP_STATUS'		=> $lock,
			'VIP_BAL'			=> $row['vip_ballance'],
			'VIP_TARIF'			=> $row['vip_tar_name'],
			'VIP_TARIF_ID'		=> $row['vip_tar_id'],
			'VIP_TARIF_EDATE'	=> $end_date,
			'VIP_TARIF_SDATE'	=> date("d.m.Y",$row['vip_start_date']),
			'VIP_TARIF_PRICE'	=> $row['vip_tar_price']
		));
	}
	
	$sql = "SELECT 
				*
			FROM 
				bb_users
			WHERE
				vip_ballance > 0
				AND vip_tarif =0";
	if ($nvrow = DB()->fetch_rowset($sql))
	{
		$total_nvip = count($nvrow);
		$template->assign_vars(array(
			'VIP_NTOTAL'	=> $total_nvip,
		));
	}
	if ( !($result = DB()->sql_query($sql)) ) 
	{
		message_die(GENERAL_ERROR, "Ошибка при выводе пользователей вип", '', __LINE__, __FILE__, $sql); 
	}
	while ( $row = DB()->sql_fetchrow($result) )
	{
		$lock = '<strong style="color:#009900">Открыт</strong>';
		if ($row['vip_lock'])
		{
			$lock = '<strong style="color:#FF0000">Заблокирован</strong>';
		}
		$template->assign_block_vars('nviprow', array(
			'VIP_NAME'			=> $row['username'],
			'VIP_ID'			=> $row['user_id'],
			'VIP_STATUS'		=> $lock,
			'VIP_BAL'			=> $row['vip_ballance']
		));		
	} 	

	print_page('admin_vip.tpl', 'admin');
	
}
else if ($mode == 'edit')
{
	$template->assign_vars(array(
		'edit'	=> true,
		'NAZV'	=> '<a href="admin_vip.php?mode=cp">Control Panel</a> - Управление пользователем',
		'DESC'	=> 'Панель для управления пользователями VIP<br />Здесь вы можете изменять балланс, изменить тариф, снять статус VIP и т.д.'
	));	
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
	
//	
// Вывод Начального Времени
// Секунда
//
	$s_s = '<select name="s_s">';
	for ($i = 0; $i < 60; $i++)
	{
		if (date('s',$vip_user['vip_start_date']) == $i)
		{
			$s_s .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$s_s .='<option value="'.$i.'">'.$i.'</option>';
		}
	}
	$s_s .='</select>';
	
//	
// Минута
//
	$i_s = '<select name="i_s">';
	for ($i = 0; $i < 60; $i++)
	{
		if (date('i',$vip_user['vip_start_date']) == $i)
		{
			$i_s .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$i_s .='<option value="'.$i.'">'.$i.'</option>';
		}	
	}
	$i_s .='</select>';

//	
// Часы
//
	$h_s = '<select name="h_s">';
	for ($i = 0; $i < 24; $i++)
	{
		if (date('H',$vip_user['vip_start_date']) == $i)
		{
			$h_s .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$h_s .='<option value="'.$i.'">'.$i.'</option>';
		}	
	}
	$h_s .='</select>';
	
//	
// Дни
//
	$d_s = '<select name="d_s">';
	for ($i = 1; $i < 32; $i++)
	{
		if (date('d',$vip_user['vip_start_date']) == $i)
		{
			$d_s .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$d_s .='<option value="'.$i.'">'.$i.'</option>';
		}	
	}
	$d_s .='</select>';
	
//	
// Месяцы
//
	$m_s ='<select name="m_s">';
	if (date('m',$vip_user['vip_start_date']) == 1)
	{
	$m_s .='<option value=1 selected="selected">Январь</option>';
	}
	else
	{
	$m_s .='<option value=1>Январь</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 2)
	{
	$m_s .='<option value=2 selected="selected">Февраль</option>';
	}
	else
	{
	$m_s .='<option value=2>Февраль</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 3)
	{
	$m_s .='<option value=3 selected="selected">Март</option>';
	}
	else
	{
	$m_s .='<option value=3>Март</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 4)
	{
	$m_s .='<option value=4 selected="selected">Апрель</option>';
	}
	else
	{
	$m_s .='<option value=4>Апрель</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 5)
	{
	$m_s .='<option value=5 selected="selected">Май</option>';
	}
	else
	{
	$m_s .='<option value=5>Май</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 6)
	{
	$m_s .='<option value=6 selected="selected">Июнь</option>';
	}
	else
	{
	$m_s .='<option value=6>Июнь</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 7)
	{
	$m_s .='<option value=7 selected="selected">Июль</option>';
	}
	else
	{
	$m_s .='<option value=7>Июль</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 8)
	{
	$m_s .='<option value=8 selected="selected">Август</option>';
	}
	else
	{
	$m_s .='<option value=8>Август</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 9)
	{
	$m_s .='<option value=9 selected="selected">Сентябрь</option>';
	}
	else
	{
	$m_s .='<option value=9>Сентябрь</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 10)
	{
	$m_s .='<option value=10 selected="selected">Октябрь</option>';
	}
	else
	{
	$m_s .='<option value=10>Октябрь</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 11)
	{
	$m_s .='<option value=11 selected="selected">Ноябрь</option>';
	}
	else
	{
	$m_s .='<option value=11>Ноябрь</option>';
	}
	if (date('m',$vip_user['vip_start_date']) == 12)
	{
	$m_s .='<option value=12  selected="selected">Декабрь</option>';
	}
	else
	{
	$m_s .='<option value=12>Декабрь</option>';
	}
	$m_s .='</select>';
	
//	
// Годы
//
	$y_s ='<select name="y_s">';
	if (date('Y',$vip_user['vip_start_date']) == 2009)
	{
	$y_s .='<option value=2009 selected="selected">2009</option>';
	}
	else
	{
	$y_s .='<option value=2009>2009</option>';
	}
	if (date('Y',$vip_user['vip_start_date']) == 2010)
	{
	$y_s .='<option value=2010 selected="selected">2010</option>';
	}
	else
	{
	$y_s .='<option value=2010>2010</option>';
	}
	if (date('Y',$vip_user['vip_start_date']) == 2011)
	{
	$y_s .='<option value=2011 selected="selected">2011</option>';
	}
	else
	{
	$y_s .='<option value=2011>2011</option>';
	}
	if (date('Y',$vip_user['vip_start_date']) == 2012)
	{
	$y_s .='<option value=2012 selected="selected">2012</option>';
	}
	else
	{
	$y_s .='<option value=2012>2012</option>';
	}
	$y_s .='</select>';
	
//	
// Вывод Конечного Времени
// Секунда
//
	$s_e = '<select name="s_e">';
	for ($i = 0; $i < 60; $i++)
	{
		if (date('s',$vip_user['vip_end_date']) == $i)
		{
			$s_e .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$s_e .='<option value="'.$i.'">'.$i.'</option>';
		}
	}
	$s_e .='</select>';
	
//	
// Минута
//
	$i_e = '<select name="i_e">';
	for ($i = 0; $i < 60; $i++)
	{
		if (date('i',$vip_user['vip_end_date']) == $i)
		{
			$i_e .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$i_e .='<option value="'.$i.'">'.$i.'</option>';
		}	
	}
	$i_e .='</select>';

//	
// Часы
//
	$h_e = '<select name="h_e">';
	for ($i = 0; $i < 24; $i++)
	{
		if (date('H',$vip_user['vip_end_date']) == $i)
		{
			$h_e .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$h_e .='<option value="'.$i.'">'.$i.'</option>';
		}	
	}
	$h_e .='</select>';
	
//	
// Дни
//
	$d_e = '<select name="d_e">';
	for ($i = 0; $i < 31; $i++)
	{
		if (date('d',$vip_user['vip_end_date']) == $i)
		{
			$d_e .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$d_e .='<option value="'.$i.'">'.$i.'</option>';
		}	
	}
	$d_e .='</select>';
	
//	
// Месяцы
//
	$m_e ='<select name="m_e">';
	if (date('m',$vip_user['vip_end_date']) == 1)
	{
	$m_e .='<option value=1 selected="selected">Январь</option>';
	}
	else
	{
	$m_e .='<option value=1>Январь</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 2)
	{
	$m_e .='<option value=2 selected="selected">Февраль</option>';
	}
	else
	{
	$m_e .='<option value=2>Февраль</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 3)
	{
	$m_e .='<option value=3 selected="selected">Март</option>';
	}
	else
	{
	$m_e .='<option value=3>Март</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 4)
	{
	$m_e .='<option value=4 selected="selected">Апрель</option>';
	}
	else
	{
	$m_e .='<option value=4>Апрель</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 5)
	{
	$m_e .='<option value=5 selected="selected">Май</option>';
	}
	else
	{
	$m_e .='<option value=5>Май</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 6)
	{
	$m_e .='<option value=6 selected="selected">Июнь</option>';
	}
	else
	{
	$m_e .='<option value=6>Июнь</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 7)
	{
	$m_e .='<option value=7 selected="selected">Июль</option>';
	}
	else
	{
	$m_e .='<option value=7>Июль</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 8)
	{
	$m_e .='<option value=8 selected="selected">Август</option>';
	}
	else
	{
	$m_e .='<option value=8>Август</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 9)
	{
	$m_e .='<option value=9 selected="selected">Сентябрь</option>';
	}
	else
	{
	$m_e .='<option value=9>Сентябрь</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 10)
	{
	$m_e .='<option value=10 selected="selected">Октябрь</option>';
	}
	else
	{
	$m_e .='<option value=10>Октябрь</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 11)
	{
	$m_e .='<option value=11 selected="selected">Ноябрь</option>';
	}
	else
	{
	$m_e .='<option value=11>Ноябрь</option>';
	}
	if (date('m',$vip_user['vip_end_date']) == 12)
	{
	$m_e .='<option value=12  selected="selected">Декабрь</option>';
	}
	else
	{
	$m_e .='<option value=12>Декабрь</option>';
	}
	$m_e .='</select>';

//	
// Годы
//
	$y_e ='<select name="y_e">';
	if (date('Y',$vip_user['vip_end_date']) == 2009)
	{
	$y_e .='<option value=2009 selected="selected">2009</option>';
	}
	else
	{
	$y_e .='<option value=2009>2009</option>';
	}
	if (date('Y',$vip_user['vip_end_date']) == 2010)
	{
	$y_e .='<option value=2010 selected="selected">2010</option>';
	}
	else
	{
	$y_e .='<option value=2010>2010</option>';
	}
	if (date('Y',$vip_user['vip_end_date']) == 2011)
	{
	$y_e .='<option value=2011 selected="selected">2011</option>';
	}
	else
	{
	$y_e .='<option value=2011>2011</option>';
	}
	if (date('Y',$vip_user['vip_end_date']) == 2012)
	{
	$y_e .='<option value=2012 selected="selected">2012</option>';
	}
	else
	{
	$y_e .='<option value=2012>2012</option>';
	}
	$y_e .='</select>';
// Конец вывода дат

	$template->assign_vars(array(
		'BALLANCE'	=> $vip_user['vip_ballance'],
		'USERNAME'	=> $vip_user['username'],
		'START_DATE'=> date('Y',$vip_user['vip_start_date']),
		'END_DATE'	=> bb_date($vip_user['vip_end_date']),
		'TARIF'		=> $tar_list,
		'LOCKED'	=> $vip_user['vip_lock'],
		'VIP_IDE'	=> $vip_id,
		// Star Date
		'S_S'		=> $s_s,
		'I_S'		=> $i_s,
		'H_S'		=> $h_s,
		'D_S'		=> $d_s,
		'M_S'		=> $m_s,
		'Y_S'		=> $y_s,
		// End Date
		'S_E'		=> $s_e,
		'I_E'		=> $i_e,
		'H_E'		=> $h_e,
		'D_E'		=> $d_e,
		'M_E'		=> $m_e,
		'Y_E'		=> $y_e
	));
	print_page('admin_vip.tpl', 'admin');
}

//
// Изменение параметров определнного вип пользователя
//
else if ($mode == 'eu')
{
	// Входящие переменные
	$ballance_get	= $_GET['b'];
	$tarif			= $_GET['tar'];
	$locked_get		= intval(isset($_POST['l'])?$_POST['l']:(isset($_GET['l'])?$_GET['l']:("")));
	
	$s_s		= $_GET['s_s'];
	$i_s		= $_GET['i_s'];
	$h_s		= $_GET['h_s'];
	$d_s		= $_GET['d_s'];
	$m_s		= $_GET['m_s'];
	$y_s		= $_GET['y_s'];
	
	$s_e		= $_GET['s_e'];
	$i_e		= $_GET['i_e'];
	$h_e		= $_GET['h_e'];
	$d_e		= $_GET['d_e'];
	$m_e		= $_GET['m_e'];
	$y_e		= $_GET['y_e'];
	
	$start_date = mktime($h_s, $i_s, $s_s, $m_s, $d_s, $y_s);
	$end_date = mktime($h_e, $i_e, $s_e, $m_e, $d_e, $y_e);
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
		$sql = "UPDATE bb_users
				SET vip_tarif = ".$tarif.", vip_end_date =".$end_date.", vip_start_date=".$start_date.", vip_lock = ".$locked_get.", vip_ballance = ".$ballance_get."
				WHERE user_id = ".$vip_id;
		if (!$result = DB()->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Ошибка при изменении', '', __LINE__, __FILE__, $sql);
		}	
	}
	message_die(GENERAL_MESSAGE, 'Профиль VIP пользователю был успешно изменен<br><a href="admin_vip.php?mode=cp">Нажмите</a> для перехода в панель управления');
}
else if ($mode == 'del')
{
	$sql = "UPDATE bb_users
			SET vip_tarif = '0', vip_end_date ='0', vip_start_date='0', vip_lock = '0', vip_ballance = '0'
			WHERE user_id = ".$vip_id;

	if (!$result = DB()->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Ошибка при изменении', '', __LINE__, __FILE__, $sql);
	}
	message_die(GENERAL_MESSAGE, 'Пользователь успешно снят со статуса VIP<br><a href="admin_vip.php?mode=cp">Нажмите</a> для перехода в панель управления');
}
else if ($mode == 'tarif')
{
	$template->assign_vars(array(
		'tarif'	=> true,
		'NAZV'	=> 'Tarif Control Page',
		'DESC'	=> 'Панель для управления тарифами VIP<br />Здесь вы можете изменять стоимость тарифа, длительность и название'
	));	
	$sql = "SELECT * FROM bb_vip_tarif";
	if (!$result = DB()->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Ошибка при выводе списка тарифов', '', __LINE__, __FILE__, $sql);
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
		'ID'		=> $row['vip_tar_id'],
		'NAME'		=> '<b style="color:'.$vip_color.';">'.$row['vip_tar_name'].'</b>',
		'TIME'		=> $datec,
		'PRICE'		=> $row['vip_tar_price'],
		'EDIT'		=> '<a href="admin_vip.php?mode=tedit&tar='.$row['vip_tar_id'].'">Изменить</a>'
		));
	}
	
	print_page('admin_vip.tpl', 'admin');
}

else if ($mode == 'tadd')
{
	$template->assign_vars(array(
		'tadd'	=> true,
		'NAZV'	=> '<a href="admin_vip.php?mode=tarif">Tarif Control Page</a> Добавление Тарифа',
		'DESC'	=> 'Панель для добавления тарифов VIP'
	));
	print_page('admin_vip.tpl', 'admin');
}

else if ($mode == 'taddc')
{
	$name_a = isset($_POST['namet'])?$_POST['namet']:(isset($_GET['namet'])?$_GET['namet']:(""));
	$time_a = intval(isset($_POST['time'])?$_POST['time']:(isset($_GET['time'])?$_GET['time']:("")));
	$price_a = intval(isset($_POST['price'])?$_POST['price']:(isset($_GET['price'])?$_GET['price']:("")));
	if ($name_a <> '' or $time_a < 1 or $price_a <1)
	{
		$time_a = $time_a * 86400;
		$sql = "INSERT INTO bb_vip_tarif (vip_tar_name, vip_tar_time, vip_tar_price)
			VALUES ('" . $name_a . "', '" . $time_a . "', '" . $price_a . "')";
		if (!$result = DB()->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Ошибка при выводе списка тарифов', '', __LINE__, __FILE__, $sql);
		}	
		message_die(GENERAL_MESSAGE, 'Добавлен новый тариф "'.$name_a.'"<br><a href="admin_vip.php?mode=tarif">Нажмите</a> для перехода к списку');
	}
	message_die(GENERAL_ERROR, 'Не заполнено одно из полей');
	
}

else if ($mode == 'delt')
{
	if ($tarif_get > 0)
	{
		$sql = "DELETE 
				FROM bb_vip_tarif
				WHERE vip_tar_id = ".$tarif_get;
		if (!$result = DB()->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Ошибка при выводе списка тарифов', '', __LINE__, __FILE__, $sql);
		}
		message_die(GENERAL_MESSAGE, 'Тариф Успешно удален<br><a href="admin_vip.php?mode=tarif">Нажмите</a> для перехода к списку');
	}
	message_die(GENERAL_ERROR, 'Неправильная категория');
}

message_die(GENERAL_ERROR, '<strong>ОПАНЬКИ</strong><br> а тут ничего нет!!! :-)<br> Ни страницы ни запроса и оставлен он только для будущего функционала. Если хотите что бы потом дополнения подошли как влитые к этому моду то не трогаем!!!<br><strong>lEx0</strong>');

?>