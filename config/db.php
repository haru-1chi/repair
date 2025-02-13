<?php
$servername = "172.16.190.6";
$username = "administratorsmhcc";
$password = "msh10723@maesot";

try {
  $conn = new PDO("mysql:host=$servername;dbname=repair", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //   echo "Connected successfully";
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
function encryptId($id)
{
  $encryptedId = base64_encode($id);
  return $encryptedId;
}

// ถอดรหัส ID
function decryptId($encryptedId)
{
  $decryptedId = base64_decode($encryptedId);
  return $decryptedId;
}
