<?php
session_start();
require_once 'config/db.php';
$id = $_GET['id'];
if (!isset($_SESSION['admin'])) {
    $_SESSION['warning'] = "กรุณาเข้าสู่ระบบ";
    header("location: login.php");
}

if (isset($_SESSION['admin'])) {
    $admin = $_SESSION['admin'];
    $sql = "SELECT * FROM admin WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $admin);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (isset($_POST['status'])) {
    $borrowed_time = $_POST['borrowed_time'];
    $return_date = $_POST['return_date'];
    $device_id = $_POST['device_id'];
    $admin = $_SESSION['admin'];
    $status = 2;
    $device_status = 2;
    $sql = "UPDATE equipmentborrow
    SET borrowed_time = :borrowed_time , return_date = :return_date ,status = :status,username = :username
    WHERE id_borrow = $id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":borrowed_time", $borrowed_time);
    $stmt->bindParam(":return_date", $return_date);
    $stmt->bindParam(":status", $status);
    $stmt->bindParam(":username", $admin);

    $sqlInsert = "UPDATE device SET device_status = :device_status WHERE id = :device_id";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bindParam(":device_status", $device_status);
    $stmtInsert->bindParam(":device_id", $device_id);
    $stmtInsert->execute();

    if ($stmt->execute()) {
        $_SESSION['success'] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
        header("location: admin.php");
    }
}
if (isset($_POST['notyet'])) {
    $borrowed_time = $_POST['borrowed_time'];
    $return_date = $_POST['return_date'];
    $admin = $_SESSION['admin'];

    $status = 1;
    $sql = "UPDATE equipmentborrow
    SET borrowed_time = :borrowed_time , return_date = :return_date ,status = :status,username = :username
    WHERE id_borrow = $id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":borrowed_time", $borrowed_time);
    $stmt->bindParam(":return_date", $return_date);
    $stmt->bindParam(":username", $admin);
    $stmt->bindParam(":status", $status);
    if ($stmt->execute()) {
        $_SESSION['success'] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
        header("location: admin.php");
    }
}
$sql = "SELECT eq.*,dv.*
FROM equipmentborrow AS eq
INNER JOIN device as dv ON dv.id = eq.device_id
WHERE eq.id_borrow = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบยืมอุปกรณ์ออนไลน์ || <?= $user['borrower_name'] ?></title>
    <link rel="shortcut icon" href="img/logo_20170112044011.png" type="image/x-icon">

    <link rel="stylesheet" href="bootstrap/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <script>
        window.onload = function() {
            const currentTime = new Date();
            const hours = currentTime.getHours();
            const minutes = currentTime.getMinutes();
            const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
            document.querySelector('input[name="borrowed_time"]').value = formattedTime;
        };
    </script>
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between">
            <h1>ข้อมูลของ : <?= $user['borrower_name'] ?></h1>
            <a href="admin.php" class="btn btn-secondary mb-3">กลับ</a>
        </div>
        <form action="" method="POST">
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
            <div class="row">
                <div class="col-sm-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">รายการอุปกรณ์</label>
                        <select disabled required name="" class="form-select" aria-label="Default select example">
                            <option value="<?= $user['device_id'] ?>" disabled selected><?= $user['device_name'] ?></option>
                        </select>
                        <input type="hidden" value="<?= $user['device_id'] ?>" name="device_id">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">เวลายืม</label>
                        <input readonly required name="borrowed_time" type="time" class="form-control">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">วันที่ยืม</label>
                        <input readonly disabled required value="<?= $user['borrowing_date'] ?>" name="borrowing_date" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">วันส่งคืน</label>
                        <input required value="<?= $user['return_date'] ?>" name="return_date" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">ชื่อ-สกุล ผู้ยืม</label>
                        <input readonly disabled required value="<?= $user['borrower_name'] ?>" name="borrower_name" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">หน่วยงาน</label>
                        <select disabled required name="department_id" class="form-select" aria-label="Default select example">
                            <option value="<?= $user['department_id'] ?>" disabled selected><?= $user['department_id'] ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">เบอร์มือถือ</label>
                        <input readonly disabled required value="<?= $user['phone_number'] ?>" name="phone_number" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">เหตุผล</label>
                        <textarea readonly disabled required name="purpose" class="form-control"><?= $user['purpose'] ?></textarea>
                    </div>
                </div>
            </div>
            <?php
            if ($user['username'] == null) { ?>
                <h5>ผู้อนุมัติ : ยังไม่มี</h5>

            <?php } else { ?>

                <h5>ผู้อนุมัติ : <?= $user['username'] ?></h5>
            <?php  }
            ?>
            <div class="d-grid gap-3">
                <button type="submit" name="status" class="btn p-3 btn-success">คืนแล้ว</button>
                <button type="submit" name="notyet" class="btn p-3 btn-warning">อนุมัติแล้ว</button>
            </div>
        </form>
    </div>


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    f

    <script src="bootstrap/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>