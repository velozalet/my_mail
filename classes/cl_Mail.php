<?
require_once "cl_Interf_Mail.php"; // Класс Интерфейса c декларацией/описанием свойств и методов работы с Мини-почтовым порталом

// КЛАСС для РАБОТЫ ПОЧТОЙ

class cl_Mail extends cl_connectDB implements cl_Interf_Mail {  // 
//СВОЙСТВА для работы с почтовым порталом:
	static $from= "myself@mail.com"; // E-mail отправителя(един для всего почтового сервера)
	static $from_name= "Jonh Smith"; // E-mail отправителя(един для всего почтового сервера)
	private $_type= "text/html"; // тип содержимого E-mail(Content-type)
	private $_encoding= "utf-8"; // тип кодировки E-mail
	//private $_notify= FALSE; // для проверки подтверждения получения письма(по надобности) ff


// МЕТОДЫ для работы почтовым порталом:

// Фильтр-очистка принимаемых данных из GET/POST  (!)_для расширения PHP(php_mysqli.dill). Started ih PHP ver.5.0_(!): */
	public function f_clearData($data,$type){  // param.:2/ $data-from METHOD GET/POST; 
											  // $type-по какому шаблону ее фильтровать(см.по case)
		switch($type){
			case 'integer': return trim(htmlspecialchars(strip_tags(abs((integer)$data)))); break; //для числа: 1)не дробное число 2)положительн. 3)без знаков "+/-"
			case 'string_to_db': return $this->_db->real_escape_string(trim(htmlspecialchars(strip_tags($data)))); break; // для строки,идущей в БД (но не методом Подготовл.Запроса)
			case 'string_to_db_prepare': return trim(htmlspecialchars(strip_tags($data))); break; // для строки,идущей в БД, методом Подготовл.Запроса
			case 'string_to_lower': return strtolower(trim(addslashes(htmlspecialchars(strip_tags($data))))); break;  // как для (case'string')+ преобразует строку в НИЖНИЙ регистр
		}  // в случае ненадобности экранировать апостроф в строке(O'Brian),- убрать addslashes,где он стоит!
	} // END f_clearData	
	
// Конвертер данных из БД($result) в асс.массив_(результат возврата запроса SQL будет конвертироваться в массив. Массив может быть пустым(!)- учесть-это не ошибка,- в БД нет данных)
	public function f_dbArray($result) { // $result- выборка из БД
		$arr_db= array(); //инициал.массив,который будем возвращать

		while($myrow= $result->fetch_array(MYSQLI_ASSOC)) { 
			$arr_db[]=$myrow; // закидываем в МАССИВ $arr_db[] посторочно данные из выборки из БД($myrow)
		}
		return $arr_db; // храним сконвертированный МАССИВ.При этом МАССИВ может быть как заполненный, так и пустой
	}// END f_dbArray


// f_sendMail - Отправка письма на почтовый сервер Адресата 
	public function f_sendMail($to, $subject, $message, $dt) { // результат отработки Ф-и: mail($to, $subject, $message, $headers) - письмо уходит на почтовый сервер
		
		$dt= date("d-m-Yг., H:i:s", $dt); // форматируем timestamp,лежащий в $dt в формат Дата-Время для письма
		$subject= $subject."\r\n"; // "=?utf-8?B?". base64_encode($subject). "?="; - кодируем тему письма(уход от пробл.с кодировкой)
		$message= "(".$dt.")"."\r\n"."СОДЕРЖАНИЕ: \r\n".wordwrap($message,70,"\r\n"); //ограничение длинны символов одной строки в теле письма - 70 символов + приклеиваем дату отправки($dt)
		$headers= "ОТ КОГО: ". self::$from_name. " <".self::$from.">"."\r\n"; //"From: =?utf-8?B?".base64_encode(self::$from_name)."?="." <".self::$from.">\r\n"; -кодируем Имя отправителя(уход от пробл.с кодировкой)
		$headers .="КОМУ: ".$to."\r\n"; 
		$headers .="Content-type: " .$this->_type. ": charset=". $this->_encoding."\r\n"; // Устанавливаем нужные заголовки для E-mail + присоединяем к ним предыдущее в строке $headers
			
		return mail($to, $subject, $message, $headers); // отправка письма, возврат результата
	} // END f_sendMail


// f_saveNews- Добавление новой записи(данных из письма) в табл.отправленные(outboxes) БД	 
	public function f_saveMail($email_to, $theme, $text_email, $date_format, $dt) {  // Ф-я возвращает: TRUE или FALSE
		$stmt= $this->_db->stmt_init(); //выделяем память и инициализируем объект запроса,кот.можно будет использовать в $stmt->prepare()

		try{
		//заполняем табл. "outboxes"
			$sql = "INSERT INTO outboxes(email, theme, text, date_format, datetime) VALUES(?,?,?,?,?)"; // строка запроса для подготовленного запроса
			$stmt->prepare($sql); /* создаем подготавливаемый запрос */
			$stmt->bind_param("ssssi", $email_to, $theme, $text_email, $date_format, $dt); /* привязываем переменные к параметрам */
			$stmt->execute(); /* выполняем запрос */
			if(!$stmt) { throw new Exception($this->_db->error); } // если в($stmt) ничего не пришло,возбуждаем Exception
				return TRUE; // если все ОК и в($stmt) все пришло,-возвращаем TRUE
		}
		catch(Exception $e){ // сценарий если есть отловленные ошибки 
			//echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
			return FALSE;
		}		
			$stmt->close(); // очищаем результурующий набор
			$this->_db->close(); // закрываем соединение			
	} // END f_saveMail


// Вывод всех имеющихся писем из cоответствующей табл. БД по единому шаблону
	public function f_getMail($name_table, $select, $select_name) { //$name_table- заходит имя таблицы БД из кот.надо делать выборку 
													 //$select_name - значение из крутилки(<select>),- критерий выборки по Имени Отправителя письма
													 // $select - значение из крутилки(<select>),- критерий выборки по Дате письма
													//Ф-я возвращает: array или FALSE

		if(!$select_name) { // если НЕТУ значения из (<select>) по Имени Отправителя письма, то работаем со значением (<select>) по Дате письма:

		// если ($select) нет или он ='all_mail',- значит выводим все письма этого списка. Это же условие есть и ПО-УМОЛЧАНИЮ (!)
			if(!$select || $select==='all_mail') { 
				try{
					$sql = "SELECT id, name, email, theme, text, datetime FROM $name_table ORDER BY datetime DESC";
					
					$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
					if(!is_object($result)) { throw new Exception($this->_db->error); } // если в($result) ничего не пришло,возбуждаем Exception
					return $this->f_dbArray($result); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
											// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !
				}
				catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 		//echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
				return FALSE;
				}

				$result->close(); // очищаем результурующий набор
				$this->_db->close();  // закрываем соединение	
			} // endif
	
	// если($select)='today',- выбрана выборка писем за весь данный текущий день
			if($select==='today') { 
				try{
					$tmst= date("d-m-Y",time());
					$sql = "SELECT id, name, email, theme, text, datetime FROM $name_table WHERE date_format ='$tmst' ORDER BY datetime DESC"; 
				
					$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
					if(!is_object($result)) { throw new Exception($this->_db->error); } // если в($result) ничего не пришло,возбуждаем Exception
					return $this->f_dbArray($result); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
											// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !
				}
				catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 		//echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
				return FALSE;
				}

				$result->close(); // очищаем результурующий набор
		 		$this->_db->close(); // закрываем соединение		
			} // endif
	
