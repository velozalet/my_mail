<?
// inerface cl_Interf_Mail содержит декрарирование основных методов для работы с мини-почтовым порталлом

interface cl_Interf_Mail {
/* СВОЙСТВА для работы с почтовым порталом:

	* static $from= ''; // E-mail отправителя(един для всего почтового сервера)
	* static $from_name= ''; // E-mail отправителя(един для всего почтового сервера)
	* private $_type= ''; // тип содержимого E-mail(Content-type)
	* private $_encoding= ''; // тип кодировки E-mail
	* private $_notify= FALSE; // для проверки подтверждения получения письма(по надобности)
*/

// МЕТОДЫ для работы почтовым порталом:

	public function f_clearData($data, $type);
		/* Фильтр-очистка принимаемых данных из GET/POST  
		  (!)_для расширения PHP(php_mysqli.dill). Started ih PHP ver.5.0_(!)
		
		PARAM.:2
			* $data- данные от пользователя с мет. GET/POST;
			* $type-по какому шаблону ее фильтровать(см.по case)

		RETURN: string or integer отфильтрованные по указанному параметру($type)
		*/ 

	
	public function f_dbArray($result); 
		/* Конвертер данных из БД($result) в асс.массив_(результат возврата запроса SQL будет конвертироваться в массив. 
		  Массив может быть пустым(!)- учесть-это не ошибка,- в БД просто нет данных)

		PARAM.:1
			* $result- выборка из БД по запросу SQL
		RETURN: array - может быть как заполненный, так и пустой(!)
		*/


	public function f_sendMail($to, $subject, $message, $dt);
		/* Отправка письма на почтовый сервер Адресата
			PARAM.:4
				* $to - кому письмо: корректный e-mail получателя
				* $subject - тема письма
				* $message - тело письма
				* $dt - timestamp

			Отправка непосредственно письма, возврат результата:
				* $headers- формируем строку, передаем нужные нам заголовки 
			return mail($to, $subject, $message, $headers); // cтандартн.встроенн.Ф-я PHP для почтового сервера

			RETURN: результат отработки Ф-и: mail($to, $subject, $message, $headers) - письмо уходит на почтовый сервер
		*/


 	public function f_saveMail($email_to, $theme, $text_email, $date_format, $dt);
		/* Добавление новой записи(данных из письма) в табл.отправленные(outboxes) БД	
			PARAM.:5
				* $email_to - кому письмо: корректный e-mail получателя
				* $theme - тема письма
				* $text_email - тело письма
				* $date_format - timestamp отформатированный по типу Дата-Время
				* $dt - timestamp

			RETURN:
				* TRUE- если вставка(INSERT) в табл.(outboxes) БД прошла успешно
				* FALSE- если вставка(INSERT) в табл.(outboxes) БД не произошла (ошибка)
		*/


	public function f_getMail($name_table, $select, $select_name);
		/* Вывод всех имеющихся писем из cоответствующей табл. БД по единому шаблону	
			PARAM.:3
				* $name_table- заходит имя таблицы БД из кот.надо делать выборку 
				* $select_name - значение из крутилки(<select>),- критерий выборки по Имени Отправителя письма
				* $select - значение из крутилки(<select>),- критерий выборки по Дате письма

			RETURN:
				* array - если выборка(SELECT) из соответствующей табл.БД прошла успешно и преобразовалась в массив 
				* FALSE- если выборка(SELECT) из соответствующей табл.БД не произошла (ошибка)
		*/


				// логическая структура
		/*
		if(!$select_name) { // если НЕТУ значения из (<select>) по Имени Отправителя письма, то работаем со значением (<select>) по Дате письма:

			* если ($select) нет или он ='all_mail',- значит выводим все письма этого списка. Это же условие есть и ПО-УМОЛЧАНИЮ (!)
			    if(!$select || $select==='all_mail') {instructions} // endif
	
			* если($select)='today',- выбрана выборка писем за весь данный текущий день
				if($select==='today') {instructions} // endif
	
			* если($select)='last_week',- выбрана выборка писем за последнюю неделю(7дней)
			if($select==='last_week')  {instructions} // endif

			* если($select)='last_month',- выбрана выборка писем за текущий месяц от нынешней даты (-31 день)
				if($select==='last_month') {instructions} // endif

			* если($select)='last_2month',- выбрана выборка писем за предыдущий месяц от нынешней даты(т.е.разница тут кол-во дней текушего месяца + весь предыщущий) (-31 день)
			if($select==='last_2month') {instructions} // endif
				
		} // endif  when (!$select_name)
		else { // когда ЕСТЬ значение из (<select>) по Имени Отправителя письма, то работаем именно с ним:
			instructions
		} // endelse	
		*/


	public function f_getTextMail($name_table, $id); 
		/* Вывод определенного письма по его(id). (id)прикрепляется к ссылке и передается мет.GET(см.view_list_mail.php)	
			PARAM.:2
				* $name_table - заходит имя таблицы БД из кот.надо делать выборку 
				* $id - идентификатор записи(письма),- передается мет.GET(см.view_list_mail.php)

			RETURN:
				* array - если выборка(SELECT) из соответствующей табл.БД прошла успешно и преобразовалась в массив 
				* FALSE- если выборка(SELECT) из соответствующей табл.БД не произошла (ошибка)
		*/


	public function f_deleteMail($name_table, $id_string); 
		/* Удаление отмеченных чекбоксами писем из БД по их идентификаторам(id)
			PARAM.:2
				* $name_table - заходит имя таблицы БД из кот.надо делать выборку 
				* $id_string - значения из массива чекбоксов($_POST['chbox_input'])) по которым нужно делать удаление(см.delete_mail.prc.php)

			RETURN:
				* TRUE- если удаление(DELETE) из соответст.таблицы БД прошло успешно
				* FALSE- если вставка(DELETE)из соответст.таблицы БД прошло не произошло (ошибка)
		*/


	public function f_resaveMail($name_table, $id_string); 
		/* Перемещение выбранных по(checkbox) писем на удаление в КОРЗИНУ(таб.(trashboxes)) БД по их идентификаторам(id).
		   При этом идет одновременные: выборка данных из соотв.табл.БД по значениям чекбоксов и вставка результата этой выборки в КОРЗИНУ(табл.trashboxes) БД
			
			PARAM.:2
				* $name_table - заходит имя таблицы БД из кот.надо делать выборку 
				* $id_string - значения из массива чекбоксов($_POST['chbox_input'])) по которым нужно делать удаление(см.delete_mail.prc.php)

			RETURN:
				* TRUE- если (SELECT->INSERT) из соответст.таблиц БД прошлb успешно
				* FALSE- если (SELECT->INSERT) из соответст.таблиц БД прошло не произошло (ошибка)
		*/


	public function f_getName($name_table); 
		/* Вывод в крутилку(<select>) списка уникальных имен отправителя (это для последующего критерия сортировки писем по Имени)	
			PARAM.:1
				* $name_table- заходит имя таблицы БД из кот.надо делать выборку 

			RETURN:
				* array - если выборка(SELECT) из соответствующей табл.БД прошла успешно и преобразовалась в массив 
				* FALSE- если выборка(SELECT) из соответствующей табл.БД не произошла (ошибка)
		*/

} // END cl_Interf_Mail
?>