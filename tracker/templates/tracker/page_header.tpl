<!-- IF QUIRKS_MODE --><!-- ELSE --><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"><!-- ENDIF -->
<html dir="{L_CONTENT_DIRECTION}">

<head>
<title><!-- IF PAGE_TITLE -->{PAGE_TITLE} :: {SITENAME}<!-- ELSE -->{SITENAME}<!-- ENDIF --></title>
<meta http-equiv="Content-Type" content="text/html; charset={L_CONTENT_ENCODING}" />
<meta http-equiv="Content-Style-Type" content="text/css" />
{META}
<link rel="stylesheet" href="{STYLESHEET}?v={$bb_cfg['css_ver']}" type="text/css">
<link rel="icon" type="image/png" href="templates/tracker/images/icons/logo_big.png" />
<link rel="shortcut icon" href="templates/tracker/images/icons/favicon.ico" type="image/x-icon">
<link rel="search" type="application/opensearchdescription+xml" href="opensearch_desc.xml" title="{SITENAME} (Forum)" />
<link rel="search" type="application/opensearchdescription+xml" href="opensearch_desc_bt.xml" title="{SITENAME} (Tracker)" />
<script type="text/javascript" src="{#BB_ROOT}misc/js/jquery.pack.js?v={$bb_cfg['js_ver']}"></script>
<script type="text/javascript" src="{#BB_ROOT}misc/js/main.js?v={$bb_cfg['js_ver']}"></script>

<!-- IF TPL_READONLY_ADD -->


 <link rel="stylesheet" type="text/css" media="all" href="misc/js/datepicker.css" />
 <script type="text/javascript" src="misc/js/datepicker.js"></script>
<!-- ENDIF -->
<script type="text/javascript" src="{#BB_ROOT}misc/js/release.js"></script>
<!-- IF INCLUDE_BBCODE_JS -->
<script type="text/javascript" src="{#BB_ROOT}misc/js/bbcode.js?v={$bb_cfg['js_ver']}"></script>
<script type="text/javascript">
var postImg_MaxWidth = screen.width - {POST_IMG_WIDTH_DECR_JS};
var postImgAligned_MaxWidth = Math.round(screen.width/3);
var attachImg_MaxWidth = screen.width - {ATTACH_IMG_WIDTH_DECR_JS};
var ExternalLinks_InNewWindow = '{EXT_LINK_NEW_WIN}';
var hidePostImg = false;

function copyText_writeLink(node)
{
	if (!is_ie) return;
	document.write('<p style="float: right;"><a class="txtb" onclick="if (ie_copyTextToClipboard('+node+')) alert(\'{L_CODE_COPIED}\'); return false;" href="#">{L_CODE_COPY}</a></p>');
}
function initPostBBCode(context)
{
	$('span.post-hr', context).html('<hr align="left" />');
	initQuotes(context);
	initExternalLinks(context);
	initYoutube(context);
	initSpoilers(context);
  	initPostImages(context);
  }
function initQuotes(context)
{
	$('div.q', context).each(function(){
		var $q = $(this);
		var name = $(this).attr('head');
		$q.before('<div class="q-head">'+ (name ? '<b>'+name+'</b> {L_WROTE}:' : '<b>{L_QUOTE}</b>') +'</div>');
	});
}

function initPostImages(context)
{
	if (hidePostImg) return;
	var $in_spoilers = $('div.sp-body var.postImg', context);
	$('var.postImg', context).not($in_spoilers).each(function(){
		var $v = $(this);
		var src = $v.attr('title');
		var $img = $('<img src="'+ src +'" class="'+ $v.attr('className') +'" alt="pic" />');
		$img = fixPostImage($img);
		var maxW = ($v.hasClass('postImgAligned')) ? postImgAligned_MaxWidth : postImg_MaxWidth;
		$img.bind('click', function(){ return imgFit(this, maxW); });
		if (user.opt_js.i_aft_l) {
			$('#preload').append($img);
			var loading_icon = '<a href="'+ src +'" target="_blank"><img src="images/pic_loading.gif" alt="" /></a>';
			$v.html(loading_icon);
			if ($.browser.msie) {
				$v.after('<wbr>');
			}
			$img.one('load', function(){
				imgFit(this, maxW);
				$v.empty().append(this);
			});
		}
		else {
			$img.one('load', function(){ imgFit(this, maxW) });
			$v.empty().append($img);
			if ($.browser.msie) {
				$v.after('<wbr>');
			}
		}
	});
}
function initSpoilers(context)
{
	$('div.sp-body', context).each(function(){
		var $sp_body = $(this);
		var name = $.trim(this.title) || '{L_SPOILER_HEAD}';
		this.title = '';
		var $sp_head = $('<div class="sp-head folded clickable">'+ name +'</div>');
		$sp_head.insertBefore($sp_body).click(function(e){
			if (!$sp_body.hasClass('inited')) {
				initPostImages($sp_body);
						$sp_body.prepend('<div class="clear"></div>').append('<div class="clear"></div>').addClass('inited');
				$sp_body.after('<div class="sp-fold clickable"  style="display:none" onclick="spoilerHide($(this));"><div class="sp_ico unfolded"><b>Закрыть</b></div></div>');			
}
			if (e.shiftKey) {
				e.stopPropagation();
				e.shiftKey = false;
				var fold = $(this).hasClass('unfolded');
				$('div.sp-head', $($sp_body.parents('td')[0])).filter( function(){ return $(this).hasClass('unfolded') ? fold : !fold } ).click();
			}
			else {
				$(this).toggleClass('unfolded');
				$sp_body.slideToggle('fast');
$sp_body.next().slideToggle('fast');
			}
		});
	});
}
	function spoilerHide($sp_body) 
	{
	    if ($(document).scrollTop() > $sp_body.prev().offset().top) {
	        $(document).scrollTop($sp_body.prev().offset().top - 200);
	    }
	   $sp_body.slideToggle('fast');
	    $sp_body.prev().slideToggle('fast');
	    $sp_body.prev().prev().toggleClass('unfolded');
	}


