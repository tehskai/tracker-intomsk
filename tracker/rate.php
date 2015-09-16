<?php

define('IN_PHPBB', true);
define('BB_ROOT', './');
require(BB_ROOT . "common.php");

// Start session management
$user->session_start();

$attach_id = $_GET['a'];
$rating = $_GET['v'];

if( $userdata['user_id'] != ANONYMOUS && is_numeric($attach_id) && is_numeric($rating) && $rating>=1 && $rating<=5 ) {

	$sql = "insert into ". BB_ATTACHMENTS_RATING ."(attach_id,user_id,rating)values("
		. $attach_id .",". $userdata['user_id'] .",". $rating .")on duplicate key update rating=values(rating)";

	if( DB()->sql_query($sql) ) {
		echo 'var vd=document.getElementById("VD'. $attach_id .'");vd.innerHTML="'. $lang['YOUR_VOTE'] .' '. $rating .' '. $lang['VOTE_COUNTED'] .'";';
	}

	$sql = "select sum(rating) as r, count(*) as c from ". BB_ATTACHMENTS_RATING ." where rating>0 and attach_id=". $attach_id;

	if( $result = DB()->sql_query($sql) ) {
		if ($row = DB()->sql_fetchrow($result)) {
			echo 'var vr=document.getElementById("VR'. $attach_id .'");vr.innerHTML="'. round($row['r']/$row['c'],1) .'";';
			echo 'var vc=document.getElementById("VC'. $attach_id .'");vc.innerHTML="'. $row['c'] .'";';
			$sql = "update ". BB_ATTACHMENTS_DESC ." set rating_sum=". $row['r'] .", rating_count=". $row['c'] ." where attach_id=". $attach_id;
			DB()->sql_query($sql);
		}
	}
}
