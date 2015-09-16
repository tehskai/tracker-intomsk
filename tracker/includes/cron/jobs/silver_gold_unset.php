<?php
if (!defined('BB_ROOT')) die(basename(__FILE__));
require(INC_DIR .'functions_torrent.php');
$sql='SELECT attach_id FROM bb_bt_torrents WHERE '.time().' > tor_type_time  AND tor_type_time != -1 AND tor_type IN(1,2)';
if($result=DB()->sql_query($sql))
{
	while ($row = DB()->sql_fetchrow($result))
	{
		change_tor_type($row['attach_id'], 0);
	}
}
?>