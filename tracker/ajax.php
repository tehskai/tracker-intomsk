<?php

define('IN_AJAX', true);
$ajax = new ajax_common();

require('./common.php');

$ajax->init();

// Handle "board disabled via ON/OFF trigger"
if (file_exists(BB_DISABLED))
{
	$ajax->ajax_die($bb_cfg['board_disabled_msg']);
}

// Load actions required modules
switch ($ajax->action)
{
	case 'view_post':
		require(INC_DIR .'bbcode.php');
	break;

	case 'posts':
		require(INC_DIR .'bbcode.php');
		require(INC_DIR .'functions_post.php');
		require(INC_DIR .'functions_admin.php');
	break;

	case 'view_torrent':
	case 'mod_action':
	case 'change_tor_status':
	case 'gen_passkey';
		require(BB_ROOT .'attach_mod/attachment_mod.php');
		require(INC_DIR .'functions_torrent.php');
	break;

	case 'change_torrent':
		require(BB_ROOT .'attach_mod/attachment_mod.php');
		require(INC_DIR .'functions_torrent.php');
	break;

	case 'user_register':
		require(INC_DIR .'functions_validate.php');
	break;

	case 'manage_user':
		require(INC_DIR .'functions_admin.php');
	break;

	case 'group_membership':
		require(INC_DIR .'functions_group.php');
	break;
}

// position in $ajax->valid_actions['xxx']
define('AJAX_AUTH', 0);  //  'guest', 'user', 'mod', 'admin'

$user->session_start();
$ajax->exec();

//
// Ajax
//
class ajax_common
{
	var $request  = array();
	var $response = array();

	var $valid_actions = array(
	//   ACTION NAME             AJAX_AUTH
		'ads_status'		=> array('admin'),
		'edit_user_profile' => array('admin'),
		'change_user_rank'  => array('admin'),
		'change_user_opt'   => array('admin'),
		'manage_user'       => array('admin'),

		'change_tor_status' => array('mod'),
		'mod_action'        => array('mod'),
        'topic_tpl'         => array('mod'),
        'group_membership'  => array('mod'),

		'gen_passkey'       => array('user'),
		'change_torrent'    => array('user'),

		'view_post'         => array('guest'),
		'view_torrent'      => array('guest'),
		'user_register'     => array('guest'),
		'posts'             => array('guest'),
		'index_data'        => array('guest'),
		'user_stats'        => array('user'),
		'rate_post'     	=> array('user')
);

	var $action = null;

	/**
	*  Constructor
	*/
	function ajax_common ()
	{
		ob_start(array(&$this, 'ob_handler'));
		header('Content-Type: text/plain');
	}

	/**
	*  Perform action
	*/
	function exec ()
	{
		global $lang;

		// Exit if we already have errors
		if (!empty($this->response['error_code']))
		{
			$this->send();
		}

		// Check that requested action is valid
		$action = $this->action;

		if (!$action || !is_string($action))
		{
			$this->ajax_die('no action specified');
		}
		else if (!$action_params =& $this->valid_actions[$action])
		{
			$this->ajax_die('invalid action: '. $action);
		}

		// Auth check
		switch ($action_params[AJAX_AUTH])
		{
			// GUEST
			case 'guest':
				break;

			// USER
			case 'user':
				if (IS_GUEST)
				{
					$this->ajax_die($lang['NEED_TO_LOGIN_FIRST']);
				}
				break;

			// MOD
			case 'mod':
				if (!IS_AM)
				{
					$this->ajax_die($lang['ONLY_FOR_MOD']);
				}
				$this->check_admin_session();
				break;

			// ADMIN
			case 'admin':
				if (!IS_ADMIN)
				{
					$this->ajax_die($lang['ONLY_FOR_ADMIN']);
				}
				$this->check_admin_session();
				break;

			default:
				trigger_error("invalid auth type for $action", E_USER_ERROR);
		}

		// Run action
		$this->$action();

		// Send output
		$this->send();
	}

