<?
// BLOCK: Отправка письма: START

	// если в поля формы что-то введено и они не пустые
if( isset($_POST['email_to']) && !empty($_POST['email_to']) &&
	isset($_POST['theme']) && !empty($_POST['theme']) &&
	isset($_POST['text_email']) && !empty($_POST['text_email'])
	) {
	// Фильтруем полученные данные
	$email_to= $O_mail->f_clearData($_POST['email_to'],'string_to_db_prepare');
	$theme= $O_mail->f_clearData($_POST['theme'],'string_to_db_prepare');
	$text_email= $O_mail->f_clearData($_POST['text_email'],'string_to_db_prepare');
	$date_format= date("d-m-Y",time()); // для того,чтобы в БД шла форматир.дата отправки письма, но без времени
	$dt= time(); // timestamp

	if (!$O_mail->f_sendMail($email_to, $theme, $text_email, $dt)) { echo $err_msg = "Произошла ошибка при отправке письма(!) &nbsp"; } // если ф-я НЕ отработала(т.е.у этого выражения FALSE)
	if (!$O_mail->f_saveMail($email_to, $theme, $text_email, $date_format, $dt)) { echo $err_msg = "Произошла ошибка при добавлении отправленного письма в БД(!) &nbsp"; } // если ф-я НЕ отработала(т.е.у этого выражения FALSE)
	header('Location: ' . $_SERVER['REQUEST_URI']); exit; //перезапрос с переходом на основную стр.(index.php)
}
else{ echo $err_msg = "Заполните все поля формы(!) &nbsp";}

// BLOCK: Отправка письма: END
?>