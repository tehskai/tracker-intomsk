
<!-- IF TPL_ADMIN_FRAMESET -->
<!--========================================================================-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html dir="{L_CONTENT_DIRECTION}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={L_CONTENT_ENCODING}" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<title>{L_ADMIN}</title>
</head>

<frameset cols="220,*" rows="*" border="1" framespacing="1" frameborder="yes">
  <frame src="{S_FRAME_NAV}" name="nav" marginwidth="0" marginheight="0" scrolling="auto">
  <frame src="{S_FRAME_MAIN}" name="main" marginwidth="0" marginheight="0" scrolling="auto">
</frameset>

<noframes>
	<body bgcolor="#FFFFFF" text="#000000">
		<p>Sorry, your browser doesn't seem to support frames</p>
	</body>
</noframes>

</html>
<!--========================================================================-->
<!-- ENDIF / TPL_ADMIN_FRAMESET -->

<!-- IF TPL_ADMIN_NAVIGATE -->
<!--========================================================================-->
<script language="javascript" type="text/javascript">
<!--
var menuVersion = "Slide Menu v1.0.0";

menuVersion += ' &copy; 2004<br />by <a href="http://www.phpmix.com/" target="_blank" class="copyright">phpMiX</a>';

function getCookie(name)
{
  var cookies = document.cookie;
  var start = cookies.indexOf(name + '=');
  if( start < 0 ) return null;
  var len = start + name.length + 1;
  var end = cookies.indexOf(';', len);
  if( end < 0 ) end = cookies.length;
  return unescape(cookies.substring(len, end));
}
function setCookie(name, value, expires, path, domain, secure)
{
  document.cookie = name + '=' + escape (value) +
    ((expires) ? '; expires=' + ( (expires == 'never') ? 'Thu, 31-Dec-2099 23:59:59 GMT' : expires.toGMTString() ) : '') +
    ((path)    ? '; path='    + path    : '') +
    ((domain)  ? '; domain='  + domain  : '') +
    ((secure)  ? '; secure' : '');
}
function delCookie(name, path, domain)
{
  if( getCookie(name) )
  {
    document.cookie = name + '=;expires=Thu, 01-Jan-1970 00:00:01 GMT' +
      ((path)    ? '; path='    + path    : '') +
      ((domain)  ? '; domain='  + domain  : '');
  }
}

function menuCat(id, rows)
{
  this.cat_id = id;
  this.cat_rows = rows;
  this.status = 'block';
}
var menuCats = new Array();
<!-- BEGIN catrow -->
menuCats['menuCat_{catrow.MENU_CAT_ID}'] = new menuCat('{catrow.MENU_CAT_ID}', {catrow.MENU_CAT_ROWS});
<!-- END catrow -->

function getObj(obj)
{
  return ( document.getElementById ? document.getElementById(obj) : ( document.all ? document.all[obj] : null ) );
}
function displayObj(obj, status)
{
  var x = getObj(obj);
  if( x && x.style ) x.style.display = status;
}

var queueInterval = 20;    // milliseconds between queued steps.
var execInterval = 0;
var queuedSteps;
var currentStep;

function queueStep(o, s)
{
  this.obj = o;
  this.status = s;
}
function execQueue()
{
  if( currentStep < queuedSteps.length )
  {
    var obj = queuedSteps[currentStep].obj;
    var status = queuedSteps[currentStep].status;
    displayObj(obj, status);
    if( menuCats[obj] ) menuCats[obj].status = status;
    currentStep++;
    setTimeout("execQueue();", execInterval);
  }
  else
  {
    execInterval = queueInterval;
  }
}
function onMenuCatClick(cat_id)
{
  var currentCat, currentStatus;

  currentCat = 'menuCat_'+cat_id;
  currentStatus = menuCats[currentCat].status;

  queuedSteps = new Array();
  currentStep = 0;

  for( var catName in menuCats )
  {
    if( menuCats[catName].status == 'none' ) continue;

    for( var i=(menuCats[catName].cat_rows-1); i >= 0; i-- )
    {
      queuedSteps[currentStep++] = new queueStep(catName+'_'+i, 'none');
    }
    queuedSteps[currentStep++] = new queueStep(catName, 'none');
  }

  if( currentStatus == 'none' )
  {
    queuedSteps[currentStep++] = new queueStep(currentCat, 'block');
    for( var i=0; i < menuCats[currentCat].cat_rows; i++ )
    {
      queuedSteps[currentStep++] = new queueStep(currentCat+'_'+i, 'block');
    }
    var  expdate = new Date();    // 72 Hours from now
    expdate.setTime(expdate.getTime() + (72 * 60 * 60 * 1000));
    setCookie('{COOKIE_NAME}_menu_cat_id', cat_id, expdate,
        ('{COOKIE_PATH}'   == '') ? null : '{COOKIE_PATH}',
        ('{COOKIE_DOMAIN}' == '') ? null : '{COOKIE_DOMAIN}',
        ('{COOKIE_SECURE}' == '0') ? false : true);
  }
  else
  {
    delCookie('{COOKIE_NAME}_menu_cat_id',
        ('{COOKIE_PATH}'   == '') ? null : '{COOKIE_PATH}',
        ('{COOKIE_DOMAIN}' == '') ? null : '{COOKIE_DOMAIN}');
  }

  currentStep = 0;
  setTimeout("execQueue();", execInterval);
}