	/**
	*  Exit on error
	*/
	function ajax_die ($error_msg, $error_code = E_AJAX_GENERAL_ERROR)
	{
		$this->response['error_code'] = $error_code;
		$this->response['error_msg'] = $error_msg;

		$this->send();
	}

	/**
	*  Initialization
	*/
	function init ()
	{
		$this->request = $_POST;
		$this->action  =& $this->request['action'];
	}

	/**
	*  Send data
	*/
	function send ()
	{
		$this->response['action'] = $this->action;

		if (DBG_USER && SQL_DEBUG && !empty($_COOKIE['sql_log']))
		{
			$this->response['sql_log'] = get_sql_log();
		}

		// sending output will be handled by $this->ob_handler()
		exit();
	}

	/**
	*  OB Handler
	*/
	function ob_handler ($contents)
	{
		if (DBG_USER)
		{
			if ($contents)
			{
				$this->response['raw_output'] = $contents;
			}
		}

		$response_js = bb_json_encode($this->response);

		if (GZIP_OUTPUT_ALLOWED && !defined('NO_GZIP'))
		{
			if (UA_GZIP_SUPPORTED && strlen($response_js) > 2000)
			{
				header('Content-Encoding: gzip');
				$response_js = gzencode($response_js, 1);
			}
		}

		return $response_js;
	}

	/**
	*  Admin session
	*/
	function check_admin_session ()
	{
		global $user;

		if (!$user->data['session_admin'])
		{
			if (empty($this->request['user_password']))
			{
				$this->prompt_for_password();
			}
			else
			{
				$login_args = array(
					'login_username' => $user->data['username'],
					'login_password' => $_POST['user_password'],
				);
				if (!$user->login($login_args, true))
				{
					$this->ajax_die('Wrong password');
				}
			}
		}
	}

	/**
	*  Prompt for password
	*/
	function prompt_for_password ()
	{
		$this->response['prompt_password'] = 1;
		$this->send();
	}

	/**
	*  Prompt for confirmation
	*/
	function prompt_for_confirm ($confirm_msg)
	{
		if(empty($confirm_msg)) $this->ajax_die('false');

		$this->response['prompt_confirm'] = 1;
		$this->response['confirm_msg'] = $confirm_msg;
		$this->send();
	}

    /**
	*  Verify mod rights
	*/
	function verify_mod_rights ($forum_id)
	{
		global $userdata, $lang;

		$is_auth = auth(AUTH_MOD, $forum_id, $userdata);

		if (!$is_auth['auth_mod'])
		{
			$this->ajax_die($lang['ONLY_FOR_MOD']);
		}
	}

	function edit_user_profile ()
	{
        require(AJAX_DIR .'edit_user_profile.php');
	}

	function change_user_rank ()
	{
		global $datastore, $lang;

		$ranks   = $datastore->get('ranks');
		$rank_id = intval($this->request['rank_id']);

		if (!$user_id = intval($this->request['user_id']) OR !$profiledata = get_userdata($user_id))
		{
			$this->ajax_die("invalid user_id: $user_id");
		}
		if ($rank_id != 0 && !isset($ranks[$rank_id]))
		{
			$this->ajax_die("invalid rank_id: $rank_id");
		}

		DB()->query("UPDATE ". BB_USERS ." SET user_rank = $rank_id WHERE user_id = $user_id LIMIT 1");

		$this->response['html'] = ($rank_id != 0) ? $lang['AWARDED_RANK'] . ' <b> '. $ranks[$rank_id]['rank_title'] .'</b>' : $lang['SHOT_RANK'];
	}

    function change_user_opt ()
	{
		global $bf, $lang;

		$user_id = (int) $this->request['user_id'];
		$new_opt = bb_json_decode($this->request['user_opt']);

		if (!$user_id OR !$u_data = get_userdata($user_id))
		{
			$this->ajax_die('invalid user_id');
		}
		if (!is_array($new_opt))
		{
			$this->ajax_die('invalid new_opt');
		}

		foreach ($bf['user_opt'] as $opt_name => $opt_bit)
		{
			if (isset($new_opt[$opt_name]))
			{
				setbit($u_data['user_opt'], $opt_bit, !empty($new_opt[$opt_name]));
			}
		}

		DB()->query("UPDATE ". BB_USERS ." SET user_opt = {$u_data['user_opt']} WHERE user_id = $user_id LIMIT 1");

        // Удаляем данные из кеша
        cache_rm_user_sessions ($user_id);

		$this->response['resp_html'] = $lang['SAVED'];
	}

