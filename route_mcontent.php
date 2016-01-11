<? 
// РОУТЕР ОСНОВНОГО КОНТЕНТА по($page) из мет.($_GET). Переключение в левом НавБаре:

require 'view_list_mail.php'; // инклюдим страницу со скриптом-шаблоном вывода писем в зависимости от($page) и ($select)
	
// Динамика вывода НАЗВАНИЯ СПИСКА ВЫВОДА писем: ВХОДЯЩИЕ/ИСХОДЯЩИЕ/КОРЗИНА 
switch($page):  //изменяем значение($name_folder),где лежит надпись выводимого в данный момент списка писем(ВХОДЯЩИЕ/ОТПРАВЛЕНН./ КОРЗИНА),по $page,приходящей по_GET на index.php
	case 'output': echo $name_folder='ОТПРАВЛЕННЫЕ ПИСЬМА ('.$cnt.')'; break;
	case 'trash': echo $name_folder='КОРЗИНА ('.$cnt.')'; break;
	default: echo $name_folder='ВХОДЯЩИЕ ПИСЬМА ('.$cnt.')';
endswitch;

// если был передан парам.(id) через _GET - это запрос на вывод целиком отдельного письма. Инклюдим файл-шаблон для вывода (view_textmail.php)
if(isset($_GET['id'])) { 
	$id= $O_mail->f_clearData($_GET['id'],'integer');
	require "view_textmail.php";
}