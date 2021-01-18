<?php get_header(); ?>
 <link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/lib2/style.css">
        <div class="container"><h1>Памятник - ГРАНИТ Карелия</h1>
            <div class="row">

                <div id="content" class="col-md-4">

                    <div id="body-construct-img">
                        <img src="<?=get_stylesheet_directory_uri()?>/images/1.jpg" alt="">
                    </div>
                    <div>
                        <ul id="body-construct-order"></ul>
                        <div id="body-construct">
                            сумма - <strong id="body-constr">12000 </strong> руб.
                        </div>
                    </div>
                </div>
                <div id="con_content" class="col-md-8">
                    <form id="form" action="" method="POST">

                        <fieldset class="form-group form-size">
                            <div class="row">
                                <legend class="col-sm-12 pt">Размер</legend>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_size" data-inf="0,80 х 0,40 - 12000" id="gridRadios1-1" value="1" checked>
                                        <label class="form-check-label" for="gridRadios1-1">
                                            0,80 х 0,40 - 12000 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_size" data-inf="1,0 х 0,50 - 18000" id="gridRadios1-2" value="2">
                                        <label class="form-check-label" for="gridRadios1-2">
                                            1,0 х 0,50 - 18000 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_size" data-inf="1,20 х 0,60 - 22000" id="gridRadios1-3" value="3" >
                                        <label class="form-check-label" for="gridRadios1-3">
                                            1,20 х 0,60 - 22000 руб.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group form-f">
                            <div class="row">
                                <legend class="col-sm-12 pt">Обработка с приданием формы</legend>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_form" data-inf='форма "волна" - 4000' id="con_form_wave" value="1" >
                                        <label class="form-check-label" for="con_form_wave">
                                            форма "волна" - 4000 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_form" data-inf='иная форма (кроме формы "волна") - 5000' id="con_form_notwave" value="2">
                                        <label class="form-check-label" for="con_form_notwave">
                                            иная форма (кроме формы "волна") - 5000 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_form" data-inf='форма "роза с крестом"  памятник 1.0 метра - 7000' id="gridRadios2-3" value="3" >
                                        <label class="form-check-label" for="gridRadios2-3">
                                            форма "роза с крестом"  памятник 1.0 метра - 7000 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_form" data-inf='форма "роза с крестом"  памятник 1.2 метра - 10000' id="gridRadios2-4" value="4" >
                                        <label class="form-check-label" for="gridRadios2-4">
                                            форма "роза с крестом"  памятник 1.2 метра - 10000 руб.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group form-flower">
                            <div class="row">
                                <legend class="col-sm-12 pt">Цветники</legend>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_parterre" data-inf='короткий - 750' id="gridRadios3-2" value="1" >
                                        <label class="form-check-label" for="gridRadios3-2">
                                            короткий - 750 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_parterre" data-inf='длинный - 1500' id="gridRadios3-1" value="2" >
                                        <label class="form-check-label" for="gridRadios3-1">
                                            длинный - 1500 руб.
                                        </label>
                                    </div>                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="con_parterre_dop" data-inf='Дополнительный цветник (5 элементов) - 5250' id="gridCheck1" value="1">
                                        <label class="form-check-label" for="gridCheck1">
                                            дополнительный цветник (5 элементов) - 5250 руб.
                                        </label>
                                    </div>                                    
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group form-plita">
                            <div class="row">
                                <legend class="col-sm-12 pt">Плита</legend>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_plate" data-inf='плита тонкая надгробная - 7000' id="gridRadios4-1" value="1" >
                                        <label class="form-check-label" for="gridRadios4-1">
                                            плита тонкая надгробная - 7000 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_plate" data-inf='Плита 30*40 - 1300' id="gridRadios4-2" value="2">
                                        <label class="form-check-label" for="gridRadios4-2">
                                            30*40 - 1300 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_plate" data-inf='Плита 40*60 - 2200' id="gridRadios4-3" value="3" >
                                        <label class="form-check-label" for="gridRadios4-3">
                                            40*60 - 2200 руб.
                                        </label>
                                    </div>                                    
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group form-list">
                            <div class="row">
                                <legend class="col-sm-12 pt">Надписи</legend>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <p class="form-check-label" for="slov">
                                            один знак - 35 руб. <span id="sum-slov"></span>
                                        </p>
                                        <textarea class="form-control" id="slov" name="slov" data-inf='Надпись' rows="2" placeholder="ФИО дата рождения - дата смерти"></textarea>
                                        <span id="for_slov">Добавить</span>
                                    </div>                                                                      
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group form-oformlenie">
                            <div class="row">
                                <legend class="col-sm-12 pt">Оформление</legend>
                                <div class="col-sm-12">                                   

                                    <div class="form-check oform">
                                        <input type="checkbox" id="number_oformlenie1">
                                        <input type="number"  class="input-text" step="1" min="0" max="" name="number_cross" value="0" title="Крест - 300" size="4" pattern="[0-9]*" inputmode="numeric" disabled>
                                        <label class="form-check-label" for="number_oformlenie1">
                                            крест - 300 руб.
                                        </label>
                                    </div>
                                    <div class="form-check oform">
                                        <input type="checkbox" id="number_oformlenie2" >
                                        <input type="number" class="input-text" step="1" min="0" max="" name="number_vignette" value="0" title="виньетка - 400" size="4" pattern="[0-9]*" inputmode="numeric" disabled>
                                        <label class="form-check-label" for="number_oformlenie2">
                                            виньетка - 400 руб.
                                        </label>
                                    </div>
                                    <div class="form-check oform">
                                        <input type="checkbox" id="number_oformlenie3" >
                                        <input type="number" class="input-text" step="1" min="0" max="" name="number_rose" value="0" title="роза - 500" size="4" pattern="[0-9]*" inputmode="numeric" disabled>
                                        <label class="form-check-label" for="number_oformlenie3">
                                            роза - 500 руб.
                                        </label>
                                    </div>
                                    <div class="form-check oform">
                                        <input type="checkbox" id="number_oformlenie4">
                                        <input type="number" class="input-text" step="1" min="0" max="" name="number_carnation" value="0" title="гвоздика - 500" size="4" pattern="[0-9]*" inputmode="numeric" disabled>
                                        <label class="form-check-label" for="number_oformlenie4">
                                            гвоздика - 500 руб.
                                        </label>
                                    </div>
                                    <div class="form-check oform">
                                        <input type="checkbox" id="number_oformlenie5" >
                                        <input type="number"  class="input-text" step="1" min="0" max="" name="number_candle" value="0" title="свеча - 500" size="4" pattern="[0-9]*" inputmode="numeric" disabled>
                                        <label class="form-check-label" for="number_oformlenie5">
                                            свеча - 500 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_portrait" id="con_portrait1" value="1" >
                                        <label class="form-check-label" for="con_portrait1">
                                            портрет - 4500 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_portrait" id="con_portrait2" value="2">
                                        <label class="form-check-label" for="con_portrait2">
                                            портрет на памятник высотой 1.2м - 5000 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_portrait" id="con_portrait3" value="3" >
                                        <label class="form-check-label" for="con_portrait3">
                                            портрет на памятник высотой 1.2м(с бюстом) - 7000 руб.
                                        </label>
                                    </div>                                    
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-group form-ustanovka">
                            <div class="row">
                                <legend class="col-sm-12 pt">УСТАНОВКА памятников</legend>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_install" id="con_install1" value="1" >
                                        <label class="form-check-label" for="con_install1">
                                            памятники до 1.0 м - 3000 руб.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="con_install" id="con_install2" value="2">
                                        <label class="form-check-label" for="con_install2">
                                            памятники до 1.2 м - 5000 руб.
                                        </label>
                                    </div>                                                                      
                                </div>
                            </div>
                        </fieldset>   

                        <div class="form-group form-user">
                            <div class="form-row">
                                <h3 class="col-sm-12 f-u">Оставить заявку</h3>
                                <div class="form-group col-md-6">

                                    <input class="form-control" type="text" name="con_name" id="con_name" required placeholder="Имя" >

                                </div>
                                <div class="form-group col-md-6">

                                    <input class="form-control" type="text" name="con_phone" id="con_phone" required placeholder="Телефон">

                                </div>  
								<div class="form-group col-md-12">

                                    <input class="form-control" type="text" name="con_adress" id="con_adress" required placeholder="Адрес:">

                                </div> 
                                <div class="col-sm-12">
                                    <textarea class="form-control" id="slov" name="con_masseg" placeholder="Дополнительная информация!" rows="2"></textarea>
                                </div>
                            </div>
                        </div>


<!--                        <input name="slovo" id="slov" type="text" value="">                       -->
                        <input class="btn btn-dark" type="submit" name="sub" value="Отправить заявку">
                    </form>

                </div>
            </div>
        </div>
		<?php
			
		?>
      
        

<?php
get_footer();