	function gen_passkey ()
	{
		global $userdata, $lang;

		$req_uid = (int) $this->request['user_id'];

		if ($req_uid == $userdata['user_id'] || IS_ADMIN)
		{
			if (empty($this->request['confirmed']))
			{
				$this->prompt_for_confirm($lang['BT_GEN_PASSKEY_NEW']);
			}

			if (!$passkey = generate_passkey($req_uid, IS_ADMIN))
			{
				$this->ajax_die('Could not insert passkey');
			}
			tracker_rm_user($req_uid);
			$this->response['passkey'] = $passkey;
		}
		else $this->ajax_die($lang['NOT_AUTHORISED']);
	}

    // User groups membership
	function group_membership ()
	{
		global $lang, $user;

		if (!$user_id = intval($this->request['user_id']) OR !$profiledata = get_userdata($user_id))
		{
			$this->ajax_die("invalid user_id: $user_id");
		}
		if (!$mode = (string) $this->request['mode'])
		{
			$this->ajax_die('invalid mode (empty)');
		}

		switch ($mode)
		{
			case 'get_group_list':
				$sql = "
					SELECT ug.user_pending, g.group_id, g.group_type, g.group_name, g.group_moderator, self.user_id AS can_view
					FROM       ". BB_USER_GROUP ." ug
					INNER JOIN ". BB_GROUPS     ." g ON(g.group_id = ug.group_id AND g.group_single_user = 0)
					 LEFT JOIN ". BB_USER_GROUP ." self ON(self.group_id = g.group_id AND self.user_id = {$user->id} AND self.user_pending = 0)
					WHERE ug.user_id = $user_id
					ORDER BY g.group_name
				";
				$html = array();
				foreach (DB()->fetch_rowset($sql) as $row)
				{
					$class  = ($row['user_pending']) ? 'med' : 'med bold';
					$class .= ($row['group_moderator'] == $user_id) ? ' colorMod' : '';
					$href   = "groupcp.php?g={$row['group_id']}";

					if (IS_ADMIN)
					{
						$href .= "&amp;u=$user_id";
						$link  = '<a href="'. $href .'" class="'. $class .'" target="_blank">'. htmlCHR($row['group_name']) .'</a>';
						$html[] = $link;
					}
					else
					{
						// скрытая группа и сам юзер не является её членом
						if ($row['group_type'] == GROUP_HIDDEN && !$row['can_view'])
						{
							continue;
						}
						if ($row['group_moderator'] == $user->id)
						{
							$class .= ' selfMod';
							$href  .= "&amp;u=$user_id";  // сам юзер модератор этой группы
						}
						$link  = '<a href="'. $href .'" class="'. $class .'" target="_blank">'. htmlCHR($row['group_name']) .'</a>';
						$html[] = $link;
					}
				}
				if ($html)
				{
					$this->response['group_list_html'] = '<tr><td class="row2" nowrap="nowrap" align=left class="catLeft"><span class="gentbl"><b>'. join('</b></span></td></tr><tr><td class="row2" nowrap="nowrap" align=left class="catLeft"><span class="gentbl"><b>', $html) .'</b></span></td></tr>';
				}
				else
				{
					$this->response['group_list_html'] = $lang['GROUP_LIST_HIDDEN'];
				}
				break;

			default:
				$this->ajax_die("invalid mode: $mode");
		}
	}

	function view_post ()
	{
		require(AJAX_DIR .'view_post.php');
	}

	function change_tor_status ()
	{
		require(AJAX_DIR .'change_tor_status.php');
	}

	function change_torrent ()
	{
		require(AJAX_DIR .'change_torrent.php');
	}

	function view_torrent ()
	{
		require(AJAX_DIR .'view_torrent.php');
	}

	function user_register()
    {
		require(AJAX_DIR .'user_register.php');
    }

