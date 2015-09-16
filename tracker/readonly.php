<?php
define('IN_PHPBB', true);
define('BB_ROOT', './');
require(BB_ROOT .'common.php');
$user->session_start();
if (!$userdata['session_logged_in']) redirect(append_sid("login.php?redirect={$_SERVER['REQUEST_URI']}", TRUE));
if( $userdata['user_level']!=MOD && $userdata['user_level']!=ADMIN){message_die(GENERAL_MESSAGE, 'Доступ только для администрации и модераторов.');}
$modid = $userdata['user_id'];

if(@$_REQUEST['action']=="add"){
  if(@isset($_REQUEST['userid']) && @isset($_REQUEST['expire']) && @isset($_REQUEST['descr'])){
    $userid = intval($_POST['userid']); $expire = strtotime($_POST['expire']); $reason = $_POST['descr'];
    $userid_data = get_userdata($userid);
    if($userid_data['user_readonly'] == 0){
      $sql = "INSERT INTO `readonly_active` (`id`, `active`, `user`, `moderator`, `expire`, `reason`) VALUES (NULL, '1', '".$userid."', '".$modid."', '".$expire."', '".$reason."');";
      DB()->sql_query($sql);
      $sql2 = "UPDATE `bb_users` SET `user_readonly` = '1' WHERE `user_id` =".$userid." LIMIT 1 ;";
      DB()->sql_query($sql2);
      $sql3 = "INSERT INTO `readonly_log` (`id`, `moderator`, `entry`) VALUES (NULL, '".$modid."', 'Заглушил пользователя ".$userid_data['username'].".');";
      DB()->sql_query($sql3);
      $readonly_notice = "Пользователь ".$userid_data['username']." заглушен.";
    } else {
      $readonly_notice = "Пользователь ".$userid_data['username']." уже был заглушен другим модератором. Или же он сам модератор. Или же он вообще админ.";
    }
    $template->assign_vars(array(
      'TPL_READONLY_SIMPLE' => true,
      'TPL_READONLY_NOTICE' => true,
      'READONLY_NOTICE' => "Пользователь ".$userid_data['username']." заглушен."
    ));
  } elseif (@isset($_GET['id'])) {
    $userid = intval($_GET['id']);
    $userid_data = get_userdata($userid);
    if($userid_data['user_readonly'] == 0){
      $template->assign_vars(array(
        'PAGE_TITLE'     =>  "Заглушить пользователя",
        'TPL_READONLY_SIMPLE' => true,
        'TPL_READONLY_ADD' => true,
        'READONLY_USERNAME' => $userid_data['username'],
        'READONLY_USERID' => intval($_GET['id'])
      ));
    } else {
      $template->assign_vars(array(
        'TPL_READONLY_SIMPLE' => true,
        'TPL_READONLY_NOTICE' => true,
        'READONLY_NOTICE' => "Пользователь ".$userid_data['username']." уже был заглушен другим модератором."
      ));
    }
  } else {
    $template->assign_vars(array(
      'TPL_READONLY_SIMPLE' => true,
      'TPL_READONLY_NOTICE' => true,
      'READONLY_NOTICE' => "Произошла ошибка."
    ));
  }
  print_page('readonly.tpl', 'simple');
} elseif (@$_GET['action']=="del" && @isset($_GET['id'])) {
    if(@!isset($_GET['id'])){message_die(GENERAL_MESSAGE, 'Ошибка при получении ID заглушки.');}
    $recordid = intval($_GET['id']);
    
    $sql = "SELECT `user`,`moderator`,`active` FROM `readonly_active` WHERE `id` = '".$recordid."' LIMIT 1";
    if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, 'Ошибка при получении ID заглушки.', '', __LINE__, __FILE__, $sql);

    $row = DB()->sql_fetchrowset($result);
    if ($row['0']['active'] == 0) {
      $template->assign_vars(array(
        'TPL_READONLY_SIMPLE' => true,
        'TPL_READONLY_NOTICE' => true,
        'READONLY_NOTICE' => "Заглушка уже была снята ранее."
      ));
      print_page('readonly.tpl', 'simple'); die;
    }
    $userid = $row['0']['user'];
    $moderator = $row['0']['moderator'];
    if(IS_MOD && $modid != $moderator) {
      message_die(GENERAL_MESSAGE, 'У Вас нет прав на удаление этой заглушки.');
    }
    $userid_data = get_userdata($userid);
    
    $sql = "UPDATE `readonly_active` SET `active` = '0' WHERE `id` =".$recordid.";";
    if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, 'Ошибка при снятии заглушки.', '', __LINE__, __FILE__, $sql);
    
    $sql = "UPDATE `bb_users` SET `user_readonly` = '0' WHERE `user_id` =".$userid." LIMIT 1 ;";
    if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, 'Ошибка при снятии заглушки.', '', __LINE__, __FILE__, $sql);
    
    $sql = "INSERT INTO `readonly_log` (`id`, `moderator`, `entry`) VALUES (NULL, '".$modid."', 'Снял заглушку с пользователя ".$userid_data['username'].".');";
    if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, 'Ошибка при записи в лог.', '', __LINE__, __FILE__, $sql);
    
    $template->assign_vars(array(
      'TPL_READONLY_SIMPLE' => true,
      'TPL_READONLY_NOTICE' => true,
      'READONLY_NOTICE' => "Заглушка с пользователя ".$userid_data['username']." снята."
    ));
    print_page('readonly.tpl', 'simple');
} elseif (@$_GET['action']=="cron") {
  if(!IS_ADMIN) {
    message_die(GENERAL_MESSAGE, 'У Вас нет прав на принудительный запуск крона.');
  }
	$sql = "SELECT id, expire, user FROM `readonly_active` WHERE active = 1";
	foreach (DB()->fetch_rowset($sql) as $row)
	{
    if($row['expire'] < time()) 
    {
      $userid_data = get_userdata($row['user']);
      $sql1 = "UPDATE `readonly_active` SET `active` = '0' WHERE `id` =".$row['id']." LIMIT 1;";
      DB()->sql_query($sql1);
      $sql2 = "UPDATE `bb_users` SET `user_readonly` = '0' WHERE `user_id` =".$row['user']." LIMIT 1;";
      DB()->sql_query($sql2);
      $sql3 = "INSERT INTO `readonly_log` (`id`, `moderator`, `entry`) VALUES (NULL, '".$modid."', 'Снял заглушку с пользователя ".$userid_data['username'].".');";
      DB()->sql_query($sql3);
    }
}
  message_die(GENERAL_MESSAGE, 'Принудительный запуск крона прошел успешно.');
} elseif (@$_GET['action']=="log") {
  
  ///message_die(GENERAL_MESSAGE, 'Доступ запрещен.');
  $sql = "SELECT * FROM `readonly_log` ORDER BY `id` DESC";
  if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, 'Ошибка при получении лога заглушек.', '', __LINE__, __FILE__, $sql);
  $readonly_log_row = DB()->sql_fetchrowset($result);
  $num_readonly_log_row = DB()->num_rows($result);
  DB()->sql_freeresult($result);
  $template->assign_vars(array(
    'PAGE_TITLE'     =>  "Лог заглушек",
    'READONLY_LOG_PRESENT' => true,
    'TPL_READONLY_LOG' => true
  ));
    for ($i = 0; $i < $num_readonly_log_row; $i++) {
    $modid_data = get_userdata($readonly_log_row[$i]['moderator']);
      $template->assign_block_vars('readonly_log_row', array(
        'MODID' => '<a href="profile.php?mode=viewprofile&u='.$readonly_log_row[$i]['moderator'].'">'.$modid_data['username'].'</a>',
        'ENTRY' => $readonly_log_row[$i]['entry']
      ));
    }
    print_page('readonly.tpl');
} else {
  if(IS_MOD){
    $sql = "SELECT * FROM `readonly_active` WHERE `active` = '1' AND `moderator` = '".$modid."' ORDER BY `id` DESC";
  } else {
    $sql = "SELECT * FROM `readonly_active` WHERE `active` = '1' ORDER BY `id` DESC";
  }
  if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, 'Ошибка при получении списка заглушек.', '', __LINE__, __FILE__, $sql);
  $readonly_row = DB()->sql_fetchrowset($result);
  $num_readonly_row = DB()->num_rows($result);
  DB()->sql_freeresult($result);
  if ($num_readonly_row > 0) {
  $template->assign_vars(array(
    'PAGE_TITLE'     =>  "Панель управления заглушками",
    'READONLY_PRESENT' => true,
    'TPL_READONLY_SHOW' => true
   ));
  for ($i = 0; $i < $num_readonly_row; $i++) {
    $userid_data = get_userdata($readonly_row[$i]['user']);
    $modid_data = get_userdata($readonly_row[$i]['moderator']);
      $template->assign_block_vars('readonly_row', array(
        'USERID' => '<a href="profile.php?mode=viewprofile&u='.$readonly_row[$i]['user'].'">'.$userid_data['username'].'</a>',
        'MODID' => '<a href="profile.php?mode=viewprofile&u='.$readonly_row[$i]['moderator'].'">'.$modid_data['username'].'</a>',
        'EXPIRE' => date('d.m.Y', $readonly_row[$i]['expire']),
        'REASON' => $readonly_row[$i]['reason'],
        'MODIFY' => '<a href="readonly.php?action=mod&id='.$readonly_row[$i]['id'].'">Редактировать</a>',
        'DELETE' => '<input type="submit" name="usersubmit" class="lite" value="Удалить" onclick="window.open(\'readonly.php?action=del&id='.$readonly_row[$i]['id'].'\', \'readonly\', \'HEIGHT=310,resizable=yes,WIDTH=400\');return false;" />')
        );
    }
  } else {
    $template->assign_vars(array(
      'PAGE_TITLE'     =>  "Панель управления заглушками",
      'READONLY_PRESENT' => false,
      'TPL_READONLY_SHOW' => true
     ));
  }
  print_page('readonly.tpl');
}
?>