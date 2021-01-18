<?php

$con_arr = [
    'con_size' => [12000, 18000, 22000],
    'con_form' => [4000, 5000, 7000, 10000],
    'con_parterre' => [750, 1500],
    'con_parterre_dop' => [5250],
    'con_plate' => [7000, 1300, 2200],
    'con_title' => [35],
    'number_cross' => [300],
    'number_vignette' => [400],
    'number_rose' => [500],
    'number_carnation' => [500],
    'number_candle' => [500],
    'con_portrait' => [4500, 5000, 7000],
    'con_install' => [3000, 5000]
];
$con_arr_info = [
    'con_size' => ['Размер - 0,80 х 0,40 - 12000 руб.', 'Размер - 1,0 х 0,50 - 18000 руб.', 'Размер - 1,20 х 0,60 - 22000 руб.'],
    'con_form' => ['форма "волна" - 4000 руб.', 'иная форма (кроме формы "волна") - 5000 руб.', 'форма "роза с крестом" памятник 1.0м. - 7000 руб.', 'форма "роза с крестом" памятник 1.2м. - 10000 руб.'],
    'con_parterre' => ['Цветник - короткий - 750 руб.', 'Цветник - длинный - 1500 руб.'],
    'con_parterre_dop' => ['дополнительный цветник (5 элементов) - 5250 руб.'],
    'con_plate' => ['плита тонкая надгробная - 7000 руб.', 'плита - 30*40 - 1300 руб.', 'плита - 40*60 - 2200 руб.'],
    'con_title' => ['один знак - 35 руб.'],
    'number_cross' => ['крест '],
    'number_vignette' => ['виньетка '],
    'number_rose' => ['роза '],
    'number_carnation' => ['гвоздика '],
    'number_candle' => ['свеча '],
    'con_portrait' => ['портрет - 4500 руб.', 'портрет на памятник высотой 1.2м - 5000 руб.', 'портрет на памятник высотой 1.2м(с бюстом) - 7000 руб.'],
    'con_install' => ['Установка - памятники до 1.0 м - 3000 руб.', 'Установка - памятники до 1.2 м - 5000 руб.']
];
//foreach ($_REQUEST as $key=>$val){
//    
//}
//echo $con_arr['con_size'][$_POST['con_size']-1];

if (isset($_POST['con_size']) && !empty($_POST['con_size'])) {
    $con_size = $con_arr['con_size'][$_POST['con_size'] - 1]; // размер
}
if (isset($_POST['con_form']) && !empty($_POST['con_form'])) {
    $con_form = $con_arr['con_form'][$_POST['con_form'] - 1]; // Обработка с приданием формы
}
if (isset($_POST['con_parterre']) && !empty($_POST['con_parterre'])) {
    $con_parterre = $con_arr['con_parterre'][$_POST['con_parterre'] - 1]; // Цветники
}
if (isset($_POST['con_parterre_dop']) && !empty($_POST['con_parterre_dop'])) {
    $con_parterre_dop = $con_arr['con_parterre_dop'][$_POST['con_parterre_dop'] - 1]; // Дополнительный цветник
}
if (isset($_POST['con_plate']) && !empty($_POST['con_plate'])) {
    $con_plate = $con_arr['con_plate'][$_POST['con_plate'] - 1]; // Плита
}
if (isset($_POST['slov']) && !empty($_POST['slov'])) {
    $con_title = $_POST['slov']; // Надпись
    $count_title = mb_strlen(str_replace(array(" "), '', $con_title), 'utf-8');
    $con_title = $count_title * 35;
}
if (isset($_POST['number_cross']) && !empty($_POST['number_cross'])) {
    $number_cross = $_POST['number_cross']; // Оформление - Крест
    ($number_cross != 0) ? $number_cross = $number_cross * 300 : $number_cross = '';
}
if (isset($_POST['number_vignette']) && !empty($_POST['number_vignette'])) {
    $number_vignette = $_POST['number_vignette']; // Оформление - виньетка
    ($number_vignette != 0) ? $number_vignette = $number_vignette * 400 : $number_vignette = '';
}
if (isset($_POST['number_rose']) && !empty($_POST['number_rose'])) {
    $number_rose = $_POST['number_rose']; // Оформление - роза
    ($number_rose != 0) ? $number_rose = $number_rose * 500 : $number_rose = '';
}
if (isset($_POST['number_carnation']) && !empty($_POST['number_carnation'])) {
    $number_carnation = $_POST['number_carnation']; // Оформление - гвоздика
    ($number_carnation != 0) ? $number_carnation = $number_carnation * 500 : $number_carnation = '';
}
if (isset($_POST['number_candle']) && !empty($_POST['number_candle'])) {
    $number_candle = $_POST['number_candle']; // Оформление - свеча
    ($number_candle != 0) ? $number_candle = $number_candle * 500 : $number_candle = '';
}
if (isset($_POST['con_portrait']) && !empty($_POST['con_portrait'])) {
    $con_portrait = $con_arr['con_portrait'][$_POST['con_portrait'] - 1]; // Оформление - плита
}
if (isset($_POST['con_install']) && !empty($_POST['con_install'])) {
    $con_install = $con_arr['con_install'][$_POST['con_install'] - 1]; // УСТАНОВКА памятников
}

$all_summ = $con_size + $con_form + $con_parterre + $con_parterre_dop + $con_plate + $con_title + $number_cross + $number_vignette + $number_rose + $number_carnation + $number_candle + $con_portrait + $con_install;
//$out = [];
foreach ($_POST as $key => $val) {
    if ($key != 'con_name' && $key != 'con_phone' && $key != 'con_masseg' && $key != 'con_adress') {
        if (strpos($key, 'number') !== false && $val != '0') {
            $out.="<li>" . $con_arr_info[$key][0] . '(' . $val . 'шт) - ' . $con_arr[$key][0] * $val . " руб.</li>";
        } elseif ($key != 'slov' && strpos($key, 'number') === false) {
            $out.="<li>" . $con_arr_info[$key][$val - 1] . "</li>";
        } elseif ($key == 'slov' && !empty($val)) {
            $out.="<li> Надпись - ( " . $val . " ) - " . $con_title . " руб.</li>";
        }
        //$out=$key.'+';
    }
}

$arr = [$all_summ, $out];
//echo $all_summ;
echo json_encode($arr);
exit();
//var_dump($_POST);