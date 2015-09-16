<?php

define('IN_PHPBB',   true);
define('BB_SCRIPT', 'index');
define('BB_ROOT', './');
require(BB_ROOT ."common.php");

$page_cfg['load_tpl_vars'] = array(
	'post_icons',
);

$show_last_topic    = true;
$last_topic_max_len = 28;
$show_online_users  = true;
$show_subforums     = true;

$datastore->enqueue(array(
	'stats',
	'moderators',
));
if ($bb_cfg['show_latest_news'])
{
	$datastore->enqueue('latest_news');
}
if ($bb_cfg['show_network_news'])
{
   $datastore->enqueue('network_news');
}

if ($bb_cfg['t_last_added_num'])
{
	$datastore->enqueue('last_added');
}
//Enqueue top downloaded
if ($bb_cfg['t_top_downloaded'])
{
	$datastore->enqueue('top_downloaded');
}
//Enqueue top uploaders
if ($bb_cfg['t_top_uploaders'])
{
	$datastore->enqueue('top_uploaders');
}
//Enqueue top downloaders
if ($bb_cfg['t_top_downloaders'])
{
	$datastore->enqueue('top_downloaders');
}

// Init userdata
$user->session_start();

// Init main vars
$viewcat = isset($_GET['c']) ? (int) $_GET['c'] : 0;
$lastvisit = (IS_GUEST) ? TIMENOW : $userdata['user_lastvisit'];

// Caching output
$req_page = 'index_page';
$req_page .= ($viewcat) ? "_c{$viewcat}" : '';

define('REQUESTED_PAGE', $req_page);
caching_output(IS_GUEST, 'send', REQUESTED_PAGE .'_guest_'. $bb_cfg['default_lang']);

$hide_cat_opt  = isset($user->opt_js['h_cat']) ? (string) $user->opt_js['h_cat'] : 0;
$hide_cat_user = array_flip(explode('-', $hide_cat_opt));
$showhide = isset($_GET['sh']) ? (int) $_GET['sh'] : 0;

//Enqueue tor hits
if ($bb_cfg['t_tor_hits'])
{
   $datastore->enqueue('tor_hits');
}

// Topics read tracks
$tracking_topics = get_tracks('topic');
$tracking_forums = get_tracks('forum');

// Statistics
if (!$stats = $datastore->get('stats'))
{
	$datastore->update('stats');
	$stats = $datastore->get('stats');
}

// Forums data
if (!$forums = $datastore->get('cat_forums'))
{
	$datastore->update('cat_forums');
	$forums = $datastore->get('cat_forums');
}
$cat_title_html = $forums['cat_title_html'];
$forum_name_html = $forums['forum_name_html'];

$anon = ANONYMOUS;
$excluded_forums_csv = $user->get_excluded_forums(AUTH_VIEW);
$only_new = $user->opt_js['only_new'];

// Validate requested category id
if ($viewcat AND !$viewcat =& $forums['c'][$viewcat]['cat_id'])
{
	redirect("index.php");
}
// Forums
$forums_join_sql = 'f.cat_id = c.cat_id';
$forums_join_sql .= ($viewcat) ? "
	AND f.cat_id = $viewcat
" : '';
$forums_join_sql .= ($excluded_forums_csv) ? "
	AND f.forum_id NOT IN($excluded_forums_csv)
	AND f.forum_parent NOT IN($excluded_forums_csv)
" : '';

// Posts
$posts_join_sql = "p.post_id = f.forum_last_post_id";
$posts_join_sql .= ($only_new == ONLY_NEW_POSTS) ? "
	AND p.post_time > $lastvisit
" : '';
$join_p_type = ($only_new == ONLY_NEW_POSTS) ? 'INNER JOIN' : 'LEFT JOIN';

// Topics
$topics_join_sql = "t.topic_last_post_id = p.post_id";
$topics_join_sql .= ($only_new == ONLY_NEW_TOPICS) ? "
	AND t.topic_time > $lastvisit
