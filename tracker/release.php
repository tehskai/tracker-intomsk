<?php
#
# Release wizard for PHPBB Bittorent Tracker (TorrentPier)
# Copyright (c) by pservit
# Support forum: http://www.torrentpier.info/viewtopic.php?t=15
#

define('IN_PHPBB', TRUE);

include('common.php');

####
# include release-wizard config
$VERSION = '3.0 Alpha';
include('release_cfg.php');
####

if ( ! function_exists('array_map_recursive') ) {
	function array_map_recursive($function, $data) 
	{
		foreach ( $data as $i => $item ) {
		        $data[$i] = is_array($item) ? array_map_recursive($function, $item) : $function($item) ;
		}
	        return $data ;
	}
}

if ( get_magic_quotes_gpc() ) {
	$_GET = array_map_recursive('stripslashes', $_GET) ;
	$_POST = array_map_recursive('stripslashes', $_POST) ;
	$_COOKIE = array_map_recursive('stripslashes', $_COOKIE) ;
	$_REQUEST = array_map_recursive('stripslashes', $_REQUEST) ;
}



// Init userdata
$user->session_start();

$user_id = $userdata['user_id'];

$what = isset($_REQUEST['what']) ? $_REQUEST['what'] : '';
$fid = (isset($_REQUEST['fid'])) ? intval($_REQUEST['fid']) : '';
$step = (isset($_REQUEST['step'])) ? intval($_REQUEST['step']) : 0;

$page_title = "Новый релиз";

if($user_id > 0) 
{
	if($what == '')	{
	include('includes/page_header.php');
?>
	<b><h2><?echo $page_title;?></h2></b>
	<table width="100%" border="0" cellspacing="1" cellpadding="3" class="forumline">
	<tr>
		<th class="thHead" height="25" width="260px"><b>Выбирите раздел</b></th>
		<th class="thHead" height="25"><b>Информация</b></th>
	</tr>
	<tr bgcolor="white">
	<td class="row2">
	    <br/>
	    <ul style="font-size: 14px">
	    <?php
include('release_category.php');
/*
			foreach($config as $code => $cfg ) {
				
			}*/

foreach ($release as $key => $item)
{
	$rel = explode(",", $item);
	foreach ($rel as $item1 )
	{
		$parent = DB()->fetch_row("SELECT * FROM bb_forums WHERE forum_id=$item1");
		$name = DB()->fetch_row("SELECT * FROM bb_forums WHERE forum_id=$item1");
		if($parent['forum_parent'] == 0){
		print"<li class='release_entry parent1'><a href=?what=".($key)."&fid=".($item1).">".($name['forum_name'])."</a></li>";
		}else{
		print"<li class='release_entry'><a href=?what=".($key)."&fid=".($item1).">".($name['forum_name'])."</a></li>";
		}
	}
}

?>
	    </ul>
	</td>
	<td class="row2">
<style type="text/css">
.ws6 {font-size: 8px;}
.ws7 {font-size: 10px;}
.ws8 {font-size: 11px;}
.ws9 {font-size: 12px;}
.ws10 {font-size: 13px;}
.ws11 {font-size: 15px;}
.ws12 {font-size: 16px;}
.ws14 {font-size: 12px;}
.ws16 {font-size: 21px;}
.ws18 {font-size: 24px;}
.ws20 {font-size: 27px;}
.ws22 {font-size: 29px;}
.ws24 {font-size: 32px;}
.ws26 {font-size: 35px;}
.ws28 {font-size: 37px;}
.ws36 {font-size: 48px;}
.ws48 {font-size: 64px;}
.ws72 {font-size: 96px;}
.wpmd {font-size: 13px;font-family: 'Arial';font-style: normal;font-weight: normal;}
</style>

	<div class="wpmd">
<div align="center"><font class="ws14" color="#008080" face="Tahoma">Модуль Release-wizard предназначен облегчить жизнь пользователей и стандартизировать внешний вид раздач!</font></div>
<div align="center"><font class="ws14" color="#008080" face="Tahoma">Данный модуль будет очень полезен новичкам!</font></div>
<div><br></div>
<div align="center"><font color="#FF6600" face="Tahoma">Краткое FAQ по Release-wizard</font></div>
<div align="center"><font color="#FF6600" face="Tahoma"><br></font></div>
<div align="center"><font color="#FF6600" face="Tahoma">Способы оформления.</font></div>
<div align="center"><font color="#FF6600" face="Tahoma">Данная версия поддерживает 2 способа с помощю которых можно начать оформление</font></div>
<div align="center"><font color="#FF6600" face="Tahoma">a) Выбрать категорю в которой вы бы хотели оформить релиз в списке категорй слева.</font></div>
<div align="center"><font color="#FF6600" face="Tahoma">b) В каждом форуме для которого есть настроенный шаблон оформления появляется кнопка </font><font color="#FF0000" face="Tahoma"><b>"Новый релиз" </b></font><font color="#FF6600" face="Tahoma">которя находится рядом с кнопкой </font><font color="#FF0000" face="Tahoma"><b>"Новая тема"</b></font><font color="#FF6600" face="Tahoma">!</font></div>

