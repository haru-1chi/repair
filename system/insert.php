<?php
session_start();
require_once '../config/db.php';

if (isset($_POST['insert'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM admin WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            if ($username == $user['username']) {
                $_SESSION["error"] = "ชื่อบัญชีซ้ำกับที่มีอยู่";
                header("location: ../admin.php");
            }
        } else if (!isset($_SESSION['error'])) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admin(username,password) VALUES(:username,:password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->execute();

            $_SESSION["success"] = "เพิ่มเจ้าหน้าสำเร็จ";
            header("location: ../admin.php");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if (isset($_POST['insertDepart'])) {
    $depart_name = $_POST['depart_name'];
    try {
        $sql = "SELECT * FROM department WHERE depart_name = :depart_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":depart_name", $depart_name);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            if ($depart_name == $user['depart_name']) {
                $_SESSION["error"] = "ชื่อหน่วยงานซ้ำ";
                header("location: ../admin.php");
            }
        } else if (!isset($_SESSION['error'])) {
            $sql = "INSERT INTO department(depart_name) VALUES(:depart_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":depart_name", $depart_name);
            $stmt->execute();

            $_SESSION["success"] = "เพิ่มหน่วยงานสำเร็จ";
            header("location: ../admin.php");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
if (isset($_POST['insertDevice'])) {
    $device_name = $_POST['device_name'];
    $number_device = $_POST['number_device'];
    $device_enc = $_POST['device_enc'];
    $device_status = $_POST['device_status'];
    try {
        $sql = "SELECT * FROM device WHERE number_device = :number_device";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":number_device", $number_device);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            if ($number_device == $user['number_device']) {
                $_SESSION["error"] = "หมายเลขอ้างอิงซ้ำ";
                header("location: ../admin.php");
            }
        } else if (!isset($_SESSION['error'])) {
            $sql = "INSERT INTO device(device_name,number_device,device_enc,device_status) VALUES(:device_name,:number_device,:device_enc,:device_status)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":device_name", $device_name);
            $stmt->bindParam(":number_device", $number_device);
            $stmt->bindParam(":device_enc", $device_enc);
            $stmt->bindParam(":device_status", $device_status);
            $stmt->execute();

            $_SESSION["success"] = "เพิ่มอุปกรณ์สำเร็จ";
            header("location: ../admin.php");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
