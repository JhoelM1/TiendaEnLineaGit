<?php

define("CLIENT_ID", "AZhbuOBrno0jDHJl-a2n5PSJ9ItxBl1p-DM4OqUByQFL5Hp9UrDMLZUHDP60mwTuEJZ4-jN0FnOJBDkb");
define("CURRENCY", "USD");
define("KEY_TOKEN", "CLV.123");
define("MONEDA", "$");

session_start();
$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>