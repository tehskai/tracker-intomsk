<?php

###############
$url = "http://109.124.45.20/";

$rw_cover = array (
						"name" => "Обложка", 
						"type" => "image", 
						"multiple" => 0, 
						"hide_name" => 1, 
						"gallery_type" => 1, 
						"right" => true,
						);

$rw_mark = array (
	"name" => "Личная оценка",
	"type" => "select",
	"values" => array (
		"10 из 10",
		"9 из 10",
		"8 из 10",
		"7 из 10",
		"6 из 10",
		"5 из 10",
		"4 из 10",
		"3 из 10",
		"2 из 10",
		"1 из 10",
		"0 из 10"
	),
	"after_value" => "\n",
);/*
$rw_screenshots2 = 
		array (
				"name" => "Скриншоты",
				"type" => "text", 
				"multiple" =>  3, 
				"before_value" => "[spoiler=\"Скриншоты\"][img]", 
				"after_value" => "[/img][/spoiler]", 
				"hide_name" => 1, 
				); */
$rw_screenshots = 
		array (
				"name" => "Скриншоты",
				"type" => "image", 
				"multiple" =>  3, 
				"before_value" => "[spoiler=\"Скриншоты\"][align=center]", 
				"after_value" => "[/align][/spoiler]", 
				"hide_name" => 1, 
				); 
/*
array (
				"name" => "Скриншоты",
				"type" => "image", 
				"multiple" =>  3, 
				"before_value" => "[spoiler]", 
				"after_value" => "[/spoiler]", 
				"hide_name" => 1, 
				);*/

$rw_game_type = array (
	"name" => "Тип издания",
	"type" => "select",
	"values" => array (
				"Лицензия",
				"Пиратка",
				"Репак",
	),
	"after_value" => "\n",
	"group_before_name" => "",
	"group_after_name"  => ": ",
);
$rw_rep = array (
	"name" => "От кого",
	"type" => "text",
	"comment" => "Если репак от кого",
	"after_value" => "\n",
	"group_before_name" => "",
	"group_after_name"  => ": ",
);
$rw_game_lang = array (
	"name" => "Язык интерфейса",
	"type" => "select",
	"values" => array (
				"английский + русский",
				"только английский",
				"Только русский",
				"Немецкий",
	),
	"after_value" => "\n",
	"group_before_name" => "",
	"group_after_name"  => ": ",
);
$rw_audio_lang = array (
	"name" => "Озвучка",
	"type" => "select",
	"values" => array (
				"Русская",
				"Английская",
	),
	"after_value" => "\n",
	"group_before_name" => "",
	"group_after_name"  => ": ",
);
$rw_multyplaer = array (
	"name" => "Мультиплеер",
	"type" => "select",
	"values" => array (
				"Присутствует",
				"Отсутствует",
	),
	"after_value" => "\n",
	"group_before_name" => "",
	"group_after_name"  => ": ",
);

$rw_tabletka = array (
	"name" => "Таблэтка",
	"type" => "select",
	"values" => array (
				"Присутствует",
				"Отсутствует",
				"Не требуется",
	),
	"after_value" => "[hr]\n",
	"group_before_name" => "",
	"group_after_name"  => ": ",
);
/*----EnD FoR GameS----*/

/*----FoR VideO----*/

$rw_video_codec = array (
	"name" => "кодек",
	"type" => "select",
	"values" => array (
		"DivX",
		"XviD",
		"MPEG",
		"ASF",
		"WMV",
		"MKV",
	),
	"group_before_name" => "",
	"group_after_name"  => " : ",
);

$rw_video_quality = array (
	"name" => "Качество видео",
	"type" => "select",
	"values" => array (
		"HDTV",
		"DVDRip",
		"DVDScr",
		"Scr",
		"SatRip",
		"TVRip",
		"TS",
		"CAMRip",
	),
	"comment" => "( <a href=\"#\" onclick=\"return wopen('http://www.192.168.111.2/forum/viewtopic.php?p=964#964', 740, 450)\">расшифровка сокращений</a> )"
);


$rw_video_bitrate = array (
	"name" => "битрейт",
	"type" => "text",
	"default" => " кб/с",
	"example" => "1234 кб/с",
	"size" => 20,
	"group_before_name" => "",
	"group_after_name"  => " : ",
);

