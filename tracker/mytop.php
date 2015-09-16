<?php

define('BB_SCRIPT', 'mytop');
require('./common.php');
global $lang;

$page_cfg['load_tpl_vars'] = array(
	'topic_icons',
);

$user->session_start(array('req_login' => $bb_cfg['disable_search_for_guest']));

$result=0;
$top_limit = 30; //кол-во пользователей в топе
$ttl = 1800; //время хранения данных в кеше (сек)

$css_style = "<style type=\"text/css\">
.nonborder {
border-left:0 none !important; border-right:0 none !important;
}
.nonborderd {
border-left:0 none !important; border-right:0 none !important; border-bottom:0 none !important;
}
       
.krugl {
        background:#FFF !important;

 opacity: 1;filter:alpha(opacity=100);zoom:1;

         -webkit-border-top-left-radius:10px;
         -webkit-border-top-right-radius:0px;
         -webkit-border-bottom-left-radius:10px;
         -webkit-border-bottom-right-radius:0px;

         -khtml-border-radius-topleft:10px;
         -khtml-border-radius-topright:0px;
         -khtml-border-radius-bottomleft:10px;
         -khtml-border-radius-bottomright:0px;

         -moz-border-radius-topleft:10px;
         -moz-border-radius-topright:0px;
         -moz-border-radius-bottomleft:10px;
         -moz-border-radius-bottomright:0px;
}
.krugc {
        background:#FFF !important;

 opacity: 1;filter:alpha(opacity=100);zoom:1;


}
.krugr {
        background:#FFF !important;

 opacity: 1;filter:alpha(opacity=100);zoom:1;

         -webkit-border-top-left-radius:0px;
         -webkit-border-top-right-radius:10px;
         -webkit-border-bottom-left-radius:0px;
         -webkit-border-bottom-right-radius:10px;

         -khtml-border-radius-topleft:0px;
         -khtml-border-radius-topright:10px;
         -khtml-border-radius-bottomleft:0px;
         -khtml-border-radius-bottomright:10px;

         -moz-border-radius-topleft:0px;
         -moz-border-radius-topright:10px;
         -moz-border-radius-bottomleft:0px;
         -moz-border-radius-bottomright:10px;
}

.kruglt {
        background:#FFF !important;

 opacity: 1;filter:alpha(opacity=100);zoom:1;

         -webkit-border-top-left-radius:0px;
         -webkit-border-top-right-radius:0px;
         -webkit-border-bottom-left-radius:10px;
         -webkit-border-bottom-right-radius:0px;

         -khtml-border-radius-topleft:0px;
         -khtml-border-radius-topright:0px;
         -khtml-border-radius-bottomleft:10px;
         -khtml-border-radius-bottomright:0px;

         -moz-border-radius-topleft:0px;
         -moz-border-radius-topright:0px;
         -moz-border-radius-bottomleft:10px;
         -moz-border-radius-bottomright:0px;
}
.krugct {
        background:#FFF !important;

 opacity: 1;filter:alpha(opacity=100);zoom:1;


}
.krugrt {
        background:#FFF !important;

 opacity: 1;filter:alpha(opacity=100);zoom:1;

         -webkit-border-top-left-radius:0px;
         -webkit-border-top-right-radius:0px;
         -webkit-border-bottom-left-radius:0px;
         -webkit-border-bottom-right-radius:10px;

         -khtml-border-radius-topleft:0px;
         -khtml-border-radius-topright:0px;
         -khtml-border-radius-bottomleft:0px;
         -khtml-border-radius-bottomright:10px;

         -moz-border-radius-topleft:0px;
         -moz-border-radius-topright:0px;
         -moz-border-radius-bottomleft:0px;
         -moz-border-radius-bottomright:10px;
}

.tzagol {

         color:#fff;
         background:#000;
         padding:5px 5px 5px 5px;
         margin:0 0 0 0px;

         opacity: 0.8;filter:alpha(opacity=80);zoom:1;

         -webkit-border-top-left-radius:10px;
         -webkit-border-top-right-radius:10px;
         -webkit-border-bottom-left-radius:0px;
         -webkit-border-bottom-right-radius:0px;

         -khtml-border-radius-topleft:10px;
         -khtml-border-radius-topright:10px;
         -khtml-border-radius-bottomleft:0px;
         -khtml-border-radius-bottomright:0px;

         -moz-border-radius-topleft:10px;
         -moz-border-radius-topright:10px;
         -moz-border-radius-bottomleft:0px;
         -moz-border-radius-bottomright:0px;

}
</style>";


