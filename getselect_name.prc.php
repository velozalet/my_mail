<?
// 
$result_name= $O_mail->f_getName($name_table);

if(!is_array($result_name)) { $err_msg= "Произошла ошибка при выводе записей(!) &nbsp"; } // если в $result не массив
else {
	foreach($result_name as $item) {
		$name= $item['name']; 
		echo "<option value='$name' > $name </option> <br>";

	}
}  
?>