	// если($select)='last_week',- выбрана выборка писем за последнюю неделю(7дней)
			if($select==='last_week') { 
				try{ 
					$date_current= time(); 
					$date_end= strtotime('-1 week');
			
					$sql = "SELECT id, name, email, theme, text, datetime FROM $name_table WHERE datetime <='$date_current' 
								AND datetime >= '$date_end' ORDER BY datetime DESC"; 
				
					
					$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
					if(!is_object($result)) { throw new Exception($this->_db->error); } // если в($result) ничего не пришло,возбуждаем Exception
					return $this->f_dbArray($result); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
											// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !
				}
				catch(Exception $e){ // сценарий если есть отловленные ошибки 
					 //echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
				return FALSE;
				}

				$result->close(); // очищаем результурующий набор
				$this->_db->close();  // закрываем соединение		
			} // endif

	// если($select)='last_month',- выбрана выборка писем за текущий месяц от нынешней даты (-31 день)
			if($select==='last_month') { 
				try{ 
					$date_current= time(); 
					$date_end= strtotime('-1 month'); // 'Last month' или '-31 day' 
			
					$sql = "SELECT id, name, email, theme, text, datetime FROM $name_table WHERE datetime <='$date_current' 
								AND datetime >= '$date_end' ORDER BY datetime DESC"; 
				
					
				$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
				if(!is_object($result)) { throw new Exception($this->_db->error); } // если в($result) ничего не пришло,возбуждаем Exception
				return $this->f_dbArray($result); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
											// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !
				}
				catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 		//echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
				return FALSE;
				}	

