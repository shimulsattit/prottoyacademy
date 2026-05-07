<?php
$conn = @new mysqli('127.0.0.1', 'prottoyacademy_main', 'YIPN[o,Lm2IP', 'prottoyacademy_main');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$conn->close();
?>
