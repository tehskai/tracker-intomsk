<!-- BEGIN attach -->
<div class="clear"></div>
<div class="spacer_8"></div>

<!-- BEGIN denyrow -->
<fieldset class="attach">
<legend>{ATTACHMENT_ICON} {L_ATTACHMENT}</legend>
	<p class="attach_link denied">{postrow.attach.denyrow.L_DENIED}</p>
</fieldset>

<div class="spacer_12"></div>
<!-- END denyrow -->

<!-- BEGIN cat_stream -->
<div><img src="{SPACER}" alt="" width="1" height="6" /></div>
<table width="95%" border="1" class="attachtable" align="center">
<tr>
	<td width="100%" colspan="2" class="attachheader" align="center"><b><span class="gen">{postrow.attach.cat_stream.DOWNLOAD_NAME}</span></b></td>
</tr>
<tr>
	<td width="15%" class="attachrow"><span class="med">&nbsp;{L_DESCRIPTION}:</span></td>
	<td width="75%" class="attachrow">
	<table width="100%" cellspacing="4">
		<tr>
			<td class="attachrow"><span class="med">{postrow.attach.cat_stream.COMMENT}</span></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td width="15%" class="attachrow"><span class="med">&nbsp;{L_FILESIZE}:</span></td>
	<td width="75%" class="attachrow"><span class="med">&nbsp;{postrow.attach.cat_stream.FILESIZE} {postrow.attach.cat_stream.SIZE_VAR}</td>
</tr>
<tr>
	<td width="15%" class="attachrow"><span class="med">&nbsp;{L_VIEWED}:</span></td>
	<td width="75%" class="attachrow"><span class="med">&nbsp;{postrow.attach.cat_stream.DOWNLOAD_COUNT}</span></td>
</tr>
<tr>
	<td colspan="2" align="center"><br />
	<object id="wmp" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,0,0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">
	<param name="FileName" value="{postrow.attach.cat_stream.U_DOWNLOAD_LINK}">
							<param name="ShowControls" value="1">
	<param name="ShowDisplay" value="0">
	<param name="ShowStatusBar" value="1">
	<param name="AutoSize" value="1">
	<param name="AutoStart" value="0">
	<param name="Visible" value="1">
	<param name="AnimationStart" value="0">
	<param name="Loop" value="0">
	<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/windows95/downloads/contents/wurecommended/s_wufeatured/mediaplayer/default.asp" src="{postrow.attach.cat_stream.U_DOWNLOAD_LINK}" name=MediaPlayer2 showcontrols=1 showdisplay=0 showstatusbar=1 autosize=1 autostart=0 visible=1 animationatstart=0 loop=0></embed>
	</object> <br /><br />
	</td>
</tr>
</table>
<div><img src="{SPACER}" alt="" width="1" height="6" /></div>
<!-- END cat_stream -->

<!-- BEGIN cat_swf -->
<div><img src="{SPACER}" alt="" width="1" height="6" /></div>
<table width="95%" border="1" class="attachtable" align="center">
<tr>
	<td width="100%" colspan="2" class="attachheader" align="center"><b><span class="gen">{postrow.attach.cat_swf.DOWNLOAD_NAME}</span></b></td>
</tr>
<tr>
	<td width="15%" class="attachrow"><span class="med">&nbsp;{L_DESCRIPTION}:</span></td>
	<td width="75%" class="attachrow">
	<table width="100%" cellspacing="4">
		<tr>
			<td class="attachrow"><span class="med">{postrow.attach.cat_swf.COMMENT}</span></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td width="15%" class="attachrow"><span class="med">&nbsp;{L_FILESIZE}:</span></td>
	<td width="75%" class="attachrow"><span class="med">&nbsp;{postrow.attach.cat_swf.FILESIZE} {postrow.attach.cat_swf.SIZE_VAR}</td>