				$result->close(); // очищаем результурующий набор
				$this->_db->close();  // закрываем соединение		
			} // endif


	// если($select)='last_2month',- выбрана выборка писем за предыдущий месяц от нынешней даты(т.е.разница тут кол-во дней текушего месяца + весь предыщущий) (-31 день)
			if($select==='last_2month') { 
				try{ 
					$date_current= strtotime('-1 month');  // '-31 day' 
					$date_end= strtotime('-2 month');  // '-61 day'
			
					$sql = "SELECT id, name, email, theme, text, datetime FROM $name_table WHERE datetime <='$date_current' 
								AND datetime >= '$date_end' ORDER BY datetime DESC"; 
				
					
					$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
					if(!is_object($result)) { throw new Exception($this->_db->error); } // если в($result) ничего не пришло,возбуждаем Exception
					return $this->f_dbArray($result); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
											// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !
				}
				catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 		//echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
				return FALSE;
				}

				$result->close(); // очищаем результурующий набор
				$this->_db->close();  // закрываем соединение		
			} // endif

		} // endif  when (!$select_name)
		else { // когда ЕСТЬ значение из (<select>) по Имени Отправителя письма, то работаем именно с ним:
			try { 
				$sql = "SELECT id, name, email, theme, text, datetime FROM inboxes WHERE name= '$select_name' ORDER BY name DESC"; 
				
				$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
				return $this->f_dbArray($result); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
											// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !
			}
			catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 	//echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
			return FALSE;
			}
												
			$result->close(); // очищаем результурующий набор
			$this->_db->close(); // закрываем соединение		
		} // endelse

	} // END f_getMail
	

// f_getTextMail- Вывод определенного письма по его(id). (id)прикрепляется к ссылке и передается мет.GET(см.view_list_mail.php)
	public function f_getTextMail($name_table, $id) { //$name_table- заходит имя таблицы БД из кот.надо делать выборку
													 // $id - Идентификатор записи(письма),- передается мет.GET(см.view_list_mail.php) 
													//Ф-я возвращает: array или false
		 try {
			$sql = "SELECT name, email, theme, text, datetime FROM $name_table WHERE id='$id'";
					
			$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
			if(!is_object($result)) { throw new Exception($this->_db->error); } // если в($result) ничего не пришло,возбуждаем Exception
			return $this->f_dbArray($result); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
											// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !
		 }
		 catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 //echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
			return FALSE;
		}
		$result->close(); // очищаем результурующий набор
		$this->_db->close(); // закрываем соединение
		
	}// END f_getTextMail


// f_deleteMail- Удаление отмеченных чекбоксами писем из БД по их идентификаторам(id)
	public function f_deleteMail($name_table, $id_string){ //$name_table- заходит имя таблицы БД из кот.надо делать удаление //Ф-я возвращает: array или false
														  //$id_string- значения из Массива чекбоксов($_POST['chbox_input'])) по которым нужно делать удаление(см.delete_mail.prc.php)
														//Ф-я возвращает: TRUE или FALSE
		try {
			$sql = "DELETE FROM $name_table WHERE id IN ($id_string)"; // строка запроса для подготовленного запроса id IN ($ids) outboxes
			$result_del= $this->_db->query($sql); // результат выборки из БД заносим в $result_del
			
			if(!$result_del) { throw new Exception($this->_db->error); } // если в($result) ничего не пришло,возбуждаем Exception
			return TRUE; // если все ОК и в($result_del) все пришло,-возвращаем TRUE
		}
		catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 //echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
			return FALSE;
		}
		$result_del->close(); // очищаем результурующий набор
		$this->_db->close(); // закрываем соединение
	} // END f_deleteMail


