<?php
session_start();
require_once '../config/db.php';

if (isset($_POST['UpdateDevice'])) {
    $device_name = $_POST['device_name'];
    $number_device = $_POST['number_device'];
    $device_enc = $_POST['device_enc'];
    $device_status = $_POST['device_status'];
    $id = $_POST['id'];

    $sql = "UPDATE device
        SET device_name = :device_name, number_device = :number_device,device_enc = :device_enc,device_status = :device_status
        WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":device_name", $device_name);
    $stmt->bindParam(":number_device", $number_device);
    $stmt->bindParam(":device_enc", $device_enc);
    $stmt->bindParam(":device_status", $device_status);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
    header("location: ../admin.php");
}
if (isset($_POST['UpdateDepart'])) {
    $id = $_POST['id'];
    $depart_name = $_POST['depart_name'];
    $sql = "UPDATE department
    SET depart_name = :depart_name
    WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":depart_name", $depart_name);
    $stmt->execute();

    $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
    header("location: ../admin.php");
}
