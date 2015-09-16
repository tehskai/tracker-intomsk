<?php
/**************************************************************
*
*  Название Мода :  Complete banner
*  Версия Мода   :  1.2.0.
*  Перевод      :  Русский
*  Дата выпуска  :  10/12/2003
*
*
*
***************************************************************/

// this is the text showen in admin panel, depending on your template layout,
// you may change the text, so this reflect the placement in the templates
// these are only exampels, you may add more or remove some of them.

$lang['Banner_spot']['0'] = "Баннер в шапке"; // used for {BANNER_0_IMG} tag in the template files
$lang['Banner_spot']['1'] = "Вверху слева 1"; // used for {BANNER_1_IMG} tag in the template files
$lang['Banner_spot']['2'] = "Вверху слева 2"; // used for {BANNER_2_IMG} tag in the template files
$lang['Banner_spot']['3'] = "Вверху в центре 1"; // used for {BANNER_3_IMG} tag in the template files
$lang['Banner_spot']['4'] = "Вверху в центре 2"; // used for {BANNER_4_IMG} tag in the template files
$lang['Banner_spot']['5'] = "Вверху справа 1"; // used for {BANNER_5_IMG} tag in the template files
$lang['Banner_spot']['6'] = "Вверху справа 2"; // used for {BANNER_6_IMG} tag in the template files
$lang['Banner_spot']['7'] = "Внизу слева 1"; // used for {BANNER_7_IMG} tag in the template files
$lang['Banner_spot']['8'] = "Внизу слева 2"; // used for {BANNER_8_IMG} tag in the template files
$lang['Banner_spot']['9'] = "Внизу в центре 1"; // used for {BANNER_9_IMG} tag in the template files
$lang['Banner_spot']['10'] = "Внизу в центре 2"; // used for {BANNER_10_IMG} tag in the template files
$lang['Banner_spot']['11'] = "Внизу справа 1"; // used for {BANNER_11_IMG} tag in the template files
$lang['Banner_spot']['12'] = "Внизу справа 2"; // used for {BANNER_12_IMG} tag in the template files
$lang['Banner_spot']['13'] = "В форумах сверху"; // used for {BANNER_13_IMG} tag in the template files
$lang['Banner_spot']['14'] = "В темах сверху"; // used for {BANNER_14_IMG} tag in the template files
$lang['Banner_spot']['15'] = "В темах снизу"; // used for {BANNER_15_IMG} tag in the template files
$lang['Banner_spot']['16'] = "Портал 1"; // used for {BANNER_16_IMG} tag in the template files
$lang['Banner_spot']['17'] = "Портал 2"; // used for {BANNER_17_IMG} tag in the template files
$lang['Banner_spot']['18'] = "Портал 3"; // used for {BANNER_18_IMG} tag in the template files
$lang['Banner_spot']['19'] = "Портал 4"; // used for {BANNER_18_IMG} tag in the template files

//
// пожалуйста, не меняйте код ниже (если Вы конечно, не переводчик)
//
$lang['Banner_title'] = "Управление баннерами";
$lang['Banner_text'] = "<center>Отсюда Вы можете управлять баннерами, которые отображаются на Вашем форуме.</center>";
$lang['Add_new_banner'] = "Новый баннер";
$lang['Banner_add_text'] = "Здест Вы можете удалять или добавлять баннеры";

$lang['Banner_example']="Пример";
$lang['Banner_example_explain'] ="Так будет выглядеть новый баннер";
$lang['Banner_type_text'] = "Тип";
$lang['BANNER_TYPE_EXPLAIN'] = "Выберите тип баннера";

//Предопределённые типы
$lang['Banner_type'][0] = "картинка";
$lang['Banner_type'][2] = "текстовая ссылка";
$lang['Banner_type'][4] = "HTML код";
$lang['Banner_type'][6] = "Флэш-анимация";

$lang['Banner_name'] = "Код баннера";
$lang['Banner_name_explain'] = "Полный путь (включая http://)";
$lang['Banner_size'] = "Размер баннера";
$lang['Banner_size_explain'] = "Если Вы не установите размеры вручную, то размер баннера останется по умолчанию";
$lang['Banner_width'] = "Ширина";
$lang['Banner_height'] = "Высота";

