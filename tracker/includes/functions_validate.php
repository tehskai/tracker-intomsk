<?php

if (!defined('BB_ROOT')) die(basename(__FILE__));

// !!! $username должен быть предварительно обработан clean_username() !!!
function validate_username ($username, $check_ban_and_taken = true)
{
	global $userdata, $lang;

	static $name_chars = 'a-z0-9а-яё_@$%^&;(){}\#\-\'.:+ ';

	$username = str_compact($username);
	$username = clean_username($username);

	// Length
	if (mb_strlen($username, 'UTF-8') > USERNAME_MAX_LENGTH)
	{
		return $lang['USERNAME_TOO_LONG'];
	} 
	else if (mb_strlen($username, 'UTF-8') < USERNAME_MIN_LENGTH)
	{
		return $lang['USERNAME_TOO_SMALL'];
	}
	// Allowed symbols
	if (!preg_match('#^['.$name_chars.']+$#iu', $username, $m))
	{
		$invalid_chars = preg_replace('#['.$name_chars.']#iu', '', $username);
		return "{$lang['USERNAME_INVALID']}: <b>". htmlCHR($invalid_chars) ."</b>";
	}
	// HTML Entities
	if (preg_match_all('/&(#[0-9]+|[a-z]+);/iu', $username, $m))
	{
		foreach ($m[0] as $ent)
		{
			if (!preg_match('/^(&amp;|&lt;|&gt;)$/iu', $ent))
			{
				return $lang['USERNAME_INVALID'];
			}
		}
	}
	if ($check_ban_and_taken)
	{
		// Занято
		$username_sql = DB()->escape($username);

		if ($row = DB()->fetch_row("SELECT username FROM ". BB_USERS ." WHERE username = '$username_sql' LIMIT 1"))
		{
			if ((!IS_GUEST && $row['username'] != $userdata['username']) || IS_GUEST)
			{
				return $lang['USERNAME_TAKEN'];
			}
		}
		// Запрещено
		$banned_names = array();

		foreach (DB()->fetch_rowset("SELECT disallow_username FROM ". BB_DISALLOW ." ORDER BY NULL") as $row)
		{
			$banned_names[] = str_replace('\*', '.*?', preg_quote($row['disallow_username'], '#u'));
		}
		if ($banned_names_exp = join('|', $banned_names))
		{
			if (preg_match("#^($banned_names_exp)$#iu", $username))
			{
				return $lang['USERNAME_DISALLOWED'];
			}
		}
	}

	return false;
}

function validate_invite_code ($invite_code, $check_activ_and_taken = true)
{
	global $lang;

	$invite_code = str_compact($invite_code);
	$invite_code = clean_username($invite_code);
	if($invite_code != ''){
		if ($check_activ_and_taken)
		{
			$invite_code_sql = DB()->escape($invite_code);
			$sql = "SELECT `invite_id` FROM `invites` WHERE `invite_code` = '$invite_code_sql' AND `active` = '1'";
			if (!($result = DB()->sql_query($sql))){
			return message_die(GENERAL_ERROR, 'Error checking code, invite', '', __LINE__, __FILE__, $sql);
			}else{
				$num_row = DB()->num_rows($result);
				DB()->sql_freeresult($result);
				if ($num_row == 0) {
					return $lang['INVITE_TAKEN'];
				}
			}
		}
	}else{
	return $lang['INVITE_EMPTY'];
	}
	return false;
}


// Check to see if email address is banned or already present in the DB
function validate_email ($email, $check_ban_and_taken = true)
{
	global $lang, $userdata;

	if (!$email || !preg_match('#^([_a-z\d])[a-z\d\.\-_]+@[a-z\d\-]+\.([a-z\d\-]+\.)*?[a-z]{2,4}$#i', $email))
	{
		return $lang['EMAIL_INVALID'];
	}
	if (strlen($email) > USEREMAIL_MAX_LENGTH)
	{
		return $lang['EMAIL_TOO_LONG'];
	}

	if ($check_ban_and_taken)
	{
		$banned_emails = array();

		foreach (DB()->fetch_rowset("SELECT ban_email FROM ". BB_BANLIST ." ORDER BY NULL") as $row)
		{
			$banned_emails[] = str_replace('\*', '.*?', preg_quote($row['ban_email'], '#'));
		}
		if ($banned_emails_exp = join('|', $banned_emails))
		{
			if (preg_match("#^($banned_emails_exp)$#i", $email))
			{
				return sprintf($lang['EMAIL_BANNED'], $email);
			}
		}

		$email_sql = DB()->escape($email);

		if ($row = DB()->fetch_row("SELECT `user_email` FROM ". BB_USERS ." WHERE user_email = '$email_sql' LIMIT 1"))
		{	
			if($row['user_email'] == $userdata['user_email'])
				return false;
			else
				return $lang['EMAIL_TAKEN'];
		}
	}

	return false;
}
