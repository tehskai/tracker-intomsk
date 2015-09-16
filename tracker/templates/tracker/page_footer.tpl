<!-- IF SIMPLE_FOOTER -->

<!-- ELSEIF IN_ADMIN -->

<!-- ELSE -->

	</div><!--/main_content_wrap-->
	</td><!--/main_content-->
	<!-- IF SHOW_SIDEBAR2 -->
		<!--sidebar2-->
		<td id="sidebar2">
		<div id="sidebar2_wrap">
			<?php if (!empty($bb_cfg['sidebar2_static_content_path'])) include($bb_cfg['sidebar2_static_content_path']); ?>
			<img width="210" class="spacer" src="{SPACER}" alt="" />
		</div><!--/sidebar2_wrap-->
		</td><!--/sidebar2-->
	<!-- ENDIF -->
	</tr></table>
	</div>
	<!--/page_content-->
<!-- IF AD_BLOCK_103 --><div id="ad-103">
<center>
<div class="banner_l" style="align:center;">
{AD_BLOCK_103}
</div>
</center>
</div><!--/ad-103--><!-- ENDIF / AD_BLOCK_103 -->
	<!--page_footer-->
	<div id="page_footer" class="bottom_box">
		<table class="w100" align="center" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td align="left" class="copyright">
			{L_POWERED} <br>
			{L_DIVE} <br><br>
			Последующая доработка и модификации <b>Tusken</b>, <b>Invincible</b>, <b>Hardkor</b> <br>
			Design by <b><i>ARXANGEL</i></b> & <b><i>Mir1nda</i></b>
			</td>

						<!--BANNERS-->
			<td align="center" class="copyright">
			<div id="banners" class="tiny tcenter"><span style="margin:0;padding:0" id="tbec">
			<!-- TBEX  -->
			<script type="text/javascript">setTimeout('var tbex=document.createElement("SCRIPT");tbex.type="text/javascript";tbex.src="http://c.tbex.ru/t/0!8031!tracker.intomsk.com!c.js?rev=2"+String.fromCharCode(38)+"rnd="+(new Date().getTime());document.documentElement.firstChild.appendChild(tbex)',1)</script>
			<!-- TBEX [End]  -->
			<!-- TSK.ru  -->
			<a href="http://top.T-sk.ru/detailed/tracker.intomsk.com.html"><img src="http://top.t-sk.ru/image.php?host=tracker.intomsk.com&amp;vtype=4&amp;ctype=6" widht="80" height="31" title="Томск - Рейтинг сайтов" alt="Томск" style="borger:0" /></a>
			<!-- TSK.ru [End]  -->
			</span></div>
			</td>
						<!--BANNERS [End]-->

			<td align="right" class="copyright">
			Проект <a href="http://Tracker.intomsk.com">tracker.intomsk.com</a> <br> специально оптимизирован под браузеры: <br><a href="http://ru.opera.com/">Opera</a>, <a href="http://www.mozilla.com/ru/firefox/">Mozilla Firefox</a>, <a href="http://www.google.com/chrome/?hl=ru">Google Chrome</a>, <a href="http://www.apple.com/ru/safari/download/">Safari</a>.
			</td>
		</tr>
		</table>
		<div class="clear"></div>
		<!-- ENDIF -->
	<!--<div class="copyright" align="center">
	<font color="#FF0000" style="font-weight:bold;">!!! ВНИМАНИЕ !!!</font><br />
	Сайт не предоставляет электронные версии произведений, а занимается лишь коллекционированием и каталогизацией ссылок, присылаемых и публикуемых на форуме нашими читателями. Если вы являетесь правообладателем какого-либо представленного материала и не желаете чтобы ссылка на него находилась в нашем каталоге, свяжитесь с нами и мы незамедлительно удалим её. Файлы для обмена на трекере предоставлены пользователями сайта, и администрация не несёт ответственности за их содержание. Просьба не заливать файлы, защищенные авторскими правами, а также файлы нелегального содержания!
	</div>-->
	<!--/page_footer -->

	</div>
	<!--/page_container -->

<!-- ENDIF -->

<!-- IF ONLOAD_FOCUS_ID -->

<script type="text/javascript">
$p('{ONLOAD_FOCUS_ID}').focus();
</script>

<!-- ENDIF -->
</div>