$lang['Banner_activated'] = "Активный";
$lang['Banner_activate'] = "Активировать баннер";
$lang['Banner_comment'] = "Комментарий";
$lang['Banner_description'] = "Описание баннера";
$lang['Banner_description_explain'] = "Этот текст будет виден при наведении на баннер указателя мыши";
$lang['Banner_url'] = "Редирект";
$lang['Banner_url_explain'] ="Ссылка на сайт должна начинаться с HTTP://<br />(редирект будет работать только для Каринок и Текстовых ссылок)";
$lang['Banner_owner']="Модератор баннера";
$lang['Banner_owner_explain']="Этот пользователь сможет управлять баннером";
$lang['Banner_placement'] = "Расположение";
$lang['Banner_clicks'] = "Клики";
$lang['Banner_clicks_explain'] = "(Клики считаются только если баннер Картинка или Текстовая ссылка)";
$lang['Banner_view'] = "Показы";
$lang['Banner_weigth'] = "Показы текущего баннера";
$lang['Banner_weigth_explain'] = "Как часто должен быть показан этот баннер, по отношению к другим активным баннерам (1-99)";
$lang['Show_to_users'] ='Показывать пользователям';
$lang['Show_to_users_explain'] ='Укажите, кто сможет видеть баннер';
$lang['Show_to_users_select'] = 'Пользователь должен быть %s to %s'; //%s are supstituded with dropdown selections
$lang['Banner_level']['-1'] = 'Гость';
$lang['Banner_level']['0'] = 'Зарегистрированный';
$lang['Banner_level']['1'] = 'Модератор';
$lang['Banner_level']['2'] = 'Администратор';
$lang['Banner_level_type']['0'] = 'равный';
$lang['Banner_level_type']['1'] = 'меньший или равный';
$lang['Banner_level_type']['2'] = 'больший или равный';
$lang['Banner_level_type']['3'] = 'нет';

$lang['Time_interval'] = "Временные интервалы";
$lang['Time_interval_explain'] = "Здесь Вы можете установить, когда баннер будет показан";
$lang['Start'] = "Начало";
$lang['End'] = "Конец";
$lang['Year'] = "Год";
$lang['Month'] = "Месяц";
$lang['Date'] = "Число";
$lang['Weekday'] = "День";
$lang['Hour'] = "Hour";
$lang['Min'] = "Мин.";
$lang['Time_type'] = "Когда показывать";
$lang['Time_type_explain'] = "Если Вы выберите по времени/по дню недели/по дате, то Вам нужно будет указать промежуток времени в следующем меню";
$lang['Not_specify'] = "не выбран";
$lang['No_time'] = "всегда";
$lang['By_time'] = "по времени";
$lang['By_week'] = "по дню недели";
$lang['By_date'] = "по дате";

// Сообщения
$lang['Missing_banner_id'] = "id баннера пропущен.";
$lang['Missing_banner_owner'] = "Вы должны выбрать владельца баннера.";
$lang['Missing_time'] = "Если Вы устанавливаете баннер на определённое время, то Вы должны указать временной интервал.";
$lang['Missing_date'] ="Если Вы устанавливаете баннер на определённую дату, то Вы должны указать дату и временной интервал.";
$lang['Missing_week'] ="Если Вы устанавливаете баннер на определённый день недели, то Вы должны указать день недели и временной интервал.";

$lang['Banner_removed'] = "Баннер удалён";
$lang['Banner_updated'] = "Информация баннера обновлена";
$lang['Banner_added'] = "Баннер успешно добавлен";
$lang['Click_return_banneradmin'] = 'Вернуться к  %sУправлению баннерами%s';

$lang['No_redirect_error'] = 'Если страница не грузится, то нажмите <b><a href="%s" id="jumplink" name="jumplink">Здесь<a></b>, чтобы проследовать на запрашиваемый адрес (URL)';
$lang['Left_via_banner'] = 'Левый баннер';

$lang['Banner_filter'] = 'Фильтр баннеров';
$lang['Banner_filter_explain'] = 'Скрыть баннер, после того как по нему кликнет пользователь';
$lang['Banner_filter_time'] = 'Время деактивации';
$lang['Banner_filter_time_explain'] = 'Количество секунд(прошедщих после клика на нём), после которых баннер становится неактивным, если фильтр баннеров включен, то в это время баннер виден не будет';


//Add-on by Gosudar
$lang['Banner_statistic_title'] = 'Статистика';
$lang['Banner_statistic_explane'] = 'Аддон добавил Gosudar';
$lang['Banner_statistic'] = 'Статистика кликов по баннерам';
$lang['Banner_clear_statistic'] = 'Очистить таблицу статистики кликов';

?>