" : '';
$join_t_type = ($only_new == ONLY_NEW_TOPICS) ? 'INNER JOIN' : 'LEFT JOIN';

$sql = "
	SELECT SQL_CACHE
		f.cat_id, f.forum_id, f.forum_status, f.forum_parent, f.show_on_index,
		p.post_id AS last_post_id, p.post_time AS last_post_time,
		t.topic_id AS last_topic_id, t.topic_title AS last_topic_title,
		u.user_id AS last_post_user_id, u.user_rank AS last_post_user_rank,
		IF(p.poster_id = $anon, p.post_username, u.username) AS last_post_username
	FROM       ". BB_CATEGORIES ." c
	INNER JOIN ". BB_FORUMS     ." f ON($forums_join_sql)
	$join_p_type ". BB_POSTS      ." p ON($posts_join_sql)
	$join_t_type ". BB_TOPICS     ." t ON($topics_join_sql)
	 LEFT JOIN ". BB_USERS      ." u ON(u.user_id = p.poster_id)
	ORDER BY c.cat_order, f.forum_order
";
$cat_forums = array();

$replace_in_parent = array(
	'last_post_id',
	'last_post_time',
	'last_post_user_id',
	'last_post_username',
	'last_post_user_rank',
	'last_topic_title',
	'last_topic_id',
);

foreach (DB()->fetch_rowset($sql) as $row)
{
	if (!$cat_id = $row['cat_id'] OR !$forum_id = $row['forum_id'])
	{
		continue;
	}

	if ($parent_id = $row['forum_parent'])
	{
		if (!$parent =& $cat_forums[$cat_id]['f'][$parent_id])
		{
			$parent = $forums['f'][$parent_id];
			$parent['last_post_time'] = 0;
		}
		if ($row['last_post_time'] > $parent['last_post_time'])
		{
			foreach ($replace_in_parent as $key)
			{
				$parent[$key] = $row[$key];
			}
		}
		if ($show_subforums && $row['show_on_index'])
		{
			$parent['last_sf_id'] = $forum_id;
		}
		else
		{
			continue;
		}
	}
	else
	{
		$f =& $forums['f'][$forum_id];
		$row['forum_desc']   = $f['forum_desc'];
		$row['forum_posts']  = $f['forum_posts'];
		$row['forum_topics'] = $f['forum_topics'];
	}

	$cat_forums[$cat_id]['f'][$forum_id] = $row;
}
unset($forums);
$datastore->rm('cat_forums');

// Obtain list of moderators
$moderators = array();
if (!$mod = $datastore->get('moderators'))
{
	$datastore->update('moderators');
	$mod = $datastore->get('moderators');
}

if (!empty($mod))
{
	foreach ($mod['mod_users'] as $forum_id => $user_ids)
	{
		foreach ($user_ids as $user_id)
		{
			$moderators[$forum_id][] = '<a href="'. (PROFILE_URL . $user_id) .'">'. $mod['name_users'][$user_id] .'</a>';
		}
	}
	foreach ($mod['mod_groups'] as $forum_id => $group_ids)
	{
		foreach ($group_ids as $group_id)
		{
			$moderators[$forum_id][] = '<a href="'. (GROUP_URL . $group_id) .'">'. $mod['name_groups'][$group_id] .'</a>';
		}
	}
}

unset($mod);
$datastore->rm('moderators');

if (!$forums_count = count($cat_forums) AND $viewcat)
{
	redirect("index.php");
}

//nullratio
if ($bb_cfg['rationull_enabled']) {
	$btu = get_bt_userdata($userdata['user_id']);
	
	$up_total = $btu['u_up_total'] + $btu['u_up_release'] + $btu['u_up_bonus'];
	$down_total =$btu['u_down_total'];
	$ratio = ($down_total) ? round((($up_total) / $down_total), 2) : '0';
	$ratio_nulled = $btu['ratio_nulled'];
	
	$template->assign_vars(array( 
		'SHOW_RATIO_WARN'   => (($down_total > MIN_DL_FOR_RATIO) && ($ratio < $bb_cfg['ratio_to_null']) && !$ratio_nulled)
	));
}
//end

