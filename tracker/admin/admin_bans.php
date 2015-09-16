<?php
// ACP Header - START
if (!empty($setmodules))
{
  $module['Mods']['Список банов'] = basename(__FILE__) . '?mode=list';
  return;
}
require('./pagestart.php');
// ACP Header - END


$sql = "
		SELECT  ub.id, ub.userid, ub.userip, ub.ban_time_exp, ub.un_ban_date, ub.mod_id, u.username, u2.username AS modername
		FROM  " . BB_UNBANLIST . " ub
		LEFT JOIN ". BB_USERS ." u ON (u.user_id = ub.userid)
		LEFT JOIN ". BB_USERS ." u2 ON (u2.user_id = ub.mod_id)
		LIMIT 1000
	   ";

if (!$result = DB()->sql_query($sql))
 {

  message_die(GENERAL_MESSAGE, "Нихера не работает, обратитесь к разработчику");
 }

while ($row = DB()->sql_fetchrow($result))
{
	$user_id = $user_ip = $modername = '';
	if ($row['userid'] == 0)
		{
			$user_id = '-';
			$user_ip = decode_ip($row['userip']);
		}
	else
		{
			$user_id = '<a href="/profile.php?mode=viewprofile&u=' . $row['userid'] . '">' . $row['username'] . '</a>';
			$user_ip = '-';
		}

    $modername = '<a href="/profile.php?mode=viewprofile&u=' . $row['mod_id'] . '">' . $row['modername'] . '</a>';


 	if ( (int) $row['ban_time_exp'] == 0)
 		{
			$ban_date_exp = 'Постоянный';
 		}
 	else
 		{
 			$ban_date_exp = date("H:i d-m-Y", $row['ban_time_exp']);
 		}


	$template->assign_block_vars("ubm",array(
			'UNBAN_ID'			=> $row['id'],
			'UNBAN_IP'			=> $user_ip,
			'UNBAN_TIME_EXP'	=> $ban_date_exp,
			'UNBAN_DATE'		=> date("H:i d-m-Y", $row['un_ban_date']),
			'UNBAN_USERNAME'	=> $user_id,
			'UNBAN_MODERNAME'	=> $modername,
	));
}



$sql = "
		SELECT  b.ban_id, b.ban_userid, b.ban_ip, b.ban_time, b.ban_time_exp, b.ban_theme, b.ban_by, u.username, u2.username AS modername
		FROM       ". BB_BANLIST ." b
		LEFT JOIN ". BB_USERS      ." u ON (u.user_id = b.ban_userid)
		LEFT JOIN ". BB_USERS      ." u2 ON (u2.user_id = b.ban_by)
		LIMIT 100
	   ";

if (!$result = DB()->sql_query($sql))
 {

  message_die(GENERAL_MESSAGE, "Нихера не работает, обратитесь к разработчику");
 }

while ($row = DB()->sql_fetchrow($result))
{
	$ban_user_id = $ban_user_ip = '';
	if ($row['ban_userid'] == 0)
		{
			$ban_user_id = '-';
			$ban_user_ip = decode_ip($row['ban_ip']);
		}
	else
		{
			$ban_user_id = '<a href="/profile.php?mode=viewprofile&u=' . $row['ban_userid'] . '">' . $row['username'] . '</a>';
			$ban_user_ip = '-';
		}

	$ban_delete = '<a href="/ban.php?s_mode=unban&bid=' . $row['ban_id'] . '">Удалить</a>';
    $ban_modername = '<a href="/profile.php?mode=viewprofile&u=' . $row['ban_by'] . '">' . $row['modername'] . '</a>';

 	if ($row['ban_time'] == 0)
 		{
			$ban_date = 'Неизвестно';
 		}
 	else
 		{
 			$ban_date = date("H:i d-m-Y", $row['ban_time']);
 		}


  	if ($row['ban_time_exp'] == 0)
 		{
			$ban_date_exp = 'Постоянный';
 		}
 	else
 		{
 			$ban_date_exp = date("H:i d-m-Y", $row['ban_time_exp']);
 		}


	$template->assign_block_vars('userban',array(
			'BAN_ID'			=> $row['ban_id'],
			'BAN_IP'			=> $ban_user_ip,
			'BAN_TIME'			=> $ban_date,
			'BAN_TIME_EXP'		=> $ban_date_exp,
			'BAN_THEME'			=> $row['ban_theme'],
			'BAN_BY'			=> $row['ban_by'],
			'BAN_USERNAME'		=> $ban_user_id,
			'BAN_MODERNAME'		=> $ban_modername,
			'BAN_DELETE'		=> $ban_delete,
	));
}


print_page('admin_bans.tpl', 'admin');
?>