<?php
$conn = @new mysqli('127.0.0.1', 'prottoyacademy_main', 'YIPN[o,Lm2IP', 'prottoyacademy_main');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, value FROM settings WHERE name IN ('footer_menu_one_links', 'footer_menu_two_links')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo $row["name"] . ": " . $row["value"] . "\n";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
