<?php

require 'config/config.php';

unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_cliente']);
unset($_SESSION['user_type']);
unset($_SESSION['token']);

header("Location: index.php");

?>
