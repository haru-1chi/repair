<?php

session_start();
unset($_SESSION['admin_log']);
$_SESSION['success'] = "ออกจากระบบสำเร็จ";
header("Location: ../../orderit/login.php");