function print_block($main_index,$main_text)
{
    global $i,$top_limit,$row,$result,$thtop;
    
    $i=$i+1;
    if($i==$top_limit) $class = "nonborderd";
    else $class = "nonborder";
    $avatar = get_avatar($row['user_avatar'],$row['user_avatar_type'],!bf($row['user_opt'], 'user_opt', 'allow_avatar'));
    if($row['u_down_total']>=MIN_DL_FOR_RATIO) $reit=round(($row['u_up_total']+$row['u_up_release']+$row['u_up_bonus'])/$row['u_down_total'],2);
    else $reit="Нет";
    $upload=humn_size($row[$main_index]);
    $result .= "<tr height=130>
    <td align=center class=krugl$thtop valign=top><h1>".$i."</h1></td>
    <td align=center class=krugc$thtop>$avatar</td>
    <td valign=top class=krugr$thtop align=left><center><b>".profile_url($row)."</b></center><br>
    <i>".$main_text.":</i> <font color=green><b>".$upload."</b></font><br>
    <i>Рейтинг:</i> <font color=green><b>".$reit."</b></font><br>
    <i>Сообщений:</i> <font color=green><b>".$row['user_posts']."</b></font>
    </td></tr>";
    $thtop= '';
}

function print_block2()
{
    global $i, $top_limit, $row, $result, $thtop;
    
    $i=$i+1;
    if($i==$top_limit) $class = "nonborderd";
    else $class = "nonborder";
    $avatar = get_avatar($row['user_avatar'],$row['user_avatar_type'],!bf($row['user_opt'], 'user_opt', 'allow_avatar'));
    if($row['u_down_total']>=MIN_DL_FOR_RATIO) $reit=round(($row['u_up_total']+$row['u_up_release']+$row['u_up_bonus'])/$row['u_down_total'],2);
    else $reit="Нет";
    $osize=humn_size($row['SUM(tor.size)']);
    $result .= "<tr height=130>
    <td align=center class=krugl$thtop valign=top><h1>".$i."</h1></td>
    <td align=center class=krugc$thtop>$avatar</td>
    <td valign=top class=krugr$thtop align=left><center><b>".profile_url($row)."</b></center><br>
    <i>Релизов:</i> <font color=green><a href=tracker.php?rid=".$row['poster_id']."#results class=seed><b>".$row['COUNT(tor.poster_id)']."</b></a></font><br>
    <i>Объемом:</i> <font color=green><b>".$osize."</font></b><br>
    <i>Скачаны раз:</i> <font color=green><b>".$row['SUM(tor.complete_count)']."</b></font><br>
    <i>Рейтинг:</i> <font color=green><b>".$reit."</b></font><br>
    <i>Сообщений:</i> <font color=green><b>".$row['user_posts']."</b></font>
    </td></tr>";
    $thtop= '';
}

