<? header("Content-type:text/html; charset=utf-8"); // кодировка ?>
<!-- БЛОК МОДАЛЬ. START -->

<!-- Триггер кнопка модали-->
<!-- <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal"><i class="fa fa-key fa-fw text-danger fa-lg"></i>Войти на сайт</button> -->
<!-- Триггер кнопка модали. END -->

<div class="modal fade" id="myModal_del" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> <!-- БЛОК МОДАЛЬ -->
  <div class="modal-dialog modal-sm"> <!-- modal-dialog--><!--modal-sm / modal-lg-->
    <div class="modal-content"> <!-- modal-content-->
	
      <div class="modal-header"> <!-- Шапка.Визуально отделена от контента Модали -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">&nbsp Вы действительно хотите удалить выбранные письма ?</h4>
           <span class="input-group-addon"> <i class="fa fa-trash fa-fw text-primary fa-lg"></i> </span>

           <form class='form-inline' action='<?= $_SERVER['REQUEST_URI'] ?>' method='POST' >  <!--form    action='delete.php' method='GET'-->  
      
              <div class="input-group">  <!-- has-success сюда.И можно будет менять фон для содержимого span и цвет границы для всего поля ввода в целом -->  
              <!-- <span class="input-group-addon"> <i class="fa fa-trash fa-fw text-primary fa-lg"></i> </span> -->
                <input class="form-control input-lg" type="hidden" name='dellete' maxlength="25" title="Максимально 25 символов" placeholder="От кого" value="<?=3851?>" required autofocus>
                &nbsp&nbsp <br><br>                 
                <div class='center-block'>
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <button type='submit'  name='btn_modal_del' form="form_delete" class='btn btn-primary'><em class='glyphicon glyphicon-ok'></em>&nbsp Удалить</button>&nbsp&nbsp&nbsp&nbsp
                <button type="tab" class="btn btn-primary" data-dismiss="modal"><em class='glyphicon glyphicon-remove'></em> Отменить</button>
                </div>   

              </div>        
          
           </form>  <!--form. END-->
        </div>  <!-- Шапка. END-->

    </div> <!-- modal-content END -->
  </div> <!-- modal-dialog END -->
</div> 
<!-- БЛОК МОДАЛЬ. END     REQUEST_URI-->