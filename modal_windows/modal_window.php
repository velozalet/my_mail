<!--БЛОК МОДАЛЬ. START -->

<!-- Триггер кнопка модали-->
<!-- <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal"><i class="fa fa-key fa-fw text-danger fa-lg"></i>Войти на сайт</button>
--> <!-- Триггер кнопка модали. END -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> <!-- БЛОК МОДАЛЬ -->
  <div class="modal-dialog modal-lg"> <!--modal-sm / modal-lg-->
    <div class="modal-content">
	
      <div class="modal-header"> <!-- Шапка.Визуально отделена от контента Модали -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title center-block" id="myModalLabel"><em class='glyphicon glyphicon-envelope'></em>&nbsp Написать E-mail</h4>
      </div>                     <!-- Шапка. END-->
	  
      <div class="modal-body"> <!-- Контент Модали -->
     
	 	<form class='form-inline' action='<?= $_SERVER['REQUEST_URI'] ?>' method='POST' >  <!--form-->	

			<div class="input-group">  <!-- has-success сюда.И можно будет менять фон для содержимого span и цвет границы для всего поля ввода в целом -->	
				<span class="input-group-addon"> <i class="fa fa-envelope-o fa-fw text-primary fa-lg"></i> </span>
				<input class="form-control input-lg" type="email" name='my_email' disabled placeholder="От кого" value="<?=cl_Mail::$from ?>" required autofocus>
			</div>

		<div style='height:5px;'></div>  <!-- div-разделитель для примера -->	

			<div class="input-group"> <!-- has-success сюда.И можно будет менять фон для содержимого span и цвет границы для всего поля ввода в целом -->	
				<span class="input-group-addon "> <i class="fa fa-envelope-o fa-fw text-primary fa-lg"></i> </span>
				<input class='form-control input-lg'  type="email" name="email_to" maxlength="30" title="Максимально 30 символов" placeholder="Email получателя" required autofocus>
			</div>

		<div style='height:5px;'></div>  <!-- div-разделитель для примера -->

			<div class="input-group"> <!-- has-success сюда.И можно будет менять фон для содержимого span и цвет границы для всего поля ввода в целом -->	
				<span class="input-group-addon "> <i class="fa fa-pencil fa-fw text-primary fa-lg"></i> </span>
				<input class='form-control input-lg'  type="text" name="theme" maxlength="70" title="Максимально 70 символов" id="my_input_for_mail" placeholder="Тема письма" required autofocus>
			</div>

		<div style='height:5px;'></div>  <!-- div-разделитель для примера -->
		<div style='height:5px;'></div>  <!-- div-разделитель для примера -->

			<div class="input-group"> <!-- has-success сюда.И можно будет менять фон для содержимого span и цвет границы для всего поля ввода в целом -->	
				<span class="input-group-addon "> <i class="fa fa-pencil fa-fw text-primary fa-lg"></i> </span>
				<textarea  class="form-control" name="text_email" maxlength="1000" title="Максимально 1000 символов" id="my_textarea_for_mail" required> </textarea>
			</div> 
	
			<input class='form-control input-lg'  type="hidden" name="datatime" value="<?=time();?>"> <!-- скрытое поле -> timestamp -->
			
		&nbsp&nbsp <br><br>	
			<button type='reset' class='btn btn-primary btn-lg'><em class='glyphicon glyphicon-remove'></em>&nbsp Отменить</button>
			<button type='submit' name='btn_modal' class='btn btn-primary btn-lg'><em class='glyphicon glyphicon-new-window'></em>&nbsp Отправить</button>
		</form>                      <!--form. END--> 
		
      </div> <!-- Контент Модали. END-->
	  
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button> 
        
      </div>
	  
    </div> <!-- modal-content. END -->
  </div> <!-- modal-dialog. END -->
</div> 
<!-- БЛОК МОДАЛЬ. END -->