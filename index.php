<?
require "main_config.php"; // Настройки/ переходы/ подключение скриптов исполнения
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Почтовый клиент</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet" media="screen"> <!-- подключ.основной css-файл Bootstrapа--> 
	<link href="css/font-awesome.css" rel="stylesheet"> <!-- подключ.css-файл font-awesome со шрифт.иконками--> 
  </head>
<body>
 
<!-- START CONTAINER-FLUID --> 
<div class='container-fluid'>
	<header>
		 <h3 class="text-center">МОЙ МИНИ-ПОЧТОВЫЙ КЛИЕНТ &nbsp <sub>(<?= cl_Mail::$from_name." < ",cl_Mail::$from." >" ?> )</sub></h3>
	</header>
<br>

<!-- Вертик.НавБар- НАЧАЛО-->
<div class='navbar navbar-default my_navbar_vertical'>
	<nav class='navbar-collapse collapse' id='responsive-menu'> 	
		<ul id="myTab" class="nav my_nav_ver my_nav-tabs my_nav-tabs_ver" >
			<li class="<?if($page=='') {echo $a='active';} ?>"><a href="<?=$_SERVER['SCRIPT_NAME'];?>" role="tab" data-toggle="submit"><em class='glyphicon glyphicon-log-in'></em>&nbsp Входящие </a></li>
			<li class="<?if($page==='output') {echo $a='active';} ?>"><a href="<?=$_SERVER['SCRIPT_NAME'];?>?page=output" role="tab" data-toggle="link"><em class='glyphicon glyphicon-log-out'></em>&nbsp Отправленные </a></li>
			<li class="<?if($page==='trash') {echo $a='active';} ?>"><a href="<?=$_SERVER['SCRIPT_NAME'];?>?page=trash" role="tab" data-toggle="submit"><em class='glyphicon glyphicon-trash'></em>&nbsp Корзина </a></li>
			</li> 	<!-- по $page,приходящей по_GET,соответственно,активируем закладку ставя с-во(active)-->						
		</ul>
	</nav> 	
</div>
<!-- Вертик.НавБар- КОНЕЦ-->

&nbsp 
<!-- START Main CONTENT ZONE -->
<div class='row center-block'>
		
	<div class='col-sm-9 delete_border my_div_blc'>

		<div style="margin-left: 382px;">  
			<form action='<?= $_SERVER['REQUEST_URI'] ?>' method='POST' name="select_form_name" id="select_form_name">
&nbsp &nbsp&nbsp&nbsp Сортировать по имени: <select name="select_name" size="1">
												<? require "getselect_name.prc.php"; ?>  
											</select>
					&nbsp&nbsp&nbsp<button type='submit'  name='submit' value="<?=btn_select ?>" class='btn btn-primary btn-sm'><em class='glyphicon glyphicon-ok'></em>&nbsp Выполнить</button>
			</form>
		</div>

		<!-- START Кнопки-ссылки сверху в виде горизонт.списка --> 	
		<nav class='navbar-collapse collapse' id='responsive-menu'>
			<ul id="myTab" class="nav nav-tabs">
				<li><a href="create Email" role="submit" data-toggle="modal" data-target="#myModal"><em class='glyphicon glyphicon-pencil'></em>&nbsp Написать письмо</a></li>
				<li><a href="delete Email" role="submit"  data-toggle="modal" data-target="#myModal_del"><em class='glyphicon glyphicon-trash'></em> &nbsp Удалить выбранные</a></li>
				
				<form action='<?= $_SERVER['REQUEST_URI'] ?>' method='POST' name="select_form" id="select_form">
&nbsp &nbsp&nbsp&nbsp Сортировать по дате: <select name="select" size="1">
						<option value="all_mail" <?if($select==='all_mail') {echo $s='selected';} ?> > все имеющиеся письма </option>
						<option value="today" <?if($select==='today') {echo $s='selected';} ?> > за сегодня </option>
						<option value="last_week" <?if($select==='last_week') {echo $s='selected';} ?> > за текущую неделю </option>
						<option value="last_month" <?if($select==='last_month') {echo $s='selected';} ?> > за текущий месяц </option>
						<option value="last_2month" <?if($select==='last_2month') {echo $s='selected';} ?> > за предыдуший месяц </option>
					</select>
					&nbsp&nbsp&nbsp<button type='submit'  name='submit' value="<?=btn_select ?>" class='btn btn-primary btn-sm'><em class='glyphicon glyphicon-ok'></em>&nbsp Выполнить</button>
				</form>
			</ul>
		</nav> 
		<!-- END Кнопки-ссылки сверху в виде горизонт.списка
