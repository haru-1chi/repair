<?php
require_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/logo_20170112044011.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>ระบบยืมอุปกรณ์ออนไลน์ || ศูนย์คอมพิวเตอร์</title>
</head>

<body>
    <?php navbar() ?>
    <nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img class="logo" src="img/logo_20170112044011.png" alt="Logo" class="d-inline-block align-text-top">
                <span>
                    ระบบบริหารวัสดุหน่วยงาน
                </span>
            </a>
        </div>
    </nav>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-8">
                <div class="imageTitle">
                    <img src="img/24242.png" alt="">
                </div>
            </div>
            <div class="col-4">
                <div class="box">
                    <div class="content">
                        <br>
                        <br>
                        <h1 class="text-center">ระบบยืมอุปกรณ์ออนไลน์</h1>
                        <p class="text-center">สร้างเมื่อ 14 พฤศจิกายน 2566</p>
                        <div class="d-flex justify-content-center">
                            <div class="d-grid buttonSpace te gap-3">
                                <a href="borrow.php" class="btN-Green">ยืม</a>
                                <a href="login.php" class="btN-Red">สำหรับเจ้าหน้าที่</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 footer mt-auto py-3" style="background: #fff;">

        <marquee style="font-weight: bold; font-size: 1rem"><span style="font-size: 1rem" class="text-muted text-center">Design website by นายอภิชน ประสาทศรี , พุฒิพงศ์ ใหญ่แก้ว &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Coding โดย นายอานุภาพ ศรเทียน &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ควบคุมโดย นนท์ บรรณวัฒน์ นักวิชาการคอมพิวเตอร์ ปฏิบัติการ</span>
        </marquee>

    </footer>

    <script src="bootstrap/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>