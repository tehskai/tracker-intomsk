<html>
	<head>
		<title>Авторизация :: tracker.intomsk.com</title>
		<link rel=stylesheet type="text/css" href="style.css"  title="Style">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		    <script type="text/javascript" src="misc/cd/js/jquery.min.js"></script>
    <script src="misc/cd/js/jquery.countdown.js" type="text/javascript"></script>
    		<script type="text/javascript">
function calcage(secs, num1, num2) {
    s = ((Math.floor(secs/num1))%num2).toString();
    if ( s.length < 2)
   s = "0" + s;
    return s;
  }
$(function () {
   var finalDate = new Date({FINAL_DATE});
   var curDate = new Date();
   var secRemaining = finalDate - curDate;
   if(secRemaining >= 0)
   {
    secRemaining = Math.floor(secRemaining/1000);
    var Days = calcage(secRemaining,86400,100000);
    var Hours = calcage(secRemaining,3600,24);
    var Minutes = calcage(secRemaining,60,60);
    var Seconds = calcage(secRemaining,1,60);
    var arr = [Days,Hours,Minutes,Seconds];
  
    $('#counter').countdown({
      digitImages: 6,
      image: 'misc/cd/digits2.png',
      startTime: arr.join(':'),
      timerEnd: function(){
         $('#timer').remove();
         $('#form').fadeIn(30);
         }
    });
  }
else
  {
  $('#timer').remove();
  $('#form').show();
  }
});
		</script>
    <!--<script type="text/javascript">
      $(function(){
        $('#counter').countdown({
          image: 'misc/cd/digits2.png',
          startTime: '01:12:12:00'
        });
      });
    </script>-->

    <style type="text/css">
      br { clear: both; }
      .cntSeparator {
        font-size: 54px;
        margin: 10px 7px;
        color: #000;
      }
      .desc { margin: 7px 3px; }
      .desc div {
        float: left;
        font-family: Arial;
        width: 70px;
        margin-right: 65px;
        font-size: 13px;
        font-weight: bold;
        color: #000;
      }
    </style>
	</head>
	<body>
		<table id="bg">
			<tr>
				<td>
					<div id="form" style="display:none;">
					<form action="{S_LOGIN_ACTION}" method="post">
					<!-- IF ADMIN_LOGIN --><input type="hidden" name="admin" value="1" /><!-- ENDIF -->
					<input type="hidden" name="redirect" value="{REDIRECT_URL}" />
					<div id="text" align="center"><!-- IF ADMIN_LOGIN -->{L_ADMIN_REAUTHENTICATE}<!-- ELSE -->{L_ENTER_PASSWORD}<!-- ENDIF --></div>
					<div id="hr">&nbsp;</div>
					<div align="center">
						<!-- IF ERROR_MESSAGE -->
						<div class="warnColor1">{ERROR_MESSAGE}</div>
						<!-- ENDIF -->
					</div>
					<br />
					<table class="table" align="center">
						<tr>
							<td colspan="2">
							<input class="username" name="login_username" type="text" accesskey="l" placeholder="Логин" value="{LOGIN_USERNAME}" <!-- IF ADMIN_LOGIN --> readonly="readonly" style="color: gray"<!-- ENDIF --> />
							</td>
						</tr>
						<tr>
							<td colspan="2">
							<input class="username" name="login_password" type="password" placeholder="Пароль" />
							</td>
						</tr>
						<tr>
							<td colspan="2">
							<!-- IF CAPTCHA_HTML -->
							{CAPTCHA_HTML}
							<!-- ENDIF -->
							</td>
						</tr>
						<tr>
							<td colspan="2">
							<div id="text"><input class="check" type="checkbox" name="autologin" value="1" <!-- IF ADMIN_LOGIN || AUTOLOGIN_DISABLED -->disabled="disabled"<!-- ELSE -->checked="checked"<!-- ENDIF -->>&nbsp;Запомнить</div>
							</td>
						</tr>
						<!-- IF CAPTCHA_HTML -->
						<!-- ELSE -->
						<tr>
							<td colspan="2">
							&nbsp;
							</td>
						</tr>
						<!-- ENDIF -->
						<tr>
							<td>
							<input class="send" type="submit" name="login" value="">
							</td>
							<td>
							<input class="Clear" type="reset" name="reset" value="">
							</td>
						</tr>
					</table><br>
					<div align="center" id="text"><a href="{U_SEND_PASSWORD}">{L_FORGOTTEN_PASSWORD}</a></div>
					<div align="center" id="text"><a href="profile.php?mode=register">Регистрация</a></div>
					<div align="center" id="text"><a href="invitation.php">Как получить инвайт</a></div>
					</form>
					</div>
					<div id="timer">
					<table class="table" align="center">
						<tr>
							<td>
								<div id="counter" style="position:relative;"></div>
									<div class="desc">
										<div>Дней</div>
										<div>Часов</div>
										<div>Минут</div>
										<div>Секунд</div>
									</div>
							</td>
						</tr>
					</table>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>

