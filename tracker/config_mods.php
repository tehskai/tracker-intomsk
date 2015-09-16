<?php

/*
  This is a modules config!
  This file contain the settings for:
   - Last added torrents
   - Top downloaded torrents
   - Top seeders
   - Top leechers
   - No avatar
   - Gender
   - Torrent hits by Invincible
   - Seed points
*/

if (!defined('BB_ROOT')) die(basename(__FILE__));

$bb_cfg['reg_agrement'] = true;
/*
//Last added torrents
$bb_cfg['t_last_added_num'] = 10;

//Top downloaded torrents
$bb_cfg['t_top_downloaded'] = 0;

//Top seeders
$bb_cfg['t_top_uploaders'] = 0;

//Top leechers
$bb_cfg['t_top_downloaders'] = 0;
*/
// No avatar
$bb_cfg['show_no_avatar'] = true;
$bb_cfg['no_avatar'] = 'images/avatars/gallery/noavatar.png';

// Gender
$bb_cfg['gender'] = true;

// Torrent hits by Invincible
$bb_cfg['t_tor_hits'] = 20;
$bb_cfg['hit_link'] = "/viewtopic.php?t=";

// Seed points 
$bb_cfg['seed_points_enabled'] = true;

$bb_cfg['seed_points_per_hour'] = 1; // Base value

$bb_cfg['seed_points_f'] = array( // Factors depending on count of seeding torrents
	'50' => 3,
	'25' => 2,
	'1'  => 1,
);

$bb_cfg['seed_points_ex'] = array( // Exchanges ary
	'upload' => array( // Please keep order ascending!
		# traffic (in GiB) => cost
		'1'   => 150,
		'2'   => 275,
		'5'   => 325,
		'10'  => 450,
		'20'  => 575,
		'50'  => 650,
		'100' => 775,
		'200' => 850,
		'500' => 975,
		'1024' => 1500
	),
	'invite' => array(
		# invites => cost
		'1'   => 300,
		'2'   => 550,
		'3'   => 750,
		'4'   => 900,
		'5'   => 950
	),
	'vip' => array(
		# => cost
		'1'   => 2000,
		'2'   => 2500,
		'3'   => 4000,
		'4'   => 7000
	),
);
// Seed points

// ICQ_STATUS_SHOW
$bb_cfg['show_status_icons'] = false;
// Invites MOD
$bb_cfg['new_user_reg_only_by_invite'] = true;
$bb_cfg['enable_non_invite_reg_with_ips'] = false;
$bb_cfg['allow_non_invite_reg_ips'] = array(
'109.124.'
);
//ratio null mod
$bb_cfg['rationull_enabled']		   = true ;
$bb_cfg['ratio_to_null']			   = 0.3 ;
//end
// Золотые дни
$bb_cfg['gold'] = array("17-04", "18-04", "19-04", "20-04", "21-04", "22-04", "23-04", "24-04");

?>