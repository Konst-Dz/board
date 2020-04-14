<?php
include "elems/link.php";
var_dump($_SERVER['REQUEST_URI']);
 //список категорий.
$list = '';
$query = "SELECT * FROM category";
$result = mysqli_query($connect,$query) or die(mysqli_error($connect));
for($data = [];$row = mysqli_fetch_assoc($result);$data[] = $row);

$list .= "<ul class=\"list\"><li><a href=\"/\">Все обьвления</a></li>";

foreach ($data as $datum) {
    $list .= "<li><a href=\"?list={$datum['id']}\">{$datum['category']}</a></li>";
}
$list .= "</ul>";

//Пагинация
if(isset($_GET['page']) and is_numeric($_GET['page'])){
    $page = $_GET['page'];
}
else{
    $page = 1;
}
//Кол-во записей на странице
$howMany = 5;
//категория обьяв
$category = $_GET['list'] ?? '' ;

//Вывод  на страницу с пагинацией.
include "elems/func/content.php";
$content = content($connect,$page,$category,$howMany);

include "elems/layout.php";