</tr>
<tr>
	<td width="15%" class="attachrow"><span class="med">&nbsp;{L_VIEWED}:</span></td>
	<td width="75%" class="attachrow"><span class="med">&nbsp;{postrow.attach.cat_swf.DOWNLOAD_COUNT}</span></td>
</tr>
<tr>
	<td colspan="2" align="center"><br />
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="{postrow.attach.cat_swf.WIDTH}" height="{postrow.attach.cat_swf.HEIGHT}">
	<param name=movie value="{postrow.attach.cat_swf.U_DOWNLOAD_LINK}">
	<param name=loop value=true>
	<param name=quality value=high>
	<param name=scale value=noborder>
	<param name=wmode value=transparent>
	<param name=bgcolor value=#000000>
	<embed src="{postrow.attach.cat_swf.U_DOWNLOAD_LINK}" loop=true quality=high scale=noborder wmode=transparent bgcolor=#000000  width="{postrow.attach.cat_swf.WIDTH}" height="{postrow.attach.cat_swf.HEIGHT}" type="application/x-shockwave-flash" pluginspace="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
	</object><br /><br />
	</td>
</tr>
</table>
<div><img src="{SPACER}" alt="" width="1" height="6" /></div>
<!-- END cat_swf -->

<!-- BEGIN cat_images -->
<fieldset class="attach">
<legend>{ATTACHMENT_ICON} {L_ATTACHMENT} ({postrow.attach.cat_images.FILESIZE} {postrow.attach.cat_images.SIZE_VAR})</legend>
	<p class="tCenter pad_6">
		<img src="{postrow.attach.cat_images.IMG_SRC}" id="attachImg" class="postImg" alt="img" border="0" />
	</p>
	<!-- IF postrow.attach.cat_images.COMMENT -->
	<p class="tCenter med lh_110">
		{postrow.attach.cat_images.COMMENT}
	</p>
	<!-- ENDIF -->
</fieldset>

<div class="spacer_12"></div>
<!-- END cat_images -->

<!-- BEGIN cat_thumb_images -->
<fieldset class="attach">
<legend>{ATTACHMENT_ICON} {L_ATTACHMENT_THUMBNAIL}</legend>
	<p class="attach_link">
		<a href="{postrow.attach.cat_thumb_images.IMG_SRC}" target="_blank"><img src="{postrow.attach.cat_thumb_images.IMG_THUMB_SRC}" alt="{postrow.attach.cat_thumb_images.DOWNLOAD_NAME}" border="0" /></a>
	</p>
	<p class="attach_link">
		<a href="{postrow.attach.cat_thumb_images.IMG_SRC}" target="_blank"><b>{postrow.attach.cat_thumb_images.DOWNLOAD_NAME}</b></a>
		<span class="attach_stats med">({postrow.attach.cat_thumb_images.FILESIZE} {postrow.attach.cat_thumb_images.SIZE_VAR})</span>
	</p>
	<!-- IF postrow.attach.cat_thumb_images.COMMENT -->
	<p class="attach_comment med">
		{postrow.attach.cat_thumb_images.COMMENT}
	</p>
	<!-- ENDIF -->
</fieldset>

<div class="spacer_12"></div>
<!-- END cat_thumb_images -->

<!-- BEGIN attachrow -->
<fieldset class="attach">
<legend>{postrow.attach.attachrow.S_UPLOAD_IMAGE} {L_ATTACHMENT}</legend>
	<p class="attach_link">
		<a href="{postrow.attach.attachrow.U_DOWNLOAD_LINK}" {postrow.attach.attachrow.TARGET_BLANK}><b>{postrow.attach.attachrow.DOWNLOAD_NAME}</b></a>
		<span class="attach_stats med">({postrow.attach.attachrow.FILESIZE} {postrow.attach.attachrow.SIZE_VAR}, {L_DOWNLOADED}: {postrow.attach.attachrow.DOWNLOAD_COUNT})</span>
	</p>
	<!-- IF postrow.attach.attachrow.COMMENT -->
	<p class="attach_comment med">
		{postrow.attach.attachrow.COMMENT}
	</p>
	<!-- ENDIF -->
