<?php

if (!defined('BB_ROOT')) die(basename(__FILE__));

require(INC_DIR .'bbcode.php');

$datastore->enqueue(array(
	'ranks',
));

if (empty($_GET[POST_USERS_URL]) || $_GET[POST_USERS_URL] == ANONYMOUS)
{
	bb_die($lang['NO_USER_ID_SPECIFIED']);
}
if (!$profiledata = get_userdata($_GET[POST_USERS_URL]))
{
	bb_die($lang['NO_USER_ID_SPECIFIED']);
}

if (!$userdata['session_logged_in'])
{
	redirect("login.php?redirect={$_SERVER['REQUEST_URI']}");
}

if (!$ranks = $datastore->get('ranks'))
{
	$datastore->update('ranks');
	$ranks = $datastore->get('ranks');
}

$poster_rank = $rank_image= $rank_style = $rank_select = '';
if ($user_rank = $profiledata['user_rank'] AND isset($ranks[$user_rank]))
{
	$rank_image = ($ranks[$user_rank]['rank_image']) ? '<img src="'. $ranks[$user_rank]['rank_image'] .'" alt="" title="" border="0" />' : '';
	$poster_rank = $ranks[$user_rank]['rank_title'];
	$rank_style  = $ranks[$user_rank]['rank_style'];
}
$park_status = $profiledata['user_park_profile'] ? $lang['PARK_PROFILE_STATUS'] : '';
 
$template->assign_vars(array(
    'STATUS_PARK'            => $park_status,
));
if (IS_ADMIN)
{
	$rank_select = array($lang['NO'] => 0);
	foreach ($ranks as $row)
	{
		$rank_select[$row['rank_title']] = $row['rank_id'];
	}
	$rank_select = build_select('rank-sel', $rank_select, $user_rank);
}


