<?php
if (!defined('BB_ROOT')) die(basename(__FILE__));

$modid = "-746";
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

?>