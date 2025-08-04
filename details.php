<?php
session_start();
require_once 'config/db.php';
require_once 'navbar.php';

$id = $_GET['id'];
if (!isset($_SESSION['admin_log'])) {
    $_SESSION['warning'] = "กรุณาเข้าสู่ระบบ";
    header("location: login.php");
}

if (isset($_SESSION['admin_log'])) {
    $admin = $_SESSION['admin_log'];
    $sql = "SELECT * FROM admin WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $admin);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (isset($_POST['status'])) {
    $return_date = $_POST['return_date'];
    $device_ids = explode(',', $_POST['device_id']); // Now an array: ['52', '35', '11']
    $admin = $_SESSION['admin_log'];
    $status = 2;
    $device_status = 2;
    date_default_timezone_set('Asia/Bangkok');
    $return_time = date('H:i:s');
    // Update the borrow record
    $sql = "UPDATE equipmentborrow
            SET return_date = :return_date,
                status = :status,
                username = :username,
                return_time = :return_time
            WHERE id_borrow = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":return_date", $return_date);
    $stmt->bindParam(":status", $status);
    $stmt->bindParam(":username", $admin);
    $stmt->bindParam(":return_time", $return_time);
    $stmt->bindParam(":id", $id);

    // Execute the update on equipmentborrow
    if ($stmt->execute()) {
        // Update each device's status
        $sqlUpdateDevice = "UPDATE device SET device_status = :device_status WHERE id = :device_id";
        $stmtUpdate = $conn->prepare($sqlUpdateDevice);

        foreach ($device_ids as $device_id) {
            $device_id = trim($device_id); // remove extra space just in case
            $stmtUpdate->bindParam(":device_status", $device_status);
            $stmtUpdate->bindParam(":device_id", $device_id);
            $stmtUpdate->execute();
        }

        $_SESSION['success'] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
        header("location: admin.php");
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการอัพเดทข้อมูลการยืม";
    }
}

if (isset($_POST['notyet'])) {
    $borrowed_time = $_POST['borrowed_time'];
    $return_date = $_POST['return_date'];
    $admin = $_SESSION['admin_log'];

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

$sql = "SELECT * FROM equipmentborrow WHERE id_borrow = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Split the device IDs
$deviceIds = explode(',', $user['device_id']); // ['52', '35', '11']

// Create a string of question marks for the IN clause
$placeholders = rtrim(str_repeat('?,', count($deviceIds)), ',');

// Fetch all matching device names
$sqlDevices = "SELECT * FROM device WHERE id IN ($placeholders)";
$stmtDevices = $conn->prepare($sqlDevices);
$stmtDevices->execute($deviceIds);
$devices = $stmtDevices->fetchAll(PDO::FETCH_ASSOC);

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
    <style>
        /* เพิ่มสไตล์ CSS เพื่อปรับแต่ง Navbar */
        .navbar {
            background-color: #365486;
            /* สีเขียว */
        }

        .navbar-brand {
            font-weight: 900;
            color: #fff !important;
            /* สีข้อความของ Navbar Brand */
        }

        .navbar-toggler-icon {
            background-color: #fff;
            /* สีไอคอน Toggle */
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            transition: border 0.3s;
            /* เพิ่ม transition เพื่อทำให้การเปลี่ยนสีเป็นจุดประสงค์ */
        }

        .navbar-nav .nav-link:hover {
            border-bottom: 2px solid #ffc107;
            /* สีกรอบเมื่อ Hover */
            color: #ffc107 !important;
        }

        .btn.dropdown-toggle {
            border-radius: 0 !important;
            /* Remove rounded corners */
            border-color: transparent !important;
            /* Ensure no border by default */
        }

        .btn.dropdown-toggle:focus,
        .btn.dropdown-toggle:active,
        .show>.btn.dropdown-toggle {
            border: none;
            border-bottom: 2px solid #ffc107 !important;
            /* Change border color */
            color: #ffc107 !important;
            /* Change text color */
        }

        .btn.dropdown-toggle:not(:focus):not(:active) {
            border-color: transparent !important;
            /* Remove border when losing focus */
            box-shadow: none !important;
            /* Remove Bootstrap's default focus glow */
        }
    </style>
</head>

<body>
    <?php navbar(); ?>
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
        <div class="card card-body rounded-4 shadow-sm p-4 mt-5">
            <h2 class="mb-4">ข้อมูลของ : <?= $user['borrower_name'] ?></h2>
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
                            <label>รายการอุปกรณ์</label>
                            <?php foreach ($devices as $device): ?>
                                <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($device['device_name']) ?>" disabled>
                            <?php endforeach; ?>
                            <input type="hidden" name="device_id" value="<?= htmlspecialchars($user['device_id']) ?>">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="mb-3">
                            <label for="exampleInputEmail1">เวลายืม</label>
                            <input readonly required name="borrowed_time" type="time" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="mb-3">
                            <label for="exampleInputEmail1">วันที่ยืม</label>
                            <input readonly disabled required value="<?= $user['borrowing_date'] ?>" name="borrowing_date" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="mb-3">
                            <label for="exampleInputEmail1">วันส่งคืน</label>
                            <input required value="<?= $user['return_date'] ?>" name="return_date" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1">ชื่อ-สกุล ผู้ยืม</label>
                            <input readonly disabled required value="<?= $user['borrower_name'] ?>" name="borrower_name" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1">หน่วยงาน</label>
                            <select disabled required name="department_id" class="form-select" aria-label="Default select example">
                                <option value="<?= $user['department_id'] ?>" disabled selected><?= $user['department_id'] ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1">เบอร์มือถือ</label>
                            <input readonly disabled required value="<?= $user['phone_number'] ?>" name="phone_number" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="exampleInputEmail1">เหตุผล</label>
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