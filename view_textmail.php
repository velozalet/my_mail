<?
	// Динамика ПОДСТАНОВКИ НУЖНОГО ИМЕНИ ТАБЛИЦЫ БД в ($name_table) именно в таком виде для этой страницы и показа полного текста письма  
switch($page): case 'input?': $name_table= 'inboxes'; break; 
			   case 'output?': $name_table= 'outboxes'; break;
			   case 'trash?':  $name_table= 'trashboxes'; break; 
endswitch; 

// ВЫВОД ОПРЕДЕЛЕННОГО письма по его(id),кот.цепляется мет.GET (см. view_list_mail.php)

$result= $O_mail->f_getTextMail($name_table, $id); // в $result ложим выборку(конкретное 1 письмо) из соотв.табл.в БД в виде асс.массива
									// $name_table - имя таблицы БД из которой делать выборку(заходит автоматом на index.php блпгодаря скрипту в инклюженном route_mcontent.php)

	foreach($result as $item){
		//$id= $item['id']; // id записи по порядку
		$name= $item['name']; // имя 
		$email= $item['email']; // почта
		$theme= $item['theme']; // тема письма
		$text= nl2br($item['text']); // текст письма
		$dt= date("d-m-Y H:i:s",$item['datetime']*1); // timestamp преобразованный по формату в Дата-Время
		
		$my_name= cl_Mail::$from_name; // собственное имя переливаем из св-ва Класса
		$my_email= cl_Mail::$from;  // собственный e-mail переливаем из св-ва Класса

//когда мы на ВХОДЯЩИХ письмах,- строка "Отправитель"- Имя и Почта приходящие данные, потом строка "Получатель"- свои данные	
		if($page=='input?') { 
			echo <<<END
				<tr> <td> </td> 	
					<td> $name <br> <a href="$email"> $email </a></td>
					<td> $my_name <br> <a href="$my_email"> $my_email </a></td> 
					<td> <br>  $theme </td> 
					<td> <br> $dt </td> 
				</tr>
				<tr> <td colspan="5"> $text </td> </tr> 
END;
		} 
//когда мы на ИСХОДЯЩИХ письмах,- строка "Отправитель"- свои данные, потом строка "Получатель"- Имя и Почта приходящие данные
		elseif($page=='output?') { 
			echo <<<END
				<tr> <td> </td> 	
					<td> $my_name <br> <a href="$my_email"> $my_email </a></td>
					<td> $name <br> <a href="$email"> $email </a></td>
					<td> <br>  $theme </td> 
					<td> <br> $dt </td>  
				</tr>
				<tr> <td colspan="5"> $text </td> </tr> 
END;
		} 
//когда мы в КОРЗИНЕ. В БД в табл.(trashboxes),когда попадают письма из ВХОДЯЩ.-там есть поле(name).Когда же туда идет письмо из ИСХОД.,поле(name) НЕТУ,- оно NULL
 // Поэтому тут выводим с еще одним, доп.условием: если поле(name)==NULL,а значит и $name куда мы кладем его значение,то мы кего вообще не выводим		
		elseif($page=='trash?') { 

			if($name==NULL){ // т.е. если $name==NULL,значит тут письмо из табл.(outboxes)-ИСХОДЯЩИЕ,-значит это поле вообще убираем из вывода!
							
			echo <<<END
				<tr> <td> </td> 	
					<td> $my_name <br> <a href="$my_email"> $my_email </a></td>
					<td><br> <a href="$email"> $email </a></td>
					<td> <br>  $theme </td>  
					<td> <br> $dt </td> 
				</tr>
				<tr> <td colspan="5"> $text </td> </tr> 
END;
			}
			else {   // иначе(т.е. в поле(name) есть значение),то тут письмо из табл.(inboxes)-ВХОДЯЩИЕ,-выводим как для случая ВХОДЯЩИХ,когда $page==''
				echo <<<END
				<tr> <td> </td> 	
					<td> $name <br> <a href="$email"> $email </a></td>
					<td> $my_name <br> <a href="$my_email"> $my_email </a></td>
					<td> <br>  $theme </td>  
					<td> <br> $dt </td> 
				</tr>
				<tr> <td colspan="5"> $text </td> </tr> 
END;
			}
		}

	} // END foreach
?>