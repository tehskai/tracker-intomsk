<?php
if (!defined('BB_ROOT')) die(basename(__FILE__));

$sql = "(SELECT i.invite_id
      FROM invites i
      LEFT JOIN bb_users u ON (u.user_id = i.new_user_id)
      WHERE i.new_user_id <> 0 AND u.username IS NULL)
      UNION
      (SELECT i.invite_id
      FROM invites i
      LEFT JOIN bb_users u ON (u.user_id = i.user_id)
      WHERE i.user_id <> 0 AND u.username IS NULL)";
$new_id = array();
if($result = DB()->fetch_rowset($sql))
{
   foreach ($result as $row)
   {
      $new_id[] = $row['invite_id'];
   }
$del_ids = implode(",",$new_id);
DB()->query("DELETE FROM invites WHERE invite_id IN($del_ids)");
}
?>