if (bf($profiledata['user_opt'], 'user_opt', 'viewemail') || IS_AM)
{
	$email_uri = ($bb_cfg['board_email_form']) ? 'profile.php?mode=email&amp;'. POST_USERS_URL .'='. $profiledata['user_id'] : 'mailto:'. $profiledata['user_email'];
	$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['SEND_EMAIL'] . '" title="' . $lang['SEND_EMAIL'] . '" border="0" /></a>';
	$email = '<a class="editable" href="'. $email_uri .'">'. $profiledata['user_email'] .'</a>';
}
else
{
	$email_img = '';
	$email = '';
}
$www_img = ( $profiledata['user_website'] ) ? '<a href="' . $profiledata['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['VISIT_WEBSITE'] . '" title="' . $lang['VISIT_WEBSITE'] . '" border="0" /></a>' : '';
$www = ( $profiledata['user_website'] ) ? '<a href="' . $profiledata['user_website'] . '" target="_userwww">' . $profiledata['user_website'] . '</a>' : '';
if ( !empty($profiledata['user_icq']) )
{
	$icq_status_img = '<a href="http://www.icq.com/people/searched=1&uin=' . $profiledata['user_icq'] . '" target="_blank" ><img src="http://web.icq.com/whitepages/online?icq=' . $profiledata['user_icq'] . '&img=5" width="18" height="18" border="0" /></a>';
	$icq_img = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $profiledata['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $profiledata['user_icq'] . '" border="0" /></a>';
	$icq =  '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $profiledata['user_icq'] . '">' . $lang['ICQ'] . '</a>';
}
else
{
	$icq_status_img = '';
	$icq_img = '';
	$icq = '';
}

if ( !empty($profiledata['user_skype']) )
{//<a href="skype:{SKYPE}">
	$skype_status_img = '<a href="skype:"' . $profiledata['user_skype'] . '" target="_blank" ><img src="http://mystatus.skype.com/smallicon/' . $profiledata['user_skype'] . '&img=5" width="18" height="18" border="0" /></a>';
	$skype_img = '<a href="skype:' . $profiledata['user_skype'] . '"><img src="' . $images['icon_skype'] . '" alt="' . $lang['SKYPE'] . '" title="' . $profiledata['user_skype'] . '" border="0" /></a>';
	//$skype =  '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $profiledata['user_skype '] . '">' . $lang['ICQ'] . '</a>';
}
else
{
	$skype_status_img = '';
	$skype_img = '';
	//$icq = '';
}

// Report
//
// Get report user module and create report link
//
include(INC_DIR ."functions_report.php");
$report_user = report_modules('name', 'report_user');

if ($report_user && $report_user->auth_check('auth_write'))
{
	$template->assign_block_vars('switch_report_user', array());
	$template->assign_vars(array(
		'U_REPORT_USER' => 'report.php?mode='. $report_user->mode .'&amp;id='. $profiledata['user_id'],
		'L_REPORT_USER' => $report_user->lang['WRITE_REPORT'])
	);
}
// Report [END]

//
// Generate page
//

$profile_user_id = ($profiledata['user_id'] == $userdata['user_id']);

$signature = ($bb_cfg['allow_sig'] && $profiledata['user_sig']) ? $profiledata['user_sig'] : '';

if(bf($profiledata['user_opt'], 'user_opt', 'allow_sig'))
{
	if($profile_user_id)
	{
		$signature = $lang['SIGNATURE_DISABLE'];
	}
	else
	{
		$signature = '';
	}
}
else if ($signature)
{
	$signature = bbcode2html($signature);
}

//
// Проверка на VIP
//
$vrow = DB()->fetch_row("SELECT * FROM bb_vip_tarif WHERE vip_tar_id = '".$profiledata['vip_tarif']."'");
$cur_date = time();
if ($profiledata['vip_ballance'] >=0 AND $profiledata['vip_tarif'] > 0 AND $profiledata['vip_start_date']<$cur_date AND $profiledata['vip_end_date'] > $cur_date OR $profiledata['vip_end_date'] < 0 AND $profiledata['vip_lock'] < 1)
{
$template->assign_vars(array(
	'NO_ADD'	=> true
));
}

if ($userdate['user_id'] = $profiledata['user_id'] or $userdate['user_level']=2)
{
if ($profiledata['vip_ballance'] >= 0 )
{
	if ($profiledata['vip_tarif'] > 0 )
	{
		if ($profiledata['vip_start_date'] < $cur_date)
		{
			if ($profiledata['vip_end_date'] > $cur_date || $profiledata['vip_end_date'] == -1)
			{
				if ($profiledata['vip_lock'] < 1)
				{
					if($profiledata['vip_end_date'] > 0){
					$end_date = bb_date($profiledata['vip_end_date']);
					} else {
					$end_date = 'Тариф Unlim не закончится.';
					} 
					$date2 = $vrow['vip_tar_time'] / 86400;
				if($date2 == 7){
					$tar_color = "#DD5500";
				}elseif($date2 > 7 && $date2 <= 30){
					$tar_color = "#DDAA00";
				}elseif($date2 > 30 && $date2 <= 60){
					$tar_color = "#AADD00";
				}elseif($date2 > 60 && $date2 <= 180){
					$tar_color = "#00AA00";
				}elseif($date2 > 180 && $date2 <= 365){
					$tar_color = "#99AA00";
				}elseif($date2 < 0){
					$tar_color = "#009999";
				}
					$vip_body = '<strong class="colorVIP" ><b>VIP</b></strong><tr><th>Тарифный план:</th><td><strong style="color:'.$tar_color.'">'.$vrow['vip_tar_name'].'</strong></td></tr><tr><th>Балланс:</th><td>'.$profiledata['vip_ballance'].' IN</td></tr><tr><th>Начат:</th><td>'.bb_date($profiledata['vip_start_date']).'</td></tr><tr><th>Закончится:</th><td>'.$end_date.'</td></tr>';
				}
				else
				{
					$vip_body = '<strong style="color:darkred"><b>NO VIP</b></strong>&nbsp;<small> Ваш статус VIP был заморожен Администратором, связаться с <a href="groupcp.php?g=6"><b>Администраторами</b></a></small>';
				}
			}
			else
			{
				$vip_body = '<strong style="color:darkred"><b>NO VIP</b></strong>&nbsp; <small>Ваш тарифный план VIP закончился в <b>'.bb_date($profiledata['vip_end_date']).'</b></small>';
			}
		}
		else
		{
			$vip_body = '<strong style="color:darkred">NO VIP</strong>&nbsp; <small>Тарифный план VIP начнется в <b>'.bb_date($profiledata['vip_start_date']).'</b></small>';
		}
	}
	else
	{
		$vip_body = '<strong style="color:darkred"><b>NO VIP</b></strong>&nbsp; <small>У вас нет статуса VIP, для получения свяжитесь с <a href="groupcp.php?g=6"><b>Администраторами</b></a></small>';
	}
}
else
{
	$vip_body = '<strong style="color:darkred"><b>NO VIP</b></strong>&nbsp; <small>>В данный момент ваш Балланс в минусе и у вас отключен статус VIP</small>';
}
}
else
{
$vip_body = '<strong class="colorVIP"><b>VIP</strong>&nbsp; Тарифный план: <strong style="color:#009900">'.$vrow['vip_tar_name'].'</strong>';
}
// Конец

// Prov
$prov = ($profiledata['user_prov']) ? $profiledata['user_prov'] : '';

// Status
$user_status = ($profiledata['user_status']) ? $profiledata['user_status'] : '';

//user group#
if((IS_ADMIN || IS_MOD) && $profiledata['user_readonly'] == "0" && $profiledata['user_level'] !=MOD && $profiledata['user_level'] != ADMIN){
$readonly_switch = '<input type="submit" name="usersubmit" class="lite" value="Заглушить" onclick="window.open(\'readonly.php?action=add&id='.$profiledata['user_id'].'\', \'readonly\', \'HEIGHT=350,resizable=yes,WIDTH=410\');return false;" />';
} else {
$readonly_switch = '';
}

// User group
$user_id = $userdata['user_id'];
$view_user_id = $profiledata['user_id'];
$groups = array();
$sql = '
   SELECT
      g.group_id,
      g.group_name,
      g.group_type
   FROM
      ' . BB_USER_GROUP . ' as l,
      ' . BB_GROUPS . ' as g
   WHERE
      l.user_pending = 0 AND
      g.group_single_user = 0 AND
      l.user_id ='. $view_user_id.' AND
      g.group_id = l.group_id
   ORDER BY
      g.group_name,
      g.group_id';
if ( !($result = DB()->sql_query($sql)) ) message_die(GENERAL_ERROR, 'Could not read groups', '', __LINE__, __FILE__, $sql);   
while ($group = DB()->sql_fetchrow($result)) $groups[] = $group;

$template->assign_vars(array(
   'L_USERGROUPS' => $lang['USERGROUPS'],
   )
);
if (count($groups) > 0)
{
$groupsw=TRUE;
   for ($i=0; $i < count($groups); $i++)
   {
      $is_ok = false;
      //
      // groupe invisible ?
      if ( ($groups[$i]['group_type'] != GROUP_HIDDEN) || ($userdata['user_level'] == ADMIN) )
      {
         $is_ok=true;
      }
      else
      {
         $group_id = $groups[$i]['group_id'];
         $sql = 'SELECT * FROM '. BB_USER_GROUP .' WHERE group_id='.$group_id.' AND user_id='.$user_id.' AND user_pending=0';
         if ( !($result = DB()->sql_query($sql)) ) message_die(GENERAL_ERROR, 'Couldn\'t obtain viewer group list', '', __LINE__, __FILE__, $sql);
         $is_ok = ( $group = DB()->sql_fetchrow($result) );
      }  // end if ($view_list[$i]['group_type'] == GROUP_HIDDEN)
      //
      // groupe visible : afficher
      if ($is_ok)
      {
         $u_group_name = 'groupcp.php?g='.$groups[$i]['group_id'];
         $l_group_name = $groups[$i]['group_name'];
         $template->assign_block_vars('groups',array(
            'U_GROUP_NAME' => $u_group_name,
            'L_GROUP_NAME' => $l_group_name,
            )
         );
      }  // end if ($is_ok)
   }  // end for ($i=0; $i < count($groups); $i++)
}  // end if (count($groups) > 0)
else
{
$groupsw = false;
}
//user group#

$template->assign_vars(array(
	'PAGE_TITLE'           => sprintf($lang['VIEWING_USER_PROFILE'], $profiledata['username']),
	'USERNAME'             => $profiledata['username'],
	'PROFILE_USER_ID'      => $profiledata['user_id'],
	'USER_REGDATE'         => bb_date($profiledata['user_regdate'], 'Y-m-d H:i', 'false'),
	'POSTER_RANK'          => ($poster_rank) ? "<span class=\"$rank_style\">". $poster_rank ."</span>" : $lang['USER'],
	'RANK_IMAGE'           => $rank_image,
	'RANK_SELECT'          => $rank_select,
	'POSTS'                => $profiledata['user_posts'],
	'PM_IMG'               => '<a href="privmsg.php?mode=post&amp;'. POST_USERS_URL .'='. $profiledata['user_id'] .'"><img src="' . $images['icon_pm'] . '" alt="' . $lang['SEND_PRIVATE_MESSAGE'] . '" title="' . $lang['SEND_PRIVATE_MESSAGE'] . '" border="0" /></a>',
	'EMAIL_IMG'            => $email_img,
	'PM'                   => '<a href="privmsg.php?mode=post&amp;'. POST_USERS_URL .'='. $profiledata['user_id'] .'">'. $lang['SEND_PRIVATE_MESSAGE'] .'</a>',
//	'PM'                   => '<a href="'. append_sid('privmsg.php?mode=post&amp;'. POST_USERS_URL .'='. $profiledata['user_id']) .'">'. $lang['SEND_PRIVATE_MESSAGE'] .'</a>',
	'EMAIL'                => $email,
	'GROUPSW'              => $groupsw,
	'WWW_IMG'              => $www_img,
	'WWW'                  => $profiledata['user_website'],
	'ICQ'                  => $profiledata['user_icq'],
	'ICQ_STATUS_IMG'       => $icq_status_img,
	'ICQ_IMG'              => $icq_img,
	'LAST_VISIT_TIME'      => ($profiledata['user_lastvisit']) ? (bf($profiledata['user_opt'], 'user_opt', 'allow_viewonline') && !IS_ADMIN) ? $lang['HIDDEN_USER'] : bb_date($profiledata['user_lastvisit'], 'Y-m-d H:i', 'false') : $lang['NEVER'],
	'LAST_ACTIVITY_TIME'   => ($profiledata['user_session_time']) ? (bf($profiledata['user_opt'], 'user_opt', 'allow_viewonline') && !IS_ADMIN) ? $lang['HIDDEN_USER'] : bb_date($profiledata['user_session_time'], 'Y-m-d H:i', 'false') : $lang['NEVER'],
	'ALLOW_DLS'            => (!bf($profiledata['user_opt'], 'user_opt', 'allow_dls') || (IS_AM || $profile_user_id)),
	'LOCATION'             => $profiledata['user_from'],
	'SPEED_USER_UP'        => ($profiledata['user_speed_up']) ? user_speed($profiledata['user_speed_up']) : $lang['NOT_DEFINED'],
	'SPEED_USER_DOWN'      => ($profiledata['user_speed_down']) ? user_speed($profiledata['user_speed_down']) : $lang['NOT_DEFINED'],
	'USER_ACTIVE'          => $profiledata['user_active'],
	'PROV'                 => $prov,
	'STATUS'               => $user_status,

	'OCCUPATION'           => $profiledata['user_occ'],
	'INTERESTS'            => $profiledata['user_interests'],
	'SKYPE'                => $profiledata['user_skype'],
	'SKYPE_STATUS_IMG'     => $skype_status_img,
	'SKYPE_IMG'            => $skype_img,
	'GENDER'               => ($bb_cfg['gender'] && $profiledata['user_gender']) ? $lang['GENDER_SELECT'][$profiledata['user_gender']] : '',
	'BIRTHDAY'             => ($bb_cfg['birthday_enabled'] && $profiledata['user_birthday']) ? realdate($profiledata['user_birthday'], 'Y-m-d') : '',
	'AGE'                  => ($bb_cfg['birthday_enabled'] && $profiledata['user_birthday']) ? birthday_age($profiledata['user_birthday']) : '',
	'AVATAR_IMG'           => get_avatar($profiledata['user_avatar'], $profiledata['user_avatar_type'], !bf($profiledata['user_opt'], 'user_opt', 'allow_avatar')),

	'L_VIEWING_PROFILE'    => sprintf($lang['VIEWING_USER_PROFILE'], $profiledata['username']),

	'U_SEARCH_USER'        => "search.php?search_author=1&amp;uid={$profiledata['user_id']}",
	'U_SEARCH_TOPICS'      => "search.php?uid={$profiledata['user_id']}&amp;myt=1",
	'U_SEARCH_RELEASES'    => "tracker.php?rid={$profiledata['user_id']}#results",

	'S_PROFILE_ACTION'     => 'profile.php',
	'VIP_BODY'			=> $vip_body,
	'L_SIGNATURE_USER'     => $lang['SIGNATURE'],
	'SIGNATURE'            => $signature,
	'READONLY_BUTTON'  => $readonly_switch,
	'SHOW_PASSKEY'         => (IS_ADMIN || $profile_user_id),
	'SHOW_ROLE'            => (IS_AM || $profile_user_id || $profiledata['user_active']),
	'GROUP_MEMBERSHIP'     => false,
	'TRAF_STATS'           => !(IS_AM || $profile_user_id),
));

if (IS_ADMIN)
{
	$group_membership = array();
	$sql = "
		SELECT COUNT(g.group_id) AS groups_cnt, g.group_single_user, ug.user_pending
		FROM ". BB_USER_GROUP ." ug
		LEFT JOIN ". BB_GROUPS ." g USING(group_id)
		WHERE ug.user_id = {$profiledata['user_id']}
		GROUP BY ug.user_id, g.group_single_user, ug.user_pending
		ORDER BY NULL
	";
	if ($rowset = DB()->fetch_rowset($sql))
	{
		$member = $pending = $single = 0;
		foreach ($rowset as $row)
		{
			if (!$row['group_single_user'] && !$row['user_pending'])
			{
				$member = $row['groups_cnt'];
			}
			else if (!$row['group_single_user'] && $row['user_pending'])
			{
				$pending = $row['groups_cnt'];
			}
			else if ($row['group_single_user'])
			{
				$single = $row['groups_cnt'];
			}
		}
		if ($member)  $group_membership[] = $lang['PARTY'] ." <b>$member</b>";
		if ($pending) $group_membership[] = $lang['CANDIDATE'] ." <b>$pending</b>";
		if ($single)  $group_membership[] = $lang['INDIVIDUAL'];
		$group_membership = join(', ', $group_membership);
	}
	$template->assign_vars(array(
		'GROUP_MEMBERSHIP'      => (bool) $group_membership,
		'GROUP_MEMBERSHIP_TXT'  => $group_membership,
	));
}
else if (IS_MOD)
{
	$template->assign_vars(array(
		'SHOW_GROUP_MEMBERSHIP' => ($profiledata['user_level'] != USER),
	));
}

if (!bf($profiledata['user_opt'], 'user_opt', 'allow_dls') || (IS_AM || $profile_user_id))
{
    // Show users torrent-profile
    define('IN_VIEWPROFILE', TRUE);
    include(INC_DIR .'ucp/torrent_userprofile.php');
}

// Ajax bt_userdata
if (IS_AM || $profile_user_id)
{
    show_bt_userdata($profiledata['user_id']);
}
else
{
	$template->assign_vars(array(
		'DOWN_TOTAL_BYTES' => false,
		'MIN_DL_BYTES' => false,
	));
}

$template->assign_vars(array(
	'SHOW_ACCESS_PRIVILEGE' => IS_ADMIN,
	'IGNORE_SRV_LOAD'       => ($profiledata['user_level'] != USER || $profiledata['ignore_srv_load']) ? $lang['NO'] : $lang['YES'],
	'IGNORE_SRV_LOAD_EDIT'  => ($profiledata['user_level'] == USER),
));

if (IS_ADMIN)
{
	$template->assign_vars(array(
		'EDITABLE_TPLS' => true,

		'U_MANAGE'      => "profile.php?mode=editprofile&amp;u={$profiledata['user_id']}",
		'U_PERMISSIONS' => "admin/admin_ug_auth.php?mode=user&amp;u={$profiledata['user_id']}",
	));

	$ajax_user_opt = bb_json_encode(array(
		'allow_avatar'     => bf($profiledata['user_opt'], 'user_opt', 'allow_avatar'),
		'allow_sig'        => bf($profiledata['user_opt'], 'user_opt', 'allow_sig'),
		'allow_passkey'    => bf($profiledata['user_opt'], 'user_opt', 'allow_passkey'),
		'allow_pm'         => bf($profiledata['user_opt'], 'user_opt', 'allow_pm'),
		'allow_post'       => bf($profiledata['user_opt'], 'user_opt', 'allow_post'),
		'allow_post_edit'  => bf($profiledata['user_opt'], 'user_opt', 'allow_post_edit'),
		'allow_topic'      => bf($profiledata['user_opt'], 'user_opt', 'allow_topic'),
	));

	$template->assign_vars(array(
		'EDITABLE_TPLS'    => true,
		'AJAX_USER_OPT'    => $ajax_user_opt,
		'EMAIL_ADDRESS'    => htmlCHR($profiledata['user_email']),
	));
}

$user_restrictions = array();

if (bf($profiledata['user_opt'], 'user_opt', 'allow_avatar'))    $user_restrictions[] = $lang['HIDE_AVATARS'];
if (bf($profiledata['user_opt'], 'user_opt', 'allow_sig'))     $user_restrictions[] = $lang['SHOW_CAPTION'];
if (bf($profiledata['user_opt'], 'user_opt', 'allow_passkey'))   $user_restrictions[] = $lang['DOWNLOAD_TORRENT'];
if (bf($profiledata['user_opt'], 'user_opt', 'allow_pm'))        $user_restrictions[] = $lang['SEND_PM'];
if (bf($profiledata['user_opt'], 'user_opt', 'allow_post'))      $user_restrictions[] = $lang['SEND_MESSAGE'];
if (bf($profiledata['user_opt'], 'user_opt', 'allow_post_edit')) $user_restrictions[] = $lang['EDIT_POST'];
if (bf($profiledata['user_opt'], 'user_opt', 'allow_topic'))     $user_restrictions[] = $lang['NEW_THREADS'];

$template->assign_var('USER_RESTRICTIONS', join('</li><li>', $user_restrictions));

print_page('usercp_viewprofile.tpl');
