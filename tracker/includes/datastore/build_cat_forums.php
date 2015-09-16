<?php

if (!defined('BB_ROOT')) die(basename(__FILE__));

global $bf, $bb_cfg;

//
// cat_forums
//
$data = array(
	'not_auth_forums' => array(
		'guest_view' => array(),
		'guest_read' => array(),
		'user_view'  => array(),
		'user_read'  => array(),
	),
	'tracker_forums'  => array(),
	'cat_title_html'  => array(),
	'forum_name_html' => array(),
	'c' => array(),                // also has $data['c']['cat_id']['forums'] key
	'f' => array(),                // also has $data['f']['forum_id']['subforums'] key
);

// Store only these fields from BB_FORUMS in $data['f']
$forum_store_fields = array_flip(array_keys($bf['forum_perm']));
$forum_store_fields += array_flip(array(
  'forum_id',
  'cat_id',
  'forum_name',
  'forum_desc',
  'forum_status',
  'forum_posts',
  'forum_topics',
  'forum_parent',
));

// Categories
$sql = "SELECT * FROM ". BB_CATEGORIES ." ORDER BY cat_order";

foreach(DB()->fetch_rowset($sql) as $row)
{
	$data['c'][$row['cat_id']] = $row;
	$data['cat_title_html'][$row['cat_id']] = htmlCHR($row['cat_title']);
}

$sql = "
	SELECT f.*
	FROM ". BB_FORUMS ." f, ". BB_CATEGORIES ." c
	WHERE f.cat_id = c.cat_id
	ORDER BY c.cat_order, f.forum_order
";

foreach (DB()->fetch_rowset($sql) as $row)
{
	$fid = $row['forum_id'];
	$not_auth =& $data['not_auth_forums'];

	// Find not auth forums
	if ($row['auth_view'] != AUTH_ALL)
	{
		$not_auth['guest_view'][] = $fid;
	}
	if ($row['auth_view'] != AUTH_ALL && $row['auth_view'] != AUTH_REG)
	{
		$not_auth['user_view'][] = $fid;
	}
	if ($row['auth_read'] != AUTH_ALL)
	{
		$not_auth['guest_read'][] = $fid;
	}
	if ($row['auth_read'] != AUTH_ALL && $row['auth_read'] != AUTH_REG)
	{
		$not_auth['user_read'][] = $fid;
	}

    $data['forum'][$fid] = $row;

	// Store forums data
	if ($parent_id = $row['forum_parent'])
	{
		$parent =& $data['f'][$parent_id];

		$parent['subforums'][] = $fid;
		$parent['forum_posts']  += $row['forum_posts'];
		$parent['forum_topics'] += $row['forum_topics'];
	}
	if ($row['allow_reg_tracker'])
	{
		$data['tracker_forums'][] = $fid;
	}

	$data['f'][$fid] = array_intersect_key($row, $forum_store_fields);
	$data['forum_name_html'][$fid] = htmlCHR($row['forum_name']);

	// Forum ids in cat
	$data['c'][$row['cat_id']]['forums'][] = $fid;
}
foreach ($data['not_auth_forums'] as $key => $val)
{
	$data['not_auth_forums'][$key] = join(',', $val);
}
$data['tracker_forums'] = join(',', $data['tracker_forums']);

$this->store('cat_forums', $data);

//
// jumpbox
//
$data = array(
	'guest' => get_forum_select('guest', 'f', null, null, null, 'id="jumpbox" onchange="window.location.href=\'viewforum.php?f=\'+this.value;"'),
	'user'  => get_forum_select('user',  'f', null, null, null, 'id="jumpbox" onchange="window.location.href=\'viewforum.php?f=\'+this.value;"'),
);

$this->store('jumpbox', $data);

file_write($data['guest'], AJAX_HTML_DIR .'jumpbox_guest.html', false, true, true);
file_write($data['user'], AJAX_HTML_DIR .'jumpbox_user.html', false, true, true);

//
// viewtopic_forum_select
//
$data = array(
	'viewtopic_forum_select' => get_forum_select('admin', 'new_forum_id'),
);

$this->store('viewtopic_forum_select', $data);