$template->assign_vars(array(
	'SHOW_FORUMS'           => $forums_count,
	'PAGE_TITLE'            => $lang['HOME'],
	'NO_FORUMS_MSG'         => ($only_new) ? $lang['NO_NEW_POSTS'] : $lang['NO_FORUMS'],

	'TOTAL_TOPICS'          => sprintf($lang['POSTED_TOPICS_TOTAL'], $stats['topiccount']),
	'TOTAL_POSTS'           => sprintf($lang['POSTED_ARTICLES_TOTAL'], $stats['postcount']),
	'TOTAL_USERS'           => sprintf($lang['REGISTERED_USERS_TOTAL'], $stats['usercount']),
	'TOTAL_GENDER'          => ($bb_cfg['gender']) ? sprintf($lang['USERS_TOTAL_GENDER'], $stats['male'], $stats['female'], $stats['unselect']) : '',
	'NEWEST_USER'           => sprintf($lang['NEWEST_USER'], profile_url($stats['newestuser'])),

	// Tracker stats
	'TORRENTS_STAT'         => ($bb_cfg['tor_stats']) ? sprintf($lang['TORRENTS_STAT'], $stats['torrentcount'], humn_size($stats['size'])) : '',
	'PEERS_STAT'		    => ($bb_cfg['tor_stats']) ? sprintf($lang['PEERS_STAT'], $stats['peers'], $stats['seeders'], $stats['leechers']) : '',
	'SPEED_STAT'		    => ($bb_cfg['tor_stats']) ? sprintf($lang['SPEED_STAT'], humn_size($stats['speed']) .'/s') : '',
	'SHOW_MOD_INDEX'	=> $bb_cfg['show_mod_index'],
	'FORUM_IMG'             => $images['forum'],
	'FORUM_NEW_IMG'         => $images['forum_new'],
	'FORUM_LOCKED_IMG'      => $images['forum_locked'],

	'SHOW_ONLY_NEW_MENU'    => true,
	'ONLY_NEW_POSTS_ON'     => ($only_new == ONLY_NEW_POSTS),
	'ONLY_NEW_TOPICS_ON'    => ($only_new == ONLY_NEW_TOPICS),

	'U_SEARCH_NEW'          => "search.php?new=1",
	'U_SEARCH_SELF_BY_MY'   => "search.php?uid={$userdata['user_id']}&amp;o=1",
	'U_SEARCH_LATEST'       => "search.php?search_id=latest",
	'U_SEARCH_UNANSWERED'   => "search.php?search_id=unanswered",

	'SHOW_LAST_TOPIC'       => $show_last_topic,
));

// BEGIN Hot Torrents.
$sql = 'SELECT *
   FROM '.TORHIT_TABLE.' ORDER BY torhit_id DESC LIMIT 20';

if ( !$result = DB()->sql_query($sql) )
{
   message_die(GENERAL_ERROR, "Could not fetch torrent hits!", '', __LINE__, __FILE__, $sql);
}
function design_platform($title){
  $title = str_replace("[PS3]",'<img src="/images/torhits/ps3.png">',$title);
  $title = str_replace("[PC]",'<img src="/images/torhits/pc.png">',$title);
  $title = str_replace("[XBOX360]",'<img src="/images/torhits/xbox360.png">',$title);
  $title = str_replace("[XBOX]",'<img src="/images/torhits/xbox.png">',$title);
  $title = str_replace("[PSP]",'<img src="/images/torhits/psp.png">',$title);
  $title = str_replace("[V]",'<img src="/images/torhits/video.png">',$title);
  $title = str_replace("[NINTENDO]",'<img src="/images/torhits/nintendo.png">',$title);
  $title = str_replace("[PROGRAM]",'<img src="/images/torhits/program.png">',$title);
  $title = str_replace("[OTHER]",'<img src="/images/torhits/other.png">',$title);
return $title;
}
while ( $row = DB()->sql_fetchrow($result) )
{
   $tor_hits = $datastore->get('tor_hits');

      $template -> assign_block_vars('torhits',array(
         'URL'    => $bb_cfg['hit_link'].$row['torhit_title'],
         'IMAGES' => design_platform($row['torhit_images']),
      )) ;
}


