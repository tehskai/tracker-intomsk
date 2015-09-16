<?php

if (!defined('BB_ROOT')) die(basename(__FILE__));
if (defined('PAGE_HEADER_SENT')) return;


// Parse and show the overall page header

global $page_cfg, $userdata, $user, $ads, $bb_cfg, $template, $lang, $images;

// Golden Days
if (in_array(date("d-m"), $bb_cfg['gold']))
{
  $template->assign_vars(array(
  'SHOW_GOLDEN_DAYS' => true
  ));
}

$logged_in = (int) !empty($userdata['session_logged_in']);
$is_admin  = ($logged_in && IS_ADMIN);
$is_mod    = ($logged_in && IS_MOD);

// Generate logged in/logged out status
if ($logged_in)
{
	$u_login_logout = BB_ROOT ."login.php?logout=1";
}
else
{
	$u_login_logout = BB_ROOT ."login.php";
}

show_bt_userdata_my($userdata['user_id']);
/*if ($bb_cfg['bt_show_dl_stat_on_index'] && !IS_GUEST)
{
	show_bt_userdata_index($userdata['user_id']);
}*/
$profile_user_id = $userdata['user_id'];

$seeding = $leeching = $releasing = array();

$sql = 'SELECT f.forum_id, f.forum_name, t.topic_title, tor.size,sn.seeders, sn.leechers, tr.*
	FROM '. BB_FORUMS .' f, '. BB_TOPICS .' t, '. BB_BT_TRACKER .' tr, '. BB_BT_TORRENTS .' tor, '. BB_BT_TRACKER_SNAP ." sn
	WHERE tr.user_id = $profile_user_id
		AND tr.topic_id = tor.topic_id
		AND sn.topic_id = tor.topic_id
		AND tor.topic_id = t.topic_id
		AND t.forum_id = f.forum_id
	GROUP BY tr.topic_id
	ORDER BY f.forum_name, t.topic_title";

if (!$result = DB()->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not query users torrent profile information', '', __LINE__, __FILE__, $sql);
}

if ($rowset = DB()->sql_fetchrowset($result))
{
	DB()->sql_freeresult($result);
	$rowset_count = count($rowset);

	for ($i=0; $i<$rowset_count; $i++)
	{
		if ($rowset[$i]['releaser'])
		{
			$releasing[] = $rowset[$i];
		}
		else if ($rowset[$i]['seeder'])
		{
			$seeding[] = $rowset[$i];
		}
		else
		{
			$leeching[] = $rowset[$i];
		}
	}
	unset($rowset);
}

$seeding_count = count($seeding);
$leeching_count = count($leeching);
$releasing_count = count($releasing);

// Online userlist
if (defined('SHOW_ONLINE') && SHOW_ONLINE)
{
	$online_full = !empty($_REQUEST['online_full']);
	$online_list = ($online_full) ? 'online' : 'online_short';

	${$online_list} = array(
		'stat'     => '',
		'userlist' => '',
		'cnt'      => '',
	);

if (defined('IS_GUEST') && !(IS_GUEST || IS_USER))
	{
		$template->assign_var('SHOW_ONLINE_LIST');

		if (!${$online_list} = CACHE('bb_cache')->get($online_list))
		{
			require(INC_DIR .'online_userlist.php');
		}
	}

	$template->assign_vars(array(
		'TOTAL_USERS_ONLINE'  => ${$online_list}['stat'],
		'LOGGED_IN_USER_LIST' => ${$online_list}['userlist'],
		'USERS_ONLINE_COUNTS' => ${$online_list}['cnt'],
		'RECORD_USERS'        => sprintf($lang['RECORD_ONLINE_USERS'], $bb_cfg['record_online_users'], bb_date($bb_cfg['record_online_date'])),
		'U_VIEWONLINE'        => "viewonline.php",
	));
}

// Info about new private messages
$icon_pm = $images['pm_no_new_msg'];
$pm_info = $lang['NO_NEW_PM'];
$have_new_pm = $have_unread_pm = 0;