<?
		
?>
		<!-- START БЛОК для списка писем -->
		<div class='col-sm-12'> &nbsp <em class='glyphicon glyphicon-folder-open'>&nbsp</em>  <!-- вывод названия отображаемых на странице писем: ВХОДЯЩИЕ/ОТПРАВЛЕННЫЕ/КОРЗИНА -->

			<table class='table panel table-responsive table-border'>
				<tr> <th><img  src='my_img/img_check-box.jpg' alt=''></th> <th>Отправитель</th> <th>Получатель</th> <th>Тема письма</th> <th>Дата получения</th> </tr> 
				<form action='<?= $_SERVER['REQUEST_URI'] ?>' method='POST' name="del_letters" id="form_delete"> 
					<? require "route_mcontent.php"; ?>	 <!-- Динамика подсоединения контента в таблицу вывода писем(по $page приходящей по_GET)-->
														<!-- и вывод названия отображаемых на странице писем: ВХОДЯЩИЕ/ОТПРАВЛЕННЫЕ/КОРЗИНА -->

					<input class="form-control input-lg" type="hidden" name='qq' value="<?=77 ?>" required autofocus><?="<h5> $change_del </h5>";?> <?= $err_msg; ?>
					<!-- <button type='submit' name='btn_delletters' class='btn btn-primary btn-lg'  ><em class='glyphicon glyphicon-log-in'></em>&nbsp DELEETE</button> --> <!-- эта родная кнопка Формы вынесена в модальное окно на удаление(modal_window_del.php)-->
				&nbsp&nbsp
				</form>        
			</table>

		</div>
		<!-- END БЛОК для списка писем -->

		<!-- START Кнопки-ссылки снизу в виде горизонт.списка --> 
		<nav class='navbar-collapse collapse' id='responsive-menu'>
			<ul id="myTab" class="nav nav-tabs my_nav-tabs_btm">
				<li><a href="create Email" role="submit" data-toggle="modal" data-target="#myModal"><em class='glyphicon glyphicon-pencil'></em>&nbsp Написать письмо</a></li>
				<li><a href="delete Email" role="submit" data-toggle="modal" data-target="#myModal_del"><em class='glyphicon glyphicon-trash'></em> &nbsp Удалить выбранные</a></li>
			</ul>
		</nav>
		<!-- END Кнопки-ссылки снизу в виде горизонт.списка --> 

	</div> <!-- END class='col-sm-9' --> 

</div> <!-- END class='row' -->
<!-- END  Main CONTENT ZONE -->

<?
require "modal_windows/modal_window.php";  //инклюдинг Модали на создание E-mail
require "modal_windows/modal_window_del.php"; // инклюдинг Модали на удаление E-mail
?>


</div> <!-- END class='container-fluid' -->
<!-- END CONTAINER-FLUID --> 
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> это онлайновая библиотека jQuery,она будет работать и выполнять 
	             скрипты только при подключ.INTERNET. Чтобы добавить и подкл.эту библ.jQuery,нужно скопировать эту ссылку,и загрузить в адрес.строке браузера,
				 затем весь текст, что появится(это и есть сама библиотека) сохранить(сохранить как прав.клав.на нем) как /jquery.min.js/-файл в папке /js/
				 и потом тут(важно именно тут и выше строки:<script src="js/bootstrap.min.js" type="text/javascript"></script>) подключить эту библиотеку,
				 прописав строку: <script src="js/jquery.min.js" type="text/javascript"></script> -->
	
	<script src="js/jquery.min.js" type="text/javascript"></script> <!-- Include lib.jQuery из он-лайновой ссылки-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js" type="text/javascript"></script> <!-- Include lib.Javascript из исходника Бутстрап-->

  </body>
</html>