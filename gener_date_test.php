<?
header("Content-type:text/html; charset=utf-8"); // кодировка
ob_start();  //cтартуем буфферизацию контента
function __autoload($name_class) { require "classes/$name_class.php"; } // ф-я,которая автоматом будет подгружать нужный класс при создании соответств.объекта Кл.

$O_mail= new cl_Mail(); // создание объекта Кл.
$O_connDB= new cl_connectDB(); // создание объекта Кл.


//-------------------------------------------------------------------------------------------------------------------------------------------------
//echo date(time()); echo "<br>";

echo mktime(0,0,0,1,6,1982); // 379116000
		echo "<br>";
echo date("d-m-Y", 379116000); // 06-01-1982
echo "<br>"; echo "<br>"; echo "<br>";


$mk_time= mktime(0,0,0,6,30,2015); // мес.-день-год
echo $mk_time; 
		echo "<br>";
echo date("d-m-Y", $mk_time);
		echo "<br>"; echo "<hr>"; 


//-------------------------------------------------------------------------------------------------------------------------------------------------

?>

