<?php
session_start();
require_once 'config/db.php';
require_once 'navbar.php';

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

if (isset($_GET['username'])) {
    $deleteid = $_GET['username'];
    $stmtDelete = $conn->prepare("DELETE FROM admin WHERE username = :username");
    $stmtDelete->bindParam(":username", $deleteid);
    $stmtDelete->execute();

    if ($stmtDelete) {
        $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
        header("Location: admin.php");
    }
}
if (isset($_GET['device'])) {
    $deleteid = $_GET['device'];
    $stmtDelete = $conn->query("DELETE FROM device WHERE id = $deleteid");
    $stmtDelete->execute();

    if ($stmtDelete) {
        $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
        header("Location: admin.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบยืมอุปกรณ์ออนไลน์ || Admin</title>
    <link rel="shortcut icon" href="img/logo_20170112044011.png" type="image/x-icon">

    <link rel="stylesheet" href="bootstrap/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        #borrow tbody tr td {
            background-color: #f2f7ff;
            color: #000;
        }

        #borrow2 tbody tr td {
            background-color: #f2f7ff;
            color: #000;
        }

        #return tbody tr td {
            background-color: #f2f7ff;
            color: #000;
        }

        #admin tbody tr td {
            background-color: #f2f7ff;
            color: #000;
        }

        #device tbody tr td {
            background-color: #f2f7ff;
            color: #000;
        }

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

        .card-hover {
            transition: background-color 0.3s ease;
            cursor: pointer;
            /* 👈 Add this */
        }

        .bg-info.card-hover:hover {
            background-color: rgb(0, 163, 196) !important;
            /* darker blue */
        }

        .bg-danger.card-hover:hover {
            background-color: rgb(190, 22, 39) !important;
        }

        .bg-success.card-hover:hover {
            background-color: rgb(7, 100, 56) !important;
        }

        .bg-secondary.card-hover:hover {
            background-color: rgb(71, 82, 92) !important;
        }

        .section {
            position: absolute;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.2s ease;
            width: 100%;
        }

        .section.active {
            position: static;
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>

<body>
    <?php navbar(); ?>
    <!-- <nav class="navbar p-3 navbar-expand-lg bg-green text-center">
        <div class="container">
            <a class="navbar-brand" href="../orderit/dashboard.php">ระบบบริหารงานซ่อม</a>
            <div class="collapse navbar-collapse align-items-center" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="borrow.php">ยืม</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">สำหรับเจ้าหน้าที่</a>
                    </li>
                    <li class="nav-item ms-5">
                        <a class="nav-link" href="system/logout.php" type="submit">ออกจากระบบ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav> -->

    <div class="container mt-4 mb-5">

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
        <!-- Button trigger modal -->

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">เพิ่มเจ้าหน้าที่</h1>
                    </div>
                    <div class="modal-body">
                        <form action="system/insert.php" method="POST">
                            <div class="mb-3">
                                <h5 for="exampleInputEmail1">Username</h5>
                                <input required type="text" class="form-control" name="username">
                            </div>
                            <div class="mb-3">
                                <h5 for="exampleInputPassword1">Password</h5>
                                <input required type="password" class="form-control" name="password">
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="insert" class="btn btn-primary">บันทึก</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>



        <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">เพิ่มอุปกรณ์</h1>
                    </div>
                    <div class="modal-body">
                        <form action="system/insert.php" method="POST">
                            <div class="mb-3">
                                <h5 for="exampleInputEmail1">ชื่ออุปกรณ์</h5>
                                <input required type="text" class="form-control" name="device_name">
                                <h5 for="exampleInputEmail1" class="mt-2">หมายเลขครุภัณฑ์</h5>
                                <input required type="text" class="form-control" name="number_device">
                                <h5 for="exampleInputEmail1" class="mt-2">อุปกรณ์เพิ่มเติม</h5>
                                <input required type="text" class="form-control" name="device_enc">
                                <h5 for="exampleInputEmail1" class="mt-2">สถานะอุปกรณ์</h5>
                                <select class="form-select" name="device_status" id="">
                                    <option value="1">ยืม</option>
                                    <option value="2" selected>ว่าง</option>
                                    <option value="3">ชำรุด</option>
                                    <option value="4">ซ่อมแซม</option>
                                    <option value="5">งดยืม</option>
                                </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="insertDevice" class="btn btn-primary">บันทึก</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <?php
            $sqlBorrow = "
    SELECT 
                COUNT(CASE WHEN status != 2 THEN 1 END) AS pending,
        COUNT(CASE WHEN STR_TO_DATE(eq.return_date, '%Y-%m-%d') < CURDATE() AND status != 2 THEN 1 END) AS overdue,
        COUNT(CASE WHEN status = 2 THEN 1 END) AS returned 
    FROM equipmentborrow eq
";
            $resultBorrow = $conn->query($sqlBorrow);
            $borrowData = $resultBorrow->fetch(PDO::FETCH_ASSOC);

            // Query for device count
            $sqlDevice = "SELECT COUNT(*) AS equipment FROM device";
            $resultDevice = $conn->query($sqlDevice);
            $deviceData = $resultDevice->fetch(PDO::FETCH_ASSOC);

            $cards = [
                ['id' => 'borrowed', 'label' => 'กำลังยืม', 'bg' => 'bg-info', 'count' => $borrowData['pending']],
                ['id' => 'overdue', 'label' => 'เลยกำหนด', 'bg' => 'bg-danger', 'count' => $borrowData['overdue']],
                ['id' => 'returned', 'label' => 'คืนแล้ว', 'bg' => 'bg-success', 'count' => $borrowData['returned']],
                ['id' => 'equipment', 'label' => 'อุปกรณ์', 'bg' => 'bg-secondary', 'count' => $deviceData['equipment']],
            ];
            ?>

            <div class="d-flex justify-content-center flex-wrap gap-5 mb-3">
                <?php foreach ($cards as $card): ?>
                    <div class="rounded-3 px-3 pb-2 text-white <?= $card['bg'] ?> card-hover"
                        style="width: 12rem;"
                        onclick="showSection('<?= $card['id'] ?>')">
                        <div class="card-header">
                            <div class="d-flex align-items-end">
                                <p style="font-size: 45px; margin: 0px;"><?= $card['count'] ?></p>
                                <p class="ms-2" style="font-size: 32px; margin: 0px; margin-bottom:.4rem;">รายการ</p>
                            </div>
                            <p style="font-size: 20px; margin: 0px;"><?= $card['label'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-sm-12 section active" id="borrowed-section">
                <h1 class="text-center">จำนวนผู้ยืม</h1>
                <table id="borrow" class="table table-hover table-primary mt-5">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">ชื่อ</th>
                            <th class="text-center" scope="col">หน่วยงาน</th>
                            <th class="text-center" scope="col">อุปกรณ์ที่ยืม</th>
                            <th class="text-center" scope="col">วันที่ยืม - วันส่งคืน</th>
                            <th class="text-center" scope="col">จำนวนวันยืม</th>
                            <th class="text-center" scope="col">เลยกำหนด</th>
                            <th class="text-center" scope="col">สถานะ</th>
                            <th class="text-center" scope="col">ตรวจสอบ</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php

                        function formatThaiDate($date)
                        {
                            $thaiMonths = [
                                "01" => "ม.ค.",
                                "02" => "ก.พ.",
                                "03" => "มี.ค.",
                                "04" => "เม.ย.",
                                "05" => "พ.ค.",
                                "06" => "มิ.ย.",
                                "07" => "ก.ค.",
                                "08" => "ส.ค.",
                                "09" => "ก.ย.",
                                "10" => "ต.ค.",
                                "11" => "พ.ย.",
                                "12" => "ธ.ค."
                            ];

                            $dateObj = DateTime::createFromFormat('Y-m-d', $date);
                            if (!$dateObj) return '-'; // Handle invalid dates

                            $year = $dateObj->format('Y') + 543; // Convert to Thai Buddhist year
                            $month = $thaiMonths[$dateObj->format('m')]; // Get Thai month name
                            $day = $dateObj->format('d'); // Get day

                            return "$day $month $year";
                        }

                        function calculateBorrowDays($borrowDate, $returnDate)
                        {
                            $borrowDateObj = DateTime::createFromFormat('Y-m-d', $borrowDate);
                            $returnDateObj = DateTime::createFromFormat('Y-m-d', $returnDate);

                            if (!$borrowDateObj || !$returnDateObj) return '-'; // Handle invalid dates

                            $diff = $borrowDateObj->diff($returnDateObj);
                            return $diff->days . ' วัน';
                        }

                        function calculateOverdueDays($returnDate)
                        {
                            $returnDateObj = DateTime::createFromFormat('Y-m-d', $returnDate);
                            $currentDateObj = new DateTime();

                            if (!$returnDateObj) return '-'; // Handle invalid dates

                            if ($currentDateObj > $returnDateObj) {
                                $diff = $returnDateObj->diff($currentDateObj);
                                return $diff->days . ' วัน';
                            }

                            return '-';
                        }

                        $sql = "SELECT eq.*,dv.*
                FROM equipmentborrow AS eq
                INNER JOIN device as dv ON dv.id = eq.device_id
                WHERE status <> 2 ORDER BY status DESC
                ";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $user = $stmt->fetchAll();
                        foreach ($user as $d) {
                            $deviceListStmt = $conn->prepare("SELECT id, device_name FROM device");
                            $deviceListStmt->execute();
                            $allDevices = $deviceListStmt->fetchAll(PDO::FETCH_KEY_PAIR);
                            $device_ids = explode(',', $d['device_id']);
                            $device_names = array_map(function ($id) use ($allDevices) {
                                return "$id " . ($allDevices[trim($id)] ?? "Unknown");
                            }, $device_ids);
                            $device_names_str = implode("\n", $device_names);

                            if ($d['status'] == 1) {
                                $status = "อนุมัติแล้ว";
                            } else if ($d['status'] == 2) {
                                $status = "คืนแล้ว";
                            } else {
                                $status = "ยังไม่อนุมัติ";
                            }
                        ?>
                            <tr>
                                <td><?= $d['id_borrow'] ?></td>
                                <td scope="row"><?= $d['borrower_name'] ?></td>
                                <td><?= $d['department_id'] ?></td>
                                <td><?= nl2br($device_names_str) ?></td>
                                <td><?= formatThaiDate($d['borrowing_date']) . ' - ' . formatThaiDate($d['return_date']) ?></td>
                                <td><?= calculateBorrowDays($d['borrowing_date'], $d['return_date']) ?></td>
                                <td>
                                    <p style="margin: 0; color: <?= (calculateOverdueDays($d['return_date']) !== '-') ? 'red' : 'black' ?>;">
                                        <?= calculateOverdueDays($d['return_date']) ?>
                                    </p>
                                </td>

                                <?php
                                if ($d['status'] == 1) { ?>
                                    <td>
                                        <div style="background-color: #ffc107; text-align: center; color: white; padding: 0px; border-radius: 5px;">
                                            <?= $status ?><br>( <?= $d['username'] ?> )
                                        </div>
                                    </td>
                                <?php } // ตรงนี้
                                else if ($d['status'] == 2) { ?>
                                    <td>
                                        <div style="background-color: green; text-align: center; color: white; padding: 0px; border-radius: 5px;">
                                            <?= $status ?><br>( <?= $d['username'] ?> )
                                        </div>
                                    </td>
                                <?php  } else { ?>
                                    <td>
                                        <div style="background-color: #ef4444; text-align: center; color: white; padding: 12px; border-radius: 5px;">
                                            <?= $status ?>
                                        </div>
                                    </td>
                                <?php }
                                ?>

                                <td>
                                    <a class="btn btn-warning" href="details.php?id=<?= $d['id_borrow'] ?>">ตรวจสอบ</a>
                                </td>

                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-12 section" id="overdue-section">
                <h1 class="text-center">เลยกำหนด</h1>
                <table id="borrow2" class="table table-hover table-primary">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">ชื่อ</th>
                            <th class="text-center" scope="col">หน่วยงาน</th>
                            <th class="text-center" scope="col">อุปกรณ์ที่ยืม</th>
                            <th class="text-center" scope="col">วันที่ยืม - วันส่งคืน</th>
                            <th class="text-center" scope="col">จำนวนวันยืม</th>
                            <th class="text-center" scope="col">เลยกำหนด</th>
                            <th class="text-center" scope="col">สถานะ</th>
                            <th class="text-center" scope="col">ตรวจสอบ</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php
                        $currentDate = date('Y-m-d');

                        $sql = "SELECT eq.*,dv.*
                        FROM equipmentborrow AS eq
                        INNER JOIN device as dv ON dv.id = eq.device_id
                        WHERE eq.status = 1 AND STR_TO_DATE(eq.return_date, '%Y-%m-%d') < '$currentDate'
                        ORDER BY eq.status DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $user = $stmt->fetchAll();
                        foreach ($user as $d) {
                            $deviceListStmt = $conn->prepare("SELECT id, device_name FROM device");
                            $deviceListStmt->execute();
                            $allDevices = $deviceListStmt->fetchAll(PDO::FETCH_KEY_PAIR);
                            $device_ids = explode(',', $d['device_id']);
                            $device_names = array_map(function ($id) use ($allDevices) {
                                return "$id " . ($allDevices[trim($id)] ?? "Unknown");
                            }, $device_ids);
                            $device_names_str = implode("\n", $device_names);
                            if ($d['status'] == 1) {
                                $status = "อนุมัติแล้ว";
                                $statusColor = 'background-color: #ffc107;';
                            } else if ($d['status'] == 2) {
                                $status = "คืนแล้ว";
                                $statusColor = 'background-color: green;';
                            } else {
                                $status = "ยังไม่อนุมัติ";
                                $statusColor = 'background-color: red;';
                            }
                        ?>
                            <tr>
                                <td><?= $d['id_borrow'] ?></td>
                                <td scope="row"><?= $d['borrower_name'] ?></td>
                                <td><?= $d['department_id'] ?></td>
                                <td><?= nl2br($device_names_str) ?></td>
                                <td><?= formatThaiDate($d['borrowing_date']) . ' - ' . formatThaiDate($d['return_date']) ?></td>
                                <td><?= calculateBorrowDays($d['borrowing_date'], $d['return_date']) ?></td>
                                <td>
                                    <p style="margin: 0; color: <?= (calculateOverdueDays($d['return_date']) !== '-') ? 'red' : 'black' ?>;">
                                        <?= calculateOverdueDays($d['return_date']) ?>
                                    </p>
                                </td>

                                <td>
                                    <div style="<?= $statusColor ?> text-align: center; color: white; padding: 0px; border-radius: 5px;">
                                        <?= $status ?><br>( <?= $d['username'] ?> )
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="details.php?id=<?= $d['id_borrow'] ?>">ตรวจสอบ</a>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12 section" id="returned-section">
                <h1 class="text-center">คืนแล้ว</h1>
                <table id="return" class="table table-hover table-primary">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">ชื่อ</th>
                            <th class="text-center" scope="col">หน่วยงาน</th>
                            <th class="text-center" scope="col">อุปกรณ์ที่ยืม</th>
                            <th class="text-center" scope="col">วันที่ยืม - วันส่งคืน</th>
                            <th class="text-center" scope="col">จำนวนวันยืม</th>
                            <th class="text-center" scope="col">เวลาที่คืน</th>
                            <th class="text-center" scope="col">สถานะ</th>
                            <th class="text-center" scope="col">ตรวจสอบ</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php
                        $currentDate = date('Y-m-d');
                        $sql = "SELECT eq.*,dv.*
                        FROM equipmentborrow AS eq
                        INNER JOIN device as dv ON dv.id = eq.device_id
                        WHERE eq.status = 2
                        ORDER BY eq.status DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $user = $stmt->fetchAll();
                        foreach ($user as $d) {
                            $status = '';
                            $statusColor = '';

                            if (empty($d['return_time']) || $d['return_time'] === '00:00:00.000000') {
                                $returnTimeFormatted = '-';
                            } else {
                                $returnTimeFormatted = date('H:i', strtotime($d['return_time'])) . ' น.';
                            }

                            if ($d['status'] == 1) {
                                $status = "อนุมัติแล้ว";
                                $statusColor = 'background-color: #ffc107;';
                            } else if ($d['status'] == 2) {
                                $status = "คืนแล้ว";
                                $statusColor = 'background-color: green;';
                            } else {
                                $status = "ยังไม่อนุมัติ";
                                $statusColor = 'background-color: red;';
                            }
                        ?>
                            <tr>
                                <td><?= $d['id_borrow'] ?></td>
                                <td scope="row"><?= $d['borrower_name'] ?></td>
                                <td><?= $d['department_id'] ?></td>
                                <td><?= $d['device_name'] ?></td>
                                <td><?= formatThaiDate($d['borrowing_date']) . ' - ' . formatThaiDate($d['return_date']) ?></td>
                                <td><?= calculateBorrowDays($d['borrowing_date'], $d['return_date']) ?></td>

                                <td><?= $returnTimeFormatted  ?></td>
                                <td>
                                    <div style="<?= $statusColor ?> text-align: center; color: white; padding: 0px; border-radius: 5px;">
                                        <?= $status ?><br>( <?= $d['username'] ?> )
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="details.php?id=<?= $d['id_borrow'] ?>">ตรวจสอบ</a>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12 section" id="equipment-section">
                <h1 class="text-center">อุปกรณ์</h1>
                <div class="d-flex justify-content-end gap-4">
                    <button class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop2" style="margin-top: -45px;">+ เพิ่มอุปกรณ์</button>
                </div>
                <table id="device" class="table table-hover table-primary">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">อุปกรณ์</th>
                            <th class="text-center" scope="col">หมายเลขครุภัณฑ์</th>
                            <th class="text-center" scope="col">อุปกรณ์เพิ่มเติม</th>
                            <th class="text-center" scope="col">สถานะ</th>
                            <th class="text-center" scope="col">แก้ไข</th>
                            <th class="text-center" scope="col">ลบ</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php
                        $sql = "SELECT * FROM device";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $user = $stmt->fetchAll();

                        foreach ($user as $d) {
                            $status_device = "";
                            $statusColor = "";

                            if ($d['device_status'] == 1) {
                                $status_device = "ยืม";
                                $statusColor = "background-color: #FACC15;"; // Yellow
                            } else if ($d['device_status'] == 2) {
                                $status_device = "ว่าง";
                                $statusColor = "background-color: #22C55E;"; // Green
                            } else if ($d['device_status'] == 3) {
                                $status_device = "ชำรุด";
                                $statusColor = "background-color: #EF4444;"; // Red
                            } else if ($d['device_status'] == 4) {
                                $status_device = "ซ่อมแซม";
                                $statusColor = "background-color: #FB923C;"; // Orange
                            } else if ($d['device_status'] == 5) {
                                $status_device = "งดยืม";
                                $statusColor = "background-color: gray;"; // Gray
                            }
                        ?>
                            <tr>
                                <td scope="row"><?= $d['device_name'] ?></td>
                                <td scope="row"><?= $d['number_device'] ?></td>
                                <td scope="row"><?= $d['device_enc'] ?></td>
                                <td>
                                    <div style="<?= $statusColor ?> text-align: center; color: white; padding: 0px; border-radius: 5px;">
                                        <?= $status_device ?>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editDevice<?= $d['id'] ?>">
                                        แก้ไข
                                    </button>
                                </td>
                                <div class="modal fade" id="editDevice<?= $d['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">เพิ่มอุปกรณ์</h1>
                                            </div>
                                            <div class="modal-body">
                                                <form action="system/update.php" method="POST">
                                                    <div class="mb-3">
                                                        <h5 for="exampleInputEmail1">ชื่ออุปกรณ์</h5>
                                                        <input required type="text" value="<?= $d['device_name'] ?>" class="form-control" name="device_name">
                                                        <h5 for="exampleInputEmail1" class="mt-2">หมายเลขครุภัณฑ์</h5>
                                                        <input required type="text" value="<?= $d['number_device'] ?>" class="form-control" name="number_device">
                                                        <h5 for="exampleInputEmail1" class="mt-2">อุปกรณ์เพิ่มเติม</h5>
                                                        <input required type="text" value="<?= $d['device_enc'] ?>" class="form-control" name="device_enc">

                                                        <h5 for="exampleInputEmail1" class="mt-2">สถานะอุปกรณ์</h5>

                                                        <select class="form-select" name="device_status" id="">
                                                            <option value="<?= $d['device_status'] ?>" selected><?= $status_device ?></option>

                                                            <?php
                                                            $statuses = [
                                                                1 => 'ยืม',
                                                                2 => 'ว่าง',
                                                                3 => 'ชำรุด',
                                                                4 => 'ซ่อมแซม',
                                                                5 => 'งดยืม',
                                                            ];

                                                            foreach ($statuses as $value => $label) {
                                                                // Exclude the currently selected status
                                                                if ($value != $d['device_status']) {
                                                            ?>
                                                                    <option value="<?= $value ?>"><?= $label ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>



                                                        <input required type="hidden" value="<?= $d['id'] ?>" class="form-control" name="id">
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="UpdateDevice" class="btn btn-primary">บันทึก</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <td> <button onclick="confirmDelete(<?= $d['id'] ?>)" class="btn btn-danger">ลบ</button></td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showSection(sectionName) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => {
                section.classList.remove('active');
            });
            const target = document.getElementById(`${sectionName}-section`);
            if (target) {
                target.classList.add('active');
            }
        }
    </script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to delete URL
                    window.location.href = '?device=' + id;
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#borrow').DataTable({
                "order": [
                    [0, 'desc']
                ]
            });
            $('#borrow2').DataTable({
                "order": [
                    [0, 'desc']
                ]
            });
            $('#return').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 100],
                "order": [
                    [0, 'desc']
                ]
            });

            // $('#return').DataTable({
            //     "pageLength": 5,
            //     "lengthMenu": [5, 10, 25, 50, 100],
            //     "order": [
            //         [0, 'desc']
            //     ]
            // });
            $('#depart').DataTable();
            $('#admin').DataTable();
            $('#device').DataTable();
        });
    </script>


    <script src="bootstrap/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>