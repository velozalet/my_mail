<?
// КЛАСС СОЕДИНЕНИЕ С БД(mySQL)

class cl_connectDB {
	protected $_db; // c-во для экземпляра Кл.(объекта) для хранения соединения с БД
		
	const DB_HOST= 'localhost'; // Хост localhost
	const DB_LOGIN= 'root_mail'; // Логин к БД
	const DB_PASSWORD= '12345'; // Пароль к БД
	const DB_NAME= 'my_mail'; // Имя БД

// __construct
	function __construct(){ 
		$this->_db= new mysqli(self::DB_HOST, self::DB_LOGIN, self::DB_PASSWORD,self::DB_NAME); // подкл.к БД
		if (mysqli_connect_errno()) {printf("Нет соединения с БД '".DB_NAME."': %s\n", mysqli_connect_error()); }
	} 
// END __construct

// __destruct	
	function __destruct() { unset($this->_db);} // удаляем свойство,где лежит соединение. (можно и так $this->_db = NULL;)
// END __destruct

} // END cl_connectDB
?>