// END Hot Torrents

// Build index page
foreach ($cat_forums as $cid => $c)
{
    $template->assign_block_vars('h_c', array(
		'H_C_ID'     => $cid,
		'H_C_TITLE'  => $cat_title_html[$cid],
		'H_C_CHEKED' => in_array($cid, preg_split("/[-]+/", $hide_cat_opt)) ? 'checked' : '',
	));

    $template->assign_vars(array(
		'H_C_AL_MESS'  => ($hide_cat_opt && !$showhide) ? true : false
	));

    if (!$showhide && isset($hide_cat_user[$cid]))
	{
		continue;
	}

	$template->assign_block_vars('c', array(
		'CAT_ID'    => $cid,
		'CAT_TITLE' => $cat_title_html[$cid],
		'U_VIEWCAT' => "index.php?c=$cid",
	));

	foreach ($c['f'] as $fid => $f)
	{
		if (!$fname_html =& $forum_name_html[$fid])
		{
			continue;
		}
		$is_sf = $f['forum_parent'];

		$new = is_unread($f['last_post_time'], $f['last_topic_id'], $f['forum_id']) ? '_new' : '';
		$folder_image = ($is_sf) ? $images["icon_minipost{$new}"] : $images["forum{$new}"];

		if ($f['forum_status'] == FORUM_LOCKED)
		{
			$folder_image = ($is_sf) ? $images['icon_minipost'] : $images['forum_locked'];
		}

		if ($is_sf)
		{
			$template->assign_block_vars('c.f.sf', array(
				'SF_ID'   => $fid,
				'SF_NAME' => $fname_html,
				'SF_NEW'  => $new ? ' new' : '',
			));
			continue;
		}

		$template->assign_block_vars('c.f',	array(
			'FORUM_FOLDER_IMG' => $folder_image,

			'FORUM_ID'   		=> $fid,
			'FORUM_NAME' 		=> $fname_html,
			'FORUM_DESC'		=> $f['forum_desc'],
			'POSTS'     		=> commify($f['forum_posts']),
			'TOPICS'    		=> commify($f['forum_topics']),
			'LAST_SF_ID'		=> isset($f['last_sf_id']) ? $f['last_sf_id'] : null,
			'MODERATORS' 		=> isset($moderators[$fid]) ? join(', ', $moderators[$fid]) : '',
			'FORUM_FOLDER_ALT' 	=> ($new) ? $lang['NEW'] : $lang['OLD'],
		));

		if ($f['last_post_id'])
		{
			$template->assign_block_vars('c.f.last', array(
				'LAST_TOPIC_ID'       => $f['last_topic_id'],
				'LAST_TOPIC_TIP'      => $f['last_topic_title'],
				'LAST_TOPIC_TITLE'    => wbr(str_short($f['last_topic_title'], $last_topic_max_len)),

				'LAST_POST_TIME'      => bb_date($f['last_post_time'], $bb_cfg['last_post_date_format']),
				'LAST_POST_USER'      => profile_url(array('username' => str_short($f['last_post_username'], 15), 'user_id' => $f['last_post_user_id'], 'user_rank' => $f['last_post_user_rank'])),
			));
		}
	}
}

// Set tpl vars for bt_userdata
if ($bb_cfg['bt_show_dl_stat_on_index'] && !IS_GUEST)
{
	show_bt_userdata($userdata['user_id']);
}

