<?php

if (!defined('IN_AJAX')) die(basename(__FILE__));

global $bb_cfg, $bb_cache;
  $a_id = (int) $this->request['a_id'];
  $a_type = '';
  $status = DB()->sql_query("SELECT ad_status
                           FROM   bb_ads
                           WHERE  ad_id = $a_id
                         ");
  while ($type = DB()->sql_fetchrow($status))
    {     
  if ($type['ad_status'] == 1)
     {
        DB()->query("UPDATE bb_ads SET ad_status = 0 WHERE ad_id = $a_id LIMIT 1");
        $a_type = '<img class="clickable" src="../images/icon_delete.gif" onclick="ajax.ads_status('.$a_id.'); return false" />';

 } else if ($type['ad_status'] == 0) {

        DB()->query("UPDATE bb_ads SET ad_status = 1 WHERE ad_id = $a_id LIMIT 1");
        $a_type = '<img class="clickable" src="../images/icon_run.gif" onclick="ajax.ads_status('.$a_id.'); return false" />';
      } else {
        ajax_die('Неизвестная ошибка');
      }
    }
 $this->response['id'] = (int) $a_id;
 $this->response['html'] = $a_type;