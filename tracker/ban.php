<?php
define('BB_SCRIPT', 'ban');
require('./common.php');
$user->session_start();
$ip_this_post = $ban_userid = $sql = $post_id = $ban_id = '';
global $userdata;

//Проверка Админ/Модератор
if(!(IS_MOD || IS_ADMIN))
 {
 	message_die(GENERAL_MESSAGE, "Возможность банить доступна только модераторам и администраторам");
 	break;
 }

//Получаем основной режим (Отправить - режим бана (срок, причина, юзер_ид(если есть) пост_ид) | unban - Режим анбана (ban_id))
if(isset($_POST['s_mode'])) $s_mode = $_POST['s_mode'];
else
 {
  if(isset($_GET['s_mode'])) $s_mode = $_GET['s_mode'];
  else $s_mode="none";
 }

//Получаем режим бана (ban u p)
if(isset($_POST['mode'])) $mode = $_POST['mode'];
else
 {
  if(isset($_GET['mode'])) $mode = $_GET['mode'];
  else $mode="none";
 }

if ($mode == "none" && $s_mode == "none")
	 {
	 	message_die(GENERAL_MESSAGE, "Нехватает параметров");
	 	break;
	 }

// Получаем юзер_ид
if(isset($_POST['u'])) $ban_userid = (int) $_POST['u'];
else
 {
  if(isset($_GET['u'])) $ban_userid = (int) $_GET['u'];
  else $ban_userid ="none";
 }


//Получаем пост_ид
if(isset($_POST['p'])) $post_id = (int) $_POST['p'];
else
 {
  if(isset($_GET['p'])) $post_id = (int) $_GET['p'];
  else $post_id ="none";
 }
//Если пост ид = 0 - недостаточно параметров
if ($post_id == 0) {$post_id = "none";}


//Получаем бан_ид
if(isset($_POST['bid'])) $ban_id = (int) $_POST['bid'];
else
 {
  if(isset($_GET['bid'])) $ban_id = (int) $_GET['bid'];
  else $ban_id ="none";
 }
//Проверка на вызов скрипта




