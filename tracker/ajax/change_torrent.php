<?php

if (!defined('IN_AJAX')) die(basename(__FILE__));

global $userdata, $bb_cfg, $lang;

if (!isset($this->request['attach_id']))
{
	$this->ajax_die($lang['EMPTY_ATTACH_ID']);
}
if (!isset($this->request['type']))
{
	$this->ajax_die('type');
}
$attach_id  = (int) $this->request['attach_id'];
$type       = (string) $this->request['type'];

$torrent = DB()->fetch_row("
		SELECT
			a.post_id, d.physical_filename, d.extension, d.tracker_status,
			t.topic_first_post_id,
			p.poster_id, p.topic_id, p.forum_id,
			f.allow_reg_tracker
		FROM
			". BB_ATTACHMENTS      ." a,
			". BB_ATTACHMENTS_DESC ." d,
			". BB_POSTS            ." p,
			". BB_TOPICS           ." t,
			". BB_FORUMS           ." f
		WHERE
			    a.attach_id = $attach_id
			AND d.attach_id = $attach_id
			AND p.post_id = a.post_id
			AND t.topic_id = p.topic_id
			AND f.forum_id = p.forum_id
		LIMIT 1
	");

if (!$torrent) $this->ajax_die($lang['INVALID_ATTACH_ID']);

if($torrent['poster_id'] == $userdata['user_id'] && !IS_AM)
{
    if($type == 'del_torrent' || $type == 'reg' || $type == 'unreg')
    {
    	true;
    }
    else
	{
	    $this->ajax_die($lang['ONLY_FOR_MOD']);
    }
}
elseif(!IS_AM)
{
	$this->ajax_die($lang['ONLY_FOR_MOD']);
}

$title = $url = '';
switch($type)
{
	case 'set_gold';
	case 'set_silver';
	case 'unset_silver_gold';
/*		if ($type == 'set_silver')
		{
			$tor_type = TOR_TYPE_SILVER;
		}
		elseif ($type == 'set_gold')
		{
			$tor_type = TOR_TYPE_GOLD;
		}
		else
		{
			$tor_type = 0;
		}
		change_tor_type($attach_id, $tor_type);
		$title = $lang['CHANGE_TOR_TYPE'];
		$url = make_url(TOPIC_URL . $torrent['topic_id']);*/
		if ($type == 'set_silver')
		{
            $plastdate  = (string) $this->request['plastdate'];
            $set_po_inp = (string) $this->request['set_po_inp'];
            $status     = (int) $this->request['status'];
			$tor_type = TOR_TYPE_SILVER;
		}
		elseif ($type == 'set_gold')
		{
            $plastdate  = (string) $this->request['plastdate'];
            $set_po_inp = (string) $this->request['set_po_inp'];
            $status     = (int) $this->request['status'];
			$tor_type = TOR_TYPE_GOLD;
		}
		else
		{
			$tor_type = 0;
		}
        $time = 0;
        if ($type == 'set_silver' OR $type=='set_gold')
        {
            if($status == 1 && isset($plastdate)) 
            { 
              switch($plastdate) 
              { 
                case "1h": 
                $time = time() + 60*60; 
                break; 
                case "2h": 
                $time = time() + 60*60*2; 
                break; 
                case "3h": 
                $time = time() + 60*60*3; 
                break; 
                case "5h": 
                $time = time() + 60*60*5; 
                break; 
                case "10h": 
                $time = time() + 60*60*10; 
                break; 
                case "1d": 
                $time = time() + 60*60*24; 
                break; 
                case "2d": 
                $time = time() + 60*60*24*2; 
                break; 
                case "3d": 
                $time = time() + 60*60*24*3; 
                break; 
                case "5d": 
                $time = time() + 60*60*24*5; 
                break; 
                case "10d": 
                $time = time() + 60*60*24*10; 
                break; 
                case "1m": 
                $time = time() + 60*60*24*30; 
                break; 
                case "2m": 
                $time = time() + 60*60*24*30*2; 
                break; 
                case "3m": 
                $time = time() + 60*60*24*30*3; 
                break; 
                case "1y": 
                $time = time() + 60*60*24*365; 
                break; 
                case "inf": 
                $time = -1; 
                break; 
                default: $time=0; 
              } 
            } 
            else if(isset($set_po_inp) && $status == 2) 
            { 
              $time_massiv = explode(" ", $set_po_inp); 
              if(!empty($time_massiv[0])) $time_tmp1 = explode(".", $time_massiv[0]); else $this->ajax_die("Ошибка при вводе даты (dmY)"); 
              if(!empty($time_massiv[1])) $time_tmp2 = explode(":", $time_massiv[1]); else $this->ajax_die("Ошибка при вводе даты (Hi)"); 
              if(!$time = mktime($time_tmp2[0], $time_tmp2[1], 0, $time_tmp1[1], $time_tmp1[0], $time_tmp1[2])) $this->ajax_die("Ошибка при вводе даты (mk)"); 
            }

            if(!$time) $this->ajax_die("Необходимо ввести дату до нажатия кнопки"); 
            if(time() > $time && $time != -1) $this->ajax_die("Введенная дата не может быть меньше текущей"); 
        }
		change_tor_type($attach_id, $tor_type, $time);
		$title = 'Тип торрента изменён';
		$url = make_url(TOPIC_URL . $torrent['topic_id']);
	break;

	case 'reg';
		tracker_register($attach_id);
		$url = (TOPIC_URL . $torrent['topic_id']);
	break;

	case 'unreg';
	    tracker_unregister($attach_id);
	    $url = (TOPIC_URL . $torrent['topic_id']);
	break;

	case 'del_torrent';
		if (empty($this->request['confirmed'])) $this->prompt_for_confirm($lang['DEL_TORRENT']);
	    delete_torrent($attach_id);
	    $url = make_url(TOPIC_URL . $torrent['topic_id']);
	break;

	case 'del_torrent_move_topic';
		if (empty($this->request['confirmed'])) $this->prompt_for_confirm($lang['DEL_MOVE_TORRENT']);
	    delete_torrent($attach_id);
		$url = make_url("modcp.php?t={$torrent['topic_id']}&mode=move&sid={$userdata['session_id']}");
	break;
}

$this->response['url']   = $url;
$this->response['title'] = $title;