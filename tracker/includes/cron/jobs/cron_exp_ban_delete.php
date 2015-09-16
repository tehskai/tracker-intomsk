<?php
if (!defined('BB_ROOT')) die(basename(__FILE__));

$time = TIMENOW;
DB()->query("DELETE FROM ". BB_BANLIST . " WHERE ban_time_exp < " . $time . " AND ban_time_exp <> 0");
