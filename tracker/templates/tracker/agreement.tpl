
<h1 class="pagetitle">{PAGE_TITLE}</h1>

<table class="forumline">
<tr>
	<th>{SITENAME} - {L_REGISTRATION}</th>

<tr>
	<td class="row1">
		<div class="w80 bCenter">
			<?php require(BB_ROOT .'/misc/html/agreement.html'); ?>
			<br /><br />
			<div class="tCenter">
			<div id="warn_text"></div>
			<script type="text/javascript">
        var timer_start = (new Date()).getTime();function getSecs(){var timer_now = (new Date()).getTime();var timeDiff = 60 - (timer_now - timer_start) / 1000;if (timeDiff > 0) {$('#warn_text').html('<b>Пожалуйста, прочтите Пользовательское соглашение. Продолжить регистрацию вы сможете только через ' + Math.round(timeDiff, 0) + ' секунд.</b>');window.setTimeout("getSecs()",1000)} else {$('#warn_text').html('<b>Теперь Вы можете регистрироваться.</b>');$('#agree_button').removeAttr('disabled');
}}getSecs()</script>
        <br />
        <form action="profile.php?mode=register" method="post">
          <!--<input type="hidden" name="mode" value="register">-->
          <input type="hidden" name="reg_agreed" value="1">
          <input class="liteoption" type="submit" disabled="disabled" id="agree_button" value="{L_AGREE}" >
        </form>
        <form action="index.php">
          <input type="submit" value="{L_DO_NOT_AGREE}">
        </form>
<!--<form id="go-to-reg" action="profile.php?mode=register" method="post">
				<input type="hidden" name="reg_agreed" value="1" />
				</form>
				<a href="#" onclick="$('#go-to-reg').submit(); return false;">{L_TERMS_ON}</a>
				<br /><br />
				<a href="index.php">{L_TERMS_OFF}</a>-->
			</div>
			<br />
      </tr>
      
			<br />
		</div>
	</td>
</tr>
</table>
