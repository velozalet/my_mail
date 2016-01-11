<?
// Пересохранение писем из списков ВХОДЯЩИЕ/ ИСХОДЯЩИЕ в КОРЗИНУ и удаление писем,если удаление произвелось с КОРЗИНЫ (!)

if(isset($_POST['chbox_input'])) { $chbox_input= $_POST['chbox_input'];}  // если есть хоть один чекбокс,значит значения id для удаления писем переданы

if(!is_array($chbox_input)) { return FALSE;} // пришел не массив,- возвращаем FALSE
else { // если тут,- значит массив есть 
	$id_string= implode(",", $chbox_input);  //Объединяем элементы в пришедшем Массиве в строку для дальнейшей работы с нею
		
		if($page==='trash') {  //случай когда мы находимся в КОРЗИНЕ - письма именно на удаление
			if (!$result_set= $O_mail->f_deleteMail($name_table,$id_string)) { echo $err_msg = "Произошла ошибка при удалении письма(ем)(!) &nbsp"; } // если ф-я НЕ отработала(т.е.у этого выражения FALSE)
			header('Location: ' . $_SERVER['REQUEST_URI']); exit; // перезапрос с переходом на основную стр.(index.php)
		}

		else {   //случай когда мы не в КОРЗИНЕ (ВХОД./ ИСХОД.)- письма на перемещение в корзину, а потом удаление
			if (!$result_set= $O_mail->f_resaveMail($name_table,$id_string)) { echo $err_msg = "Произошла ошибка при удалении письма(ем)(!) &nbsp"; } // если ф-я НЕ отработала(т.е.у этого выражения FALSE)
			if (!$result_set2= $O_mail->f_deleteMail($name_table,$id_string)) { echo $err_msg = "Произошла ошибка при удалении письма(ем)(!) &nbsp"; } // если ф-я НЕ отработала(т.е.у этого выражения FALSE)
			header('Location: ' . $_SERVER['REQUEST_URI']); exit; // перезапрос с переходом на основную стр.(index.php)
		}
}
