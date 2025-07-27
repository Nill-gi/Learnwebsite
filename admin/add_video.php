<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';

require_admin_sub();

$errors = [];
$success = false;

// گرفتن لیست دوره‌های ایجادشده
$courses = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $duration = intval($_POST['duration'] ?? 0);
    $video = $_FILES['video'] ?? null;

    if (!$course_id || !$title || !$desc || !$duration || !$video) {
        $errors[] = 'همه فیلدها الزامی هستند.';
    }

    // بررسی و آپلود ویدیو
    if ($video && $video['error'] === 0) {
        $ext = pathinfo($video['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), ['mp4'])) {
            $errors[] = 'فقط فایل MP4 مجاز است.';
        } else {
            $filename = uniqid() . '.mp4';
            $path = '../uploads/videos/' . $filename;
            move_uploaded_file($video['tmp_name'], $path);
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO videos (course_id, title, description, filepath, duration) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$course_id, $title, $desc, $filename, $duration]);
        $success = true;
    }
}
?>

<h2>➕ افزودن ویدیو به دوره</h2>

<?php if ($success): ?>
    <p style="color: green;">✅ ویدیو با موفقیت اضافه شد.</p>
<?php endif; ?>

<?php if ($errors): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>انتخاب دوره:
        <select name="course_id" required>
            <option value="">-- انتخاب کنید --</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>عنوان ویدیو:
        <input type="text" name="title" required>
    </label><br><br>

    <label>توضیحات:
        <textarea name="description" required></textarea>
    </label><br><br>

    <label>مدت زمان (دقیقه):
        <input type="number" name="duration" min="1" required>
    </label><br><br>

    <label>فایل ویدیو (فقط mp4):
        <input type="file" name="video" accept="video/mp4" required>
    </label><br><br>

    <button type="submit">افزودن ویدیو</button>
</form>

<?php require '../includes/footer.php'; ?>