if ($logged_in && empty($gen_simple_header) && !defined('IN_ADMIN'))
{
	if ($userdata['user_new_privmsg'])
	{
		$have_new_pm = $userdata['user_new_privmsg'];
		$icon_pm = $images['pm_new_msg'];
		$pm_info = declension($userdata['user_new_privmsg'], $lang['NEW_PMS_DECLENSION'], $lang['NEW_PMS_FORMAT']);

		if ($userdata['user_last_privmsg'] > $userdata['user_lastvisit'] && defined('IN_PM'))
		{
			$userdata['user_last_privmsg'] = $userdata['user_lastvisit'];

			db_update_userdata($userdata, array(
				'user_last_privmsg' => $userdata['user_lastvisit'],
			));

			$have_new_pm = ($userdata['user_new_privmsg'] > 1);
		}
	}
	if (!$have_new_pm && $userdata['user_unread_privmsg'])
	{
		// synch unread pm count
		if (defined('IN_PM'))
		{
			$row = DB()->fetch_row("
				SELECT COUNT(*) AS pm_count
				FROM ". BB_PRIVMSGS ."
				WHERE privmsgs_to_userid = ". $userdata['user_id'] ."
					AND privmsgs_type = ". PRIVMSGS_UNREAD_MAIL ."
				GROUP BY privmsgs_to_userid
			");

			$real_unread_pm_count = (int) $row['pm_count'];

			if ($userdata['user_unread_privmsg'] != $real_unread_pm_count)
			{
				$userdata['user_unread_privmsg'] = $real_unread_pm_count;

				db_update_userdata($userdata, array(
					'user_unread_privmsg' => $real_unread_pm_count,
				));
			}
		}

		$pm_info = declension($userdata['user_unread_privmsg'], $lang['UNREAD_PMS_DECLENSION'], $lang['UNREAD_PMS_FORMAT']);
		$have_unread_pm = true;
	}
}
$template->assign_vars(array(
	'HAVE_NEW_PM'    => $have_new_pm,
	'HAVE_UNREAD_PM' => $have_unread_pm,
));

// Start add - Complete banner MOD
$forum_idd = !empty($_REQUEST['f']);
$forum_idd = ($forum_idd) ? @intval($_GET['f']) : 0;
//echo $forum_idd;
$time_now=time();
$hour_now=bb_date('Hi',$time_now,$bb_cfg['board_timezone']);
$date_now=bb_date('Ymd',$time_now,$bb_cfg['board_timezone']);
$week_now=bb_date('w',$time_now,$bb_cfg['board_timezone']);
$sql_level= ($userdata['user_id']==ANONYMOUS) ? ANONYMOUS : (($userdata['user_level']==ADMIN) ? MOD : (($userdata['user_level']==MOD) ? ADMIN : $userdata['user_level'])); 
$sql = "SELECT DISTINCT * FROM ".BB_BANNERS ."
    WHERE banner_active
    AND IF(banner_level_type,IF(banner_level_type=1,".intval($sql_level)."<=banner_level,IF(banner_level_type=2,".intval($sql_level).">=banner_level,".intval($sql_level)."<>banner_level)),banner_level=".intval($sql_level).")
    AND (banner_timetype=0 
    OR (( $hour_now BETWEEN time_begin AND time_end) AND ((banner_timetype=2
    OR (( $week_now BETWEEN date_begin AND date_end) AND banner_timetype=4)
    OR (( $date_now BETWEEN date_begin AND date_end) AND banner_timetype=6)
    )))) ORDER BY banner_spot,banner_weigth*SUBSTRING(RAND(),6,2) DESC";
if ( !($result = DB()->sql_query($sql)) )
{
  message_die(GENERAL_ERROR, "Couldn't get banners data", "", __LINE__, __FILE__, $sql);
} 
$banners = array();
$i=0;
while ($banners[$i] = DB()->sql_fetchrow($result))
{
  $cookie_name = $bb_cfg['cookie_prefix'] . '_b_' . $banners[$i]['banner_id'];
  if ( !(@$_COOKIE[@$cookie_name] && $banners[$i]['banner_filter']) )
  {
    $banner_spot=$banners[$i]['banner_spot'];
    if ($banner_spot<>@$last_spot  AND ($banners[$i]['banner_forum']==@$forum_idd || empty($banners[$i]['banner_forum'])))
    {
      $banner_size = '';
      $banner_size = ($banners[$i]['banner_width']<>'') ? ' width="'.$banners[$i]['banner_width'].'"' : '';
      $banner_size .= ($banners[$i]['banner_height']<>'') ? ' height="'.$banners[$i]['banner_height'].'"' : '';     switch ($banners[$i]['banner_type'])
      {
        case 6 :
          // swf file
          $template->assign_vars(array('BANNER_'.$banner_spot.'_IMG' => '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" id="macromedia'.$i.'" '.$banner_size.' align="abscenter"><param name="allowScriptAccess" value="sameDomain" /><param name=movie value="'.$banners[$i]['banner_name'].'?clickTAG='.append_sid('redirect.'.$phpEx.'?banner_id='.$banners[$i]['banner_id']).'"><param name=quality value=high><embed src="'.$banners[$i]['banner_name'].'?clickTAG='.append_sid('redirect.'.$phpEx.'?banner_id='.$banners[$i]['banner_id']).'" quality=high name="macromedia'.$i.'"  align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" autostart="true" /><noembed><a href="'.append_sid('redirect.'.$phpEx.'?banner_id='.$banners[$i]['banner_id']).'" target="_blank">'.$banners[$i]['banner_description'].'</a></noembed></object>')); 
          break;
        case 4 :
          // custom code
          $template->assign_var('BANNER_'.$banner_spot.'_IMG', $banners[$i]['banner_name'] );
          $template->assign_var('BANNER_'.$banner_spot.'_IMG', '<br />'.$banners[$i]['banner_name'].'<br />' );

          break;
        case 2 :
          // Text link
          $template->assign_var('BANNER_'.$banner_spot.'_IMG', '<a href="redirect.php?banner_id='.$banners[$i]['banner_id'].'" target="_blank" alt="'.$banners[$i]['banner_description'].'" title="'.$banners[$i]['banner_description'].'">'.$banners[$i]['banner_name'].'</a>');
          break;
        case 0 :
        default: 
          $template->assign_var('BANNER_'.$banner_spot.'_IMG', '<a href="redirect.php?banner_id='.$banners[$i]['banner_id'].'" target="_blank"><img src="'.$banners[$i]['banner_name'].'" '.$banner_size.' border="0" alt="'.$banners[$i]['banner_description'].'" title="'.$banners[$i]['banner_description'].'" /></a>');
      }
      @$banner_show_list.= ', '.$banners[$i]['banner_id'];
    }
    $last_spot = (@$banners[$i]['banner_forum']==@$forum_idd || empty($banners[$i]['banner_forum'])) ? $banner_spot : @$last_spot;
  }
  $i++;
}
// End add - Complete banner MOD

// The following assigns all _common_ variables that may be used at any point in a template
// Report
//
// Report list link
//
if ($bb_cfg['reports_enabled'])
{
	if (empty($gen_simple_header) && ($userdata['user_level'] == ADMIN || (!$bb_cfg['report_list_admin'] && $userdata['user_level'] == MOD)))
	{
		if (!function_exists("report_count_obtain"))
			include(INC_DIR . "functions_report.php");

		$report_count = report_count_obtain();
		if ($report_count > 0)
		{
			$template->assign_block_vars('switch_report_list_new', array());

			$report_list = $lang['REPORTS'];
			$report_list .= ($report_count == 1) ? $lang['NEW_REPORT'] : sprintf($lang['NEW_REPORTS'], $report_count);
		}
		else
		{
			$template->assign_block_vars('switch_report_list', array());

			$report_list = $lang['REPORTS'] . $lang['NO_NEW_REPORTS'];
		}
	}
	else
	{
		$report_list = '';
	}
	//
	// Get report general module and create report link
	//
	if (empty($gen_simple_header))
	{
		if (!function_exists("report_count_obtain"))
			include(INC_DIR . "functions_report.php");

		$report_general = report_modules('name', 'report_general');

		if ($report_general && $report_general->auth_check('auth_write'))
		{
			$template->assign_block_vars('switch_report_general', array());

			$template->assign_vars(array(
				'U_WRITE_REPORT' => "report.php?mode=" . $report_general->mode,
				'L_WRITE_REPORT' => $report_general->lang['WRITE_REPORT'])
			);
		}
	}
}
else $report_list = '';
// Report [END]

$template->assign_vars(array(
	'SHOW_ADMIN_OPTIONS' => (IS_ADMIN && !defined('IN_ADMIN')),
	'SHOW_AM'            => (IS_ADMIN || IS_MOD),
	'SHOW_USER_OPTIONS'  => (!IS_GUEST),
	'SIMPLE_HEADER'      => !empty($gen_simple_header),
	'IN_ADMIN'           => defined('IN_ADMIN'),
	'QUIRKS_MODE'        => !empty($page_cfg['quirks_mode']),
	'SHOW_ADS'           => (!$logged_in || isset($bb_cfg['show_ads_users'][$user->id]) || (!($is_admin || $is_mod) && $user->show_ads)),
	'USER_HIDE_CAT'      => (BB_SCRIPT == 'index'),

	'USER_RUS'           => ($userdata['user_lang'] != 'english') ? true : false,

	'INCLUDE_BBCODE_JS'  => !empty($page_cfg['include_bbcode_js']),
	'USER_OPTIONS_JS'    => ($logged_in) ? bb_json_encode($user->opt_js) : '{}',

	'USE_TABLESORTER'    => !empty($page_cfg['use_tablesorter']),

	'SITENAME'           => $bb_cfg['sitename'],
	'U_INDEX'            => BB_ROOT ."index.php",
	'T_INDEX'            => sprintf($lang['FORUM_INDEX'], $bb_cfg['sitename']),

	'IS_GUEST'           => IS_GUEST,
	'IS_USER'            => IS_USER,
	'IS_ADMIN'           => IS_ADMIN,
	'IS_MOD'             => IS_MOD,
	'IS_AM'              => IS_AM,

	'FORUM_PATH'         => FORUM_PATH,
	'FULL_URL'           => FULL_URL,
	'RELEASING'          => ($releasing_count) ? ($releasing_count)  : 0,
	'SEEDING'            => ($seeding_count) ? ($seeding_count)  : 0,
	'LEECHING'           => ($leeching_count) ?  ($leeching_count)  : 0,

	'LAST_VISIT_DATE'    => ($logged_in) ? sprintf($lang['YOU_LAST_VISIT'], bb_date($userdata['user_lastvisit'], $bb_cfg['last_visit_date_format'])) : '',
	'CURRENT_TIME'       => sprintf($lang['CURRENT_TIME'], bb_date(TIMENOW, $bb_cfg['last_visit_date_format'])),
	'S_TIMEZONE'         => sprintf($lang['ALL_TIMES'], $lang[''.str_replace(',', '.', floatval($bb_cfg['board_timezone'])).'']),

	'PM_INFO'            => $pm_info,
	'PRIVMSG_IMG'        => $icon_pm,

	// Report
	'REPORT_LIST'        => $report_list,
	'U_REPORT_LIST'      => "report.php",
	// Report [END]

	'LOGGED_IN'          => $logged_in,
	'SESSION_USER_ID'    => $userdata['user_id'],
	'THIS_USER'          => profile_url2($userdata),
	'THIS_AVATAR'  	     => get_avatar($userdata['user_avatar'], $userdata['user_avatar_type'], !bf($userdata['user_opt'], 'user_opt', 'allow_avatar')),
	'SHOW_LOGIN_LINK'    => !defined('IN_LOGIN'),
	'AUTOLOGIN_DISABLED' => !$bb_cfg['allow_autologin'],
	'S_LOGIN_ACTION'     => BB_ROOT ."login.php",

	'U_CUR_DOWNLOADS'    => PROFILE_URL . $userdata['user_id'],
	'U_FAQ'              => $bb_cfg['faq_url'],
	'U_FORUM'            => "viewforum.php",
	'U_GROUP_CP'         => "groupcp.php",
	'U_LOGIN_LOGOUT'     => $u_login_logout,
	'U_MEMBERLIST'       => "memberlist.php",
	'U_MODCP'            => "modcp.php",
	'U_OPTIONS'          => "profile.php?mode=editprofile",
	'U_PRIVATEMSGS'      => "privmsg.php?folder=inbox",
	'U_PROFILE'          => PROFILE_URL . $userdata['user_id'],
	'U_READ_PM'          => "privmsg.php?folder=inbox". (($userdata['user_newest_pm_id'] && $userdata['user_new_privmsg'] == 1) ? "&mode=read&p={$userdata['user_newest_pm_id']}" : ''),
	'U_REGISTER'         => "profile.php?mode=register",
	'U_SEARCH'           => "search.php",
	'U_SEND_PASSWORD'    => "profile.php?mode=sendpassword",
	'U_TERMS'            => $bb_cfg['terms_and_conditions_url'],
	'U_TRACKER'          => "tracker.php",
	'U_GALLERY'          => "gallery.php",

	'SHOW_SIDEBAR1'      => (!empty($page_cfg['show_sidebar1'][BB_SCRIPT]) || $bb_cfg['show_sidebar1_on_every_page']),
	'SHOW_SIDEBAR2'      => (!empty($page_cfg['show_sidebar2'][BB_SCRIPT]) || $bb_cfg['show_sidebar2_on_every_page']),

	// Common urls
	'CAT_URL'            => BB_ROOT . CAT_URL,
	'DOWNLOAD_URL'       => BB_ROOT . DOWNLOAD_URL,
	'FORUM_URL'          => BB_ROOT . FORUM_URL,
	'GROUP_URL'          => BB_ROOT . GROUP_URL,
	'NEWEST_URL'         => '&amp;view=newest#newest',
	'POST_URL'           => BB_ROOT . POST_URL,
	'PROFILE_URL'        => BB_ROOT . PROFILE_URL,
	'TOPIC_URL'          => BB_ROOT . TOPIC_URL,

	'AJAX_HTML_DIR'      => AJAX_HTML_DIR,
	'AJAX_HANDLER'       => BB_ROOT .'ajax.php',

	'ONLY_NEW_POSTS'     => ONLY_NEW_POSTS,
	'ONLY_NEW_TOPICS'    => ONLY_NEW_TOPICS,

	// Misc
	'DEBUG'              => DEBUG,
	'BOT_UID'            => BOT_UID,
	'COOKIE_MARK'        => COOKIE_MARK,
	'SID'                => $userdata['session_id'],
	'SID_HIDDEN'         => '<input type="hidden" name="sid" value="'. $userdata['session_id'] .'" />',

	'CHECKED'            => HTML_CHECKED,
	'DISABLED'           => HTML_DISABLED,
	'READONLY'           => HTML_READONLY,
	'SELECTED'           => HTML_SELECTED,

	'U_SEARCH_SELF_BY_LAST' => "search.php?uid={$userdata['user_id']}&amp;o=5",
));

if (!empty($page_cfg['dl_links_user_id']))
{
	$dl_link = "search.php?dlu={$page_cfg['dl_links_user_id']}&amp;";

	$template->assign_vars(array(
		'SHOW_SEARCH_DL'       => true,
		'U_SEARCH_DL_WILL'     => $dl_link .'dlw=1',
		'U_SEARCH_DL_DOWN'     => $dl_link .'dld=1',
		'U_SEARCH_DL_COMPLETE' => $dl_link .'dlc=1',
		'U_SEARCH_DL_CANCEL'   => $dl_link .'dla=1',
	));
}

if (!empty($page_cfg['show_torhelp'][BB_SCRIPT]) && !empty($userdata['torhelp']))
{
	$ignore_time = !empty($_COOKIE['torhelp']) ? (int) $_COOKIE['torhelp'] : 0;

	if (TIMENOW > $ignore_time)
	{
		if ($ignore_time)
		{
			bb_setcookie('torhelp', '', COOKIE_EXPIRED);
		}

		$sql = "
			SELECT topic_id, topic_title
			FROM ". BB_TOPICS ."
			WHERE topic_id IN(". $userdata['torhelp'] .")
			LIMIT 8
		";
		$torhelp_topics = array();

		foreach (DB()->fetch_rowset($sql) as $row)
		{
			$torhelp_topics[] = '<a href="viewtopic.php?t='. $row['topic_id'] .'">'. $row['topic_title'] .'</a>';
		}

		$template->assign_vars(array(
			'TORHELP_TOPICS'  => join("</li>\n<li>", $torhelp_topics),
		));
	}
}

if (DBG_USER)
{
	$template->assign_vars(array(
		'INCLUDE_DEVELOP_JS' => true,
		'EDITOR_PATH'        => @addslashes($bb_cfg['dbg']['editor_path']),
		'EDITOR_ARGS'        => @addslashes($bb_cfg['dbg']['editor_args']),
	));
}

// Ads
if ($user->show_ads)
{
	$load_ads = array('trans');
	if (defined('BB_SCRIPT'))
	{
		$load_ads[] = BB_SCRIPT;
	}
	foreach ($ads->get($load_ads) as $block_id => $ad_html)
	{
		$template->assign_var("AD_BLOCK_{$block_id}", $ad_html);
	}
}

if ($userdata['user_id'] > -1)
{
      $timer = time();
      $sql = "UPDATE ". BB_USERS ." SET user_timer = '$timer' WHERE user_id = " . intval($userdata['user_id']);

      if ( !($result = DB()->sql_query($sql)) )
      {
            message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
      }
}

// Login box
$in_out = ($logged_in) ? 'in' : 'out';
$template->assign_block_vars("switch_user_logged_{$in_out}", array());

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
{
	header('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
	header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header('Expires: 0');
header('Pragma: no-cache');

$template->set_filenames(array('page_header' => 'page_header.tpl'));
$template->pparse('page_header');

define('PAGE_HEADER_SENT', true);

if(!$bb_cfg['gzip_compress'])
{
	flush();
}