<div align="center"><font color="#FF6600" face="Tahoma"><br></font></div>
<div align="center"><font class="ws14" color="#077E85" face="Tahoma">Модуль доработан и исправлен администраторами</font><font color="#FF6600"> </font><font class="ws14" color="#008000">Invincible</font><font class="ws14" color="#FF6600"> </font><font class="ws14" color="#008000">&amp; Tusken</font></div>
<div align="center"><font class="ws14" color="#008000"><br></font></div>
<div align="center"><font color="#808000"><b><i>Release-wizard v<?php echo($VERSION); ?></i></b></font></div>
</div>
	<!--
	<font color="green"><h3><center>Модуль Release-Wizard предназначен облегчить жизнь пользователей и стандартизировать внешний вид раздач!<br> Данный модуль будет очень полезен новичкам!</center></h3></font>
	<br>
	<br><font color="red"><h1><center>Модуль доработан и исправлен администраторами Invincible & Tusken<br>Release-wizard v.</center></h1></font>
	-->
	</td>
	</tr>
	</table>
	<br/>
<?
	include('includes/page_footer.php');
	} else {
		$cfg = $config[$what];
		
		if(!$cfg) {
			include('includes/page_header.php');
			message_die(GENERAL_ERROR, 'Can\'t get config for this release type', '', __LINE__, __FILE__);
			include('includes/page_footer.php');
		} else {
			$cfg['db'] = DB();
			$cfg['url'] = $url;
			$cfg['user_id'] = $user_id;
			
			$allow = 1;
			
			$gid = isset($cfg['only_for_group']) ? intval($cfg['only_for_group']) : 0;
			if( $gid && !IS_ADMIN && !IS_MOD ) {
				$gdata = DB()->fetch_row("SELECT user_id FROM " . USER_GROUP_TABLE . " WHERE user_id = $user_id AND group_id = $gid AND user_pending = 0");
				if( !isset($gdata['user_id']) ) $allow = 0;
			}
			
			if($allow) {
				if(!$step || !is_form_valid($cfg)) {
					include('includes/page_header.php');
					echo(get_release_form($cfg, $step));
					include('includes/page_footer.php');
				} else {
					echo(get_phpbb_code_form($cfg));
				}
			} else {
				echo("Hello, world!");
			}
		}
	}
} else {
	include('includes/page_header.php');
?>
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="3" class="forumline">
	<tr>
		<th class="thHead" height="25"><b>Ошибка</b></th>
	</tr>
	<tr>
		<td class="row1" align="center"><br /><font color="#CC3333">Для создание релиза необходимо войти на сайт под своим именем.</font><br /><br /></td>
	</tr>
	</table>
	<br />
<?
	include('includes/page_footer.php');
}