</fieldset>

<div class="spacer_12"></div>
<!-- END attachrow -->

<!-- BEGIN tor_not_reged -->
<table class="attach bordered med">
	<tr class="row3">
		<th colspan="3">{postrow.attach.tor_not_reged.DOWNLOAD_NAME}</th>
	</tr>
	<tr class="row1">
		<td width="15%">{L_TORRENT}:</td>
		<td width="70%">{postrow.attach.tor_not_reged.TRACKER_LINK}</td>
		<td width="15%" rowspan="6" class="tCenter pad_6">
			<p>{postrow.attach.tor_not_reged.S_UPLOAD_IMAGE}</p>
			<p>{L_DOWNLOAD}</p>
			<p class="small">{postrow.attach.tor_not_reged.FILESIZE}</p>
		</td>
	</tr>
	<tr class="row1">
		<td>{L_ADDED}:</td>
		<td>{postrow.attach.tor_not_reged.POSTED_TIME}</td>
	</tr>
	<tr class="row1">
		<td>{L_DOWNLOADED}:</td>
		<td>{postrow.attach.tor_not_reged.DOWNLOAD_COUNT} <!-- IF SHOW_DL_LIST_LINK -->&nbsp;[ <a href="{DL_LIST_HREF}" class="med">{L_SHOW_DL_LIST}</a> ] <!-- ENDIF --></td>
	</tr>
	<!-- BEGIN comment -->
	<tr class="row1 tCenter">
		<td colspan="3">{postrow.attach.tor_not_reged.comment.COMMENT}</td>
	</tr>
	<!-- END comment -->
	<tr class="row3 tCenter">
		<td colspan="3">&nbsp;
		<script type="text/javascript">
		ajax.callback.change_torrent = function(data) {
		    if(data.title) alert(data.title);
		    if(data.url) document.location.href = data.url;
		};
		</script>
		<!-- IF TOR_CONTROLS -->
		<script type="text/javascript">
		function change_torrents()
		{
			ajax.exec({
				action    : 'change_torrent',
				attach_id : {postrow.attach.tor_not_reged.ATTACH_ID},
				type      : $('#tor-select-{postrow.attach.tor_not_reged.ATTACH_ID}').val(),
			});
		}
		</script>
			<select name="tor_action" id="tor-select-{postrow.attach.tor_not_reged.ATTACH_ID}" onchange="$('#tor-confirm-{postrow.attach.tor_not_reged.ATTACH_ID}').attr('checked', 0); $('#tor-submit-{postrow.attach.tor_not_reged.ATTACH_ID}').attr('disabled', 1)">
				<option value="" selected="selected" class="select-action">&raquo; {L_SELECT_ACTION}</option>
				<option value="del_torrent">{L_DELETE_TORRENT}</option>
				<option value="del_torrent_move_topic">{L_DEL_MOVE_TORRENT}</option>
			</select>

			&nbsp; <a href="#" onclick="change_torrents($('#tor-{postrow.attach.tor_reged.ATTACH_ID} select').val()); return false;"><input type="submit" value="{L_DO_SUBMIT}" class="liteoption" /></a>
		<!-- ENDIF -->
		&nbsp;</td>
	</tr>
</table>

<div class="spacer_12"></div>
<!-- END tor_not_reged -->

<!-- BEGIN tor_reged -->

