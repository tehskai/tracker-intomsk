<?php
 /***************************************************************************
  *                               redirect.php
  *                            -------------------
  *   begin                :  Feb, 2003
  *   author               : Niels Chr. Denmark <ncr@db9.dk> (http://mods.db9.dk)
  *
  * version 1.0.0.
  *
  * History:
  *   0.9.0. - initial BETA
  *   0.9.1. - header added
  *   0.9.2. - added language support
  *   0.9.3. - corrected banner_id
  *   0.9.4. - added banner location to who-is online, if "topic in who-is-online MOD" is installed
  *   1.0.0. - changed cookie store procedure
  *
  ***************************************************************************/

 /***************************************************************************
  *
  *   This program is free software; you can redistribute it and/or modify
  *   it under the terms of the GNU General Public License as published by
  *   the Free Software Foundation; either version 2 of the License, or
  *   (at your option) any later version.
  *
  ***************************************************************************/

   
 define('IN_PHPBB', true);
 include(BB_ROOT . 'common.php');

 // Start session management
 $user->session_start();

 $banner_id = ( isset($_POST['banner_id']) ) ? intval ($_POST['banner_id']) :
 ( isset($_GET['banner_id']) ) ? intval ($_GET['banner_id']) : '';

 $banner_id = ( isset($_POST['banner_id']) ) ? intval ($_POST['banner_id']) :
 ( isset($_GET['banner_id']) ) ? intval ($_GET['banner_id']) : '';
 if ( !isset($banner_id ))
 {
   message_die(GENERAL_ERROR, "No banner id specified", "", __LINE__, __FILE__,"banner_id='".$banner_id."'");
 }
 $sql ="select * FROM ".BANNERS_TABLE." WHERE banner_id='".$banner_id."'";
 if ( !($result = DB()->sql_query($sql)) )
 {
   message_die(GENERAL_ERROR, "Couldn't retrieve banner data", "", __LINE__, __FILE__, $sql);
 }
 $banner_data = DB()->sql_fetchrow($result);
 $redirect_url = $banner_data['banner_url'];
 $cookie_name = $bb_cfg['cookie_name'] . '_b_' . $banner_id;
 if (!isset($_COOKIE[$cookie_name]))
 {
   $banner_filter_time = time() + (( $banner_data['banner_filter_time'] ) ? $banner_data['banner_filter_time'] : 600 ) ;
   setcookie($cookie_name , 1 ,$banner_filter_time , $bb_cfg['cookie_path'], $bb_cfg['cookie_domain'], $bb_cfg['cookie_secure']);
   $sql ="UPDATE ".BANNERS_TABLE." SET banner_click=banner_click+1 WHERE banner_id='".$banner_id."'";
   if ( !($result = DB()->sql_query($sql)) )
   {
     message_die(GENERAL_ERROR, "Couldn't update banner data", "", __LINE__, __FILE__, $sql);
   }
 }
 @$sql ="INSERT INTO ".BANNER_STATS_TABLE." (banner_id,click_date,click_ip,click_user,user_duration) VALUES ('".$banner_id."', '".time()."', '".$userdata['session_ip']."', '".$userdata['user_id']."', '".($userdata['session_time']-$userdata['session_start']+$bb_cfg['session_length'])."')";
 if ( !($result = DB()->sql_query($sql)) )
 {
   message_die(GENERAL_ERROR, "Couldn't insert banner stats", "", __LINE__, __FILE__, $sql);
 }

 require_once(BB_ROOT . 'language/lang_' . $bb_cfg['default_lang'] . '/lang_banner.php');

 $template->assign_vars(array(
       'REDIRECT_URL' => $redirect_url,
       'MESSAGE' => sprintf($lang['No_redirect_error'],$redirect_url)
 ));

 print_page('redirect.tpl');

 ?>