$rw_video_size = array (
	"name" => "размер кадра",
	"type" => "text",
	"example" => "768 х 576",
	"size" => 20,
	"group_before_name" => "",
	"group_after_name"  => ": ",
);

$rw_audio_codec = array (
	"name" => "кодек",
	"type" => "select",
	"values" => array (
		"MP3",
		"AC3 2.0",
		"AC3 5.1",
		"WMA",
		"PCM",
	),
	"group_before_name" => "",
	"group_after_name"  => ": ",
);

$rw_audio_language = array (
	"name" => "язык",
	"type" => "select",
	"values" => array (
		    "Русский",
        	"Украинский",
	        "Английский",
	        "Немецкий",
	        "Французский",
    		"Итальянский",
	        "Финский",
	        "Чешский",
	        "Польский",
	        "Китайский",
	        "Японский",
	        "Корейский",
	),
	"group_before_name" => "",
	"group_after_name"  => ": ",
);

$rw_audio_translation = array (
	"name" => "перевод",
	"type" => "select",
	"values" => array (
		"оригинал",
	        "профессиональный дублированный",
	        "многоголосый закадровый",
        	"двухголосый закадровый",
	        "одноголосый закадровый",
    		"гоблин",
	),
	"after_value" => "",
	"group_before_name" => "",
	"group_after_name"  => ": ",
);



$rw_audio_bitrate = array (
	"name" => "битрейт",
	"type" => "text",
	"default" => " кб/с",
	"example" => "96 кб/с VBR",
	"size" => 20,
	"group_before_name" => "",
	"group_after_name"  => ": ",
);


$rw_story = array (
	"name" => "Сюжет фильма",
	"type" => "textarea",
	"before_value" => "",
	"after_value" => "\n\n",
);

$rw_length = array (
	"name" => "Продолжительность",
	"type" => "text",
	"default" => "чч:мм:сс",
	"example" => "01:23:12"
);

/*----EnD FoR VideO*/

//include($phpbb_root_path .'posting.php');
$fid2 = isset($_REQUEST['fid']) ? $_REQUEST['fid'] : '';

$config = array (
	'ardent-news' => array (
		"title" => "Релиз Горячих Новинок",
		"forum_id" => $fid2,
		"subject_after" => "(Название игры (язык игры) [тип издания])",
		"subject_example" => "Например, \"Dungeon siege III (ENG/Multi5) [L]\"",
		"before_value" => "[font=\"Comic Sans MS\"][size=20][b][align=center]",
		"after_value" => "[/align][/b][/size][/font][hr]\n",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видеокарта[/i]: \n[i]Звуковая карта[/i]: \n[i]Свободное место на жостком диске[/i]: ",
				"after_value" => "[hr]\n",
					),
			$rw_game_type,
			$rw_rep,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),


	'games' => array (
		"title" => "Релиз игр для PC",
		"forum_id" => $fid2,

		"subject_after" => "Название игры",
		"subject_example" => "Например, \"Dungeon siege III (ENG/Multi5) [L]\"",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
			$rw_game_type,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Операционная система[/i]: \n[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видео карта[/i]: \n[i]Версия Direct X[/i]: \n[i]CD / DVD Rom[/i]: \n[i]HDD[/i]:",
				"after_value" => "\n",
					),
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),


	'xbox' => array (
		"title" => "Релизы для Xbox",
		"forum_id" => $fid2,
		"subject_after" => "Название игры",
		"subject_example" => "Например, \"Dungeon siege III (ENG/Multi5) [L]\"",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
			$rw_game_type,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Операционная система[/i]: \n[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видео карта[/i]: \n[i]Версия Direct X[/i]: \n[i]HDD[/i]:", //[i]CD / DVD Rom[/i]: \n
				"after_value" => "\n",
					),
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),


	'playstation' => array (
		"title" => "Релизы PlayStation",
		"forum_id" => $fid2,
		"subject_after" => "Название игры",
		"subject_example" => "Например, \"Dungeon siege III (ENG/Multi5) [L]\"",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
			$rw_game_type,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Операционная система[/i]: \n[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видео карта[/i]: \n[i]Версия Direct X[/i]: \n[i]CD / DVD Rom[/i]: \n[i]HDD[/i]:",
				"after_value" => "\n",
					),
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),


	'nintendo' => array (
		"title" => "Релизы для Nintendo",
		"forum_id" => $fid2,
		"subject_after" => "Название игры",
		"subject_example" => "Например, \"Dungeon siege III (ENG/Multi5) [L]\"",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
			$rw_game_type,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Операционная система[/i]: \n[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видео карта[/i]: \n[i]Версия Direct X[/i]: \n[i]CD / DVD Rom[/i]: \n[i]HDD[/i]:",
				"after_value" => "\n",
					),
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),


	'console' => array (
		"title" => "Релиз Игры для старых консолей",
		"forum_id" => $fid2,
		"subject_after" => "Название игры ",
		"subject_example" => "Например, \"Dungeon siege III (ENG/Multi5) [L]\"",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
			$rw_game_type,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Операционная система[/i]: \n[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видео карта[/i]: \n[i]Версия Direct X[/i]: \n[i]CD / DVD Rom[/i]: \n[i]HDD[/i]:",
				"after_value" => "\n",
					),
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),