// f_resaveMail- Перемещение выбранных по(checkbox) писем на удаление в КОРЗИНУ(таб.(trashboxes)) БД по их идентификаторам(id)
	public function f_resaveMail($name_table, $id_string){ //$name_table- заходит имя таблицы БД из кот.надо делать перемещение в КОРЗИНУ //Ф-я возвращает: true или false
														  //$id_string- значения из Массива чекбоксов($_POST['chbox_input'])) по которым нужно делать удаление(см.delete_mail.prc.php)
		try {
 		// Выборка из таблицы с письмами (ВХОД./ ИСХОД.) по($id_string) и одновременная вставка этих же данных в табл.(trashboxes)-т.е.КОРЗИНУ
			$sql = "INSERT INTO trashboxes SELECT id, name, email, theme, text, date_format, datetime FROM $name_table WHERE id IN ($id_string)";
			$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
			
			if(!$result) { throw new Exception($this->_db->error); }  // если в($result) ничего не пришло,возбуждаем Exception
			return TRUE; // если все ОК и в($result) все пришло,-возвращаем TRUE
		}
		catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 //echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
			return FALSE;
		}

		//$this->_db->close(); //закрываем соединение - НЕ закрыл, т.к.по коду сразу же идет Ф-я на удаление(f_deleteMail) и эта строка не дает одновременно удалять и перемещать письма
	
	} // END f_resaveMail

/********************************(!) Эта функция по функционалу полный аналог одноименной Ф-и (f_resaveMail),- делает абсол.то же самое (!)
// f_resaveMail- Перемещение выбранных(checkbox) на удаление Писем из разделов "ВХОДЯЩИЕ/ИСХОДЯЩИЕ" в КОРЗИНУ(т.е.в таб.(trashboxes)БД по их идентификаторам(т.е.по id)
	public function f_resaveMail($name_table, $id_string){ //$name_table- заходит имя таблицы БД из кот.надо делать перемещение в КОРЗИНУ //Ф-я возвращает: true или false
															//$id_string- значения из Массива чекбоксов($_POST['chbox_input'])) по которым нужно делать удаление(см.delete_mail.prc.php)
		try { 
		// выборка из БД отмеченных как удаленные по checkbox'ам писем 
			$sql = "SELECT id, name, email, theme, text, datetime FROM $name_table WHERE id IN ($id_string)"; 
			$result_sql= $this->_db->query($sql); // результат выборки из БД заносим в $result_sql
			$result_set= $this->f_dbArray($result_sql); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
														// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !
		
		// преобразование рез.выборки ($result_set) и вставка в КОРЗИНУ
			$string= array(); // инициал.массив для строки с склеинными запятой переменными 

			foreach ($result_set as $result) { // Основн.ЦИКЛ: START
				$value= array();

				foreach ($result as $item) { // Вложенн.ЦИКЛ: START
					$value[]= "'".$item."'";
				} // Вложенн.ЦИКЛ: END     

				$string[]= '('.implode("," ,$value). ')'; 
			} // Основн.ЦИКЛ: END
			
			$sql = "INSERT INTO trashboxes(id, name, email, theme, text, datetime) VALUES".implode(',',$string); // строка запроса для подготовленного запроса
			$result2_sql= $this->_db->query($sql); 
			
			if(!$result_set or !$result2_sql ) { throw new Exception($this->_db->error); }  // если в($result) ничего не пришло,возбуждаем Exception

		}
		catch(Exception $e){ // сценарий если есть отловленные ошибки 
			 //echo $e->getMessage(); // если надо вывести свой текст ошибки и др. $e-> из этой серии
			return FALSE;
		}

		$result_sql->close(); // очищаем результурующий набор
		$this->_db->close(); // закрываем соединение
	} // END f_resaveMail
**************************************************************************************************************************************************/

// f_getName- Вывод в крутилку(<select>) списка уникальных имен	отправителя (это для последующего критерия сортировки писем по Имени)
	public function f_getName($name_table) { //$name_table- заходит имя таблицы БД из кот.надо делать выборку 
											//Ф-я возвращает: array или false
	
		$sql = "SELECT DISTINCT name FROM $name_table ORDER BY name"; // выбирает уникальные записи из колонки(name),сортирует по(name)  
			$result= $this->_db->query($sql); // результат выборки из БД заносим в $result
			if(!is_object($result)) { return FALSE; } // если в($result) ничего не пришло,возбуждаем Exception
			return $this->f_dbArray($result); // возвращаем в($this->) то,что в $result + пропустив через ф-ю(f_dbArray),которая делает из данных выбрки асс.массив
											// можно и вот так: return $result->fetch_all(MYSQLI_ASSOC); - тогда  Ф-я(f_dbArray) НЕ нужна !

		$result->close(); // очищаем результурующий набор
		$this->_db->close(); // закрываем соединение	
} // END f_getName
	
//---------------------------------------------------------END

} // END cl_NewsDB
?>
