<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';
 require_admin_main(); 

$video_id = $_GET['id'] ?? null;
if (!$video_id || !is_numeric($video_id)) {
    die('<div class="error">شناسه ویدیو نامعتبر است.</div>');
}

// گرفتن اطلاعات ویدیو
$stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->execute([$video_id]);
$video = $stmt->fetch();

if (!$video) {
    die('<div class="error">ویدیو یافت نشد.</div>');
}

// دریافت لیست دوره‌ها
$courses = $pdo->query("SELECT id, title FROM courses ORDER BY title")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $duration = intval($_POST['duration']);
    $filepath = trim($_POST['filepath']);
    $course_id = intval($_POST['course_id']);

    $stmt = $pdo->prepare("
        UPDATE videos
        SET title = ?, description = ?, duration = ?, filepath = ?, course_id = ?
        WHERE id = ?
    ");
    $stmt->execute([$title, $description, $duration, $filepath, $course_id, $video_id]);

    echo '<div class="success">✅ ویدیو با موفقیت به‌روزرسانی شد.</div>';
    echo '<a href="dashboard.php">بازگشت به پنل مدیریت</a>';
    require '../includes/footer.php';
    exit;
}
?>

<div class="container">
    <h2>✏️ ویرایش ویدیو</h2>

    <form method="post" style="max-width: 600px;">
        <label>عنوان ویدیو:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($video['title']) ?>" required>

        <label>توضیحات:</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($video['description']) ?></textarea>

        <label>مدت زمان (دقیقه):</label>
        <input type="number" name="duration" value="<?= $video['duration'] ?>" required>

        <label>نام فایل ویدیو:</label>
        <input type="text" name="filepath" value="<?= htmlspecialchars($video['filepath']) ?>" required>

        <label>دوره مربوطه:</label>
        <select name="course_id" required>
            <?php foreach ($courses as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $video['course_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <button type="submit" class="btn">💾 ذخیره تغییرات</button>
        <a href="dashboard.php" class="btn">↩️ بازگشت</a>
    </form>
</div>

<?php require '../includes/footer.php'; ?>