// Latest news
if ($bb_cfg['show_latest_news'])
{
	if (!$latest_news = $datastore->get('latest_news'))
	{
		$datastore->update('latest_news');
		$latest_news = $datastore->get('latest_news');
	}

	$template->assign_vars(array(
		'SHOW_LATEST_NEWS' => true,
	));

	foreach ($latest_news as $news)
	{
		$template->assign_block_vars('news', array(
			'NEWS_TOPIC_ID' => $news['topic_id'],
			'NEWS_TITLE'    => str_short($news['topic_title'], $bb_cfg['max_news_title']),
			'NEWS_TIME'     => bb_date($news['topic_time'], 'd-M', 'false'),
			'NEWS_IS_NEW'   => is_unread($news['topic_time'], $news['topic_id'], $news['forum_id']),
		));
	}
}

// Network news
if ($bb_cfg['show_network_news'])
{
    if (!$network_news = $datastore->get('network_news'))
	{
	    $datastore->update('network_news');
		$network_news = $datastore->get('network_news');
	}

    $template->assign_vars(array(
        'SHOW_NETWORK_NEWS' => true,
    ));

   foreach ($network_news as $net)
    {
        $template->assign_block_vars('net', array(
			'NEWS_TOPIC_ID' => $net['topic_id'],
			'NEWS_TITLE'    => str_short($net['topic_title'], $bb_cfg['max_net_title']),
			'NEWS_TIME'     => bb_date($net['topic_time'], 'd-M', 'false'),
			'NEWS_IS_NEW'   => is_unread($net['topic_time'], $net['topic_id'], $net['forum_id']),
        ));
    }
}

// BEGIN last 10.
if($bb_cfg['t_last_added_num']) 
{
	
	if (!$last_added = $datastore->get('last_added'))
	{
		$datastore->update('last_added');
		$last_added = $datastore->get('last_added');
	}

		
	$template -> assign_vars(array(
		'LAST_ADDED_ON' => true,
    ));

	$last_added = $datastore->get('last_added');
	foreach ($last_added as $last_add)
	{
		$template -> assign_block_vars('lastAdded',array(
			'TITLE' => $last_add['topic_title'],
			'TOPIC_ID' => $last_add['topic_id'],
			'FORUM' => $last_add['forum_name'],
			'FORUM_ID' => $last_add['forum_id'],
			'POSTER' => profile_url(array('username' => $last_add['username'], 'user_rank' => $last_add['user_rank'])),
			'POSTER_ID' => $last_add['user_id'],
			'TORRENT_TIME' => bb_date($last_add['reg_time'], 'd-M', 'false')
			
		)) ;
	}
}
// END last 10


// BEGIN TopDownloaded
if($bb_cfg['t_top_downloaded']) 
{
	if (!$top_downloaded = $datastore->get('top_downloaded'))
	{
		$datastore->update('top_downloaded');
		$top_downloaded = $datastore->get('top_downloaded');
	}

	
	$template -> assign_vars(array(
		'TOP_DOWNLOADED_ON' => true,
    ));

	foreach ($top_downloaded as $top_download)
	{
		$template -> assign_block_vars('TopDownloaded',array(
			'TITLE' => $top_download['topic_title'],
			'TOPIC_ID' => $top_download['topic_id'],
			'FORUM' => $top_download['forum_name'],
			'FORUM_ID' => $top_download['forum_id'],
			'POSTER' 	   => profile_url(array('username' => $top_download['username'], 'user_rank' => $top_download['user_rank'])),
			'POSTER_ID' => $top_download['user_id'],
			'COMPLETED' => $top_download['complete_count'] . ' раз',
			'TORRENT_TIME' => bb_date($top_download['reg_time'], 'd-M', 'false')
		)) ;
	}
}
// END TopDownloaded