<!-- IF postrow.attach.tor_reged.TOR_SILVER_GOLD == 1 || postrow.attach.tor_reged.TOR_SILVER_GOLD == 2 && postrow.attach.tor_reged.TOR_TIME > 1 --> 
        <script language=JavaScript type=text/javascript> 
        function fulltime () { 
        var time=new Date(); 
        var newStat=new Date("{postrow.attach.tor_reged.TOR_TIME_SET}"); 
        var totalRemains=(newStat.getTime()-time.getTime()); 
        if (totalRemains>1){ 
          var RemainsSec = (parseInt(totalRemains/1000));//5 
          var RemainsFullDays=(parseInt(RemainsSec/(24*60*60))); 
          var secInLastDay=RemainsSec-RemainsFullDays*24*3600; 
          var RemainsFullHours=(parseInt(secInLastDay/3600)); 
          if (RemainsFullHours<10 && RemainsFullHours > 0) RemainsFullHours="0"+RemainsFullHours; 
          var secInLastHour=secInLastDay-RemainsFullHours*3600;//5 
          var RemainsMinutes=(parseInt(secInLastHour/60));//51 
          if (RemainsMinutes<10) RemainsMinutes="0"+RemainsMinutes; 
          var lastSec=secInLastHour-RemainsMinutes*60; 
          if (lastSec<10){lastSec="0"+lastSec}; 
          if(RemainsFullDays) $('#day').text(RemainsFullDays+'дн. '); 
          if(RemainsFullHours) $('#hour').text(RemainsFullHours+'ч. '); 
          $('#min').text(RemainsMinutes+'мин. '); 
          $('#sec').text(lastSec+"сек. "); 
          setTimeout('fulltime()',10) 
        } 
        else{ 
         $('#change').text('Статус сменится через несколько минут'); 
        } 
        } 
        </script> 
<!-- ENDIF --> 

<!-- IF TOR_BLOCKED -->
<table id="tor_blocked" class="error">
	<tr><td><p class="error_msg">{TOR_BLOCKED_MSG}</p></td></tr>
</table>

<div class="spacer_12"></div>
<!-- ELSE -->
<!-- IF SHOW_RATIO_WARN -->
<table id="tor_blocked" class="error">
	<tr><td><p class="error_msg">{RATIO_WARN_MSG}</p></td></tr>
</table>

<div class="spacer_12"></div>
<!-- ENDIF -->

