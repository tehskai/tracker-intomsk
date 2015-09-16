<?php

define('IN_PHPBB', true);
define('BB_SCRIPT', 'hits');
define('BB_ROOT', './');
require(BB_ROOT ."common.php");

//
// Start session management
//

$user->session_start(array('req_login' => true));

$page_title = $lang['HITS'];
$user_id = $userdata['user_id'];

		$template->assign_vars(array(
		'PAGE_TITLE'     =>  $page_title
	    )
	  );
	if (isset($_REQUEST['id']))  
		{
			$id = $_REQUEST['id'];
			if (preg_match("/[^a-zA-Z0-9_]/", $id))
				{
				Header("Location: hits.php");
				exit;
				}
		}
		else
		{
			$id = '';
		}

switch ($id)
{

case 'all_hits':
		$template->assign_vars(array(
	   'TPL_P_HITS'         => true,
		'TPL_EDIT_HITS'      => false,
		'TPL_ADD_HITS'       => false,
		'PAGE_TITLE'         => $page_title,
	    )
	  );
	  $sql = 'SELECT * FROM `bb_torhit` ORDER BY `torhit_id` DESC';
	  if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, $lang['ERR_HITS_LIST'], '', __LINE__, __FILE__, $sql);
	  $hit_row = DB()->sql_fetchrowset($result);
	  $num_hit_row = DB()->num_rows($result);
	  DB()->sql_freeresult($result);
	  if ($num_hit_row > 0) {
		$n=0;
	    for ($i = 0; $i < $num_hit_row; $i++) {
	    $n++;
	      $template->assign_block_vars('hit_row', array(
	        'NUM' => $n,
	        'HIT_ID' => $hit_row[$i]['torhit_id'],
	        'HIT_TITLE' => $hit_row[$i]['torhit_title'],
	        'HIT_IMG' => $hit_row[$i]['torhit_images'],
	        'E_LINK' => $hit_row[$i]['torhit_id']
	        )
	      );
	    }
	  }
break;



case "edit":
if (isset($_POST['ids']))
{
$ids = $_POST['ids'];
}
		$template->assign_vars(array(
		'TPL_P_HITS'        => false,
		'TPL_EDIT_HITS'     => true,
		'TPL_ADD_HITS'      => false,
		'PAGE_TITLE'        => $page_title,
		)
	  );
	  $sql = "SELECT * FROM `bb_torhit` WHERE `torhit_id` = $ids ";
	  if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, $lang['ERR_HITS_LIST'], '', __LINE__, __FILE__, $sql);
	  $hit_row = DB()->sql_fetchrowset($result);
	  $num_hit_row = DB()->num_rows($result);
	  DB()->sql_freeresult($result);
	  if ($num_hit_row > 0) {
	    for ($i = 0; $i < $num_hit_row; $i++) {
	      $template->assign_block_vars('edit_hit', array(
	        'HIT_ID'     => $hit_row[$i]['torhit_id'],
	        'HIT_TITLE'  => $hit_row[$i]['torhit_title'],
	        'HIT_IMG'    => $hit_row[$i]['torhit_images']
	        )
	      );
	    }
	  }
	  break;
case 'edit_news':

