<?php

session_start();
unset($_SESSION['admin']);
$_SESSION['success'] = "ออกจากระบบสำเร็จ";
header("Location: ../login.php");
