<?php
$file = 'c:/laragon/www/prottoyacademy/prottoy_db.sql';
$content = file_get_contents($file);

// Remove CREATE DATABASE and USE statements which cause issues on Shared Hosting
$content = preg_replace('/CREATE DATABASE IF NOT EXISTS `.*?` .*?;/', '', $content);
$content = preg_replace('/USE `.*?`;/', '', $content);

// Ensure all collations are compatible
$content = str_replace('utf8mb4_0900_ai_ci', 'utf8mb4_unicode_ci', $content);
$content = str_replace('utf8mb4_general_ci', 'utf8mb4_unicode_ci', $content);

file_put_contents($file, $content);
echo "SQL file cleaned and optimized for Shared Hosting!";
?>
