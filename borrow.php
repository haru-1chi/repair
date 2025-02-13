<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['submit'])) {
    // รับข้อมูลจากฟอร์ม
    $device_id = $_POST['device_id'];
    // $borrowed_time = $_POST['borrowed_time'];
    $borrowing_date = $_POST['borrowing_date'];
    $return_date = $_POST['return_date'];
    $borrower_name = $_POST['borrower_name'];
    $department_id = $_POST['department_id'];
    $phone_number = $_POST['phone_number'];
    $purpose = $_POST['purpose'];
    $device_status = 1;
    $status = 0;
    try {
        // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูล
        $sql = "INSERT INTO equipmentborrow (device_id,borrowing_date, return_date, borrower_name, department_id, phone_number, purpose, status)
            VALUES (:device_id, :borrowing_date, :return_date, :borrower_name, :department_id, :phone_number, :purpose, :status)";
        $stmt = $conn->prepare($sql);

        // ผูกค่าพารามิเตอร์
        $stmt->bindParam(':device_id', $device_id);
        // $stmt->bindParam(':borrowed_time', $borrowed_time);
        $stmt->bindParam(':borrowing_date', $borrowing_date);
        $stmt->bindParam(':return_date', $return_date);
        $stmt->bindParam(':borrower_name', $borrower_name);
        $stmt->bindParam(':department_id', $department_id);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':purpose', $purpose);
        $stmt->bindParam(':status', $status);


        $sqlInsert = "UPDATE device SET device_status = :device_status WHERE id = :device_id";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bindParam(":device_status", $device_status);
        $stmtInsert->bindParam(":device_id", $device_id);
        $stmtInsert->execute();
        if ($stmt->execute()) {
            $_SESSION['success'] = "ส่งคำขอยืมแล้ว ติดต่อรับที่ศูนย์คอมพิวเตอร์";
            header("refresh: 5; url=borrow.php"); // หน่วงเวลา 5
        }

        // echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="bootstrap/bootstrap/css/bootstrap.min.css">
    <link rel="shortcut icon" href="img/logo_20170112044011.png" type="image/x-icon">

    <title>ระบบยืมอุปกรณ์ออนไลน์ || ศูนย์คอมพิวเตอร์</title>
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
        <div class="mt-5">&nbsp;</div>

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
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">รายการอุปกรณ์</label>
                        <select required name="device_id" class="form-select" aria-label="Default select example">
                            <?php
                            $sql = "SELECT * FROM device WHERE device_status NOT IN (1, 3, 4, 5)";
                            $device = $conn->prepare($sql);
                            $device->execute();
                            $Alldevice = $device->fetchAll();
                            ?>
                            <option value="" disabled selected>เลือกรายการอุปกรณ์</option>
                            <?php foreach ($Alldevice as $d) { ?>
                                <option value="<?= $d['id'] ?>"><?= $d['device_name'] ?></option>
                            <?php  } ?>
                        </select>

                    </div>
                </div>
                <!-- <div class="col-sm-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">เวลายืม</label>
                        <input required name="borrowed_time" type="time" class="form-control">
                    </div>
                </div> -->
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">วันที่ยืม</label>
                        <input required name="borrowing_date" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">วันส่งคืน</label>
                        <input required name="return_date" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">ชื่อ-สกุล ผู้ยืม</label>
                        <input required name="borrower_name" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label class="form-label" for="departInput">หน่วยงาน</label>
                        <input type="text" required class="form-control" id="departInput" name="department_id">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">เบอร์มือถือ</label>
                        <input required name="phone_number" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">เหตุผล</label>
                        <textarea name="purpose" class="form-control" required></textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-3 mb-5">
                <button type="submit" name="submit" class="btn btn-primary p-3">บันทึก</button>
                <a href="index.php" class="btn btn-secondary p-3">กลับ</a>
            </div>
        </form>
    </div>
    <footer class="mt-5 footer mt-auto py-3" style="background: #fff;">

        <marquee style="font-weight: bold; font-size: 1rem"><span style="font-size: 1rem" class="text-muted text-center">Design website by นายอภิชน ประสาทศรี , พุฒิพงศ์ ใหญ่แก้ว &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Coding โดย นายอานุภาพ ศรเทียน &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ควบคุมโดย นนท์ บรรณวัฒน์ นักวิชาการคอมพิวเตอร์ ปฏิบัติการ</span>
        </marquee>

    </footer>


    <script src="bootstrap/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>

</html>