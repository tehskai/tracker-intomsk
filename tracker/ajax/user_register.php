<?php

if (!defined('IN_AJAX')) die(basename(__FILE__));

global $bb_cfg, $lang, $userdata;

$mode = (string) $this->request['mode'];

$html = '<img src="./images/aff_tick.gif">';
switch($mode)
{
	case 'check_name':
		$username = clean_username($this->request['username']);

		if (empty($username))
		{
			$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. $lang['CHOOSE_A_NAME'] .'</span>';
		}
		else if($err = validate_username($username))
		{
			$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. $err .'</span>';
		}
		break;
	case 'check_email':
		$email = (string) $this->request['email'];

		if (empty($email))
		{
			$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. $lang['CHOOSE_E_MAIL'] .'</span>';
		}
		else if($err = validate_email($email))
		{
			$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. $err .'</span>';
		}
		break;
	case 'check_pass':
		$pass = (string) $this->request['pass'];
		$pass_confirm = (string) $this->request['pass_confirm'];
		if (empty($pass) || empty($pass_confirm))
		{
			$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. $lang['CHOOSE_PASS'] .'</span>';
		}
		else
		{
		    if ($pass != $pass_confirm)
			{
				$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. $lang['CHOOSE_PASS_ERR'] .'</span>';
			}
			else
			{
				if (mb_strlen($pass, 'UTF-8') > 20)
				{
					$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. sprintf($lang['CHOOSE_PASS_ERR_MAX'], 20) .'</span>';
				}
				elseif (mb_strlen($pass, 'UTF-8') < 5)
				{
					$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. sprintf($lang['CHOOSE_PASS_ERR_MIN'], 5) .'</span>';
				}
				else
				{
					$text = (IS_GUEST) ? $lang['CHOOSE_PASS_REG_OK'] : $lang['CHOOSE_PASS_OK'];
					$html = '<img src="./images/aff_tick.gif"> <span class="seedmed bold">'. $text .'</span>';
			    }
			}
		}
	break;
//invite check
/*
	case 'check_invite':
	 $invite_code = (string) $this->request['invite_code'];
	if (empty($invite_code))
		{ 
		$text = $lang['NO_INPUT_INVITE'];
		$html = '<img src="./images/aff_cross.gif"><span class="leechmed bold">&nbsp;'.$text.'</span>'; 
		}
		else
		{
		$invite_code = DB()->escape($invite_code);
		
		$query = "SELECT invite_code
					FROM `invites`
					WHERE `invite_code` = '$invite_code'";
   
		if ($row = DB()->fetch_row($query))  
			{
			$query = "SELECT invite_code
						FROM `invites`
						WHERE `invite_code` = '$invite_code' AND `active` = 1";
			if($row = DB()->fetch_row($query))
				{
				//Есть активен
				$html = '<img src="./images/aff_tick.gif">';
				}
			else
				{
				//Есть но занят
				$text = $lang['INVITE_EXISTS'];
				$html = '<img src="./images/aff_cross.gif"><span class="leechmed bold">&nbsp;'.$text.'</span>';
				}
			}
		else 
			{
			// Совсем нету
			$text = $lang['INVITE_NOT_EXISTS'];
			$html = '<img src="./images/aff_cross.gif"><span class="leechmed bold">&nbsp;'.$text.'</span>';
			}
		}
	break;*/
	case 'check_invite':
		$invite_code = (string) $this->request['invite_code'];
		if (empty($invite_code))
		{
			$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. $lang['NO_INPUT_INVITE'] .'</span>';
		}
		else if($err = validate_invite_code($invite_code))
		{
			$html = '<img src="./images/aff_cross.gif"> <span class="leechmed bold">'. $err .'</span>';
		}
		break;
	case 'refresh_captcha';
	    $html = CAPTCHA()->get_html();
	break;
}

$this->response['html'] = $html;
$this->response['mode'] = $mode;