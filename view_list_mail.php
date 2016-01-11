<?
/* ВЫВОД СПИСКОВ ПИСЕМ ИЗ БД по значению($page). От ее значения письма выводятся из разных табл.БД :
	(inboxes)- ВХОДЯЩИЕ
	(outboxes)- ИСХОДЯЩИЕ
	(trashboxes)- КОРЗИНА
*/

$result= $O_mail->f_getMail($name_table, $select, $select_name); // в $result ложим выборку всех имеющихся входящих писем в БД в виде асс.массива
									// $name_table - имя таблицы БД из которой делать выборку(заходит автоматом на index.php блпгодаря скрипту в инклюженном route_mcontent.php)
									// $select - значение из крутилки(<select>),в зависимости от чего и формируем критерий выборки из БД
//print_r($result);
if(!is_array($result)) { echo $err_msg = "Произошла ошибка при выводе записей(!) &nbsp"; } // если в $result не массив
else {
	$cnt= count($result); //можно и так $O_mail->_db->affected_rows, НО если только св-во Kласса $_db - public !

	foreach($result as $item){
		$id= $item['id']; // id записи по порядку
		$name= $item['name']; // имя 
		$email= $item['email']; // почта
		$theme= $item['theme']; // тема письма
		$text= nl2br($item['text']); // текст письма
		$dt= date("d-m-Y H:i:s",$item['datetime']*1); // timestamp преобразованный по формату в Дата-Время
		
		$my_name= cl_Mail::$from_name; // собственное имя переливаем из св-ва Класса
		$my_email= cl_Mail::$from;  // собственный e-mail переливаем из св-ва Класса

//когда мы на ВХОДЯЩИХ письмах,- строка "Отправитель"- Имя и Почта приходящие данные, потом строка "Получатель"- свои данные	
		if($page=='') {  // modal_windows/modal_window_view.php
			echo <<<END
				<tr> <td> <input type='checkbox' name='chbox_input[]' id="chbox_input" value="$id"> </td> 	
					<td> $name <br> <a href="$email"> $email </a></td>
					<td> $my_name <br> <a href="$my_email"> $my_email </a></td> 
					<td> <br> <a href="{$_SERVER['SCRIPT_NAME']}?page=input?&id=$id" > $theme</a> </td> 
					<td> <br> $dt </td> 
				</tr>
END;
		} 
//когда мы на ИСХОДЯЩИХ письмах,- строка "Отправитель"- свои данные, потом строка "Получатель"- Имя и Почта приходящие данные
		elseif($page=='output') {
			echo <<<END
				<tr> <td> <input type='checkbox' name='chbox_input[]' id="chbox_input" value="$id"> </td> 	
					<td> $my_name <br> <a href="$my_email"> $my_email </a></td>
					<td> $name <br> <a href="$email"> $email </a></td>
					<td> <br> <a href="{$_SERVER['SCRIPT_NAME']}?page=$page?&id=$id" data-toggle="modal" > $theme</a> </td> 
					<td> <br> $dt </td>  
				</tr>
END;
		} 
//когда мы в КОРЗИНЕ. В БД в табл.(trashboxes),когда попадают письма из ВХОДЯЩ.-там есть поле(name).Когда же туда идет письмо из ИСХОД.,поле(name) НЕТУ,- оно NULL
 // Поэтому тут выводим с еще одним, доп.условием: если поле(name)==NULL,а значит и $name куда мы кладем его значение,то мы кего вообще не выводим		
		elseif($page=='trash') { 

			if($name==NULL){ // т.е. если $name==NULL,значит тут письмо из табл.(outboxes)-ИСХОДЯЩИЕ,-значит это поле вообще убираем из вывода!
							
			echo <<<END
				<tr> <td> <input type='checkbox' name='chbox_input[]' id="chbox_input" value="$id"> </td> 	
					<td> $my_name <br> <a href="$my_email"> $my_email </a></td>
					<td><br> <a href="$email"> $email </a></td>
					<td> <br> <a href="{$_SERVER['SCRIPT_NAME']}?page=$page?&id=$id" data-toggle="modal" > $theme</a> </td> 
					<td> <br> $dt </td> 
				</tr>
END;
			}
			else {   // иначе(т.е. в поле(name) есть значение),то тут письмо из табл.(inboxes)-ВХОДЯЩИЕ,-выводим как для случая ВХОДЯЩИХ,когда $page==''
				echo <<<END
				<tr> <td> <input type='checkbox' name='chbox_input[]' id="chbox_input" value="$id"> </td> 	
					<td> $name <br> <a href="$email"> $email </a></td>
					<td> $my_name <br> <a href="$my_email"> $my_email </a></td>
					<td> <br> <a href="{$_SERVER['REQUEST_URI']}?page=$page?&id=$id" data-toggle="modal" > $theme</a> </td> 
					<td> <br> $dt </td> 
				</tr>
END;
			}
		}

	} // END foreach   href="{$_SERVER['SCRIPT_NAME']}?page=$page&id=$id
}
?>