if(isset($_GET['target']))$target=(int) $_GET['target'];
else $target=0;
switch($target) 
{
    case 2:
	if (!$result = CACHE('bb_cache')->get('mytop2'))
	{
    $result=$css_style;
    $result .= "
    <h2><a href=mytop.php>Перейти к спискам: TopReleasers, MegaReleasers, BestReleasers</a></h2>
    <table widh=100% style=\"border:0 none !important;\"><tr style=\"border:0 none !important;\"><td style=\"border:0 none !important;\" align=center valign=top>";

    //Начало колонки
    $result .= "<div class=tzagol><h1>TopSeeder</h1></div><table width=100%  cellspacing=0 cellpadding=0  style=\"border:0 none !important;\">";

    $sql = "SELECT u.username, u.user_avatar, u.user_avatar_type, u.user_opt, u.user_rank, u.user_level, ut.u_up_total, ut.u_down_total, ut.u_up_release, ut.u_up_bonus, u.user_posts, u.user_id
    FROM bb_bt_users ut LEFT JOIN bb_users u ON(u.user_id = ut.user_id)
    WHERE u.user_active >0 
    ORDER BY ut.u_up_total DESC
    LIMIT 0 , ".$top_limit;

    $thtop="t";
    if ( !($zapros = DB()->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
    }
    $i=0;
    while($row = DB()->sql_fetchrow($zapros) )
    {
        print_block('u_up_total','Отдано');
    }

    $result .= "
    </table>";


    DB()->sql_freeresult($zapros);
    $avatar=FALSE;
    $row=FALSE;
    $list=FALSE;
    $i=FALSE;


    //Конец колонки


    $result .= "</td><td style=\"border:0 none !important;\" align=center valign=top>";



    //Начало колонки


    $result .= "<div class=tzagol><h1>TopOwner</h1></div><table width=100%  cellspacing=0 cellpadding=0  style=\"border:0 none !important;\">";

    $sql = "SELECT u.username, u.user_avatar, u.user_avatar_type, u.user_opt, u.user_rank, u.user_level, ut.u_up_total, ut.u_down_total, ut.u_up_release, ut.u_up_bonus, u.user_posts, u.user_id
    FROM bb_bt_users ut LEFT JOIN bb_users u ON(u.user_id = ut.user_id)
    WHERE u.user_active >0 
    ORDER BY ut.u_up_release DESC
    LIMIT 0 , ".$top_limit;


    $thtop="t";
    if ( !($zapros = DB()->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
    }
    $i=0;
    while($row = DB()->sql_fetchrow($zapros) )
    {
        print_block('u_up_release','На своих');
    }

    $result .= "
    </table>";


    DB()->sql_freeresult($zapros);
    $avatar=FALSE;
    $row=FALSE;
    $list=FALSE;
    $i=FALSE;


    //Конец колонки

    $result .= "</td><td style=\"border:0 none !important;\" align=center valign=top>";

    //Начало колонки

    $result .= "<div class=tzagol><h1>TopKeeper</h1></div><table width=100%  cellspacing=0 cellpadding=0  style=\"border:0 none !important;\">";

    $sql = "SELECT u.username, u.user_avatar, u.user_avatar_type, u.user_opt, u.user_rank, u.user_level, ut.u_up_total, ut.u_down_total, ut.u_up_release, ut.u_up_bonus, u.user_posts, u.user_id
    FROM bb_bt_users ut LEFT JOIN bb_users u ON(u.user_id = ut.user_id)
    WHERE u.user_active >0 
    ORDER BY ut.u_up_bonus DESC
    LIMIT 0 , ".$top_limit;


    $thtop="t";
    if ( !($zapros = DB()->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
    }
    $i=0;
    while($row = DB()->sql_fetchrow($zapros) )
    {
        print_block('u_up_bonus','Бонус');
    }

    $result .= "
    </table>";

    DB()->sql_freeresult($zapros);
    $avatar=FALSE;
    $row=FALSE;
    $list=FALSE;
    $i=FALSE;


    //Конец колонки


    $result .= "</td><td style=\"border:0 none !important;\" align=center valign=top>";


    //Начало колонки

    $result .= "<div class=tzagol><h1>Top Leecher</h1></div><table width=100%  cellspacing=0 cellpadding=0  style=\"border:0 none !important;\">";

    $sql = "SELECT u.username, u.user_avatar, u.user_avatar_type, u.user_opt, u.user_rank, u.user_level, ut.u_up_total, ut.u_down_total, ut.u_up_release, ut.u_up_bonus, u.user_posts, u.user_id
    FROM bb_bt_users ut LEFT JOIN bb_users u ON(u.user_id = ut.user_id)
    WHERE u.user_active >0 
    ORDER BY ut.u_down_total DESC
    LIMIT 0 , ".$top_limit;

    $thtop="t";
    if ( !($zapros = DB()->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
    }
    $i=0;
    while($row = DB()->sql_fetchrow($zapros) )
    {
        print_block('u_down_total','Скачал');
    }

    $result .= "
    </table>";

    DB()->sql_freeresult($zapros);
    $avatar=FALSE;
    $row=FALSE;
    $list=FALSE;
    $i=FALSE;


    //Конец колонки

    $result .= "</td>";

    //Завершение общей таблицы
    $result .= "</tr></table>";

    CACHE('bb_cache')->set('mytop2', $result, $ttl);
    }
    break;

    default:
    if (!$result = CACHE('bb_cache')->get('mytop'))
	{
    $result=$css_style;
    $result .= "
<h2><a href=mytop.php?target=2>Перейти к спискам: TopSeed, TopOwner, TopKeeper, TopLeecher</a></h2>
<table widh=100% style=\"border:0 none !important;\"><tr style=\"border:0 none !important;\"><td style=\"border:0 none !important;\" align=center valign=top>";

//Начало колонки

    $result .= "<div class=tzagol><h1>TopReleasers</h1></div><table width=100%  cellspacing=0 cellpadding=0  style=\"border:0 none !important;\">";

    $sql = "SELECT COUNT(tor.poster_id), SUM(tor.size), tor.poster_id, u.username, u.user_avatar, u.user_avatar_type, u.user_opt, u.user_rank, u.user_level, ut.u_up_total, ut.u_down_total, ut.u_up_release, ut.u_up_bonus, u.user_posts, SUM(tor.complete_count)
FROM bb_bt_torrents tor LEFT JOIN bb_users u ON(u.user_id = tor.poster_id) LEFT JOIN bb_bt_users ut ON(ut.user_id = tor.poster_id)
WHERE u.user_active >0 
GROUP BY tor.poster_id 
 ORDER BY COUNT(tor.poster_id) DESC
LIMIT 0 , ".$top_limit;


    $thtop="t";
    if ( !($zapros = DB()->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
    }
    $i=0;
	while($row = DB()->sql_fetchrow($zapros) )
	{
        print_block2();
    }

    $result .= "
    </table>";


    DB()->sql_freeresult($zapros);
    $avatar=FALSE;
    $row=FALSE;
    $list=FALSE;
    $i=FALSE;


    //Конец колонки


    $result .= "</td><td style=\"border:0 none !important;\" align=center valign=top>";


    //Начало колонки

    $result .= "<div class=tzagol><h1>MegaReleasers</h1></div><table width=100%  cellspacing=0 cellpadding=0  style=\"border:0 none !important;\">";

    $sql = "SELECT COUNT(tor.poster_id), SUM(tor.size), tor.poster_id, u.username, u.user_avatar, u.user_avatar_type, u.user_opt, u.user_rank, u.user_level, ut.u_up_total, ut.u_down_total, ut.u_up_release, ut.u_up_bonus, u.user_posts, SUM(tor.complete_count)
FROM bb_bt_torrents tor LEFT JOIN bb_users u ON(u.user_id = tor.poster_id) LEFT JOIN bb_bt_users ut ON(ut.user_id = tor.poster_id)
WHERE u.user_active >0 
GROUP BY tor.poster_id 
 ORDER BY SUM(tor.size) DESC
LIMIT 0 , ".$top_limit;


    $thtop="t";
    if ( !($zapros = DB()->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
    }
    $i=0;
	while($row = DB()->sql_fetchrow($zapros) )
	{
        print_block2();
    }

    $result .= "
    </table>";

    DB()->sql_freeresult($zapros);
    $avatar=FALSE;
    $row=FALSE;
    $list=FALSE;
    $i=FALSE;

    //Конец колонки

    $result .= "</td><td style=\"border:0 none !important;\" align=center valign=top>";

    //Начало колонки

    $result .= "<div class=tzagol><h1>BestReleasers</h1></div><table width=100%  cellspacing=0 cellpadding=0  style=\"border:0 none !important;\">";

    $sql = "SELECT COUNT(tor.poster_id), SUM(tor.size), tor.poster_id, u.username, u.user_avatar, u.user_avatar_type, u.user_opt, u.user_rank, u.user_level, ut.u_up_total, ut.u_down_total, ut.u_up_release, ut.u_up_bonus, u.user_posts, SUM(tor.complete_count)
FROM bb_bt_torrents tor LEFT JOIN bb_users u ON(u.user_id = tor.poster_id) LEFT JOIN bb_bt_users ut ON(ut.user_id = tor.poster_id)
WHERE u.user_active >0 
GROUP BY tor.poster_id 
 ORDER BY SUM(tor.complete_count) DESC
LIMIT 0 , ".$top_limit;

    $thtop="t";
    if ( !($zapros = DB()->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
    }
    $i=0;
	while($row = DB()->sql_fetchrow($zapros) )
	{
        print_block2();
    }

    $result .= "
    </table>";


    DB()->sql_freeresult($zapros);
    $avatar=FALSE;
    $row=FALSE;
    $list=FALSE;
    $i=FALSE;

    //Конец колонки

    //Завершение общей таблицы
    $result .= "</tr></table>";

    CACHE('bb_cache')->set('mytop', $result, $ttl);
    
    break;
    }
}

$l_title = "Рейтинг пользователей";

$template->assign_vars(array(
	'PAGE_TITLE' => $l_title,
	'L_MYMES_TITLE' => $l_title,
	'MES_TEXT' => $result,

));

print_page('mytop.tpl');
?>