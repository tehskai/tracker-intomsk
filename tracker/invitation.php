<?php
/***************************************************************************
 *                                  invitation.php
 *                            -------------------
 *   begin                : Sunday, Jul 8, 2001
 *   copyright            : (C) 2010 Hardkor
 *   email                : admin@intomsk.ru
 *
 *   $Id: faq.php,v 1.14.2.2 2004/07/11 16:46:15 acydburn Exp $
 *
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
define('BB_ROOT', './');
require(BB_ROOT .'common.php');
// Start session management
$user->session_start();

$l_title = 'Получения инвайта';

//include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/' . $lang_file . '.' . $phpEx);
//include("{$phpbb_root_path}language/lang_{$board_config['default_lang']}/lang_faq_attach.$phpEx");

//
// Lets build a page ...
//

$template->assign_vars(array(
	'PAGE_TITLE' => $l_title,
	'L_DONATE_TITLE' => $l_title,
));
print_page('invitation.tpl');