//
// latest_news
//
if ($bb_cfg['show_latest_news'] AND $news_forum_ids = $bb_cfg['latest_news_forum_id'])
{
	$news_count = max($bb_cfg['latest_news_count'], 1);

	$data = DB()->fetch_rowset("
		SELECT topic_id, topic_time, topic_title, forum_id
		FROM ". BB_TOPICS ."
		WHERE forum_id IN ($news_forum_ids)
		ORDER BY topic_time DESC
		LIMIT $news_count
	");

	$this->store('latest_news', $data);
}

//
// Network_news
//
if ($bb_cfg['show_network_news'] AND $net_forum_ids = $bb_cfg['network_news_forum_id'])
{
   $net_count = max($bb_cfg['network_news_count'], 1);

   $data = DB()->fetch_rowset("
      SELECT topic_id, topic_time, topic_title, forum_id
      FROM ". BB_TOPICS ."
      WHERE forum_id IN ($net_forum_ids)
      ORDER BY topic_time DESC
      LIMIT $net_count
   ");

   $this->store('network_news', $data);
}

//
// Ads
//
if ($bb_cfg['show_ads'])
{
	$ad_html = $ad_block_assignment = array();

	$active_ads = DB()->fetch_rowset("
		SELECT *
		FROM ". BB_ADS ."
		WHERE ad_status = 1
		  AND ad_start_time < NOW()
		  AND ad_finish_time > NOW()
	");

	foreach ($active_ads as $ad)
	{
		if ($ad['ad_block_ids'])
		{
			foreach(explode(',', $ad['ad_block_ids']) as $block_id)
			{
				$ad_block_assignment[$block_id][] = $ad['ad_id'];
			}
		}

		$ad_html[$ad['ad_id']] = $ad['ad_html'];
	}
	$this->store('ads', $ad_html);
	bb_update_config(array('active_ads' => serialize($ad_block_assignment)));
}

//Store torhits
if($bb_cfg['t_tor_hits'])
{
   $this->store('tor_hits', $data);
}

//Store LastAdded
if($bb_cfg['t_last_added_num']) 
{
	$data = DB()->fetch_rowset("SELECT
			tr.topic_id, tr.forum_id, tr.reg_time,
			t.topic_title,
			f.forum_name,
			u.username, u.user_id, u.user_rank
		FROM
			". BB_BT_TORRENTS ." tr
		LEFT JOIN ". BB_TOPICS ." t ON tr.topic_id = t.topic_id
		LEFT JOIN ". BB_FORUMS ." f ON tr.forum_id = f.forum_id
		LEFT JOIN ". BB_USERS ." u  ON tr.poster_id = u.user_id
		ORDER BY
			tr.reg_time DESC
		LIMIT 0,".$bb_cfg['t_last_added_num']);
	$this->store('last_added', $data);
}
//Store TopDownloaded
if($bb_cfg['t_top_downloaded']) 
{
	$data = DB()->fetch_rowset("SELECT
			tr.topic_id, tr.forum_id, tr.reg_time, tr.complete_count,
			t.topic_title,
			f.forum_name,
			u.username, u.user_id, u.user_rank
		FROM
			". BB_BT_TORRENTS ." tr
		LEFT JOIN ". BB_TOPICS ." t ON tr.topic_id = t.topic_id
		LEFT JOIN ". BB_FORUMS ." f ON tr.forum_id = f.forum_id
		LEFT JOIN ". BB_USERS ." u  ON tr.poster_id = u.user_id
		ORDER BY
			tr.complete_count DESC
		LIMIT 0,".$bb_cfg['t_top_downloaded']);
	$this->store('top_downloaded', $data);
}
//Store TopUploaders
if($bb_cfg['t_top_uploaders']) 
{
	$data = DB()->fetch_rowset("SELECT t.user_id, u.username, u.user_rank, SUM( t.u_up_total ) sum
		FROM ". BB_BT_USERS ." t
		LEFT JOIN ". BB_USERS ." u ON ( t.user_id = u.user_id )
		GROUP BY t.user_id
		ORDER BY sum DESC
		LIMIT 0 ,".$bb_cfg['t_top_uploaders']);
	$this->store('top_uploaders', $data);
}
//Store TopDownloaders
if($bb_cfg['t_top_downloaders']) 
{
	$data = DB()->fetch_rowset("SELECT t.user_id, u.username, u.user_rank, SUM( t.u_down_total ) sum
		FROM ". BB_BT_USERS ." t
		LEFT JOIN ". BB_USERS ." u ON ( t.user_id = u.user_id )
		GROUP BY t.user_id
		ORDER BY sum DESC
		LIMIT 0 ,".$bb_cfg['t_top_downloaders']);
	$this->store('top_downloaders', $data);
}