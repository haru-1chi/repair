<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['login'])) {
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
                if (password_verify($password, $user['password'])) {
                    $_SESSION['admin_log'] = $user["username"];
                    header("location: admin.php");
                }
            } else {
                $_SESSION['error'] = "ไม่พบข้อมูลในระบบ";
                header("location: login.php");
            }
        } else {
            $_SESSION['error'] = "ไม่พบข้อมูลในระบบ";
            header("location: login.php");
        }
    } catch (PDOException $e) {
        echo "ERROR TRY CATCH" . $e->getMessage();
    }
} else {
    echo "ERROR ISSET";
}