function initExternalLinks(context)
{
  	var context = context || 'body';
  	if (ExternalLinks_InNewWindow) {
  		$("a.postLink:not([href*='"+ window.location.hostname +"/'])", context).attr({ target: '_blank' });
  		//$("a.postLink:not([@href*='"+ window.location.hostname +"/'])", context).replaceWith('<span style="color: red;">Ссылки запрещены</span>');
  	}
}
function fixPostImage ($img)
{
	var banned_image_hosts = /imagebanana|hidebehind/i;  // imageshack
	var src = $img[0].src;
	if (src.match(banned_image_hosts)) {
		$img.wrap('<a href="'+ this.src +'" target="_blank"></a>').attr({ src: "{SMILES_URL}/tr_oops.gif", title: "{L_SCREENSHOTS_RULES}" });
	}
	return $img;
}

function initYoutube(context)
{
	var apostLink = $('a.postLink', context);
	for (var i = 0; i < apostLink.length; i++) {
		if (/^http:\/\/www.youtube.com\/watch\?(.*)?(&?v=([a-z0-9\-_]+))(.*)?|http:\/\/youtu.be\/.+/i.test(apostLink[i].href)) {
			var a = document.createElement('span');
			a.className = 'YTLink';
			a.innerHTML = '<span title="Начать проигрывание на текущей странице" class="YTLinkButton">&#9658;</span>';
			window.addEvent(a, 'click', function (e) {
				var vhref = e.target.nextSibling.href.replace(/^http:\/\/www.youtube.com\/watch\?(.*)?(&?v=([a-z0-9\-_]+))(.*)?|http:\/\/youtu.be\//ig, "http://www.youtube.com/embed/$3");
				var text  = e.target.nextSibling.innerText != "" ? e.target.nextSibling.innerText : e.target.nextSibling.href;
				$('#Panel_youtube').remove();
				ypanel('youtube', {
					title: '<b>' + text + '</b>',
					resizing: 0,
					width: 862,
					height: 550,
					content: '<iframe width="853" height="510" frameborder="0" allowfullscreen="" src="' + vhref + '?wmode=opaque"></iframe>'
				});
			});
			apostLink[i].parentNode.insertBefore(a, apostLink[i]);
			a.appendChild(apostLink[i]);
			if (/<var class="postImg/i.test(apostLink[i].innerHTML)) {
				$(apostLink[i]).parent().css({
					backgroundColor: 'transparent',
					border: 'none'
				}).find('.YTLinkButton').css({
					margin: '0px'
				});
			}
		}
	}
}

$(document).ready(function(){
  	$('div.post_wrap, div.signature').each(function(){ initPostBBCode( $(this) ) });
});
</script>
<script type="text/javascript">
function emoticon(text) {
	text = ' ' + text + ' ';
	if (opener.document.forms['post'].message.createTextRange && opener.document.forms['post'].message.caretPos) {
		var caretPos = opener.document.forms['post'].message.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		opener.document.forms['post'].message.focus();
	} else {
	opener.document.forms['post'].message.value  += text;
	opener.document.forms['post'].message.focus();
	}
}
</script>
<!-- ENDIF / INCLUDE_BBCODE_JS -->
<script type="text/javascript">
var BB_ROOT       = "{#BB_ROOT}";
var cookieDomain  = "{$bb_cfg['cookie_domain']}";
var cookiePath    = "{$bb_cfg['cookie_path']}";
var cookieSecure  = {$bb_cfg['cookie_secure']};
var cookiePrefix  = "{$bb_cfg['cookie_prefix']}";
var LOGGED_IN     = {LOGGED_IN};
var InfoWinParams = 'HEIGHT=510,resizable=yes,WIDTH=780';

var user = {
  	opt_js: {USER_OPTIONS_JS},

  	set: function(opt, val, days, reload) {
  		this.opt_js[opt] = val;
  		setCookie('opt_js', $.toJSON(this.opt_js), days);
  		if (reload) {
  			window.location.reload();
  		}
  	}
}

<!-- IF SHOW_JUMPBOX -->
$(document).ready(function(){
  	$("div.jumpbox").html('\
  		<span id="jumpbox-container"> \
  		<select id="jumpbox"> \
  			<option id="jumpbox-title" value="-1">&nbsp;&raquo;&raquo; {L_JUMPBOX_TITLE} &nbsp;</option> \
  		</select> \
  		</span> \
  		<input id="jumpbox-submit" type="button" class="lite" value="{L_GO}" /> \
  	');
  	$('#jumpbox-container').one('click', function(){
  		$('#jumpbox-title').html('&nbsp;&nbsp; {L_LOADING} ... &nbsp;');
  		var jumpbox_src = '{AJAX_HTML_DIR}' + ({LOGGED_IN} ? 'jumpbox_user.html' : 'jumpbox_guest.html');
  		$(this).load(jumpbox_src);
  		$('#jumpbox-submit').click(function(){ window.location.href='{FORUM_URL}'+$('#jumpbox').val(); });
  	});
});
<!-- ENDIF -->

var ajax = new Ajax('{AJAX_HANDLER}', 'POST', 'json');

function getElText (e)
{
  	var t = '';
  	if (e.textContent !== undefined) { t = e.textContent; } else if (e.innerText !== undefined) { t = e.innerText; } else { t = jQuery(e).text(); }
  	return t;
}
function escHTML (txt)
{
  	return txt.replace(/</g, '&lt;');
}
<!-- IF USE_TABLESORTER -->
$(document).ready(function(){
  	$('.tablesorter').tablesorter(); //	{debug: true}
});
<!-- ENDIF -->
</script>

<!--[if lte IE 6]><script type="text/javascript">
$(ie6_make_clickable_labels);

$(function(){
	$('div.menu-sub').prepend('<iframe class="ie-fix-select-overlap"></iframe>'); // iframe for IE select box z-index issue
	Menu.iframeFix = true;
});
</script><![endif]-->


<!--[if gte IE 7]><style type="text/css">
input[type="checkbox"] { margin-bottom: -1px; }
</style><![endif]-->

<!--[if lte IE 6]><style type="text/css">
.forumline th { height: 24px; padding: 2px 4px; }
.menu-sub iframe.ie-fix-select-overlap { display: none; display: block; position: absolute; z-index: -1; filter: mask(); }
</style><![endif]-->

<!--[if IE]><style type="text/css">
.post-hr { margin: 2px auto; }
.fieldsets div > p { margin-bottom: 0; }
</style><![endif]-->

<!-- IF INCLUDE_DEVELOP_JS -->
<script type="text/javascript">
var dev = true;
function OpenInEditor ($file, $line)
{
  	$editor_path = '{EDITOR_PATH}';
  	$editor_args = '{EDITOR_ARGS}';

  	$url = BB_ROOT +'develop/open_editor.php';
  	$url += '?prog='+ $editor_path +'&args='+ $editor_args.sprintf($file, $line);

  	window.open($url,'','height=1,width=1,left=1,top=1,resizable=yes,scrollbars=no,toolbar=no');
}
</script>
<!-- ENDIF / INCLUDE_DEVELOP_JS -->
<style type="text/css">
	.menu-sub, #ajax-loading, #ajax-error, var.ajax-params, .sp-title { display: none; }
</style>
<link type="text/css" href="templates/{$bb_cfg['tpl_name']}/css/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />	
				<script type="text/javascript" src="misc/js/ui/jquery-ui-1.7.3.js"></script>
<script type="text/javascript">
   $(function(){
     $("#dialog").dialog({
          autoOpen: true,
         width: 500,
         height: 200,
         //show: "slide",
         buttons: {
            "Ok": function() {
               $(this).dialog("close");
            },
            "Закрыть": function() {
               $(this).dialog("close");
         },
       }
     });
   });
   </script>
    <link type="text/css" href="templates/{$bb_cfg['tpl_name']}/css/menu.css" rel="stylesheet" />
    <script type="text/javascript" src="templates/{$bb_cfg['tpl_name']}/js/menu.js"></script>

<!-- Thumbnails -->
<script type="text/javascript" src="{#BB_ROOT}misc/js/subSiver/highslide.js"></script> 
<link rel="stylesheet" type="text/css" href="{#BB_ROOT}misc/js/subSiver/highslide.css" /> 
<script type="text/javascript"> 
  hs.graphicsDir = '{#BB_ROOT}misc/js/subSiver/graphics/'; 
  hs.align = 'center'; 
  hs.transitions = ['expand', 'crossfade']; 
  hs.outlineType = 'glossy-dark'; 
    hs.wrapperClassName = 'dark'; 
  hs.fadeInOut = true; 
  hs.dimmingOpacity = 0.50; 
  hs.numberPosition = 'caption'; 
  if (hs.addSlideshow) hs.addSlideshow({ 
    interval: 5000, 
    repeat: false, 
    useControls: true, 
    fixedControls: 'fit', 
    overlayOptions: { 
      opacity: .75, 
      position: 'bottom center', 
      hideOnMouseOut: true 
    }, 
    thumbstrip: { 
      position: 'above', 
      mode: 'horizontal', 
      relativeTo: 'expander' 
    }
  }); 
</script>

	<link rel="stylesheet" type="text/css" href="{#BB_ROOT}misc/lightbox/resource/sample.css" media="screen,tv" title="default" />
	<link rel="stylesheet" type="text/css" href="{#BB_ROOT}misc/lightbox/resource/lightbox.css" media="screen,tv" />
	<script type="text/javascript" src="{#BB_ROOT}misc/lightbox/resource/lightbox_plus.js"></script>

<link type="text/css" href="templates/{$bb_cfg['tpl_name']}/css/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />	
				<script type="text/javascript" src="templates/{$bb_cfg['tpl_name']}/js/ui/jquery-ui-1.7.3.custom.min.js"></script>
	<script type="text/javascript">
			$(function(){
				// Dialog			
				$('#new_pm').dialog({
					autoOpen: true,
					width: 500,
					height: 155,
					buttons: {
						/*"Прочитать": function() {
							$.get("pmurl");
							//alert(pmurl);
						},*/
						"Закрыть": function() { 
							$(this).dialog("close"); 
						} 
					},
				});
				pmurl = "{U_READ_PM}";
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				// whote_parking			
				$('#whote_park').dialog({
					autoOpen: false,
					width: 690,
					height: 200,
					buttons: {
						"Закрыть": function() { 
							$(this).dialog("close"); 
						}
					}
				});
				// whote_parking Link
				$('#whote_link').click(function(){
					$('#whote_park').dialog('open');
					return false;
				});
			});
		</script>
<!-- Thumbnails [END] -->
<style type="text/css">
.menu-a { background: #FFFFFF; border: 1px solid #92A3A4; }
.menu-a a { background: #EFEFEF; padding: 4px 10px 5px; margin: 1px; display: block; }
</style>
<script type="text/javascript" src="templates/{$bb_cfg['tpl_name']}/js/tabslideout.js"></script>
<script type="text/javascript">
$(function(){
  $('.slide-out-div').click(function(){ 
  $('.user_ratio').toggle(300);
  });
  $('.slide-out-div').tabSlideOut({
            tabHandle: '.handle',          //класс элемента вкладки
            pathToTabImage: 'templates/tracker/images/profile_tab3.png',  //путь к изображению "обязательно"
            imageHeight: '135px',          //высота изображения "обязательно"
            imageWidth: '25px',          //ширина изображения "обязательно"
            tabLocation: 'right',          //сторона на которой будет вкладка top, right, bottom, или left
            speed: 300,              //скорость анимации
            action: 'click',            //опции=: 'click' или 'hover', анимация при нажатии или наведении
            topPos: '45px',            //расположение от верхнего края/ использовать если tabLocation = left или right
            leftPos: '0px',            //расположение от левого края/ использовать если tabLocation = bottom или top
            fixedPosition: false          //опции: true сделает данную вкладку неподвижной при скролле
        });

});
</script>
<style type="text/css" media="screen">
  .slide-out-div { margin-top:-196px;}
</style>
</head>

<body>
<!-- IF HAVE_NEW_PM -->
   <div id="new_pm" title="Получены персональные сообщения">
     Уважаемый <strong>{THIS_USERNAME}</strong>, 
     </br> с момента вашего отсутствия на сайте вам было прислано:</font></br>
<p><a href="{U_READ_PM}"><font>{PM_INFO}</font></a></p>
   </div>
<!-- ENDIF -->

<!-- IF EDITABLE_TPLS -->
<div id="editable-tpl-input" style="display: none;">
	<span class="editable-inputs nowrap" style="display: none;">
		<input type="text" class="editable-value" />
		<input type="button" class="editable-submit" value="&raquo;" style="width: 30px; font-weight: bold;" />
		<input type="button" class="editable-cancel" value="x" style="width: 30px;" />
	</span>
</div>
<div id="editable-tpl-yesno-select" style="display: none;">
	<span class="editable-inputs nowrap" style="display: none;">
		<select class="editable-value"><option value="1">{L_YES}</option><option value="0">{L_NO}</option></select>
		<input type="button" class="editable-submit" value="&raquo;" style="width: 30px; font-weight: bold;" />
		<input type="button" class="editable-cancel" value="x" style="width: 30px;" />
	</span>
</div>
<div id="editable-tpl-yesno-radio" style="display: none;">
	<span class="editable-inputs nowrap" style="display: none;">
		<label><input class="editable-value" type="radio" name="editable-value" value="1" />{L_YES}</label>
		<label><input class="editable-value" type="radio" name="editable-value" value="0" />{L_NO}</label>&nbsp;
		<input type="button" class="editable-submit" value="&raquo;" style="width: 30px; font-weight: bold;" />
		<input type="button" class="editable-cancel" value="x" style="width: 30px;" />
	</span>
</div>
<!-- ENDIF / EDITABLE_TPLS -->

	<!-- IF PAGINATION -->
	<div class="menu-sub" id="pg-jump">
		<table cellspacing="1" cellpadding="4">
		<tr><th>{L_GO_TO_PAGE}</th></tr>
		<tr><td>
			<form method="get" onsubmit="return go_to_page();">
				<input id="pg-page" type="text" size="5" maxlength="4" />
				<input type="submit" value="{L_JUMP_TO}"/>
			</form>
		</td></tr>
		</table>
	</div>
	<script type="text/javascript">
	function go_to_page ()
	{
		var page_num = (parseInt( $('#pg-page').val() ) > 1) ? $('#pg-page').val() : 1;
		var pg_start = (page_num - 1) * {PG_PER_PAGE};
		window.location = '{PG_BASE_URL}&start=' + pg_start;
		return false;
	}
	</script>
	<!-- ENDIF -->
	<!--
<table id="ajax-loading" cellpadding="0" cellspacing="0"><tr><td class="loading-1"></td><td><i class="loading-1">{L_LOADING}</i></td></tr></table>
<table id="ajax-error" cellpadding="0" cellspacing="0"><tr><td>Error</td></tr></table>-->
<div id="ajax-loading"></div><div id="ajax-error"></div>
<div id="preload" style="position: absolute; overflow: hidden; top: 0; left: 0; height: 1px; width: 1px;"></div>

<div id="body_container">

<!--************************************************************************-->
<!-- IF SIMPLE_HEADER -->
<!--========================================================================-->

<style type="text/css">
body {

background: #E3E3E3 url(images/av/bkground.jpg);
min-width: 10px; }
</style>

<!--========================================================================-->
<!-- ELSEIF IN_ADMIN -->
<!--========================================================================-->

<!--========================================================================-->
<!-- ELSE -->
<!--========================================================================-->

<!--page_container-->
<div id="page_container">
<a name="top"></a>

<!--page_header-->
<div id="page_header">
<!--Панель поиска-->
<!-- IF SHOW_USER_OPTIONS -->
	<div style=" position: absolute; top: 5px; right: 60px; z-index:500;">
	<form id="quick-search" action="" method="post" onsubmit="
		$(this).attr('action', $('#search-action').val());
		var txt=$('#search-text').val(); return !(txt=='{L_SEARCH_S}' || !txt);
	">
		<input type="hidden" name="max" value="1" />
		<input type="hidden" name="to" value="1" />
		<input id="search-text" type="text" name="nm" onfocus="if(this.value=='{L_SEARCH_S}') this.value='';" onblur="if(this.value=='') this.value='{L_SEARCH_S}';" value="{L_SEARCH_S}" class="hint" style="width: 120px;" />
		<select id="search-action">
			<option value="tracker.php#results" selected="selected"> {L_TRACKER_S} </option>
			<option value="search.php"> {L_FORUM_S} </option>
		</select>
		<input type="submit" class="med bold" value="&raquo;" style="width: 30px;" />
	</form>
	</div>
<!-- ENDIF -->
<!--/Панель поиска-->
<!--main_nav-->
<style type="text/css">
div#copyright {
	visibility: hidden;
	position:absolute;
}
</style>
<!-- IF SHOW_USER_OPTIONS -->
<div id="menu">
	<ul class="menu">
		<li><a href="{U_INDEX}"><span>{L_HOME}</span></a></li>
			<li><a class="parent"><span>Навигация</span></a>
				<div><ul>
					<li><a href="/viewtopic.php?t=4455"><span>Заказ доставки</span></a></li>
					<li><a href="/viewforum.php?f=137"><span>Даты выхода игр</span></a></li>
					<li><a href="{U_TRACKER}"><span>{L_TRACKER}</span></a></li>
					<li><a href="{U_SEARCH}"><span>{L_SEARCH}</span></a></li>
					<li><a href="/bans.php"><span>Баны</span></a></li>
					<!--<li><a href="/forum/boockmarks.php"><span>Закладки</span></a></li>-->
				</ul></div>
			</li>
			<li><a class="parent"><span>{L_FAQ}</span></a>
				<div><ul>
					<li><a href="{U_FAQ}"><span>{L_FAQ}</span></a></li>
					<li><a href="/viewtopic.php?t=12"><span>Правила</span></a></li>
				</ul></div>
			</li>
			<li><a href="#" class="parent"><span>Личное</span></a>
				<div><ul>
					<li><a href="{U_PROFILE}"><span>Профиль</span></a></li>
					<li><a href="{U_READ_PM}"><span>ЛС</span></a></li> 
					<li><a href="{U_GROUP_CP}"><span>{L_USERGROUPS}</span></a></li>
					<li><a href="{U_MEMBERLIST}"><span>{L_MEMBERLIST}</span></a></li>
					<li><a href="vip_list.php"><span><b class="colorVIP">VIP</b> <b>тарифы</b></span></a></li>
				</ul></div>
			</li>
		<!--<li><a href="/order.php"><span>Заявки</span></a></li>-->
			<li><a class="parent"><span>Топ</span></a>
				<div><ul>
					<li><a href="/viewforum.php?f=129"><span>Доска почета</span></a></li>
					<li><a href="/mytop.php"><span>Top 30</span></a></li> 
					<li><a href="/releasetop.php"><span>Статистика</span></a></li>
					<li><a href="/profile.php?mode=bonus"><span>Обмен бонусов</span></a></li>
				</ul></div>
			</li>
			<li><a class="parent"><span>Общение</span></a>
				<div><ul>
					<li><a href="/viewforum.php?f=57"><span>Ваши предложения</span></a></li>
					<li><a href="/viewforum.php?f=58"><span>Общение пользователей</span></a></li> 
				</ul></div>
			</li>
			<li><a href="#" class="parent"><span>Важные сведения</span></a>
				<div><ul>
					<li><a href="{$bb_cfg['copyright_holders_url']}" onclick="window.open(this.href, '', InfoWinParams); return false;"><span>{L_COPYRIGHT_HOLDERS}</span></a></li>
					<li><a href="{$bb_cfg['advert_url']}" onclick="window.open(this.href, '', InfoWinParams); return false;"><span>{L_ADVERT}</span></a></li>
					<li><a href="{$bb_cfg['user_agreement_url']}" onclick="window.open(this.href, '', InfoWinParams); return false;"><span>{L_USER_AGREEMENT}</span></a></li> 
					<li><a href="/viewtopic.php?t=3150" target="_blank"><span>Помочь ресурсу</span></a></li> 
				</ul></div>
			</li>
		<li><a href="{U_GALLERY}" target="_blank"><span>{L_GALLERY}</span></a></li>
		<li class="last"><a href="irc:///intomsk"><span>IRC Канал</span></a></li>
	</ul>
	<div id="menur"></div>
</div>
<div id="copyright">
		Copyright &copy; 2011 <a href="http://apycom.com/">Apycom jQuery Menus</a>
</div>
<!-- ENDIF -->
<!--/main_nav-->
<!--logo-->
<div id="logo">
<div style="left:0px; top:30px; background: url('templates/{$bb_cfg['tpl_name']}/images/av/logo_cellpic.jpg') repeat-x;">
<div ><a href="{U_INDEX}"><img src="templates/{$bb_cfg['tpl_name']}/images/av/logo_left.jpg" alt="" /></a></div>
</div>
<!--/logo-->
<!--topmenu-->
<!-- IF LOGGED_IN -->

<script type="text/javascript">
ajax.index_data = function(tz) {
	ajax.exec({
		action  :'index_data',
		mode    : 'change_tz',
		tz      : tz,
	});
};
ajax.callback.index_data = function(data) {
};
$(document).ready(function() {
	x = new Date();
	tz = -x.getTimezoneOffset()/60;
	if (tz != <?php echo $bb_cfg['board_timezone']?>)
	{
		ajax.index_data(tz);
	}
});
</script>
<!-- IF SHOW_USER_OPTIONS -->
<!--logout-->
<div class="topmenu">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td width="40%">
        {L_USER_WELCOME} &nbsp;<b class="med">{THIS_USER}</b>&nbsp;<!-- IF $bb_cfg['text_buttons'] -->[ <a href="{U_LOGIN_LOGOUT}" onclick="return confirm('{L_CONFIRM_LOGOUT}');">{L_LOGOUT}</a> ]<!-- ELSE --><a href="{U_LOGIN_LOGOUT}" onclick="return confirm('{L_CONFIRM_LOGOUT}');"><img style="margin-bottom:-4px;" src="templates/tracker/images/lang_russian/exit2.png" height="17" title="{L_LOGOUT}" ></a><!-- ENDIF -->
    </td>

	<td align="center" nowrap="nowrap">
		<!-- BEGIN switch_report_list -->
		<a href="{U_REPORT_LIST}" class="mainmenu">{REPORT_LIST}</a> &#0183;
		<!-- END switch_report_list -->
		<!-- BEGIN switch_report_list_new -->
		<strong><a href="{U_REPORT_LIST}" class="mainmenu">{REPORT_LIST} &#0183; </a></strong>
		<!-- END switch_report_list_new -->
    </td>

	<td style="padding: 2px;">
				<!-- IF HAVE_NEW_PM || HAVE_UNREAD_PM -->
					<a href="{U_READ_PM}" class="new-pm-link">{L_PRIVATE_MESSAGES}: {PM_INFO}</a>
				<!-- ELSE -->
					<a href="{U_PRIVATEMSGS}">{L_PRIVATE_MESSAGES}: {PM_INFO}</a>
				<!-- ENDIF -->
	</td>
    <td width="50%" class="tRight">
	    <a href="#dls-more" class="menu-root menu-alt1"><b>Дополнительно</b></a> &#0183;
	    <a href="#dls-menu" class="menu-root menu-alt1"><b>Закачки</b></a> &#0183;<!--&#9660; onclick="this.className = (this.className == 'unfolded3' ? 'folded3' : 'unfolded3')"-->
	    <a href="{U_SEARCH_SELF_BY_LAST}">{L_SEARCH_SELF}</a>
    </td>
        </tr>
    </table>
</div>
<!--/logout-->
<div class="menu-sub" id="dls-more">
	<div class="menu-a bold nowrap">
		<a class="med" href="{U_OPTIONS}" style="color:#993300"><b>{L_OPTIONS}</b></a>
		<!-- IF SHOW_ADMIN_OPTIONS -->
		<a class="med" href="/admin/" target="_blank">Админцентр</a>
		<a class="med" href="invite.php">Выдать инвайт</a>
		<!-- ENDIF -->
		<!-- IF SHOW_AM -->
		<a class="med" href="readonly.php">Заглушки пользователей</a>
		<a class="med" href="hits.php">Управление новинками</a>
		<!-- ENDIF -->
		<!-- BEGIN switch_report_general -->
	    <a class="med" href="{U_WRITE_REPORT}">{L_WRITE_REPORT}</a>
	    <!-- END switch_report_general -->
	</div>
</div>
<div class="menu-sub" id="dls-menu">
	<div class="menu-a bold nowrap">
		<a class="med" href="{U_TRACKER}?rid={SESSION_USER_ID}#results">{L_CUR_UPLOADS}</a>
		<a class="med" href="{U_CUR_DOWNLOADS}#torrent">{L_CUR_DOWNLOADS}</a>
		<a class="med" href="{U_SEARCH}?dlu={SESSION_USER_ID}&dlc=1">{L_SEARCH_DL_COMPLETE_DOWNLOADS}</a>
		<a class="med" href="{U_SEARCH}?dlu={SESSION_USER_ID}&dlw=1">{L_SEARCH_DL_WILL_DOWNLOADS}</a>
	</div>
</div>
<!-- ELSE -->

<!--login form-->
<div class="topmenu">
   <table width="100%" cellpadding="0" cellspacing="0">
   <tr>
        <td class="tCenter pad_2">
            <a href="{U_REGISTER}" id="register_link"><b>{L_REGISTER}</b></a> &#0183;
                <form action="{S_LOGIN_ACTION}" method="post">
                    {L_USERNAME}: <input type="text" name="login_username" size="12" tabindex="1" accesskey="l" />
                    {L_PASSWORD}: <input type="password" name="login_password" size="12" tabindex="2" />
                    <label title="{L_AUTO_LOGIN}"><input type="checkbox" name="autologin" value="1" tabindex="3" />{L_REMEMBER}</label>&nbsp;
                    <input type="submit" name="login" value="{L_LOGIN}" tabindex="4" />
                </form> &#0183;
            <a href="{U_SEND_PASSWORD}">{L_FORGOTTEN_PASSWORD}</a>
        </td>
    </tr>
    </table>
</div>

<!--/login form-->
<!-- ENDIF -->
<!-- ENDIF -->
<!--/topmenu-->


<!--breadcrumb-->
<!--<div id="breadcrumb"></div>-->
<!--/breadcrumb-->

<!-- IF SHOW_IMPORTANT_INFO -->
<!--important_info-->
<!--<div id="important_info">
important_info
</div>-->
<!--/important_info-->
<!-- ENDIF -->

</div>
<!--/page_header-->
<!--menus-->

<!-- IF SHOW_ONLY_NEW_MENU -->
<div class="menu-sub" id="only-new-options">
	<table cellspacing="1" cellpadding="4">
	<tr>
		<th>{L_DISPLAYING_OPTIONS}</th>
	</tr>
	<tr>
		<td>
			<fieldset id="show-only">
			<legend>{L_SHOW_ONLY}</legend>
			<div class="pad_4">
				<label>
					<input id="only_new_posts" type="checkbox" <!-- IF ONLY_NEW_POSTS_ON -->{CHECKED}<!-- ENDIF -->
						onclick="
							user.set('only_new', ( this.checked ? {ONLY_NEW_POSTS} : 0 ), 365, true);
							$('#only_new_topics').attr('checked', 0);
						" />{L_ONLY_NEW_POSTS}
				</label>
				<label>
					<input id="only_new_topics" type="checkbox" <!-- IF ONLY_NEW_TOPICS_ON -->{CHECKED}<!-- ENDIF -->
						onclick="
							user.set('only_new', ( this.checked ? {ONLY_NEW_TOPICS} : 0 ), 365, true);
							$('#only_new_posts').attr('checked', 0);
						" />{L_ONLY_NEW_TOPICS}
				</label>
			</div>
			</fieldset>
			<!-- IF USER_HIDE_CAT -->
			<fieldset id="user_hide_cat">
			<legend>{L_HIDE_CAT}</legend>
			<div id="h-cat-ctl" class="pad_4 nowrap">
				<form autocomplete="off">
					<!-- BEGIN h_c -->
					<label><input class="h-cat-cbx" type="checkbox" value="{h_c.H_C_ID}" {h_c.H_C_CHEKED} />{h_c.H_C_TITLE}</label>
					<!-- END h_c -->
				</form>
				<div class="spacer_6"></div>
				<div class="tCenter">
					<!-- IF H_C_AL_MESS -->
					<input style="width: 100px;" type="button" onclick="$('input.h-cat-cbx').attr('checked',false); $('input#sec_h_cat').click(); return false;" value="Сбросить">
					<!-- ENDIF -->
					<input id="sec_h_cat" type="button" onclick="set_h_cat();" style="width: 100px;" value="Отправить">
				    <script type="text/javascript">
					function set_h_cat ()
					{
						h_cats = [];
						$.each($('input.h-cat-cbx:checked'), function(i,el){
							h_cats.push( $(this).val() );
						});
						user.set('h_cat', h_cats.join('-'), 365, true);
					}
					</script>
				</div>
			</div>
			</fieldset>
			<!-- ENDIF -->
		</td>
	</tr>
	</table>
</div><!--/only-new-options-->
<!-- ENDIF / SHOW_ONLY_NEW_MENU -->

<!--/menus-->
<!--page_content-->
<div id="page_content">
	<table cellspacing="0" cellpadding="0" border="0" style="width: 100%;"><tr>
<!-- IF SHOW_SIDEBAR1 -->
	<!--sidebar1-->
		<td id="sidebar1">
		<div id="sidebar1-wrap">
			<table border="0" cellpadding="0" cellspacing="0" width="229px">
				<!-- IF SHOW_GOLDEN_DAYS -->
				<table border="0" cellpadding="0" cellspacing="0" width="229px">
					<tbody>
						<tr>
							<td class="block_top1" !important="" height="7px"></td>
						</tr>
						<tr>
							<td class="block_body">
								<center>
									<img style="margin-left:-27px" src="images/golddays.png" title="Золотой День: Скачаное не учитывается, Отданное учитывается в полной объеме." alt="Золотой День: Скачаное не учитывается, Отданное учитывается в полной объеме.">
								</center> 
							</td>
						</tr>
						<tr>
							<td class="block_foot" width="7px">&nbsp;</td>
						</tr>
					</tbody>
				</table>
				<!-- ENDIF -->
				<table border="0" cellpadding="0" cellspacing="0" width="229px">
					<tbody>
						<tr>
							<td class="block_top" !important="" height="25px">Мы в Соц. сетях</td>
						</tr>
						<tr>
							<td class="block_body">
								<br>
									<div id="banners">
											<span class="post-align" style="text-align: left;">
												&nbsp;<a href="http://vk.com/club23114374"><img src="images/social/Vkontakte.png"></a>&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="http://www.facebook.com/groups/354458494588713/"><img src="images/social/Facebook.png"></a>&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="https://twitter.com/#!/tracker_intomsk"><img src="images/social/Twitter.png"></a>&nbsp;&nbsp;&nbsp;&nbsp;
											</span>
									</div>
							</td>
					</tr>
						<tr>
							<td class="block_foot" width="7px">&nbsp;</td>
						</tr>
					</table>
					<!-- IF AD_BLOCK_102 -->
					<table border="0" cellpadding="0" cellspacing="0" width="229px">
						<tbody>
							<tr>
								<td class="block_top" !important="" height="25px">Реклама</td>
							</tr>
							<tr>
								<td class="block_body">
									<ul>
									<br>
									<div id="ad-100">{AD_BLOCK_102}</div><!--/ad-102-->
									</ul>
								</td>
							</tr>
							<tr>
								<td class="block_foot" width="7px">&nbsp;</td>
							</tr>
					</table>
					<!-- ENDIF / AD_BLOCK_102 -->
					<!--
					<table border="0" cellpadding="0" cellspacing="0" width="229px">
						<tbody>
							<tr>
								<td class="block_top" !important="" height="25px">Реклама</td>
							</tr>
							<tr>
								<td class="block_body">
									<ul>
										<img src="http://tracker.intomsk.com/forum/images/banners/200300.png">
									</ul>
								</td>
							</tr>
							<tr>
								<td class="block_foot" width="7px">&nbsp;</td>
							</tr>
						</table>
						-->
						<table border="0" cellpadding="0" cellspacing="0" width="229px">
							<tbody>
								<tr>
									<td class="block_top" !important="" height="25px">Новинки</td>
								</tr>
									<ul>
										<tr>
											<td class="block_body">
												<ul>
												<!-- BEGIN torhits -->
													<li class="torhit_entry"><a href="{torhits.URL}">{torhits.IMAGES}</a></li>
													</div>
												<!-- END torhits -->
												</ul>
											</td>
										</tr>
										<tr>
											<td class="block_foot" width="7px">&nbsp;</td>
										</tr>
									</table>
									<!-- IF AD_BLOCK_104 -->
									<table border="0" cellpadding="0" cellspacing="0" width="229px">
										<tbody>
											<tr>
												<td class="block_top" !important="" height="25px">Реклама</td>
											</tr>
											<tr>
												<td class="block_body">
													<ul>
													<br>
													<div id="ad-100">{AD_BLOCK_104}</div><!--/ad-102-->
													</ul>
												</td>
											</tr>
											<tr>
												<td class="block_foot" width="7px">&nbsp;</td>
											</tr>
									</table>
									<!-- ENDIF / AD_BLOCK_104 -->
<!-- IF LAST_ADDED_ON -->
<table border="0" cellpadding="0" cellspacing="0" width="229px">
<tbody><tr><td class="block_top" !important="" height="25px">{L_LAST_ADDED}</td></tr>
<!-- BEGIN lastAdded -->
<tr>
	<td class="block_body">
<ul>
		<div><li class="torhit_entry"><a href="viewtopic.php?t={lastAdded.TOPIC_ID}" style="font-size:11px">{lastAdded.TITLE}</a></li></div>
		<div style="font-size:10px">{L_AUTHOR}: <a href="profile.php?mode=viewprofile&u={lastAdded.POSTER_ID}">{lastAdded.POSTER}</a>; {lastAdded.TORRENT_TIME}</div><div style="font-size:10px"> {L_FORUM}: <a href="viewforum.php?f={lastAdded.FORUM_ID}">{lastAdded.FORUM}</a></div>
</ul>
	</td>
</tr>
<!-- END lastAdded -->
<tr><td class="block_foot" width="7px">&nbsp;</td></tr></table>
<!-- ENDIF -->

<!-- IF TOP_DOWNLOADED_ON -->
<table border="0" cellpadding="0" cellspacing="0" width="229px">
<tbody><tr><td class="block_top" !important="" height="25px">{L_TOP_DOWNLOADED}</td></tr>
<!-- BEGIN TopDownloaded -->
<tr>
	<td class="block_body">
<ul>
		<div><li class="torhit_entry"><a href="viewtopic.php?t={TopDownloaded.TOPIC_ID}" style="font-size:11px">{TopDownloaded.TITLE}</a></li></div>
<div style="font-size:10px">{L_COMPLETED}: <b>{TopDownloaded.COMPLETED}</b></div>
<div style="font-size:10px">{L_AUTHOR}: <a href="profile.php?mode=viewprofile&u={TopDownloaded.POSTER_ID}">{TopDownloaded.POSTER}</a>; {TopDownloaded.TORRENT_TIME}</div><div style="font-size:10px"> {L_FORUM}: <a href="viewforum.php?f={TopDownloaded.FORUM_ID}">{TopDownloaded.FORUM}</a></div>
</ul>
	</td>
</tr>
<!-- END TopDownloaded -->
<tr><td class="block_foot" width="7px">&nbsp;</td></tr></table>
<!-- ENDIF -->

<!-- IF TOP_UPLOADERS_ON -->
<div class="spacer" style="height:5px">&nbsp;</div>
<table width="229px" cellpadding="3" cellspacing="0" border="0" class="attachtable">
<tr class="cat_title"><th colspan="2" scope="col"><b>{L_TOP_SEEDERS}</b></th></tr>
<!-- BEGIN TopUploaders -->
<tr>
<td class="row1 f_titles" style="border-bottom: 1px solid #C3CBD1;">
<div style="font-size:11px" align="right"><b><a href="profile.php?mode=viewprofile&u={TopUploaders.USER_ID}">{TopUploaders.UPL_NAME}:</a><b></div>
</td>
<td class="row1 f_titles" style="border-bottom: 1px solid #C3CBD1;">
<div style="font-size:11px"><b><span class="seedmed">{TopUploaders.UPLOADED}</span></b>    </div>
</td>
</tr>
<!-- END TopUploaders -->
</table>
<!-- ENDIF -->

<!-- IF TOP_DOWNLOADERS_ON -->
<h3></h3>
<div class="spacer" style="height:5px">&nbsp;</div>
<table width="229px" cellpadding="3" cellspacing="0" border="0" class="attachtable">
<tr class="cat_title"><th colspan="2" scope="col"><b>{L_TOP_LEECHERS}</b></th></tr>
<!-- BEGIN TopDownloaders -->
<tr>
<td class="row1 f_titles" style="border-bottom: 1px solid #C3CBD1;">
<div style="font-size:11px" align="right"><b><a href="profile.php?mode=viewprofile&u={TopDownloaders.USER_ID}">{TopDownloaders.DOWNL_NAME}:</a><b></div>
</td>
<td class="row1 f_titles" style="border-bottom: 1px solid #C3CBD1;">
<div style="font-size:11px"><b><span class="leechmed">{TopDownloaders.DOWNLOADED}</span></b>    </div>
</td>
</tr>
<!-- END TopDownloaders -->
</table>
<!-- ENDIF -->
<?php if (!empty($bb_cfg['sidebar1_static_content_path'])) include($bb_cfg['sidebar1_static_content_path']);?>
									<img width="210" class="spacer" src="{SPACER}" alt="" />
								</div>
							</td>
<!-- ENDIF -->


<!--main_content--> 
<td id="main_content"> 
<div id="main_content_wrap"> 
<table width="100%" align="center">
	<tr>
		<td align="center">
		<!-- Start add - Complete banner MOD -->
		<!-- Banners -->
			<!-- IF AD_BLOCK_100 -->
			<div class="banner_l" style="float:left;">
				<div id="ad-100">{AD_BLOCK_100}</div><!--/ad-100-->
			</div>
			<!-- ENDIF / AD_BLOCK_100 -->
			<!-- IF AD_BLOCK_101 -->
			<div class="banner_r" style="float:right;">
				<div id="ad-100">{AD_BLOCK_101}</div><!--/ad-101-->
			</div>
			<!-- ENDIF / AD_BLOCK_101 -->
		<!-- End Banners -->
		<!-- End add - Complete banner MOD -->
		<!--<div class="banner" style="float:left;">
	</div>-->
		</td>
	<td>
<!-- IF LOGGED_IN -->
<!-- IF SHOW_BT_USERDATA_MY --> 
<div class="slide-out-div">
	<a class="handle" style="margin-top:-9px;" href="#" title="Панель пользователя"></a>
	<div>
		<div class="user_ratio" style="float:right; margin-top:-7px; display: none;">
			<table cellpadding="0" cellspacing="0" height="124"> 
				<tr>
					<td rowspan="7" class="userpic"><div class="radius">{THIS_AVATAR}</div></td>
					<td class="tRight panel-top-element">{L_USER_RATIO}</td><td class="panel-top-element"><!-- IF DOWN_TOTAL_BYTES_MY gt MIN_DL_BYTES_MY --><b>{USER_RATIO_MY}</b><!-- ELSE --><b>нет</b> (DL < {MIN_DL_FOR_RATIO})<!-- ENDIF --></td>
				</tr> 
				<tr><td class="tRight panel-element">{L_DOWNLOADED}</td><td class="leechmed"><b>{DOWN_TOTAL_MY}</b></td></tr>
				<tr><td class="tRight panel-element">{L_UPLOADED}</td><td class="seedmed"><b>{UP_TOTAL_MY}</b></td></tr>
				<tr><td class="tRight panel-element"><i>{L_RELEASED}</i></td><td class="seedmed">{RELEASED_MY}</td></tr>
				<tr><td class="tRight panel-element"><i>Сид-бонус:</i></td><td class="seedmed"><a href="profile.php?mode=bonus" class="med">{SEED_POINTS_MY}</a></td></tr> 
				<tr><td class="tRight panel-bottom-element">Закачки:</td><td class="panel-bottom-element"><span title="Свои ~ Раздает в данный момен свои раздачи "><img src="templates/{$bb_cfg['tpl_name']}/images/head/arrowup2.gif">{RELEASING}</span><span title="Раздает ~ Раздаёте в данный момент."><img src="templates/{$bb_cfg['tpl_name']}/images/head/arrowup.gif">{SEEDING}</span>  <span title="Качает ~ Качаете в данный момент, если вы скачали раздачу не на 100% т.е. пропустили какие либо файлы, то закачка останется в этой категории"><img src="templates/{$bb_cfg['tpl_name']}/images/head/arrowdown.gif">{LEECHING}</span></td></tr>
				<!--<tr><td class="tRight panel-element">{L_BONUS}</td><td class="seedmed">{UP_BONUS}</td></tr>-->
				<!--<tr>
					<td class="tRight panel-bottom-element">Закачки:</td><td class="panel-bottom-element"><img src="images/arrowup2.gif">{SEEDING} <img src="images/arrowdown.gif" alt="Качаете">{LEECHING} | <span class="cursor_help" title="Общий обьем ваших релизов."><b>{RELEASING}</b></span></td> 
				</tr>-->
				</tr>
			</table>
		</div>
	</div>
</div>
<!-- ENDIF / SHOW_BT_USERDATA -->
<!-- ENDIF -->
</td>
</tr>
</table>
<!-- IF SHOW_LATEST_NEWS -->
<!--latest_news-->
<div id="latest_news"> 
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
   <td width="50%"> <!--class="latest_news_l"-->
   <div class="block_title">
      <h3 >{L_LATEST_NEWS}</h3><hr>
      <table cellpadding="0">
<!-- BEGIN news -->
<tr>
<td><div class="news_date">{news.NEWS_TIME}</div></td>
<td width="100%"><div class="news_title<!-- IF news.NEWS_IS_NEW --> new<!-- ENDIF -->"><a href="{TOPIC_URL}{news.NEWS_TOPIC_ID}">{news.NEWS_TITLE}</a></div></td>
</tr>
<!-- END news -->
            </table></div>
            <td></td>
   </td>
   <!--latest_net-->
   <td width="50%">
      <div class="block_title">
      <h3>{L_NETWORK_NEWS} [Игровые]</h3><hr>
      <table cellpadding="0">
<!-- BEGIN net -->
<tr>
<td><div class="news_date">{net.NEWS_TIME}</div></td>
<td width="100%"><div class="news_title<!-- IF net.NEWS_IS_NEW --> new<!-- ENDIF -->"><a href="{TOPIC_URL}{net.NEWS_TOPIC_ID}">{net.NEWS_TITLE}</a></div></td>
</tr>
<!-- END net -->
            </table></div>
   </td><!--/latest_net-->
</tr>
</table>
</div><!--/latest_news-->
<!-- ENDIF / SHOW_LATEST_NEWS -->




<!--=======================-->
<!-- ENDIF / COMMON_HEADER -->
<!--***********************-->

<!-- IF ERROR_MESSAGE -->
<div class="info_msg_wrap">
<table class="error">
	<tr><td><div class="msg">{ERROR_MESSAGE}</div></td></tr>
</table>
</div>
<!-- ENDIF / ERROR_MESSAGE -->

<!-- IF INFO_MESSAGE -->
<div class="info_msg_wrap">
<table class="info_msg">
	<tr><td><div class="msg">{INFO_MESSAGE}</div></td></tr>
</table>
</div>
<!-- ENDIF / INFO_MESSAGE -->


<!-- page_header.tpl END -->
<!-- module_xx.tpl START -->