// BEGIN Top Uploaders.
if($bb_cfg['t_top_uploaders'])
{
	if (!$top_uploaders = $datastore->get('top_uploaders'))
	{
		$datastore->update('top_uploaders');
		$top_uploaders = $datastore->get('top_uploaders');
	}


	$template -> assign_vars(array(
       'TOP_UPLOADERS_ON' => true,
	   'UL_TOP_COUNT'     => $bb_cfg['t_top_uploaders'],
    ));

	foreach ($top_uploaders as $top_uploader)
	{
		$template -> assign_block_vars('TopUploaders',array(
			'USER_ID'  => $top_uploader['user_id'],
			'UPL_NAME' => profile_url(array('username' => $top_uploader['username'], 'user_rank' => $top_uploader['user_rank'])),
			'UPLOADED' => (humn_size($top_uploader['sum'])),			
		)) ;
	}
}
// END Top Uploaders



// BEGIN Top Downloaders.
if($bb_cfg['t_top_downloaders']) {

if (!$top_downloaders = $datastore->get('top_downloaders'))
	{
		$datastore->update('top_downloaders');
		$top_downloaders = $datastore->get('top_downloaders');
	}


	$template -> assign_vars(array(
       'TOP_DOWNLOADERS_ON' => true,
	   'DL_TOP_COUNT'       => $bb_cfg['t_top_downloaders'],
    ));
	

	foreach ($top_downloaders as $top_downloader)
	{
		$template -> assign_block_vars('TopDownloaders',array(
			'USER_ID'    => $top_downloader['user_id'],
			'DOWNL_NAME' => profile_url(array('username' => $top_downloader['username'], 'user_rank' => $top_downloader['user_rank'])),
			'DOWNLOADED' => (humn_size($top_downloader['sum'])),			
		)) ;
	}
}
// END Top Downloaders


if ($bb_cfg['birthday_check_day'] && $bb_cfg['birthday_enabled'])
{
	$week_list = $today_list = array();
	$week_all = $today_all = false;

	if ($stats['birthday_week_list'])
	{
		shuffle($stats['birthday_week_list']);
		foreach($stats['birthday_week_list'] as $i => $week)
		{
			if($i >= 5)
			{
				$week_all = true;
				continue;
			}

			$week_list[] = profile_url($week) .' <span class="small">('. birthday_age($week['age']) .')</span>';
		}
		$week_all = ($week_all) ? '&nbsp;<a class="txtb" href="#" onclick="ajax.exec({action: \'index_data\', mode: \'birthday_week\'}); return false;" title="'. $lang['ALL'] .'">...</a>' : '';
		$week_list = sprintf($lang['BIRTHDAY_WEEK'], $bb_cfg['birthday_check_day'], join(', ', $week_list)) . $week_all;
	}
	else $week_list = sprintf($lang['NOBIRTHDAY_WEEK'], $bb_cfg['birthday_check_day']);

	if ($stats['birthday_today_list'])
	{
		shuffle($stats['birthday_today_list']);
		foreach($stats['birthday_today_list'] as $i => $today)
		{
			if($i >= 5)
			{
				$today_all = true;
				continue;
			}

			$today_list[] = profile_url($today) .' <span class="small">('. birthday_age($today['age'], 0) .')</span>';
		}
		$today_all = ($today_all) ? '&nbsp;<a class="txtb" href="#" onclick="ajax.exec({action: \'index_data\', mode: \'birthday_today\'}); return false;" title="'. $lang['ALL'] .'">...</a>' : '';
		$today_list = $lang['BIRTHDAY_TODAY'] . join(', ', $today_list) . $today_all;
	}
	else $today_list = $lang['NOBIRTHDAY_TODAY'];

	$template->assign_vars(array(
		'WHOSBIRTHDAY_WEEK'  => $week_list,
		'WHOSBIRTHDAY_TODAY' => $today_list,
	));
}

// Allow cron
if (IS_AM)
{
	if (@file_exists(CRON_RUNNING))
	{
		if (@file_exists(CRON_ALLOWED))
		{
			unlink (CRON_ALLOWED);
		}
		rename(CRON_RUNNING, CRON_ALLOWED);
	}
}

// Display page
define('SHOW_ONLINE', $show_online_users);

