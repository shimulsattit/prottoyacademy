<?php
$file = 'c:/laragon/www/prottoyacademy/prottoy_db.sql';
$content = file_get_contents($file);
$content = str_replace('utf8mb4_0900_ai_ci', 'utf8mb4_unicode_ci', $content);
$content = str_replace('utf8mb4_general_ci', 'utf8mb4_unicode_ci', $content);
file_put_contents($file, $content);
echo "Database file optimized for cPanel import successfully!";
?>
