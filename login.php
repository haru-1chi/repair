<?php
session_start();
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/logo_20170112044011.png" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/bootstrap/css/bootstrap.min.css">
    <title>เข้าสู่ระบบสำหรับเจ้าหน้าที่</title>
</head>

<body>
    <nav class="navbar mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img class="logo" src="img/logo_20170112044011.png" alt="Logo" class="d-inline-block align-text-top">
                <span>
                    ระบบบริหารวัสดุหน่วยงาน
                </span>
            </a>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="containerbox">
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['warning'])) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php
                    echo $_SESSION['warning'];
                    unset($_SESSION['warning']);
                    ?>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>
            <div class="boxLogin mb-5">
                <form action="loginAdmin.php" method="POST">
                    <div class="mb-3">
                        <h3 for="exampleInputEmail1" class="text-center">Username</h3>
                        <input required type="text" class="form-control p-3" name="username">
                    </div>
                    <div class="mb-3">
                        <h3 for="exampleInputPassword1" class="text-center">Password</h3>
                        <input required type="password" class="form-control p-3" name="password">
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button type="submit" name="login" class="btn btn-lg p-3 btn-outline-primary">เข้าสู่ระบบ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <script src="bootstrap/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>