<?php

if (!defined('BB_ROOT')) die(basename(__FILE__));

global $tr_cfg;

$releaser = DL_STATUS_RELEASER;

if($bb_cfg['announce_type'] != 'xbt')
{
	define('NEW_BB_BT_LAST_TORSTAT',  'new_bt_last_torstat');
	define('OLD_BB_BT_LAST_TORSTAT',  'old_bt_last_torstat');
	define('NEW_BB_BT_LAST_USERSTAT', 'new_bt_last_userstat');
	define('OLD_BB_BT_LAST_USERSTAT', 'old_bt_last_userstat');

	DB()->query("DROP TABLE IF EXISTS ". NEW_BB_BT_LAST_TORSTAT .", ". NEW_BB_BT_LAST_USERSTAT);
	DB()->query("DROP TABLE IF EXISTS ". OLD_BB_BT_LAST_TORSTAT .", ". OLD_BB_BT_LAST_USERSTAT);

	DB()->query("CREATE TABLE ". NEW_BB_BT_LAST_TORSTAT  ." LIKE ". BB_BT_LAST_TORSTAT);
	DB()->query("CREATE TABLE ". NEW_BB_BT_LAST_USERSTAT ." LIKE ". BB_BT_LAST_USERSTAT);

	DB()->expect_slow_query(600);

	// Update dlstat (part 1)
	if ($tr_cfg['update_dlstat'])
	{
		// ############################ Tables LOCKED ################################
		DB()->lock(array(
			BB_BT_TRACKER,
			NEW_BB_BT_LAST_TORSTAT,
		));

		// Get PER TORRENT user's dlstat from tracker
		DB()->query("
			INSERT INTO ". NEW_BB_BT_LAST_TORSTAT ."
			(topic_id, user_id, dl_status, up_add, down_add, release_add, seed_time_add, seeder, speed_up, speed_down)
			SELECT
			topic_id, user_id, IF(releaser, $releaser, seeder), SUM(up_add), SUM(down_add), IF(releaser, SUM(up_add), 0), SUM(seed_time_add), seeder, SUM(speed_up), SUM(speed_down)
			FROM ". BB_BT_TRACKER ."
		WHERE (up_add != 0 OR down_add != 0 OR seed_time_add != 0)
			GROUP BY topic_id, user_id
		");

		// Reset up/down additions in tracker
	DB()->query("UPDATE ". BB_BT_TRACKER ." SET up_add = 0, down_add = 0, seed_time_add = 0");

		DB()->unlock();
		// ############################ Tables UNLOCKED ##############################
	}

}
// Update last seeder info in BUF
DB()->query("
	REPLACE INTO ". BUF_LAST_SEEDER ."
		(topic_id, seeder_last_seen)
	SELECT
		topic_id, ". TIMENOW ."
	FROM ". BB_BT_TRACKER ."
	WHERE seeder = 1
	GROUP BY topic_id
");

// Clean peers table
if ($tr_cfg['autoclean'])
{
	$announce_interval = max(intval($bb_cfg['announce_interval']), 60);
	$expire_factor     = max(floatval($tr_cfg['expire_factor']), 1);
	$peer_expire_time  = TIMENOW - floor($announce_interval * $expire_factor);

	DB()->query("DELETE FROM ". BB_BT_TRACKER ." WHERE update_time < $peer_expire_time");
}

if($bb_cfg['announce_type'] == 'xbt')
{
	DB()->query("
		INSERT IGNORE INTO ". BB_BT_TORSTAT ."
			(topic_id, user_id)
		SELECT
			topic_id, user_id
		FROM ". BB_BT_TRACKER ."
		WHERE IF(releaser, $releaser, seeder) = ". DL_STATUS_COMPLETE ." AND (up_add != 0 OR down_add != 0)
	");
	// Reset up/down additions in tracker
	DB()->query("UPDATE ". BB_BT_TRACKER ." SET up_add = 0, down_add = 0");
}
// Delete not registered torrents from tracker
/*
DB()->query("
	DELETE tr
	FROM ". BB_BT_TRACKER ." tr
	LEFT JOIN ". BB_BT_TORRENTS ." tor USING(topic_id)
	WHERE tor.topic_id IS NULL
");
*/

if($bb_cfg['announce_type'] != 'xbt')
{
	// Update dlstat (part 2)
	if ($tr_cfg['update_dlstat'])
	{
		// Set "only 1 seeder" bonus
		DB()->query("
			UPDATE
			  ". NEW_BB_BT_LAST_TORSTAT  ." tb,
			  ". BB_BT_TRACKER_SNAP      ." sn
			SET
			  tb.bonus_add = tb.up_add
			WHERE
			      tb.topic_id = sn.topic_id
			  AND sn.seeders = 1
			  AND tb.up_add != 0
			  AND tb.dl_status = ". DL_STATUS_COMPLETE ."
		");

	// Get SUMMARIZED user's dlstat
	DB()->query("
		INSERT INTO ". NEW_BB_BT_LAST_USERSTAT ."
			(user_id, topics_count, up_add, down_add, release_add, bonus_add, seed_points_raw, speed_up, speed_down)
		SELECT
			user_id, SUM(seeder), SUM(up_add), SUM(down_add), SUM(release_add), SUM(bonus_add), ((SUM(seed_time_add)*{$bb_cfg['seed_points_per_hour']})/(3600*SUM(seeder))), SUM(speed_up), SUM(speed_down)
		FROM ". NEW_BB_BT_LAST_TORSTAT ."
		GROUP BY user_id
	");

	// Update TOTAL user's dlstat
	if (in_array(date("d-m"), $bb_cfg['gold'])){
		DB()->query("
			UPDATE
				". BB_BT_USERS             ." u,
				". NEW_BB_BT_LAST_USERSTAT ." ub
			SET
				u.u_up_total   = u.u_up_total   + ub.up_add,
				u.u_down_total = u.u_down_total,
				u.u_up_release = u.u_up_release + ub.release_add,
				u.u_up_bonus   = u.u_up_bonus   + ub.bonus_add,
				u.seed_points  = u.seed_points  + (". get_bonus_sql_case ($bb_cfg['seed_points_f'], 'ub.topics_count', 'ub.seed_points_raw') .")
			WHERE u.user_id = ub.user_id
		");
	}else{
		DB()->query("
			UPDATE
				". BB_BT_USERS             ." u,
				". NEW_BB_BT_LAST_USERSTAT ." ub
			SET
				u.u_up_total   = u.u_up_total   + ub.up_add,
				u.u_down_total = u.u_down_total + ub.down_add,
				u.u_up_release = u.u_up_release + ub.release_add,
				u.u_up_bonus   = u.u_up_bonus   + ub.bonus_add,
				u.seed_points  = u.seed_points  + (". get_bonus_sql_case ($bb_cfg['seed_points_f'], 'ub.topics_count', 'ub.seed_points_raw') .")
			WHERE u.user_id = ub.user_id
		");
	}


		// Delete from MAIN what exists in BUF but not exsits in NEW
		DB()->query("
			DELETE main
			FROM ". BB_BT_DLSTATUS_MAIN ." main
			INNER JOIN (
				". NEW_BB_BT_LAST_TORSTAT ." buf
				LEFT JOIN ". BB_BT_DLSTATUS_NEW ." new USING(user_id, topic_id)
			) USING(user_id, topic_id)
			WHERE new.user_id IS NULL
				AND new.topic_id IS NULL
		");

		// Update DL-Status
		DB()->query("
			REPLACE INTO ". BB_BT_DLSTATUS_NEW ."
				(user_id, topic_id, user_status)
			SELECT
				user_id, topic_id, dl_status
			FROM ". NEW_BB_BT_LAST_TORSTAT ."
		");

		// Update PER TORRENT DL-Status (for "completed" counter)
		DB()->query("
			INSERT IGNORE INTO ". BB_BT_TORSTAT ."
				(topic_id, user_id)
			SELECT
				topic_id, user_id
			FROM ". NEW_BB_BT_LAST_TORSTAT ."
			WHERE dl_status = ". DL_STATUS_COMPLETE ."
		");
	}

	DB()->query("
		RENAME TABLE
		". BB_BT_LAST_TORSTAT     ." TO ". OLD_BB_BT_LAST_TORSTAT .",
		". NEW_BB_BT_LAST_TORSTAT ." TO ". BB_BT_LAST_TORSTAT ."
	");
	DB()->query("DROP TABLE IF EXISTS ". NEW_BB_BT_LAST_TORSTAT .", ". OLD_BB_BT_LAST_TORSTAT);

	DB()->query("
		RENAME TABLE
		". BB_BT_LAST_USERSTAT     ." TO ". OLD_BB_BT_LAST_USERSTAT .",
		". NEW_BB_BT_LAST_USERSTAT ." TO ". BB_BT_LAST_USERSTAT ."
	");
	DB()->query("DROP TABLE IF EXISTS ". NEW_BB_BT_LAST_USERSTAT .", ". OLD_BB_BT_LAST_USERSTAT);

	DB()->expect_slow_query(10);
}