<table class="attach bordered med">
	<tr class="row3">
		<th colspan="3" class="{postrow.attach.tor_reged.DL_LINK_CLASS}">{postrow.attach.tor_reged.DOWNLOAD_NAME}<!-- IF postrow.attach.tor_reged.TOR_FROZEN == 0 --><!-- IF MAGNET_LINKS -->&nbsp;{postrow.attach.tor_reged.MAGNET}<!-- ENDIF --><!-- ENDIF --></th>
	</tr>
	<!-- IF postrow.attach.tor_reged.TOR_SILVER_GOLD == 2 && $tr_cfg['gold_silver_enabled'] -->
    <tr class="row4">
        <th colspan="3" class="row7"><img src="images/tor_silver.gif" width="33" height="16" title="{L_SILVER}" />&nbsp;{L_SILVER_STATUS}&nbsp;<img src="images/tor_silver.gif" width="33" height="16" title="{L_SILVER}" /></th>
    </tr>
	<tr class="row4" align="center"> 
		<td colspan=3>        
			<!-- IF postrow.attach.tor_reged.TOR_TIME > 0 --> 
			<b>Осталось: <span id="change"><span id="day"></span><span id="hour"></span><span id="min"></span><span id="sec"></span></span></b><script language=JavaScript>fulltime();</script> [  {postrow.attach.tor_reged.TOR_TIME_SET1} ] 
			<!-- ELSE --> 
			<b>Вечный</b> 
			<!-- ENDIF --> 
		</td> 
	</tr> 
	<!-- ELSEIF postrow.attach.tor_reged.TOR_SILVER_GOLD == 1 && $tr_cfg['gold_silver_enabled'] -->
	<tr class="row4">
		<th colspan="3" class="row7"><img src="images/tor_gold.gif" width="33" height="16" title="{L_GOLD}" />&nbsp;{L_GOLD_STATUS}&nbsp;<img src="images/tor_gold.gif" width="33" height="16" title="{L_GOLD}" /></th>
	</tr>
	<tr class="row4" align="center"> 
		<td colspan=3>        
			<!-- IF postrow.attach.tor_reged.TOR_TIME > 0 --> 
			<b>Осталось: <span id="change"><span id="day"></span><span id="hour"></span><span id="min"></span><span id="sec"></span></span></b><script language=JavaScript>fulltime();</script> [  {postrow.attach.tor_reged.TOR_TIME_SET1} ] 
			<!-- ELSE --> 
			<b>Вечный</b> 
			<!-- ENDIF --> 
		</td> 
	</tr>
	<!-- ENDIF -->
	<tr class="row1">
		<td width="15%">{L_TORRENT}:</td>
		<td width="70%">
			{postrow.attach.tor_reged.TRACKER_LINK} &nbsp;
			[ <span title="{postrow.attach.tor_reged.REGED_DELTA}">{postrow.attach.tor_reged.REGED_TIME}</span> ]
		</td>
		<td width="15%" rowspan="6" class="tCenter pad_6">
			<!-- IF postrow.attach.tor_reged.TOR_FROZEN -->
			<!-- IF $bb_cfg['text_buttons'] --><p>{L_DOWNLOAD}</p><!-- ELSE --><p>{postrow.attach.tor_reged.S_UPLOAD_IMAGE}</p><!-- ENDIF -->
			<!-- ELSE -->
			<a href="{postrow.attach.tor_reged.U_DOWNLOAD_LINK}" class="{postrow.attach.tor_reged.DL_LINK_CLASS}">
			<!-- IF $bb_cfg['text_buttons'] --><p><b>{L_DOWNLOAD}</b></p><!-- ELSE --><p>{postrow.attach.tor_reged.S_UPLOAD_IMAGE}</p><!-- ENDIF --></a>
			<!-- ENDIF -->
			<p style="padding-top: 0px;"><img src="images/btn_files.png" border="0" id="tor-filelist-btn"></p>
			<p style="padding-top: 0px;">{postrow.attach.tor_reged.TH_BUTTON}</p>
			<p class="small">{postrow.attach.tor_reged.FILESIZE}</p>
		</td>
	</tr>
	<tr class="row1">
		<td>{L_TOR_STATUS}:</td>
		<td>
			<span id="tor-{postrow.attach.tor_reged.ATTACH_ID}-status">{postrow.attach.tor_reged.TOR_STATUS_ICON} <b>{postrow.attach.tor_reged.TOR_STATUS_TEXT}</b>
			<!-- IF postrow.attach.tor_reged.TOR_STATUS_BY -->{postrow.attach.tor_reged.TOR_STATUS_BY}<!-- ENDIF -->
			</span>
			<!-- IF AUTH_MOD -->
			
			<script src="./misc/calendar/js/jscal2.js"></script> 
			<script src="./misc/calendar/js/lang/ru.js"></script> 
			<link rel="stylesheet" type="text/css" href="./misc/calendar/css/jscal2.css" /> 
			<link rel="stylesheet" type="text/css" href="./misc/calendar/css/border-radius.css" /> 
			<link rel="stylesheet" type="text/css" href="./misc/calendar/css/gold/gold.css" /> 
			
			<script type="text/javascript">
				ajax.change_tor_status = function(status) {
					ajax.exec({
						action    : 'change_tor_status',
						attach_id : {postrow.attach.tor_reged.ATTACH_ID},
						status    : status
					});
				};
				ajax.callback.change_tor_status = function(data) {
					$('#tor-'+ data.attach_id +'-status').html(data.status);
				};
			</script>

			<span id="tor-{postrow.attach.tor_reged.ATTACH_ID}">{postrow.attach.tor_reged.TOR_STATUS_SELECT}</span>
			<a href="#" onclick="ajax.change_tor_status($('#tor-{postrow.attach.tor_reged.ATTACH_ID} select').val()); return false;"><input type="submit" value="{L_EDIT}" class="liteoption" /></a>

			<!-- ENDIF -->
		</td>
	</tr>
	<tr class="row1">
		<td>{L_COMPLETED}:</td>
		<td><span title="{L_DOWNLOADED}: {postrow.attach.tor_reged.DOWNLOAD_COUNT}">{postrow.attach.tor_reged.COMPLETED}</span></td>
	</tr>
	<tr class="row1">
		<td>{L_SIZE}:</td>
		<td>{postrow.attach.tor_reged.TORRENT_SIZE}</td>
	</tr>
		<tr class="row1">
		<td class="genmed">{L_RATING}:&nbsp;</td>
		<td class="genmed">{postrow.attach.tor_reged.RATING}&nbsp;({L_RATING_VOTES}: {postrow.attach.tor_reged.RATING_VOTES})
		<!-- IF postrow.attach.tor_reged.YOUR_VOTE -->
		{L_YOUR_VOTE}: {postrow.attach.tor_reged.YOUR_VOTE}
		<!-- ELSE -->
		{postrow.attach.tor_reged.DO_VOTE}
		<!-- ENDIF -->
		</td>
	</tr>
	<tr class="row1">
		<script language="JavaScript" type="text/javascript" src="./misc/js/ajax.js"></script>
		<td>{L_THANKED}:</td>
		<td>{postrow.attach.tor_reged.THANKED}</td>
	</tr>
	<!-- BEGIN comment -->
	<tr class="row1 tCenter">
		<td colspan="3">{postrow.attach.tor_reged.comment.COMMENT}</td>
	</tr>
	<!-- END comment -->
	<tr class="row3 tCenter">
		<td colspan="3">
		<script type="text/javascript">
		ajax.callback.change_torrent = function(data) {
		    if(data.title) alert(data.title);
		    if(data.url) document.location.href = data.url;
		};
		</script>
		<!-- IF TOR_CONTROLS -->
		<script type="text/javascript">
		function change_torrents()
		/*{
			ajax.exec({
				action    : 'change_torrent',
				attach_id : {postrow.attach.tor_reged.ATTACH_ID},
				type      : $('#tor-select-{postrow.attach.tor_reged.ATTACH_ID}').val(),
			});
		}*/
		{
			ajax.exec({
				action    : 'change_torrent',
				attach_id : {postrow.attach.tor_reged.ATTACH_ID},
				type      : $('#tor-select-{postrow.attach.tor_reged.ATTACH_ID}').val(),
				plastdate : $('#plastdate-{postrow.attach.tor_reged.ATTACH_ID}').val(),
				set_po_inp : $('#set_po_inp-{postrow.attach.tor_reged.ATTACH_ID}').val(),
				status    : $('#set_on-{postrow.attach.tor_reged.ATTACH_ID}').val(),
			});
		}
		</script>
			<select name="tor_action" id="tor-select-{postrow.attach.tor_reged.ATTACH_ID}" onchange="$('#tor-confirm-{postrow.attach.tor_reged.ATTACH_ID}').attr('checked', 0); $('#tor-submit-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 1); change_status($(this).val(), 0);">
				<option value="" selected="selected" class="select-action">&raquo; {L_SELECT_ACTION}</option>
				<option value="del_torrent">{L_DELETE_TORRENT}</option>
				<option value="del_torrent_move_topic">{L_DEL_MOVE_TORRENT}</option>
				<!-- IF AUTH_MOD -->
				<!-- IF $tr_cfg['gold_silver_enabled'] -->
				<!-- IF postrow.attach.tor_reged.TOR_SILVER_GOLD == 1 -->
				<option value="unset_silver_gold">{L_UNSET_GOLD_TORRENT} / {L_UNSET_SILVER_TORRENT}</option>
				<option value="set_silver">{L_SET_SILVER_TORRENT}</option>
				<!-- ELSEIF postrow.attach.tor_reged.TOR_SILVER_GOLD == 2 -->
				<option value="unset_silver_gold">{L_UNSET_GOLD_TORRENT} / {L_UNSET_SILVER_TORRENT}</option>
				<option value="set_gold">{L_SET_GOLD_TORRENT}</option>
				<!-- ELSE -->
				<option value="set_gold">{L_SET_GOLD_TORRENT}</option>
				<option value="set_silver">{L_SET_SILVER_TORRENT}</option>
				<!-- ENDIF -->
				<!-- ENDIF -->
				<!-- ENDIF -->
			</select>
			<input type="radio" name="status" value="1" id="set_on-{postrow.attach.tor_reged.ATTACH_ID}" onchange="change_status($('#tor-select-{postrow.attach.tor_reged.ATTACH_ID}').val(), 1);" disabled /> 
			на: <select name=plastdate id="plastdate-{postrow.attach.tor_reged.ATTACH_ID}" disabled> 
				<option value=1h> Один час</option> 
				<option value=2h> Два часа</option> 
				<option value=3h> Три часа</option> 
				<option value=5h> Пять часов</option> 
				<option value=10h> Десять часов</option> 
				<option value=1d> Один день</option> 
				<option value=2d> Два дня</option> 
				<option value=3d> Три дня</option> 
				<option value=5d> Пять дней</option> 
				<option value=10d> Десять дней</option> 
				<option value=1m> Один месяц</option> 
				<option value=2m> Два месяца</option> 
				<option value=3m> Три месяца</option> 
				<option value=1y> Один год</option> 
				<option style="Font-weight: bold;" value=inf>Вечный</option> 
			</select> 
			<a href="#" onclick="change_torrents($('#tor-{postrow.attach.tor_reged.ATTACH_ID} select').val()); return false;"><input type="submit" value="{L_EDIT}" class="liteoption" /></a>
