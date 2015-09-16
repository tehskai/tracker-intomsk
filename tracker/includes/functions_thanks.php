<?php
function get_user_thanks ($uid) //Сколько раз юзер поблагодарил
{
	$query2 = "SELECT COUNT(*) FROM ". BB_ATTACHMENTS_RATING ." WHERE user_id=$uid" ;

	$result2 = mysql_query($query2) or die(mysql_error());
	$row2 = mysql_fetch_assoc($result2);
	$count2 = $row2['COUNT(*)'];
	return $count2;
}
function get_user_thanked ($uid) //сколько раз юзера благодарили
{
	$query3 = "SELECT COUNT(*) FROM (
	". BB_ATTACHMENTS_RATING ." r 
    LEFT JOIN bb_attachments a ON ( a.attach_id=r.attach_id) )
    WHERE a.user_id_1=$uid";
	$result3 = mysql_query($query3) or die(mysql_error());
	$row3 = mysql_fetch_assoc($result3);
	$count3 = $row3['COUNT(*)'];
	return $count3;
}