if (isset($_POST['hid']))
{
$h_id = $_POST['hid'];
}
if (isset($_POST['linkth1']))
{
$linkth = $_POST['linkth1'];
}
if (isset($_POST['descr1']))
{
$descr = $_POST['descr1'];
}
$link = $linkth;

		$template->assign_vars(array(
		'TPL_P_HITS'    => false,
		'TPL_EDIT_HITS' => true,
		'TPL_ADD_HITS'  => false,
		'PAGE_TITLE'    => $page_title
	    )
	  );


	if (preg_match("/^[ А-яа-яA-Za-z0-9\-\]\[\)\( \?\!\=\_\/\;\,\.\:]+$/i", $link) && preg_match("/^[ А-яа-яA-Za-z0-9\-\]\[\)\( \?\!\=\_\/\;\,\.\:\|\'\"]+$/i", $descr))
	{
		$descr = htmlspecialchars($descr, ENT_QUOTES);
		if ($id="hits.php" && $link && $descr)
		{
				$sql = "UPDATE `bb_torhit` SET `torhit_title`='{$link}', `torhit_images` = '{$descr}' WHERE torhit_id={$h_id}";
				DB()->sql_query($sql);
				DB()->close(DB());
				
				$title = 'Редактирование';
				$info  = $lang['HIT_EDITED'];
				
				$template->assign_vars(array(
					'TPL_P_HITS' => false,
					'TPL_EDIT_HITS' => false,
					'TPL_ADD_HITS'  => false,
					'TPL_INFO'      => true,
					'L_TITLE'       => $title,
					'L_INFO'        => $info,
					'PAGE_TITLE'    => $page_title
					)
				);
			}
		else
		{
			$title = 'Ошибка';
			$info  = '<p><b>MySQL Error</b></p>';
			
			$template->assign_vars(array(
				'TPL_P_HITS'    => false,
				'TPL_EDIT_HITS' => false,
				'TPL_ADD_HITS'  => false,
				'TPL_INFO'      => true,
				'L_TITLE'       => $title,
				'L_INFO'        => $info,
				'PAGE_TITLE'    => $page_title
				)
			);
		}
	}
	else
	{
		$title = 'Ошибка';
		$info  = $lang['HIT_NOT_RELATED_CHARACTERS'];
		
		$template->assign_vars(array(
			'TPL_P_HITS'    => false,
			'TPL_EDIT_HITS' => false,
			'TPL_ADD_HITS'  => false,
			'TPL_INFO'      => true,
			'L_TITLE'       => $title,
			'L_INFO'        => $info,
			'PAGE_TITLE'    => $page_title
			)
		);
	}
break;

case 'add':
		$template->assign_vars(array(
		'TPL_P_HITS'    => false,
		'TPL_EDIT_HITS' => false,
		'TPL_ADD_HITS'  => true,
		'PAGE_TITLE'    => $page_title
	    )
	  );

break;
case 'add_news':

