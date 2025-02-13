<?php
session_start();
require_once 'config/db.php';

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
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #365486; width: 100%;
  height: 100px;">
        <div class="container">
            <a class="navbar-brand" style="color: white;" href="#"><?= $data['username'] ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a type="button" style="color: white; cursor: pointer;" class="nav-link" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            เพิ่มเจ้าหน้าที่
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: white; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                            เพิ่มอุปกรณ์
                        </a>
                    </li>

                </ul>
                <form class="d-flex" role="search">
                    <a href="system/logout.php" class="btn btn-danger" type="submit">ออกจากระบบ</a>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-5 mb-5">


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
                                <h5 for="exampleInputEmail1" class="text-center">Username</h5>
                                <input required type="text" class="form-control" name="username">
                            </div>
                            <div class="mb-3">
                                <h5 for="exampleInputPassword1" class="text-center">Password</h5>
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
                                <h5 for="exampleInputEmail1" class="text-center">ชื่ออุปกรณ์</h5>
                                <input required type="text" class="form-control" name="device_name">
                                <h5 for="exampleInputEmail1" class="text-center">หมายเลขครุภัณฑ์</h5>
                                <input required type="text" class="form-control" name="number_device">
                                <h5 for="exampleInputEmail1" class="text-center">อุปกรณ์เพิ่มเติม</h5>
                                <input required type="text" class="form-control" name="device_enc">
                                <h5 for="exampleInputEmail1" class="text-center">สถานะอุปกรณ์</h5>
                                <select class="form-select" name="device_status" id="">
                                    <option required disabled value="" selected>สถานะ</option>
                                    <option value="1">ยืม</option>
                                    <option value="2">ว่าง</option>
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

        <hr>
        <div class="row">
            <h1 class="text-center">จำนวนผู้ยืม</h1>
            <div class="col-sm-12">

                <table id="borrow" class="table table-hover table-primary mt-5">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">ชื่อ</th>
                            <th class="text-center" scope="col">หน่วยงาน</th>
                            <th class="text-center" scope="col">อุปกรณ์ที่ยืม</th>
                            <th class="text-center" scope="col">วันที่ยืม - วันส่งคืน</th>
                            <th class="text-center" scope="col">สถานะ</th>
                            <th class="text-center" scope="col">ตรวจสอบ</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
                        $sql = "SELECT eq.*,dv.*
                FROM equipmentborrow AS eq
                INNER JOIN device as dv ON dv.id = eq.device_id
                WHERE status <> 2 ORDER BY status DESC
                ";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $user = $stmt->fetchAll();
                        foreach ($user as $d) {
                            if ($d['status'] == 1) {
                                $status = "อนุมัติแล้ว";
                            } else if ($d['status'] == 2) {
                                $status = "คืนแล้ว";
                            } else {
                                $status = "ยังไม่อนุมัติ";
                            }
                        ?>
                            <tr>
                                <th scope="row"><?= $d['borrower_name'] ?></th>
                                <td><?= $d['department_id'] ?></td>
                                <td><?= $d['device_name'] ?></td>
                                <td><?= $d['borrowing_date'] . ' - ' . $d['return_date'] ?></td>
                                <?php
                                if ($d['status'] == 1) { ?>
                                    <td style="background-color: #ffc107; text-align: center; color: white; width: 8rem; padding: 16px;"><?= $status ?><br>( <?= $d['username'] ?> )</td>
                                <?php } // ตรงนี้
                                else if ($d['status'] == 2) { ?>
                                    <td style="background-color: green; text-align: center; color: white; width: auto; padding: 16px;"><?= $status ?><br>( <?= $d['username'] ?> )</td>
                                <?php  } else { ?>
                                    <td style="background-color: red; text-align: center; color: white; width: auto; padding: 16px;"><?= $status ?></td>
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
            <hr>
            <h1 class="text-center">ครบกำหนดแล้ว แต่ยังไม่คืน</h1>

            <div class="col-sm-12">
                <table id="borrow2" class="table table-hover table-primary mt-5">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">ชื่อ</th>
                            <th class="text-center" scope="col">หน่วยงาน</th>
                            <th class="text-center" scope="col">อุปกรณ์ที่ยืม</th>
                            <th class="text-center" scope="col">วันที่ยืม - วันส่งคืน</th>
                            <th class="text-center" scope="col">สถานะ</th>
                            <th class="text-center" scope="col">ตรวจสอบ</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
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
                            $status = '';
                            $statusColor = '';
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
                                <th scope="row"><?= $d['borrower_name'] ?></th>
                                <td><?= $d['department_id'] ?></td>
                                <td><?= $d['device_name'] ?></td>
                                <td><?= $d['borrowing_date'] . ' - ' . $d['return_date'] ?></td>
                                <td style="<?= $statusColor ?> text-align: center; color: white; width: auto; padding: 16px;"><?= $status ?><br>( <?= $d['username'] ?> )</td>
                                <td>
                                    <a class="btn btn-warning" href="details.php?id=<?= $d['id_borrow'] ?>">ตรวจสอบ</a>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>


        <hr>
        <div class="row mb-5">
            <div class="col-sm-6 mt-5">
                <h5 class="text-center">เจ้าหน้าที่</h5>
                <table id="admin" class="table table-hover table-primary mt-5">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">ชื่อ</th>
                            <th class="text-center" scope="col">ลบ</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
                        $sql = "SELECT * FROM admin";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $user = $stmt->fetchAll();
                        foreach ($user as $d) {
                            if ($d['username'] != $admin) { ?>
                                <tr>
                                    <th scope="row"><?= $d['username'] ?></th>
                                    <td><a onclick="confirm('ต้องการลบข้อมูลใช่หรือไม่')" class="btn btn-danger" href="?username=<?= $d['username'] ?>">ลบ</a></td>
                                </tr>
                            <?php    }
                            ?>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6 mt-5">
                <h5 class="text-center">อุปกรณ์</h5>
                <table id="device" class="table table-hover table-primary mt-5">
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
                    <tbody class="text-center">
                        <?php
                        $sql = "SELECT * FROM device";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $user = $stmt->fetchAll();
                        $status_device = "";
                        foreach ($user as $d) {

                            if ($d['device_status'] == 1) {
                                $status_device = "ยืม";
                            } else if ($d['device_status'] == 2) {
                                $status_device = "ว่าง";
                            } else if ($d['device_status'] == 3) {
                                $status_device = "ชำรุด";
                            } else if ($d['device_status'] == 4) {
                                $status_device = "ซ่อมแซม";
                            } else if ($d['device_status'] == 5) {
                                $status_device = "งดยืม";
                            }
                        ?>
                            <tr>
                                <th scope="row"><?= $d['device_name'] ?></th>
                                <th scope="row"><?= $d['number_device'] ?></th>
                                <th scope="row"><?= $d['device_enc'] ?></th>
                                <th scope="row"><?= $status_device ?></th>
                                <td> <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editDevice<?= $d['id'] ?>">
                                        แก้ไข
                                    </button></td>


                                <div class="modal fade" id="editDevice<?= $d['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">เพิ่มอุปกรณ์</h1>
                                            </div>
                                            <div class="modal-body">
                                                <form action="system/update.php" method="POST">
                                                    <div class="mb-3">
                                                        <h5 for="exampleInputEmail1" class="text-center">ชื่ออุปกรณ์</h5>
                                                        <input required type="text" value="<?= $d['device_name'] ?>" class="form-control" name="device_name">
                                                        <h5 for="exampleInputEmail1" class="text-center">หมายเลขครุภัณฑ์</h5>
                                                        <input required type="text" value="<?= $d['number_device'] ?>" class="form-control" name="number_device">
                                                        <h5 for="exampleInputEmail1" class="text-center">อุปกรณ์เพิ่มเติม</h5>
                                                        <input required type="text" value="<?= $d['device_enc'] ?>" class="form-control" name="device_enc">

                                                        <h5 for="exampleInputEmail1" class="text-center">สถานะอุปกรณ์</h5>

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

                                <td><a onclick="confirm('ต้องการลบข้อมูลใช่หรือไม่')" class="btn btn-danger" href="?device=<?= $d['id'] ?>">ลบ</a></td>
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

    <script>
        $(document).ready(function() {
            $('#borrow').DataTable();
            $('#borrow2').DataTable();
            $('#depart').DataTable();
            $('#admin').DataTable();
            $('#device').DataTable();
        });
    </script>
    f

    <script src="bootstrap/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>