<script type="text/javascript"> 
$('#tor-select-{postrow.attach.tor_reged.ATTACH_ID}').val(''); 
change_status(1, 0); 
function change_status(param, value) 
{ 
  var param = param; 
  var value = value; 
  if (param == 'set_gold' || param == 'set_silver') 
  { 
    switch (value) 
    { 
      case 0: 
      $('#set_po-{postrow.attach.tor_reged.ATTACH_ID}').removeAttr('disabled'); 
      $('#set_on-{postrow.attach.tor_reged.ATTACH_ID}').removeAttr('disabled'); 
      $('#set_po-{postrow.attach.tor_reged.ATTACH_ID}').removeAttr('checked'); 
      $('#set_on-{postrow.attach.tor_reged.ATTACH_ID}').removeAttr('checked'); 
      break; 
      case 1: 
      $('#plastdate-{postrow.attach.tor_reged.ATTACH_ID}').removeAttr('disabled'); 
      $('#set_po_inp-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled'); 
      break; 
      case 2: 
      $('#set_po_inp-{postrow.attach.tor_reged.ATTACH_ID}').removeAttr('disabled'); 
      $('#plastdate-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled'); 
      break; 
    } 
      
  } 
  else 
  { 
    $('#set_po-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled'); 
    $('#set_on-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled');$('#set_po_inp-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled');$('#plastdate-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled'); 
    $('#plastdate-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled');$('#set_po_inp-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled');$('#plastdate-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled'); 
    $('#set_po_inp-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled');$('#set_po_inp-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled');$('#plastdate-{postrow.attach.tor_reged.ATTACH_ID}').attr('disabled', 'disabled'); 
  } 
} 
</script> 
		<!-- ELSEIF TOR_HELP_LINKS -->
		{TOR_HELP_LINKS}
		<!-- ELSE -->
		&nbsp;
		<!-- ENDIF -->
		</td>
	</tr>