//Проверяем основной режим
switch($s_mode) {
case "Отправить":
	$plasdate_s = $_POST['plasdate'];
	$warn_theme = htmlspecialchars($_POST['warn_theme']);
//Проверяем режим бана
	switch($mode) {
	case "none":
		message_die(GENERAL_MESSAGE, "Не задан режим");
	 	break;
	case "ban":
		if ($ban_userid != "none" && $post_id != "none")
		 {
		 	if ($ban_userid != -1)
	 		 {
//Бан по ЮзерИД
//===============================================
				if($plasdate_s == "inf")
						   {
// Перманент
							$date = 0;
//Проверка на существующий бан
//===================================================
							$sql = "
									SELECT  b.ban_userid, b.ban_time_exp, b.ban_theme, b.ban_by, u.username, u2.username AS modername
									FROM       ". BB_BANLIST ." b
									LEFT JOIN ". BB_USERS      ." u ON (u.user_id = b.ban_userid)
									LEFT JOIN ". BB_USERS      ." u2 ON (u2.user_id = b.ban_by)
									WHERE b.ban_userid = " . $ban_userid ."
									LIMIT 1
								   ";
							if (!$result = DB()->sql_query($sql))
							 {
							  message_die(GENERAL_MESSAGE, "Нихера не работает, обратитесь к разработчику");
							 }
							$row = mysql_fetch_array($result);
							if ( $row != FALSE)
								{
									$t_exp = '';
									if ($row['ban_time_exp'] != 0)
										{
											$t_exp = "до: " . date("H:i d-m-Y", $row['ban_time_exp']);
										}
									else $t_exp = 'перманентно';
									message_die(GENERAL_MESSAGE, "Пользователь " . $row['username'] . " уже забанен  " . $row['modername'] . ", срок ". $t_exp . ", причина: " . $row['ban_theme']);
									break;
								}
//Закончили проверку
//=======================================
//Выбираем имя пользователя
							$user_name = '';
							$sql = "
									SELECT  username, user_level
									FROM ". BB_USERS ."
									WHERE user_id = '" . $ban_userid . "'
									LIMIT 1
								   ";
							if ( !($result = DB()->sql_query($sql)) )
							{
								message_die(GENERAL_ERROR, 'Не могу получить имя пользователя', '', __LINE__, __FILE__, $sql);
							}
							$row = DB()->sql_fetchrow($result);
// Проверяем на модера/админа
							if ($row['user_level'] == 1 || $row['user_level'] == 2)
								{
									message_die(GENERAL_ERROR, "Вы не можете забанить администратора или модератора");
								}

							$user_name = $row['username'];

//Записываем бан в таблицу банов
							$cur_date = TIMENOW;
							$sql = "INSERT INTO ". BB_BANLIST .
							" (ban_id, ban_userid, ban_ip, ban_email, ban_time, ban_time_exp, ban_theme, ban_by) VALUES
							  ('', $ban_userid, '','',$cur_date, $date,'" . str_replace("\'", "''", $warn_theme) ."', '" . $userdata['user_id'] . "')";
							if (!(DB()->sql_query($sql)))
							{
								message_die(GENERAL_ERROR, 'Не удалось добавить бан', '', __LINE__, __FILE__, $sql);
							}

//Прибиваем сессию
							cache_rm_user_sessions ($ban_userid);
							delete_user_sessions ($ban_userid);
	 	 				 	message_die(GENERAL_MESSAGE, 'Пользователь ' . $user_name . ' забанен перманентно');
				 		 	break;
					       }
				else {

						 switch($plasdate_s)
						  {
						    case "sel":
//Не выбран срок бана
				 		 		message_die(GENERAL_MESSAGE, 'Вы не выбрали срок бана!');
								break;
						    case "1d":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d")+1, date("Y"));
						        break;
						    case "2d":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d")+2, date("Y"));
						        break;
					    	case "3d":
					        	$date = mktime(date("H"), date("i"), 0, date("m")  , date("d")+3, date("Y"));
						        break;
						    case "1w":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d")+7, date("Y"));
						        break;
						    case "2w":
						        $date = mktime(date("H"), date("i"), 0, date("m"), date("d")+14, date("Y"));
					    	    break;
						    case "1m":
						        $date = mktime(date("H"), date("i"), 0, date("m")+1  , date("d"), date("Y"));
						        break;
						    case "2m":
						        $date = mktime(date("H"), date("i"), 0, date("m")+2  , date("d"), date("Y"));
						        break;
					    	case "3m":
					        	$date = mktime(date("H"), date("i"), 0, date("m")+3  , date("d"), date("Y"));
						        break;
						    case "6m":
						        $date = mktime(date("H"), date("i"), 0, date("m")+6  , date("d"), date("Y"));
						        break;
						    case "1y":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d"), date("Y")+1);
					    	    break;
						    case "2y":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d"), date("Y")+2);
						        break;
						    case "3y":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d"), date("Y")+3);
						        break;
						  }
					// Бан на время

//Проверка на существующий бан
//===================================================
							$sql = "
									SELECT  b.ban_userid, b.ban_time_exp, b.ban_theme, b.ban_by, u.username, u2.username AS modername
									FROM       ". BB_BANLIST ." b
									LEFT JOIN ". BB_USERS      ." u ON (u.user_id = b.ban_userid)
									LEFT JOIN ". BB_USERS      ." u2 ON (u2.user_id = b.ban_by)
									WHERE b.ban_userid = " . $ban_userid ."
									LIMIT 1
								   ";
							if (!$result = DB()->sql_query($sql))
							 {
							  message_die(GENERAL_MESSAGE, "Нихера не работает, обратитесь к разработчику");
							 }
							$row = mysql_fetch_array($result);
							if ( $row != FALSE)
								{
									$t_exp = '';
									if ($row['ban_time_exp'] != 0)
										{
											$t_exp = "до: " . date("H:i d-m-Y", $row['ban_time_exp']);
										}
									else $t_exp = 'перманентно';
									message_die(GENERAL_MESSAGE, "Пользаватель " . $row['username'] . " уже забанен  " . $row['modername'] . ", срок ". $t_exp . ", причина: " . $row['ban_theme']);
									break;
								}
//Закончили проверку
//=======================================
//Получаем имя и юзер левел пользователя.
						$user_name = '';
						$sql = "
								SELECT  username, user_level
								FROM ". BB_USERS ."
								WHERE user_id = '" . $ban_userid . "'
								LIMIT 1
							   ";
						if ( !($result = DB()->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'Не могу получить имя пользователя', '', __LINE__, __FILE__, $sql);
						}
						$row = DB()->sql_fetchrow($result);
// Проверяем на модера/админа
						if ($row['user_level'] == 1 || $row['user_level'] == 2)
							{
								message_die(GENERAL_ERROR, "Вы не можете забанить администратора или модератора");
							}
						$user_name = $row['username'];
//Записываем бан
						$cur_date = TIMENOW;
						$sql = "INSERT INTO ". BB_BANLIST .
						" (ban_id, ban_userid, ban_ip, ban_email, ban_time, ban_time_exp, ban_theme, ban_by) VALUES
						  ('', $ban_userid, '','',$cur_date, $date,'" . str_replace("\'", "''", $warn_theme) ."','" . $userdata['user_id'] . "')";
						if (!(DB()->sql_query($sql)))
						{
							message_die(GENERAL_ERROR, 'Не удалось добавить бан', '', __LINE__, __FILE__, $sql);
						}
//Прибиваем сессию
						cache_rm_user_sessions ($ban_userid);
						delete_user_sessions ($ban_userid);
	 		 			message_die(GENERAL_MESSAGE, 'Пользователь ' . $user_name  . ' забанен до ' . date("H:i d-m-Y", $date));
		 		 		break;
					}
//===============================================
		 	 }
			else
			 {
//Бан по ИП
				// Look up relevent data for this post
//Выбераем ИП постера
				$sql = "SELECT poster_ip
					FROM " . POSTS_TABLE . "
					WHERE post_id = $post_id
					LIMIT 1";
				if ( !($result = DB()->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Не могу получить IP адрес постера', '', __LINE__, __FILE__, $sql);
				}

				if ( !($post_row = DB()->sql_fetchrow($result)) )
				{
					message_die(GENERAL_MESSAGE, $lang['No_such_post']);
				}

				$ip_this_post = $post_row['poster_ip'];

//===============================================
				if($plasdate_s == "inf")
						   {
							// Перманент
							$date = 0;


//Проверка на существующий бан
//===================================================
							$sql = "
									SELECT  b.ban_ip, b.ban_time_exp, b.ban_theme, b.ban_by, u2.username AS modername
									FROM ". BB_BANLIST ." b
									LEFT JOIN ". BB_USERS ." u2 ON (u2.user_id = b.ban_by)
									WHERE b.ban_ip = '" . $ip_this_post . "'
									LIMIT 1
								   ";
							if (!$result = DB()->sql_query($sql))
							 {
							  message_die(GENERAL_MESSAGE, "Нихера не работает, обратитесь к разработчику");
							 }
							$row = mysql_fetch_array($result);
							if ( $row != FALSE)
								{
									$t_exp = '';
									if ($row['ban_time_exp'] != 0)
										{
											$t_exp = "до: " . date("H:i d-m-Y", $row['ban_time_exp']);
										}
									else $t_exp = 'перманентно';
									message_die(GENERAL_MESSAGE, "Пользаватель с IP " . decode_ip($ip_this_post) . " уже забанен  " . $row['modername'] . ", срок ". $t_exp . ", причина: " . $row['ban_theme']);
									break;
								}
//Закончили проверку
//=======================================




							$cur_date = TIMENOW;
							$sql = "INSERT INTO ". BB_BANLIST .
							" (ban_id, ban_userid, ban_ip, ban_email, ban_time, ban_time_exp, ban_theme, ban_by) VALUES
							  ('', '', '" . $ip_this_post . "','',$cur_date, $date,'" . str_replace("\'", "''", $warn_theme) ."','" . $userdata['user_id'] . "')";
							if (!(DB()->sql_query($sql)))
							{
								message_die(GENERAL_ERROR, 'Не удалось добавить бан', '', __LINE__, __FILE__, $sql);
							}
				 		 	message_die(GENERAL_MESSAGE, 'Пользователь с IP ' . decode_ip($ip_this_post) . ' забанен перманентно');
	 	 					break;
					       }
				else {

						 switch($plasdate_s)
						  {
						    case "sel":
//Не выбран срок бана
				 		 		message_die(GENERAL_MESSAGE, 'Вы не выбрали срок бана!');
					 		 	break;
						    case "1d":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d")+1, date("Y"));
						        break;
						    case "2d":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d")+2, date("Y"));
						        break;
					    	case "3d":
					        	$date = mktime(date("H"), date("i"), 0, date("m")  , date("d")+3, date("Y"));
						        break;
						    case "1w":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d")+7, date("Y"));
						        break;
						    case "2w":
						        $date = mktime(date("H"), date("i"), 0, date("m"), date("d")+14, date("Y"));
					    	    break;
						    case "1m":
						        $date = mktime(date("H"), date("i"), 0, date("m")+1  , date("d"), date("Y"));
						        break;
						    case "2m":
						        $date = mktime(date("H"), date("i"), 0, date("m")+2  , date("d"), date("Y"));
						        break;
					    	case "3m":
					        	$date = mktime(date("H"), date("i"), 0, date("m")+3  , date("d"), date("Y"));
						        break;
						    case "6m":
						        $date = mktime(date("H"), date("i"), 0, date("m")+6  , date("d"), date("Y"));
						        break;
						    case "1y":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d"), date("Y")+1);
					    	    break;
						    case "2y":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d"), date("Y")+2);
						        break;
						    case "3y":
						        $date = mktime(date("H"), date("i"), 0, date("m")  , date("d"), date("Y")+3);
						        break;
						  }
					// Бан на время
//Проверка на существующий бан
//===================================================

							$sql = "
									SELECT  b.ban_ip, b.ban_time_exp, b.ban_theme, b.ban_by, u2.username AS modername
									FROM ". BB_BANLIST ." b
									LEFT JOIN ". BB_USERS ." u2 ON (u2.user_id = b.ban_by)
									WHERE b.ban_ip = '" . $ip_this_post . "'
									LIMIT 1
								   ";
							if (!$result = DB()->sql_query($sql))
							 {
							  message_die(GENERAL_MESSAGE, "Нихера не работает, обратитесь к разработчику");
							 }
							$row = mysql_fetch_array($result);
							if ( $row != FALSE)
								{
									$t_exp = '';
									if ($row['ban_time_exp'] != 0)
										{
											$t_exp = "до: " . date("H:i d-m-Y", $row['ban_time_exp']);
										}
									else $t_exp = 'перманентно';
									message_die(GENERAL_MESSAGE, "Пользаватель с IP " . decode_ip($ip_this_post) . " уже забанен  " . $row['modername'] . ", срок ". $t_exp . ", причина: " . $row['ban_theme']);
									break;
								}
//Закончили проверку
//=======================================


						$cur_date = TIMENOW;
						$sql = "INSERT INTO ". BB_BANLIST .
						" (ban_id, ban_userid, ban_ip, ban_email, ban_time, ban_time_exp, ban_theme, ban_by) VALUES
						  ('', '', '" . $ip_this_post . "','',$cur_date, $date,'" . str_replace("\'", "''", $warn_theme) ."','" . $userdata['user_id'] . "')";
						if (!(DB()->sql_query($sql)))
						{
							message_die(GENERAL_ERROR, 'Не удалось добавить бан', '', __LINE__, __FILE__, $sql);
						}
			 		 	message_die(GENERAL_MESSAGE, 'Пользователь с IP ' . decode_ip($ip_this_post) . ' забанен до ' . date("H:i d-m-Y", $date));
	 				 	break;
					}
//===============================================
			 }
		 }
		else
		 {
	 		message_die(GENERAL_MESSAGE, "Не заданны необходимые параметры" . $ban_userid. "|" . $post_id);
		 	break;
		 }
    }


case "unban":
	if ($ban_id != "none")
	 {
		$sql = "SELECT ban_userid, ban_ip, ban_time_exp, ban_by
		 FROM ". BB_BANLIST . "
		 WHERE ban_id = '" .$ban_id ."'
		 LIMIT 1
		";
		$result = DB()->sql_query($sql);
		$row = mysql_fetch_array($result);
		$cur_date = TIMENOW;
		$sql = "INSERT INTO " . UN_BB_BANLIST .
		" (id, userid, userip, ban_time_exp, un_ban_date, mod_id) VALUES
		  ('', '" .$row['ban_userid'] .  "', '" .$row['ban_ip'] .  "', '" .$row['ban_time_exp'] . "', $cur_date,'" . $userdata['user_id'] . "')";
		if (!(DB()->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Не удалось записать лог', '', __LINE__, __FILE__, $sql);
			}


		$sql = "DELETE FROM ". BB_BANLIST . "
		 WHERE ban_id = '" .$ban_id ."'
		 LIMIT 1
		";
		if (!(DB()->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Не удалось удалить бан', '', __LINE__, __FILE__, $sql);
		}


	 	message_die(GENERAL_MESSAGE, 'Бан снят');
	 	break;
	 }
	else
	 {
	 	message_die(GENERAL_MESSAGE, "Не заданны необходимые параметры");
	 	break;
	 }
}

// Выбераем имя юзера или ип
$user_name ='';
if ($ban_userid == -1)
	{
//Выбераем ИП постера
		$sql = "SELECT poster_ip
			FROM " . POSTS_TABLE . "
			WHERE post_id = $post_id
			LIMIT 1";
		if ( !($result = DB()->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Не могу получить IP адрес постера', '', __LINE__, __FILE__, $sql);
		}

		if ( !($post_row = DB()->sql_fetchrow($result)) )
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_post']);
		}
		$user_name = decode_ip($post_row['poster_ip']);

	}
else
	{
		$sql = "
				SELECT  username
				FROM ". BB_USERS ."
				WHERE user_id = '" . $ban_userid . "'
				LIMIT 1
			   ";
		if ( !($result = DB()->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Не могу получить имя пользователя', '', __LINE__, __FILE__, $sql);
		}
		$row = DB()->sql_fetchrow($result);
		$user_name = $row['username'];
	}




$nak_time = "sel";
$template->assign_vars(array(
'NAK_TIME' 	=> $nak_time,
'USER'		=> $ban_userid,
'POST_ID'   => $post_id,
'USER_NAME'	=> $user_name,
));
print_page('ban.tpl');

