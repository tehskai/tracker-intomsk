<?php

define('IN_PHPBB', true);
define('BB_ROOT', './');
require(BB_ROOT . "common.php");

// Start session management
$user->session_start();

// Check if user logged in
if (!$userdata['session_logged_in'])
{
	redirect(append_sid("login.php?redirect=index.php", true));
}

// GET's
$mode      = (isset($_GET['mode'])) ? $_GET['mode'] : '';
$attach_id = (isset($_GET['a']) && is_numeric($_GET['a'])) ? $_GET['a'] : '0';
$user_id   = (isset($_GET['u']) && is_numeric($_GET['u'])) ? $_GET['u'] : $userdata['user_id'];

// Thank!
if($mode == 'thank' && $userdata['user_id'] != ANONYMOUS) {

	$sql = "INSERT INTO ". BB_ATTACHMENTS_RATING ." (attach_id,user_id,thanked) VALUES ("
		. $attach_id .",". $userdata['user_id'] .",1)on duplicate key update thanked=1";

	if( DB()->sql_query($sql) ) {
		echo 'var vb=document.getElementById("VB'. $attach_id .'");vb.innerHTML="";';
	}

	$sql = "select sum(thanked) as c from ". BB_ATTACHMENTS_RATING ." where attach_id=". $attach_id;

	if( $result = DB()->sql_query($sql) ) {
		if ($row = DB()->sql_fetchrow($result)) {
			echo 'var vt=document.getElementById("VT'. $attach_id .'");vt.innerHTML="'. $row['c'] .'";';
			$sql = "UPDATE ". BB_ATTACHMENTS_DESC ." SET thanks=". $row['c'] ." where attach_id=". $attach_id;
			DB()->sql_query($sql);
		}
	}
}

// Thanks list
if($mode == 'list') 
{
	$sql = DB()->fetch_rowset("SELECT u.user_id, u.username, u.user_rank FROM ". BB_ATTACHMENTS_RATING ." r join ". BB_USERS ." u on u.user_id=r.user_id where r.thanked=1 and r.attach_id=". $attach_id);

	$html = '';
    foreach	($sql as $row) 
	{
		if( $html ) $html .= ', ';
		$html .= profile_url($row);
	}
	echo 'var vl=document.getElementById("VL'. $attach_id .'");vl.innerHTML=\''. $html .'\';';
}

// User thanks page
if(!$mode) 
{
	// Prepare data 
	$sql = "SELECT username FROM bb_users WHERE user_id=$user_id";
	if( $result = DB()->sql_query($sql) ) {
		while ($row = DB()->sql_fetchrow($result)) {
			$username = $row['username'];
		}
	}
	$page_title = $lang['USER_THANKS'] . ' ' . $username;
	$template->assign_vars(array(
		'PAGE_TITLE' => $page_title,
));
	
	include(INC_DIR . 'page_header.php');
	
	// Get
	$sql = "SELECT art.*, a.*, tor.complete_count, t.topic_id, t.forum_id, t.topic_title, f.forum_name FROM bb_attachments_rating art
		LEFT JOIN bb_attachments a ON (a.attach_id=art.attach_id)
		LEFT JOIN bb_bt_torrents tor ON (tor.attach_id=art.attach_id)
		LEFT JOIN bb_topics t ON (t.topic_first_post_id=a.post_id)
		LEFT JOIN bb_forums f ON (f.forum_id=t.forum_id)		
	WHERE art.user_id=$user_id";
	if( $result = DB()->sql_query($sql) ) {
		$template->assign_block_vars('thanks', array());
		
		$f = 1;
		while ($row = DB()->sql_fetchrow($result)) {		
			$template->assign_block_vars('thanks.thanksrow', array(
				'RCN'          => $f,
				'FORUM_NAME'   => $row['forum_name'],
				'TOPIC_TITLE'  => $row['topic_title'] . " (".humn_size($seeding[$i]['size']).")",
				'DOWNLOADS'    => $row['complete_count'],
				'THANKS_COUNT' => $row['thanks_count'], 
				'U_VIEW_FORUM' => "viewforum.php?". POST_FORUM_URL .'='. $row['forum_id'],
				'U_VIEW_TOPIC' => "viewtopic.php?". POST_TOPIC_URL .'='. $row['topic_id'] .'&amp;spmode=full#seeders',
				'DL_TORRENT_HREF' => append_sid("download.php?id=" . $row['attach_id']),
				
			));
			if($f==1)$f=2;else $f=1;
		}
	}
	print_page('thanks.htm');

}