</table>

<script type="text/javascript">
function humn_size (size) {
	var i = 0;
	var units = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
	while ((size/1024) >= 1) {
		size = size/1024;
		i++;
	}
	size = new String(size);
	if (size.indexOf('.') != -1) {
		size = size.substring(0, size.indexOf('.') + 3);
	}
	return size + ' ' + units[i];
}

ajax.tor_filelist_loaded = false;
$('#tor-filelist-btn').click(function(){
	if (ajax.tor_filelist_loaded) {
		$('#tor-fl-wrap').toggle();
		return false;
	}
	$('#tor-fl-wrap').show();

	ajax.exec({action: 'view_torrent', attach_id: {postrow.attach.tor_reged.ATTACH_ID} });
	ajax.callback.view_torrent = function(data) {
		$('#tor-filelist').html(data.html);
		$('#tor-filelist > ul.tree-root').treeview({
			control: "#tor-fl-treecontrol"
		});
		$('#tor-filelist li.collapsable').each(function(){
			var $li = $(this);
			var dir_size = 0;
			$('i', $li).each(function(){ dir_size += parseInt(this.innerHTML) });
			$('span.b:first', $li).append(' &middot; <s>' + humn_size(dir_size) + '</s>');
		});
		$('#tor-filelist i').each(function(){
			var size_bytes = this.innerHTML;
			this.innerHTML = '('+ size_bytes +')';
			$(this).prepend('<s>'+ humn_size(size_bytes) +'</s> ');
		});
		ajax.tor_filelist_loaded = true;
	};
	$('#tor-fl-treecontrol a').click(function(){ this.blur(); });
	return false;
});
</script>