function doOnLoadMenuACP()
{
  var cat_id;

  if( getObj('menuCat_0') )
  {
    cat_id = getCookie('{COOKIE_NAME}_menu_cat_id');
    if( !menuCats['menuCat_'+cat_id] )
    {
      cat_id = 0;
    }
    else
    {
      menuCats['menuCat_'+cat_id].status = 'none';
    }
    onMenuCatClick(cat_id);
  }
  if( oldOnLoadMenuACP )
  {
    oldOnLoadMenuACP();
  }
}
var  oldOnLoadMenuACP = window.onload;
window.onload = doOnLoadMenuACP;

// -->
</script>

<style type="text/css">
body { background: #E5E5E5; min-width: 10px; }
#body_container { background: #E5E5E5; padding: 4px 3px 4px; }
table.forumline { margin: 0 auto; }
</style>

<table class="forumline" id="acp_main_nav">
	<col class="row1">
	<tr>
		<th>{L_ADMIN}</th>
	</tr>
	<tr>
		<td><a href="{U_ADMIN_INDEX}" target="main" class="med">{L_ADMIN_INDEX}</a></td>
	</tr>
	<tr>
		<td><a href="{U_FORUM_INDEX}" target="_parent" class="med">{L_MAIN_INDEX}</a></td>
	</tr>
	<!-- BEGIN catrow -->
	<tr>
		<td class="catTitle" style="cursor:pointer;cursor:hand;" onclick="onMenuCatClick('{catrow.MENU_CAT_ID}', this);">{catrow.ADMIN_CATEGORY}</td>
	</tr>
    <tr>
      <td class="row1">
        <div id="menuCat_{catrow.MENU_CAT_ID}" style="display:block;">
          
	<!-- BEGIN modulerow -->
<p class="row1">
<div id="menuCat_{catrow.MENU_CAT_ID}_{catrow.modulerow.ROW_COUNT}" style="display:block;"><a href="{catrow.modulerow.U_ADMIN_MODULE}" target="main" class="med">{catrow.modulerow.ADMIN_MODULE}</a></div>
</p>
	<!-- END modulerow -->
        </div>
      </td>
    </tr>
	<!-- END catrow -->
</table>

<!--========================================================================-->
<!-- ENDIF / TPL_ADMIN_NAVIGATE -->

<!-- IF TPL_ADMIN_MAIN -->
<!--========================================================================-->

<br />

<table>
	<tr>
		<td><b>{L_CLEAR_CACHE}:</b></td>
		<td>
			<a href="{U_CLEAR_DATASTORE}">{L_DATASTORE}</a>,&nbsp;
			<a href="{U_CLEAR_TPL_CACHE}">{L_TEMPLATES}</a>&nbsp;
		</td>
	</tr>
	<tr>
		<td><b>{L_UPDATE}:</b></td>
		<td>
			<a href="{U_UPDATE_USER_LEVEL}">{L_USER_LEVELS}</a>&nbsp;
		</td>
	</tr>
	<tr>
		<td><b>{L_SYNCHRONIZE}:</b></td>
		<td>
			<a href="{U_SYNC_TOPICS}">{L_TOPICS}</a>,&nbsp;
			<a href="{U_SYNC_USER_POSTS}">{L_USER_POSTS_COUNT}</a>&nbsp;
		</td>
	</tr>
	<tr>
		<td><b>{L_ADMIN}:</b></td>
		<td>
			<a href="../profile.php?mode=register&admin=1">{L_CREATE_PROFILE}</a>
		</td>
	</tr>
</table>
<br />

<table class="forumline">
	<tr>
		<th colspan="2">{L_VERSION_INFORMATION}</th>
	</tr>
	<tr>
		<td class="row1" nowrap="nowrap"  width="25%">{L_TP_VERSION}:</td>
		<td class="row2"><b>{$bb_cfg['tp_version']} ({$bb_cfg['tp_release_state']})</b></td>
	</tr>
	<tr>
		<td class="row1" nowrap="nowrap"  width="25%">{L_TP_RELEASE_DATE}:</td>
		<td class="row2"><b>{$bb_cfg['tp_release_date']}</b></td>
	</tr>
</table>
<h3>{L_FORUM_STATS}</h3>

<table class="forumline">
	<tr>
		<th width="25%">{L_STATISTIC}</th>
		<th width="25%">{L_VALUE}</th>
		<th width="25%">{L_STATISTIC}</th>
		<th width="25%">{L_VALUE}</th>
	</tr>
	<tr>
		<td class="row1" nowrap="nowrap">{L_NUMBER_POSTS}:</td>
		<td class="row2"><b>{NUMBER_OF_POSTS}</b></td>
		<td class="row1" nowrap="nowrap">{L_POSTS_PER_DAY}:</td>
		<td class="row2"><b>{POSTS_PER_DAY}</b></td>
	</tr>
	<tr>
		<td class="row1" nowrap="nowrap">{L_NUMBER_TOPICS}:</td>
		<td class="row2"><b>{NUMBER_OF_TOPICS}</b></td>
		<td class="row1" nowrap="nowrap">{L_TOPICS_PER_DAY}:</td>
		<td class="row2"><b>{TOPICS_PER_DAY}</b></td>
	</tr>
	<tr>
		<td class="row1" nowrap="nowrap">{L_NUMBER_USERS}:</td>
		<td class="row2"><b>{NUMBER_OF_USERS}</b></td>
		<td class="row1" nowrap="nowrap">{L_USERS_PER_DAY}:</td>
		<td class="row2"><b>{USERS_PER_DAY}</b></td>
	</tr>
	<tr>
		<td class="row1" nowrap="nowrap">{L_BOARD_STARTED}:</td>
		<td class="row2"><b>{START_DATE}</b></td>
		<td class="row1" nowrap="nowrap">{L_AVATAR_DIR_SIZE}:</td>
		<td class="row2"><b>{AVATAR_DIR_SIZE}</b></td>
	</tr>
	<tr>
		<td class="row1" nowrap="nowrap">{L_DATABASE_SIZE}:</td>
		<td class="row2"><b>{DB_SIZE}</b></td>
		<td class="row1" nowrap="nowrap">{L_GZIP_COMPRESSION}:</td>
		<td class="row2"><b>{GZIP_COMPRESSION}</b></td>
	</tr>
</table>

<a name="online"></a>
<h3>{L_WHOSONLINE}</h3>

<!-- IF SHOW_USERS_ONLINE -->
<table class="forumline">
	<tr>
		<th>{L_USERNAME}</th>
		<th>{L_LOGIN} / {L_LAST_UPDATED}</th>
		<th>{L_IP_ADDRESS}</th>
	</tr>
	<!-- BEGIN reg_user_row -->
	<tr class="{reg_user_row.ROW_CLASS}">
		<td class="bold" nowrap="nowrap">{reg_user_row.USER}</td>
		<td align="center" nowrap="nowrap">{reg_user_row.STARTED}-{reg_user_row.LASTUPDATE}</td>
		<td class="tCenter"><a href="{reg_user_row.U_WHOIS_IP}" class="gen" target="_blank">{reg_user_row.IP_ADDRESS}</a></td>
	</tr>
	<!-- END reg_user_row -->
	<tr>
		<td colspan="3" class="row3"><img src="{SPACER}" width="1" height="1" alt="."></td>
	</tr>
	<!-- BEGIN guest_user_row -->
	<tr class="{guest_user_row.ROW_CLASS}">
		<td nowrap="nowrap">{L_GUEST}</td>
		<td align="center">{guest_user_row.STARTED}-{guest_user_row.LASTUPDATE}</td>
		<td class="tCenter"><a href="{guest_user_row.U_WHOIS_IP}" target="_blank">{guest_user_row.IP_ADDRESS}</a></td>
	</tr>
	<!-- END guest_user_row -->
</table>
<!-- ELSE -->
<a href="{USERS_ONLINE_HREF}#online">{L_SHOW_ONLINE_USERLIST}</a>
<!-- ENDIF -->

<!--========================================================================-->
<!-- ENDIF / TPL_ADMIN_MAIN -->

