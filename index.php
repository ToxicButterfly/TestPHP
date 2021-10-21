<?php
error_reporting(0);
session_start();
// Include and show the requested page
include_once 'human.php';
include_once 'database.php';
include_once 'humanlist.php';

$database = new Database();
$db = $database->getConnection();

//Можно раскомментировать, чтобы создавать новых людей и проверять работу методов.
//$human = new Human($db, "Peter", "Parker", "2011-02-21", false, "New York", 0);
//$human2 = new Human($db, "Hermione", "Granger", "1999-04-30", true, "London", 0);

//Аргументы со 2-го по 6-ой не нужны, 7-ой - id человека, которого нужно взять из БД.
//$human3 = new Human($db, "1", "2", "3", "4", "5", 17);

//echo $human3->name;
//$person = $human2->changePerson();
//print_r($person);

$hl = new HumanList($db, ">", 25);
$hl->massDeleteHuman();

?>