if(!IS_GUEST){
print_page('index.tpl');
}
else
{
?>
<html>
	<head>
		<title>Авторизация :: tracker.intomsk.com</title>
		<link rel=stylesheet type="text/css" href="style.css"  title="Style">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		    <script type="text/javascript" src="misc/cd/js/jquery.min.js"></script>
    <script src="misc/cd/js/jquery.countdown.js" type="text/javascript"></script>
    		<script type="text/javascript">
function calcage(secs, num1, num2) {
    s = ((Math.floor(secs/num1))%num2).toString();
    if ( s.length < 2)
   s = "0" + s;
    return s;
  }
$(function () {
   var finalDate = new Date(<?php echo $bb_cfg['final_date'];?>);
   var curDate = new Date();
   var secRemaining = finalDate - curDate;
   if(secRemaining >= 0)
   {
    secRemaining = Math.floor(secRemaining/1000);
    var Days = calcage(secRemaining,86400,100000);
    var Hours = calcage(secRemaining,3600,24);
    var Minutes = calcage(secRemaining,60,60);
    var Seconds = calcage(secRemaining,1,60);
    var arr = [Days,Hours,Minutes,Seconds];
  
    $('#counter').countdown({
      digitImages: 6,
      image: 'misc/cd/digits2.png',
      startTime: arr.join(':'),
      timerEnd: function(){
         $('#timer').remove();
         $('#form').fadeIn(30);
         }
    });
  }
else
  {
  $('#timer').remove();
  $('#form').show();
  }
});
		</script>
    <!--<script type="text/javascript">
      $(function(){
        $('#counter').countdown({
          image: 'misc/cd/digits2.png',
          startTime: '01:12:12:00'
        });
      });
    </script>-->

    <style type="text/css">
      br { clear: both; }
      .cntSeparator {
        font-size: 54px;
        margin: 10px 7px;
        color: #000;
      }
      .desc { margin: 7px 3px; }
      .desc div {
        float: left;
        font-family: Arial;
        width: 70px;
        margin-right: 65px;
        font-size: 13px;
        font-weight: bold;
        color: #000;
      }
    </style>
	</head>
	<body>
		<table id="bg">
			<tr>
				<td>
					<div id="form" style="display: none;">
					<form action="login.php" method="post">
					<div id="text" align="center">Введите ваш логин и пароль для входа в систему</div>
					<div id="hr">&nbsp;</div>
					<br />
					<table class="table" align="center">
						<tr>
							<td colspan="2">
							<input class="username" name="login_username" type="text" accesskey="l" placeholder="Логин">
							</td>
						</tr>
						<tr>
							<td colspan="2">
							<input class="username" name="login_password" type="password" placeholder="Пароль">
							</td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan="2">
							<div id="text"><input class="check" type="checkbox" name="autologin" value="1">&nbsp;Запомнить</div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;
							</td>
						</tr>
						<tr>
							<td>
							<input class="send" type="submit" name="login" value="">
							</td>
							<td>
							<input class="Clear" type="reset" name="reset" value="">
							</td>
						</tr>
					</table>
								<br>
								<div align="center" id="text"><a href="profile.php?mode=sendpassword">Забыли пароль?</a></div>
								<div align="center" id="text"><a href="profile.php?mode=register">Регистрация</a></div>
								<div align="center" id="text"><a href="invitation.php">Как получить инвайт</a></div>
						</form>
					</div>
					<div id="timer">
					<table class="table" align="center">
						<tr>
							<td>
								<div id="counter" style="position:relative;"></div>
									<div class="desc">
										<div>Дней</div>
										<div>Часов</div>
										<div>Минут</div>
										<div>Секунд</div>
									</div>
							</td>
						</tr>
					</table>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php
/*$template->assign_vars(array(
	'NO_FORUMS_MSG'         => ($only_new) ? $lang['GUEST_MSG'] : $lang['GUEST_MSG'],
	'TOTAL_USERS'           => sprintf($lang['Registered_users_total'], $stats['usercount']),
));*/}
