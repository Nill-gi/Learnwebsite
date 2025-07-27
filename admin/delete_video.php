<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';
require_admin_main();

$video_id = $_GET['id'] ?? null;
if (!$video_id || !is_numeric($video_id)) {
    die('<div class="error">شناسه ویدیو نامعتبر است.</div>');
}

$stmt = $pdo->prepare("SELECT title FROM videos WHERE id = ?");
$stmt->execute([$video_id]);
$video = $stmt->fetch();

if (!$video) {
    die('<div class="error">ویدیو پیدا نشد.</div>');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->execute([$video_id]);
    echo '<div class="success">✅ ویدیو با موفقیت حذف شد.</div>';
    echo '<a href="dashboard.php">بازگشت به پنل مدیریت</a>';
    require '../includes/footer.php';
    exit;
}
?>

<div class="container" style="max-width: 500px;">
    <h2>🗑️ حذف ویدیو</h2>
    <p>آیا مطمئن هستید که می‌خواهید ویدیو <strong><?= htmlspecialchars($video['title']) ?></strong> را حذف کنید؟</p>

    <form method="post">
        <button type="submit" class="btn" style="background-color: darkred; color: white;">بله، حذف شود</button>
        <a href="dashboard.php" class="btn">لغو</a>
    </form>
</div>

<?php require '../includes/footer.php'; ?>