<style type="text/css">
#tor-fl-wrap {
	margin: 12px auto 0; width: 95%;
}
#fl-tbl-wrap { margin: 2px 14px 16px 14px; }
#tor-filelist {
	margin: 0 2px; padding: 8px 6px;
	max-height: 284px; overflow: auto;
}
#tor-filelist i { color: #7A7A7A; padding-left: 4px; }
#tor-filelist s { color: #0000FF; text-decoration: none; }
#tor-filelist .b > s { color: #800000; }
#tor-filelist .b { font-weight: bold; padding-left: 20px; background: transparent url('images/folder.gif') no-repeat 3px 50%;}
#tor-filelist ul li span { padding-left: 20px; background: transparent url('images/page.gif') no-repeat 3px 50%;}
#tor-filelist .tor-root-dir { font-size: 13px; font-weight: bold; line-height: 12px; padding-left: 4px; }
#tor-fl-treecontrol { padding: 2px 0 4px; }
#tor-fl-treecontrol a { padding: 0 8px; font-size: 11px; text-decoration: none; }
#tor-fl-bgn { width: 200px; height: 300px; margin-right: 6px; border: 1px solid #B5BEC4;}
</style>

<div id="tor-fl-wrap" class="border bw_TRBL row2 hidden">
<div id="fl-tbl-wrap">
	<table class="w100 borderless" cellspacing="0" cellpadding="0">
	<tr>
		<!--<td></td>-->
		<td>
		    <div id="tor-fl-treecontrol">
			    <a href="#">{L_COLLAPSE}</a>&middot;
				<a href="#">{L_EXPAND}</a>&middot;
				<a href="#">{L_SWITCH}</a>
			</div>
		</td>
	</tr>
	<tr>
		<!--<td class="vTop"><div id="tor-fl-bgn" class="border bw_TRBL med row1">YOUR ADS BLOCK</div></td>-->
		<td class="vTop" style="width: 100%;"><div id="tor-filelist" class="border bw_TRBL med row1"><span class="loading-1">{L_LOADING}</span></div></td>
	</tr>
	</table>
</div>
</div>

<div class="spacer_12"></div>
<!-- ENDIF -->
<!-- END tor_reged -->

<!-- END attach -->