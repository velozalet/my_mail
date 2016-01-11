<?
header("Content-type:text/html; charset=utf-8"); // кодировка проекта
ob_start();  // cтартуем буфферизацию контента
function __autoload($name_class) { require "classes/$name_class.php"; } // ф-я,которая автоматом будет подгружать нужный класс при создании соответств.объекта Кл.

$err_msg= ''; // для вывода пользовательской ошибки 

$O_mail= new cl_Mail(); // Объект Класса для работы  почтового Мини-портала
$O_connDB= new cl_connectDB(); // Объект Класса подключения к БД MySQL

//если есть $page,отправленная(по_GET),принимаем,прогоняем по нижнему регистру(по ней идет роут гл.Контента,Имя БД,Надписей- см.(route_mcontent.php) )
if(isset($_GET['page'])) { $page= $O_mail->f_clearData($_GET['page'],'string_to_lower'); }

// Динамика ПОДСТАНОВКИ НУЖНОГО ИМЕНИ ТАБЛИЦЫ БД в ($name_table)     
switch($page): case 'output': $name_table= 'outboxes'; break;
			   case 'trash':  $name_table= 'trashboxes'; break; 
			   default: $name_table= 'inboxes';				   
endswitch;

//если была отправлена Форма Письма(интерфейсом Модали) - подключаем обработчик отправки письма
if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['btn_modal'])) { require "send_mail.prc.php"; } 

// если был POST из Формы (<select>) на сортировку писем по критерию даты
if($_SERVER['REQUEST_METHOD']=="POST"  && isset($_POST['select'])) { 
	$select= $_POST['select']; 
	$select_form= $_POST['select_form'];
}

// если был POST из Формы (<select>) на сортировку писем по критерию Имени отправителя
if($_SERVER['REQUEST_METHOD']=="POST"  && isset($_POST['select_name'])) { 
	$select_name= $_POST['select_name']; 
	$select_form_name= $_POST['select_form_name'];
}

//если была отправлена Форма(нажата Кнопка) на УДАЛЕНИЕ выбранных писем по их(id), подключаем обработчик перемещения/удаления выбранных писем 
if($_SERVER['REQUEST_METHOD']=="POST"  && isset($_POST['btn_modal_del']) && !isset($_POST['chbox_input'])) { $change_del="(!) Выберите письма для удаления";}
if($_SERVER['REQUEST_METHOD']=="POST"  && isset($_POST['btn_modal_del']) && isset($_POST['chbox_input'])) { require "resave_mail.prc.php";} 
?>