    function mod_action()
    {
		require(AJAX_DIR .'mod_action.php');
    }

    function posts()
    {
		require(AJAX_DIR .'posts.php');
    }

	function manage_user()
	{
		require(AJAX_DIR .'manage_user.php');
	}

	function topic_tpl()
	{
		require(AJAX_DIR .'topic_tpl.php');
	}
	
	function index_data()
    {
		require(AJAX_DIR .'index_data.php');
	}
	function ads_status()
	{
		require(AJAX_DIR .'ads_status.php');
	}
	function user_stats()
		{
			global $bb_cfg, $lang;
			$user_id = (int) $this->request['user_id'];
			$btu = get_bt_userdata($user_id);
			$ratio = get_bt_ratio($btu);
			$u_up_total = humn_size($btu['u_up_total']);
			$u_up_bonus = humn_size($btu['u_up_bonus']);
			$seed_points = ($btu['seed_points']) ? ($btu['seed_points']) : 0;
			$u_up_release = humn_size($btu['u_up_release']);
			$u_down_total = humn_size($btu['u_down_total']);
			if ($btu['u_down_total'] < MIN_DL_FOR_RATIO) $ratio = '<b>'.$lang['NONE'].'</b> (DL <'.humn_size(MIN_DL_FOR_RATIO).')';
############ User Information ############
			$u_info = get_userdata($user_id);
			if($u_info['user_from']){$u_from ='<p class="small"><em>'.$lang['U_FROM'].':</em>'.$u_info['user_from'].'</p>';}else{$u_from ='';}
			if($bb_cfg['text_buttons']){
				if($u_info['user_gender'] == 0){
					$gender = '';
				}elseif($u_info['user_gender'] == 1){
					$gender = '<p class="small"><em>'.$lang['GENDER'].':</em>'.$lang['GENDER_SELECT'][1].'</p>';
				}elseif($u_info['user_gender'] == 2){
					$gender = '<p class="small"><em>'.$lang['GENDER'].':</em>'.$lang['GENDER_SELECT'][2].'</p>';
				}
			}else{
				if($u_info['user_gender'] == 0){
					$gender = '';
				}elseif($u_info['user_gender'] == 1){
					$gender = '<img src="images/gender_male.jpg" width="10px" height="10px" title="'.$lang['GENDER_SELECT'][1].'">';
				}elseif($u_info['user_gender'] == 2){
					$gender = '<img src="images/gender_female.jpg" width="10px" height="10px" title="'.$lang['GENDER_SELECT'][2].'">';
				}
			}
			if($u_info['user_gender'] != 0){$u_gender ='<p class="small"><em>'.$lang['GENDER'].':</em>'.$gender.'</p>';}else{$u_gender ='';}
			if($u_info['user_prov']){$u_prov ='<p class="small"><em>'.$lang['PROV'].':</em>'.$u_info['user_prov'].'</p>';}else{$u_prov ='';}
			$sp_up = (get_speed_cache($u_info['user_speed_up']) == $lang['NOT_DEFINED']) ? false : get_speed_cache($u_info['user_speed_up']);
			$sp_down = (get_speed_cache($u_info['user_speed_down']) == $lang['NOT_DEFINED']) ? false : get_speed_cache($u_info['user_speed_down']);
			if( $u_info['user_speed_down'] && $u_info['user_speed_up']){
				if($bb_cfg['text_buttons']){
				$spd_u = '<p class="small"><em>'.$lang['UPLOADSPEED'].':</em>'.$sp_up.'</p>';
				$spd_d = '<p class="small"><em>'.$lang['DOWNLOADSPEED'].':</em>'.$sp_down.'</p>';
				}else{
				$spd_u = '<center><p><img src="images/speed_up.png" width="17px" height="17px" title="'.$lang['UPLOADSPEED'].'">'.$sp_up;
				$spd_d = '<img src="images/speed_down.png" width="17px" height="17px" title="'.$lang['DOWNLOADSPEED'].'">'.$sp_down.'</p></center>';
				}
			}else{
			$spd_u = '';
			$spd_d = '';
			}

############ Закончили ###################

############ Выводим данные ##############
$this->response['post_id'] = (int) $this->request['post_id'];
$this->response['html'] = '
<!--<table class="ratio1 table-wrap bCenter borderless w100" cellspacing="1" style="border-style: inset;">
<tr class="row1 dlWill">
  <td class="med tLeft">&nbsp;<i><a class="small dlWill" href="profile.php?mode=bonus">Сид-бонус</a>:</i>&nbsp;</td>
  <td class="tLeft">&nbsp;<i>'.$seed_points.'</i></td>
</tr>
</table>-->
'. $u_from .'
'. $u_gender .'
'. $u_prov .'
<p class="small"><em>'.$lang['USER_RATIO'].':</em> <b>'.$ratio.'</b></p>
<p class="small" style="color: red;"><em><a class="leechsmall1" href="search.php?dlu='.$user_id.'&dlc=1#results">'.$lang['DOWNLOADED'].'</a></em>: <b>'.$u_down_total.'</b></p>
<p class="small" style="color: blue;"><em style="color: blue;">'.$lang['UPLOADED'].':</em> <b>'.$u_up_total.'</b></p>
<p class="small"><em><i><a class="seedsmall1" href="tracker.php?rid='.$user_id.'#results">'.$lang['RELEASED'].'</a></em>: <i><b class="seedsmall1">'.$u_up_release.'</b></i></p>
'.$spd_u.'&nbsp;'.$spd_d.'';
}
function rate_post()
{
	global $db, $userdata, $lang, $bb_cfg;
	$postid = intval($this->request['postid']);
	$rating = intval($this->request['rating']);
	$fetched_row = DB()->fetch_rowset("SELECT `poster_id`, `post_rating` FROM `bb_posts` WHERE `post_id`=".$postid." LIMIT 1;");
	if(($fetched_row[0]['poster_id'] != $userdata['user_id']) && ($fetched_row[0]['poster_id'] != "18")){
		$rating_array = $fetched_row[0]['post_rating'];
		if(empty($rating_array)){
			$rating_array = array();
			$rating_array[$userdata['user_id']] = $rating;
			//$dump = serialize($rating_array);
		} else {
			$rating_array = unserialize($rating_array);
			$dump = serialize($rating_array);
			$rating_array[$userdata['user_id']] = $rating;
			//$dump = serialize($rating_array);
		}
		$html = '';
				$ratings = array(
				1 => 'Согласен',
				2 => 'Не согласен',
				3 => 'Смешно',
				4 => 'Это победа',
				5 => 'Огочо',
				6 => 'Кэп',
				7 => 'Понравилось',
				8 => 'Полезно',
				9 => 'Оптимистично',
				10 => 'Креатив',
				11 => 'Too slow',
				12 => 'Идиот'
			);
			$ratings_count = array();
			$post_rating_html = "";
			foreach($rating_array as $post_rating_user_id => $post_rating_rating){
				@$ratings_count[$post_rating_rating]++;
			}
			if(count($ratings_count) <= 3) {
				foreach($ratings_count as $ratings_count_id => $ratings_count_times){
					$html .= '<span><img src="images/postrating/'.$ratings_count_id.'.png" title="'.$ratings[$ratings_count_id].'" 
/> '.$ratings[$ratings_count_id].' x <strong>'.$ratings_count_times.'</strong></span> ';
				}
			} else {
				foreach($ratings_count as $ratings_count_id => $ratings_count_times){
					$html .= '<span><img src="images/postrating/'.$ratings_count_id.'.png" title="'.$ratings[$ratings_count_id].'" 
/> x <strong>'.$ratings_count_times.'</strong></span> ';
				}
			}
		$rating_array = serialize($rating_array);
		DB()->sql_query("UPDATE `bb_posts` SET `post_rating` = '".$rating_array."' WHERE `post_id`=".$postid.";");
		$this->response['message']  = "Я заснял!";
		$this->response['result']  = $html;
		//$this->response['dump']  = $dump;
	} else {
		$this->response['error']  = "Я не заснял.";
	}
}
}