if (isset($_POST['hid']))
{
$h_id = $_POST['hid'];
}
if (isset($_POST['platform']))
{
$platform = $_POST['platform'];
}
if (isset($_POST['linkth']))
{
$linkth = $_POST['linkth'];
}
if (isset($_POST['descr']))
{
$descr = $_POST['descr'];
}
$link = $linkth;

		$template->assign_vars(array(
		'TPL_P_HITS'    => false,
		'TPL_EDIT_HITS' => false,
		'TPL_ADD_HITS'  => true,
		'PAGE_TITLE'    => $page_title,
	    )
	  );

	if (preg_match("/^[ А-Яа-яA-Za-z0-9\-\]\[\)\( \?\!\=\_\/\;\,\.\:]+$/i", $link) && preg_match("/^[ А-Яа-яA-Za-z0-9\-\]\[\)\( \?\!\=\_\/\;\,\.\:\|\'\"]+$/i", $descr))
	{
		$descr = htmlspecialchars($descr, ENT_QUOTES);
		if ($id="hits.php" && $link && $descr)
		{
			$sql = "SELECT * from bb_torhit where torhit_title='".$link."'";
			if( !($check = DB()->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Could not query users", '', __LINE__, __FILE__, $sql);
			}
			$check1=DB()->num_rows($check);
			if($check1>0)
			{
				$title = 'Проверка';
				$info  = $lang['HIT_IS_ALREADY'];
				
				$template->assign_vars(array(
					'TPL_P_HITS'    => false,
					'TPL_EDIT_HITS' => false,
					'TPL_ADD_HITS'  => false,
					'TPL_INFO'      => true,
					'L_TITLE'       => $title,
					'L_INFO'        => $info,
					'PAGE_TITLE'    => $page_title
					)
				);
			}
			else
			{
				$add = "INSERT INTO `bb_torhit` (`torhit_title`, `torhit_images`) VALUES ('{$link}', '{$platform} {$descr}')";
				DB()->sql_query($add);
				DB()->close(DB());
				
				$title = 'Добавление';
				$info  = $lang['HIT_ADDED'];
				
				$template->assign_vars(array(
					'TPL_P_HITS'    => false,
					'TPL_EDIT_HITS' => false,
					'TPL_ADD_HITS'  => false,
					'TPL_INFO'      => true,
					'L_TITLE'       => $title,
					'L_INFO'        => $info,
					'PAGE_TITLE'    => $page_title
					)
				);
			}
		}
		else
		{
			$title = 'Ошибка';
			$info  = '<p><b>MySQL Error</b></p>';
			
			$template->assign_vars(array(
				'TPL_P_HITS'    => false,
				'TPL_EDIT_HITS' => false,
				'TPL_ADD_HITS'  => false,
				'TPL_INFO'      => true,
				'L_TITLE'       => $title,
				'L_INFO'        => $info,
				'PAGE_TITLE'    => $page_title
				)
			);
		}
	}
	else
	{
		$title = 'Ошибка';
		$info  = $lang['H_NOT_RELATED_CHARACTERS'];
		
		$template->assign_vars(array(
			'TPL_P_HITS'    => false,
			'TPL_EDIT_HITS' => false,
			'TPL_ADD_HITS'  => false,
			'TPL_INFO'      => true,
			'L_TITLE'       => $title,
			'L_INFO'        => $info,
			'PAGE_TITLE'    => $page_title
			)
		);
	}

break;

case 'delete':

if (isset($_POST['ids']))
{
$h_id = $_POST['ids'];
}

		$template->assign_vars(array(
		'TPL_P_HITS'      => false,
		'TPL_EDIT_HITS'   => false,
		'TPL_DELETE_HITS' => true,
		'TPL_ADD_HITS'    => false,
		'PAGE_TITLE'      => $page_title
	    )
	  );
				$sql = "DELETE FROM `bb_torhit` WHERE torhit_id={$h_id}";
				DB()->sql_query($sql);
				DB()->close(DB());
				
				$title = 'Удаление';
				$info  = $lang['HIT_DELETED'];
				
				$template->assign_vars(array(
					'TPL_P_HITS'    => false,
					'TPL_EDIT_HITS' => false,
					'TPL_ADD_HITS'  => false,
					'TPL_INFO'      => true,
					'L_TITLE'       => $title,
					'L_INFO'        => $info,
					'PAGE_TITLE'    => $page_title
					)
				);
				
break;

default:
		$template->assign_vars(array(
		'TPL_P20_HITS'       => true,
	   'TPL_P_HITS'         => false,
		'TPL_EDIT_HITS'      => false,
		'TPL_ADD_HITS'       => false,
		'PAGE_TITLE'         => $page_title,
	    )
	  );
	  $sql = 'SELECT * FROM `bb_torhit` ORDER BY `torhit_id` DESC LIMIT 20';
	  if (!($result = DB()->sql_query($sql))) message_die(GENERAL_ERROR, $lang['ERR_HITS_LIST'], '', __LINE__, __FILE__, $sql);
	  $hit_row = DB()->sql_fetchrowset($result);
	  $num_hit_row = DB()->num_rows($result);
	  DB()->sql_freeresult($result);
	  if ($num_hit_row > 0) {
		$n=0;
	    for ($i = 0; $i < $num_hit_row; $i++) {
	    $n++;
	      $template->assign_block_vars('hit_row', array(
	        'NUM' => $n,
	        'HIT_ID' => $hit_row[$i]['torhit_id'],
	        'HIT_TITLE' => $hit_row[$i]['torhit_title'],
	        'HIT_IMG' => $hit_row[$i]['torhit_images'],
	        'E_LINK' => $hit_row[$i]['torhit_id']
	        )
	      );
	    }
	  }
}
print_page('hits.tpl');
		
?>