function get_release_form($cfg, $step)
{
	$forum_id = $cfg["forum_id"];
	$title = $cfg["title"];
	$fields = $cfg["fields"];
	$subject_after = $cfg["subject_after"];
	$subject_example = $cfg["subject_example"];
	$what = isset($_REQUEST['what']) ? $_REQUEST['what'] : '';

	$subject = isset($_REQUEST['subject']) ? $_REQUEST["subject"] : '';
	$subject = str_replace("\"", "\\\"", $subject);
	
	if( $subject == "" ) $subject = $subject_after;
	
	$res = $cfg['db']->sql_query("select forum_id, forum_name from " . BB_FORUMS . " as u where (forum_id=$forum_id OR forum_parent=$forum_id) AND allow_reg_tracker=1");
        if (!$res) {
	        message_die(GENERAL_ERROR, 'Could not get forums list', '', __LINE__, __FILE__);
	} else {
		$forums = @$cfg['db']->sql_fetchrowset($res);
	}
			
?>
	<style type="text/css">
	.rw_drop_btn, .rw_add_btn {
		width: 24px;
		font-weight: bold;
		color: white;
		background-color: #CC3333;
	}
	
	.rw_add_btn {
		background-color: #33CC33;
	}
	</style>

	<script language="JavaScript" src="release.js"></script>

	
	<form method="post" name="post" enctype="multipart/form-data">
	<input type="hidden" name="step" value="1">
	<input type="hidden" name="what" value="<? echo($what); ?>">
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="forumline">
<SPAN style="FONT-WEIGHT: bold">
<!--<A class=postlink href="viewtopic.php?t=13" target="blank">Как создать торрент файл и разместить его на сайте?</A>-->
<BR><BR>
	<table width="100%" border="0" cellspacing="1" cellpadding="3" class="forumline">
	<tr>
		<th class="thHead" colspan="2" height="25"><b><? echo($title); ?></b></th>
	</tr>
	<tr bgcolor="white">
		<td class="row1" align="right" width="20%"><b>Раздел:</b></td>
		<td class="row2">
			<?
			if( count($forums) == 1 ) {
					echo("<input type=\"hidden\" name=\"f\" value=\"".($forums[0]['forum_id'])."\" />");
					echo("<b>".($forums[0]['forum_name'])."</b>");
			} else {
				echo("<select name=\"f\">\n");
				for($i = 0; $i < count($forums); ++$i) {
					echo("<option value=\"".($forums[$i]['forum_id'])."\">".($forums[$i]['forum_name'])."</option>");
				}
				echo("</select>\n");
	    			echo("<font color=\"#CC3333\"><b>&laquo;-- выберите правильный подфорум !!!</b></font>\n");
			}
			?>
			</select>
		</td>
	</tr>
	<tr bgcolor="white">
		<td class="row1" align="right"><b>Название:</b></td>
		<td class="row2">
			<input type="text" name="subject" size="60" maxlength="120" value="" />
			<?
			if($subject_after != '') {
				echo("<small> $subject_after</small>");
			}
			if($subject_example != '') {
				echo("<br /><font color=\"gray\"><small>Пример: $subject_example</small></font>");
			}
			?>
		</td>
	</tr>
<?

	$in_group = false;
	$multiple_group = false;
	$html = '';
	$js = '';
	
	for($i = 0; $i < count($fields); ++$i ) {
		$name       = isset($fields[$i]["name"]) ? $fields[$i]["name"] : '';
		$type       = isset($fields[$i]["type"]) ? $fields[$i]["type"] : '';
		$multiple   = isset($fields[$i]["multiple"]) ? $fields[$i]["multiple"] : 0;
		$default    = isset($fields[$i]["default"]) ? $fields[$i]["default"] : '';
		$example    = isset($fields[$i]["example"]) ? $fields[$i]["example"] : '';
		$values     = isset($fields[$i]["values"]) ? $fields[$i]["values"] : '';
		$comment    = isset($fields[$i]["comment"]) ? $fields[$i]["comment"] : '';
		$value      = ( isset($_REQUEST["f$i"]) && $_REQUEST["f$i"] != '') ? $_REQUEST["f$i"] : $default;
 		$group      = isset($fields[$i]["group"]) ? ($fields[$i]["group"] ? 1 : -1) : '';
 		$size       = isset($fields[$i]["size"]) ? $fields[$i]["size"] : 60;
 		$columns    = isset($fields[$i]["columns"]) ? $fields[$i]["columns"] : 4;
		//$validate   = isset($fields[$i]["validate"]) ? $fields[$i]["validate"] : 0;
		
		$field_name = "f$i";

 		if( $group == 1 ) {
 			$html .= ("<tr bgcolor=\"white\">\n");
 			$html .= ("<td class=\"row1\" align=\"right\">");
			$html .= ("<b>$name:</b>&nbsp;");
			if($comment) $html .= ("<br /><small>".$comment."</small>");
			$html .= ("</td><td class=\"row2\" style=\"font-size: 11px;\">");
 			$html .= ("<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\"><tr>");
			
			for($j = $i + 1; $i < count($fields) && !isset($fields[$j]["group"]); ++$j ) {
				$html .= ("<td><small>");
				$html .= ($fields[$j]["name"]);
				$html .= ("</small></td>");

			}

			if( $multiple ) {
				$multiple_group = true;
				$html .= (" <td>&nbsp;</td>");
			} else {
				$multiple_group = false;
			}
			
			$html .= ("</tr><tr>");
			
 	        	$in_group = true;
 		} else if( $group == -1 ) {
			$key = 'f'.($i - 1);
 			$html .= ("</td>");
			
			if($multiple_group) {
				$html .= ('<td><input class="rw_add_btn" name="insert_'.$key.'" type="button" onclick="mlAddVal(this);" value="+" />&nbsp;<input class="rw_drop_btn" name="drop_'.$key.'" type="button" onclick="mlDropVal(this);" value="-" />');
				$html .= ("<script>ml_vals['$key'] = new Array(); mlCheckForLast('$key');</script>");
				$html .= "</td>";
			}
					
			$html .= "</tr></table>";
 			$html .= ("</td></tr>");
 	        	$in_group = false;
 		} else {
		    	if(!$in_group) {
 				$html .= ("<tr bgcolor=\"white\">");
	 			$html .= ("<td class=\"row1\" align=\"right\">");
		 		$html .= ("<b>$name:</b>&nbsp;");
				if($comment) $html .= ("<br /><small>".$comment."</small>");
				$html .= ("</td>\n\t<td class=\"row2\">\n\t\t");
 			} else {
 				$html .= ("<td>");
				$field_name .= '[]';
			}

			if( $type == "text" ) {
				$value = str_replace("\"", "\\\"", $value);
				$html .= ("<input type=\"text\" name=\"$field_name\" size=\"".($size)."\" value=\"$value\">");
			} if( $type == "variant" ) {
				$value = str_replace("\"", "\\\"", $value);
				$html .= ("<input type=\"text\" name=\"$field_name\" id=\"$field_name\" size=\"60\" value=\"$value\"> <input type=\"button\" onclick=\"showhide('v$field_name')\" value=\"показать/спрятать список\" />");
				if( $values != '' ) {
					$html .= ("<div id=\"v$field_name\" style=\"display: none\"><table class=\"row3\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">");
					for($j = 0; $j < count($values); ++$j ) {
						$v = $values[$j];
						if($j % $columns == 0) $html .= ("<tr>");
						$html .= ("<td width=\"".intval(100 / $columns)."%\"><input onchange=\"ch_var(this, $i)\" type=\"checkbox\" value=\"$v\" id=\"v$field_name-$j\"><label for=\"v$field_name-$j\">$v<label></td>");
						if($j % $columns == ($columns - 1)) $html .= ("</tr>\n");
					}
					$html .= ("</table></div>");
				}
			} else if( $type == "textarea" ) {
				$html .= ("<textarea rows=\"12\" style=\"width: 100%\" name=\"$field_name\">$value</textarea>");
			} else if( $type == "image" ) {
				$html .= ("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">");
		    		$key = $field_name;
				$n = $multiple ? $multiple : 1;
				for($j = 0; $j < $n; ++$j) {
					$html .= ("<tr><td><input type=\"file\" name=\"f".$i."[]\" size=\"70\" />");
					if( $multiple ) {
						$html .= ("</td><td>");
						$html .= ('<input class="rw_add_btn" name="insert_'.$key.'" type="button" onclick="mlAddVal(this);" value="+" />&nbsp;<input class="rw_drop_btn" name="drop_'.$key.'" type="button" onclick="mlDropVal(this);" value="-" />');
						$html .= ("<script>ml_vals['$key'] = new Array( new Array(0, 'input', '') ); mlCheckForLast('$key');</script>");
					}
					$html .= ("</td></tr>");
				}
				$html .= ("</table>");
			} else if( $type == "select" ) {
				$html .= ("<select name=\"$field_name\">");
				$html .= ("<option value=\"0\">--- Выберите ---</option>");
				for($j = 1; $j <= count($values); ++$j) {
					$html .= ("<option value=\"$j\"");
					if($j == $value) { $html .= (" selected=\"selected\""); }
					$html .= (">".$values[$j-1]."</option>");
				}
				$html .= ("</select>");
			}
			
			if($example && !$in_group) {
				$html .= ("<br /><font color=\"gray\"><small>Например, \"$example\"</small></font>");
			}
			
			if(!$in_group) {
				$html .= ("\n\t</td>\n");
				$html .= ("</tr>\n");
			} else {
	     			$html .= ("</td>");
	     		}
		}
	}
	
	echo($html);
?>
	<tr bgcolor="white">
		<td class="row1" align="right"><b>Торрент</b>:</td>
		<td class="row2">
			<input type="file" name="torrent" size="65"><br>
      Выберите торрент-файл, который будете использовать для релиза
		</td>
	</tr>
	<tr><td class="row2" colspan="2" align="center"><input type="submit" name="add_attachment" class="mainoption" value="Отправить"></td></tr>
	</table>
	
	</form>
	<br />
	
<?
}

function create_thumb ($dir, $name, $att)
{
	$infile = $dir . $name . $att;
	if ($att == ".jpg" || $att == ".jpeg")
		$im = imagecreatefromjpeg($infile);
	elseif ($att == ".png")
		$im = imagecreatefrompng($infile);
	elseif ($att == ".gif")
		$im = imagecreatefromgif($infile);

	$oh = imagesy($im);
	$ow = imagesx($im);
	$r = $oh/$ow;
	$newh = 200;
	$neww = $newh/$r;
	$outfile = $dir ."thumb_". $name . $att;
	$im1 = imagecreatetruecolor($neww,$newh);
	imagecopyresampled($im1, $im, 0, 0, 0, 0, $neww, $newh, imagesx($im), imagesy($im));
	imagejpeg($im1, $outfile, 75);
	imagedestroy($im);
	imagedestroy($im1);
}

function get_phpbb_code_field($config, $field_name, $value, $in_group, $number)
{
	global $cfg;

		$name         = isset($config["name"]) ? $config["name"] : '';
		$type         = isset($config["type"]) ? $config["type"] : '';
		$multiple     = isset($config["multiple"]) ? $config["multiple"] : '';
		$example      = isset($config["example"]) ? $config["example"] : '';
		$values       = isset($config["values"]) ? $config["values"] : '';
		$comment      = isset($config["comment"]) ? $config["comment"] : '';
 		$group        = isset($config["group"]) ? ($config["group"] ? 1 : -1) : '';
		
				
		$before_name  = isset($config["before_name"]) ? $config["before_name"] : 0;
		if( !$before_name ) {
			if($in_group == 1 && $group != 1) {
				if( isset($config["group_before_name"]) ) $before_name = $config["group_before_name"]; else $before_name = "";
			} else {
				$before_name = "[b]";
			}
		}
		$after_name   = isset($config["after_name"]) ? $config["after_name"] : 0;
		if( !$after_name ) {
			if($in_group == 1 && $group != 1) {
				if( isset($config["group_after_name"]) ) $after_name = $config["group_after_name"]; else $after_name = ": ";
			} else {
				$after_name = ": [/b]";
			}
		}
		
		$before_value = isset($config["before_value"]) ? $config["before_value"] : "";
		$after_value  = isset($config["after_value"]) ? $config["after_value"] : ($in_group ? ($group == -1 ? "\n" : "") : "\n");
		
		$right        = isset($config["right"]) ? $config["right"] : 0;
		$hide_name    = isset($config["hide_name"]) ? $config["hide_name"] : 0;
		$as_url       = isset($config["as_url"]) ? $config["as_url"] : 0;
		
		$bbcode = '';
		
		if( $group == 1 ) {
			if(!$hide_name) $bbcode .= ($before_name.($name).($number ? ' #'.$number : '').$after_name);
			$bbcode .= ($before_value);
		} else if( $group == -1 ) {
			$bbcode .= ($after_value);
		} else {
			if( ($type == "text" || $type == 'variant') && $value != '' ) {
				if(!$hide_name) $bbcode .= ($before_name.($name).$after_name);
				$bbcode .= ($before_value.$value.$after_value);
			} else if( $type == "textarea" && $value != '' ) {
				if(!$hide_name) $bbcode .= ($before_name.($name).$after_name);
				$bbcode .= ($before_value.$value.$after_value);
			} else if( $type == "select" && $value > 0 ) {
				if(!$hide_name) $bbcode .= ($before_name.($name).$after_name);
				$bbcode .= $before_value.$values[$value - 1].$after_value;
			} else if( $type == "image" ) {
				$img_bbcode = "";
		
				if(!$hide_name) $img_bbcode .= $before_name.($name).$after_name;
			
				$img_bbcode .= $before_value;
			
				$cnt = count($_FILES[$field_name]['name']);
				if(!$multiple && $cnt > 1) $cnt = 1;
				
				$has_images = 0;
			
				for($j = 0; $j < $cnt; ++$j) {
					if(is_uploaded_file($_FILES[$field_name]['tmp_name'][$j])) {
				    		$im_type = $_FILES[$field_name]['type'][$j];
					
						if($im_type == "image/gif") {
							$ext = "gif";
						} else if($im_type == "image/jpg" || $im_type == "image/jpeg" || $im_type == "image/pjpeg") {
							$ext = "jpg";
						} else if($im_type == "image/png") {
							$ext = "png";
						//torrent posting
						} else if($im_type == "application/x-bittorrent") {
							$ext = "torrent";
						}
		    			
						if($ext != "" && $ext != "torrent") {
				    			$imageInfo = getimagesize($_FILES[$field_name]['tmp_name'][$j]);
						    	$w = $imageInfo[0];
							$h = $imageInfo[1];
							
							$filename = md5(strftime("%y%m%d%H%M%S").$cfg["user_id"].'_'.$field_name.'_'.$j).".$ext";
							$fname = "photos/".$filename;
		
							if( is_callable('imagick_readimage') && $cfg['max_w'] && $cfg['max_h'] ) {
								if($w > $cfg['max_w'] || $h > $cfg['max_h']) {
							    		$v = $cfg['max_w'];
				    					$h = $cfg['max_h'];
								}
						
								$handle = imagick_readimage($_FILES[$field_name]['tmp_name'][$j]);
						                if ( !imagick_iserror( $handle ) ) {
		    		    		    			if ( imagick_resize( $handle, $w, $h, IMAGICK_FILTER_UNKNOWN, 0.8 ) ) {
										if ( !imagick_writeimage( $handle,  $fname ) ) {
					    						$fname = "";
		    						    		}
	    								}
								}
							} else {
								move_uploaded_file($_FILES[$field_name]['tmp_name'][$j], $fname);
							}
							
							DB()->sql_query("INSERT INTO ". PHOTOS_TABLE ." (user_id, filename, added, last_viewed) VALUES ('{$cfg["user_id"]}', '$filename', '".time()."', '".time()."')");
							
							//$id = mysql_insert_id();
							$id = DB()->sql_nextid();
							if($right){
							$img_bbcode .= ($as_url?'':'[img'.($right ? '=right' : '').']').($cfg['url']."photos/".$filename).($as_url?'':'[/img]')."\n\n";
							}else{
							$img_bbcode .= ($as_url?'':'[thumbnails]').($cfg['url']."photos/".$filename).($as_url?'':'[/thumbnails]')."";
							}
							
							//$img_bbcode .= ($as_url?'':'[img'.($right ? '=right' : '').']').($cfg['url']."photos/img_".$id.".$ext").($as_url?'':'[/img]')."\n\n";
							$has_images = 1;
						}
					}
				}
				
	    			$img_bbcode .= $after_value;
	
				if($has_images) $bbcode .= ($img_bbcode);
			}
		}

	return $bbcode;
}
/*
function create_thumb ($dir, $name, $att)
{
	$infile = $dir . $name . $att;
	if ($att == ".jpg" || $att == ".jpeg")
		$im = imagecreatefromjpeg($infile);
	elseif ($att == ".png")
		$im = imagecreatefrompng($infile);
	elseif ($att == ".gif")
		$im = imagecreatefromgif($infile);

	$oh = imagesy($im);
	$ow = imagesx($im);
	$r = $oh/$ow;
	$newh = 200;
	$neww = $newh/$r;
	$outfile = $dir ."thumb_". $name . $att;
	$im1 = imagecreatetruecolor($neww,$newh);
	imagecopyresampled($im1, $im, 0, 0, 0, 0, $neww, $newh, imagesx($im), imagesy($im));
	imagejpeg($im1, $outfile, 75);
	imagedestroy($im);
	imagedestroy($im1);
}
*/
function get_tor_html()
{
			$code = '';
			if($_FILES['torrent']['name']) 
			{
				$six = 'torrent';
				$has_torrent = 0;
				$name  = trim(stripslashes($_FILES[$six]['name']));
				$new_name =str_replace('php','fuck',strftime("%y%m%d%H%M%S").'_'.$name);
				$fsize = $_FILES[$six]["size"];
				$fname = "files/".$new_name;
				move_uploaded_file($_FILES[$six]['tmp_name'], $fname);
				$has_torrent = 1;
			
				$tor_html = 	'<input type="hidden" name="add_attachment_body" value="0" />
	<input type="hidden" name="posted_attachments_body" value="0" /><input type="hidden" name="attachment_list[]" value="'.$new_name.'" />
	<input type="hidden" name="filename_list[]" value="'.$name.'" /><input type="hidden" name="extension_list[]" value="torrent" />
	<input type="hidden" name="mimetype_list[]" value="application/x-bittorrent" />
	<input type="hidden" name="filesize_list[]" value="'.$fsize.'" />
	<input type="hidden" name="filetime_list[]" value="'.time().'" />';
	
				$code = ($tor_html);
			}
//		}
	
	return $code;
}
function get_phpbb_code_form($cfg)
{
	$forum_id = $cfg["forum_id"];
	$title = $cfg["title"];
	$fields = $cfg["fields"];

	$subject = isset($_REQUEST['subject']) ? $_REQUEST["subject"] : '';
	$subject = str_replace("\"", "&quot;", $subject);

	$f = isset($_REQUEST['f']) ? intval($_REQUEST["f"]) : 0;
	
	$before_value = isset($cfg["before_value"]) ? $cfg["before_value"] : "[font=\"Tahoma\"][size=20][b]";
	$after_value  = isset($cfg["after_value"])  ? $cfg["after_value"] : "[/b][/size][/font]\n\n";
	
?>
	<div style="height: 1px; overflow: hidden;">
	<form action="posting.php" method="post" name="post">
	<input type="hidden" name="mode" value="newtopic" />
	<input type="hidden" name="f" value="<? echo($f); ?>" />
	<input type="hidden" name="preview" value="Предв. просмотр" />

	<? echo get_tor_html();	?>


	<table width="100%" border="0" cellspacing="1" cellpadding="3" class="forumline">
	<tr>
		<th class="thHead" colspan="2" height="25"><b><? echo($title); ?></b></th>
	</tr>
	<tr bgcolor="white">
		<td class="row1" align="right">Тема:</td>
		<td class="row2"><input type="text" name="subject" size="60" maxlength="120" value="<? echo($subject); ?>" /></td>
	</tr>

	<tr bgcolor="white">
	<td class="row1" align="right">Сообщение:</td><td class="row2"><textarea name="message" cols="80" rows="15">
	<? echo($before_value.$subject.$after_value); ?>
<?
	$in_group = false;
	$multiple_group = false;
	$bbcode = "";

	for($i = 0; $i < count($fields); ++$i) {
		$field_name = "f$i";
		$config = $fields[$i];
		
 		$group    = isset($config["group"]) ? ($config["group"] ? 1 : -1) : '';
		$multiple = isset($config["multiple"]) ? $config["multiple"] : '';
		$default  = isset($config["default"]) ? $config["default"] : '';
		$fields_separator  = isset($config["fields_separator"]) ? $config["fields_separator"] : ', ';

 		if( $group == 1 ) {
			$cnt = 1;
		
			if( $multiple ) {
				$multiple_group = true;
				$cnt = count( $_REQUEST[ 'f'.($i + 1) ] );
			} else {
				$multiple_group = false;
			}
			
			for($k = 0; $k < $cnt; ++$k) {
				$config     = $fields[$i];
				
				$bbcode .= get_phpbb_code_field($config, $field_name, $value, 1, ($cnt > 1 ? ($k + 1) : 0));
				
				for($j = $i + 1; $j < count($fields) && !isset($fields[$j]['group']); ++$j) {
					if( $j != $i + 1 ) $bbcode .= $fields_separator;
					$config     = $fields[$j];
			    		$field_name = "f$j";
					$value      = ( isset($_REQUEST[$field_name][$k]) && $_REQUEST[$field_name][$k] != '') ? $_REQUEST[$field_name][$k] : '';
					$bbcode     .= get_phpbb_code_field($config, $field_name, $value, 1, 0);
				}
				if($k != $cnt - 1) $bbcode .= "\n";
			}
			
			$i = $j-1;
			
 	        	$in_group = true;
 		} else if( $group == -1 ) {
			$bbcode .= get_phpbb_code_field($config, $field_name, $value, 1, 0);
 	        	$in_group = false;
 		} else {
			$value   = ( isset($_REQUEST[$field_name]) && $_REQUEST[$field_name] != '') ? $_REQUEST[$field_name] : $default;
			$bbcode .= get_phpbb_code_field($config, $field_name, $value, 0, 0);
		}
	}
	
	echo($bbcode);

?>
	</textarea>
	</td></tr>
	<tr><td class="row2" colspan="2" align="center">
	</td></tr>
	</table>
	</td></tr></table>
	</form>
	</div>
	<?php echo get_tor_html()?>
	<script>
	document.forms['post'].submit();
	</script>
	<br />
	
<?
}

function is_form_valid($cfg)
{
	return 1;
}

?>