<?php
require_once 'init.php';
$db = \colocation\Database::getInstance()->getConnection();
$stmt = $db->query("SELECT * FROM photo ORDER BY id_photo DESC LIMIT 5");
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "--- Latest 5 Photos in DB ---\n";
foreach ($photos as $p) {
    echo "ID: " . $p['id_photo'] . " | URL: " . $p['url'] . "\n";
}