/*
	'video-psp' => array (
		"title" => "Релиз Видео для PSP",
		"forum_id" => $fid2,
		"subject_after" => "Название видео",
		"subject_example" => "Например, \"28 day later/ 2\"",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
			$rw_game_type,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Операционная система[/i]: \n[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видео карта[/i]: \n[i]Версия Direct X[/i]: \n[i]CD / DVD Rom[/i]: \n[i]HDD[/i]:",
				"after_value" => "\n",
					),
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),

	'video-ps3-xbox360' => array (
		"title" => "Релиз Видео для PS3 & Xbox360",
		"forum_id" => $fid2,
		"subject_after" => "Название игры",
		"subject_example" => "Например, \"Dungeon siege III (ENG/Multi5) [L]\"",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
			$rw_game_type,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Операционная система[/i]: \n[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видео карта[/i]: \n[i]Версия Direct X[/i]: \n[i]CD / DVD Rom[/i]: \n[i]HDD[/i]:",
				"after_value" => "\n",
					),
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),*/

	'video-psp' => array (
		"title" => "Релиз фильма",
		"forum_id" => $fid2,
		"right"  => true,
#		"max_w" => 350,
#		"max_h" => 500,
		"subject_after" => "На русском / На оригинальном языке (Год) Качество",
		"subject_example" => "13 друзей Оушена / Ocean's 13 (2007) DVDRip",
		"before_value" => "[size=20]",
		"after_value" => "[/size]\n\n",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Жанр",
				"type" => "variant",
				"example" => "Комедия",
				"values" => array( "анимация", "антиутопия", "биография", "боевик", "вестерн", "военный", "детектив", "документальный", "драма", "истерн", "история", "каратэ", "катастрофа", "киберпанк", "комедия", "криминал", "мелодрама", "мистика", "мьюзикл", "пародия", "приключения", "саспенс", "семейный", "сказка", "спектакль", "спорт", "трагедия", "трагикомедия", "триллер", "ужасы", "фантастика", "фэнтези", "черная комедия", "экстрим", "эротика" ),
				"columns" => 5,
                ),
     			array (
				"name" => "Страна",
				"type" => "text",
				"example" => "Россия",
			),
			array (
				"name" => "Режиссер",
				"type" => "text",
			),
			array (
				"name" => "В ролях",
				"type" => "text",
                "after_value" => "\n\n",
			),
			$rw_story,
			$rw_mark,
			$rw_length,
			$rw_video_quality, 
			
			// --- video group -------
			array (
				"group" => true,
				"name" => "Видео",
			),
			$rw_video_codec, 
			$rw_video_size, 
			$rw_video_bitrate, 
			array (
				"group" => false
			),
			// -----------------------

			// --- audio group -------
			array (
				"group" => true,
				"name" => "Аудио",
				"multiple" => 1,
			),
			$rw_audio_language,
			$rw_audio_translation,
			$rw_audio_codec,
			$rw_audio_bitrate,
			array (
				"group" => false
			),
			// -----------------------

			$rw_screenshots,

		  ),
	),

	'video-ps3-xbox360' => array (
		"title" => "Релиз фильма",
		"forum_id" => $fid2,
		"right"  => true,
#		"max_w" => 350,
#		"max_h" => 500,
		"subject_after" => "На русском / На оригинальном языке (Год) Качество",
		"subject_example" => "13 друзей Оушена / Ocean's 13 (2007) DVDRip",
		"before_value" => "[size=20]",
		"after_value" => "[/size]\n\n",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Жанр",
				"type" => "variant",
				"example" => "Комедия",
				"values" => array( "анимация", "антиутопия", "биография", "боевик", "вестерн", "военный", "детектив", "документальный", "драма", "истерн", "история", "каратэ", "катастрофа", "киберпанк", "комедия", "криминал", "мелодрама", "мистика", "мьюзикл", "пародия", "приключения", "саспенс", "семейный", "сказка", "спектакль", "спорт", "трагедия", "трагикомедия", "триллер", "ужасы", "фантастика", "фэнтези", "черная комедия", "экстрим", "эротика" ),
				"columns" => 5,
                ),
     			array (
				"name" => "Страна",
				"type" => "text",
				"example" => "Россия",
			),
			array (
				"name" => "Режиссер",
				"type" => "text",
			),
			array (
				"name" => "В ролях",
				"type" => "text",
                "after_value" => "\n\n",
			),
			$rw_story,
			$rw_mark,
			$rw_length,
			$rw_video_quality, 
			
			// --- video group -------
			array (
				"group" => true,
				"name" => "Видео",
			),
			$rw_video_codec, 
			$rw_video_size, 
			$rw_video_bitrate, 
			array (
				"group" => false
			),
			// -----------------------

			// --- audio group -------
			array (
				"group" => true,
				"name" => "Аудио",
				"multiple" => 1,
			),
			$rw_audio_language,
			$rw_audio_translation,
			$rw_audio_codec,
			$rw_audio_bitrate,
			array (
				"group" => false
			),
			// -----------------------

			$rw_screenshots,

		  ),
	),


	'online-games' => array (
		"title" => "Релиз сетевых игр",
		"forum_id" => $fid2,
		"subject_after" => "Название игры",
		"subject_example" => "Например, \"Dungeon siege III (ENG/Multi5) [L]\"",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Год выпуска",
				"type" => "text",
				"comment" => "Например, \"2006\"",
				"default" => "2008",
			),
			array (
				"name" => "Жанр",
				"type" => "text",
				"comment" => "Например, \"Arcade / 3D / 3rd Person\"",
				"default" => "",
			),
			array (
				"name" => "Разработчик",
				"type" => "text",
				"comment" => "Например, \"Primal Software (Россия)\"",
				"default" => "",
			),
						array (
				"name" => "Издательство",
				"type" => "text",
				"comment" => "Например, \"Акелла\"",
				"default" => "",
			),
			$rw_game_type,
			$rw_game_lang,
			$rw_audio_lang,
			$rw_tabletka,
						array (
				"name" => "Системные требования",
				"type" => "textarea",
				"before_value" => "\n",
				"default" => "[i]Операционная система[/i]: \n[i]Процессор[/i]: \n[i]Оперативная память[/i]: \n[i]Видео карта[/i]: \n[i]Версия Direct X[/i]: \n[i]CD / DVD Rom[/i]: \n[i]HDD[/i]:",
				"after_value" => "\n",
					),
			$rw_multyplaer,
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			array (
				"name" => "Доп. информация",
				"type" => "textarea",
				"before_value" => "\n",
				"after_value" => "\n",
					),
			$rw_mark,
			$rw_screenshots,
		),
	),

	'programm' => array (
		"title" => "Релиз софта",
		"forum_id" => $fid2,
#		"max_w" => 350,
#		"max_h" => 500,
		"subject_after" => "Название ПО, версия",
		"subject_example" => "Farstone.Virtual.Drive.PRO.10.Retail.WinALL-KYA",
		"fields" => array (
			$rw_cover,
			array (
				"name" => "Версия в раздаче",
				"type" => "text",
				"comment" => "Обязательно указывайте, если версия - бета",
				"before_name" => "[color=darkgreen][b]",
				"after_name" => ": ",
				"after_value" => "[/b][/color]\n",
			),
			array (
				"name" => "Последняя версия",
				"type" => "text",
				"comment" => "На момент релиза",
				"before_name" => "[color=darkgreen][b]",
				"after_name" => ": ",
				"after_value" => "[/b][/color]\n\n",
			),
			array (
				"name" => "Адрес официального сайта",
				"type" => "text",
				"before_value" => "[url]",
				"after_value" => "[/url]",
			),
			array (
				"name" => "Описание",
				"type" => "textarea",
				"before_name" => "\n\n[b]",
                "after_name" => "[/b]\n",
				"before_value" => "\n",
				"after_value" => "\n\n",
	    		),
			$rw_screenshots,

		),
	),
	
);


?>
