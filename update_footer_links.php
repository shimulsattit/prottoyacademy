<?php
$conn = @new mysqli('127.0.0.1', 'prottoyacademy_main', 'YIPN[o,Lm2IP', 'prottoyacademy_main');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$links = [
    "/about-us",
    "/privacy-policy",
    "/terms-and-conditions",
    "/refund-policy",
    "/disclaimer"
];
$json_links = json_encode($links);

$sql = "UPDATE settings SET value = '$json_links' WHERE name = 'footer_menu_two_links'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
