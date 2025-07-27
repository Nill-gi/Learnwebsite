<?php
require '../includes/db.php';
require '../includes/auth.php';

require_admin_main(); // فقط مدیر اصلی

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die('شناسه نامعتبر است.');
}

// حذف دوره و تمام ویدیوهای مرتبط (به‌خاطر FOREIGN KEY CASCADE)
$stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_courses.php");
exit;
